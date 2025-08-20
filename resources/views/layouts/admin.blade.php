<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'Bissmoi') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center w-full">
                        <!-- Logo et titre à l'extrême gauche -->
                        <div class="shrink-0 flex items-center absolute left-0 pl-0 ml-0">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                                <img src="{{ asset('images/logo-bissmoi.svg') }}" alt="BISSMOI" class="h-10 w-auto mr-3" style="max-height: 40px;">
                                <div>
                                    <span class="text-xl font-bold text-gray-800">Bissmoi</span>
                                    <span class="block text-xs text-purple-600 font-semibold">Administration</span>
                                </div>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-6 sm:-my-px sm:ml-10 sm:flex">
                        <nav class="flex gap-4 ml-8">
                            <div class="flex gap-4">
                                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-gray-600 hover:text-purple-700 border-transparent hover:border-purple-300 font-medium">
                                    Dashboard
                                </x-nav-link>
                                <x-nav-link :href="route('admin.analytics')" :active="request()->routeIs('admin.analytics')" class="text-gray-600 hover:text-purple-700 border-transparent hover:border-purple-300 font-medium">
                                    Analytics
                                </x-nav-link>
                                <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')" class="text-gray-600 hover:text-purple-700 border-transparent hover:border-purple-300 font-medium">
                                    Catégories
                                </x-nav-link>
                                @php
                                    // Use the same logic as AdminController for counters
                                    $pendingProductsCount = \App\Models\Product::where('status', 'pending')->count();
                                    $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->count();
                                    $pendingMerchantsCount = \App\Models\User::where('role', 'merchant')->where('merchant_approved', false)->count();
                                    $newUsersCount = \App\Models\User::where('is_active', false)->count();
                                @endphp
                                <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')" class="relative text-gray-600 hover:text-purple-700 border-transparent hover:border-purple-300 font-medium">
                                    Produits
                                    @if($pendingProductsCount > 0)
                                        <span class="absolute -top-1 -right-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full shadow-sm">
                                            {{ $pendingProductsCount > 99 ? '99+' : $pendingProductsCount }}
                                        </span>
                                    @endif
                                </x-nav-link>
                                <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" class="relative text-gray-600 hover:text-purple-700 border-transparent hover:border-purple-300 font-medium">
                                    Commandes
                                    @if($pendingOrdersCount > 0)
                                        <span class="absolute -top-1 -right-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full shadow-sm">
                                            {{ $pendingOrdersCount > 99 ? '99+' : $pendingOrdersCount }}
                                        </span>
                                    @endif
                                </x-nav-link>
                                <x-nav-link :href="route('admin.merchants')" :active="request()->routeIs('admin.merchants*')" class="relative text-gray-600 hover:text-purple-700 border-transparent hover:border-purple-300 font-medium">
                                    Commerçants
                                    @if($pendingMerchantsCount > 0)
                                        <span class="absolute -top-1 -right-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full shadow-sm">
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

                    <!-- Settings Dropdown (desktop only) -->
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
                                    <span id="adminNotifBadge" class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full shadow">
                                        {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                                    </span>
                                @endif
                            </a>
                        </div>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition ease-in-out duration-150">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-purple-200 rounded-full flex items-center justify-center mr-2">
                                            <span class="text-xs font-medium text-purple-700">
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
                                <x-dropdown-link :href="route('home')" class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0 9c-5 0-9-4-9-9m9 9c5 0 9-4 9-9m-9-9v9"/>
                                    </svg>
                                    {{ __('Voir le site') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('profile.edit')" class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ __('Profile') }}
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

            <!-- Responsive Navigation Menu - Admin Sliding Panel -->
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
                        <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Administration</span>
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
                        <a href="{{ route('admin.dashboard') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.dashboard') ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('admin.analytics') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('admin.analytics') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.analytics') ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Analytics
                        </a>

                        <a href="{{ route('admin.categories.index') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('admin.categories.*') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.categories.*') ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Catégories
                        </a>

                        <a href="{{ route('admin.products.index') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('admin.products.*') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.products.*') ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Produits
                        </a>

                        <a href="{{ route('admin.orders.index') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('admin.orders.*') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.orders.*') ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Commandes
                        </a>

                        <a href="{{ route('admin.merchants') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('admin.merchants*') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.merchants*') ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Commerçants
                        </a>

                        <a href="{{ route('admin.users.index') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.users.*') ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            Utilisateurs
                        </a>

                        <a href="{{ route('admin.merchant_verification_requests') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('admin.merchant_verification_requests') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.merchant_verification_requests') ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Demandes Badge
                        </a>

                        <a href="{{ route('admin.notifications.index') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('admin.notifications.*') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                            <div class="relative mr-4">
                                <svg class="h-6 w-6 {{ request()->routeIs('admin.notifications.*') ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @php
                                    $unreadNotifications = \App\Models\AdminNotification::unread()->count();
                                @endphp
                                @if($unreadNotifications > 0)
                                    <span id="adminMobileNotifCount" class="absolute -top-1 -right-1 bg-red-600 text-white rounded-full text-xs px-1.5 py-0.5 min-w-[18px] h-[18px] flex items-center justify-center">{{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}</span>
                                @endif
                            </div>
                            Notifications
                        </a>

                        <a href="{{ route('admin.blog.index') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('admin.blog.*') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.blog.*') ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            Blog
                        </a>

                        <a href="{{ route('admin.seo.edit') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('admin.seo.*') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.seo.*') ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Référencement
                        </a>

                        <a href="{{ route('admin.settings.index') }}" class="mobile-menu-item group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('admin.settings.*') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.settings.*') ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Paramètres
                        </a>
                    </div>
                </div>

                <!-- Section utilisateur admin -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-purple-600 font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-purple-600 truncate">Administrateur</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <a href="{{ route('home') }}" class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Voir le site
                        </a>
                        
                        <a href="{{ route('profile.edit') }}" class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
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

        <!-- Navigation bottom mobile admin -->
        <x-admin-mobile-bottom-nav />
    </div>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
