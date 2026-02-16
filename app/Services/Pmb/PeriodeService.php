<?php
namespace App\Services\Pmb;

use App\Models\Pmb\Periode;
use Illuminate\Http\Request;

class PeriodeService
{
    public function getPaginateData(Request $request)
    {
        $query = Periode::query();

        if ($request->filled('search.value')) {
            $search = $request->input('search.value');
            $query->where('nama_periode', 'like', "%{$search}%");
        }

        return $query->latest();
    }

    public function createPeriode(array $data)
    {
        return Periode::create($data);
    }

    public function updatePeriode($id, array $data)
    {
        $periode = Periode::findOrFail($id);
        $periode->update($data);
        return $periode;
    }

    public function deletePeriode($id)
    {
        $periode = Periode::findOrFail($id);
        return $periode->delete();
    }

    public function getActivePeriode()
    {
        return Periode::where('is_aktif', true)->first();
    }
}
