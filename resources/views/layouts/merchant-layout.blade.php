<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Espace Marchand</title>

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
    </head>
    <body class="font-sans antialiased">
        <!-- Navigation -->
        <div class="min-h-screen bg-gray-100">
            <nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('merchant.dashboard') }}" class="flex items-center">
                                    <img src="{{ asset('images/logo-bissmoi.svg') }}" alt="BISSMOI" class="h-10 w-auto mr-3" style="max-height: 40px;">
                                    <div>
                                        <span class="text-xl font-bold text-gray-800">Bissmoi</span>
                                        <span class="block text-xs text-green-600 font-semibold">Espace Marchand</span>
                                    </div>
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-6 sm:-my-px sm:ml-10 sm:flex">
                                <x-nav-link :href="route('merchant.dashboard')" :active="request()->routeIs('merchant.dashboard')" class="text-gray-600 hover:text-green-700 border-transparent hover:border-green-300 font-medium">
                                    {{ __('Tableau de bord') }}
                                </x-nav-link>
                                <x-nav-link :href="route('merchant.products.index')" :active="request()->routeIs('merchant.products.*')" class="text-gray-600 hover:text-green-700 border-transparent hover:border-green-300 font-medium">
                                    {{ __('Mes produits') }}
                                </x-nav-link>
                                <x-nav-link :href="route('merchant.orders')" :active="request()->routeIs('merchant.orders')" class="text-gray-600 hover:text-green-700 border-transparent hover:border-green-300 font-medium">
                                    {{ __('Commandes') }}
                                </x-nav-link>
                                <x-nav-link :href="route('merchant.profile')" :active="request()->routeIs('merchant.profile')" class="text-gray-600 hover:text-green-700 border-transparent hover:border-green-300 font-medium">
                                    {{ __('Profil') }}
                                </x-nav-link>
                                <x-nav-link :href="route('merchant.verification.request.form')" :active="request()->routeIs('merchant.verification.request.form')" class="text-gray-600 hover:text-green-700 border-transparent hover:border-green-300 font-medium">
                                    {{ __('Demande de badge') }}
                                </x-nav-link>
                            </div>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition ease-in-out duration-150">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 bg-green-200 rounded-full flex items-center justify-center mr-2">
                                                <span class="text-xs font-medium text-green-700">
                                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            {{ Auth::user()->name }}
                                        </div>

                                        <div class="ml-2">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('welcome')" class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0 9c-5 0-9-4-9-9m9 9c5 0 9-4 9-9m-9-9v9"/>
                                        </svg>
                                        {{ __('Voir le site') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('merchant.profile')" class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ __('Profil') }}
                                    </x-dropdown-link>

                                    <!-- Authentication -->
                                    <hr class="my-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();"
                                                class="flex items-center text-red-600 hover:bg-red-50">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            {{ __('Se déconnecter') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-blue-200 hover:text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 focus:text-white transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu - Merchant Sliding Panel -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-300" 
                     x-transition:enter-start="transform -translate-x-full" 
                     x-transition:enter-end="transform translate-x-0"
                     x-transition:leave="transition ease-in duration-200" 
                     x-transition:leave-start="transform translate-x-0" 
                     x-transition:leave-end="transform -translate-x-full"
                     class="fixed inset-y-0 left-0 z-50 w-80 bg-white shadow-xl sm:hidden mobile-menu-panel">
                    
                    <!-- Header du menu -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <span class="text-lg font-semibold text-gray-900">Espace Vendeur</span>
                        </div>
                        <button @click="open = false" class="p-2 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Navigation principale -->
                    <div class="py-4 flex-1 overflow-y-auto">
                        <div class="space-y-1 px-4">
                            <a href="{{ route('merchant.dashboard') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('merchant.dashboard') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                                <svg class="mr-4 h-6 w-6 {{ request()->routeIs('merchant.dashboard') ? 'text-green-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Tableau de Bord
                            </a>

                            <a href="{{ route('merchant.products.index') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('merchant.products.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                                <svg class="mr-4 h-6 w-6 {{ request()->routeIs('merchant.products.*') ? 'text-green-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Mes Produits
                            </a>

                            <a href="{{ route('merchant.orders') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('merchant.orders') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                                <svg class="mr-4 h-6 w-6 {{ request()->routeIs('merchant.orders') ? 'text-green-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Mes Commandes
                            </a>

                            <a href="{{ route('merchant.verification.request.form') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('merchant.verification.request.form') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                                <svg class="mr-4 h-6 w-6 {{ request()->routeIs('merchant.verification.request.form') ? 'text-green-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Demande Badge
                            </a>

                            <a href="{{ route('home') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200">
                                <svg class="mr-4 h-6 w-6 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Voir le Site
                            </a>
                        </div>
                    </div>

                    <!-- Section utilisateur marchand -->
                    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-green-600 font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-green-600 truncate">Commerçant</p>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <a href="{{ route('merchant.profile') }}" class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Mon Profil
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="mr-3 h-4 w-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Se Déconnecter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Overlay pour fermer le menu -->
                <div x-show="open" 
                     x-transition:enter="transition-opacity ease-out duration-300" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-in duration-200" 
                     x-transition:leave-start="opacity-100" 
                     x-transition:leave-end="opacity-0"
                     @click="open = false"
                     class="fixed inset-0 z-40 bg-black bg-opacity-25 sm:hidden mobile-menu-overlay"></div>
            </nav>

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="py-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @hasSection('slot')
                        @yield('slot')
                    @else
                        {{ $slot }}
                    @endif
                </div>
            </main>

            <!-- Navigation bottom mobile marchand -->
            <x-merchant-mobile-bottom-nav />
        </div>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </body>
</html>
