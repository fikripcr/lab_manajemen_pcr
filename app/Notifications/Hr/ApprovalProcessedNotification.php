<?php

namespace App\Notifications\Hr;

use App\Models\Hr\RiwayatApproval;
use App\Models\Hr\Pegawai;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovalProcessedNotification extends Notification
{
    use Queueable;

    public $approval;
    public $pegawai;
    public $processedBy;
    public $action;

    /**
     * Create a new notification instance.
     */
    public function __construct(RiwayatApproval $approval, ?Pegawai $pegawai, ?User $processedBy, string $action = 'approved')
    {
        $this->approval = $approval;
        $this->pegawai = $pegawai;
        $this->processedBy = $processedBy;
        $this->action = $action;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $modelClass = class_basename($this->approval->model);
        $processedByName = $this->processedBy?->name ?? 'System';
        $isApproved = $this->action === 'approved';
        
        $statusText = $isApproved ? 'Disetujui' : 'Ditolak';
        $statusColor = $isApproved ? 'hijau' : 'merah';

        return (new MailMessage)
            ->subject('Status Pengajuan ' . $statusText . ' - ' . $modelClass)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Pengajuan perubahan data Anda telah ' . $statusText . ':')
            ->line('**Tipe Perubahan:** ' . $modelClass)
            ->line('**Status:** ' . $statusText)
            ->line('**Diproses oleh:** ' . $processedByName)
            ->line('**Tanggal:** ' . now()->format('d M Y H:i'))
            ->line('**Keterangan:** ' . ($this->approval->keterangan ?? 'Tidak ada keterangan'))
            ->action('Lihat Detail', route('hr.pegawai.show', $this->pegawai?->encrypted_pegawai_id))
            ->line($isApproved 
                ? 'Perubahan data Anda telah disetujui dan diterapkan.'
                : 'Perubahan data Anda ditolak. Silakan hubungi HR untuk informasi lebih lanjut.'
            )
            ->salutation('Terima kasih, HR System');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $modelClass = class_basename($this->approval->model);
        $isApproved = $this->action === 'approved';
        
        return [
            'title' => 'Pengajuan ' . ($isApproved ? 'Disetujui' : 'Ditolak'),
            'message' => 'Pengajuan ' . $modelClass . ' Anda telah ' . ($isApproved ? 'disetujui' : 'ditolak'),
            'type' => 'approval_processed',
            'approval_id' => $this->approval->riwayatapproval_id,
            'pegawai_id' => $this->pegawai?->pegawai_id,
            'model' => $this->approval->model,
            'action' => $this->action,
            'status' => $this->approval->status,
            'processed_by' => $this->processedBy?->name,
            'created_at' => now(),
        ];
    }
}
