<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $merchant = User::where('role', 'merchant')->first();
        $categories = Category::all();

        $products = [
            [
                'name' => 'iPhone 14 Pro',
                'description' => 'Dernière génération d\'iPhone avec puce A16 Bionic et appareil photo professionnel',
                'short_description' => 'Smartphone haut de gamme Apple',
                'price' => 1299.99,
                'sale_price' => 1199.99,
                'stock_quantity' => 50,
                'status' => 'active',
                'featured' => true,
                'sku' => 'IPH14PRO001',
            ],
            [
                'name' => 'MacBook Air M2',
                'description' => 'Ordinateur portable ultra-fin avec puce M2, écran Retina 13 pouces',
                'short_description' => 'Ordinateur portable Apple',
                'price' => 1499.99,
                'stock_quantity' => 30,
                'status' => 'active',
                'featured' => true,
                'sku' => 'MBA-M2-001',
            ],
            [
                'name' => 'T-shirt Premium Coton Bio',
                'description' => 'T-shirt 100% coton biologique, coupe moderne et confortable',
                'short_description' => 'T-shirt homme premium',
                'price' => 29.99,
                'stock_quantity' => 100,
                'status' => 'active',
                'sku' => 'TSH-BIO-001',
            ],
            [
                'name' => 'Robe d\'été florale',
                'description' => 'Robe légère avec motifs floraux, parfaite pour l\'été',
                'short_description' => 'Robe femme été',
                'price' => 79.99,
                'sale_price' => 59.99,
                'stock_quantity' => 25,
                'status' => 'active',
                'featured' => true,
                'sku' => 'ROB-ETE-001',
            ],
            [
                'name' => 'Lampe de bureau LED',
                'description' => 'Lampe de bureau moderne avec éclairage LED ajustable',
                'short_description' => 'Lampe bureau LED',
                'price' => 89.99,
                'stock_quantity' => 40,
                'status' => 'pending',
                'sku' => 'LAM-LED-001',
            ],
        ];

        foreach ($products as $productData) {
            $category = $categories->where('parent_id', '!=', null)->random();
            
            Product::create(array_merge($productData, [
                'user_id' => $merchant->id,
                'category_id' => $category->id,
            ]));
        }
    }
}
