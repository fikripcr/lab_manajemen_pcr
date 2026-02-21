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
        $perusahaan = Perusahaan::create($data);
        logActivity('eoffice', "Menambah perusahaan: {$perusahaan->nama_perusahaan}");
        return $perusahaan;
    }

    /**
     * Update an existing perusahaan
     */
    public function updatePerusahaan($id, array $data)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        $perusahaan->update($data);
        logActivity('eoffice', "Memperbarui perusahaan: {$perusahaan->nama_perusahaan}");
        return $perusahaan;
    }

    /**
     * Delete a perusahaan
     */
    public function deletePerusahaan($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        $nama       = $perusahaan->nama_perusahaan;
        $perusahaan->delete();
        logActivity('eoffice', "Menghapus perusahaan: {$nama}");
        return true;
    }

    public function getById($id)
    {
        return Perusahaan::with('kategori')->findOrFail($id);
    }
}
