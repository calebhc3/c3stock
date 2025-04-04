<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Novo Insumo') }}
        </h2>
    </x-slot>
<!-- Tailwind vai ver isso e manter a classe no build -->
<div class="hidden bg-blue-600"></div>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-4 shadow-md rounded-lg max-w-xl mx-auto">

                <form action="{{ route('insumos.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Nome --}}
                    <div>
                        <x-label for="nome" value="Nome do Insumo" />
                        <x-input id="nome" name="nome" type="text" class="mt-1 block w-full" required />
                    </div>

                    {{-- Tipo --}}
                    <div>
                        <x-label for="tipo" value="Tipo" />
                        <select id="tipo" name="tipo" class="mt-1 block w-full rounded-md shadow-sm border-gray-300">
                            <option value="insumo">Insumo</option>
                            <option value="medicamento">Medicamento</option>
                        </select>
                    </div>

                    {{-- Agrupando 3 campos lado a lado --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Quantidade Mínima --}}
                        <div>
                            <x-label for="quantidade_minima" value="Qtd. Mínima" />
                            <x-input id="quantidade_minima" name="quantidade_minima" type="number" class="mt-1 block w-full" required />
                        </div>

                        {{-- Unidade de Medida --}}
                        <div>
                            <x-label for="unidade_medida" value="Unidade de Medida" />
                            <x-input id="unidade_medida" name="unidade_medida" type="text" class="mt-1 block w-full" required />
                        </div>

                        {{-- Quantidade Existente --}}
                        <div>
                            <x-label for="quantidade_existente" value="Qtd. Existente" />
                            <x-input id="quantidade_existente" name="quantidade_existente" type="number" class="mt-1 block w-full" required />
                        </div>
                    </div>

                    {{-- Botão --}}
                    <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 ">
                        Salvar
                    </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</x-app-layout>
