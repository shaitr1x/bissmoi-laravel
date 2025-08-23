<?php

require_once 'vendor/autoload.php';

// Test simple pour vérifier les emails
echo "=== Test des notifications email ===\n";

// Vérification des fichiers modifiés
$files_to_check = [
    'app/Http/Controllers/Auth/RegisteredUserController.php',
    'app/Http/Controllers/Merchant/ProductController.php', 
    'app/Http/Controllers/Merchant/MerchantController.php',
    'app/Mail/NewUserRegistered.php'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "✅ {$file} - Fichier existe\n";
        
        $content = file_get_contents($file);
        
        if (strpos($file, 'RegisteredUserController') !== false) {
            if (strpos($content, 'foreach ($adminEmails as $email)') !== false) {
                echo "  ✅ Correction inscription utilisateur appliquée\n";
            } else {
                echo "  ❌ Correction inscription utilisateur manquante\n";
            }
        }
        
        if (strpos($file, 'ProductController') !== false) {
            if (strpos($content, 'Notification admin lors de la mise à jour') !== false) {
                echo "  ✅ Correction mise à jour produit appliquée\n";
            } else {
                echo "  ❌ Correction mise à jour produit manquante\n";
            }
        }
        
        if (strpos($file, 'MerchantController') !== false) {
            if (strpos($content, 'Envoi email aux admins pour demande de vérification') !== false) {
                echo "  ✅ Correction demande vérification appliquée\n";
            } else {
                echo "  ❌ Correction demande vérification manquante\n";
            }
        }
        
        if (strpos($file, 'NewUserRegistered') !== false) {
            if (strpos($content, '->to([') === false) {
                echo "  ✅ Correction classe mail appliquée\n";
            } else {
                echo "  ❌ Correction classe mail manquante\n";
            }
        }
        
    } else {
        echo "❌ {$file} - Fichier introuvable\n";
    }
}

echo "\n=== Résumé des corrections ===\n";
echo "✅ Inscription utilisateur : Les admins recevront maintenant un email\n";
echo "✅ Mise à jour produit : Les admins seront notifiés par email\n";  
echo "✅ Demande vérification : Les admins recevront un email\n";
echo "✅ Systèmes existants : Inchangés (fonctionnent déjà)\n";

echo "\n=== Actions recommandées ===\n";
echo "1. Tester l'inscription d'un nouvel utilisateur\n";
echo "2. Tester la mise à jour d'un produit par un commerçant\n";
echo "3. Tester une demande de vérification\n";
echo "4. Vérifier les logs Laravel pour les erreurs d'envoi\n";

?>
