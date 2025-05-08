<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label for="nome-proprio" value="Nome" class="block text-sm font-medium text-gray-700 mb-1" />

            @php
                $partesNome = explode(' - ', old('state.name', $state['name'] ?? ''));
                $nomeFixo = trim($partesNome[0] ?? 'Administrador BETIM');
                $nomeProprio = trim($partesNome[1] ?? '');
            @endphp

            <div x-data="{
                nomeProprio: '{{ $nomeProprio }}',
                updateNomeCompleto() {
                    this.$wire.set('state.name', '{{ $nomeFixo }} - ' + this.nomeProprio);
                }
            }" class="flex items-center gap-2">
                <!-- Texto fixo -->
                <span class="text-sm font-semibold whitespace-nowrap text-gray-700">
                    {{ $nomeFixo }} -
                </span>

                <!-- Input -->
                <input
                    id="nome-proprio"
                    type="text"
                    class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-300"
                    x-model="nomeProprio"
                    x-on:input.debounce.500ms="updateNomeCompleto()"
                    autocomplete="name"
                />
            </div>

            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>