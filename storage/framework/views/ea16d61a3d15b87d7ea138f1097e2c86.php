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
            🧠 Painel Geral de Estoque - Admin Master
        </h2>
        <p class="text-sm text-gray-500 mt-1">Visão consolidada dos estoques com filtro por unidade e exibição por pacotes.</p>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <?php if($pedidos->count() > 0): ?>
                <div class="bg-white p-6 rounded shadow mb-6">
                    <?php if(session('success')): ?>
                        <div id="success-message" class="mb-4 p-3 bg-green-100 border border-green-400 text-green-800 rounded-md text-sm text-center">
                            ✅ <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <h3 class="text-lg font-bold mb-4">📦 Pedidos Pendentes de Envio</h3>

                    <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Unidade</th>
                                <th class="px-4 py-2 text-left">Usuário</th>
                                <th class="px-4 py-2 text-left">Data do Pedido</th>
                                <th class="px-4 py-2 text-left">Arquivo</th>
                                <th class="px-4 py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr id="pedido-<?php echo e($pedido->id); ?>" class="border-b">
                                    <td class="px-4 py-2"><?php echo e($pedido->team->name); ?></td>
                                    <td class="px-4 py-2"><?php echo e($pedido->user->name); ?></td>
                                    <td class="px-4 py-2"><?php echo e($pedido->created_at->format('d/m/Y H:i')); ?></td>
                                    <td class="px-4 py-2">
                                        <a href="<?php echo e(Storage::url($pedido->arquivo)); ?>" target="_blank" class="text-blue-500 underline">Ver Arquivo</a>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="status-envio">
                                            <?php if($pedido->enviado_em): ?>
                                                ✅ Enviado em <?php echo e($pedido->enviado_em->format('d/m/Y H:i')); ?>

                                            <?php else: ?>
                                                <button onclick="confirmarEnvio(<?php echo e($pedido->id); ?>)"
                                                    class="confirmar-btn px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                                    Confirmar Envio
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="flex justify-between items-center mb-4">
                <label class="block text-sm font-medium text-gray-700">
                    Filtrar por unidade:
                    <select id="filtroUnidade" class="mt-1 block w-64 rounded-md border-gray-300 shadow-sm focus:border-c3green focus:ring focus:ring-c3green focus:ring-opacity-50">
                        <option value="todas">Todas</option>
                        <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($team->id); ?>"><?php echo e($team->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </label>

                <label class="flex items-center text-sm text-gray-600 space-x-2">
                    <input type="checkbox" id="togglePacotes" class="form-checkbox h-4 w-4 text-c3green" onchange="togglePacote()">
                    <span class="ml-2">Exibir por pacotes</span>
                </label>
            </div>

            <div class="overflow-x-auto">
                <table id="tabelaInsumos" class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-c3green text-xs uppercase text-gray-700">
                        <tr>
                            <th class="px-4 py-3">Unidade</th>
                            <th class="px-4 py-3">Nome</th>
                            <th class="px-4 py-3">Qtd. Existente</th>
                            <th class="px-4 py-3">Qtd. Mínima</th>
                            <th class="px-4 py-3">Qtd. Indicada</th>
                            <th class="px-4 py-3">Faltante</th>
                            <th class="px-4 py-3">Situação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $team->insumos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $insumo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $minima = $insumo->pivot->quantidade_minima;
                                    $existente = $insumo->pivot->quantidade_existente;
                                    $indicada = ceil($minima * 1.2);
                                    $faltante = $insumo->necessario_comprar;
                                    $porPacote = $insumo->pivot->unidades_por_pacote ?? 1;

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
                                <tr class="border-b hover:bg-gray-50" data-team="<?php echo e($team->id); ?>" data-unidade="<?php echo e($team->name); ?>" data-pacote='<?php echo e($dadosPacote); ?>' data-unidade-info='<?php echo e($dadosUnidade); ?>'>
                                    <td class="px-4 py-2"><?php echo e($team->name); ?></td>
                                    <td class="px-4 py-2"><?php echo e($insumo->nome); ?></td>
                                    <td class="px-4 py-2 quantidade-existente"><?php echo e($existente); ?></td>
                                    <td class="px-4 py-2 quantidade-minima"><?php echo e($minima); ?></td>
                                    <td class="px-4 py-2 quantidade-indicada"><?php echo e($indicada); ?></td>
                                    <td class="px-4 py-2 quantidade-faltante"><?php echo e($faltante); ?></td>
                                    <td class="px-4 py-2">
                                        <?php if($existente < $minima): ?>
                                            <span class="text-red-600 font-semibold">Crítico</span>
                                        <?php else: ?>
                                            <span class="text-green-600 font-semibold">Ok</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function confirmarEnvio(pedidoId) {
            if (!confirm("Tem certeza que deseja confirmar o envio deste pedido?")) return;

            fetch(`/pedidos/${pedidoId}/confirmar-envio`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById(`pedido-${pedidoId}`);
                    row.querySelector('.confirmar-btn').remove();
                    row.querySelector('.status-envio').textContent = `✅ Enviado em ${data.timestamp}`;
                } else {
                    alert(data.message || "Erro ao confirmar envio.");
                }
            }).catch(error => {
                alert("Erro na requisição.");
                console.error(error);
            });
        }

        function togglePacote() {
            const usarPacote = document.getElementById('togglePacotes').checked;
            const linhas = document.querySelectorAll('#tabelaInsumos tbody tr');

            linhas.forEach(linha => {
                const dados = JSON.parse(linha.dataset[usarPacote ? 'pacote' : 'unidadeInfo']);
                linha.querySelector('.quantidade-minima').textContent = dados.minima;
                linha.querySelector('.quantidade-indicada').textContent = dados.indicada;
                linha.querySelector('.quantidade-existente').textContent = dados.existente;
                linha.querySelector('.quantidade-faltante').textContent = dados.faltante;
            });
        }

        document.getElementById('filtroUnidade').addEventListener('change', function () {
            const unidadeSelecionada = this.value;
            const linhas = document.querySelectorAll('#tabelaInsumos tbody tr');

            linhas.forEach(linha => {
                const idUnidade = linha.dataset.team;
                linha.style.display = (unidadeSelecionada === 'todas' || unidadeSelecionada === idUnidade) ? '' : 'none';
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
<?php endif; ?>
<?php /**PATH /var/www/resources/views/dashboard-admin.blade.php ENDPATH**/ ?>