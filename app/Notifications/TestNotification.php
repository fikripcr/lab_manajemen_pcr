<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;

    private $channelPreference;

    /**
     * Create a new notification instance.
     */
    public function __construct($channelPreference = null)
    {
        $this->channelPreference = $channelPreference;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if ($this->channelPreference) {
            return [$this->channelPreference];
        }
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Test Email Notification')
            ->greeting('Hello!')
            ->line('This is a test email notification from the system.')
            ->line('If you are seeing this, email functionality is working correctly.')
            ->action('View Dashboard', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Test Notifikasi',
            'body' => 'Ini adalah notifikasi percobaan dari sistem.',
            'action_url' => url('/'),
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
