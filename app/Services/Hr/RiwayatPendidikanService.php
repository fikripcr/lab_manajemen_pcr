<?php
namespace App\Services\Hr;

use App\Models\Hr\RiwayatPendidikan;
use App\Models\Shared\Pegawai;
use Illuminate\Support\Facades\DB;

class RiwayatPendidikanService
{
    public function __construct(protected ApprovalService $approvalService)
    {}

    public function requestAddition(Pegawai $pegawai, array $data)
    {
        return DB::transaction(function () use ($pegawai, $data) {
            $data['pegawai_id'] = $pegawai->pegawai_id;
            $model              = RiwayatPendidikan::create($data);

            $approval = $this->approvalService->createRequest(
                RiwayatPendidikan::class,
                $model->riwayatpendidikan_id,
                'Pengajuan Penambahan Riwayat Pendidikan'
            );

            $model->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);
            return $model;
        });
    }

    public function requestChange(Pegawai $pegawai, array $data, ?RiwayatPendidikan $existingModel = null)
    {
        return DB::transaction(function () use ($pegawai, $data, $existingModel) {
            if ($existingModel) {
                $data = array_merge($existingModel->getAttributes(), $data);
                unset($data[$existingModel->getKeyName()]);
                unset($data['created_at'], $data['updated_at'], $data['deleted_at'], $data['latest_riwayatapproval_id']);
                $data['before_id'] = $existingModel->riwayatpendidikan_id;
            }

            $data['pegawai_id'] = $pegawai->pegawai_id;
            $model              = RiwayatPendidikan::create($data);

            $approval = $this->approvalService->createRequest(
                RiwayatPendidikan::class,
                $model->riwayatpendidikan_id,
                $existingModel ? 'Pengajuan Perubahan Riwayat Pendidikan' : 'Pengajuan Penambahan Riwayat Pendidikan'
            );

            $model->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);
            return $model;
        });
    }

    public function processApproval(int $approvalId, string $status, ?string $reason = null)
    {
        return DB::transaction(function () use ($approvalId, $status, $reason) {
            $approval = $this->approvalService->processApproval($approvalId, $status, $reason);

            $model   = RiwayatPendidikan::findOrFail($approval->model_id);
            $pegawai = Pegawai::findOrFail($model->pegawai_id);
            $pegawai->update(['latest_riwayatpendidikan_id' => $model->riwayatpendidikan_id]);
            logActivity('hr', "Memproses ({$status}) Riwayat Pendidikan: {$pegawai->nama}", $pegawai);

            return $approval;
        });
    }
}
