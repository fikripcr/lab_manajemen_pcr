<?php
namespace App\Services\Lab;

use App\Models\Lab\MataKuliah;
use Exception;
use Illuminate\Support\Facades\DB;

class MataKuliahService
{
    /**
     * Get Query for DataTables
     */
    public function getFilteredQuery(array $filters = [])
    {
        $query = MataKuliah::select('*')->whereNull('deleted_at');

        if (! empty($filters['sks'])) {
            $query->where('sks', $filters['sks']);
        }

        // Global search handled by Controller closure usually, or here if returning builder.
        // Returning builder for Controller to apply specific DataTables 'filter' closure or simple where clauses.

        return $query;
    }

    /**
     * Get MataKuliah by ID
     */
    public function getMataKuliahById(string $id): ?MataKuliah
    {
        return MataKuliah::find($id);
    }

    /**
     * Create a new MataKuliah
     */
    public function createMataKuliah(array $data): MataKuliah
    {
        return DB::transaction(function () use ($data) {
            $mataKuliah = MataKuliah::create($data);

            logActivity('mata_kuliah_management', "Membuat mata kuliah baru: {$mataKuliah->nama_mk}");

            return $mataKuliah;
        });
    }

    /**
     * Update an existing MataKuliah
     */
    public function updateMataKuliah(MataKuliah $mataKuliah, array $data): bool
    {
        return DB::transaction(function () use ($mataKuliah, $data) {
            $oldName = $mataKuliah->nama_mk;

            $mataKuliah->update($data);

            logActivity(
                'mata_kuliah_management',
                "Memperbarui mata kuliah: {$oldName}" . ($oldName !== $mataKuliah->nama_mk ? " menjadi {$mataKuliah->nama_mk}" : "")
            );

            return true;
        });
    }

    /**
     * Delete a MataKuliah
     */
    public function deleteMataKuliah(MataKuliah $mataKuliah): bool
    {
        return DB::transaction(function () use ($mataKuliah) {
            // Dependency Checks
            if ($mataKuliah->jadwals()->count() > 0) {
                throw new Exception('Tidak dapat menghapus mata kuliah karena masih digunakan dalam Jadwal.');
            }
            if ($mataKuliah->requestSoftwares()->count() > 0) {
                throw new Exception('Tidak dapat menghapus mata kuliah karena masih digunakan dalam Request Software.');
            }

            $name = $mataKuliah->nama_mk;
            $mataKuliah->delete();

            logActivity('mata_kuliah_management', "Menghapus mata kuliah: {$name}");

            return true;
        });
    }

    protected function findOrFail(string $id): MataKuliah
    {
        $model = MataKuliah::find($id);
        if (! $model) {
            throw new Exception("Mata Kuliah dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
