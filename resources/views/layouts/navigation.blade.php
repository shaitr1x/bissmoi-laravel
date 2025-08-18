<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}">
                        <img src="{{ asset('images/logo-bissmoi.svg') }}" alt="BISSMOI" class="block h-9 w-auto" style="max-height: 48px;">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                        {{ __('Accueil') }}
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        {{ __('Produits') }}
                    </x-nav-link>
                    @auth
                        @if(Auth::user()->isAdmin())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                                {{ __('Administration') }}
                            </x-nav-link>
                        @elseif(Auth::user()->isMerchant())
                            <x-nav-link :href="route('merchant.dashboard')" :active="request()->routeIs('merchant.*')">
                                {{ __('Mon espace') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Cart and notifications (desktop) -->
            <div class="hidden sm:flex items-center sm:ml-6 space-x-4">
                @auth
                    <!-- Panier desktop -->
                    <a href="{{ route('cart.index') }}" class="relative text-gray-500 hover:text-gray-700 transition duration-150 flex items-center" aria-label="Mon panier">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l-2.5 5m0 0L17 18m0 0v0a1.5 1.5 0 01-3 0v0m3 0a1.5 1.5 0 01-3 0m0 0H9m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v9.5z"/>
                        </svg>
                        <span id="cartCount" class="absolute -top-2 -right-2 bg-blue-600 text-white rounded-full text-xs px-1.5 py-0.5 hidden"></span>
                    </a>
                    
                    <!-- Notifications desktop -->
                    <div class="relative">
                        <a href="{{ route('notifications.client') }}" class="relative text-gray-500 hover:text-gray-700 transition duration-150 focus:outline-none" aria-label="Notifications">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span id="notifBadge" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full text-xs px-1.5 py-0.5 hidden"></span>
                        </a>
                    </div>
                @endauth

                @auth
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
                            <x-dropdown-link :href="route('orders.index')">
                                {{ __('Mes commandes') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">
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
                @else
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Se connecter</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">S'inscrire</a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="relative inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span id="burgerNotifDot" class="absolute top-1 right-1 w-2 h-2 bg-red-600 rounded-full hidden"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu - Sliding Panel -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="transform -translate-x-full" 
         x-transition:enter-end="transform translate-x-0"
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="transform translate-x-0" 
         x-transition:leave-end="transform -translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-80 bg-white shadow-xl sm:hidden">
        
        <!-- Header du menu -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo-bissmoi.svg') }}" alt="BISSMOI" class="h-8 w-auto">
                <span class="text-lg font-semibold text-gray-900">Menu</span>
            </div>
            <button @click="open = false" class="p-2 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation principale -->
        <div class="py-4">
            <div class="space-y-1 px-4">
                <a href="{{ route('welcome') }}" class="group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('welcome') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('welcome') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Accueil
                </a>

                <a href="{{ route('products.index') }}" class="group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('products.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('products.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Produits
                </a>

                @auth
                    <a href="{{ route('cart.index') }}" class="group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('cart.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                        <div class="relative mr-4">
                            <svg class="h-6 w-6 {{ request()->routeIs('cart.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l-2.5 5m0 0L17 18" />
                            </svg>
                            <span id="mobileCartCount" class="absolute -top-1 -right-1 bg-blue-600 text-white rounded-full text-xs px-1.5 py-0.5 hidden"></span>
                        </div>
                        Mon Panier
                    </a>

                    <a href="{{ route('notifications.client') }}" id="mobileNotifLink" class="group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('notifications.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                        <div class="relative mr-4">
                            <svg class="h-6 w-6 {{ request()->routeIs('notifications.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span id="mobileNotifCount" class="absolute -top-1 -right-1 bg-red-600 text-white rounded-full text-xs px-1.5 py-0.5 hidden"></span>
                        </div>
                        Notifications
                    </a>

                    <a href="{{ route('orders.index') }}" class="group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('orders.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                        <svg class="mr-4 h-6 w-6 {{ request()->routeIs('orders.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Mes Commandes
                    </a>

                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('admin.*') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.*') ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Administration
                        </a>
                    @elseif(Auth::user()->isMerchant())
                        <a href="{{ route('merchant.dashboard') }}" class="group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('merchant.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('merchant.*') ? 'text-green-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Mon Espace Vendeur
                        </a>
                    @endif
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('login') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                        <svg class="mr-4 h-6 w-6 {{ request()->routeIs('login') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Se Connecter
                    </a>

                    <a href="{{ route('register') }}" class="group flex items-center px-4 py-3 text-base font-medium rounded-xl {{ request()->routeIs('register') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                        <svg class="mr-4 h-6 w-6 {{ request()->routeIs('register') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        S'Inscrire
                    </a>
                @endguest
            </div>
        </div>

        <!-- Section utilisateur -->
        @auth
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                
                <div class="space-y-2">
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
        @endauth
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
         class="fixed inset-0 z-40 bg-black bg-opacity-25 sm:hidden"></div>
</nav>
