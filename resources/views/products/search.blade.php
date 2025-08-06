<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <!-- Header avec recherche -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Résultats de recherche</h1>
                        <p class="text-gray-600">{{ $products->total() }} résultat(s) pour "{{ $query }}"</p>
                    </div>
                    
                    <!-- Barre de recherche -->
                    <div class="mt-4 md:mt-0 md:ml-6">
                        <form action="{{ route('products.search') }}" method="GET" class="flex">
                            <input type="text" name="q" placeholder="Rechercher un produit..." 
                                   value="{{ $query }}"
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
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
                            <div class="aspect-w-16 aspect-h-12">
                                <a href="{{ route('products.show', $product) }}">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                                             class="w-full h-48 object-cover rounded-t-lg">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
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
                                    <span class="text-xs text-gray-500">par {{ $product->user->name }}</span>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun résultat trouvé</h3>
                    <p class="mt-1 text-sm text-gray-500">Aucun produit ne correspond à votre recherche "{{ $query }}".</p>
                    <div class="mt-6">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition duration-150">
                            Voir tous les produits
                        </a>
                    </div>
                </div>
            @endif

            <!-- Suggestions -->
            @if($products->count() == 0)
                <div class="mt-8 bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Suggestions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Essayez de:</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Vérifier l'orthographe</li>
                                <li>• Utiliser des mots plus généraux</li>
                                <li>• Essayer des synonymes</li>
                                <li>• Réduire le nombre de mots</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Catégories populaires:</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($categories->take(5) as $category)
                                    <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                                       class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full hover:bg-blue-200 transition duration-150">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
