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
        if (isset($data['type_value']) && is_array($data['type_value'])) {
            // Handle array if needed, but model might handle it via casts
        }
        return KategoriIsian::create($data);
    }

    /**
     * Update an existing kategori isian
     */
    public function updateKategori($id, $data)
    {
        $kategori = KategoriIsian::findOrFail($id);
        $kategori->update($data);
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

        return $kategori->delete();
    }

    /**
     * Get by ID
     */
    public function getById($id)
    {
        return KategoriIsian::findOrFail($id);
    }
}
