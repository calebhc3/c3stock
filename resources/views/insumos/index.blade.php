<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('📦 Gerenciamento de Insumos') }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">Visualize e controle os insumos cadastrados por unidade.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Notificação --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg shadow-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Tabela --}}
            <div class="bg-white shadow rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-gray-700">
                        <thead class="bg-gray-200 text-gray-800 uppercase text-xs font-bold tracking-wider border-b">
                            <tr>
                                <th class="px-6 py-4 text-left">Nome</th>
                                <th class="px-6 py-4 text-left">Qtd. Mínima</th>
                                <th class="px-6 py-4 text-left">Qtd. Existente</th>
                                <th class="px-6 py-4 text-left">Unidade</th>
                                <th class="px-6 py-4 text-left">Comprar?</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($insumos as $insumo)
                                <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition">
                                    <td class="px-6 py-4">{{ $insumo->nome }}</td>
                                    <td class="px-6 py-4">{{ $insumo->pivot->quantidade_minima }}</td>

                                    {{-- Qtd. Existente com botões --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            {{-- Botão de - --}}
                                            <form method="POST" action="{{ route('insumos.alterar.quantidade', $insumo) }}">
                                                @csrf
                                                <input type="hidden" name="acao" value="saida">
                                                <x-button class="bg-red-500 text-white w-6 h-6 rounded-full hover:bg-red-600 text-xs font-bold leading-none flex items-center justify-center">−</x-button>
                                                </form>

                                            <span class="w-8 text-center font-semibold text-gray-800">
                                                {{ $insumo->pivot->quantidade_existente }}
                                            </span>

                                            {{-- Botão de + --}}
                                            <form method="POST" action="{{ route('insumos.alterar.quantidade', $insumo) }}">
                                                @csrf
                                                <input type="hidden" name="acao" value="entrada">
                                                <x-button class="bg-green-500 text-white w-6 h-6 rounded-full hover:bg-green-600 text-xs font-bold leading-none flex items-center justify-center">+</x-button>
                                                </form>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">{{ $insumo->unidade_medida }}</td>

                                    {{-- Comprar? --}}
                                    <td class="px-6 py-4 font-semibold {{ $insumo->necessario_comprar > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $insumo->necessario_comprar }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
