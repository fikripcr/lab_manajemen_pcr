<?php
namespace App\Services\Pmb;

use App\Models\Pmb\DokumenUpload;
use App\Models\Pmb\Pendaftaran;
use App\Models\Pmb\PilihanProdi;
use App\Models\Pmb\Camaba;
use App\Models\Pmb\RiwayatPendaftaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PendaftaranService
{
    public function getPendaftaranDetails(Pendaftaran $pendaftaran)
    {
        return $pendaftaran->load(['user', 'periode', 'jalur', 'pilihanProdi.orgUnit', 'dokumenUpload.jenisDokumen', 'riwayat.pelaku']);
    }

    public function getDocumentById($documentId)
    {
        return DokumenUpload::findOrFail(decryptId($documentId));
    }

    /**
     * Create a new registration
     */
    public function createPendaftaran(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = Auth::user();

            // 1. Create or Update Profile
            $profil = Camaba::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nik'              => $data['nik'],
                    'no_hp'            => $data['no_hp'],
                    'tempat_lahir'     => $data['tempat_lahir'],
                    'tanggal_lahir'    => $data['tanggal_lahir'],
                    'jenis_kelamin'    => $data['jenis_kelamin'],
                    'alamat_lengkap'   => $data['alamat_lengkap'],
                    'asal_sekolah'     => $data['asal_sekolah'],
                    'nisn'             => $data['nisn'] ?? null,
                    'nama_ibu_kandung' => $data['nama_ibu_kandung'],
                ]
            );

            // 2. Create Pendaftaran
            $pendaftaran = Pendaftaran::create([
                'no_pendaftaran' => $this->generateNoPendaftaran(),
                'user_id'        => $user->id,
                'periode_id'     => $data['periode_id'],
                'jalur_id'       => $data['jalur_id'],
                'status_terkini' => 'Draft',
                'waktu_daftar'   => now(),
            ]);

            // 3. Create Pilihan Prodi
            foreach ($data['pilihan_prodi'] as $index => $orgUnitId) {
                PilihanProdi::create([
                    'pendaftaran_id' => $pendaftaran->pendaftaran_id,
                    'orgunit_id'     => $orgUnitId,
                    'urutan'         => $index + 1,
                ]);
            }

            // 4. Record History
            $this->logStatusHistory($pendaftaran, 'Draft', 'Pendaftaran baru dibuat oleh calon mahasiswa.');

            logActivity('pmb_pendaftaran', "Calon mahasiswa baru mendaftar: {$user->name} ({$pendaftaran->no_pendaftaran})", $pendaftaran);

            return $pendaftaran;
        });
    }

    /**
     * Generate unique registration number: REG-YYYY-XXXX
     */
    public function generateNoPendaftaran()
    {
        $year   = date('Y');
        $prefix = "REG-{$year}-";

        $last = Pendaftaran::where('no_pendaftaran', 'like', $prefix . '%')
            ->orderBy('pendaftaran_id', 'desc')
            ->first();

        if (! $last) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($last->no_pendaftaran, -4);
            $number     = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Update status pendaftaran with history logging
     */
    public function updateStatus(Pendaftaran $pendaftaran, $status, $keterangan = '')
    {
        return DB::transaction(function () use ($pendaftaran, $status, $keterangan) {
            $statusLama = $pendaftaran->status_terkini;

            $pendaftaran->update(['status_terkini' => $status]);

            $this->logStatusHistory($pendaftaran, $status, $keterangan, $statusLama);

            logActivity('pmb_pendaftaran', "Update status pendaftaran {$pendaftaran->no_pendaftaran}: {$statusLama} -> {$status}", $pendaftaran);

            return $pendaftaran;
        });
    }

    /**
     * Finalize Graduation (NIM Generation)
     */
    public function finalizeGraduation(Pendaftaran $pendaftaran, $orgUnitDiterimaId, $nim)
    {
        return DB::transaction(function () use ($pendaftaran, $orgUnitDiterimaId, $nim) {
            $pendaftaran->update([
                'status_terkini'      => 'Lulus',
                'orgunit_diterima_id' => $orgUnitDiterimaId,
                'nim_final'           => $nim,
            ]);

            $this->logStatusHistory($pendaftaran, 'Lulus', 'Pendaftaran telah difinalisasi dengan NIM ' . $nim, 'Siap_Ujian');

            logActivity('pmb_pendaftaran', "Finalisasi kelulusan pendaftaran {$pendaftaran->no_pendaftaran} dengan NIM {$nim}", $pendaftaran);

            return $pendaftaran;
        });
    }

    /**
     * Log status history
     */
    protected function logStatusHistory(Pendaftaran $pendaftaran, $statusBaru, $keterangan, $statusLama = null)
    {
        return RiwayatPendaftaran::create([
            'pendaftaran_id' => $pendaftaran->pendaftaran_id,
            'status_lama'    => $statusLama,
            'status_baru'    => $statusBaru,
            'keterangan'     => $keterangan,
            'waktu_kejadian' => now(),
            'user_pelaku_id' => auth()->id() ?? 1, // System default
        ]);
    }

    /**
     * Get registration details for DataTables
     */
    public function getFilteredQuery(array $filters = [])
    {
        $query = Pendaftaran::with(['user', 'periode', 'jalur', 'orgUnitDiterima']);

        if (! empty($filters['status'])) {
            $query->where('status_terkini', $filters['status']);
        }

        if (! empty($filters['periode_id'])) {
            $query->where('periode_id', $filters['periode_id']);
        }

        if (! empty($filters['search'])) {
            $search = is_array($filters['search']) ? ($filters['search']['value'] ?? '') : $filters['search'];
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('no_pendaftaran', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($qu) use ($search) {
                            $qu->where('name', 'like', "%{$search}%");
                        });
                });
            }
        }

        return $query->latest();
    }

    /**
     * Verify individual uploaded document
     */
    public function verifyUploadedDocument(DokumenUpload $doc, $status, $catatan = null)
    {
        return DB::transaction(function () use ($doc, $status, $catatan) {
            $doc->update([
                'status_verifikasi' => $status,
                'catatan_revisi'    => $catatan,
                'verifikator_id'    => auth()->id(),
            ]);

            logActivity('pmb_verifikasi', "Verifikasi dokumen {$doc->jenisDokumen->nama_dokumen} untuk pendaftaran {$doc->pendaftaran->no_pendaftaran}: {$status}", $doc);

            return $doc;
        });
    }
}
