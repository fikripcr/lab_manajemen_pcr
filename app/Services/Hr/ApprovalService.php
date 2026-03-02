<?php
namespace App\Services\Hr;

use App\Models\Hr\RiwayatApproval;
use Exception;
use Illuminate\Support\Facades\Auth;

class ApprovalService
{
    /**
     * Buat approval record yang menunjuk ke sebuah model.
     * Hanya membuat record dan mengembalikannya — tidak tahu model apapun.
     */
    public function createRequest(string $modelClass, int $modelId, string $keterangan = 'Pengajuan Data'): RiwayatApproval
    {
        return RiwayatApproval::create([
            'model'      => $modelClass,
            'model_id'   => $modelId,
            'status'     => 'Pending',
            'keterangan' => $keterangan,
        ]);
    }

    /**
     * Proses approval dengan status apapun (Approved, Rejected, Tangguhkan, dll).
     * Hanya update approval record dan return $approval.
     * Post-action logic (update FK, logActivity) ditangani service spesifik masing-masing.
     *
     * @param  int     $approvalId
     * @param  string  $status      e.g. 'Approved', 'Rejected', 'Tangguhkan'
     * @param  string|null $reason  Keterangan tambahan (opsional)
     */
    public function processApproval(int $approvalId, string $status, ?string $reason = null, ?string $pejabat = null): RiwayatApproval
    {
        $approval = RiwayatApproval::findOrFail($approvalId);

        if ($approval->status !== 'Pending') {
            throw new Exception('Pengajuan ini sudah diproses sebelumnya.');
        }

        $approval->update([
            'status'     => $status,
            'pejabat'    => $pejabat ?? (Auth::user()->name ?? 'System'),
            'keterangan' => $reason ?? $approval->keterangan,
        ]);

        return $approval;
    }
}
