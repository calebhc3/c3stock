<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('📦 Gerenciar Estoque') }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">Cadastre o estoque existente.</p>
    </x-slot>

            {{-- Notificação --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg shadow-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="py-6 px-4 max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Estoque de Insumos</h1>
    </div>

    <form method="POST" action="{{ route('insumos.atualizar-estoque') }}" class="bg-white rounded-lg shadow overflow-hidden">
        @csrf
        
        <div class="divide-y divide-gray-200">
            @foreach ($insumos as $insumo)
            <div class="px-4 py-5 sm:p-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ $insumo->nome }}
                    <span class="text-xs text-gray-500">(Atual: {{ $insumo->pivot->quantidade_existente }})</span>
                </label>
                <input 
                    type="number" 
                    name="estoque[{{ $insumo->id }}]" 
                    min="0" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    value="{{ $insumo->pivot->quantidade_existente }}"
                >
            </div>
            @endforeach
        </div>
        
        <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-end space-x-3">

            <x-button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Atualizar Estoque
            </x-button>
        </div>
    </form>
</div>
</x-app-layout>