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
            <nav x-data="{ open: false }" class="bg-blue-600 border-b border-blue-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('merchant.dashboard') }}" class="flex items-center">
                                    <img src="{{ asset('images/logo-bissmoi.svg') }}" alt="BISSMOI" class="h-10 w-auto mr-2" style="max-height: 40px;">
                                    <span class="text-white text-xl font-bold">Bissmoi Marchand</span>
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <x-nav-link :href="route('merchant.dashboard')" :active="request()->routeIs('merchant.dashboard')" class="text-white hover:text-blue-200">
                                    {{ __('Tableau de bord') }}
                                </x-nav-link>
                                <x-nav-link :href="route('merchant.products.index')" :active="request()->routeIs('merchant.products.*')" class="text-white hover:text-blue-200">
                                    {{ __('Mes produits') }}
                                </x-nav-link>
                                <x-nav-link :href="route('merchant.orders')" :active="request()->routeIs('merchant.orders')" class="text-white hover:text-blue-200">
                                    {{ __('Commandes') }}
                                </x-nav-link>
                                <x-nav-link :href="route('merchant.profile')" :active="request()->routeIs('merchant.profile')" class="text-white hover:text-blue-200">
                                    {{ __('Profil') }}
                                </x-nav-link>
                                    <x-nav-link :href="route('merchant.verification.request.form')" :active="request()->routeIs('merchant.verification.request.form')" class="text-white hover:text-blue-200">
                                        {{ __('Demande de badge') }}
                                    </x-nav-link>
                            </div>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:text-blue-200 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->name }}</div>

                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('welcome')">
                                        {{ __('Voir le site') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('merchant.profile')">
                                        {{ __('Profil') }}
                                    </x-dropdown-link>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
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

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <x-responsive-nav-link :href="route('merchant.dashboard')" :active="request()->routeIs('merchant.dashboard')" class="text-white">
                            {{ __('Tableau de bord') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('merchant.products.index')" :active="request()->routeIs('merchant.products.*')" class="text-white">
                            {{ __('Mes produits') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('merchant.orders')" :active="request()->routeIs('merchant.orders')" class="text-white">
                            {{ __('Commandes') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('merchant.profile')" :active="request()->routeIs('merchant.profile')" class="text-white">
                            {{ __('Profil') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('merchant.verification.request.form')" :active="request()->routeIs('merchant.verification.request.form')" class="text-white">
                            {{ __('Demande de badge') }}
                        </x-responsive-nav-link>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-blue-700">
                        <div class="px-4">
                            <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-blue-200">{{ Auth::user()->email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <x-responsive-nav-link :href="route('merchant.profile')" class="text-white">
                                {{ __('Profil') }}
                            </x-responsive-nav-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-responsive-nav-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();" class="text-white">
                                    {{ __('Se déconnecter') }}
                                </x-responsive-nav-link>
                            </form>
                        </div>
                    </div>
                </div>
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
        </div>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </body>
</html>
