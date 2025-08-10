<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Commandes') }}
            </h2>
            
            <!-- Filtres et recherche -->
            <div class="flex space-x-3">
                <form method="GET" class="flex space-x-2">
                    <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>En traitement</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Expédiées</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Livrées</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulées</option>
                    </select>
                    
                    <input type="text" name="search" placeholder="Rechercher client..." value="{{ request('search') }}" 
                           class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Filtrer
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <!-- Statistiques rapides -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800">Total</p>
                            <p class="text-lg font-semibold text-blue-600">{{ $orders->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-500 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-800">En attente</p>
                            <p class="text-lg font-semibold text-yellow-600">{{ $orders->where('status', 'pending')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-500 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-purple-800">En traitement</p>
                            <p class="text-lg font-semibold text-purple-600">{{ $orders->where('status', 'processing')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800">Expédiées</p>
                            <p class="text-lg font-semibold text-blue-600">{{ $orders->where('status', 'shipped')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-500 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">Livrées</p>
                            <p class="text-lg font-semibold text-green-600">{{ $orders->where('status', 'delivered')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des commandes -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Commande
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Client
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Articles
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Montant
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->order_number ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-xs font-medium text-gray-600">
                                                    {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->items->count() }} articles</div>
                                    <div class="text-sm text-gray-500">{{ $order->items->sum('quantity') }} produits</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">FCFA {{ number_format($order->total_amount, 2) }}</div>
                                    @if($order->discount_amount > 0)
                                        <div class="text-sm text-gray-500">-FCFA {{ number_format($order->discount_amount, 2) }} remise</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                           ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($order->status === 'processing' ? 'bg-purple-100 text-purple-800' :
                                           ($order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'))) }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->format('d/m/Y') }}
                                    <div class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           class="text-blue-600 hover:text-blue-900">Voir</a>
                                        
                                        @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                                            <div class="relative inline-block text-left">
                                                <button type="button" 
                                                        class="text-indigo-600 hover:text-indigo-900 focus:outline-none"
                                                        onclick="toggleStatusMenu({{ $order->id }})">
                                                    Changer statut
                                                </button>
                                                <div id="status-menu-{{ $order->id }}" 
                                                     class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                                    <div class="py-1">
                                                        @foreach(['pending' => 'En attente', 'processing' => 'En traitement', 'shipped' => 'Expédiée', 'delivered' => 'Livrée', 'cancelled' => 'Annulée'] as $status => $label)
                                                            @if($status !== $order->status)
                                                                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="status" value="{{ $status }}">
                                                                    <button type="submit" 
                                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleStatusMenu(orderId) {
            const menu = document.getElementById(`status-menu-${orderId}`);
            // Fermer tous les autres menus
            document.querySelectorAll('[id^="status-menu-"]').forEach(m => {
                if (m.id !== `status-menu-${orderId}`) {
                    m.classList.add('hidden');
                }
            });
            // Toggle le menu actuel
            menu.classList.toggle('hidden');
        }

        // Fermer les menus en cliquant ailleurs
        document.addEventListener('click', function(event) {
            if (!event.target.matches('[onclick*="toggleStatusMenu"]')) {
                document.querySelectorAll('[id^="status-menu-"]').forEach(m => {
                    m.classList.add('hidden');
                });
            }
        });
    </script>
    @endpush
</x-admin-layout>
