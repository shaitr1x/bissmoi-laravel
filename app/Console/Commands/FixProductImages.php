<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class FixProductImages extends Command
{
    protected $signature = 'fix:product-images';
    protected $description = 'Corrige les chemins d\'images des produits pour pointer sur public/images/products';

    public function handle()
    {
        $count = 0;
        Product::chunk(100, function ($products) use (&$count) {
            foreach ($products as $product) {
                $changed = false;
                // Fix champ image
                if (!empty($product->image)) {
                    $basename = basename($product->image);
                    if ($product->image !== $basename) {
                        $product->image = $basename;
                        $changed = true;
                    }
                }
                // Fix champ images (array ou json)
                if (!empty($product->images)) {
                    $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                    if (is_array($images)) {
                        $newImages = array_map(function($img) {
                            return basename($img);
                        }, $images);
                        if ($images !== $newImages) {
                            $product->images = $newImages;
                            $changed = true;
                        }
                    }
                }
                if ($changed) {
                    $product->save();
                    $this->info("Produit #{$product->id} corrigé");
                    $count++;
                }
            }
        });
        $this->info("Correction terminée. {$count} produit(s) modifié(s).");
    }
}
