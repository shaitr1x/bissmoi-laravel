<?php
require_once 'vendor/autoload.php';

use App\Services\NotificationService;
use App\Models\User;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test avec un utilisateur existant
$user = User::first();
if ($user) {
    echo "Test des notifications email pour l'utilisateur: " . $user->email . "\n";
    
    $notificationService = app(NotificationService::class);
    
    try {
        // Test notification simple
        $notificationService->sendToUser(
            $user->id,
            'Test de notification',
            'Ceci est un test de notification par email depuis BISSMOI.',
            'info',
            'bell',
            null,
            true // Activer l'email
        );
        
        echo "✓ Notification envoyée avec succès !\n";
        echo "Vérifiez votre boîte email ou les logs pour confirmer l'envoi.\n";
        
    } catch (Exception $e) {
        echo "✗ Erreur lors de l'envoi de la notification: " . $e->getMessage() . "\n";
    }
} else {
    echo "Aucun utilisateur trouvé dans la base de données.\n";
}
