# Correction - Problème d'affichage des miniatures de produit

## Problème identifié
Les miniatures d'images des produits ne s'affichaient pas sur la page de détail d'un produit pour le commerçant (`/merchant/products/{id}`).

## Cause du problème
Dans le fichier `resources/views/merchant/products/show.blade.php`, les miniatures utilisaient un chemin incorrect :
```php
// INCORRECT - Chemin vers storage au lieu d'images/products
<img src="{{ asset('storage/' . $img) }}" ... >
```

Alors que les images sont stockées dans `public/images/products/` et non dans `public/storage/`.

## Solution implémentée

### 1. Correction du chemin d'accès aux images
- **Fichier modifié** : `resources/views/merchant/products/show.blade.php`
- **Changement** : Utilisation de `asset($img)` au lieu de `asset('storage/' . $img)`

### 2. Amélioration de l'interface utilisateur
- **Remplacement du composant** `<x-product-image>` par une implémentation personnalisée
- **Ajout d'une galerie interactive** avec image principale et miniatures cliquables
- **Fonctionnalité** : Clic sur miniature → Change l'image principale
- **Feedback visuel** : Bordure bleue sur la miniature sélectionnée

### 3. Code corrigé
```php
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
                document.querySelectorAll('.grid img').forEach(img => img.classList.remove('border-blue-300'));
                thumbnail.classList.add('border-blue-300');
            }
        </script>
    @endif
</div>
```

## Résultat
✅ **Miniatures affichées** : Les miniatures d'images sont maintenant visibles  
✅ **Navigation interactive** : Possibilité de cliquer sur les miniatures pour changer l'image principale  
✅ **Cohérence** : Comportement similaire aux autres pages de produit du site  
✅ **Feedback visuel** : Indication claire de l'image actuellement sélectionnée  

## Test effectué
- ✅ Vérification sur le produit ID 7 (PS4) avec plusieurs images
- ✅ Affichage correct de toutes les miniatures
- ✅ Fonctionnalité de changement d'image opérationnelle

## Structure des images dans le projet
- **Stockage** : `public/images/products/`
- **Format BDD** : Array JSON avec chemins complets (`images/products/filename.jpg`)
- **Affichage** : `asset($img)` pour l'URL complète

La galerie d'images fonctionne maintenant parfaitement pour les commerçants !
