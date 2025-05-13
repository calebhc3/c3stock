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
            <div class="bg-white p-6 rounded-xl shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">📦 Histórico de Pedidos</h3>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-c3green text-xs uppercase text-gray-700">
                <tr>
                    <th class="px-4 py-3">Data do Pedido</th>
                    <th class="px-4 py-3">Prazo Máximo</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pedidos as $pedido)
                    @php
                        $prazoMaximo = $pedido->created_at->addDays(20);
                        $atrasado = now()->greaterThan($prazoMaximo) && is_null($pedido->recebido_em);
                    @endphp
                    <tr class="border-b hover:bg-gray-50 {{ $atrasado ? 'bg-red-100' : '' }}">
                        <td class="px-4 py-2">{{ $pedido->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $prazoMaximo->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">
                            @if($pedido->recebido_em)
                                <span class="text-green-600 font-semibold">Recebido</span>
                            @else
                                <span class="text-yellow-600 font-semibold">Pendente</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if(is_null($pedido->recebido_em))
                                <form action="{{ route('pedidos.confirmarRecebimento', $pedido->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                        Confirmar Recebimento
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-500">Confirmado em {{ $pedido->recebido_em->format('d/m/Y') }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">Nenhum pedido encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-c3green p-6 rounded-xl shadow text-center">
                    <div class="text-sm text-gray-500">Insumos Cadastrados</div>
                    <div class="mt-2 text-3xl font-bold text-gray-800">{{ $totalInsumos ?? '—' }}</div>
                </div>
                <div class="bg-c3green p-6 rounded-xl shadow text-center">
                    <div class="text-sm text-gray-500">Itens Abaixo do Mínimo</div>
                    <div class="mt-2 text-3xl font-bold text-red-600">{{ $itensCriticos ?? '—' }}</div>
                </div>
                <div class="bg-c3green p-6 rounded-xl shadow text-center">
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
                    <a href="{{ route('insumos.editar-estoque') }}"
                       class="inline-flex items-center px-4 py-2 bg-c3turquoise border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-blue-700 transition">
                        Editar estoque
                    </a>
                </div>
            </div>
            {{-- Log de Movimentações --}}
<div class="bg-white p-6 rounded-xl shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Log de Movimentações</h3>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-c3green text-xs uppercase text-gray-700">
                <tr>
                    <th class="px-4 py-3">Data</th>
                    <th class="px-4 py-3">Usuário</th>
                    <th class="px-4 py-3">Ação</th>
                    <th class="px-4 py-3">Insumo</th>
                    <th class="px-4 py-3">Qtd.</th>
                    <th class="px-4 py-3">Qtd. Final</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logMovimentacoes as $log)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2">{{ $log->user->name }}</td>
                        <td class="px-4 py-2 capitalize">
                            @if($log->tipo_acao === 'entrada')
                                <span class="text-green-600 font-semibold">Entrada</span>
                            @elseif($log->tipo_acao === 'saida')
                                <span class="text-red-600 font-semibold">Saída</span>
                            @else
                                <span class="text-yellow-600 font-semibold">Edição</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $log->insumo->nome }}</td>
                        <td class="px-4 py-2">{{ $log->quantidade }}</td>
                        <td class="px-4 py-2">{{ $log->quantidade_final }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">Nenhuma movimentação registrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6 pt-4 border-t border-gray-200 flex justify-center">
    {{ $logMovimentacoes->links() }}
</div>

    </div>
</div>

<script>
</script>

        </div>
    </div>
</x-app-layout>
