<?php
namespace App\Services\Hr;

use App\Models\Hr\PengembanganDiri;
use App\Models\Shared\Pegawai;
use Illuminate\Support\Facades\DB;

class PengembanganDiriService
{
    public function __construct(protected ApprovalService $approvalService)
    {}

    public function requestAddition(Pegawai $pegawai, array $data)
    {
        return DB::transaction(function () use ($pegawai, $data) {
            $data['pegawai_id'] = $pegawai->pegawai_id;
            $model              = PengembanganDiri::create($data);

            $approval = $this->approvalService->createRequest(
                PengembanganDiri::class,
                $model->pengembangandiri_id,
                'Pengajuan Penambahan Pengembangan Diri'
            );

            $model->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);

            return $model;
        });
    }

    public function requestChange(Pegawai $pegawai, array $data, ?PengembanganDiri $existingModel = null)
    {
        return DB::transaction(function () use ($pegawai, $data, $existingModel) {
            if ($existingModel) {
                $data = array_merge($existingModel->getAttributes(), $data);
                unset($data[$existingModel->getKeyName()]);
                unset($data['created_at'], $data['updated_at'], $data['deleted_at'], $data['latest_riwayatapproval_id']);
                $data['before_id'] = $existingModel->pengembangandiri_id;
            }

            $data['pegawai_id'] = $pegawai->pegawai_id;
            $model              = PengembanganDiri::create($data);

            $approval = $this->approvalService->createRequest(
                PengembanganDiri::class,
                $model->pengembangandiri_id,
                $existingModel ? 'Pengajuan Perubahan Pengembangan Diri' : 'Pengajuan Penambahan Pengembangan Diri'
            );

            $model->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);

            return $model;
        });
    }
}
