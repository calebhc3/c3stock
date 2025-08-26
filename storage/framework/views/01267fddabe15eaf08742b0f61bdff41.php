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
        <h2 class="text-xl font-bold text-gray-800 text-center">📜 Solicitação de Insumos</h2>
     <?php $__env->endSlot(); ?>

    <?php if(session('success')): ?>
        <div id="success-message" class="mb-4 p-3 bg-green-100 border border-green-400 text-green-800 rounded-md text-sm text-center">
            ✅ <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="py-4 flex justify-center">
        <div class="max-w-7xl bg-white shadow-md rounded-lg p-4 border border-gray-300">
            <?php
            $teamName = auth()->user()->currentTeam->name;
$email = auth()->user()->email;

                $hoje = now();
                $dia = $hoje->day;
                $mes = $hoje->month;
                $ano = $hoje->year;

                $podePedir = ($teamName === 'SÃO BERNARDO DO CAMPO');
                $ehAdmin = auth()->user()->isTeamAdmin();
            ?>

            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 rounded text-sm text-center mb-4">
                <?php if($podePedir): ?>
                    ⚠️ <strong>ATENÇÃO:</strong> Pedidos liberados no último dia de cada mês.<br>
                <?php else: ?>
                    ⚠️ Os pedidos normalmente só podem ser realizados no<strong> último dia </strong>de cada mês.<br>
                <?php endif; ?>
                🕒 O prazo para recebimento dos insumos é de até <strong>20</strong> dias.
            </div>

            <h3 class="text-md font-semibold text-gray-700 text-center mb-4">🛒 Pedido de Insumos</h3>

            <form method="POST" action="<?php echo e(route('pedidos.send')); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>

                <div id="items-container" class="space-y-2 text-sm font-mono">
                    <!-- First item row -->
                    <div class="flex gap-2 items-center">
                        <select name="items[0][insumo_id]" class="w-full mt-3 border rounded p-1 focus:ring focus:ring-blue-300 text-sm" required>
                            <option value="">🔽 Selecione uma produto</option>
                            <?php $__currentLoopData = $insumos->groupBy('tipo'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <optgroup label="<?php echo e(ucfirst($tipo)); ?>">
                                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $insumo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($insumo->id); ?>"><?php echo e($insumo->nome); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </optgroup>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <input type="number" name="items[0][quantidade]" min="1" class="w-20 mt-3 border rounded p-1 text-center text-sm focus:ring focus:ring-blue-300" placeholder="Qtd" required>
                    </div>
                </div>

                <!-- Add Item Button -->
                <button type="button" id="add-item" class="w-full mt-5 text-xs px-3 py-1 border border-blue-500 text-blue-600 rounded-md hover:bg-blue-50 transition flex items-center justify-center gap-1">
                    ➕ Adicionar item
                </button>

                <!-- Submit Button -->
                    <button type="submit" class="w-full px-3 mt-10 py-1 text-white bg-c3turquoise text-sm rounded-md hover:bg-c3turquoise transition">
                        📬 Enviar Pedido
                    </button>

            </form>

            <div class="mt-10 text-sm text-gray-500">
                <p>📅 Data do último pedido: <?php echo e($hoje->format('d/m/Y')); ?></p>
                <p>🕒 Prazo de entrega: até 20 dias úteis.</p>
                
                <h3 class="text-md font-semibold text-gray-700 text-center mb-4 mt-4">📂 Histórico de Pedidos</h3>

                <?php if($historicoPedidos->count()): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full w-full bg-white text-sm border rounded shadow-sm">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
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
                                            <a href="<?php echo e(asset('storage/' . $pedido->file_path)); ?>" class="text-blue-500 underline" target="_blank">
                                                📄 Baixar Excel
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-500 text-sm">Nenhum pedido realizado ainda.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Hidden template for cloning via JS -->
    <div id="item-template" class="hidden">
        <div class="flex gap-2 items-center mt-2">
            <select class="w-full border rounded p-1 focus:ring focus:ring-blue-300 text-sm" required>
                <option value="">🔽 Selecione o produto</option>
                <?php $__currentLoopData = $insumos->groupBy('tipo'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <optgroup label="<?php echo e(ucfirst($tipo)); ?>">
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $insumo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($insumo->id); ?>"><?php echo e($insumo->nome); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </optgroup>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <input type="number" min="1" class="w-20 border rounded p-1 text-center text-sm focus:ring focus:ring-blue-300" placeholder="Qtd" required>
        </div>
    </div>

    <script>
        let itemIndex = 1;

        document.getElementById('add-item').addEventListener('click', function () {
            const container = document.getElementById('items-container');
            const template = document.getElementById('item-template').firstElementChild.cloneNode(true);

            // Update name attributes dynamically
            template.querySelector('select').name = `items[${itemIndex}][insumo_id]`;
            template.querySelector('input').name = `items[${itemIndex}][quantidade]`;

            // Add animation
            template.classList.add('opacity-0', 'animate-fade-in');
            container.appendChild(template);

            // Force transition to appear
            setTimeout(() => {
                template.classList.remove('opacity-0');
            }, 10);

            itemIndex++;
        });

        // Auto-hide success message after 5 seconds
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 5000);
        }
    </script>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-3px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.2s ease-out;
        }
        
        /* Style for optgroup labels */
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
<?php endif; ?><?php /**PATH /var/www/resources/views/pedidos/create.blade.php ENDPATH**/ ?>