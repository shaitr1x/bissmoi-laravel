<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductWithImagesSeeder extends Seeder
{
    public function run()
    {
        // Créer le dossier products dans storage/app/public si il n'existe pas
        if (!Storage::disk('public')->exists('products')) {
            Storage::disk('public')->makeDirectory('products');
        }

        // Créer des images de test simples (rectangles colorés)
        $testImages = [];
        $colors = ['ff6b6b', '4ecdc4', '45b7d1', '96ceb4', 'feca57'];
        
        for ($i = 1; $i <= 5; $i++) {
            $imageName = "test-product-{$i}.jpg";
            $imagePath = storage_path("app/public/products/{$imageName}");
            
            // Créer une image simple de test (nécessite GD extension)
            if (extension_loaded('gd')) {
                $img = imagecreate(400, 300);
                $color = $colors[$i-1];
                $rgb = [
                    hexdec(substr($color, 0, 2)),
                    hexdec(substr($color, 2, 2)),
                    hexdec(substr($color, 4, 2))
                ];
                $bgColor = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
                $textColor = imagecolorallocate($img, 255, 255, 255);
                
                imagefilledrectangle($img, 0, 0, 400, 300, $bgColor);
                imagestring($img, 5, 150, 140, "Produit {$i}", $textColor);
                
                imagejpeg($img, $imagePath);
                imagedestroy($img);
                
                $testImages[] = "products/{$imageName}";
            }
        }

        // Récupérer ou créer un utilisateur commerçant
        $merchant = User::where('role', 'merchant')->first();
        if (!$merchant) {
            $merchant = User::factory()->create([
                'name' => 'Commerçant Test',
                'email' => 'merchant@test.com',
                'role' => 'merchant'
            ]);
        }

        // Récupérer ou créer une catégorie
        $category = Category::first();
        if (!$category) {
            $category = Category::create([
                'name' => 'Électronique',
                'description' => 'Produits électroniques et high-tech'
            ]);
        }

        // Créer des produits avec images
        $products = [
            [
                'name' => 'Smartphone Premium',
                'description' => 'Un smartphone haut de gamme avec toutes les fonctionnalités modernes.',
                'price' => 799.99,
                'sale_price' => 699.99,
                'stock_quantity' => 10
            ],
            [
                'name' => 'Casque Bluetooth',
                'description' => 'Casque sans fil avec réduction de bruit active.',
                'price' => 299.99,
                'stock_quantity' => 15
            ],
            [
                'name' => 'Tablette Tactile',
                'description' => 'Tablette 10 pouces parfaite pour le travail et les loisirs.',
                'price' => 449.99,
                'sale_price' => 399.99,
                'stock_quantity' => 8
            ],
            [
                'name' => 'Montre Connectée',
                'description' => 'Smartwatch avec suivi de santé et notifications.',
                'price' => 249.99,
                'stock_quantity' => 20
            ],
            [
                'name' => 'Écouteurs Sport',
                'description' => 'Écouteurs résistants à l\'eau pour le sport.',
                'price' => 79.99,
                'sale_price' => 59.99,
                'stock_quantity' => 25
            ]
        ];

        foreach ($products as $index => $productData) {
            $product = Product::create(array_merge($productData, [
                'user_id' => $merchant->id,
                'category_id' => $category->id,
                'images' => isset($testImages[$index]) ? [$testImages[$index]] : [],
                'featured' => $index < 3, // Les 3 premiers sont en vedette
                'status' => 'active'
            ]));
        }
    }
}
