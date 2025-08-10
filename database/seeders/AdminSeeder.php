<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@bissmoi.com'],
            [
                'name' => 'Administrateur',
                'email' => 'admin@bissmoi.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'merchant_approved' => false,
                'is_active' => true,
                'email_verified_at' => now()
            ]
        );
    }
}
