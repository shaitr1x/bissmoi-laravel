<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Finaliser ma commande</h1>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Formulaire de commande -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations de livraison</h2>

                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <label for="delivery_address" class="block text-sm font-medium text-gray-700">Adresse de livraison</label>
                                <textarea name="delivery_address" id="delivery_address" rows="3" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                          required placeholder="Votre adresse complète de livraison">{{ old('delivery_address') }}</textarea>
                                @error('delivery_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Numéro de téléphone</label>
                                <input type="tel" name="phone" id="phone" 
                                       value="{{ old('phone', Auth::user()->merchant_phone) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                       required placeholder="Votre numéro de téléphone">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes pour la livraison (optionnel)</label>
                                <textarea name="notes" id="notes" rows="2" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                          placeholder="Instructions spéciales pour la livraison">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Mode de paiement -->
                        <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-yellow-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-sm text-yellow-800">
                                    <p class="font-medium mb-1">Mode de paiement</p>
                                    <p>Paiement à la livraison (espèces uniquement)</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex space-x-4">
                            <a href="{{ route('cart.index') }}" 
                               class="flex-1 text-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-150">
                                Retour au panier
                            </a>
                            <button type="submit" 
                                    class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150">
                                Confirmer la commande
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Résumé de commande -->
                <div class="bg-white rounded-lg shadow p-6 h-fit sticky top-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Résumé de votre commande</h2>

                    <div class="space-y-4 mb-6">
                        @foreach($cartItems as $item)
                            <div class="flex items-center space-x-3">
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
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name }}</p>
                                    <p class="text-sm text-gray-500">Qté: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ number_format($item->quantity * $item->product->getCurrentPrice(), 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Sous-total:</span>
                            <span class="font-medium">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
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
                                <span class="text-lg font-bold text-gray-900">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    </div>

                    <!-- Informations de livraison -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Informations de livraison</p>
                                <ul class="space-y-1">
                                    <li>• Livraison gratuite</li>
                                    <li>• Délai: 2-5 jours ouvrés</li>
                                    <li>• Paiement à la livraison</li>
                                    <li>• Suivi par SMS</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
