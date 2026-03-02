<?php
namespace App\Services\Hr;

use App\Models\Hr\Keluarga;
use App\Models\Shared\Pegawai;
use Illuminate\Support\Facades\DB;

class KeluargaService
{
    public function __construct(protected ApprovalService $approvalService)
    {}

    public function requestAddition(Pegawai $pegawai, array $data)
    {
        return DB::transaction(function () use ($pegawai, $data) {
            $data['pegawai_id'] = $pegawai->pegawai_id;
            $keluarga           = Keluarga::create($data);

            $approval = $this->approvalService->createRequest(
                Keluarga::class,
                $keluarga->keluarga_id,
                'Pengajuan Penambahan Data Keluarga'
            );

            $keluarga->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);
            return $keluarga;
        });
    }

    public function requestChange(Pegawai $pegawai, array $data, ?Keluarga $existingModel = null)
    {
        return DB::transaction(function () use ($pegawai, $data, $existingModel) {
            if ($existingModel) {
                $data = array_merge($existingModel->getAttributes(), $data);
                unset($data[$existingModel->getKeyName()]);
                unset($data['created_at'], $data['updated_at'], $data['deleted_at'], $data['latest_riwayatapproval_id']);
                $data['before_id'] = $existingModel->keluarga_id;
            }

            $data['pegawai_id'] = $pegawai->pegawai_id;
            $keluarga           = Keluarga::create($data);

            $approval = $this->approvalService->createRequest(
                Keluarga::class,
                $keluarga->keluarga_id,
                $existingModel ? 'Pengajuan Perubahan Data Keluarga' : 'Pengajuan Penambahan Data Keluarga'
            );

            $keluarga->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);
            return $keluarga;
        });
    }

    // Keluarga tidak punya header FK di pegawai — tidak perlu processApproval spesifik.
    // ApprovalController fallback: langsung panggil approvalService->processApproval()
}
