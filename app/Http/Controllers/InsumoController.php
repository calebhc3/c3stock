<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\PedidoInsumos;
use App\Models\Insumo;
use Illuminate\Http\Request;
use App\Models\Team; // Se estiver usando Jetstream Teams
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
        $team = auth()->user()->currentTeam;

        if (!$team) {
            return redirect()->route('dashboard')->with('error', 'Você não está vinculado a nenhuma unidade.');
        }

        $insumos = $team->insumos()
            ->withPivot(['quantidade_minima', 'quantidade_existente', 'unidades_por_pacote'])
            ->orderBy('nome')
            ->get();
    
        return view('insumos.index', compact('insumos'));
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
    $insumos = auth()->user()->currentTeam->insumos;
    $historicoPedidos = Pedido::where('team_id', auth()->user()->currentTeam->id)
    ->latest()
    ->get();
    return view('pedidos.create', compact(
        'insumos',
        'historicoPedidos'
    ));
}

public function sendPedido(Request $request)
{
    $validated = $request->validate([
        'items' => 'required|array|min:1',
        'items.*.insumo_id' => 'required|exists:insumos,id',
        'items.*.quantidade' => 'required|numeric|min:0.1',
    ]);

    DB::beginTransaction();

    try {
        $usuario = Auth::user();
        
        if (!$usuario->currentTeam) {
            throw new \Exception('Usuário não está associado a nenhuma equipe.');
        }

        // Sanitiza os dados para UTF-8
        $nomeUsuario = mb_convert_encoding($usuario->name, 'UTF-8', 'UTF-8');
        $nomeEquipe = mb_convert_encoding($usuario->currentTeam->name, 'UTF-8', 'UTF-8');

        // Obtém os insumos em uma única consulta
        $insumosIds = collect($validated['items'])->pluck('insumo_id')->unique();
        $insumos = Insumo::whereIn('id', $insumosIds)->get()->keyBy('id');

        // Processa os itens com sanitização UTF-8
        $insumosSelecionados = collect($validated['items'])->map(function ($item) use ($insumos) {
            $insumo = $insumos[$item['insumo_id'] ?? null];
            
            if (!$insumo) {
                throw new \Exception("Insumo ID {$item['insumo_id']} não encontrado.");
            }

            return [
                'nome' => mb_convert_encoding($insumo->nome, 'UTF-8', 'UTF-8'),
                'quantidade' => $item['quantidade'],
                'unidade_medida' => mb_convert_encoding($insumo->unidade_medida ?? 'un', 'UTF-8', 'UTF-8'),
            ];
        })->toArray();

        // Cria o registro no banco com file_path temporário
        $pedido = Pedido::create([
            'team_id' => $usuario->currentTeam->id,
            'user_id' => $usuario->id,
            'status' => 'pendente',
            'file_path' => 'temp', // Valor temporário
        ]);

        // Gera o nome do arquivo com o ID
        $fileName = "pedidos/pedido_{$pedido->id}_" . now()->format('YmdHis') . ".xlsx";
        
        // Cria e salva o Excel
        $export = new PedidoInsumosExport(
            $insumosSelecionados, 
            $nomeUsuario, 
            $nomeEquipe,
            $pedido->id
        );

        $fileContent = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
        
        if (!Storage::disk('public')->put($fileName, $fileContent)) {
            throw new \Exception('Falha ao salvar o arquivo do pedido.');
        }

        // Atualiza com o caminho real do arquivo
        $pedido->update(['file_path' => $fileName]);

        // Envia o e-mail (versão síncrona para testes)
        Mail::to('administrativo@c3saude.com.br')
            ->cc(['matheus.martins@c3saude.com.br', 'lais.costa@c3saude.com.br'])
            ->send(
                (new PedidoInsumos(
                    $insumosSelecionados,
                    $pedido->id,
                    $nomeUsuario,
                    $nomeEquipe
                ))->attachData(
                    $fileContent,
                    "pedido_insumos_{$pedido->id}.xlsx",
                    ['mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
                )
            );

        DB::commit();

        return redirect()->back()
               ->with('success', 'Pedido enviado com sucesso! 📩 Excel salvo e anexado!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erro ao enviar pedido: ' . $e->getMessage(), [
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
               ->with('error', 'Erro ao processar pedido: ' . $e->getMessage());
    }
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
