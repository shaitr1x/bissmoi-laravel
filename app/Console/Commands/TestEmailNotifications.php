<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailNotifications extends Command
{
    protected $signature = 'test:email-notifications';
    protected $description = 'Teste l\'envoi d\'emails aux admins';

    public function handle()
    {
        $this->info('=== Test des notifications email aux admins ===');

        // Liste des emails admins actuels
        $adminEmails = [
            'noreply@bissmoi.com',
            'jordymbele948@gmail.com',
            'danieltambe522@gmail.com',
            'danielmama881@gmail.com',
            'badoanagabriel94@gmail.com'
        ];

        $this->info('Emails admins configurés :');
        foreach ($adminEmails as $email) {
            $this->line("- {$email}");
        }

        $this->info('\nTest d\'envoi d\'email...');

        try {
            foreach ($adminEmails as $email) {
                Mail::raw(
                    "Test d'email administrateur BISSMOI.\n\nCeci est un email de test pour vérifier que les notifications fonctionnent correctement.\n\nSi vous recevez cet email, la configuration fonctionne !",
                    function ($message) use ($email) {
                        $message->to($email)
                            ->subject('Test notification admin - BISSMOI');
                    }
                );
                $this->info("✅ Email envoyé à : {$email}");
            }
            
            $this->info("\n✅ Tous les emails ont été envoyés avec succès !");
            $this->warn("Vérifiez vos boîtes email (y compris les spams).");
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de l'envoi : " . $e->getMessage());
            $this->warn("Vérifiez la configuration SMTP dans le fichier .env");
        }

        return Command::SUCCESS;
    }
}
