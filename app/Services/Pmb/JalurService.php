<?php
namespace App\Services\Pmb;

use App\Models\Pmb\Jalur;
use Illuminate\Http\Request;

class JalurService
{
    public function getPaginateData(Request $request)
    {
        $query = Jalur::query();

        if ($request->filled('search.value')) {
            $search = $request->input('search.value');
            $query->where('nama_jalur', 'like', "%{$search}%");
        }

        return $query->latest();
    }

    public function createJalur(array $data)
    {
        return Jalur::create($data);
    }

    public function updateJalur($id, array $data)
    {
        $jalur = Jalur::findOrFail($id);
        $jalur->update($data);
        return $jalur;
    }

    public function deleteJalur($id)
    {
        $jalur = Jalur::findOrFail($id);
        return $jalur->delete();
    }

    public function getAllActive()
    {
        return Jalur::where('is_aktif', true)->get();
    }
}
