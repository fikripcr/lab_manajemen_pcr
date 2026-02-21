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
        $kategori = KategoriPerusahaan::create($data);
        logActivity('eoffice', "Menambah kategori perusahaan: {$kategori->nama_kategori}");
        return $kategori;
    }

    /**
     * Update an existing kategori perusahaan
     */
    public function updateKategori($id, array $data)
    {
        $kategori = KategoriPerusahaan::findOrFail($id);
        $kategori->update($data);
        logActivity('eoffice', "Memperbarui kategori perusahaan: {$kategori->nama_kategori}");
        return $kategori;
    }

    /**
     * Delete a kategori perusahaan
     */
    public function deleteKategori($id)
    {
        $kategori = KategoriPerusahaan::findOrFail($id);
        $nama     = $kategori->nama_kategori;
        $kategori->delete();
        logActivity('eoffice', "Menghapus kategori perusahaan: {$nama}");
        return true;
    }

    /**
     * Get kategori by ID
     */
    public function getById($id)
    {
        return KategoriPerusahaan::findOrFail($id);
    }
}
