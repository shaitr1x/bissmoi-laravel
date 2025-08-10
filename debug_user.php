<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test de la colonne is_verified_merchant
echo "=== DEBUG: Table Users ===\n";

try {
    // Vérifier les colonnes de la table
    $columns = Schema::getColumnListing('users');
    echo "Colonnes dans la table users:\n";
    print_r($columns);
    
    // Vérifier si la colonne existe
    $hasColumn = Schema::hasColumn('users', 'is_verified_merchant');
    echo "\nLa colonne 'is_verified_merchant' existe: " . ($hasColumn ? 'OUI' : 'NON') . "\n";
    
    // Tester un utilisateur merchant
    $merchant = App\Models\User::where('role', 'merchant')->first();
    if ($merchant) {
        echo "\nMarchand trouvé: " . $merchant->name . "\n";
        echo "Attributs disponibles:\n";
        print_r($merchant->getAttributes());
        
        // Test direct d'accès
        echo "\nTest direct d'accès à is_verified_merchant:\n";
        try {
            $value = $merchant->is_verified_merchant;
            echo "Valeur: " . ($value ? 'true' : 'false') . "\n";
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage() . "\n";
        }
    } else {
        echo "\nAucun marchand trouvé\n";
    }
    
} catch (Exception $e) {
    echo "Erreur générale: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DEBUG ===\n";
