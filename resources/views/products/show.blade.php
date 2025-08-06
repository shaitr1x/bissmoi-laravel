<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-blue-600">Accueil</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('products.index') }}" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2">Produits</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-500 md:ml-2">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
                    <!-- Images -->
                    <div>
                        <div class="aspect-w-4 aspect-h-3 mb-4">
                            @if($product->image)
                                <img id="mainImage" src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                                     class="w-full h-96 object-cover rounded-lg">
                            @else
                                <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Images supplémentaires -->
                        @if($product->images)
                            @php $additionalImages = json_decode($product->images, true); @endphp
                            @if($additionalImages && count($additionalImages) > 0)
                                <div class="grid grid-cols-4 gap-2">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                                             class="w-full h-20 object-cover rounded cursor-pointer border-2 border-blue-500"
                                             onclick="changeMainImage('{{ asset($product->image) }}', this)">
                                    @endif
                                    @foreach($additionalImages as $image)
                                        <img src="{{ asset($image) }}" alt="{{ $product->name }}" 
                                             class="w-full h-20 object-cover rounded cursor-pointer border-2 border-transparent hover:border-blue-300"
                                             onclick="changeMainImage('{{ asset($image) }}', this)">
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Informations produit -->
                    <div>
                        <div class="mb-4">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span>Catégorie: {{ $product->category->name }}</span>
                                <span>•</span>
                                <span>Vendeur: {{ $product->user->name }}</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center space-x-4 mb-2">
                                @if($product->sale_price)
                                    <span class="text-3xl font-bold text-green-600">{{ number_format($product->sale_price, 0, ',', ' ') }} FCFA</span>
                                    <span class="text-xl line-through text-gray-500">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded">
                                        -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                    </span>
                                @else
                                    <span class="text-3xl font-bold text-gray-900">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                                @endif
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                @if($product->stock_quantity > 0)
                                    <span class="text-green-600 font-medium">En stock ({{ $product->stock_quantity }} disponible(s))</span>
                                @else
                                    <span class="text-red-600 font-medium">Rupture de stock</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                        </div>

                        @if($product->stock_quantity > 0)
                            @auth
                                <form action="{{ route('cart.store') }}" method="POST" class="mb-6">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    
                                    <div class="flex items-center space-x-4 mb-4">
                                        <label for="quantity" class="text-sm font-medium text-gray-700">Quantité:</label>
                                        <select name="quantity" id="quantity" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                            @for($i = 1; $i <= min($product->stock_quantity, 10); $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="flex space-x-4">
                                        <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150">
                                            <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l-2.5 5m0 0L17 18m0 0v0a1.5 1.5 0 01-3 0v0m3 0a1.5 1.5 0 01-3 0m0 0H9m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v9.5z"/>
                                            </svg>
                                            Ajouter au panier
                                        </button>
                                        <a href="{{ route('cart.index') }}" class="px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition duration-150">
                                            Voir le panier
                                        </a>
                                    </div>
                                </form>
                            @else
                                <div class="mb-6">
                                    <a href="{{ route('login') }}" class="block w-full text-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-150">
                                        Se connecter pour acheter
                                    </a>
                                </div>
                            @endauth
                        @endif

                        <!-- Informations supplémentaires -->
                        <div class="border-t pt-6">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-900">Référence:</span>
                                    <span class="text-gray-600">{{ $product->id }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900">Ajouté le:</span>
                                    <span class="text-gray-600">{{ $product->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produits similaires -->
            @if($relatedProducts->count() > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Produits similaires</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
                                <div class="aspect-w-16 aspect-h-12">
                                    <a href="{{ route('products.show', $relatedProduct) }}">
                                        @if($relatedProduct->image)
                                            <img src="{{ asset($relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" 
                                                 class="w-full h-40 object-cover rounded-t-lg">
                                        @else
                                            <div class="w-full h-40 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </a>
                                </div>

                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                        <a href="{{ route('products.show', $relatedProduct) }}" class="hover:text-blue-600">
                                            {{ $relatedProduct->name }}
                                        </a>
                                    </h3>
                                    
                                    <div class="flex justify-between items-center">
                                        @if($relatedProduct->sale_price)
                                            <span class="font-bold text-green-600">{{ number_format($relatedProduct->sale_price, 0, ',', ' ') }} FCFA</span>
                                        @else
                                            <span class="font-bold text-gray-900">{{ number_format($relatedProduct->price, 0, ',', ' ') }} FCFA</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function changeMainImage(imageSrc, thumbnail) {
            document.getElementById('mainImage').src = imageSrc;
            
            // Retirer la bordure de toutes les miniatures
            document.querySelectorAll('.cursor-pointer').forEach(img => {
                img.classList.remove('border-blue-500');
                img.classList.add('border-transparent');
            });
            
            // Ajouter la bordure à la miniature sélectionnée
            thumbnail.classList.remove('border-transparent');
            thumbnail.classList.add('border-blue-500');
        }
    </script>
</x-app-layout>
