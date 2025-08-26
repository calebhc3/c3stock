<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\PedidoInsumos;
use App\Models\Insumo;
use Illuminate\Http\Request;
use App\Models\Team; // Se estiver usando Jetstream Teams
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\LogMovimentacao;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Models\Pedido;
use App\Exports\PedidoInsumosExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InsumoController extends Controller
{

    public function dashboard()
    {
        $user = auth()->user();
    
        if (!$user || !$user->currentTeam) {
            abort(403, 'Você não está associado a nenhuma equipe.');
        }
    
        $team = $user->currentTeam;
    
        $insumos = $team->insumos()
            ->withPivot(['quantidade_minima', 'quantidade_existente'])
            ->get();
    
        $totalInsumos = $insumos->count();
    
        $itensCriticos = $insumos->filter(function ($insumo) {
            return $insumo->pivot->quantidade_existente < $insumo->pivot->quantidade_minima;
        })->count();
    
        $ultimaAtualizacao = LogMovimentacao::latest()->first()?->created_at?->format('d/m/Y H:i') ?? '—';
    
        $logMovimentacoes = LogMovimentacao::with(['user', 'insumo'])
            ->where('team_id', $team->id)
            ->latest()
            ->paginate(10);
    
        // Dados para o gráfico
        $nomes = $insumos->pluck('nome');
        $quantidadesMinimas = $insumos->pluck('pivot.quantidade_minima');
        $quantidadesExistentes = $insumos->pluck('pivot.quantidade_existente');
    
        return view('dashboard', compact(
            'totalInsumos',
            'itensCriticos',
            'ultimaAtualizacao',
            'logMovimentacoes',
            'nomes',
            'quantidadesMinimas',
            'quantidadesExistentes',
            'insumos'
        ));
    }
    public function dashboardAdmin()
{
    $user = auth()->user();

    if ($user->email !== 'owner@c3stock.com') {
        abort(403, 'Acesso negado.');
    }

    // Buscar todos os times com seus insumos e dados da pivot
    $teams = Team::with(['insumos' => function ($query) {
        $query->withPivot(['quantidade_minima', 'quantidade_existente']);
    }])->get();

    return view('dashboard-admin', compact('teams'));
}

    public function editarEstoque()
    {
        $team = auth()->user()->currentTeam;
        $insumos = $team->insumos()->withPivot(['quantidade_existente', 'quantidade_minima'])->get();
        
        return view('insumos.editar-estoque', compact('insumos'));
    }
    
    public function atualizarEstoqueEmLote(Request $request)
    {
        $team = auth()->user()->currentTeam;
        
        foreach ($request->estoque as $insumoId => $quantidade) {
            $team->insumos()->updateExistingPivot($insumoId, [
                'quantidade_existente' => $quantidade
            ]);
            
            // Registrar no log se necessário
        }
        
        return redirect()->route('dashboard')->with('success', 'Estoque atualizado com sucesso!');
    }
    
    public function index()
    {
        $user = auth()->user();
        $team = $user->currentTeam;

        if (!$team) {
            return redirect()->route('dashboard')->with('error', 'Você não está vinculado a nenhuma unidade.');
        }

        $insumos = $team->insumos()
            ->withPivot(['quantidade_minima', 'quantidade_existente', 'unidades_por_pacote'])
            ->orderBy('nome')
            ->get();

        $belowMinimumCount = $insumos->filter(function ($insumo) {
            return $insumo->pivot->quantidade_existente < $insumo->pivot->quantidade_minima;
        })->count();

        $inStockCount = $insumos->filter(function ($insumo) {
            return $insumo->pivot->quantidade_existente > 0;
        })->count();

        $categories = $insumos->pluck('tipo')->unique();

        return view('insumos.index', compact(
            'insumos',
            'belowMinimumCount',
            'inStockCount',
            'categories'
        ));
    }


    public function alterarQuantidade(Request $request, Insumo $insumo)
    {
        $request->validate([
            'acao' => 'required|in:entrada,saida',
        ]);
    
        $team = auth()->user()->currentTeam;
    
        $pivot = $insumo->estoques()->where('team_id', $team->id)->first()?->pivot;
    
        if (!$pivot) {
            return back()->with('error', 'Insumo não está vinculado ao time atual.');
        }
    
        $quantidadeAnterior = $pivot->quantidade_existente;
        $quantidadeAlterada = $request->acao === 'entrada' ? 1 : -1;
    
        $novaQuantidade = max(0, $quantidadeAnterior + $quantidadeAlterada);
    
        $insumo->estoques()->updateExistingPivot($team->id, [
            'quantidade_existente' => $novaQuantidade,
        ]);

        LogMovimentacao::create([
            'user_id' => auth()->id(),
            'insumo_id' => $insumo->id,
            'team_id' => auth()->user()->currentTeam->id,
            'tipo_acao' => $request->acao,
            'quantidade' => abs($quantidadeAlterada),
            'quantidade_final' => $novaQuantidade,
        ]);
    

        return redirect()->back()->with('success', 'Quantidade atualizada com sucesso!');

    }
    
    
    public function removerEstoque(Request $request, $id)
    {
        $insumo = Insumo::findOrFail($id);
        $quantidadeRemovida = $request->quantidade;
        $team = auth()->user()->currentTeam;
    
        $pivot = $insumo->estoques()->where('team_id', $team->id)->first()?->pivot;
    
        if (!$pivot) {
            return back()->with('error', 'Insumo não está vinculado ao time atual.');
        }
    
        if ($pivot->quantidade_existente < $quantidadeRemovida) {
            return back()->with('error', 'Quantidade insuficiente no estoque.');
        }
    
        $novaQuantidade = $pivot->quantidade_existente - $quantidadeRemovida;
    
        $insumo->estoques()->updateExistingPivot($team->id, [
            'quantidade_existente' => $novaQuantidade,
        ]);
    
        LogMovimentacao::create([
            'user_id' => auth()->id(),
            'insumo_id' => $insumo->id,
            'tipo_acao' => 'saida',
            'quantidade' => $quantidadeRemovida,
            'quantidade_final' => $novaQuantidade,
        ]);
    
        return back()->with('success', 'Saída registrada com sucesso.');
    }
        
    public function createPedido()
    {
        $user = auth()->user();
        
        if (!$user->currentTeam) {
            return redirect()->back()->with('error', 'Usuário não está associado a nenhuma equipe.');
        }

        $insumos = $user->currentTeam->insumos()
            ->with(['team' => function($query) {
                $query->select('id', 'name');
            }])
            ->get();

        $historicoPedidos = Pedido::where('user_id', $user->id)
            ->latest()
            ->limit(10) // Limita o histórico para melhor performance
            ->get(['id', 'created_at', 'file_path', 'status']);

        return view('pedidos.create', compact('insumos', 'historicoPedidos'));
    }

public function sendPedido(Request $request)
{
    $validated = $this->validatePedidoRequest($request);
    $user = Auth::user();
    
    if (!$user->currentTeam) {
        return redirect()->back()->with('error', 'Usuário não está associado a nenhuma equipe.');
    }

    return DB::transaction(function () use ($validated, $user) {
        try {
            $pedido = $this->createPedidoRecord($user);
            $insumosData = $this->processSelectedItems($validated['items']);
            $fileName = $this->generateFileName($pedido->id);
            
            $this->generateAndSaveExcel($insumosData, $user, $pedido, $fileName);
            $this->sendNotificationEmail($insumosData, $pedido, $user, $fileName);
            
            return redirect()->back()
                ->with('success', 'Pedido enviado com sucesso! 📩 Excel salvo e anexado!');

        } catch (\Exception $e) {
            Log::error('Erro ao enviar pedido: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'team_id' => $user->currentTeam->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Erro ao processar pedido: ' . $e->getMessage());
        }
    });
}

protected function validatePedidoRequest(Request $request): array
{
    return $request->validate([
        'items' => 'required|array|min:1',
        'items.*.insumo_id' => [
            'required',
            'exists:insumos,id',
            function ($attribute, $value, $fail) {
                $teamInsumos = auth()->user()->currentTeam->insumos()
                    ->pluck('insumos.id');
                
                if (!$teamInsumos->contains($value)) {
                    $fail('O insumo selecionado não está disponível para sua equipe.');
                }
            }
        ],
        'items.*.quantidade' => 'required|numeric|min:0.1|max:1000',
    ]);
}

    /**
     * Create the initial pedido record
     */
    protected function createPedidoRecord(User $user): Pedido
    {
        return Pedido::create([
            'team_id' => $user->currentTeam->id,
            'user_id' => $user->id,
            'status' => 'pendente',
            'file_path' => 'temp', // Valor temporário
        ]);
    }

    /**
     * Process and validate selected items
     */
    protected function processSelectedItems(array $items): array
    {
        $insumosIds = collect($items)->pluck('insumo_id')->unique();
        $insumos = Insumo::whereIn('id', $insumosIds)
            ->get(['id', 'nome', 'unidade_medida'])
            ->keyBy('id');

        return collect($items)->map(function ($item) use ($insumos) {
            if (!isset($insumos[$item['insumo_id']])) {
                throw new \Exception("Insumo ID {$item['insumo_id']} não encontrado.");
            }

            $insumo = $insumos[$item['insumo_id']];
            
            return [
                'nome' => $this->sanitizeUtf8($insumo->nome),
                'quantidade' => (float) $item['quantidade'],
                'unidade_medida' => $this->sanitizeUtf8($insumo->unidade_medida ?? 'un'),
            ];
        })->toArray();
    }

    /**
     * Generate Excel file and save to storage
     */
    protected function generateAndSaveExcel(
        array $insumosData, 
        User $user, 
        Pedido $pedido,
        string $fileName
    ): void {
        $export = new PedidoInsumosExport(
            $insumosData,
            $this->sanitizeUtf8($user->name),
            $this->sanitizeUtf8($user->currentTeam->name),
            $pedido->id
        );

        $fileContent = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
        
        if (!Storage::disk('public')->put($fileName, $fileContent)) {
            throw new \Exception('Falha ao salvar o arquivo do pedido.');
        }

        $pedido->update(['file_path' => $fileName]);
    }

    /**
     * Send notification email with attachment
     */
    protected function sendNotificationEmail(
        array $insumosData,
        Pedido $pedido,
        User $user,
        string $fileName
    ): void {
        $email = new PedidoInsumos(
            $insumosData,
            $pedido->id,
            $this->sanitizeUtf8($user->name),
            $this->sanitizeUtf8($user->currentTeam->name)
        );

        $filePath = Storage::disk('public')->path($fileName);
        
        Mail::to('administrativo@c3saude.com.br')
            ->cc(['matheus.martins@c3saude.com.br', 'lais.costa@c3saude.com.br'])
            ->send($email->attach($filePath, [
                'as' => "pedido_insumos_{$pedido->id}.xlsx",
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]));
    }

    /**
     * Generate consistent filename for the order
     */
    protected function generateFileName(int $pedidoId): string
    {
        return "pedidos/pedido_{$pedidoId}_" . now()->format('Ymd_His') . ".xlsx";
    }

    /**
     * Sanitize UTF-8 strings
     */
    protected function sanitizeUtf8(string $input): string
    {
        return mb_convert_encoding($input, 'UTF-8', 'UTF-8');
    }

    public function historicoMovimentacao()
    {
        $logs = LogMovimentacao::with('user', 'insumo', 'team')
            ->where('team_id', auth()->user()->current_team_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('insumos.historico', compact('logs'));
    }      

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|string',
            'quantidade_minima' => 'required|integer|min:0',
            'unidade_medida' => 'required|string|max:50',
            'quantidade_existente' => 'required|integer|min:0',
        ]);

        $insumo = Insumo::create([
            'nome' => $request->nome,
            'tipo' => $request->tipo,
            'quantidade_minima' => $request->quantidade_minima,
            'unidade_medida' => $request->unidade_medida,
            'quantidade_existente' => $request->quantidade_existente,
            'necessario_comprar' => max(0, $request->quantidade_minima - $request->quantidade_existente),
            'team_id' => auth()->user()->currentTeam->id,
        ]);

        // Registrar no log
        LogMovimentacao::create([
            'user_id' => Auth::id(),
            'insumo_id' => $insumo->id,
            'tipo_acao' => 'entrada',
            'quantidade' => $request->quantidade_existente,
            'quantidade_final' => $insumo->quantidade_existente,
        ]);

        return redirect()->route('insumos.index')->with('success', 'Insumo criado com sucesso!');
    }

}
