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
                            @php
                                $mainImage = null;
                                $images = $product->images;
                                // S'assurer que c'est un tableau
                                if (is_string($images)) {
                                    $images = json_decode($images, true) ?: [];
                                }
                                if (is_array($images) && count($images) > 0) {
                                    $mainImage = asset('images/products/' . basename($images[0]));
                                } elseif (!empty($product->image) && is_string($product->image)) {
                                    $mainImage = asset('images/products/' . basename($product->image));
                                } else {
                                    $mainImage = asset('images/default-product.svg');
                                }
                            @endphp
                            <img id="mainImage" src="{{ $mainImage }}" alt="{{ $product->name }}"
                                 class="w-full h-96 object-cover rounded-lg">
                        </div>

                        <!-- Images supplémentaires -->
                        @php
                            $images = $product->images;
                            // S'assurer que c'est un tableau
                            if (is_string($images)) {
                                $images = json_decode($images, true) ?: [];
                            }
                            // Si vide, fallback sur image unique (legacy)
                            if ((!is_array($images) || count($images) === 0) && !empty($product->image)) {
                                $images = [$product->image];
                            }
                        @endphp
                        @if(is_array($images) && count($images) > 0)
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($images as $image)
                                    @if(is_string($image))
                                        <img src="{{ asset('images/products/' . basename($image)) }}" alt="{{ $product->name }}" 
                                             class="w-full h-20 object-cover rounded cursor-pointer border-2 border-transparent hover:border-blue-300"
                                             onclick="changeMainImage('{{ asset('images/products/' . basename($image)) }}', this)">
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Informations produit -->
                    <div>
                        <div class="mb-4">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span>Catégorie: {{ $product->category->name }}</span>
                                <span>•</span>
                                <span class="flex items-center">
                                    Boutique: {{ $product->user->shop_name ?? $product->user->name }}
                                    @if($product->user->is_verified_merchant)
                                        <span title="Marchand vérifié" class="inline-flex items-center ml-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                            Vérifié
                                        </span>
                                    @endif
                                </span>
                                <span>•</span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 016 6c0 4.418-6 10-6 10S4 12.418 4 8a6 6 0 016-6zm0 8a2 2 0 100-4 2 2 0 000 4z"/></svg>
                                    <span>Ville : {{ $product->user->city ?? 'Non renseignée' }}</span>
                                </span>
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
                                        <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150" id="addToCartBtn">
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
                                        <x-product-image :product="$relatedProduct" size="large" class="h-40 rounded-t-lg" />
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

        <!-- Section des avis clients -->
        <div class="mt-12 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">Avis clients</h3>
                <div class="mt-2 flex items-center space-x-4">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                        <span class="ml-2 text-lg font-medium">{{ number_format($product->average_rating, 1) }}</span>
                    </div>
                    <span class="text-gray-600">({{ $product->reviews_count }} avis)</span>
                </div>
            </div>

            <div class="p-6">
                @auth
                    <!-- Formulaire pour ajouter un avis -->
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-4">Donnez votre avis</h4>
                        <form action="{{ route('reviews.store', $product) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                                <div class="flex space-x-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="rating" value="{{ $i }}" class="sr-only" required>
                                            <svg class="w-6 h-6 text-gray-300 hover:text-yellow-400 rating-star" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Commentaire</label>
                                <textarea id="comment" name="comment" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Partagez votre expérience avec ce produit..." required></textarea>
                            </div>

                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Publier l'avis
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg text-center">
                        <p class="text-gray-600">
                            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Connectez-vous</a> 
                            pour donner votre avis sur ce produit.
                        </p>
                    </div>
                @endauth

                <!-- Liste des avis -->
                <div id="reviews-container">
                    @foreach($product->reviews()->with('user')->latest()->take(5)->get() as $review)
                        <div class="border-b border-gray-200 pb-6 mb-6 last:border-b-0">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-600">
                                                {{ strtoupper(substr($review->user->shop_name ?? $review->user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $review->user->shop_name ?? $review->user->name }}</p>
                                        <div class="flex items-center mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $review->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <p class="text-gray-700 mb-3">{{ $review->comment }}</p>
                            <div class="flex items-center space-x-4 text-sm">
                                <button onclick="voteHelpful({{ $review->id }})" class="text-gray-500 hover:text-gray-700 vote-btn">
                                    👍 Utile (<span class="helpful-count">{{ $review->helpful_votes }}</span>)
                                </button>
                            </div>
                        </div>
                    @endforeach

                    @if($product->reviews()->count() > 5)
                        <div class="text-center">
                            <button class="px-4 py-2 text-blue-600 border border-blue-600 rounded-md hover:bg-blue-50">
                                Voir tous les avis
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gestion des étoiles pour la note
        document.querySelectorAll('input[name="rating"]').forEach((input, index) => {
            input.addEventListener('change', function() {
                const stars = document.querySelectorAll('.rating-star');
                stars.forEach((star, starIndex) => {
                    if (starIndex <= index) {
                        star.classList.remove('text-gray-300');
                        star.classList.add('text-yellow-400');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
            });
        });

        // Fonction pour voter utile
        function voteHelpful(reviewId) {
            fetch(`/reviews/${reviewId}/vote`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`button[onclick="voteHelpful(${reviewId})"] .helpful-count`).textContent = data.helpful_votes;
                }
            });
        }

        function changeMainImage(imageSrc, thumbnail) {
    // ...rien, retour au comportement classique
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

        // Mettre à jour le compteur de panier après ajout
        @if(session('success') && str_contains(session('success'), 'panier'))
            if (typeof window.updateCartCount === 'function') {
                window.updateCartCount();
            }
        @endif
    </script>
</x-app-layout>
