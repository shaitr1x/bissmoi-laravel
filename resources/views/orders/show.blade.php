<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-blue-600">Accueil</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('orders.index') }}" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2">Mes commandes</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-500 md:ml-2">Commande #{{ $order->id }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- En-tête de commande -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Commande #{{ $order->id }}</h1>
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
                            <p class="text-xl font-bold text-gray-900 mt-1">
                                {{ number_format($order->total, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Liste des produits -->
                        <div class="lg:col-span-2">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Produits commandés</h2>
                            
                            <div class="space-y-4">
                                @foreach($order->orderItems as $item)
                                    <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                        <div class="flex-shrink-0">
                                            @if($item->product->image)
                                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" 
                                                     class="w-16 h-16 object-cover rounded">
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-medium text-gray-900">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-gray-600">Vendeur: {{ $item->product->user->name }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $item->quantity }} × {{ number_format($item->price, 0, ',', ' ') }} FCFA
                                            </p>
                                        </div>
                                        <div class="text-lg font-semibold text-gray-900">
                                            {{ number_format($item->total, 0, ',', ' ') }} FCFA
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Résumé financier -->
                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Sous-total:</span>
                                        <span class="font-medium">{{ number_format($order->total, 0, ',', ' ') }} FCFA</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Livraison:</span>
                                        <span class="font-medium text-green-600">Gratuite</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">TVA:</span>
                                        <span class="font-medium">Incluse</span>
                                    </div>
                                    <div class="border-t pt-2">
                                        <div class="flex justify-between">
                                            <span class="text-lg font-semibold text-gray-900">Total:</span>
                                            <span class="text-lg font-bold text-gray-900">{{ number_format($order->total, 0, ',', ' ') }} FCFA</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations de livraison -->
                        <div class="lg:col-span-1">
                            <div class="space-y-6">
                                <!-- Statut de livraison -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Statut de la commande</h3>
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Commande passée</p>
                                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="w-3 h-3 {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-green-500' : 'bg-gray-300' }} rounded-full"></div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">En préparation</p>
                                                @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                                    <p class="text-xs text-gray-500">Commande en cours de préparation</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="w-3 h-3 {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-green-500' : 'bg-gray-300' }} rounded-full"></div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Expédiée</p>
                                                @if(in_array($order->status, ['shipped', 'delivered']))
                                                    <p class="text-xs text-gray-500">En cours de livraison</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="w-3 h-3 {{ $order->status === 'delivered' ? 'bg-green-500' : 'bg-gray-300' }} rounded-full"></div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Livrée</p>
                                                @if($order->status === 'delivered')
                                                    <p class="text-xs text-gray-500">Commande livrée avec succès</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Adresse de livraison -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Livraison</h3>
                                    <div class="text-sm text-gray-700">
                                        <p class="font-medium mb-1">Adresse:</p>
                                        <p class="mb-3">{{ $order->delivery_address }}</p>
                                        <p class="font-medium mb-1">Téléphone:</p>
                                        <p class="mb-3">{{ $order->phone }}</p>
                                        @if($order->notes)
                                            <p class="font-medium mb-1">Notes:</p>
                                            <p>{{ $order->notes }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Mode de paiement -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Paiement</h3>
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="h-5 w-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span>Paiement à la livraison (espèces)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between">
                        <a href="{{ route('orders.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition duration-150">
                            ← Retour à mes commandes
                        </a>
                        
                        @if($order->status === 'delivered')
                            <div class="space-x-2">
                                <a href="{{ route('products.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition duration-150">
                                    Commander à nouveau
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
