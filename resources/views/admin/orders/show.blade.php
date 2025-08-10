<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Commande') }} #{{ $order->id }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Retour à la liste
                </a>
                
                @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                    <div class="relative inline-block text-left">
                        <button type="button" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                                onclick="toggleStatusMenu()">
                            Changer le statut
                        </button>
                        <div id="status-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                            <div class="py-1">
                                @foreach(['pending' => 'En attente', 'processing' => 'En traitement', 'shipped' => 'Expédiée', 'delivered' => 'Livrée', 'cancelled' => 'Annulée'] as $status => $label)
                                    @if($status !== $order->status)
                                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $status }}">
                                            <button type="submit" 
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir changer le statut vers {{ $label }} ?')">
                                                {{ $label }}
                                            </button>
                                        </form>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Informations principales -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Détails de la commande -->
            <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Détails de la commande</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Numéro de commande</label>
                            <p class="mt-1 text-sm text-gray-900">#{{ $order->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de commande</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Statut</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
                                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                   ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($order->status === 'processing' ? 'bg-purple-100 text-purple-800' :
                                   ($order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'))) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Méthode de paiement</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $order->payment_method ?? 'Non spécifiée' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résumé financier -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Résumé financier</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Sous-total</span>
                        <span class="text-sm font-medium">FCFA {{ number_format($order->subtotal_amount ?? $order->total_amount, 2) }}</span>
                    </div>
                    @if($order->tax_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">TVA</span>
                            <span class="text-sm font-medium">FCFA {{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                    @endif
                    @if($order->shipping_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Livraison</span>
                            <span class="text-sm font-medium">FCFA {{ number_format($order->shipping_amount, 2) }}</span>
                        </div>
                    @endif
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Remise</span>
                            <span class="text-sm font-medium text-red-600">-FCFA {{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="border-t pt-4">
                        <div class="flex justify-between">
                            <span class="text-base font-medium text-gray-900">Total</span>
                            <span class="text-base font-bold text-gray-900">FCFA {{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations client -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations client</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-lg font-medium text-gray-600">
                                {{ strtoupper(substr($order->user->shop_name ?? $order->user->name, 0, 2)) }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-medium text-gray-900">{{ $order->user->shop_name ?? $order->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                        <a href="{{ route('admin.users.show', $order->user) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                            Voir le profil client
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Adresse de livraison -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Adresse de livraison</h4>
                        <div class="text-sm text-gray-600">
                            @php
                                $shipping = $order->shipping_address;
                                if (is_array($shipping)) {
                                    $shipping = collect($shipping)->filter()->implode(', ');
                                }
                            @endphp
                            @if(!empty($shipping))
                                {!! nl2br(e($shipping)) !!}
                            @else
                                <span class="text-gray-400">Non spécifiée</span>
                            @endif
                        </div>
                    </div>

                    <!-- Adresse de facturation -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Adresse de facturation</h4>
                        <div class="text-sm text-gray-600">
                            @if($order->billing_address)
                                {!! nl2br(e($order->billing_address)) !!}
                            @else
                                <span class="text-gray-400">Identique à l'adresse de livraison</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Articles commandés -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Articles commandés</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commerçant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($item->product)
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <x-product-image :product="$item->product" size="small" class="h-12 w-12" />
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                                <div class="text-sm text-gray-500">SKU: {{ $item->product->sku ?? 'N/A' }}</div>
                                            </div>
                                        @else
                                            <div class="flex-shrink-0 h-12 w-12 bg-gray-200 flex items-center justify-center rounded-lg">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 text-red-600">Produit supprimé</div>
                                                <div class="text-sm text-gray-500">SKU: N/A</div>
                                            </div>
                                        @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($item->product && $item->product->user)
                                        {{ $item->product->user->shop_name ?? $item->product->user->name }}
                                    @else
                                        Commerçant supprimé
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    FCFA {{ number_format($item->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    FCFA {{ number_format($item->price * $item->quantity, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Notes et commentaires -->
        @if($order->notes)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Notes de commande</h3>
                </div>
                <div class="p-6">
                    <div class="text-sm text-gray-600">
                        {!! nl2br(e($order->notes)) !!}
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function toggleStatusMenu() {
            const menu = document.getElementById('status-menu');
            menu.classList.toggle('hidden');
        }

        // Fermer le menu en cliquant ailleurs
        document.addEventListener('click', function(event) {
            if (!event.target.matches('[onclick="toggleStatusMenu()"]')) {
                document.getElementById('status-menu').classList.add('hidden');
            }
        });
    </script>
    @endpush
</x-admin-layout>
