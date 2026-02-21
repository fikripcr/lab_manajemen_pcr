<?php
namespace App\Services\Pmb;

use App\Models\Pmb\Periode;

class PeriodeService
{
    public function getPaginateData(array $filters = [])
    {
        $query = Periode::query();

        if (! empty($filters['search']['value'])) {
            $search = $filters['search']['value'];
            $query->where('nama_periode', 'like', "%{$search}%");
        }

        return $query->latest();
    }

    public function createPeriode(array $data): Periode
    {
        $periode = Periode::create($data);
        logActivity('pmb_periode', "Menambahkan periode baru: {$periode->nama_periode}", $periode);
        return $periode;
    }

    public function updatePeriode(Periode $periode, array $data): bool
    {
        $periode->update($data);
        logActivity('pmb_periode', "Memperbarui periode: {$periode->nama_periode}", $periode);
        return true;
    }

    public function deletePeriode(Periode $periode): bool
    {
        $nama = $periode->nama_periode;
        $periode->delete();
        logActivity('pmb_periode', "Menghapus periode: {$nama}");
        return true;
    }

    public function getActivePeriode()
    {
        return Periode::where('is_aktif', true)->first();
    }
}
