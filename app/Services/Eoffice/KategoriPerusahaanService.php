<?php
namespace App\Services\Eoffice;

use App\Models\Eoffice\KategoriPerusahaan;
use Illuminate\Http\Request;

class KategoriPerusahaanService
{
    /**
     * Get paginated data for DataTables
     */
    public function getPaginateData(Request $request)
    {
        $query = KategoriPerusahaan::query();

        if ($request->filled('search')) {
            $search = $request->search['value'];
            $query->where('nama_kategori', 'like', "%{$search}%");
        }

        return $query;
    }

    /**
     * Store a new kategori perusahaan
     */
    public function createKategori(array $data)
    {
        return KategoriPerusahaan::create($data);
    }

    /**
     * Update an existing kategori perusahaan
     */
    public function updateKategori($id, array $data)
    {
        $kategori = KategoriPerusahaan::findOrFail($id);
        $kategori->update($data);
        return $kategori;
    }

    /**
     * Delete a kategori perusahaan
     */
    public function deleteKategori($id)
    {
        $kategori = KategoriPerusahaan::findOrFail($id);

        // Optional: Check for dependencies before deleting
        // if ($kategori->perusahaan()->exists()) {
        //     throw new \Exception('Kategori masih digunakan oleh data perusahaan.');
        // }

        return $kategori->delete();
    }

    /**
     * Get kategori by ID
     */
    public function getById($id)
    {
        return KategoriPerusahaan::findOrFail($id);
    }
}
