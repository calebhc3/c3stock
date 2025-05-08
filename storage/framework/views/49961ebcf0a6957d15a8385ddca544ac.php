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
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            <?php echo e(__('📦 Gerenciamento de Insumos')); ?>

        </h2>
        <p class="text-sm text-gray-500 mt-1">Visualize e controle os insumos cadastrados por unidade.</p>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 space-y-6">

            <?php if(session('success')): ?>
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg shadow">
                    ✅ <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <div class="flex justify-end">
                <label class="flex items-center text-sm text-gray-600 space-x-2">
                    <input type="checkbox" id="togglePacotes" class="form-checkbox h-4 w-4 text-c3green" onchange="togglePacote()">
                    <span class="ml-2">Exibir por pacotes</span>
                </label>
            </div>

            <div class="bg-white shadow-md rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="tabelaInsumos" class="w-full text-sm text-gray-800">
                        <thead class="bg-c3green text-gray-900 uppercase text-xs font-bold border-b">
                            <tr>
                                <th class="px-6 py-4 text-left">Nome</th>
                                <th class="px-6 py-4 text-left">Qtd. Mínima</th>
                                <th class="px-6 py-4 text-left">Qtd. Indicada</th>
                                <th class="px-6 py-4 text-left">Qtd. Existente</th>
                                <th class="px-6 py-4 text-left">Unidade</th>
                                <th class="px-6 py-4 text-left">Faltantes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
        <?php $__currentLoopData = $insumos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $insumo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $minima = $insumo->pivot->quantidade_minima;
                $existente = $insumo->pivot->quantidade_existente;
                $indicada = ceil($minima * 1.2);
                $faltante = $insumo->necessario_comprar;
                $porPacote = $insumo->pivot->unidades_por_pacote ?? 1;
                
                // Prepara os dados para JSON
                $dadosPacote = json_encode([
                    'minima' => round($minima / $porPacote),
                    'indicada' => round($indicada / $porPacote),
                    'existente' => round($existente / $porPacote),
                    'faltante' => round($faltante / $porPacote)
                ]);
                
                $dadosUnidade = json_encode([
                    'minima' => $minima,
                    'indicada' => $indicada,
                    'existente' => $existente,
                    'faltante' => $faltante
                ]);
            ?>

            <tr class="<?php echo e($loop->even ? 'bg-gray-50' : 'bg-white'); ?> hover:bg-blue-50 transition"
                data-pacote='<?php echo e($dadosPacote); ?>'
                data-unidade='<?php echo e($dadosUnidade); ?>'>
                <td class="px-6 py-4"><?php echo e($insumo->nome); ?></td>
                                    <td class="px-6 py-4 quantidade-minima"><?php echo e($minima); ?></td>
                                    <td class="px-6 py-4 quantidade-indicada"><?php echo e($indicada); ?></td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="<?php echo e(route('insumos.alterar.quantidade', $insumo)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="acao" value="saida">
                                                <button type="submit" class="bg-red-500 text-white w-6 h-6 rounded-full hover:bg-red-600 text-xs font-bold flex items-center justify-center">−</button>
                                            </form>

                                            <span class="w-8 text-center font-semibold quantidade-existente">
                                                <?php echo e($existente); ?>

                                            </span>

                                            <form method="POST" action="<?php echo e(route('insumos.alterar.quantidade', $insumo)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="acao" value="entrada">
                                                <button type="submit" class="bg-c3turquoise text-white w-6 h-6 rounded-full hover:bg-green-600 text-xs font-bold flex items-center justify-center">+</button>
                                            </form>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4"><?php echo e($insumo->unidade_medida); ?></td>

                                    <td class="px-6 py-4 font-semibold quantidade-faltante <?php echo e($faltante > 0 ? 'text-red-600' : 'text-green-600'); ?>">
                                        <?php echo e($faltante); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePacote() {
            const usarPacote = document.getElementById('togglePacotes').checked;
            const linhas = document.querySelectorAll('#tabelaInsumos tbody tr');

            linhas.forEach(linha => {
                try {
                    const dados = JSON.parse(linha.dataset[usarPacote ? 'pacote' : 'unidade']);
                    
                    linha.querySelector('.quantidade-minima').textContent = dados.minima;
                    linha.querySelector('.quantidade-indicada').textContent = dados.indicada;
                    linha.querySelector('.quantidade-existente').textContent = dados.existente;
                    linha.querySelector('.quantidade-faltante').textContent = dados.faltante;
                    
                } catch (error) {
                    console.error('Erro ao processar dados:', error);
                    console.log('Dados problemáticos:', linha.dataset[usarPacote ? 'pacote' : 'unidade']);
                }
            });
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /var/www/resources/views/insumos/index.blade.php ENDPATH**/ ?>