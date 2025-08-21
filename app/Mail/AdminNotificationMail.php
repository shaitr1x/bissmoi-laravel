<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $message;
    public $type;
    public $icon;

    public function __construct($title, $message, $type = 'info', $icon = 'bell')
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->icon = $icon;
    }

    public function build()
    {
        return $this->from('noreply@bissmoi.com', 'Bissmoi')
            ->subject('Notification Admin - ' . $this->title)
            ->view('emails.admin_notification');
    }
}
