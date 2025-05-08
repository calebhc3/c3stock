<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900">
            🧠 Painel Geral de Estoque - Admin Master
        </h2>
        <p class="text-sm text-gray-500 mt-1">Visão consolidada dos estoques com filtro por unidade e exibição por pacotes.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex justify-between items-center mb-4">
                <label class="block text-sm font-medium text-gray-700">
                    Filtrar por unidade:
                    <select id="filtroUnidade" class="mt-1 block w-64 rounded-md border-gray-300 shadow-sm focus:border-c3green focus:ring focus:ring-c3green focus:ring-opacity-50">
                        <option value="todas">Todas</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
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
                        @foreach($teams as $team)
                            @foreach($team->insumos as $insumo)
                                @php
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
                                @endphp
                                <tr class="border-b hover:bg-gray-50" data-team="{{ $team->id }}" data-unidade="{{ $team->name }}" data-pacote='{{ $dadosPacote }}' data-unidade-info='{{ $dadosUnidade }}'>
                                    <td class="px-4 py-2">{{ $team->name }}</td>
                                    <td class="px-4 py-2">{{ $insumo->nome }}</td>
                                    <td class="px-4 py-2 quantidade-existente">{{ $existente }}</td>
                                    <td class="px-4 py-2 quantidade-minima">{{ $minima }}</td>
                                    <td class="px-4 py-2 quantidade-indicada">{{ $indicada }}</td>
                                    <td class="px-4 py-2 quantidade-faltante">{{ $faltante }}</td>
                                    <td class="px-4 py-2">
                                        @if($existente < $minima)
                                            <span class="text-red-600 font-semibold">Crítico</span>
                                        @else
                                            <span class="text-green-600 font-semibold">Ok</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
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
</x-app-layout>
