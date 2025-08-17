<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\NotificationService;
use App\Models\User;

echo "=== Test d'intégration complète des notifications email BISSMOI ===\n\n";

$service = app(NotificationService::class);
$user = User::first();

if (!$user) {
    echo "❌ Aucun utilisateur trouvé dans la base de données.\n";
    exit(1);
}

echo "👤 Utilisateur de test : {$user->name} ({$user->email})\n\n";

try {
    // Test 1 : Notification avec email
    echo "🔄 Test 1 : Notification avec email activé...\n";
    $service->sendToUser(
        $user->id,
        'Test d\'intégration complète',
        'Cette notification devrait apparaître dans l\'interface ET être envoyée par email.',
        'success',
        'check-circle',
        null, // Pas de métadonnées
        true // Email activé
    );
    echo "✅ Notification avec email envoyée avec succès !\n\n";

    // Test 2 : Notification sans email
    echo "🔄 Test 2 : Notification sans email...\n";
    $service->sendToUser(
        $user->id,
        'Test interface seulement',
        'Cette notification apparaît SEULEMENT dans l\'interface.',
        'info',
        'bell',
        null,
        false // Email désactivé
    );
    echo "✅ Notification interface seule envoyée avec succès !\n\n";

    // Vérification dans la base de données
    echo "🔍 Vérification des notifications créées...\n";
    $notifications = \App\Models\UserNotification::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->limit(2)
        ->get();

    foreach ($notifications as $notif) {
        echo "- {$notif->title} (créée le {$notif->created_at->format('d/m/Y H:i:s')})\n";
    }

    echo "\n✅ Test d'intégration terminé avec succès !\n";
    echo "📧 Vérifiez les logs (avec MAIL_MAILER=log) ou votre boîte email.\n";
    echo "💻 Connectez-vous à l'interface pour voir les notifications.\n";

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
