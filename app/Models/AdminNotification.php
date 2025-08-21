<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'icon',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays(7));
    }

    // Méthodes
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public static function createNotification($title, $message, $type = 'info', $icon = null, $data = null)
    {
        $notification = self::create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => $icon,
            'data' => $data,
        ]);

        // Envoyer un email à tous les admins
        $adminEmails = [
            'yannicksongmy@gmail.com',
            'dokoalanfranck@gmail.com',
            'jordymbele948@gmail.com',
            'danieltambe522@gmail.com',
            'danielmama881@gmail.com',
            'badoanagabriel94@gmail.com',
        ];

        foreach ($adminEmails as $email) {
            \Mail::to($email)->send(new \App\Mail\AdminNotificationMail($title, $message, $type, $icon));
        }

        return $notification;
    }
}
