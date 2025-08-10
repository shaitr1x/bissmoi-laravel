<x-merchant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
            {{ __('Mon Profil Marchand') }}
            @if($merchant->is_verified_merchant)
                <span title="Marchand vérifié" class="inline-flex items-center ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                    <svg class="w-4 h-4 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    Vérifié
                </span>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-6">Informations du marchand</h3>
                    
                    <form method="POST" action="{{ route('merchant.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Nom -->
                            <div>
                                <x-input-label for="name" :value="__('Nom')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $merchant->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Nom de la boutique -->
                            <div>
                                <x-input-label for="shop_name" :value="__('Nom de la boutique')" />
                                <x-text-input id="shop_name" class="block mt-1 w-full" type="text" name="shop_name" :value="old('shop_name', $merchant->shop_name)" />
                                <x-input-error :messages="$errors->get('shop_name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $merchant->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Téléphone -->
                            <div>
                                <x-input-label for="merchant_phone" :value="__('Téléphone')" />
                                <x-text-input id="merchant_phone" class="block mt-1 w-full" type="tel" name="merchant_phone" :value="old('merchant_phone', $merchant->merchant_phone)" />
                                <x-input-error :messages="$errors->get('merchant_phone')" class="mt-2" />
                            </div>

                            <!-- Statut -->
                            <div>
                                <x-input-label for="status" :value="__('Statut')" />
                                <div class="mt-1">
                                    @if($merchant->merchant_approved)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                            Marchand Vérifié
                                        </span>
                                    @else
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                En attente d'approbation
                                            </span>
                                            <form action="{{ route('merchant.request_verification') }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-md hover:bg-blue-700">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                                    Demander la vérification
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Adresse -->
                        <div class="mt-6">
                            <x-input-label for="merchant_address" :value="__('Adresse')" />
                            <textarea id="merchant_address" name="merchant_address" rows="3" 
                                      class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('merchant_address', $merchant->merchant_address) }}</textarea>
                            <x-input-error :messages="$errors->get('merchant_address')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <x-input-label for="merchant_description" :value="__('Description de votre entreprise')" />
                            <textarea id="merchant_description" name="merchant_description" rows="4" 
                                      class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                      placeholder="Décrivez votre entreprise, vos produits, vos services...">{{ old('merchant_description', $merchant->merchant_description) }}</textarea>
                            <x-input-error :messages="$errors->get('merchant_description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Mettre à jour le profil') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistiques du marchand -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-6">Statistiques</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Produits</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $merchant->products()->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-lg mr-3">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Produits actifs</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $merchant->products()->where('status', 'active')->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">En attente</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $merchant->products()->where('status', 'pending')->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-purple-100 rounded-lg mr-3">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Commandes</p>
                                    <p class="text-2xl font-bold text-gray-900">
                                        {{ $merchant->products()->withCount(['orderItems'])->get()->sum('order_items_count') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-merchant-layout>
