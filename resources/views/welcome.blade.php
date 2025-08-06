<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Bienvenue sur Bissmoi
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90">
                La marketplace qui connecte commerçants et clients
            </p>
            <div class="space-x-4">
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition">
                    Découvrir les produits
                </a>
                @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-blue-600 transition">
                        Devenir commerçant
                    </a>
                @endguest
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    @if($categories->count() > 0)
        <div class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center mb-12">Nos Catégories</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($categories as $category)
                        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="text-2xl">📦</span>
                                </div>
                                <h3 class="text-xl font-semibold mb-2">{{ $category->name }}</h3>
                                <p class="text-gray-600 mb-4">{{ $category->description }}</p>
                                <p class="text-sm text-blue-600">{{ $category->products_count }} produits</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Featured Products -->
    @if($featuredProducts->count() > 0)
        <div class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center mb-12">Produits en Vedette</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($featuredProducts as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                            <div class="h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-4xl">📱</span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->short_description, 50) }}</p>
                                <div class="flex justify-between items-center">
                                    <div>
                                        @if($product->sale_price)
                                            <span class="text-lg font-bold text-red-600">{{ number_format($product->sale_price, 2) }}€</span>
                                            <span class="text-sm text-gray-500 line-through ml-1">{{ number_format($product->price, 2) }}€</span>
                                        @else
                                            <span class="text-lg font-bold text-gray-900">{{ number_format($product->price, 2) }}€</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Latest Products -->
    @if($latestProducts->count() > 0)
        <div class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center mb-12">Derniers Produits</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($latestProducts->take(8) as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                            <div class="h-48 overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description, 80) }}</p>
                                <div class="flex justify-between items-center">
                                    <div>
                                        @if($product->sale_price)
                                            <span class="text-lg font-bold text-green-600">{{ number_format($product->sale_price, 0, ',', ' ') }} FCFA</span>
                                            <span class="text-sm text-gray-500 line-through ml-1">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                                        @else
                                            <span class="text-lg font-bold text-gray-900">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-8">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        Voir tous les produits
                        <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Features Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Pourquoi choisir Bissmoi ?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Prix compétitifs</h3>
                    <p class="text-gray-600">Des prix attractifs grâce à la vente directe entre commerçants et clients.</p>
                </div>
                <div class="text-center">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Qualité garantie</h3>
                    <p class="text-gray-600">Tous nos marchands sont vérifiés pour garantir la qualité des produits.</p>
                </div>
                <div class="text-center">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Livraison rapide</h3>
                    <p class="text-gray-600">Livraison gratuite et rapide partout avec suivi en temps réel.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">🛒 Bissmoi</h3>
                    <p class="text-gray-300">La marketplace qui connecte commerçants et clients pour une expérience d'achat unique.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liens utiles</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="{{ route('welcome') }}" class="hover:text-white">Accueil</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-white">Produits</a></li>
                        @auth
                            <li><a href="{{ route('orders.index') }}" class="hover:text-white">Mes commandes</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Pour les commerçants</h3>
                    <ul class="space-y-2 text-gray-300">
                        @guest
                            <li><a href="{{ route('register') }}" class="hover:text-white">Devenir commerçant</a></li>
                        @endguest
                        @auth
                            @if(auth()->user()->isMerchant())
                                <li><a href="{{ route('merchant.dashboard') }}" class="hover:text-white">Espace commerçant</a></li>
                            @endif
                        @endauth
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <p class="text-gray-300">
                        Email: contact@bissmoi.com<br>
                        Téléphone: +225 01 23 45 67 89
                    </p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Bissmoi. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</x-app-layout>
