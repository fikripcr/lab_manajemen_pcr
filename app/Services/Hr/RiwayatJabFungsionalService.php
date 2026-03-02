<?php
namespace App\Services\Hr;

use App\Models\Hr\RiwayatJabFungsional;
use App\Models\Shared\Pegawai;
use Illuminate\Support\Facades\DB;

class RiwayatJabFungsionalService
{
    public function __construct(protected ApprovalService $approvalService)
    {}

    public function requestChange(Pegawai $pegawai, array $data)
    {
        return DB::transaction(function () use ($pegawai, $data) {
            $data['pegawai_id'] = $pegawai->pegawai_id;
            $data['before_id']  = $pegawai->latest_riwayatjabfungsional_id;
            $model              = RiwayatJabFungsional::create($data);

            $approval = $this->approvalService->createRequest(
                RiwayatJabFungsional::class,
                $model->riwayatjabfungsional_id,
                'Pengajuan Perubahan Jabatan Fungsional'
            );

            $model->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);
            return $model;
        });
    }

    public function processApproval(int $approvalId, string $status, ?string $reason = null)
    {
        return DB::transaction(function () use ($approvalId, $status, $reason) {
            $approval = $this->approvalService->processApproval($approvalId, $status, $reason);

            $model   = RiwayatJabFungsional::findOrFail($approval->model_id);
            $pegawai = Pegawai::findOrFail($model->pegawai_id);
            $pegawai->update(['latest_riwayatjabfungsional_id' => $model->riwayatjabfungsional_id]);
            logActivity('hr', "Memproses ({$status}) Jabatan Fungsional: {$pegawai->nama}", $pegawai);

            return $approval;
        });
    }
}
