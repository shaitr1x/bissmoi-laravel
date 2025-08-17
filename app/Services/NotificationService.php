<?php

namespace App\Services;

use App\Models\UserNotification;
use App\Models\User;
use App\Notifications\UserEmailNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Envoyer une notification à un utilisateur (interface + email)
     */
    public static function sendToUser($userId, $title, $message, $type = 'info', $icon = 'info', $actionUrl = null, $actionText = 'Voir détails', $sendEmail = true)
    {
        // Créer la notification dans l'interface
        UserNotification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => $icon,
            'is_read' => false,
        ]);

        // Envoyer par email si demandé
        if ($sendEmail) {
            $user = User::find($userId);
            if ($user && $user->email) {
                $user->notify(new UserEmailNotification($title, $message, $type, $actionUrl, $actionText));
            }
        }
    }

    /**
     * Envoyer une notification à plusieurs utilisateurs
     */
    public static function sendToUsers($userIds, $title, $message, $type = 'info', $icon = 'info', $actionUrl = null, $actionText = 'Voir détails', $sendEmail = true)
    {
        foreach ($userIds as $userId) {
            self::sendToUser($userId, $title, $message, $type, $icon, $actionUrl, $actionText, $sendEmail);
        }
    }

    /**
     * Envoyer une notification à tous les utilisateurs
     */
    public static function sendToAllUsers($title, $message, $type = 'info', $icon = 'info', $actionUrl = null, $actionText = 'Voir détails', $sendEmail = true)
    {
        $userIds = User::pluck('id')->toArray();
        self::sendToUsers($userIds, $title, $message, $type, $icon, $actionUrl, $actionText, $sendEmail);
    }
}
