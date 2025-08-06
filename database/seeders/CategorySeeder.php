<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Catégories principales
        $electronics = Category::create([
            'name' => 'Électronique',
            'description' => 'Tous les produits électroniques',
            'is_active' => true,
        ]);

        $fashion = Category::create([
            'name' => 'Mode & Vêtements',
            'description' => 'Vêtements et accessoires de mode',
            'is_active' => true,
        ]);

        $home = Category::create([
            'name' => 'Maison & Jardin',
            'description' => 'Articles pour la maison et le jardin',
            'is_active' => true,
        ]);

        // Sous-catégories
        Category::create([
            'name' => 'Smartphones',
            'parent_id' => $electronics->id,
            'description' => 'Téléphones mobiles et smartphones',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Ordinateurs',
            'parent_id' => $electronics->id,
            'description' => 'Ordinateurs portables et de bureau',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Vêtements Homme',
            'parent_id' => $fashion->id,
            'description' => 'Vêtements pour hommes',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Vêtements Femme',
            'parent_id' => $fashion->id,
            'description' => 'Vêtements pour femmes',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Décoration',
            'parent_id' => $home->id,
            'description' => 'Articles de décoration intérieure',
            'is_active' => true,
        ]);
    }
}
