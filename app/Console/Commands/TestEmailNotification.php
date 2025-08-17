<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;
use App\Models\User;

class TestEmailNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-notification {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester l\'envoi de notifications par email';

    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if ($userId) {
            $user = User::find($userId);
        } else {
            $user = User::first();
        }

        if (!$user) {
            $this->error('Aucun utilisateur trouvé.');
            return Command::FAILURE;
        }

        $this->info("Test de notification email pour: {$user->name} ({$user->email})");

        try {
            $this->notificationService->sendToUser(
                $user->id,
                'Test de notification BISSMOI',
                'Ceci est un test de notification par email depuis la plateforme BISSMOI. Si vous recevez ce message, le système de notification fonctionne correctement !',
                'info',
                'bell',
                null,
                true // Activer l'email
            );

            $this->info('✓ Notification envoyée avec succès !');
            $this->info('Vérifiez la boîte email ou les logs pour confirmer l\'envoi.');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('✗ Erreur lors de l\'envoi: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
