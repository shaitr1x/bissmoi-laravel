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

        <!-- Scripts -->
        @if(app()->environment('local'))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link rel="stylesheet" href="{{ asset('build/assets/app-49e025ce.css') }}">
            <script src="{{ asset('build/assets/app-4ba226a4.js') }}" defer></script>
        @endif
        
        <!-- Scripts additionnels -->
        @auth
            <script>
                // Mettre à jour le compteur du panier
                function updateCartCount() {
                    fetch('{{ route("cart.count") }}')
                        .then(response => response.json())
                        .then(data => {
                            const cartCountEl = document.getElementById('cartCount');
                            if (data.count > 0) {
                                cartCountEl.textContent = data.count;
                                cartCountEl.classList.remove('hidden');
                            } else {
                                cartCountEl.classList.add('hidden');
                            }
                        })
                        .catch(error => console.error('Erreur:', error));
                }

                // Mettre à jour au chargement de la page
                document.addEventListener('DOMContentLoaded', function() {
                    updateCartCount();
                });

                // Mettre à jour toutes les 30 secondes
                setInterval(updateCartCount, 30000);
            </script>
        @endauth
    </head>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

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
                <!-- Messages de session -->
                @if(session('success'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Fermer</title>
                                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Fermer</title>
                                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                @endif

                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif
            </main>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </body>
</html>
