<?php
namespace App\Services\Pmb;

use App\Models\Pmb\SesiUjian;

class SesiUjianService
{
    public function store(array $data)
    {
        return SesiUjian::create($data);
    }

    public function update(SesiUjian $sesi, array $data)
    {
        $sesi->update($data);
        return $sesi;
    }

    public function delete(SesiUjian $sesi)
    {
        return $sesi->delete();
    }
}
