<?php
namespace App\Services\Hr;

use App\Models\Hr\RiwayatApproval;
use App\Models\Hr\RiwayatDataDiri;
use App\Models\Shared\Pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PegawaiService
{
    /**
     * Create a new Pegawai with initial Data Diri history.
     */
    public function createPegawai(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Buat Pegawai header (skeleton)
            $pegawai = Pegawai::create();

            // 2. Buat RiwayatDataDiri (data awal pegawai) — latest_riwayatapproval_id null dulu
            $data['pegawai_id'] = $pegawai->pegawai_id;
            $riwayat            = RiwayatDataDiri::create($data);

            // 3. Buat approval record yang langsung Approved (karena ini registrasi awal oleh admin)
            $approval = RiwayatApproval::create([
                'model'      => RiwayatDataDiri::class,
                'model_id'   => $riwayat->riwayatdatadiri_id,
                'status'     => 'Approved',
                'pejabat'    => Auth::user()->name ?? 'System',
                'keterangan' => 'Pendaftaran pegawai baru',
            ]);

            // 4. Update pointer di RiwayatDataDiri dan Pegawai
            $riwayat->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);
            $pegawai->update(['latest_riwayatdatadiri_id' => $riwayat->riwayatdatadiri_id]);

            logActivity('hr', "Mendaftarkan pegawai baru: {$riwayat->nama}", $pegawai);

            return $pegawai;
        });
    }

    /**
     * Delete a Pegawai (soft delete).
     */
    public function delete($pegawaiId)
    {
        return DB::transaction(function () use ($pegawaiId) {
            $pegawai = Pegawai::findOrFail($pegawaiId);
            logActivity('hr', "Menghapus data pegawai: {$pegawai->nama}", $pegawai);
            $pegawai->delete();
            return true;
        });
    }

    /**
     * Get filtered query for DataTables.
     */
    public function getFilteredQuery($request)
    {
        $query = Pegawai::query()
            ->with([
                'latestDataDiri.posisi',
                'latestDataDiri.departemen',
                'latestStatusPegawai.statusPegawai',
                'latestInpassing.golonganInpassing',
                'atasanSatu.latestDataDiri', // Need name of atasan
                'atasanDua.latestDataDiri',  // Need name of atasan
            ]);

        // Add filtering if needed
        // if ($request->filled('status_pegawai')) { ... }

        return $query;
    }
}
