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
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    <?php echo e(__('📜 Solicitação de Insumos')); ?>

                </h2>
                <p class="text-sm text-gray-500 mt-1"><?php echo e(auth()->user()->currentTeam->name); ?></p>
            </div>
            <div class="text-sm text-gray-500">
                <span class="bg-gray-100 px-3 py-1 rounded-full">Atualizado: <?php echo e(now()->format('d/m/Y H:i')); ?></span>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 space-y-6">
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-transition
                     x-init="setTimeout(() => show = false, 5000)"
                     class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg shadow">
                    ✅ <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php
                $hoje = now();
                $dia = $hoje->day;
                $mes = $hoje->month;
                $ano = $hoje->year;
                $podePedir = ($dia == 25 && $mes == 6 && $ano == 2025) || ($dia == 26 && $mes == 6 && $ano == 2025);
                $ehAdmin = auth()->user()->isTeamAdmin();
            ?>

            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 rounded text-sm">
                <?php if($podePedir): ?>
                    ⚠️ <strong>ATENÇÃO:</strong> Pedidos liberados excepcionalmente hoje (25/06) e amanhã (26/06).
                <?php else: ?>
                    ⚠️ Os pedidos normalmente só podem ser realizados nos dias <strong>1</strong> e <strong>15</strong> de cada mês.
                <?php endif; ?>
                <br>🕒 O prazo para recebimento dos insumos é de até <strong>20</strong> dias.
            </div>

            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-md font-semibold text-gray-700 text-center mb-4">🛒 Pedido de Insumos</h3>

                <form method="POST" action="<?php echo e(route('pedidos.send')); ?>" class="space-y-3">
                    <?php echo csrf_field(); ?>
                    <div id="items-container" class="space-y-3 text-sm">
                        <div class="flex gap-2 items-center">
                            <select name="items[0][insumo_id]" class="w-full border rounded p-2 text-sm focus:ring-c3green" required>
                                <option value="">🔽 Selecione um produto</option>
                                <?php $__currentLoopData = $insumos->groupBy('tipo'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <optgroup label="<?php echo e(ucfirst($tipo)); ?>">
                                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $insumo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($insumo->id); ?>"><?php echo e($insumo->nome); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </optgroup>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <input type="number" name="items[0][quantidade]" min="1" class="w-20 border rounded p-2 text-center focus:ring-c3green" placeholder="Qtd" required>
                        </div>
                    </div>

                    <button type="button" id="add-item" class="w-full text-sm text-blue-600 border border-blue-500 rounded-md py-1 hover:bg-blue-50 transition flex items-center justify-center gap-1">
                        ➕ Adicionar item
                    </button>

                    <?php if($podePedir || $ehAdmin): ?>
                        <button type="submit" class="w-full text-sm bg-c3turquoise text-white rounded-md py-2 hover:bg-c3turquoise-dark transition">
                            📬 Enviar Pedido
                        </button>
                    <?php else: ?>
                        <button type="button" disabled class="w-full text-sm bg-gray-300 text-gray-600 rounded-md py-2 cursor-not-allowed">
                            🚫 Pedido indisponível
                        </button>
                    <?php endif; ?>
                </form>
            </div>

            <div class="text-sm text-gray-500 mt-8">
                <p>📅 Data do último pedido: <?php echo e($hoje->format('d/m/Y')); ?></p>
                <p>🕒 Prazo de entrega: até 20 dias úteis.</p>
            </div>

            <div class="mt-6">
                <h3 class="text-md font-semibold text-gray-700 text-center mb-4">📂 Histórico de Pedidos</h3>

                <?php if($historicoPedidos->count()): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-gray-800 border rounded shadow-sm">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="p-2 border-b">#</th>
                                    <th class="p-2 border-b">Data</th>
                                    <th class="p-2 border-b">Arquivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $historicoPedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="text-center hover:bg-gray-50">
                                        <td class="p-2 border-b"><?php echo e($index + 1); ?></td>
                                        <td class="p-2 border-b"><?php echo e($pedido->created_at->format('d/m/Y H:i')); ?></td>
                                        <td class="p-2 border-b">
                                            <a href="<?php echo e(asset('storage/' . $pedido->file_path)); ?>" class="text-blue-600 underline" target="_blank">
                                                📄 Baixar Excel
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-500">Nenhum pedido realizado ainda.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="item-template" class="hidden">
        <div class="flex gap-2 items-center">
            <select class="w-full border rounded p-2 text-sm focus:ring-c3green" required>
                <option value="">🔽 Selecione o produto</option>
                <?php $__currentLoopData = $insumos->groupBy('tipo'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <optgroup label="<?php echo e(ucfirst($tipo)); ?>">
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $insumo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($insumo->id); ?>"><?php echo e($insumo->nome); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </optgroup>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <input type="number" min="1" class="w-20 border rounded p-2 text-center text-sm focus:ring-c3green" placeholder="Qtd" required>
        </div>
    </div>

    <script>
        let itemIndex = 1;
        document.getElementById('add-item').addEventListener('click', function () {
            const container = document.getElementById('items-container');
            const template = document.getElementById('item-template').firstElementChild.cloneNode(true);

            template.querySelector('select').name = `items[${itemIndex}][insumo_id]`;
            template.querySelector('input').name = `items[${itemIndex}][quantidade]`;

            template.classList.add('opacity-0', 'animate-fade-in');
            container.appendChild(template);
            setTimeout(() => template.classList.remove('opacity-0'), 10);
            itemIndex++;
        });
    </script>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-3px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.2s ease-out;
        }
        optgroup {
            font-weight: bold;
            color: #4b5563;
        }
        optgroup option {
            font-weight: normal;
            color: #111827;
        }
    </style>
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
<?php /**PATH /var/www/resources/views/pedidos/create.blade.php ENDPATH**/ ?>