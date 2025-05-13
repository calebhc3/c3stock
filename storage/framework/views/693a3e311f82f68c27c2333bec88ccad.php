<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="text-2xl font-bold text-gray-900">
            📦 Painel de Estoque - C3 Saúde Ocupacional
        </h2>
        <p class="text-sm text-gray-500 mt-1">Bem-vindo de volta, <?php echo e(Auth::user()->name); ?>! Aqui está um resumo rápido do seu estoque.</p>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white p-6 rounded-xl shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">📦 Histórico de Pedidos</h3>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-c3green text-xs uppercase text-gray-700">
                <tr>
                    <th class="px-4 py-3">Data do Pedido</th>
                    <th class="px-4 py-3">Prazo Máximo</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Ações</th>
                </tr>
            </thead>
<tbody>
    <?php $__empty_1 = true; $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $prazoMaximo = $pedido->created_at->addDays(20);
            $atrasado = now()->greaterThan($prazoMaximo) && is_null($pedido->recebido_em);
        ?>
        <tr id="pedido-row-<?php echo e($pedido->id); ?>" class="border-b hover:bg-gray-50 <?php echo e($atrasado ? 'bg-red-100' : ''); ?>">
            <td class="px-4 py-2"><?php echo e($pedido->created_at->format('d/m/Y')); ?></td>
            <td class="px-4 py-2"><?php echo e($prazoMaximo->format('d/m/Y')); ?></td>
            
            
            <td class="px-4 py-2" id="status-<?php echo e($pedido->id); ?>">
                <?php if($pedido->recebido_em): ?>
                    <span class="text-green-600 font-semibold">Recebido</span>
                <?php else: ?>
                    <span class="text-yellow-600 font-semibold">Pendente</span>
                <?php endif; ?>
            </td>

            
            <td class="px-4 py-2" id="acao-<?php echo e($pedido->id); ?>">
                <?php if(is_null($pedido->recebido_em)): ?>
                    <button type="button"
                        class="confirmar-btn px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                        data-id="<?php echo e($pedido->id); ?>">
                        Confirmar Recebimento
                    </button>
                <?php else: ?>
                    <span class="text-gray-500">
    Confirmado em <?php echo e(\Carbon\Carbon::parse($pedido->recebido_em)->format('d/m/Y')); ?>

                    </span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="4" class="px-4 py-4 text-center text-gray-500">Nenhum pedido encontrado.</td>
        </tr>
    <?php endif; ?>
</tbody>

        </table>
    </div>
</div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-c3green p-6 rounded-xl shadow text-center">
                    <div class="text-sm text-gray-500">Insumos Cadastrados</div>
                    <div class="mt-2 text-3xl font-bold text-gray-800"><?php echo e($totalInsumos ?? '—'); ?></div>
                </div>
                <div class="bg-c3green p-6 rounded-xl shadow text-center">
                    <div class="text-sm text-gray-500">Itens Abaixo do Mínimo</div>
                    <div class="mt-2 text-3xl font-bold text-red-600"><?php echo e($itensCriticos ?? '—'); ?></div>
                </div>
                <div class="bg-c3green p-6 rounded-xl shadow text-center">
                    <div class="text-sm text-gray-500">Última Atualização</div>
                    <div class="mt-2 text-lg font-medium text-gray-700"><?php echo e($ultimaAtualizacao ?? '—'); ?></div>
                </div>
            </div>

            
            <div class="bg-white p-6 rounded-xl shadow flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Ações rápidas</h3>
                    <p class="text-sm text-gray-500">Gerencie os insumos da unidade com agilidade.</p>
                </div>
                <div>
                    <a href="<?php echo e(route('insumos.editar-estoque')); ?>"
                       class="inline-flex items-center px-4 py-2 bg-c3turquoise border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-blue-700 transition">
                        Editar estoque
                    </a>
                </div>
            </div>
            
<div class="bg-white p-6 rounded-xl shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Log de Movimentações</h3>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-c3green text-xs uppercase text-gray-700">
                <tr>
                    <th class="px-4 py-3">Data</th>
                    <th class="px-4 py-3">Usuário</th>
                    <th class="px-4 py-3">Ação</th>
                    <th class="px-4 py-3">Insumo</th>
                    <th class="px-4 py-3">Qtd.</th>
                    <th class="px-4 py-3">Qtd. Final</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $logMovimentacoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2"><?php echo e($log->created_at->format('d/m/Y H:i')); ?></td>
                        <td class="px-4 py-2"><?php echo e($log->user->name); ?></td>
                        <td class="px-4 py-2 capitalize">
                            <?php if($log->tipo_acao === 'entrada'): ?>
                                <span class="text-green-600 font-semibold">Entrada</span>
                            <?php elseif($log->tipo_acao === 'saida'): ?>
                                <span class="text-red-600 font-semibold">Saída</span>
                            <?php else: ?>
                                <span class="text-yellow-600 font-semibold">Edição</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-2"><?php echo e($log->insumo->nome); ?></td>
                        <td class="px-4 py-2"><?php echo e($log->quantidade); ?></td>
                        <td class="px-4 py-2"><?php echo e($log->quantidade_final); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">Nenhuma movimentação registrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="mt-6 pt-4 border-t border-gray-200 flex justify-center">
    <?php echo e($logMovimentacoes->links()); ?>

</div>

    </div>
</div>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /var/www/resources/views/dashboard.blade.php ENDPATH**/ ?>