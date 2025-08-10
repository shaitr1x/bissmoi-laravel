<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$product = \App\Models\Product::find(6);
echo "Product found: " . ($product ? 'yes' : 'no') . "\n";
echo "Raw images: " . $product->getAttributes()['images'] . "\n";
echo "Decoded images: " . json_encode($product->images) . "\n";
echo "First image: " . ($product->images[0] ?? 'none') . "\n";
echo "Image property: " . ($product->image ?? 'null') . "\n";
echo "Image URL: " . $product->image_url . "\n";
