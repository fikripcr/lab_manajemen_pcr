<?php
namespace App\Services\Cbt;

use App\Models\Cbt\JadwalUjian;
use Illuminate\Support\Str;

class JadwalUjianService
{
    public function store(array $data)
    {
        $data['token_ujian']    = strtoupper(Str::random(6));
        $data['is_token_aktif'] = true;

        return JadwalUjian::create($data);
    }

    public function generateToken(JadwalUjian $jadwal)
    {
        $jadwal->update([
            'token_ujian'    => strtoupper(Str::random(6)),
            'is_token_aktif' => true,
        ]);
        return $jadwal;
    }

    public function toggleToken(JadwalUjian $jadwal)
    {
        $jadwal->update(['is_token_aktif' => ! $jadwal->is_token_aktif]);
        return $jadwal;
    }

    public function delete(JadwalUjian $jadwal)
    {
        return $jadwal->delete();
    }
}
