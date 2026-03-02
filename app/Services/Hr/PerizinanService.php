<?php
namespace App\Services\Hr;

use App\Models\Hr\Perizinan;
use App\Models\Hr\RiwayatApproval;
use Illuminate\Support\Facades\DB;

class PerizinanService
{
    public function __construct(protected ApprovalService $approvalService)
    {}

    /**
     * Simpan pengajuan perizinan baru.
     * Alur: Insert Perizinan → ApprovalService.createRequest → update latest_riwayatapproval_id
     */
    public function store(array $data): Perizinan
    {
        return DB::transaction(function () use ($data) {
            // 1. Insert Perizinan — latest_riwayatapproval_id = null dulu
            $perizinan = Perizinan::create([
                'jenisizin_id'           => $data['jenisizin_id'],
                'pengusul'               => $data['pengusul'],
                'pekerjaan_ditinggalkan' => $data['pekerjaan_ditinggalkan'] ?? null,
                'keterangan'             => $data['keterangan'] ?? null,
                'alamat_izin'            => $data['alamat_izin'] ?? null,
                'tgl_awal'               => $data['tgl_awal'],
                'tgl_akhir'              => $data['tgl_akhir'],
                'jam_awal'               => $data['jam_awal'] ?? null,
                'jam_akhir'              => $data['jam_akhir'] ?? null,
                'periode'                => date('Y'),
            ]);

            // 2. ApprovalService buat approval record, return approval dengan riwayatapproval_id
            $approval = $this->approvalService->createRequest(
                Perizinan::class,
                $perizinan->perizinan_id,
                'Pengajuan Perizinan'
            );

            // 3. Update perizinan agar tunjuk ke approval yang baru
            $perizinan->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);

            logActivity('hr', 'Mengajukan perizinan: ' . ($perizinan->jenisIzin?->nama ?? 'N/A'), $perizinan);

            return $perizinan;
        });
    }

    /**
     * Update data perizinan yang masih draft/belum diproses.
     */
    public function update(Perizinan $perizinan, array $data): Perizinan
    {
        return DB::transaction(function () use ($perizinan, $data) {
            $perizinan->update($data);

            logActivity('hr', 'Mengupdate perizinan: ' . ($perizinan->jenisIzin?->nama ?? 'N/A'), $perizinan);

            return $perizinan;
        });
    }

    /**
     * Proses approval perizinan (Approved / Rejected / Tangguhkan).
     * Terintegrasi dengan ApprovalService.
     */
    public function processApproval(int $approvalId, string $status, ?string $reason = null, ?string $pejabat = null): RiwayatApproval
    {
        return DB::transaction(function () use ($approvalId, $status, $reason, $pejabat) {
            $approval = $this->approvalService->processApproval($approvalId, $status, $reason, $pejabat);

            // Sync status to model for easy access if needed (Perizinan usually relies on accessor)
            $perizinan = Perizinan::findOrFail($approval->model_id);
            // latest_riwayatapproval_id is already updated during store() or previous actions

            logActivity('hr', "Memproses approval perizinan ({$status}): " . ($perizinan->jenisIzin?->nama ?? 'N/A'), $perizinan);

            return $approval;
        });
    }

    /**
     * Legacy/Local handler for approval from Perizinan detail page.
     */
    public function approve(Perizinan $perizinan, array $data): void
    {
        if (! $perizinan->latest_riwayatapproval_id) {
            // Jika belum ada request, buat dulu (harusnya tidak terjadi jika lewat store())
            $approval = $this->approvalService->createRequest(Perizinan::class, $perizinan->perizinan_id);
            $perizinan->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);
        }

        $this->processApproval($perizinan->latest_riwayatapproval_id, $data['status'], $data['keterangan'] ?? null, $data['pejabat'] ?? null);
    }
}
