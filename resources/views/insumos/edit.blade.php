<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Insumo') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">
            <form action="{{ route('insumos.update', $insumo) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nome</label>
                    <input type="text" name="nome" value="{{ $insumo->nome }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Tipo</label>
                    <select name="tipo" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="insumo" @selected($insumo->tipo === 'insumo')>Insumo</option>
                        <option value="medicamento" @selected($insumo->tipo === 'medicamento')>Medicamento</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Quantidade Mínima</label>
                    <input type="number" name="quantidade_minima" value="{{ $insumo->quantidade_minima }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Unidade de Medida</label>
                    <input type="text" name="unidade_medida" value="{{ $insumo->unidade_medida }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Quantidade Existente</label>
                    <input type="number" name="quantidade_existente" value="{{ $insumo->quantidade_existente }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <button type="submit" class="btn btn-primary">Atualizar</button>
            </form>
        </div>
    </div>
</x-app-layout>
