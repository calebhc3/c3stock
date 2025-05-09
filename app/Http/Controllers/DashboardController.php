<?php

namespace App\Http\Controllers;

use App\Models\LogMovimentacao;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
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
}
