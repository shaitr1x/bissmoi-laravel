<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Mon panier</h1>

            @if($cartItems->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Liste des produits -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-900">Articles ({{ $cartItems->count() }})</h2>
                            </div>

                            <div class="divide-y divide-gray-200">
                                @foreach($cartItems as $item)
                                    <div class="p-6">
                                        <div class="flex items-center space-x-4">
                                            <!-- Image produit -->
                                            <div class="flex-shrink-0">
                                                @if($item->product->image)
                                                    <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" 
                                                         class="w-20 h-20 object-cover rounded-lg">
                                                @else
                                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Informations produit -->
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    <a href="{{ route('products.show', $item->product) }}" class="hover:text-blue-600">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </h3>
                                                <p class="text-sm text-gray-600">{{ $item->product->category->name }}</p>
                                                <p class="text-sm text-gray-500">Vendeur: {{ $item->product->user->name }}</p>
                                                
                                                <!-- Prix -->
                                                <div class="mt-2">
                                                    @if($item->product->sale_price)
                                                        <span class="text-lg font-bold text-green-600">{{ number_format($item->product->sale_price, 0, ',', ' ') }} FCFA</span>
                                                        <span class="text-sm line-through text-gray-500 ml-2">{{ number_format($item->product->price, 0, ',', ' ') }} FCFA</span>
                                                    @else
                                                        <span class="text-lg font-bold text-gray-900">{{ number_format($item->product->price, 0, ',', ' ') }} FCFA</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Quantité et actions -->
                                            <div class="flex flex-col items-end space-y-2">
                                                <!-- Quantité -->
                                                <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center space-x-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <label for="quantity_{{ $item->id }}" class="text-sm text-gray-600">Qté:</label>
                                                    <select name="quantity" id="quantity_{{ $item->id }}" 
                                                            class="px-2 py-1 border border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500"
                                                            onchange="this.form.submit()">
                                                        @for($i = 1; $i <= min($item->product->stock_quantity, 10); $i++)
                                                            <option value="{{ $i }}" {{ $item->quantity == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </form>

                                                <!-- Sous-total -->
                                                <div class="text-lg font-bold text-gray-900">
                                                    {{ number_format($item->quantity * $item->product->getCurrentPrice(), 0, ',', ' ') }} FCFA
                                                </div>

                                                <!-- Supprimer -->
                                                <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm" 
                                                            onclick="return confirm('Retirer cet article du panier ?')">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Actions du panier -->
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                                <div class="flex justify-between">
                                    <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        ← Continuer mes achats
                                    </a>
                                    <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium"
                                                onclick="return confirm('Vider complètement le panier ?')">
                                            Vider le panier
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé de commande -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Résumé de la commande</h2>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Sous-total ({{ $cartItems->sum('quantity') }} article(s)):</span>
                                    <span class="font-medium">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Livraison:</span>
                                    <span class="font-medium">Gratuite</span>
                                </div>
                                <div class="border-t pt-3">
                                    <div class="flex justify-between">
                                        <span class="text-lg font-semibold text-gray-900">Total:</span>
                                        <span class="text-lg font-bold text-gray-900">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('orders.checkout') }}" 
                               class="block w-full text-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150">
                                Passer la commande
                            </a>

                            <!-- Informations de livraison -->
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="h-5 w-5 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="text-sm text-blue-800">
                                        <p class="font-medium mb-1">Livraison gratuite</p>
                                        <p>Délai estimé: 2-5 jours ouvrés</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Panier vide -->
                <div class="text-center py-12">
                    <div class="bg-white rounded-lg shadow p-8">
                        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l-2.5 5m0 0L17 18m0 0v0a1.5 1.5 0 01-3 0v0m3 0a1.5 1.5 0 01-3 0m0 0H9m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v9.5z"/>
                        </svg>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Votre panier est vide</h2>
                        <p class="text-gray-600 mb-6">Découvrez notre sélection de produits et ajoutez vos favoris à votre panier.</p>
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Commencer mes achats
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
