<?php

namespace App\Services\Hr;

use App\Models\Hr\Pegawai;
use App\Models\Hr\RiwayatApproval;
use App\Models\Hr\RiwayatDataDiri;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PegawaiService
{
    /**
     * Create a new Pegawai with initial Data Diri history.
     * Now populates both common columns and creates RiwayatDataDiri.
     */
    public function createPegawai(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Buat Pegawai header dengan common columns
            $pegawai = Pegawai::create([
                'nama' => $data['nama'] ?? null,
                'nip' => $data['nip'] ?? null,
                'email' => $data['email'] ?? null,
                'inisial' => $data['inisial'] ?? null,
                'no_hp' => $data['no_hp'] ?? null,
                'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
                'tempat_lahir' => $data['tempat_lahir'] ?? null,
                'tgl_lahir' => $data['tgl_lahir'] ?? null,
                'orgunit_posisi_id' => $data['orgunit_posisi_id'] ?? null,
                'orgunit_departemen_id' => $data['orgunit_departemen_id'] ?? null,
                'nidn' => $data['nidn'] ?? null,
                'no_ktp' => $data['no_ktp'] ?? null,
                'alamat' => $data['alamat'] ?? null,
                'status_nikah' => $data['status_nikah'] ?? null,
                'agama' => $data['agama'] ?? null,
                'gelar_depan' => $data['gelar_depan'] ?? null,
                'gelar_belakang' => $data['gelar_belakang'] ?? null,
                'bidang_ilmu' => $data['bidang_ilmu'] ?? null,
            ]);

            // 2. Buat RiwayatDataDiri (data awal pegawai) — latest_riwayatapproval_id null dulu
            $data['pegawai_id'] = $pegawai->pegawai_id;
            $riwayat = RiwayatDataDiri::create($data);

            // 3. Buat approval record yang langsung Approved (karena ini registrasi awal oleh admin)
            $approval = RiwayatApproval::create([
                'pegawai_id' => $pegawai->pegawai_id,
                'model' => RiwayatDataDiri::class,
                'model_id' => $riwayat->riwayatdatadiri_id,
                'status' => 'Approved',
                'pejabat' => Auth::user()->name ?? 'System',
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
     * Now uses direct columns instead of joining latestDataDiri.
     */
    public function getFilteredQuery($request)
    {
        $query = Pegawai::query()
            ->with([
                'posisi',
                'departemen',
                'latestStatusPegawai.statusPegawai',
                'latestInpassing.golonganInpassing',
                'atasanSatu',
                'atasanDua',
            ]);

        // Add filtering using direct columns
        if ($request->filled('orgunit_id') && $request->orgunit_id !== 'all') {
            $query->where('orgunit_departemen_id', $request->orgunit_id);
        }

        if ($request->filled('posisi_id') && $request->posisi_id !== 'all') {
            $query->where('orgunit_posisi_id', $request->posisi_id);
        }

        if ($request->filled('status')) {
            // Status logic if applicable
        }

        return $query;
    }

    /**
     * Sync common columns from RiwayatDataDiri to Pegawai.
     * Called after approval is approved.
     */
    public function syncCommonColumns(Pegawai $pegawai, RiwayatDataDiri $riwayat): void
    {
        $pegawai->update([
            'nama' => $riwayat->nama,
            'nip' => $riwayat->nip,
            'email' => $riwayat->email,
            'inisial' => $riwayat->inisial,
            'no_hp' => $riwayat->no_hp,
            'jenis_kelamin' => $riwayat->jenis_kelamin,
            'tempat_lahir' => $riwayat->tempat_lahir,
            'tgl_lahir' => $riwayat->tgl_lahir,
            'orgunit_posisi_id' => $riwayat->orgunit_posisi_id,
            'orgunit_departemen_id' => $riwayat->orgunit_departemen_id,
            'nidn' => $riwayat->nidn,
            'no_ktp' => $riwayat->no_ktp,
            'alamat' => $riwayat->alamat,
            'status_nikah' => $riwayat->status_nikah,
            'agama' => $riwayat->agama,
            'gelar_depan' => $riwayat->gelar_depan,
            'gelar_belakang' => $riwayat->gelar_belakang,
            'bidang_ilmu' => $riwayat->bidang_ilmu,
            'latest_riwayatdatadiri_id' => $riwayat->riwayatdatadiri_id,
        ]);
    }
}
