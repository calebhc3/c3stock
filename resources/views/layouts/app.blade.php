<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @livewireStyles
        @vite(['resources/css/app.css'])
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        <!-- Scripts -->
        @livewireScripts
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
    $(document).ready(function () {
        $('.confirmar-btn').click(function () {
            const button = $(this);
            const pedidoId = button.data('id');

            $.ajax({
                url: `/pedidos/${pedidoId}/confirmar-recebimento`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        // Atualiza a coluna de status
                        $(`#status-${pedidoId}`).html(
                            `<span class="text-green-600 font-semibold">Recebido</span>`
                        );

                        // Substitui botão por texto com data
                        $(`#acao-${pedidoId}`).html(
                            `<span class="text-gray-500">Confirmado em ${response.recebido_em}</span>`
                        );

                        // Remove o fundo vermelho se tinha vencido
                        $(`#pedido-row-${pedidoId}`).removeClass('bg-red-100');
                    }
                },
                error: function () {
                    alert('Erro ao confirmar recebimento. Tente novamente.');
                }
            });
        });
    });
</script>
        @vite(['resources/js/app.js'])
    </body>
</html>