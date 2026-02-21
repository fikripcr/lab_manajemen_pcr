<?php
namespace App\Services\Pmb;

use App\Models\Pmb\SesiUjian;

class SesiUjianService
{
    public function getPaginateQuery()
    {
        return SesiUjian::with('periode')->latest();
    }

    public function store(array $data): SesiUjian
    {
        $sesi = SesiUjian::create($data);
        logActivity('pmb_sesi_ujian', "Menambahkan sesi ujian baru: {$sesi->nama_sesi}", $sesi);
        return $sesi;
    }

    public function update(SesiUjian $sesi, array $data): bool
    {
        $sesi->update($data);
        logActivity('pmb_sesi_ujian', "Memperbarui sesi ujian: {$sesi->nama_sesi}", $sesi);
        return true;
    }

    public function delete(SesiUjian $sesi): bool
    {
        $nama = $sesi->nama_sesi;
        $sesi->delete();
        logActivity('pmb_sesi_ujian', "Menghapus sesi ujian: {$nama}");
        return true;
    }
}
