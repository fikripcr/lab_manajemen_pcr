<?php
namespace App\Services\Hr;

use App\Models\Hr\RiwayatInpassing;
use App\Models\Shared\Pegawai;
use Illuminate\Support\Facades\DB;

class RiwayatInpassingService
{
    public function __construct(protected ApprovalService $approvalService)
    {}

    public function requestChange(Pegawai $pegawai, array $data, ?RiwayatInpassing $existingModel = null)
    {
        return DB::transaction(function () use ($pegawai, $data, $existingModel) {
            if ($existingModel) {
                $data = array_merge($existingModel->getAttributes(), $data);
                unset($data[$existingModel->getKeyName()]);
                unset($data['created_at'], $data['updated_at'], $data['deleted_at'], $data['latest_riwayatapproval_id']);
                $data['before_id'] = $existingModel->riwayatinpassing_id;
            }

            $data['pegawai_id'] = $pegawai->pegawai_id;
            $model              = RiwayatInpassing::create($data);

            $approval = $this->approvalService->createRequest(
                RiwayatInpassing::class,
                $model->riwayatinpassing_id,
                $existingModel ? 'Pengajuan Perubahan Inpassing' : 'Pengajuan Penambahan Inpassing'
            );

            $model->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);
            return $model;
        });
    }

    public function processApproval(int $approvalId, string $status, ?string $reason = null)
    {
        return DB::transaction(function () use ($approvalId, $status, $reason) {
            $approval = $this->approvalService->processApproval($approvalId, $status, $reason);

            $model   = RiwayatInpassing::findOrFail($approval->model_id);
            $pegawai = Pegawai::findOrFail($model->pegawai_id);
            $pegawai->update(['latest_riwayatinpassing_id' => $model->riwayatinpassing_id]);
            logActivity('hr', "Memproses ({$status}) Inpassing: {$pegawai->nama}", $pegawai);

            return $approval;
        });
    }
}
