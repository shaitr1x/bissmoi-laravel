
<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des produits') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar filtres -->
                <div class="lg:w-1/4">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtres</h3>
                        <form action="" method="GET" id="filterForm">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom du produit..." class="mb-4 w-full border rounded px-3 py-2" />
                            <input type="text" name="merchant" value="{{ request('merchant') }}" placeholder="Nom du commerçant..." class="mb-4 w-full border rounded px-3 py-2" />
                            <input type="text" name="shop_name" value="{{ request('shop_name') }}" placeholder="Nom de la boutique..." class="mb-4 w-full border rounded px-3 py-2" />
                            <!-- Catégories -->
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Catégorie</h4>
                                <select name="category" class="w-full border rounded px-3 py-2" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">Toutes</option>
                                    @foreach(App\Models\Category::orderBy('name')->get() as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Prix -->
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Prix (FCFA)</h4>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" class="px-3 py-2 border rounded-md text-sm" onchange="document.getElementById('filterForm').submit()">
                                    <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" class="px-3 py-2 border rounded-md text-sm" onchange="document.getElementById('filterForm').submit()">
                                </div>
                            </div>
                            <!-- Statut -->
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Statut</h4>
                                <select name="status" class="w-full border rounded px-3 py-2" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">Tous</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                            <!-- Tri -->
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Trier par</h4>
                                <select name="sort" class="w-full border rounded px-3 py-2" onchange="document.getElementById('filterForm').submit()">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciens</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom A-Z</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700">Filtrer</button>
                            <a href="{{ route('admin.products.index') }}" class="block mt-2 text-center text-gray-500 text-xs underline">Effacer les filtres</a>
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
                                <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 relative">
                                    <div class="aspect-w-16 aspect-h-12">
                                        <a href="{{ route('admin.products.show', $product) }}">
                                            <x-product-image :product="$product" size="large" class="h-48 rounded-t-lg" />
                                        </a>
                                    </div>
                                    <div class="p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">
                                                <a href="{{ route('admin.products.show', $product) }}" class="hover:text-blue-600">
                                                    {{ $product->name }}
                                                </a>
                                            </h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                @if($product->status === 'active') bg-green-100 text-green-800
                                                @elseif($product->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                @if($product->status === 'active') Actif
                                                @elseif($product->status === 'pending') En attente
                                                @elseif($product->status === 'inactive') Inactif
                                                @else {{ ucfirst($product->status) }}
                                                @endif
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
                                            <span class="text-sm text-gray-500">Stock: {{ $product->stock_quantity }}</span>
                                        </div>
                                        <div class="flex justify-between items-center mb-2">
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
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <a href="{{ route('admin.products.show', $product) }}" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Détails</a>
                                            @if($product->status === 'pending')
                                                <form action="{{ route('admin.products.update', $product) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="active">
                                                    <button type="submit" class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200">Approuver</button>
                                                </form>
                                                <form action="{{ route('admin.products.update', $product) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="inactive">
                                                    <button type="submit" class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200">Rejeter</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200">Supprimer</button>
                                            </form>
                                        </div>
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
</x-admin-layout>
