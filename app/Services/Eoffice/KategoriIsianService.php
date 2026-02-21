<?php
namespace App\Services\Eoffice;

use App\Models\Eoffice\KategoriIsian;
use Exception;
use Illuminate\Http\Request;

class KategoriIsianService
{
    /**
     * Get paginated data for DataTables
     */
    public function getPaginateData(Request $request)
    {
        $query = KategoriIsian::query();

        if ($request->filled('search')) {
            $search = $request->search['value'];
            $query->where('nama_isian', 'like', "%{$search}%")
                ->orWhere('alias_on_document', 'like', "%{$search}%");
        }

        return $query;
    }

    /**
     * Store a new kategori isian
     */
    public function createKategori($data)
    {
        $kategori = KategoriIsian::create($data);
        logActivity('eoffice', "Menambah kategori isian: {$kategori->nama_isian}");
        return $kategori;
    }

    /**
     * Update an existing kategori isian
     */
    public function updateKategori($id, $data)
    {
        $kategori = KategoriIsian::findOrFail($id);
        $kategori->update($data);
        logActivity('eoffice', "Memperbarui kategori isian: {$kategori->nama_isian}");
        return $kategori;
    }

    /**
     * Delete a kategori isian
     */
    public function deleteKategori($id)
    {
        $kategori = KategoriIsian::findOrFail($id);

        // Check for dependencies (JenisLayananIsian)
        if ($kategori->jenisLayananIsians()->exists()) {
            throw new Exception('Kategori Isian tidak dapat dihapus karena sedang digunakan oleh jenis layanan.');
        }

        $nama = $kategori->nama_isian;
        $kategori->delete();
        logActivity('eoffice', "Menghapus kategori isian: {$nama}");
        return true;
    }

    /**
     * Get by ID
     */
    public function getById($id)
    {
        return KategoriIsian::findOrFail($id);
    }
}
