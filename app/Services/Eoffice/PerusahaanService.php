<?php
namespace App\Services\Eoffice;

use App\Models\Eoffice\Perusahaan;
use Illuminate\Http\Request;

class PerusahaanService
{
    /**
     * Get paginated data for DataTables
     */
    public function getPaginateData(Request $request)
    {
        $query = Perusahaan::with('kategori');

        if ($request->filled('search')) {
            $search = $request->search['value'];
            $query->where('nama_perusahaan', 'like', "%{$search}%")
                ->orWhere('kota', 'like', "%{$search}%");
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategoriperusahaan_id', $request->kategori_id);
        }

        return $query;
    }

    /**
     * Store a new perusahaan
     */
    public function createPerusahaan(array $data)
    {
        return Perusahaan::create($data);
    }

    /**
     * Update an existing perusahaan
     */
    public function updatePerusahaan($id, array $data)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        $perusahaan->update($data);
        return $perusahaan;
    }

    /**
     * Delete a perusahaan
     */
    public function deletePerusahaan($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);

        // Optional: Check for dependencies (layanan_kp)

        return $perusahaan->delete();
    }

    public function getById($id)
    {
        return Perusahaan::with('kategori')->findOrFail($id);
    }
}
