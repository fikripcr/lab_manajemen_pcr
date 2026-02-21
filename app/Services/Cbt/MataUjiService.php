<?php
namespace App\Services\Cbt;

use App\Models\Cbt\MataUji;

class MataUjiService
{
    public function getFilteredQuery(array $filters = [])
    {
        return MataUji::query();
    }

    public function store(array $data)
    {
        $mu = MataUji::create($data);
        logActivity('cbt', "Membuat mata uji: {$mu->nama_mata_uji}", $mu);
        return $mu;
    }

    public function update(MataUji $mu, array $data)
    {
        $mu->update($data);
        logActivity('cbt', "Memperbarui mata uji: {$mu->nama_mata_uji}", $mu);
        return $mu;
    }

    public function delete(MataUji $mu)
    {
        logActivity('cbt', "Menghapus mata uji: {$mu->nama_mata_uji}", $mu);
        return $mu->delete();
    }
}
