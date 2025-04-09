<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900">📜 Histórico de Movimentação</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Registros de Estoque</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-200 text-gray-800 uppercase text-xs font-bold">
                            <tr>
                                <th class="px-4 py-2">Data</th>
                                <th class="px-4 py-2">Usuário</th>
                                <th class="px-4 py-2">Insumo</th>
                                <th class="px-4 py-2">Ação</th>
                                <th class="px-4 py-2">Qtd</th>
                                <th class="px-4 py-2">Qtd Final</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($logs as $log)
                                <tr>
                                    <td class="px-4 py-2">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-2">{{ $log->user->name }}</td>
                                    <td class="px-4 py-2">{{ $log->insumo->nome }}</td>
                                    <td class="px-4 py-2 font-bold text-{{ $log->tipo_acao == 'saida' ? 'red' : 'green' }}-600">
                                        {{ ucfirst($log->tipo_acao) }}
                                    </td>
                                    <td class="px-4 py-2">{{ $log->quantidade }}</td>
                                    <td class="px-4 py-2">{{ $log->quantidade_final }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
