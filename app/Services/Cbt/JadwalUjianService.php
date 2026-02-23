<?php
namespace App\Services\Cbt;

use App\Models\Cbt\JadwalUjian;
use Illuminate\Support\Str;

class JadwalUjianService
{
    public function getFilteredQuery(array $filters = [])
    {
        return JadwalUjian::with([
            'paket',
            'pesertaBerhak',
            'riwayatSiswa' => function ($q) {
                $q->withCount('pelanggaran');
            },
        ])->latest();
    }

    public function store(array $data)
    {
        $data['token_ujian']    = strtoupper(Str::random(6));
        $data['is_token_aktif'] = true;

        $jadwal = JadwalUjian::create($data);
        logActivity('cbt', "Membuat jadwal ujian: {$jadwal->nama_kegiatan}", $jadwal);
        return $jadwal;
    }

    public function generateToken(JadwalUjian $jadwal)
    {
        $jadwal->update([
            'token_ujian'    => strtoupper(Str::random(6)),
            'is_token_aktif' => true,
        ]);
        logActivity('cbt', "Generate token baru untuk jadwal: {$jadwal->nama_kegiatan}", $jadwal);
        return $jadwal;
    }

    public function toggleToken(JadwalUjian $jadwal)
    {
        $jadwal->update(['is_token_aktif' => ! $jadwal->is_token_aktif]);
        $status = $jadwal->is_token_aktif ? 'diaktifkan' : 'dinonaktifkan';
        logActivity('cbt', "Token jadwal {$jadwal->nama_kegiatan} {$status}", $jadwal);
        return $jadwal;
    }

    public function delete(JadwalUjian $jadwal)
    {
        logActivity('cbt', "Menghapus jadwal ujian: {$jadwal->nama_kegiatan}", $jadwal);
        return $jadwal->delete();
    }
}
