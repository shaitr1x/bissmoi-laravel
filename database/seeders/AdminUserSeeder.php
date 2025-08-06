<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin Bissmoi',
            'email' => 'admin@bissmoi.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Créer un commerçant de test
        User::create([
            'name' => 'Commerçant Test',
            'email' => 'merchant@bissmoi.com',
            'password' => Hash::make('password123'),
            'role' => 'merchant',
            'merchant_approved' => true,
            'merchant_description' => 'Boutique de test pour démonstration',
            'merchant_phone' => '0123456789',
            'merchant_address' => '123 Rue de Test, 75000 Paris',
            'email_verified_at' => now(),
        ]);

        // Créer un client de test
        User::create([
            'name' => 'Client Test',
            'email' => 'client@bissmoi.com',
            'password' => Hash::make('password123'),
            'role' => 'client',
            'email_verified_at' => now(),
        ]);
    }
}
