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
                        <x-product-image :product="$product" size="xl" />
                        @php
                            $images = $product->images;
                            if (is_string($images)) {
                                $images = json_decode($images, true) ?: [];
                            }
                        @endphp
                        @if(is_array($images) && count($images) > 1)
                            <div class="flex gap-2 mt-4">
                                @foreach(array_slice($images, 1) as $img)
                                    <img src="{{ asset('storage/' . $img) }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded border">
                                @endforeach
                            </div>
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
