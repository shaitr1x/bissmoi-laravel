<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserEmailNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $type;
    public $actionUrl;
    public $actionText;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $message, $type = 'info', $actionUrl = null, $actionText = 'Voir détails')
    {
    $this->title = $title;
    $this->message = is_object($message) ? '' : (string) $message;
        $this->type = $type;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->subject($this->title . ' - BISSMOI')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line($this->message);

        // Ajouter un bouton d'action si fourni
        if ($this->actionUrl && $this->actionText) {
            $mailMessage->action($this->actionText, $this->actionUrl);
        }

        return $mailMessage
            ->line('---')
            ->line('Ceci est un message automatique de la plateforme BISSMOI.')
            ->salutation('L\'équipe BISSMOI');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
        ];
    }
}
