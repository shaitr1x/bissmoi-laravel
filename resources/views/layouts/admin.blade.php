<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'Bissmoi') }}</title>

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
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center w-full">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-800">
                                <img src="{{ asset('images/logo-bissmoi.svg') }}" alt="BISSMOI" class="h-10 w-auto mr-2" style="max-height: 40px;">
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <nav class="flex gap-2 ml-8">
                            <div class="flex gap-2">
                                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                    Dashboard
                                </x-nav-link>
                                <x-nav-link :href="route('admin.analytics')" :active="request()->routeIs('admin.analytics')">
                                    Analytics
                                </x-nav-link>
                                <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                                    Catégories
                                </x-nav-link>
                                @php
                                    // Use the same logic as AdminController for counters
                                    $pendingProductsCount = \App\Models\Product::where('status', 'pending')->count();
                                    $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->count();
                                    $pendingMerchantsCount = \App\Models\User::where('role', 'merchant')->where('merchant_approved', false)->count();
                                    $newUsersCount = \App\Models\User::where('is_active', false)->count();
                                @endphp
                                <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')" class="relative">
                                    Produits
                                    @if($pendingProductsCount > 0)
                                        <span class="absolute -top-2 -right-3 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                            {{ $pendingProductsCount > 99 ? '99+' : $pendingProductsCount }}
                                        </span>
                                    @endif
                                </x-nav-link>
                                <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" class="relative">
                                    Commandes
                                    @if($pendingOrdersCount > 0)
                                        <span class="absolute -top-2 -right-3 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                            {{ $pendingOrdersCount > 99 ? '99+' : $pendingOrdersCount }}
                                        </span>
                                    @endif
                                </x-nav-link>
                                <x-nav-link :href="route('admin.merchants')" :active="request()->routeIs('admin.merchants*')" class="relative">
                                    Commerçants
                                    @if($pendingMerchantsCount > 0)
                                        <span class="absolute -top-2 -right-3 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                            {{ $pendingMerchantsCount > 99 ? '99+' : $pendingMerchantsCount }}
                                        </span>
                                    @endif
                                </x-nav-link>
                                <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="relative">
                                    Utilisateurs
                                    @if($newUsersCount > 0)
                                        <span class="absolute -top-2 -right-3 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                            {{ $newUsersCount > 99 ? '99+' : $newUsersCount }}
                                        </span>
                                    @endif
                                </x-nav-link>
                            </div>
                            <div class="flex gap-2 border-l border-gray-200 pl-4 ml-4">
                                <x-nav-link :href="route('admin.blog.index')" :active="request()->routeIs('admin.blog.*')">
                                    Blog
                                </x-nav-link>
                                <x-nav-link :href="route('admin.seo.edit')" :active="request()->routeIs('admin.seo.*')">
                                    Référencement
                                </x-nav-link>
                                <x-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
                                    Paramètres
                                </x-nav-link>
                            </div>
                        </nav>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
                        <div class="w-px h-8 bg-gray-200 mx-4"></div>
                        <!-- Notifications -->
                        @php
                            $unreadNotifications = \App\Models\AdminNotification::unread()->count();
                        @endphp
                        <div class="relative">
                            <a href="{{ route('admin.notifications.index') }}"
                               class="relative flex items-center justify-center p-2 text-gray-400 hover:text-yellow-500 focus:outline-none transition duration-150 ease-in-out">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if($unreadNotifications > 0)
                                    <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full shadow">
                                        {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                                    </span>
                                @endif
                            </a>
                        </div>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('home')">
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

                    <!-- Notifications & Hamburger (mobile) -->
                    <div class="flex items-center sm:hidden">
                        <div class="relative mr-2">
                            <a href="{{ route('admin.notifications.index') }}" 
                               class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                </svg>
                                @if(isset($unreadNotifications) && $unreadNotifications > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                        {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                                    </span>
                                @endif
                            </a>
                        </div>
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
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
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        Dashboard
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.analytics')" :active="request()->routeIs('admin.analytics')">
                        Analytics
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                        Catégories
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                        Produits
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                        Commandes
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.merchants')" :active="request()->routeIs('admin.merchants*')">
                        Commerçants
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        Utilisateurs
                    </x-responsive-nav-link>
                       <x-responsive-nav-link :href="route('admin.merchant_verification_requests')" :active="request()->routeIs('admin.merchant_verification_requests')">
                           Demandes de badge
                       </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.blog.index')" :active="request()->routeIs('admin.blog.*')">
                        Blog
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.seo.edit')" :active="request()->routeIs('admin.seo.*')">
                        Référencement
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
                        Paramètres
                    </x-responsive-nav-link>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('admin.notifications.index')">
                            Notifications
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('home')">
                            Voir le site
                        </x-responsive-nav-link>
                        @if (request()->routeIs('home'))
                            <x-responsive-nav-link :href="route('admin.dashboard')">
                                Administration
                            </x-responsive-nav-link>
                        @endif
                        <x-responsive-nav-link :href="route('profile.edit')">
                            Profile
                        </x-responsive-nav-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Se déconnecter
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
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
