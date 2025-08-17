<?php

/*
|--------------------------------------------------------------------------
| Exemple d'utilisation du système de notifications email - BISSMOI
|--------------------------------------------------------------------------
|
| Ce fichier montre comment utiliser le NotificationService pour envoyer
| des notifications avec emails dans vos contrôleurs personnalisés.
|
*/

namespace App\Http\Controllers;

use App\Services\NotificationService;
use App\Models\User;
use Illuminate\Http\Request;

class ExampleNotificationController extends Controller
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Exemple 1: Notification simple à un utilisateur
     */
    public function sendWelcomeNotification($userId)
    {
        $this->notificationService->sendToUser(
            $userId,
            'Bienvenue sur BISSMOI !',
            'Merci de rejoindre notre plateforme. Découvrez nos produits et services.',
            'success',
            'hand-raised',
            null,
            true // Envoyer email
        );

        return response()->json(['message' => 'Notification de bienvenue envoyée']);
    }

    /**
     * Exemple 2: Notification de promotion à tous les utilisateurs
     */
    public function sendPromotionToAll()
    {
        $this->notificationService->sendToAllUsers(
            'Promotion spéciale !',
            'Profitez de -20% sur tous nos produits ce week-end. Code: WEEKEND20',
            'info',
            'tag',
            ['promo_code' => 'WEEKEND20'],
            true // Envoyer email
        );

        return response()->json(['message' => 'Promotion envoyée à tous les utilisateurs']);
    }

    /**
     * Exemple 3: Notification personnalisée avec action
     */
    public function sendProductRecommendation($userId, $productId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        $this->notificationService->sendToUser(
            $userId,
            'Produit recommandé pour vous',
            "Bonjour {$user->name}, nous pensons que ce produit pourrait vous intéresser !",
            'info',
            'star',
            [
                'product_id' => $productId,
                'action_url' => url("/products/{$productId}")
            ],
            true // Envoyer email
        );

        return response()->json(['message' => 'Recommandation envoyée']);
    }

    /**
     * Exemple 4: Notification à un groupe d'utilisateurs spécifiques
     */
    public function sendMaintenanceAlert()
    {
        // Par exemple, notifier tous les marchands
        $merchantUsers = User::where('role', 'merchant')->pluck('id')->toArray();

        $this->notificationService->sendToUsers(
            $merchantUsers,
            'Maintenance programmée',
            'Une maintenance technique aura lieu demain de 2h à 4h du matin.',
            'warning',
            'wrench-screwdriver',
            ['maintenance_date' => '2025-01-15'],
            true // Envoyer email
        );

        return response()->json([
            'message' => 'Alerte de maintenance envoyée',
            'recipients' => count($merchantUsers)
        ]);
    }

    /**
     * Exemple 5: Notification conditionnelle (avec ou sans email selon préférences)
     */
    public function sendConditionalNotification(Request $request)
    {
        $userId = $request->input('user_id');
        $sendEmail = $request->boolean('send_email', false);

        $this->notificationService->sendToUser(
            $userId,
            'Notification conditionnelle',
            'Cette notification peut être envoyée avec ou sans email selon vos préférences.',
            'info',
            'cog',
            null,
            $sendEmail // Email conditionnel
        );

        $message = $sendEmail ? 
            'Notification envoyée avec email' : 
            'Notification envoyée sans email';

        return response()->json(['message' => $message]);
    }
}

/*
|--------------------------------------------------------------------------
| Routes d'exemple (à ajouter dans routes/web.php si nécessaire)
|--------------------------------------------------------------------------
|
| Route::post('/example/welcome/{userId}', [ExampleNotificationController::class, 'sendWelcomeNotification']);
| Route::post('/example/promotion/all', [ExampleNotificationController::class, 'sendPromotionToAll']);
| Route::post('/example/recommend/{userId}/{productId}', [ExampleNotificationController::class, 'sendProductRecommendation']);
| Route::post('/example/maintenance', [ExampleNotificationController::class, 'sendMaintenanceAlert']);
| Route::post('/example/conditional', [ExampleNotificationController::class, 'sendConditionalNotification']);
|
*/
