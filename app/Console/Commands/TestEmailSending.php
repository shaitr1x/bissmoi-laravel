<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserRegistered;
use App\Models\User;

class TestEmailSending extends Command
{
    protected $signature = 'test:email-sending';
    protected $description = 'Tester l\'envoi d\'emails admin';

    public function handle()
    {
        $this->info('=== Test d\'envoi d\'emails admin ===');

        // Liste des emails admin
        $adminEmails = [
            'noreply@bissmoi.com',
            'jordymbele948@gmail.com',
            'danieltambe522@gmail.com',
            'danielmama881@gmail.com',
            'badoanagabriel94@gmail.com'
        ];

        $this->info('Emails admin configurés :');
        foreach ($adminEmails as $email) {
            $this->line("- {$email}");
        }

        // Test 1: Envoi d'email simple
        $this->info("\n--- Test 1: Envoi d'email simple ---");
        try {
            Mail::raw(
                'Test d\'envoi d\'email depuis BISSMOI. Si vous recevez ce message, la configuration fonctionne.',
                function ($message) {
                    $message->to('danieltambe522@gmail.com')
                        ->subject('Test Email BISSMOI - ' . now());
                }
            );
            $this->info('✅ Email test envoyé avec succès');
        } catch (\Exception $e) {
            $this->error('❌ Erreur envoi email: ' . $e->getMessage());
        }

        // Test 2: Test avec classe NewUserRegistered
        $this->info("\n--- Test 2: Test classe NewUserRegistered ---");
        try {
            $testUser = new \stdClass();
            $testUser->name = 'Test User';
            $testUser->email = 'test@example.com';

            $adminEmails = [
                'danieltambe522@gmail.com'
            ];

            foreach ($adminEmails as $email) {
                Mail::to($email)->send(new NewUserRegistered((object)[
                    'name' => 'Utilisateur Test',
                    'email' => 'test@test.com',
                    'role' => 'client'
                ]));
            }
            $this->info('✅ Email NewUserRegistered envoyé avec succès');
        } catch (\Exception $e) {
            $this->error('❌ Erreur NewUserRegistered: ' . $e->getMessage());
        }

        // Vérifier la config mail
        $this->info("\n--- Configuration Mail ---");
        $this->line('MAIL_MAILER: ' . config('mail.default'));
        $this->line('MAIL_HOST: ' . config('mail.mailers.smtp.host'));
        $this->line('MAIL_PORT: ' . config('mail.mailers.smtp.port'));
        $this->line('MAIL_USERNAME: ' . config('mail.mailers.smtp.username'));
        $this->line('MAIL_FROM: ' . config('mail.from.address'));

        return 0;
    }
}
