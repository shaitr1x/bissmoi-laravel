<?php
namespace App\Http\Controllers;

use App\Events\UserNotificationCreated;
use App\Events\AdminNotificationCreated;
use App\Models\UserNotification;
use App\Services\NotificationService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BecomeMerchantController extends Controller
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function showForm()
    {
        return view('auth.become-merchant');
    }

    public function submit(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'merchant_description' => 'required|string|max:1000',
            'merchant_phone' => 'required|string|max:20',
            'merchant_address' => 'required|string|max:255',
        ]);
        $user->update([
            'role' => 'merchant',
            'shop_name' => $request->shop_name,
            'merchant_description' => $request->merchant_description,
            'merchant_phone' => $request->merchant_phone,
            'merchant_address' => $request->merchant_address,
            'merchant_approved' => false,
        ]);

        // Notification côté client
        $this->notificationService->sendToUser(
            $user->id,
            'Demande commerçant envoyée',
            'Votre demande pour devenir commerçant a bien été envoyée. Elle sera validée par un administrateur.',
            'info',
            'store',
            null,
            true // Envoyer email
        );

        // Broadcast désactivé en local pour éviter l'erreur Pusher/Websockets
        $adminNotif = \App\Models\AdminNotification::createNotification(
            'Nouvelle demande commerçant',
            'Un utilisateur a soumis une demande pour devenir commerçant.',
            'info',
            'user-plus',
            ['user_id' => $user->id, 'user_email' => $user->email]
        );
        // event(new UserNotificationCreated($user->id, $userNotif));
        // event(new AdminNotificationCreated($adminNotif));

        return redirect()->route('dashboard')->with('success', 'Votre demande pour devenir commerçant a été envoyée avec succès. Elle sera validée par un administrateur.');
    }
}
