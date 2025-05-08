<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('❓ FAQ - Manual de Funcionamento') }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">Encontre respostas para as dúvidas mais frequentes sobre o sistema.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Notificação --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg shadow-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Lista de FAQs --}}
            <div class="bg-white shadow rounded-xl overflow-hidden">
                <div class="divide-y divide-gray-200">
                    @foreach($faqItems as $item)
                        <div class="p-6 hover:bg-gray-50 transition duration-150 ease-in-out">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-c3green text-white rounded-full p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $item['question'] }}</h3>
                                    <div class="mt-2 text-gray-600">
                                        <p>{{ $item['answer'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Formulário de Contato --}}
            <div class="bg-white shadow rounded-xl overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Não encontrou o que precisava?</h3>
                    <p class="text-gray-600 mb-6">Nos envie uma Mensagem.</p>

                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        </div>

                        <div class="mt-6">
                            <label for="message" class="block text-sm font-medium text-gray-700">Mensagem</label>
                            <textarea id="message" name="message" rows="4" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-c3green focus:ring focus:ring-c3green focus:ring-opacity-50"></textarea>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <x-button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-c3green border border-transparent rounded-md font-semibold text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-c3green transition">
                                Enviar Mensagem
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>