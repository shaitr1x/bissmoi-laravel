<?php

require_once 'vendor/autoload.php';

// Charger Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Créer un utilisateur admin
$admin = User::updateOrCreate(
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

echo "Utilisateur admin créé avec succès!" . PHP_EOL;
echo "Email: admin@bissmoi.com" . PHP_EOL;
echo "Mot de passe: admin123" . PHP_EOL;
