<?php
namespace App\Services\Pmb;

use App\Models\Pmb\Prodi;
use Illuminate\Http\Request;

class ProdiService
{
    public function getPaginateData(Request $request)
    {
        $query = Prodi::query();

        if ($request->filled('search.value')) {
            $search = $request->input('search.value');
            $query->where('nama_prodi', 'like', "%{$search}%")
                ->orWhere('kode_prodi', 'like', "%{$search}%");
        }

        return $query->latest();
    }

    public function createProdi(array $data)
    {
        return Prodi::create($data);
    }

    public function updateProdi($id, array $data)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->update($data);
        return $prodi;
    }

    public function deleteProdi($id)
    {
        $prodi = Prodi::findOrFail($id);
        return $prodi->delete();
    }

    public function getAll()
    {
        return Prodi::all();
    }
}
