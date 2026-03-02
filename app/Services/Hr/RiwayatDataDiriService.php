<?php
namespace App\Services\Hr;

use App\Models\Hr\RiwayatDataDiri;
use App\Models\Shared\Pegawai;
use Illuminate\Support\Facades\DB;

class RiwayatDataDiriService
{
    public function __construct(protected ApprovalService $approvalService)
    {}

    public function requestChange(Pegawai $pegawai, array $data)
    {
        return DB::transaction(function () use ($pegawai, $data) {
            $data['pegawai_id'] = $pegawai->pegawai_id;
            $model              = RiwayatDataDiri::create($data);

            $approval = $this->approvalService->createRequest(
                RiwayatDataDiri::class,
                $model->riwayatdatadiri_id,
                'Pengajuan Perubahan Data Diri'
            );

            $model->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);
            return $model;
        });
    }

    public function processApproval(int $approvalId, string $status, ?string $reason = null)
    {
        return DB::transaction(function () use ($approvalId, $status, $reason) {
            $approval = $this->approvalService->processApproval($approvalId, $status, $reason);

            $model   = RiwayatDataDiri::findOrFail($approval->model_id);
            $pegawai = Pegawai::findOrFail($model->pegawai_id);
            $pegawai->update(['latest_riwayatdatadiri_id' => $model->riwayatdatadiri_id]);
            logActivity('hr', "Memproses ({$status}) Data Diri: {$pegawai->nama}", $pegawai);

            return $approval;
        });
    }
}
