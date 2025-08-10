<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    /**
     * Handle the Product "deleting" event.
     * Supprime toutes les images du produit lors de la suppression.
     */
    public function deleting(Product $product)
    {
        $images = $product->images;
        if (is_string($images)) {
            $images = json_decode($images, true) ?: [];
        }
        if (is_array($images)) {
            foreach ($images as $img) {
                if (is_string($img) && Storage::disk('public')->exists($img)) {
                    Storage::disk('public')->delete($img);
                }
            }
        }
    }
}
