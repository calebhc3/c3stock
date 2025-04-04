<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\PedidoInsumos;
use App\Models\Insumo;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
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

        Insumo::create([
            'nome' => $request->nome,
            'tipo' => $request->tipo,
            'quantidade_minima' => $request->quantidade_minima,
            'unidade_medida' => $request->unidade_medida,
            'quantidade_existente' => $request->quantidade_existente,
            'necessario_comprar' => max(0, $request->quantidade_minima - $request->quantidade_existente),
            'team_id' => auth()->user()->currentTeam->id,
        ]);
    

        return redirect()->route('insumos.index')->with('success', 'Insumo criado com sucesso!');
    }

    public function edit(Insumo $insumo)
    {
        return view('insumos.edit', compact('insumo'));
    }

    public function update(Request $request, Insumo $insumo)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|string',
            'quantidade_minima' => 'required|integer|min:0',
            'unidade_medida' => 'required|string|max:50',
            'quantidade_existente' => 'required|integer|min:0',
        ]);

        $insumo->update($request->all());

        return redirect()->route('insumos.index')->with('success', 'Insumo atualizado com sucesso!');
    }

    public function destroy(Insumo $insumo)
    {
        $insumo->delete();

        return redirect()->route('insumos.index')->with('success', 'Insumo excluído com sucesso!');
    }

    public function updateQuantidadeMinima(Request $request, Insumo $insumo)
{
    $request->validate(['quantidade_minima' => 'required|numeric|min:0']);
    $insumo->update(['quantidade_minima' => $request->quantidade_minima]);
    return back()->with('success', 'Quantidade mínima atualizada.');
}

public function updateQuantidadeExistente(Request $request, Insumo $insumo)
{
    $request->validate(['quantidade_existente' => 'required|numeric|min:0']);
    $insumo->update(['quantidade_existente' => $request->quantidade_existente]);
    return back()->with('success', 'Quantidade existente atualizada.');
}
public function dashboard()
{
    return view('dashboard', [
        'totalInsumos' => \App\Models\Insumo::count(),
        'itensCriticos' => \App\Models\Insumo::whereColumn('quantidade_existente', '<', 'quantidade_minima')->count(),
        'ultimaAtualizacao' => \App\Models\Insumo::latest()->first()?->updated_at?->format('d/m/Y H:i'),
        'ultimosInsumos' => \App\Models\Insumo::latest()->take(5)->get(),
    ]);
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

    $pedido_id = rand(1000, 9999); // Gera um número único para o pedido

    $insumosSelecionados = collect($data['items'])->map(function ($item) {
        $insumo = Insumo::find($item['insumo_id']);
        return [
            'nome' => $insumo->nome,
            'quantidade' => $item['quantidade'],
        ];
    })->toArray(); // Transforma em array para evitar possíveis problemas de coleção

    // Enviar e-mail para o financeiro
    Mail::to('financeiro@c3ocupacional.com')->send(new PedidoInsumos($insumosSelecionados, $pedido_id));

    return redirect()->back()->with('success', 'Pedido enviado com sucesso! 📩');
}

}
