<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InsumoController;
use App\Exports\PedidoInsumosExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Sobrescreve o login padrão do Fortify
Route::post('/login', [CustomAuthenticatedSessionController::class, 'store'])
    ->name('login');

Route::get('/admin/dashboard', [DashboardController::class, 'dashboardAdmin'])
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard');


Route::match(['get', 'post'], 'register', function () {
    abort(404);
});
// Grupo de rotas autenticadas
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    // Dashboard (já com dados carregados)
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // CRUD completo de Insumos

    Route::get('/insumos', [InsumoController::class, 'index'])->name('insumos.index');
    Route::get('/insumos/novo', [InsumoController::class, 'create'])->name('insumos.create');
    Route::post('/insumos', [InsumoController::class, 'store'])->name('insumos.store');
    Route::get('/insumos/{insumo}/editar', [InsumoController::class, 'edit'])->name('insumos.edit');
    Route::put('/insumos/{insumo}', [InsumoController::class, 'update'])->name('insumos.update');
    Route::delete('/insumos/{insumo}', [InsumoController::class, 'destroy'])->name('insumos.destroy');
    Route::post('/insumos/{insumo}/alterar-quantidade', [InsumoController::class, 'alterarQuantidade'])->name('insumos.alterar.quantidade');
    Route::get('/insumos/editar-estoque', [InsumoController::class, 'editarEstoque'])
    ->name('insumos.editar-estoque');
    Route::post('/insumos/atualizar-estoque', [InsumoController::class, 'atualizarEstoqueEmLote'])
    ->name('insumos.atualizar-estoque');

    Route::get('/pedidos/create', [InsumoController::class, 'createPedido'])->name('pedidos.create');
    Route::post('/pedidos/send', [InsumoController::class, 'sendPedido'])->name('pedidos.send');
    Route::post('/pedidos/exportar', function (\Illuminate\Http\Request $request) {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.insumo_id' => 'required|exists:insumos,id',
            'items.*.quantidade' => 'required|numeric|min:1',
        ]);
    
        $insumosSelecionados = collect($data['items'])->map(function ($item) {
            $insumo = \App\Models\Insumo::find($item['insumo_id']);
            return [
                'nome' => $insumo->nome,
                'quantidade' => $item['quantidade'],
            ];
        });
    
        return Excel::download(new PedidoInsumosExport($insumosSelecionados), 'pedido-insumos.xlsx');
    })->name('pedidos.exportar');

    Route::get('/faq', [FaqController::class, 'index'])->name('faq');
    Route::post('/faq/contact', [ContactController::class, 'submit'])->name('contact.submit');

    // routes/web.php
    Route::post('/pedidos/{pedido}/confirmar-envio', [DashboardController::class, 'confirmarEnvio'])->name('pedidos.confirmar-envio');
Route::post('/pedidos/{pedido}/confirmar-recebimento', [DashboardController::class, 'confirmarRecebimentoAjax'])
    ->name('pedidos.ajax.confirmarRecebimento');

});
