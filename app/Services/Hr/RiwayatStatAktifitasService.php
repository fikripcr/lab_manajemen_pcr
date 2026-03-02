<?php
namespace App\Services\Hr;

use App\Models\Hr\RiwayatStatAktifitas;
use App\Models\Shared\Pegawai;
use Illuminate\Support\Facades\DB;

class RiwayatStatAktifitasService
{
    public function __construct(protected ApprovalService $approvalService)
    {}

    public function requestChange(Pegawai $pegawai, array $data)
    {
        return DB::transaction(function () use ($pegawai, $data) {
            $data['pegawai_id'] = $pegawai->pegawai_id;
            $data['before_id']  = $pegawai->latest_riwayatstataktifitas_id;
            $model              = RiwayatStatAktifitas::create($data);

            $approval = $this->approvalService->createRequest(
                RiwayatStatAktifitas::class,
                $model->riwayatstataktifitas_id,
                'Pengajuan Perubahan Status Aktifitas'
            );

            $model->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);
            return $model;
        });
    }

    public function processApproval(int $approvalId, string $status, ?string $reason = null)
    {
        return DB::transaction(function () use ($approvalId, $status, $reason) {
            $approval = $this->approvalService->processApproval($approvalId, $status, $reason);

            $model   = RiwayatStatAktifitas::findOrFail($approval->model_id);
            $pegawai = Pegawai::findOrFail($model->pegawai_id);
            $pegawai->update(['latest_riwayatstataktifitas_id' => $model->riwayatstataktifitas_id]);
            logActivity('hr', "Memproses ({$status}) Status Aktifitas: {$pegawai->nama}", $pegawai);

            return $approval;
        });
    }
}
