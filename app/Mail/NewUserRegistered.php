<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class NewUserRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->from('dokoalanfrack@gmail.com')
            ->to([
                'dokoalanfranck@gmail.com',
                'jordymbele948@gmail.com',
                'danieltambe522@gmail.com',
                'danielmama881@gmail.com',
                'badoanagabriel94@gmail.com'
            ])
            ->subject('Nouvel utilisateur inscrit sur Bissmoi')
            ->view('emails.new_user_registered');
    }
}
