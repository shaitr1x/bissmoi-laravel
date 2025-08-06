<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Mes commandes</h1>

            @if($orders->count() > 0)
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
                                            {{ number_format($order->total, 0, ',', ' ') }} FCFA
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-4">
                                <div class="space-y-3">
                                    @foreach($order->orderItems->take(3) as $item)
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                @if($item->product->image)
                                                    <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" 
                                                         class="w-12 h-12 object-cover rounded">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
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
