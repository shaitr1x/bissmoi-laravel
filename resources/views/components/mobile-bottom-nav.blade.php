<!-- Navigation bottom fixe pour mobile -->
<div class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 sm:hidden">
    <div class="flex items-center justify-around py-2">
        <!-- Accueil -->
        <a href="{{ route('welcome') }}" class="flex flex-col items-center py-2 px-3 {{ request()->routeIs('welcome') ? 'text-blue-600' : 'text-gray-500' }} transition-colors duration-200">
            <div class="relative">
                <svg class="h-6 w-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                @if(request()->routeIs('welcome'))
                    <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                @endif
            </div>
            <span class="text-xs font-medium">Accueil</span>
        </a>

        <!-- Produits -->
        <a href="{{ route('products.index') }}" class="flex flex-col items-center py-2 px-3 {{ request()->routeIs('products.*') ? 'text-blue-600' : 'text-gray-500' }} transition-colors duration-200">
            <div class="relative">
                <svg class="h-6 w-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                @if(request()->routeIs('products.*'))
                    <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                @endif
            </div>
            <span class="text-xs font-medium">Produits</span>
        </a>

        @auth
            <!-- Panier -->
            <a href="{{ route('cart.index') }}" class="flex flex-col items-center py-2 px-3 {{ request()->routeIs('cart.*') ? 'text-blue-600' : 'text-gray-500' }} transition-colors duration-200">
                <div class="relative">
                    <svg class="h-6 w-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l-2.5 5m0 0L17 18" />
                    </svg>
                    <span id="bottomCartCount" class="absolute -top-1 -right-1 bg-blue-600 text-white rounded-full text-xs px-1.5 py-0.5 hidden min-w-[18px] h-[18px] flex items-center justify-center"></span>
                    @if(request()->routeIs('cart.*'))
                        <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                    @endif
                </div>
                <span class="text-xs font-medium">Panier</span>
            </a>

            <!-- Notifications -->
            <a href="{{ route('notifications.client') }}" class="flex flex-col items-center py-2 px-3 {{ request()->routeIs('notifications.*') ? 'text-blue-600' : 'text-gray-500' }} transition-colors duration-200">
                <div class="relative">
                    <svg class="h-6 w-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span id="bottomNotifCount" class="absolute -top-1 -right-1 bg-red-600 text-white rounded-full text-xs px-1.5 py-0.5 hidden min-w-[18px] h-[18px] flex items-center justify-center"></span>
                    @if(request()->routeIs('notifications.*'))
                        <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                    @endif
                </div>
                <span class="text-xs font-medium">Notifications</span>
            </a>

            <!-- Profil/Menu -->
            <button x-data @click="$dispatch('toggle-profile-menu')" class="flex flex-col items-center py-2 px-3 text-gray-500 transition-colors duration-200">
                <div class="relative">
                    <div class="w-6 h-6 mb-1 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold text-xs">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                    </div>
                </div>
                <span class="text-xs font-medium">Profil</span>
            </button>
        @else
            <!-- Se connecter -->
            <a href="{{ route('login') }}" class="flex flex-col items-center py-2 px-3 {{ request()->routeIs('login') ? 'text-blue-600' : 'text-gray-500' }} transition-colors duration-200">
                <div class="relative">
                    <svg class="h-6 w-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1" />
                    </svg>
                    @if(request()->routeIs('login'))
                        <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                    @endif
                </div>
                <span class="text-xs font-medium">Connexion</span>
            </a>

            <!-- S'inscrire -->
            <a href="{{ route('register') }}" class="flex flex-col items-center py-2 px-3 {{ request()->routeIs('register') ? 'text-blue-600' : 'text-gray-500' }} transition-colors duration-200">
                <div class="relative">
                    <svg class="h-6 w-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    @if(request()->routeIs('register'))
                        <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                    @endif
                </div>
                <span class="text-xs font-medium">S'inscrire</span>
            </a>
        @endauth
    </div>
</div>

@auth
<!-- Menu profil mobile -->
<div x-data="{ profileOpen: false }" 
     @toggle-profile-menu.window="profileOpen = !profileOpen"
     x-show="profileOpen" 
     x-transition:enter="transition ease-out duration-200" 
     x-transition:enter-start="opacity-0 transform translate-y-full" 
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-150" 
     x-transition:leave-start="opacity-100 transform translate-y-0" 
     x-transition:leave-end="opacity-0 transform translate-y-full"
     class="fixed bottom-16 left-0 right-0 z-50 bg-white border-t border-gray-200 shadow-lg sm:hidden">
    
    <div class="p-4">
        <!-- Info utilisateur -->
        <div class="flex items-center space-x-3 mb-4 pb-4 border-b border-gray-200">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="text-blue-600 font-semibold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-base font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <!-- Menu items -->
        <div class="space-y-3">
            <a href="{{ route('orders.index') }}" class="flex items-center px-3 py-2 text-base text-gray-700 hover:bg-gray-50 rounded-lg transition-colors" @click="profileOpen = false">
                <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Mes Commandes
            </a>

            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-base text-purple-700 hover:bg-purple-50 rounded-lg transition-colors" @click="profileOpen = false">
                    <svg class="mr-3 h-5 w-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Administration
                </a>
            @elseif(Auth::user()->isMerchant())
                <a href="{{ route('merchant.dashboard') }}" class="flex items-center px-3 py-2 text-base text-green-700 hover:bg-green-50 rounded-lg transition-colors" @click="profileOpen = false">
                    <svg class="mr-3 h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Mon Espace Vendeur
                </a>
            @endif

            <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 text-base text-gray-700 hover:bg-gray-50 rounded-lg transition-colors" @click="profileOpen = false">
                <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Mon Profil
            </a>

            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit" class="flex items-center w-full px-3 py-2 text-base text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    <svg class="mr-3 h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1" />
                    </svg>
                    Se Déconnecter
                </button>
            </form>
        </div>
    </div>

    <!-- Close button -->
    <button @click="profileOpen = false" class="absolute top-2 right-2 p-2 text-gray-400 hover:text-gray-600 transition-colors">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<!-- Overlay -->
<div x-data="{ profileOpen: false }" 
     @toggle-profile-menu.window="profileOpen = !profileOpen"
     x-show="profileOpen" 
     x-transition:enter="transition-opacity ease-out duration-200" 
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in duration-150" 
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0"
     @click="profileOpen = false"
     class="fixed inset-0 z-40 bg-black bg-opacity-25 sm:hidden"></div>
@endauth

<!-- Padding bottom pour éviter que le contenu soit masqué par la navbar fixe -->
<div class="h-16 sm:hidden"></div>
