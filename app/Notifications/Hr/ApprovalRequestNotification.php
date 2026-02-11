<?php

namespace App\Notifications\Hr;

use App\Models\Hr\RiwayatApproval;
use App\Models\Hr\Pegawai;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovalRequestNotification extends Notification
{
    use Queueable;

    public $approval;
    public $pegawai;
    public $requestedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(RiwayatApproval $approval, ?Pegawai $pegawai, ?User $requestedBy)
    {
        $this->approval = $approval;
        $this->pegawai = $pegawai;
        $this->requestedBy = $requestedBy;
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
        $pegawaiName = $this->pegawai?->nama ?? 'Tidak diketahui';
        $requestedByName = $this->requestedBy?->name ?? 'System';

        return (new MailMessage)
            ->subject('Pengajuan Perubahan Data HR - ' . $pegawaiName)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Ada pengajuan perubahan data yang menunggu persetujuan Anda:')
            ->line('**Pegawai:** ' . $pegawaiName)
            ->line('**Tipe Perubahan:** ' . $modelClass)
            ->line('**Keterangan:** ' . ($this->approval->keterangan ?? 'Tidak ada keterangan'))
            ->line('**Diajukan oleh:** ' . $requestedByName)
            ->line('**Tanggal:** ' . $this->approval->created_at->format('d M Y H:i'))
            ->action('Lihat Detail', route('hr.approval.index'))
            ->line('Mohon segera melakukan review dan persetujuan.')
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
        $pegawaiName = $this->pegawai?->nama ?? 'Tidak diketahui';

        return [
            'title' => 'Pengajuan Perubahan Data HR',
            'message' => $pegawaiName . ' mengajukan perubahan ' . $modelClass,
            'type' => 'approval_request',
            'approval_id' => $this->approval->riwayatapproval_id,
            'pegawai_id' => $this->pegawai?->pegawai_id,
            'model' => $this->approval->model,
            'status' => $this->approval->status,
            'requested_by' => $this->requestedBy?->name,
            'created_at' => $this->approval->created_at,
        ];
    }
}
