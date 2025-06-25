<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    {{ __('📦 Gerenciamento de Estoque') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ auth()->user()->currentTeam->name }}</p>
            </div>
            <div class="text-sm text-gray-500">
                <span class="bg-gray-100 px-3 py-1 rounded-full">Atualizado: {{ now()->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 space-y-6">
            @if(session('success'))
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-transition
                     x-init="setTimeout(() => show = false, 3000)"
                     class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg shadow">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <!-- Cards de Resumo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase">Total de Itens</h3>
                    <p class="text-2xl font-bold">{{ $insumos->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase">Itens Abaixo do Mínimo</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $belowMinimumCount }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase">Itens em Estoque</h3>
                    <p class="text-2xl font-bold">{{ $inStockCount }}</p>
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
                            @foreach($insumos as $insumo)
                                @php
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
                                @endphp

                                <tr class="hover:bg-blue-50 transition"
                                    data-pacote='{{ $dadosPacote }}'
                                    data-unidade='{{ $dadosUnidade }}'
                                    data-nome="{{ strtolower($insumo->nome) }}">
                                    <td class="px-6 py-4 font-medium">
                                        {{ $insumo->nome }}
                                        @if($insumo->categoria)
                                            <span class="block text-xs text-gray-500 mt-1">{{ ucfirst($insumo->categoria) }}</span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 quantidade-minima">{{ $minima }}</td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-{{ $existente < $minima ? 'red' : 'green' }}-500 h-2.5 rounded-full" 
                                                     style="width: {{ $estoquePercent }}%"></div>
                                            </div>
                                            <span class="{{ $existente < $minima ? 'text-red-600 font-bold' : 'text-gray-700' }} quantidade-existente">
                                                {{ $existente }}
                                            </span>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $insumo->unidade_medida }}
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <!-- Botão de Remover -->
                                            <form method="POST" action="{{ route('insumos.alterar.quantidade', $insumo) }}" class="inline">
                                                @csrf
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
                                            <form method="POST" action="{{ route('insumos.alterar.quantidade', $insumo) }}" class="inline">
                                                @csrf
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
                            @endforeach
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
</x-app-layout>