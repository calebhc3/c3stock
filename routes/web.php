<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InsumoController;
use App\Exports\PedidoInsumosExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return view('welcome');
});

// Grupo de rotas autenticadas
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    // Dashboard (já com dados carregados)
    Route::get('/dashboard', [InsumoController::class, 'dashboard'])->name('dashboard');

    // CRUD completo de Insumos

    Route::get('/insumos', [InsumoController::class, 'index'])->name('insumos.index');
    Route::get('/insumos/novo', [InsumoController::class, 'create'])->name('insumos.create');
    Route::post('/insumos', [InsumoController::class, 'store'])->name('insumos.store');
    Route::get('/insumos/{insumo}/editar', [InsumoController::class, 'edit'])->name('insumos.edit');
    Route::put('/insumos/{insumo}', [InsumoController::class, 'update'])->name('insumos.update');
    Route::delete('/insumos/{insumo}', [InsumoController::class, 'destroy'])->name('insumos.destroy');
    Route::patch('/insumos/{insumo}/quantidade-minima', [InsumoController::class, 'updateQuantidadeMinima'])->name('insumos.updateQuantidadeMinima');
    Route::patch('/insumos/{insumo}/quantidade-existente', [InsumoController::class, 'updateQuantidadeExistente'])->name('insumos.updateQuantidadeExistente');
    
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
});
