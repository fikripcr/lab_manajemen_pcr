<?php
namespace App\Services\Hr;

use App\Models\Hr\Perizinan;
use App\Models\Hr\RiwayatApproval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerizinanService
{
    /**
     * Store new perizinan
     */
    public function store(array $data): Perizinan
    {
        return DB::transaction(function () use ($data) {
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

            $approval = RiwayatApproval::create([
                'model'            => 'Perizinan',
                'model_id'         => $perizinan->perizinan_id,
                'status'           => 'Draft',
                'created_by_email' => Auth::user()?->email,
            ]);

            $perizinan->update([
                'latest_riwayatapproval_id' => $approval->riwayatapproval_id,
            ]);

            logActivity('hr', "Mengajukan perizinan: " . ($perizinan->jenisIzin?->nama ?? 'N/A'), $perizinan);

            return $perizinan;
        });
    }

    /**
     * Update existing perizinan
     */
    public function update(Perizinan $perizinan, array $data): Perizinan
    {
        return DB::transaction(function () use ($perizinan, $data) {
            $perizinan->update($data);

            logActivity('hr', "Mengupdate perizinan: " . ($perizinan->jenisIzin?->nama ?? 'N/A'), $perizinan);

            return $perizinan;
        });
    }

    /**
     * Approval process for perizinan
     */
    public function approve(Perizinan $perizinan, array $data): void
    {
        DB::transaction(function () use ($perizinan, $data) {
            $approval = RiwayatApproval::create([
                'model'      => 'Perizinan',
                'model_id'   => $perizinan->perizinan_id,
                'status'     => $data['status'],
                'pejabat'    => $data['pejabat'] ?? null,
                'keterangan' => $data['keterangan_approval'] ?? null,
            ]);

            $perizinan->update([
                'latest_riwayatapproval_id' => $approval->riwayatapproval_id,
            ]);

            logActivity('hr', "Memproses approval perizinan ({$data['status']})", $perizinan);
        });
    }
}
