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
use App\Exports\PedidoInsumosExport;

class InsumoController extends Controller
{

    public function dashboard()
    {
        $team = auth()->user()->currentTeam;
    
        $insumos = $team->insumos()->withPivot(['quantidade_minima', 'quantidade_existente'])->get();
    
        $totalInsumos = $insumos->count();
    
        $itensCriticos = $insumos->filter(function ($insumo) {
            return $insumo->pivot->quantidade_existente < $insumo->pivot->quantidade_minima;
        })->count();
    
        $ultimaAtualizacao = LogMovimentacao::latest()->first()?->created_at?->format('d/m/Y H:i') ?? '—';
    
        $logMovimentacoes = LogMovimentacao::with(['user', 'insumo'])
            ->latest()
            ->take(10)
            ->get();
    
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
            'quantidadesExistentes'
        ));
    }
    
    
    public function index()
    {
        $user = auth()->user();
        $team = $user->currentTeam;
    
        $insumos = $team->insumos()
            ->withPivot(['quantidade_minima', 'quantidade_existente'])
            ->orderBy('nome')
            ->get();
    
        return view('insumos.index', compact('insumos'));
    }
    
    public function updateQuantidadeMinima(Request $request, Insumo $insumo)
    {
        $request->validate(['quantidade_minima' => 'required|numeric|min:0']);
    
        $team = auth()->user()->currentTeam;
    
        $pivot = $insumo->estoques()->where('team_id', $team->id)->first()?->pivot;
    
        if (!$pivot) {
            return back()->with('error', 'Insumo não está vinculado ao time atual.');
        }
    
        $quantidadeAnterior = $pivot->quantidade_minima;
    
        $insumo->estoques()->updateExistingPivot($team->id, [
            'quantidade_minima' => $request->quantidade_minima,
        ]);
    
        // Sem necessidade de $insumo->save(), pois estamos mexendo na pivot
    
        if ($quantidadeAnterior !== floatval($request->quantidade_minima)) {
            LogMovimentacao::create([
                'user_id' => auth()->id(),
                'insumo_id' => $insumo->id,
                'tipo_acao' => 'edicao',
                'quantidade' => abs($request->quantidade_minima - $quantidadeAnterior),
                'quantidade_final' => $request->quantidade_minima,
            ]);
        }
    
        return back()->with('success', 'Quantidade mínima atualizada com sucesso.');
    }
    
    public function updateQuantidadeExistente(Request $request, Insumo $insumo)
    {
        $request->validate(['quantidade_existente' => 'required|numeric|min:0']);
    
        $team = auth()->user()->currentTeam;
    
        $pivot = $insumo->estoques()->where('team_id', $team->id)->first()?->pivot;
    
        if (!$pivot) {
            return back()->with('error', 'Insumo não está vinculado ao time atual.');
        }
    
        $quantidadeAnterior = $pivot->quantidade_existente;
    
        $insumo->estoques()->updateExistingPivot($team->id, [
            'quantidade_existente' => $request->quantidade_existente,
        ]);
    
        if ($quantidadeAnterior !== floatval($request->quantidade_existente)) {
            LogMovimentacao::create([
                'user_id' => auth()->id(),
                'insumo_id' => $insumo->id,
                'tipo_acao' => $request->quantidade_existente > $quantidadeAnterior ? 'entrada' : 'edicao',
                'quantidade' => abs($request->quantidade_existente - $quantidadeAnterior),
                'quantidade_final' => $request->quantidade_existente,
            ]);
        }
    
        return back()->with('success', 'Quantidade existente atualizada com sucesso.');
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
    return view('pedidos.create', compact('insumos'));
}

public function sendPedido(Request $request)
{
    $data = $request->validate([
        'items' => 'required|array',
        'items.*.insumo_id' => 'required|exists:insumos,id',
        'items.*.quantidade' => 'required|numeric|min:1',
    ]);

    $pedido_id = rand(1000, 9999);
    $usuario = Auth::user();
    $equipe = $usuario->currentTeam ? $usuario->currentTeam->name : 'Sem equipe';

    $insumosSelecionados = collect($data['items'])->map(function ($item) {
        $insumo = \App\Models\Insumo::find($item['insumo_id']);
        return [
            'nome' => $insumo->nome,
            'quantidade' => $item['quantidade'],
        ];
    })->toArray();

    // Aqui passa os parâmetros novos
    $export = new PedidoInsumosExport($insumosSelecionados, $usuario->name, $equipe, $pedido_id);
    $excel = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);

    Mail::to('financeiro@c3saudeocupacional.com')->send(
        (new PedidoInsumos($insumosSelecionados, $pedido_id, $usuario->name, $equipe))
            ->attachData($excel, "pedido_{$pedido_id}.xlsx", [
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])
    );

    return redirect()->back()->with('success', 'Pedido enviado com sucesso! 📩 Excel anexado!');
}
    public function historicoMovimentacao()
    {
        $logs = LogMovimentacao::with('user', 'insumo')
            ->whereHas('insumo', function ($query) {
                $query->where('team_id', auth()->user()->currentTeam->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('insumos.historico', compact('logs'));
    }
        

    public function create()
    {
        return view('insumos.create');
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
