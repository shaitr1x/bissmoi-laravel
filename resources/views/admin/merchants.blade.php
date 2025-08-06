<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des commerçants') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="mb-4">
                <div class="flex space-x-4">
                    <div class="bg-blue-100 rounded-lg p-4 flex-1">
                        <h3 class="text-lg font-semibold text-blue-800">Total commerçants</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ $merchants->total() }}</p>
                    </div>
                    <div class="bg-green-100 rounded-lg p-4 flex-1">
                        <h3 class="text-lg font-semibold text-green-800">Approuvés</h3>
                        <p class="text-2xl font-bold text-green-600">{{ $merchants->where('merchant_approved', true)->count() }}</p>
                    </div>
                    <div class="bg-yellow-100 rounded-lg p-4 flex-1">
                        <h3 class="text-lg font-semibold text-yellow-800">En attente</h3>
                        <p class="text-2xl font-bold text-yellow-600">{{ $merchants->where('merchant_approved', false)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Commerçant
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produits
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Inscription
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($merchants as $merchant)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-600">
                                                    {{ strtoupper(substr($merchant->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $merchant->name }}
                                            </div>
                                            @if($merchant->merchant_description)
                                                <div class="text-sm text-gray-500">
                                                    {{ Str::limit($merchant->merchant_description, 50) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $merchant->email }}</div>
                                    @if($merchant->merchant_phone)
                                        <div class="text-sm text-gray-500">{{ $merchant->merchant_phone }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $merchant->products->count() }} produits</span>
                                        <span class="text-xs text-gray-500">
                                            {{ $merchant->products->where('status', 'active')->count() }} actifs,
                                            {{ $merchant->products->where('status', 'pending')->count() }} en attente
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $merchant->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $merchant->merchant_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $merchant->merchant_approved ? 'Approuvé' : 'En attente' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        @if($merchant->merchant_approved)
                                            <form action="{{ route('admin.merchants.reject', $merchant) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900" onclick="return confirm('Voulez-vous révoquer l\'approbation de ce commerçant ?')">
                                                    Révoquer
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.merchants.approve', $merchant) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-900">
                                                    Approuver
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <button onclick="showMerchantDetails({{ $merchant->id }})" class="text-blue-600 hover:text-blue-900">
                                            Détails
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $merchants->links() }}
            </div>
        </div>
    </div>

    <!-- Modal pour les détails du commerçant -->
    <div id="merchantModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Détails du commerçant</h3>
                    <button onclick="hideMerchantDetails()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="modalContent" class="space-y-4">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
            </div>
        </div>
    </div>

    <script>
        const merchants = @json($merchants->items());
        
        function showMerchantDetails(merchantId) {
            const merchant = merchants.find(m => m.id === merchantId);
            if (!merchant) return;
            
            document.getElementById('modalTitle').textContent = `Détails de ${merchant.name}`;
            document.getElementById('modalContent').innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Informations personnelles</h4>
                        <p><strong>Nom:</strong> ${merchant.name}</p>
                        <p><strong>Email:</strong> ${merchant.email}</p>
                        <p><strong>Téléphone:</strong> ${merchant.merchant_phone || 'Non renseigné'}</p>
                        <p><strong>Inscription:</strong> ${new Date(merchant.created_at).toLocaleDateString('fr-FR')}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Activité</h4>
                        <p><strong>Produits:</strong> ${merchant.products.length}</p>
                        <p><strong>Statut:</strong> <span class="px-2 py-1 rounded text-xs ${merchant.merchant_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">${merchant.merchant_approved ? 'Approuvé' : 'En attente'}</span></p>
                    </div>
                </div>
                ${merchant.merchant_description ? `
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Description</h4>
                        <p class="text-gray-600">${merchant.merchant_description}</p>
                    </div>
                ` : ''}
                ${merchant.merchant_address ? `
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Adresse</h4>
                        <p class="text-gray-600">${merchant.merchant_address}</p>
                    </div>
                ` : ''}
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Produits récents</h4>
                    ${merchant.products.length > 0 ? 
                        merchant.products.slice(0, 3).map(product => `
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded mb-2">
                                <span>${product.name}</span>
                                <span class="text-sm px-2 py-1 rounded ${product.status === 'active' ? 'bg-green-100 text-green-800' : product.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">${product.status}</span>
                            </div>
                        `).join('') 
                        : '<p class="text-gray-500">Aucun produit</p>'
                    }
                </div>
            `;
            
            document.getElementById('merchantModal').classList.remove('hidden');
        }
        
        function hideMerchantDetails() {
            document.getElementById('merchantModal').classList.add('hidden');
        }
        
        // Fermer la modal en cliquant à l'extérieur
        document.getElementById('merchantModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideMerchantDetails();
            }
        });
    </script>
</x-admin-layout>
