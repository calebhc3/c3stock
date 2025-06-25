<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 text-center">📜 Solicitação de Insumos</h2>
    </x-slot>

    @if(session('success'))
        <div id="success-message" class="mb-4 p-3 bg-green-100 border border-green-400 text-green-800 rounded-md text-sm text-center">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="py-4 flex justify-center">
        <div class="max-w-7xl bg-white shadow-md rounded-lg p-4 border border-gray-300">
            @php
                $hoje = now();
                $dia = $hoje->day;
                $mes = $hoje->month;
                $ano = $hoje->year;

                // Special dates: today (25/06/2025) and tomorrow (26/06/2025)
                $podePedir = ($dia == 25 && $mes == 6 && $ano == 2025) || 
                            ($dia == 26 && $mes == 6 && $ano == 2025);
                $ehAdmin = auth()->user()->isTeamAdmin();
            @endphp

            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 rounded text-sm text-center mb-4">
                @if($podePedir)
                    ⚠️ <strong>ATENÇÃO:</strong> Pedidos liberados excepcionalmente hoje (25/06) e amanhã (26/06).<br>
                @else
                    ⚠️ Os pedidos normalmente só podem ser realizados nos dias <strong>1</strong> e <strong>15</strong> de cada mês.<br>
                @endif
                🕒 O prazo para recebimento dos insumos é de até <strong>20</strong> dias.
            </div>

            <h3 class="text-md font-semibold text-gray-700 text-center mb-4">🛒 Pedido de Insumos</h3>

            <form method="POST" action="{{ route('pedidos.send') }}" class="space-y-3">
                @csrf

                <div id="items-container" class="space-y-2 text-sm font-mono">
                    <!-- First item row -->
                    <div class="flex gap-2 items-center">
                        <select name="items[0][insumo_id]" class="w-full mt-3 border rounded p-1 focus:ring focus:ring-blue-300 text-sm" required>
                            <option value="">🔽 Selecione uma produto</option>
                            @foreach($insumos->groupBy('tipo') as $tipo => $items)
                                <optgroup label="{{ ucfirst($tipo) }}">
                                    @foreach($items as $insumo)
                                        <option value="{{ $insumo->id }}">{{ $insumo->nome }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <input type="number" name="items[0][quantidade]" min="1" class="w-20 mt-3 border rounded p-1 text-center text-sm focus:ring focus:ring-blue-300" placeholder="Qtd" required>
                    </div>
                </div>

                <!-- Add Item Button -->
                <button type="button" id="add-item" class="w-full mt-5 text-xs px-3 py-1 border border-blue-500 text-blue-600 rounded-md hover:bg-blue-50 transition flex items-center justify-center gap-1">
                    ➕ Adicionar item
                </button>

                <!-- Submit Button -->
                @if($podePedir || $ehAdmin)
                    <button type="submit" class="w-full px-3 mt-10 py-1 text-white bg-c3turquoise text-sm rounded-md hover:bg-c3turquoise transition">
                        📬 Enviar Pedido
                    </button>
                @else
                    <button type="button" disabled class="w-full px-3 mt-10 py-1 bg-gray-300 text-gray-500 text-sm rounded-md cursor-not-allowed">
                        🚫 Pedido indisponível
                    </button>
                @endif
            </form>

            <div class="mt-10 text-sm text-gray-500">
                <p>📅 Data do último pedido: {{ $hoje->format('d/m/Y') }}</p>
                <p>🕒 Prazo de entrega: até 20 dias úteis.</p>
                
                <h3 class="text-md font-semibold text-gray-700 text-center mb-4 mt-4">📂 Histórico de Pedidos</h3>

                @if($historicoPedidos->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full w-full bg-white text-sm border rounded shadow-sm">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="p-2 border-b">#</th>
                                    <th class="p-2 border-b">Data</th>
                                    <th class="p-2 border-b">Arquivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historicoPedidos as $index => $pedido)
                                    <tr class="text-center hover:bg-gray-50">
                                        <td class="p-2 border-b">{{ $index + 1 }}</td>
                                        <td class="p-2 border-b">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="p-2 border-b">
                                            <a href="{{ asset('storage/' . $pedido->file_path) }}" class="text-blue-500 underline" target="_blank">
                                                📄 Baixar Excel
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-500 text-sm">Nenhum pedido realizado ainda.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Hidden template for cloning via JS -->
    <div id="item-template" class="hidden">
        <div class="flex gap-2 items-center mt-2">
            <select class="w-full border rounded p-1 focus:ring focus:ring-blue-300 text-sm" required>
                <option value="">🔽 Selecione o produto</option>
                @foreach($insumos->groupBy('tipo') as $tipo => $items)
                    <optgroup label="{{ ucfirst($tipo) }}">
                        @foreach($items as $insumo)
                            <option value="{{ $insumo->id }}">{{ $insumo->nome }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            <input type="number" min="1" class="w-20 border rounded p-1 text-center text-sm focus:ring focus:ring-blue-300" placeholder="Qtd" required>
        </div>
    </div>

    <script>
        let itemIndex = 1;

        document.getElementById('add-item').addEventListener('click', function () {
            const container = document.getElementById('items-container');
            const template = document.getElementById('item-template').firstElementChild.cloneNode(true);

            // Update name attributes dynamically
            template.querySelector('select').name = `items[${itemIndex}][insumo_id]`;
            template.querySelector('input').name = `items[${itemIndex}][quantidade]`;

            // Add animation
            template.classList.add('opacity-0', 'animate-fade-in');
            container.appendChild(template);

            // Force transition to appear
            setTimeout(() => {
                template.classList.remove('opacity-0');
            }, 10);

            itemIndex++;
        });

        // Auto-hide success message after 5 seconds
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 5000);
        }
    </script>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-3px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.2s ease-out;
        }
        
        /* Style for optgroup labels */
        optgroup {
            font-weight: bold;
            color: #4b5563;
        }
        optgroup option {
            font-weight: normal;
            color: #111827;
        }
    </style>
</x-app-layout>