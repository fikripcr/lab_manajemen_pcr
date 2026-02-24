<?php
namespace App\Services\Hr;

use App\Models\Hr\Lembur;
use App\Models\Hr\RiwayatApproval;
use Illuminate\Support\Facades\DB;

class LemburService
{
    /**
     * Store new lembur
     */
    public function store(array $data): Lembur
    {
        return DB::transaction(function () use ($data) {
            // 1. Create lembur
            $lembur = Lembur::create([
                'pengusul_id'      => $data['pengusul_id'],
                'judul'            => $data['judul'],
                'uraian_pekerjaan' => $data['uraian_pekerjaan'] ?? null,
                'alasan'           => $data['alasan'] ?? null,
                'tgl_pelaksanaan'  => $data['tgl_pelaksanaan'],
                'jam_mulai'        => $data['jam_mulai'],
                'jam_selesai'      => $data['jam_selesai'],
            ]);

            // 2. Attach pegawai
            if (! empty($data['pegawai_ids'])) {
                foreach ($data['pegawai_ids'] as $pegawaiId) {
                    $lembur->pegawais()->attach($pegawaiId, [
                        'catatan'          => $data['catatan_pegawai'][$pegawaiId] ?? null,
                    ]);
                }
            }

            // 3. Create approval record
            $approval = RiwayatApproval::create([
                'model'         => Lembur::class,
                'model_id'      => $lembur->lembur_id,
                'status'        => 'Diajukan',
                'jenis_jabatan' => 'Kepala Bagian',
                'keterangan'    => 'Menunggu persetujuan',
            ]);

            $lembur->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);

            logActivity('hr', "Menambahkan pengajuan lembur: {$lembur->judul}", $lembur);

            return $lembur->fresh(['pegawais', 'latestApproval']);
        });
    }

    /**
     * Update existing lembur
     */
    public function update(Lembur $lembur, array $data): Lembur
    {
        return DB::transaction(function () use ($lembur, $data) {
            // 1. Update lembur
            $lembur->update([
                'pengusul_id'      => $data['pengusul_id'],
                'judul'            => $data['judul'],
                'uraian_pekerjaan' => $data['uraian_pekerjaan'] ?? null,
                'alasan'           => $data['alasan'] ?? null,
                'tgl_pelaksanaan'  => $data['tgl_pelaksanaan'],
                'jam_mulai'        => $data['jam_mulai'],
                'jam_selesai'      => $data['jam_selesai'],
            ]);

            // 2. Sync pegawai
            $syncData = [];
            if (! empty($data['pegawai_ids'])) {
                foreach ($data['pegawai_ids'] as $pegawaiId) {
                    $syncData[$pegawaiId] = [
                        'catatan'          => $data['catatan_pegawai'][$pegawaiId] ?? null,
                    ];
                }
            }
            $lembur->pegawais()->sync($syncData);

            logActivity('hr', "Mengupdate pengajuan lembur: {$lembur->judul}", $lembur);

            return $lembur->fresh(['pegawais', 'latestApproval']);
        });
    }

    /**
     * Approve or reject lembur
     */
    public function approve(Lembur $lembur, array $data): void
    {
        DB::transaction(function () use ($lembur, $data) {
            // Create NEW approval record to maintain history
            $approval = RiwayatApproval::create([
                'model'      => Lembur::class,
                'model_id'   => $lembur->lembur_id,
                'status'     => $data['status'],
                'pejabat'    => $data['pejabat'], // Nama pejabat yang melakukan approval
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            // Update lembur status pointer
            $lembur->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);

            logActivity('hr', "Memproses approval lembur ({$data['status']}): {$lembur->judul}", $lembur);
        });
    }
}
