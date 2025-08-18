<!-- Navigation bottom fixe pour mobile - Marchand -->
<div class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 sm:hidden">
    <div class="flex items-center justify-around py-2">
        <!-- Dashboard -->
        <a href="{{ route('merchant.dashboard') }}" class="flex flex-col items-center py-2 px-3 {{ request()->routeIs('merchant.dashboard') ? 'text-green-600' : 'text-gray-500' }} transition-colors duration-200">
            <div class="relative">
                <svg class="h-6 w-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                @if(request()->routeIs('merchant.dashboard'))
                    <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-green-600 rounded-full"></div>
                @endif
            </div>
            <span class="text-xs font-medium">Dashboard</span>
        </a>

        <!-- Mes Produits -->
        <a href="{{ route('merchant.products.index') }}" class="flex flex-col items-center py-2 px-3 {{ request()->routeIs('merchant.products.*') ? 'text-green-600' : 'text-gray-500' }} transition-colors duration-200">
            <div class="relative">
                <svg class="h-6 w-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                @if(request()->routeIs('merchant.products.*'))
                    <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-green-600 rounded-full"></div>
                @endif
            </div>
            <span class="text-xs font-medium">Produits</span>
        </a>

        <!-- Commandes -->
        <a href="{{ route('merchant.orders') }}" class="flex flex-col items-center py-2 px-3 {{ request()->routeIs('merchant.orders') ? 'text-green-600' : 'text-gray-500' }} transition-colors duration-200">
            <div class="relative">
                <svg class="h-6 w-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                @if(request()->routeIs('merchant.orders'))
                    <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-green-600 rounded-full"></div>
                @endif
            </div>
            <span class="text-xs font-medium">Commandes</span>
        </a>

        <!-- Badge -->
        <a href="{{ route('merchant.verification.request.form') }}" class="flex flex-col items-center py-2 px-3 {{ request()->routeIs('merchant.verification.request.form') ? 'text-green-600' : 'text-gray-500' }} transition-colors duration-200">
            <div class="relative">
                <svg class="h-6 w-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                @if(request()->routeIs('merchant.verification.request.form'))
                    <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-green-600 rounded-full"></div>
                @endif
            </div>
            <span class="text-xs font-medium">Badge</span>
        </a>

        <!-- Menu Marchand -->
        <button x-data @click="$dispatch('toggle-merchant-menu')" class="flex flex-col items-center py-2 px-3 text-gray-500 transition-colors duration-200">
            <div class="relative">
                <div class="w-6 h-6 mb-1 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <span class="text-xs font-medium">Menu</span>
        </button>
    </div>
</div>

<!-- Menu marchand mobile -->
<div x-data="{ merchantMenuOpen: false }" 
     @toggle-merchant-menu.window="merchantMenuOpen = !merchantMenuOpen"
     x-show="merchantMenuOpen" 
     x-transition:enter="transition ease-out duration-200" 
     x-transition:enter-start="opacity-0 transform translate-y-full" 
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-150" 
     x-transition:leave-start="opacity-100 transform translate-y-0" 
     x-transition:leave-end="opacity-0 transform translate-y-full"
     class="fixed bottom-16 left-0 right-0 z-50 bg-white border-t border-gray-200 shadow-lg sm:hidden">
    
    <div class="p-4">
        <!-- Info marchand -->
        <div class="flex items-center space-x-3 mb-4 pb-4 border-b border-gray-200">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <span class="text-green-600 font-semibold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-base font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                <p class="text-sm text-green-600 truncate">Commerçant</p>
            </div>
        </div>

        <!-- Menu items marchand -->
        <div class="space-y-3">
            <a href="{{ route('home') }}" class="flex items-center px-3 py-2 text-base text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" @click="merchantMenuOpen = false">
                <svg class="mr-3 h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Voir le Site
            </a>

            <a href="{{ route('merchant.profile') }}" class="flex items-center px-3 py-2 text-base text-gray-700 hover:bg-gray-50 rounded-lg transition-colors" @click="merchantMenuOpen = false">
                <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Mon Profil
            </a>

            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit" class="flex items-center w-full px-3 py-2 text-base text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    <svg class="mr-3 h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Se Déconnecter
                </button>
            </form>
        </div>
    </div>

    <!-- Close button -->
    <button @click="merchantMenuOpen = false" class="absolute top-2 right-2 p-2 text-gray-400 hover:text-gray-600 transition-colors">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<!-- Overlay -->
<div x-data="{ merchantMenuOpen: false }" 
     @toggle-merchant-menu.window="merchantMenuOpen = !merchantMenuOpen"
     x-show="merchantMenuOpen" 
     x-transition:enter="transition-opacity ease-out duration-200" 
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in duration-150" 
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0"
     @click="merchantMenuOpen = false"
     class="fixed inset-0 z-40 bg-black bg-opacity-25 sm:hidden mobile-menu-overlay"></div>

<!-- Padding bottom pour éviter que le contenu soit masqué par la navbar fixe -->
<div class="h-16 sm:hidden"></div>
