@props(['header' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Espace Marchand</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Navigation -->
            <nav class="bg-blue-600 border-b border-blue-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('merchant.dashboard') }}" class="text-white text-xl font-bold">
                                    {{ config('app.name', 'Laravel') }} - Marchand
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <a href="{{ route('merchant.dashboard') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out
                                          {{ request()->routeIs('merchant.dashboard') ? 'border-blue-300 text-white' : 'border-transparent text-blue-100 hover:text-white hover:border-blue-300' }}">
                                    {{ __('Tableau de bord') }}
                                </a>
                                <a href="{{ route('merchant.products.index') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out
                                          {{ request()->routeIs('merchant.products.*') ? 'border-blue-300 text-white' : 'border-transparent text-blue-100 hover:text-white hover:border-blue-300' }}">
                                    {{ __('Mes produits') }}
                                </a>
                                <a href="{{ route('merchant.orders') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out
                                          {{ request()->routeIs('merchant.orders') ? 'border-blue-300 text-white' : 'border-transparent text-blue-100 hover:text-white hover:border-blue-300' }}">
                                    {{ __('Commandes') }}
                                </a>
                                <a href="{{ route('merchant.profile') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out
                                          {{ request()->routeIs('merchant.profile') ? 'border-blue-300 text-white' : 'border-transparent text-blue-100 hover:text-white hover:border-blue-300' }}">
                                    {{ __('Profil') }}
                                </a>
                                    <a href="{{ route('merchant.verification.request.form') }}" 
                                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out
                                              {{ request()->routeIs('merchant.verification.request.form') ? 'border-blue-300 text-white' : 'border-transparent text-blue-100 hover:text-white hover:border-blue-300' }}">
                                        {{ __('Demande de badge') }}
                                    </a>
                            </div>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none transition ease-in-out duration-150">
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
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
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
                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-blue-200 hover:bg-blue-700 focus:outline-none focus:bg-blue-700 focus:text-blue-200 transition duration-150 ease-in-out">
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
                        <a href="{{ route('merchant.dashboard') }}" 
                           class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium
                                  {{ request()->routeIs('merchant.dashboard') ? 'border-blue-300 text-blue-100 bg-blue-700' : 'border-transparent text-blue-200 hover:text-white hover:bg-blue-700 hover:border-blue-300' }}">
                            {{ __('Tableau de bord') }}
                        </a>
                        <a href="{{ route('merchant.products.index') }}" 
                           class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium
                                  {{ request()->routeIs('merchant.products.*') ? 'border-blue-300 text-blue-100 bg-blue-700' : 'border-transparent text-blue-200 hover:text-white hover:bg-blue-700 hover:border-blue-300' }}">
                            {{ __('Mes produits') }}
                        </a>
                        <a href="{{ route('merchant.orders') }}" 
                           class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium
                                  {{ request()->routeIs('merchant.orders') ? 'border-blue-300 text-blue-100 bg-blue-700' : 'border-transparent text-blue-200 hover:text-white hover:bg-blue-700 hover:border-blue-300' }}">
                            {{ __('Commandes') }}
                        </a>
                        <a href="{{ route('merchant.profile') }}" 
                           class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium
                                  {{ request()->routeIs('merchant.profile') ? 'border-blue-300 text-blue-100 bg-blue-700' : 'border-transparent text-blue-200 hover:text-white hover:bg-blue-700 hover:border-blue-300' }}">
                            {{ __('Profil') }}
                        </a>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-blue-700">
                        <div class="px-4">
                            <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-blue-200">{{ Auth::user()->email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <a href="{{ route('welcome') }}" 
                               class="block pl-3 pr-4 py-2 text-base font-medium text-blue-200 hover:text-white hover:bg-blue-700">
                                {{ __('Voir le site') }}
                            </a>
                            <a href="{{ route('profile.edit') }}" 
                               class="block pl-3 pr-4 py-2 text-base font-medium text-blue-200 hover:text-white hover:bg-blue-700">
                                {{ __('Profile') }}
                            </a>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();" 
                                   class="block pl-3 pr-4 py-2 text-base font-medium text-blue-200 hover:text-white hover:bg-blue-700">
                                    {{ __('Se déconnecter') }}
                                </a>
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
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </body>
</html>
