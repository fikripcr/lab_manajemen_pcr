<?php
namespace App\Services\Hr;

use App\Models\Hr\RiwayatJabStruktural;
use App\Models\Shared\Pegawai;
use Illuminate\Support\Facades\DB;

class RiwayatStrukturalService
{
    public function __construct(protected ApprovalService $approvalService)
    {}

    /**
     * Request a NEW Struktural assignment (Status: Pending).
     */
    public function requestAddition(Pegawai $pegawai, array $data)
    {
        return DB::transaction(function () use ($pegawai, $data) {
            $data['pegawai_id'] = $pegawai->pegawai_id;

            // 1. Insert into history table
            $riwayat = RiwayatJabStruktural::create($data);

            // 2. Create approval request
            $approval = $this->approvalService->createRequest(
                RiwayatJabStruktural::class,
                $riwayat->riwayatjabstruktural_id,
                'Pengajuan Penambahan Jabatan Struktural'
            );

            // 3. Update pointer on history record
            $riwayat->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);

            return $riwayat;
        });
    }

    /**
     * Request a CHANGE to existing Struktural assignment (Status: Pending).
     */
    public function requestChange(Pegawai $pegawai, array $data, ?RiwayatJabStruktural $existingModel = null)
    {
        return DB::transaction(function () use ($pegawai, $data, $existingModel) {
            if ($existingModel) {
                // Clone existing and update with new data for the new row
                $newData = array_merge($existingModel->getAttributes(), $data);
                unset($newData[$existingModel->getKeyName()]);
                unset($newData['created_at'], $newData['updated_at'], $newData['deleted_at'], $newData['latest_riwayatapproval_id']);

                $newData['before_id'] = $existingModel->riwayatjabstruktural_id;
                $data                 = $newData;
            }

            $data['pegawai_id'] = $pegawai->pegawai_id;

            // 1. Insert new row
            $riwayat = RiwayatJabStruktural::create($data);

            // 2. Create approval
            $approval = $this->approvalService->createRequest(
                RiwayatJabStruktural::class,
                $riwayat->riwayatjabstruktural_id,
                $existingModel ? 'Pengajuan Perubahan Jabatan Struktural' : 'Pengajuan Penambahan Jabatan Struktural'
            );

            // 3. Update pointer
            $riwayat->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);

            return $riwayat;
        });
    }

    /**
     * Finalizing approval results (Updating Pegawai FK).
     */
    public function processApproval(int $approvalId, string $status, ?string $reason = null)
    {
        return DB::transaction(function () use ($approvalId, $status, $reason) {
            // 1. Update approval record
            $approval = $this->approvalService->processApproval($approvalId, $status, $reason);

            // 2. Resolve history and pegawai
            $model   = RiwayatJabStruktural::findOrFail($approval->model_id);
            $pegawai = Pegawai::findOrFail($model->pegawai_id);

            // 3. Always update the latest pointer on pegawai house regardless of status (as requested)
            $pegawai->update(['latest_riwayatjabstruktural_id' => $model->riwayatjabstruktural_id]);

            logActivity('hr', "Memproses ({$status}) Jabatan Struktural: {$pegawai->nama}", $pegawai);

            return $approval;
        });
    }

    /**
     * End a structural assignment (direct action, no approval required usually, or just direct update).
     */
    public function endStruktural(RiwayatJabStruktural $struktural, $tglAkhir)
    {
        return DB::transaction(function () use ($struktural, $tglAkhir) {
            $updated = $struktural->update(['tgl_akhir' => $tglAkhir]);
            logActivity('hr', "Mengakhiri struktural untuk pegawai: {$struktural->pegawai->nama}", $struktural->pegawai);
            return $updated;
        });
    }

    /**
     * Delete an entry (direct).
     */
    public function deleteStruktural(Pegawai $pegawai, RiwayatJabStruktural $struktural)
    {
        return DB::transaction(function () use ($pegawai, $struktural) {
            $struktural->delete();

            // Revert latest pointer if needed
            if ($pegawai->latest_riwayatjabstruktural_id == $struktural->riwayatjabstruktural_id) {
                $latest = RiwayatJabStruktural::where('pegawai_id', $pegawai->pegawai_id)
                    ->orderByDesc('tgl_awal')
                    ->first();
                $pegawai->update(['latest_riwayatjabstruktural_id' => $latest?->riwayatjabstruktural_id]);
            }

            logActivity('hr', "Menghapus data struktural untuk pegawai: {$pegawai->nama}", $pegawai);

            return true;
        });
    }
}
