<?php

namespace App\Services\Hr;

use App\Models\Hr\Pegawai;
use App\Models\Hr\RiwayatStatPegawai;
use App\Models\Hr\StatusPegawai;
use Illuminate\Support\Facades\DB;

class RiwayatStatPegawaiService
{
    public function __construct(protected ApprovalService $approvalService) {}

    /**
     * Get data dropdown status pegawai
     */
    public function getStatusPegawai()
    {
        return StatusPegawai::where('is_active', 1)->get();
    }

    /**
     * Get base query for DataTable
     */
    public function getDataQuery(array $filters = [])
    {
        $query = RiwayatStatPegawai::with(['hr_pegawai', 'statusPegawai'])->select('hr_riwayat_statpegawai.*');

        if (! empty($filters['pegawai_id'])) {
            $query->where('pegawai_id', decryptIdIfEncrypted($filters['pegawai_id']));
        }

        if (! empty($filters['status_pegawai_id']) && $filters['status_pegawai_id'] !== 'all') {
            $query->where('status_pegawai_id', $filters['status_pegawai_id']);
        }

        return $query;
    }

    public function requestChange(Pegawai $pegawai, array $data)
    {
        return DB::transaction(function () use ($pegawai, $data) {
            $data['pegawai_id'] = $pegawai->pegawai_id;
            $data['before_id'] = $pegawai->latest_riwayatstatpegawai_id;
            $model = RiwayatStatPegawai::create($data);

            $approval = $this->approvalService->createRequest(
                RiwayatStatPegawai::class,
                $model->riwayatstatpegawai_id,
                'Pengajuan Perubahan Status Kepegawaian'
            );

            $model->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);

            return $model;
        });
    }

    public function processApproval(int $approvalId, string $status, ?string $reason = null)
    {
        return DB::transaction(function () use ($approvalId, $status, $reason) {
            $approval = $this->approvalService->processApproval($approvalId, $status, $reason);

            $model = RiwayatStatPegawai::findOrFail($approval->model_id);
            $pegawai = Pegawai::findOrFail($model->pegawai_id);
            $pegawai->update(['latest_riwayatstatpegawai_id' => $model->riwayatstatpegawai_id]);
            logActivity('hr', "Memproses ({$status}) Status Kepegawaian: {$pegawai->nama}", $pegawai);

            return $approval;
        });
    }
}
