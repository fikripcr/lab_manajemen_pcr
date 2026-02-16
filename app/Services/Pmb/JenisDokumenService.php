<?php
namespace App\Services\Pmb;

use App\Models\Pmb\JenisDokumen;
use Illuminate\Http\Request;

class JenisDokumenService
{
    public function getPaginateData(Request $request)
    {
        $query = JenisDokumen::query();

        if ($request->filled('search.value')) {
            $search = $request->input('search.value');
            $query->where('nama_dokumen', 'like', "%{$search}%");
        }

        return $query->latest();
    }

    public function createJenisDokumen(array $data)
    {
        return JenisDokumen::create($data);
    }

    public function updateJenisDokumen($id, array $data)
    {
        $jenisDokumen = JenisDokumen::findOrFail($id);
        $jenisDokumen->update($data);
        return $jenisDokumen;
    }

    public function deleteJenisDokumen($id)
    {
        $jenisDokumen = JenisDokumen::findOrFail($id);
        return $jenisDokumen->delete();
    }

    public function getAll()
    {
        return JenisDokumen::all();
    }
}
