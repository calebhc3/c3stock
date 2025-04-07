<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900">
            📦 Painel de Estoque - C3 Saúde Ocupacional
        </h2>
        <p class="text-sm text-gray-500 mt-1">Bem-vindo de volta, {{ Auth::user()->name }}! Aqui está um resumo rápido do seu estoque.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Cards Resumo --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow text-center">
                    <div class="text-sm text-gray-500">Insumos Cadastrados</div>
                    <div class="mt-2 text-3xl font-bold text-gray-800">{{ $totalInsumos ?? '—' }}</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow text-center">
                    <div class="text-sm text-gray-500">Itens Abaixo do Mínimo</div>
                    <div class="mt-2 text-3xl font-bold text-red-600">{{ $itensCriticos ?? '—' }}</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow text-center">
                    <div class="text-sm text-gray-500">Última Atualização</div>
                    <div class="mt-2 text-lg font-medium text-gray-700">{{ $ultimaAtualizacao ?? '—' }}</div>
                </div>
            </div>

            {{-- Atalhos --}}
            <div class="bg-white p-6 rounded-xl shadow flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Ações rápidas</h3>
                    <p class="text-sm text-gray-500">Gerencie os insumos da unidade com agilidade.</p>
                </div>
                <div>
                    <a href="{{ route('insumos.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-blue-700 transition">
                        ➕ Novo Insumo
                    </a>
                </div>
            </div>

            {{-- Log de Ações --}}
            @if(isset($logMovimentacoes) && $logMovimentacoes->count())
                <div class="bg-white p-6 rounded-xl shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📜 Log de Movimentações</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-700">
                            <thead class="bg-gray-200 text-gray-800 uppercase text-xs font-bold tracking-wider">
                                <tr>
                                    <th class="px-4 py-2">Usuário</th>
                                    <th class="px-4 py-2">Ação</th>
                                    <th class="px-4 py-2">Insumo</th>
                                    <th class="px-4 py-2">Quantidade</th>
                                    <th class="px-4 py-2">Data</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($logMovimentacoes as $log)
                                    <tr>
                                        <td class="px-4 py-2">{{ $log->usuario->name }}</td>
                                        <td class="px-4 py-2">{{ ucfirst($log->acao) }}</td>
                                        <td class="px-4 py-2">{{ $log->insumo->nome }}</td>
                                        <td class="px-4 py-2 font-bold {{ $log->quantidade < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $log->quantidade }}
                                        </td>
                                        <td class="px-4 py-2">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
