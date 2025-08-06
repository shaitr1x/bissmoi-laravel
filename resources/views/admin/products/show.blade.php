<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails du produit') }}
            </h2>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Informations du produit</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-2xl">{{ $product->name }}</h4>
                            <p class="text-gray-600 mt-1">{{ $product->category->name }}</p>
                            <p class="text-sm text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="text-right">
                            <div class="text-3xl font-bold">
                                @if($product->sale_price)
                                    <span class="text-red-600">{{ number_format($product->sale_price, 2) }}€</span>
                                    <div class="text-lg text-gray-500 line-through">{{ number_format($product->price, 2) }}€</div>
                                @else
                                    {{ number_format($product->price, 2) }}€
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                Stock: {{ $product->stock_quantity }} unités
                            </p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h5 class="font-semibold mb-2">Description courte</h5>
                        <p class="text-gray-700">{{ $product->short_description ?? 'Aucune description courte' }}</p>
                    </div>

                    <div class="mt-6">
                        <h5 class="font-semibold mb-2">Description complète</h5>
                        <div class="prose max-w-none">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avis clients -->
            @if($product->reviews->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">
                            Avis clients ({{ $product->reviews->count() }})
                        </h3>
                        
                        <div class="space-y-4">
                            @foreach($product->reviews->take(5) as $review)
                                <div class="border-b border-gray-200 pb-4 last:border-b-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-semibold">{{ $review->user->name }}</h4>
                                            <div class="flex items-center mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="text-{{ $i <= $review->rating ? 'yellow' : 'gray' }}-400">★</span>
                                                @endfor
                                                <span class="ml-2 text-sm text-gray-600">{{ $review->rating }}/5</span>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $review->approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $review->approved ? 'Approuvé' : 'En attente' }}
                                        </span>
                                    </div>
                                    @if($review->comment)
                                        <p class="mt-2 text-gray-700">{{ $review->comment }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-2">{{ $review->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions et détails -->
        <div class="space-y-6">
            <!-- Statut et actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Actions</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Statut actuel</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($product->status === 'active') bg-green-100 text-green-800
                                @elseif($product->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($product->status) }}
                            </span>
                        </div>

                        @if($product->status === 'pending')
                            <div class="flex flex-col space-y-2">
                                <form action="{{ route('admin.products.update', $product) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                        ✓ Approuver le produit
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.products.update', $product) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="inactive">
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                        ✗ Rejeter le produit
                                    </button>
                                </form>
                            </div>
                        @elseif($product->status === 'active')
                            <form action="{{ route('admin.products.update', $product) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="inactive">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                                    Désactiver
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.products.update', $product) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                    Réactiver
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce produit ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                🗑️ Supprimer définitivement
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Informations commerçant -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Commerçant</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="font-semibold">{{ $product->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $product->user->email }}</p>
                        </div>
                        
                        @if($product->user->merchant_phone)
                            <div>
                                <p class="text-sm font-medium text-gray-700">Téléphone</p>
                                <p class="text-sm text-gray-600">{{ $product->user->merchant_phone }}</p>
                            </div>
                        @endif
                        
                        @if($product->user->merchant_description)
                            <div>
                                <p class="text-sm font-medium text-gray-700">Description</p>
                                <p class="text-sm text-gray-600">{{ $product->user->merchant_description }}</p>
                            </div>
                        @endif
                        
                        <div class="pt-2">
                            <a href="{{ route('admin.merchants') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                Voir tous les produits de ce commerçant →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Statistiques</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Créé le</span>
                            <span class="text-sm font-medium">{{ $product->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Mis à jour</span>
                            <span class="text-sm font-medium">{{ $product->updated_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Note moyenne</span>
                            <span class="text-sm font-medium">
                                {{ $product->reviews->count() > 0 ? number_format($product->reviews->avg('rating'), 1) : 'N/A' }}/5
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Avis clients</span>
                            <span class="text-sm font-medium">{{ $product->reviews->count() }}</span>
                        </div>
                        @if($product->featured)
                            <div class="pt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    ⭐ Produit en vedette
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
