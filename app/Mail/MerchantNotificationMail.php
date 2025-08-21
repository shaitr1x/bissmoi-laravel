<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class MerchantNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $merchant;
    public $title;
    public $message;

    public function __construct(User $merchant, $title, $message)
    {
        $this->merchant = $merchant;
        $this->title = $title;
        $this->message = $message;
    }

    public function build()
    {
        return $this->from('noreply@bissmoi.com', 'Bissmoi')
            ->subject('Notification - ' . $this->title)
            ->view('emails.merchant_notification');
    }
}
