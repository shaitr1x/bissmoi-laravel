<x-merchant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes Commandes') }}
        </h2>
    </x-slot>

<div class="py-12">
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mes Commandes</h1>
        <p class="text-gray-600 mt-2">Gérez toutes les commandes de vos produits</p>
    </div>

    <!-- Filtres -->
    <form method="GET" action="" class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher par numéro de commande</label>
                <input type="text" name="search" id="search" placeholder="Numéro de commande..." value="{{ request('search') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex-1 min-w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="status" id="status-filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="pending" @if(request('status')=='pending') selected @endif>En attente</option>
                    <option value="processing" @if(request('status')=='processing') selected @endif>En cours</option>
                    <option value="shipped" @if(request('status')=='shipped') selected @endif>Expédiée</option>
                    <option value="delivered" @if(request('status')=='delivered') selected @endif>Livrée</option>
                    <option value="cancelled" @if(request('status')=='cancelled') selected @endif>Annulée</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Filtrer
                </button>
            </div>
        </div>
    </form>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Commandes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $orders->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">En attente</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $orders->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Livrées</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $orders->where('status', 'delivered')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Revenus Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des commandes -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Liste des Commandes</h2>
        </div>

        @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commande</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produits</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                            @if($order->delivery_address)
                                <div class="text-xs text-gray-700 mt-1"><span class="font-semibold">Adresse :</span> {{ $order->delivery_address }}</div>
                            @endif
                            @if($order->phone)
                                <div class="text-xs text-gray-700"><span class="font-semibold">Tél :</span> {{ $order->phone }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($order->items->where('product.user_id', auth()->id()) as $item)
                                <div class="flex items-center space-x-2 bg-gray-100 rounded-full px-3 py-1">
                                    <x-product-image :product="$item->product" size="xs" class="rounded-full" /></div>
                                    <span class="text-xs text-gray-700">{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ number_format($order->items->where('product.user_id', auth()->id())->sum(function($item) { return $item->price * $item->quantity; }), 2, ',', ' ') }} FCFA
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @switch($order->status)
                                    @case('pending') En attente @break
                                    @case('processing') En cours @break
                                    @case('shipped') Expédiée @break
                                    @case('delivered') Livrée @break
                                    @case('cancelled') Annulée @break
                                    @default {{ ucfirst($order->status) }}
                                @endswitch
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="viewOrder({{ $order->id }})" 
                                    class="text-blue-600 hover:text-blue-900 transition-colors">
                                Voir
                            </button>
                            @if($order->status === 'pending')
                                <form action="{{ route('merchant.orders.validate', $order) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="ml-2 text-green-600 hover:text-green-900 transition-colors">Valider</button>
                                </form>
                                <form action="{{ route('merchant.orders.cancel', $order) }}" method="POST" class="inline" onsubmit="return confirm('Annuler cette commande ?');">
                                    @csrf
                                    <button type="submit" class="ml-2 text-red-600 hover:text-red-900 transition-colors">Annuler</button>
                                </form>
                            @elseif($order->status === 'processing')
                                <form action="{{ route('merchant.orders.ship', $order) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="ml-2 text-purple-600 hover:text-purple-900 transition-colors">Expédier</button>
                                </form>
                                <form action="{{ route('merchant.orders.cancel', $order) }}" method="POST" class="inline" onsubmit="return confirm('Annuler cette commande ?');">
                                    @csrf
                                    <button type="submit" class="ml-2 text-red-600 hover:text-red-900 transition-colors">Annuler</button>
                                </form>
                            @elseif($order->status === 'shipped')
                                <form action="{{ route('merchant.orders.deliver', $order) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="ml-2 text-green-700 hover:text-green-900 transition-colors">Livrer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
        @endif

        @else
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune commande</h3>
            <p class="mt-1 text-sm text-gray-500">Vous n'avez pas encore reçu de commandes pour vos produits.</p>
        </div>

    </div>
</div>

        @endif
    </div>
</div>

<!-- Modal pour voir les détails de la commande -->
<div id="orderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex justify-center items-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Détails de la commande</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div id="orderDetails" class="p-6">
                <!-- Le contenu sera injecté ici via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
function viewOrder(orderId) {
    document.getElementById('orderModal').classList.remove('hidden');
    document.getElementById('orderDetails').innerHTML = '<p>Chargement des détails de la commande #' + orderId + '...</p>';
    fetch(`/merchant/orders/${orderId}/details`)
        .then(response => {
            if (!response.ok) throw new Error('Erreur lors du chargement');
            return response.json();
        })
        .then(data => {
            document.getElementById('orderDetails').innerHTML = data.html;
        })
        .catch(() => {
            document.getElementById('orderDetails').innerHTML = '<p class="text-red-600">Erreur lors du chargement des détails.</p>';
        });
}

function closeModal() {
    document.getElementById('orderModal').classList.add('hidden');
}

// Fermer le modal en cliquant en dehors
document.getElementById('orderModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
</x-merchant-layout>
