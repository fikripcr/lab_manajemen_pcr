<?php

namespace App\Notifications\Event;

use App\Models\Event\Rapat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RapatUndanganNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Rapat $rapat;
    protected string $jabatan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Rapat $rapat, string $jabatan = 'Peserta')
    {
        $this->rapat = $rapat;
        $this->jabatan = $jabatan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('Kegiatan.rapat.show', $this->rapat->encrypted_rapat_id);

        return (new MailMessage)
            ->subject('Undangan Rapat: ' . $this->rapat->judul_kegiatan)
            ->greeting('Yth. ' . ($notifiable->name ?? 'Bapak/Ibu'))
            ->line('Anda telah diundang untuk menghadiri rapat dengan detail berikut:')
            ->markdown('event.mail.undangan-rapat', [
                'rapat' => $this->rapat,
                'jabatan' => $this->jabatan,
            ])
            ->action('Lihat Detail Rapat', $url)
            ->line('Kami mengharapkan kehadiran Anda tepat waktu.')
            ->salutation('Terima kasih,');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Undangan Rapat',
            'message' => 'Anda diundang untuk menghadiri rapat: ' . $this->rapat->judul_kegiatan,
            'rapat_id' => $this->rapat->rapat_id,
            'encrypted_rapat_id' => $this->rapat->encrypted_rapat_id,
            'jabatan' => $this->jabatan,
            'type' => 'undangan_rapat',
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
