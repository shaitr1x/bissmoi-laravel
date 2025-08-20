<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Mes commandes</h1>

            @if($orders->count() > 0)
                <!-- Filtres, tri, recherche -->
                <form method="GET" class="mb-6 flex flex-wrap gap-2 items-end bg-white p-4 rounded-lg shadow">
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-700">Statut</label>
                        <select name="status" id="status" class="form-select mt-1 block w-full">
                            <option value="all"{{ request('status','all')=='all' ? ' selected' : '' }}>Tous</option>
                            <option value="pending"{{ request('status')=='pending' ? ' selected' : '' }}>En attente</option>
                            <option value="processing"{{ request('status')=='processing' ? ' selected' : '' }}>En préparation</option>
                            <option value="shipped"{{ request('status')=='shipped' ? ' selected' : '' }}>Expédiée</option>
                            <option value="delivered"{{ request('status')=='delivered' ? ' selected' : '' }}>Livrée</option>
                            <option value="cancelled"{{ request('status')=='cancelled' ? ' selected' : '' }}>Annulée</option>
                        </select>
                    </div>
                    <div>
                        <label for="sort" class="block text-xs font-medium text-gray-700">Trier par</label>
                        <select name="sort" id="sort" class="form-select mt-1 block w-full">
                            <option value="created_at"{{ request('sort','created_at')=='created_at' ? ' selected' : '' }}>Date</option>
                            <option value="total_amount"{{ request('sort')=='total_amount' ? ' selected' : '' }}>Montant</option>
                        </select>
                    </div>
                    <div>
                        <label for="direction" class="block text-xs font-medium text-gray-700">Ordre</label>
                        <select name="direction" id="direction" class="form-select mt-1 block w-full">
                            <option value="desc"{{ request('direction','desc')=='desc' ? ' selected' : '' }}>Décroissant</option>
                            <option value="asc"{{ request('direction')=='asc' ? ' selected' : '' }}>Croissant</option>
                        </select>
                    </div>
                    <div>
                        <label for="search" class="block text-xs font-medium text-gray-700">Recherche</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-input mt-1 block w-full" placeholder="N° commande...">
                    </div>
                    <div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">Filtrer</button>
                    </div>
                </form>

                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Commande #{{ $order->id }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                                ($order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : 
                                                 ($order->status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))) }}">
                                            @switch($order->status)
                                                @case('pending')
                                                    En attente
                                                    @break
                                                @case('processing')
                                                    En préparation
                                                    @break
                                                @case('shipped')
                                                    Expédiée
                                                    @break
                                                @case('delivered')
                                                    Livrée
                                                    @break
                                                @case('cancelled')
                                                    Annulée
                                                    @break
                                                @default
                                                    {{ ucfirst($order->status) }}
                                            @endswitch
                                        </span>
                                        <p class="text-lg font-bold text-gray-900 mt-1">
                                            {{ number_format($order->total_amount ?? $order->total, 0, ',', ' ') }} FCFA
                                        </p>
                                        @if(in_array($order->status, ['pending','processing']))
                                            <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Annuler cette commande ?');">
                                                @csrf
                                                <button type="submit" class="mt-2 px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">Annuler</button>
                                            </form>
                                        @endif
                                        @if(in_array($order->status, ['delivered','cancelled']))
                                            <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('Supprimer définitivement cette commande de l\'historique ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="mt-2 px-3 py-1 bg-gray-400 text-white text-xs rounded hover:bg-gray-600">Supprimer</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-4">
                                <div class="space-y-3">
                                    @foreach($order->orderItems->take(3) as $item)
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                @if($item->product)
                                                    <x-product-image :product="$item->product" size="small" class="w-12 h-12" />
                                                @else
                                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">
                                                    @if($item->product)
                                                        {{ $item->product->name }}
                                                    @else
                                                        <span class="text-gray-500 italic">Produit supprimé</span>
                                                    @endif
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Quantité: {{ $item->quantity }} × {{ number_format($item->price, 0, ',', ' ') }} FCFA
                                                </p>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ number_format($item->total, 0, ',', ' ') }} FCFA
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($order->orderItems->count() > 3)
                                        <p class="text-sm text-gray-500 text-center pt-2">
                                            et {{ $order->orderItems->count() - 3 }} autre(s) produit(s)...
                                        </p>
                                    @endif
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <div class="text-sm text-gray-600">
                                            <p><strong>Livraison:</strong> {{ $order->delivery_address }}</p>
                                            <p><strong>Téléphone:</strong> {{ $order->phone }}</p>
                                        </div>
                                        <a href="{{ route('orders.show', $order) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition duration-150">
                                            Voir détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="bg-white rounded-lg shadow p-8">
                        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Aucune commande</h2>
                        <p class="text-gray-600 mb-6">Vous n'avez pas encore passé de commande.</p>
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Découvrir les produits
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
