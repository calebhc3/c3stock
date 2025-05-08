<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('📦 Gerenciamento de Insumos') }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">Visualize e controle os insumos cadastrados por unidade.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg shadow">
                    ✅ {{ session('success') }}
                </div>
            @endif

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
        @foreach($insumos as $insumo)
            @php
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
            @endphp

            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition"
                data-pacote='{{ $dadosPacote }}'
                data-unidade='{{ $dadosUnidade }}'>
                <td class="px-6 py-4">{{ $insumo->nome }}</td>
                                    <td class="px-6 py-4 quantidade-minima">{{ $minima }}</td>
                                    <td class="px-6 py-4 quantidade-indicada">{{ $indicada }}</td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="{{ route('insumos.alterar.quantidade', $insumo) }}">
                                                @csrf
                                                <input type="hidden" name="acao" value="saida">
                                                <button type="submit" class="bg-red-500 text-white w-6 h-6 rounded-full hover:bg-red-600 text-xs font-bold flex items-center justify-center">−</button>
                                            </form>

                                            <span class="w-8 text-center font-semibold quantidade-existente">
                                                {{ $existente }}
                                            </span>

                                            <form method="POST" action="{{ route('insumos.alterar.quantidade', $insumo) }}">
                                                @csrf
                                                <input type="hidden" name="acao" value="entrada">
                                                <button type="submit" class="bg-c3turquoise text-white w-6 h-6 rounded-full hover:bg-green-600 text-xs font-bold flex items-center justify-center">+</button>
                                            </form>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">{{ $insumo->unidade_medida }}</td>

                                    <td class="px-6 py-4 font-semibold quantidade-faltante {{ $faltante > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $faltante }}
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
</x-app-layout>