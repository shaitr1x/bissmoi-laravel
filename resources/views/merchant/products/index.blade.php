<x-merchant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mes produits') }}
            </h2>
            <a href="{{ route('merchant.products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Ajouter un produit
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                            <!-- Image du produit -->
                            <div class="aspect-w-16 aspect-h-12">
                                <x-product-image :product="$product" size="large" class="h-48" />
                            </div>

                            <!-- Contenu -->
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">{{ $product->name }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                                        {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 
                                           ($product->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $product->status === 'active' ? 'Actif' : 
                                           ($product->status === 'pending' ? 'En attente' : 'Inactif') }}
                                    </span>
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
                                    <span class="text-sm text-gray-600">Stock: {{ $product->stock_quantity }}</span>
                                </div>

                                <div class="text-xs text-gray-500 mb-3">
                                    <p>Catégorie: {{ $product->category->name }}</p>
                                    <p>Créé le: {{ $product->created_at->format('d/m/Y') }}</p>
                                </div>

                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('merchant.products.show', $product) }}" class="flex-1 text-center px-3 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded hover:bg-blue-200 transition duration-150">
                                        Voir
                                    </a>
                                    <a href="{{ route('merchant.products.edit', $product) }}" class="flex-1 text-center px-3 py-2 bg-yellow-100 text-yellow-700 text-sm font-medium rounded hover:bg-yellow-200 transition duration-150">
                                        Modifier
                                    </a>
                                    <form action="{{ route('merchant.products.destroy', $product) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-700 text-sm font-medium rounded hover:bg-red-200 transition duration-150" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun produit</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par ajouter votre premier produit.</p>
                    <div class="mt-6">
                        <a href="{{ route('merchant.products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Ajouter un produit
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-merchant-layout>
