<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <!-- Header avec recherche -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Catalogue produits</h1>
                        <p class="text-gray-600">Découvrez notre sélection de produits</p>
                    </div>
                    
                    <!-- Barre de recherche -->
                    <div class="mt-4 md:mt-0 md:ml-6">
                        <form action="{{ route('products.search') }}" method="GET" class="flex">
                            <input type="text" name="q" placeholder="Rechercher un produit..." 
                                   value="{{ request('q') }}"
                                   class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar filtres -->
                <div class="lg:w-1/4">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtres</h3>
                        
                        <form action="{{ route('products.index') }}" method="GET" id="filterForm">
                            <!-- Conserver la recherche -->
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            
                            <!-- Catégories -->
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Catégories</h4>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="category" value="" 
                                               {{ !request('category') ? 'checked' : '' }}
                                               class="text-blue-600 focus:ring-blue-500"
                                               onchange="document.getElementById('filterForm').submit()">
                                        <span class="ml-2 text-sm text-gray-700">Toutes</span>
                                    </label>
                                    @foreach($categories as $category)
                                        <label class="flex items-center">
                                            <input type="radio" name="category" value="{{ $category->id }}" 
                                                   {{ request('category') == $category->id ? 'checked' : '' }}
                                                   class="text-blue-600 focus:ring-blue-500"
                                                   onchange="document.getElementById('filterForm').submit()">
                                            <span class="ml-2 text-sm text-gray-700">
                                                {{ $category->name }} ({{ $category->products_count }})
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Prix -->
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Prix (FCFA)</h4>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min_price" placeholder="Min" 
                                           value="{{ request('min_price') }}"
                                           class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500"
                                           onchange="document.getElementById('filterForm').submit()">
                                    <input type="number" name="max_price" placeholder="Max" 
                                           value="{{ request('max_price') }}"
                                           class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500"
                                           onchange="document.getElementById('filterForm').submit()">
                                </div>
                            </div>

                            <!-- Ville -->
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Ville</h4>
                                <select name="city" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                        onchange="document.getElementById('filterForm').submit()">
                                    <option value="" {{ !request('city') ? 'selected' : '' }}>Toutes les villes</option>
                                    <option value="Yaoundé" {{ request('city') == 'Yaoundé' ? 'selected' : '' }}>Yaoundé</option>
                                    <option value="Douala" {{ request('city') == 'Douala' ? 'selected' : '' }}>Douala</option>
                                    <option value="Bertoua" {{ request('city') == 'Bertoua' ? 'selected' : '' }}>Bertoua</option>
                                    <option value="Garoua" {{ request('city') == 'Garoua' ? 'selected' : '' }}>Garoua</option>
                                    <option value="Ngaoundéré" {{ request('city') == 'Ngaoundéré' ? 'selected' : '' }}>Ngaoundéré</option>
                                </select>
                            </div>

                            <!-- Tri -->
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Trier par</h4>
                                <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                        onchange="document.getElementById('filterForm').submit()">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciens</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom A-Z</option>
                                </select>
                            </div>

                            <button type="button" onclick="clearFilters()" class="w-full px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                                Effacer les filtres
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Grille de produits -->
                <div class="lg:w-3/4">
                    @if($products->count() > 0)
                        <div class="flex justify-between items-center mb-6">
                            <p class="text-gray-600">{{ $products->total() }} produit(s) trouvé(s)</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
                                    <div class="aspect-w-16 aspect-h-12">
                                        <a href="{{ route('products.show', $product) }}">
                                            <x-product-image :product="$product" size="large" class="h-48 rounded-t-lg" />
                                        </a>
                                    </div>

                                    <div class="p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">
                                                <a href="{{ route('products.show', $product) }}" class="hover:text-blue-600">
                                                    {{ $product->name }}
                                                </a>
                                            </h3>
                                        </div>

                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->description }}</p>

                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                @if($product->sale_price)
                                                    <span class="text-lg font-bold text-green-600">{{ number_format($product->sale_price, 0, ',', ' ') }} FCFA</span>
                                                    <span class="text-sm line-through text-gray-500 ml-2">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                                                @else
                                                    <span class="text-lg font-bold text-gray-900">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                                                @endif
                                            </div>
                                            <span class="text-sm text-gray-500">Stock: {{ $product->stock_quantity }}</span>
                                        </div>

                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-gray-500">{{ $product->category->name }}</span>
                                            <span class="text-xs text-gray-500 flex items-center">
                                                par {{ $product->user->shop_name ?? $product->user->name }}
                                                @if($product->user->is_verified_merchant)
                                                    <span title="Marchand vérifié" class="inline-flex items-center ml-1 px-1 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                                    </span>
                                                @endif
                                            </span>
                                        </div>

                                        @auth
                                            <form action="{{ route('cart.store') }}" method="POST" class="mt-4">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150">
                                                    Ajouter au panier
                                                </button>
                                            </form>
                                        @else
                                            <div class="mt-4">
                                                <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-150">
                                                    Se connecter pour acheter
                                                </a>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun produit trouvé</h3>
                            <p class="mt-1 text-sm text-gray-500">Essayez d'ajuster vos filtres ou votre recherche.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function clearFilters() {
            window.location.href = '{{ route("products.index") }}';
        }

        // Mettre à jour le compteur de panier après ajout
        @if(session('success') && str_contains(session('success'), 'panier'))
            if (typeof window.updateCartCount === 'function') {
                window.updateCartCount();
            }
        @endif
    </script>
</x-app-layout>
