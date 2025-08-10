<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Fix legacy image and images fields for all products
Product::chunk(100, function ($products) {
    foreach ($products as $product) {
        $changed = false;
        // Fix 'image' field
        if (!empty($product->image)) {
            $basename = basename($product->image);
            if ($product->image !== $basename) {
                $product->image = $basename;
                $changed = true;
            }
        }
        // Fix 'images' field (array or json string)
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
            echo "Product #{$product->id} images fixed\n";
        }
    }
});

echo "Done.\n";
