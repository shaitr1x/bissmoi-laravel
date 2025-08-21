<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ClientNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $title;
    public $message;

    public function __construct(User $client, $title, $message)
    {
        $this->client = $client;
        $this->title = $title;
        $this->message = $message;
    }

    public function build()
    {
        return $this->from('noreply@bissmoi.com', 'Bissmoi')
            ->subject('Notification - ' . $this->title)
            ->view('emails.client_notification');
    }
}
