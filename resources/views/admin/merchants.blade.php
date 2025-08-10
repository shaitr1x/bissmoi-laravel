<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des commerçants') }}
        </h2>
    </x-slot>


    <!-- Filtres et recherche modernisés -->
    <form method="GET" action="" class="mb-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:gap-4">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un commerçant, email ou boutique..." class="w-full border rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="submit" name="status" value="" class="px-4 py-1 rounded-full border {{ request('status') == '' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }} hover:bg-blue-50">Tous</button>
                <button type="submit" name="status" value="approved" class="px-4 py-1 rounded-full border {{ request('status') == 'approved' ? 'bg-green-600 text-white' : 'bg-white text-gray-700' }} hover:bg-green-50">Approuvés</button>
                <button type="submit" name="status" value="pending" class="px-4 py-1 rounded-full border {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700' }} hover:bg-yellow-50">En attente</button>
                <button type="submit" name="verified" value="1" class="px-4 py-1 rounded-full border {{ request('verified') == '1' ? 'bg-blue-700 text-white' : 'bg-white text-gray-700' }} hover:bg-blue-100">Vérifiés</button>
            </div>
        </div>
    </form>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($merchants as $merchant)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 flex flex-col">
                <div class="flex items-center gap-4 p-4 border-b">
                    <div class="h-14 w-14 rounded-full bg-gray-200 flex items-center justify-center text-xl font-bold text-gray-600">
                        {{ strtoupper(substr($merchant->shop_name ?? $merchant->name, 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-lg text-gray-900">{{ $merchant->shop_name ?? $merchant->name }}</span>
                            @if($merchant->is_verified_merchant)
                                <span title="Marchand vérifié" class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <svg class="w-3 h-3 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    Vérifié
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500">{{ $merchant->email }}</div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $merchant->merchant_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $merchant->merchant_approved ? 'Approuvé' : 'En attente' }}
                    </span>
                </div>
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div class="mb-2">
                        <span class="text-sm text-gray-700 font-medium">{{ $merchant->products->count() }} produits</span>
                        <span class="text-xs text-gray-500 ml-2">{{ $merchant->products->where('status', 'active')->count() }} actifs, {{ $merchant->products->where('status', 'pending')->count() }} en attente</span>
                    </div>
                    @if($merchant->merchant_description)
                        <div class="text-xs text-gray-500 mb-2">{{ Str::limit($merchant->merchant_description, 60) }}</div>
                    @endif
                    <div class="flex flex-wrap gap-2 mt-2">
                        @if($merchant->merchant_approved)
                            <form action="{{ route('admin.merchants.reject', $merchant) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-yellow-600 hover:text-yellow-900" onclick="return confirm('Voulez-vous révoquer l\'approbation de ce commerçant ?')">Révoquer</button>
                            </form>
                        @else
                            <form action="{{ route('admin.merchants.approve', $merchant) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-green-600 hover:text-green-900">Approuver</button>
                            </form>
                        @endif
                        @if($merchant->is_verified_merchant)
                            <form action="{{ route('admin.merchants.unverify', $merchant) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-blue-600 hover:text-blue-900" onclick="return confirm('Retirer le badge vérifié à ce commerçant ?')">Retirer le badge</button>
                            </form>
                        @else
                            <form action="{{ route('admin.merchants.verify', $merchant) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-blue-600 hover:text-blue-900">Vérifier (badge)</button>
                            </form>
                        @endif
                        <button onclick="showMerchantDetails({{ $merchant->id }})" class="text-blue-600 hover:text-blue-900">Détails</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 py-8">Aucun commerçant trouvé.</div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $merchants->links() }}
    </div>

    <!-- Modal pour les détails du commerçant (unique, hors boucle) -->
    <div id="merchantModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white" onclick="event.stopPropagation()">
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
                        <p><strong>Boutique:</strong> ${merchant.shop_name ? merchant.shop_name : '<span class=\'text-gray-400\'>Non renseigné</span>'}</p>
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
