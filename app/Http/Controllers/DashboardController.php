<?php

namespace App\Http\Controllers;

use App\Models\LogMovimentacao;
use Illuminate\Support\Facades\Auth;
use App\Models\Team; // Se estiver usando Jetstream Teams
use App\Models\Pedido;

class DashboardController extends Controller
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
    
        $pedidos = Pedido::with('team', 'user')
            ->where('team_id', $team->id)
            ->latest()
            ->paginate(10);
            
        return view('dashboard', compact(
            'totalInsumos',
            'itensCriticos',
            'ultimaAtualizacao',
            'logMovimentacoes',
            'nomes',
            'quantidadesMinimas',
            'quantidadesExistentes',
            'insumos',
            'pedidos'
        ));
    }

    public function index()
    {
        $team = Auth::user()->currentTeam;

        $insumos = $team->insumos()->withPivot(['quantidade_minima', 'quantidade_existente'])->get();

        $totalInsumos = $insumos->count();

        $itensCriticos = $insumos->filter(fn($insumo) =>
            $insumo->pivot->quantidade_existente < $insumo->pivot->quantidade_minima
        )->count();

        $ultimaAtualizacao = LogMovimentacao::latest()->first()?->created_at?->format('d/m/Y H:i') ?? '—';

        $logMovimentacoes = LogMovimentacao::with(['user', 'insumo'])
            ->where('team_id', $team->id)
            ->latest()
            ->paginate(10);

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

    $pedidos = Pedido::with('team', 'user')->latest()->get();

    return view('dashboard-admin', compact('teams', 'pedidos'));
    }

public function confirmarEnvio(Pedido $pedido)
{
    if ($pedido->enviado_em) {
        return response()->json([
            'success' => false,
            'message' => 'Pedido já confirmado.'
        ]);
    }

    $pedido->update([
        'enviado_em' => now(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Pedido confirmado com sucesso.',
        'timestamp' => now()->format('d/m/Y H:i')
    ]);
    }

    public function confirmarRecebimento(Pedido $pedido)
    {
        $pedido->update([
            'recebido_em' => now(),
        ]);

        return redirect()->back()->with('success', 'Recebimento confirmado com sucesso!');
    }

}
