<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\PedidoInsumos;
use App\Models\Insumo;
use Illuminate\Http\Request;
use App\Models\Team; // Se estiver usando Jetstream Teams
use Illuminate\Support\Facades\Auth;
use App\Models\LogMovimentacao;

class InsumoController extends Controller
{

    public function dashboard()
    {
        return view('dashboard', [
            'totalInsumos' => \App\Models\Insumo::count(),
            'itensCriticos' => \App\Models\Insumo::whereColumn('quantidade_existente', '<', 'quantidade_minima')->count(),
            'ultimaAtualizacao' => \App\Models\Insumo::latest()->first()?->updated_at?->format('d/m/Y H:i'),
            'ultimosInsumos' => \App\Models\Insumo::latest()->take(5)->get(),
            'logMovimentacoes' => LogMovimentacao::with('user', 'insumo')->latest()->limit(10)->get()
        ]);
    }

    public function index()
    {
        $insumos = Insumo::where('team_id', auth()->user()->currentTeam->id)->get();
        return view('insumos.index', compact('insumos'));
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

    public function updateQuantidadeMinima(Request $request, Insumo $insumo)
    {
        $request->validate(['quantidade_minima' => 'required|numeric|min:0']);
    
        $quantidadeAnterior = $insumo->quantidade_minima;
        $insumo->update(['quantidade_minima' => $request->quantidade_minima]);
    
        // Registrar no log apenas se houver mudança
        if ($quantidadeAnterior !== $request->quantidade_minima) {
            LogMovimentacao::create([
                'user_id' => Auth::id(),
                'insumo_id' => $insumo->id,
                'tipo_acao' => 'edicao',
                'quantidade' => abs($request->quantidade_minima - $quantidadeAnterior),
                'quantidade_final' => $request->quantidade_minima,
            ]);
        }
    
        return back()->with('success', 'Quantidade mínima atualizada.');
    }
    
    public function updateQuantidadeExistente(Request $request, Insumo $insumo)
    {
        $request->validate(['quantidade_existente' => 'required|numeric|min:0']);
    
        $quantidadeAnterior = $insumo->quantidade_existente;
        $insumo->update(['quantidade_existente' => $request->quantidade_existente]);
    
        // Registrar no log apenas se houver mudança
        if ($quantidadeAnterior !== $request->quantidade_existente) {
            LogMovimentacao::create([
                'user_id' => Auth::id(),
                'insumo_id' => $insumo->id,
                'tipo_acao' => $request->quantidade_existente > $quantidadeAnterior ? 'entrada' : 'ajuste',
                'quantidade' => abs($request->quantidade_existente - $quantidadeAnterior),
                'quantidade_final' => $request->quantidade_existente,
            ]);
        }
    
        return back()->with('success', 'Quantidade existente atualizada.');
    }
    
    public function removerEstoque(Request $request, $id)
    {
        $insumo = Insumo::findOrFail($id);
        $quantidadeRemovida = $request->quantidade;

        if ($insumo->quantidade_existente < $quantidadeRemovida) {
            return back()->with('error', 'Quantidade insuficiente no estoque.');
        }

        $insumo->quantidade_existente -= $quantidadeRemovida;
        $insumo->save();

        // Registrar no log
        LogMovimentacao::create([
            'user_id' => Auth::id(),
            'insumo_id' => $insumo->id,
            'tipo_acao' => 'saida',
            'quantidade' => $quantidadeRemovida,
            'quantidade_final' => $insumo->quantidade_existente,
        ]);

        return back()->with('success', 'Saída registrada com sucesso.');
    }

    public function createPedido()
{
    $insumos = Insumo::all();
    return view('pedidos.create', compact('insumos'));
}

public function sendPedido(Request $request)
{
    $data = $request->validate([
        'items' => 'required|array',
        'items.*.insumo_id' => 'required|exists:insumos,id',
        'items.*.quantidade' => 'required|numeric|min:1',
    ]);

    $pedido_id = rand(1000, 9999); // Número do pedido
    $usuario = Auth::user(); // Obtém o usuário autenticado
    $equipe = $usuario->currentTeam ? $usuario->currentTeam->name : 'Sem equipe'; // Obtém a equipe vinculada ao usuário

    $insumosSelecionados = collect($data['items'])->map(function ($item) {
        $insumo = \App\Models\Insumo::find($item['insumo_id']);
        return [
            'nome' => $insumo->nome,
            'quantidade' => $item['quantidade'],
        ];
    })->toArray();

    // Enviar e-mail para o financeiro
    Mail::to('financeiro@c3saudeocupacional.com')->send(new PedidoInsumos($insumosSelecionados, $pedido_id, $usuario->name, $equipe));

    return redirect()->back()->with('success', 'Pedido enviado com sucesso! 📩');
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
}
