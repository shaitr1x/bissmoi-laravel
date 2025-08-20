<x-merchant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Images -->
                    <div>
                        <div class="aspect-w-4 aspect-h-3 mb-4">
                            @php
                                $mainImage = null;
                                $images = $product->images;
                                if (is_string($images)) {
                                    $images = json_decode($images, true) ?: [];
                                }
                                if (is_array($images) && count($images) > 0) {
                                    $mainImage = asset($images[0]);
                                } else {
                                    $mainImage = asset('images/default-product.svg');
                                }
                            @endphp
                            <img id="mainImage" src="{{ $mainImage }}" alt="{{ $product->name }}" class="w-full h-96 object-cover rounded-lg">
                        </div>
                        
                        @if(is_array($images) && count($images) > 1)
                            <div class="grid grid-cols-4 gap-2 mt-4">
                                @foreach($images as $img)
                                    <img src="{{ asset($img) }}" alt="{{ $product->name }}" 
                                         class="w-full h-20 object-cover rounded cursor-pointer border-2 border-transparent hover:border-blue-300"
                                         onclick="changeMainImage('{{ asset($img) }}', this)">
                                @endforeach
                            </div>
                            <script>
                                function changeMainImage(imageSrc, thumbnail) {
                                    document.getElementById('mainImage').src = imageSrc;
                                    // Retirer la bordure de toutes les miniatures
                                    document.querySelectorAll('.grid img').forEach(img => img.classList.remove('border-blue-300'));
                                    // Ajouter la bordure à la miniature cliquée
                                    thumbnail.classList.add('border-blue-300');
                                }
                            </script>
                        @endif
                    </div>
                    <!-- Infos produit -->
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
                        <div class="mb-2 text-gray-600">Catégorie : {{ $product->category->name ?? '-' }}</div>
                        <div class="mb-4">
                            <span class="text-2xl font-bold text-blue-700">{{ number_format($product->current_price, 0, ',', ' ') }} FCFA</span>
                            @if($product->sale_price)
                                <span class="ml-2 text-lg line-through text-gray-400">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                            @endif
                        </div>
                        <div class="mb-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                @if($product->status === 'active') bg-green-100 text-green-800
                                @elseif($product->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($product->status) }}
                            </span>
                        </div>
                        <div class="mb-4">
                            <span class="font-semibold">Stock :</span> {{ $product->stock_quantity }}
                        </div>
                        <div class="mb-6">
                            <span class="font-semibold">Description :</span>
                            <p class="mt-2 text-gray-700">{!! nl2br(e($product->description)) !!}</p>
                        </div>
                        <div class="flex gap-4 mt-8">
                            <a href="{{ route('merchant.products.edit', $product) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Modifier</a>
                            <form method="POST" action="{{ route('merchant.products.destroy', $product) }}" onsubmit="return confirm('Supprimer ce produit ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-merchant-layout>
