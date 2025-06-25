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
                    <?php echo e(__('📦 Gerenciamento de Estoque')); ?>

                </h2>
                <p class="text-sm text-gray-500 mt-1"><?php echo e(auth()->user()->currentTeam->name); ?></p>
            </div>
            <div class="text-sm text-gray-500">
                <span class="bg-gray-100 px-3 py-1 rounded-full">Atualizado: <?php echo e(now()->format('d/m/Y H:i')); ?></span>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 space-y-6">
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-transition
                     x-init="setTimeout(() => show = false, 3000)"
                     class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg shadow">
                    ✅ <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <!-- Cards de Resumo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase">Total de Itens</h3>
                    <p class="text-2xl font-bold"><?php echo e($insumos->count()); ?></p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase">Itens Abaixo do Mínimo</h3>
                    <p class="text-2xl font-bold text-red-600"><?php echo e($belowMinimumCount); ?></p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase">Itens em Estoque</h3>
                    <p class="text-2xl font-bold"><?php echo e($inStockCount); ?></p>
                </div>
            </div>

            <!-- Controles -->
            <div class="flex flex-col md:flex-row justify-between gap-4 mb-4">
                <div class="flex items-center">
                    <input type="checkbox" id="togglePacotes" class="form-checkbox h-4 w-4 text-c3green" onchange="togglePacote()">
                    <label for="togglePacotes" class="ml-2 text-sm text-gray-600">Exibir por pacotes</label>
                </div>
                
                <div class="relative w-full md:w-64">
                    <input type="text" id="searchInput" placeholder="Pesquisar insumo..." 
                           class="w-full text-sm border-gray-300 rounded focus:border-c3green focus:ring-c3green px-3 py-2">
                </div>
            </div>

            <!-- Tabela de Insumos -->
            <div class="bg-white shadow-md rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="tabelaInsumos" class="w-full text-sm text-gray-800">
                        <thead class="bg-c3green text-gray-900 uppercase text-xs font-bold">
                            <tr>
                                <th class="px-6 py-3 text-left">Nome</th>
                                <th class="px-6 py-3 text-left">Mínimo</th>
                                <th class="px-6 py-3 text-left">Estoque</th>
                                <th class="px-6 py-3 text-left">Unidade</th>
                                <th class="px-6 py-3 text-left">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php $__currentLoopData = $insumos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $insumo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $minima = $insumo->pivot->quantidade_minima;
                                    $existente = $insumo->pivot->quantidade_existente;
                                    $faltante = max($minima - $existente, 0);
                                    $porPacote = $insumo->pivot->unidades_por_pacote ?? 1;
                                    $estoquePercent = min(100, ($existente / $minima) * 100);
                                    
                                    $dadosPacote = json_encode([
                                        'minima' => round($minima / $porPacote, 2),
                                        'existente' => round($existente / $porPacote, 2),
                                        'faltante' => round($faltante / $porPacote, 2)
                                    ]);
                                    
                                    $dadosUnidade = json_encode([
                                        'minima' => $minima,
                                        'existente' => $existente,
                                        'faltante' => $faltante
                                    ]);
                                ?>

                                <tr class="hover:bg-blue-50 transition"
                                    data-pacote='<?php echo e($dadosPacote); ?>'
                                    data-unidade='<?php echo e($dadosUnidade); ?>'
                                    data-nome="<?php echo e(strtolower($insumo->nome)); ?>">
                                    <td class="px-6 py-4 font-medium">
                                        <?php echo e($insumo->nome); ?>

                                        <?php if($insumo->categoria): ?>
                                            <span class="block text-xs text-gray-500 mt-1"><?php echo e(ucfirst($insumo->categoria)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="px-6 py-4 quantidade-minima"><?php echo e($minima); ?></td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-<?php echo e($existente < $minima ? 'red' : 'green'); ?>-500 h-2.5 rounded-full" 
                                                     style="width: <?php echo e($estoquePercent); ?>%"></div>
                                            </div>
                                            <span class="<?php echo e($existente < $minima ? 'text-red-600 font-bold' : 'text-gray-700'); ?> quantidade-existente">
                                                <?php echo e($existente); ?>

                                            </span>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-gray-500">
                                        <?php echo e($insumo->unidade_medida); ?>

                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <!-- Botão de Remover -->
                                            <form method="POST" action="<?php echo e(route('insumos.alterar.quantidade', $insumo)); ?>" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="acao" value="saida">
                                                <button type="submit" 
                                                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition text-sm flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                    Remover
                                                </button>
                                            </form>
                                            
                                            <!-- Botão de Adicionar -->
                                            <form method="POST" action="<?php echo e(route('insumos.alterar.quantidade', $insumo)); ?>" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="acao" value="entrada">
                                                <button type="submit" 
                                                        class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition text-sm flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    Adicionar
                                                </button>
                                            </form>
                                        </div>
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
        // Alternar entre visualização por pacotes/unidades
        function togglePacote() {
            const usarPacote = document.getElementById('togglePacotes').checked;
            const linhas = document.querySelectorAll('#tabelaInsumos tbody tr');

            linhas.forEach(linha => {
                try {
                    const dados = JSON.parse(linha.dataset[usarPacote ? 'pacote' : 'unidade']);
                    
                    linha.querySelector('.quantidade-minima').textContent = dados.minima;
                    linha.querySelector('.quantidade-existente').textContent = dados.existente;
                    
                    // Atualiza a barra de progresso
                    const percent = Math.min(100, (dados.existente / dados.minima) * 100);
                    const progressBar = linha.querySelector('.bg-gray-200 div');
                    progressBar.style.width = `${percent}%`;
                    progressBar.classList.toggle('bg-red-500', dados.existente < dados.minima);
                    progressBar.classList.toggle('bg-green-500', dados.existente >= dados.minima);
                    
                } catch (error) {
                    console.error('Erro ao processar dados:', error);
                }
            });
        }

        // Filtro de pesquisa
        document.getElementById('searchInput').addEventListener('input', function() {
            const termo = this.value.toLowerCase();
            const linhas = document.querySelectorAll('#tabelaInsumos tbody tr');
            
            linhas.forEach(linha => {
                const nome = linha.dataset.nome;
                linha.style.display = nome.includes(termo) ? '' : 'none';
            });
        });
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