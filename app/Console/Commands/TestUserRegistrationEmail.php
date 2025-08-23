<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TestUserRegistrationEmail extends Command
{
    protected $signature = 'test:user-registration-email';
    protected $description = 'Teste l\'envoi d\'email lors de l\'inscription d\'un utilisateur';

    public function handle()
    {
        $this->info('=== Test email inscription utilisateur ===');

        // Créer un utilisateur de test (pas sauvé en base)
        $testUser = new User([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'city' => 'Yaoundé',
            'created_at' => now()
        ]);

        // Liste des emails admins
        $adminEmails = [
            'noreply@bissmoi.com',
            'jordymbele948@gmail.com',
            'danieltambe522@gmail.com',
            'danielmama881@gmail.com',
            'badoanagabriel94@gmail.com'
        ];

        $this->info('Test de l\'envoi des emails d\'inscription...');

        try {
            foreach ($adminEmails as $email) {
                Mail::to($email)->send(new \App\Mail\NewUserRegistered($testUser));
                $this->info("✅ Email NewUserRegistered envoyé à : {$email}");
            }

            // Test email de bienvenue
            Mail::to('test@example.com')->send(new \App\Mail\WelcomeUser($testUser));
            $this->info("✅ Email WelcomeUser envoyé à : test@example.com");

            $this->info("\n✅ Tous les emails d'inscription ont été envoyés !");

        } catch (\Exception $e) {
            $this->error("❌ Erreur : " . $e->getMessage());
            $this->warn("Détails de l'erreur : " . $e->getTraceAsString());
        }

        return Command::SUCCESS;
    }
}
