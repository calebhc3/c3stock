<?php

namespace App\Http\Controllers;

use App\Models\LogMovimentacao;
use Illuminate\Support\Facades\Auth;
use App\Models\Team; // Se estiver usando Jetstream Teams

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

    return view('dashboard-admin', compact('teams'));
    }

}
