<?php
namespace App\Services\Hr;

use App\Models\Hr\Lembur;
use App\Models\Hr\RiwayatApproval;
use Illuminate\Support\Facades\DB;

class LemburService
{
    public function __construct(protected ApprovalService $approvalService)
    {}

    /**
     * Simpan pengajuan lembur baru.
     * Alur: Insert Lembur + attach pegawai → ApprovalService.createRequest → update latest_riwayatapproval_id
     */
    public function store(array $data): Lembur
    {
        return DB::transaction(function () use ($data) {
            // 1. Insert Lembur — latest_riwayatapproval_id = null dulu
            $lembur = Lembur::create([
                'pengusul_id'      => $data['pengusul_id'],
                'judul'            => $data['judul'],
                'uraian_pekerjaan' => $data['uraian_pekerjaan'] ?? null,
                'alasan'           => $data['alasan'] ?? null,
                'tgl_pelaksanaan'  => $data['tgl_pelaksanaan'],
                'jam_mulai'        => $data['jam_mulai'],
                'jam_selesai'      => $data['jam_selesai'],
            ]);

            // 2. Attach pegawai ke lembur
            if (! empty($data['pegawai_ids'])) {
                foreach ($data['pegawai_ids'] as $pegawaiId) {
                    $lembur->pegawais()->attach($pegawaiId, [
                        'catatan' => $data['catatan_pegawai'][$pegawaiId] ?? null,
                    ]);
                }
            }

            // 3. ApprovalService buat approval record, return approval dengan riwayatapproval_id
            $approval = $this->approvalService->createRequest(
                Lembur::class,
                $lembur->lembur_id,
                'Pengajuan Lembur'
            );

            // 4. Update lembur agar tunjuk ke approval yang baru
            $lembur->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);

            logActivity('hr', "Mengajukan lembur: {$lembur->judul}", $lembur);

            return $lembur->fresh(['pegawais', 'latestApproval']);
        });
    }

    /**
     * Update data lembur.
     */
    public function update(Lembur $lembur, array $data): Lembur
    {
        return DB::transaction(function () use ($lembur, $data) {
            $lembur->update([
                'pengusul_id'      => $data['pengusul_id'],
                'judul'            => $data['judul'],
                'uraian_pekerjaan' => $data['uraian_pekerjaan'] ?? null,
                'alasan'           => $data['alasan'] ?? null,
                'tgl_pelaksanaan'  => $data['tgl_pelaksanaan'],
                'jam_mulai'        => $data['jam_mulai'],
                'jam_selesai'      => $data['jam_selesai'],
            ]);

            $syncData = [];
            if (! empty($data['pegawai_ids'])) {
                foreach ($data['pegawai_ids'] as $pegawaiId) {
                    $syncData[$pegawaiId] = [
                        'catatan' => $data['catatan_pegawai'][$pegawaiId] ?? null,
                    ];
                }
            }
            $lembur->pegawais()->sync($syncData);

            logActivity('hr', "Mengupdate lembur: {$lembur->judul}", $lembur);

            return $lembur->fresh(['pegawais', 'latestApproval']);
        });
    }

    /**
     * Proses approval lembur (Approved / Rejected / Tangguhkan).
     * Terintegrasi dengan ApprovalService.
     */
    public function processApproval(int $approvalId, string $status, ?string $reason = null, ?string $pejabat = null): RiwayatApproval
    {
        return DB::transaction(function () use ($approvalId, $status, $reason, $pejabat) {
            $approval = $this->approvalService->processApproval($approvalId, $status, $reason, $pejabat);

            $lembur = Lembur::findOrFail($approval->model_id);

            logActivity('hr', "Memproses approval lembur ({$status}): {$lembur->judul}", $lembur);

            return $approval;
        });
    }

    /**
     * Legacy/Local handler for approval from Lembur detail page.
     */
    public function approve(Lembur $lembur, array $data): void
    {
        if (! $lembur->latest_riwayatapproval_id) {
            $approval = $this->approvalService->createRequest(Lembur::class, $lembur->lembur_id);
            $lembur->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);
        }

        $this->processApproval($lembur->latest_riwayatapproval_id, $data['status'], $data['keterangan'] ?? null, $data['pejabat'] ?? null);
    }
}
