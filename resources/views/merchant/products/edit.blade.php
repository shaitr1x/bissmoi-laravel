<x-merchant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier le produit : {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <form method="POST" action="{{ route('merchant.products.update', $product) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nom -->
                        <div>
                            <x-input-label for="name" :value="__('Nom du produit')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ old('name', $product->name) }}" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <!-- Catégorie -->
                        <div>
                            <x-input-label for="category_id" :value="__('Catégorie')" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 rounded-md">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @if(old('category_id', $product->category_id) == $category->id) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>
                        <!-- Prix -->
                        <div>
                            <x-input-label for="price" :value="__('Prix')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>
                        <!-- Prix promo -->
                        <div>
                            <x-input-label for="sale_price" :value="__('Prix promotionnel')" />
                            <x-text-input id="sale_price" class="block mt-1 w-full" type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" />
                            <x-input-error :messages="$errors->get('sale_price')" class="mt-2" />
                        </div>
                        <!-- Stock -->
                        <div>
                            <x-input-label for="stock_quantity" :value="__('Quantité en stock')" />
                            <x-text-input id="stock_quantity" class="block mt-1 w-full" type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required />
                            <x-input-error :messages="$errors->get('stock_quantity')" class="mt-2" />
                        </div>
                        <!-- Statut -->
                        <div>
                            <x-input-label for="status" :value="__('Statut')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 rounded-md">
                                <option value="active" @if(old('status', $product->status) == 'active') selected @endif>Actif</option>
                                <option value="pending" @if(old('status', $product->status) == 'pending') selected @endif>En attente</option>
                                <option value="inactive" @if(old('status', $product->status) == 'inactive') selected @endif>Inactif</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 rounded-md">{{ old('description', $product->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Images actuelles -->
                    <div class="mt-6">
                        <div class="mb-2 font-semibold">Images actuelles :</div>
                        @php
                            $images = $product->images;
                            if (is_string($images)) {
                                $images = json_decode($images, true) ?: [];
                            }
                        @endphp
                        <div class="flex gap-2 flex-wrap">
                            @foreach($images as $img)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $img) }}" alt="Image" class="w-24 h-24 object-cover rounded border">
                                    <!-- Option de suppression (à implémenter côté backend si besoin) -->
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Ajouter de nouvelles images -->
                    <div class="mt-6">
                        <x-input-label for="image" :value="__('Image principale (remplacer)')" />
                        <x-text-input id="image" class="block mt-1 w-full" type="file" name="image" accept="image/*" />
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="images" :value="__('Ajouter d\'autres images')" />
                        <x-text-input id="images" class="block mt-1 w-full" type="file" name="images[]" accept="image/*" multiple />
                        <x-input-error :messages="$errors->get('images')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <x-primary-button>
                            {{ __('Enregistrer les modifications') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-merchant-layout>
