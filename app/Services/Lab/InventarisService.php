<?php
namespace App\Services\Lab;

use App\Exports\InventarisExport;
use App\Models\Lab\Inventaris;
use App\Models\Lab\Lab;
// use App\Imports\InventarisImport; // If needed later
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class InventarisService
{
    /**
     * Get filtered query for DataTables and Exports
     */
    public function getFilteredQuery(array $filters = [])
    {
        $query = Inventaris::select([
            'inventaris_id',
            'nama_alat',
            'jenis_alat',
            'kondisi_terakhir',
            'tanggal_pengecekan',
        ])
            ->whereNull('deleted_at');

        // Apply filters
        if (! empty($filters['condition'])) {
            $query->where('kondisi_terakhir', $filters['condition']);
        }

        // Handle DataTables search format (array with 'value' key)
        $searchValue = $filters['search'] ?? '';
        if (is_array($searchValue)) {
            $searchValue = $searchValue['value'] ?? '';
        }

        if (! empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('nama_alat', 'like', '%' . $searchValue . '%')
                    ->orWhere('jenis_alat', 'like', '%' . $searchValue . '%');
            });
        }

        // Lab Filter for Export (using relationship)
        if (! empty($filters['lab_id'])) {
            $query->whereHas('labInventaris', function ($q) use ($filters) {
                $q->where('lab_id', $filters['lab_id']);
            });
        }

        return $query;
    }

    /**
     * Get Inventaris by ID
     */
    public function getInventarisById(string $id): ?Inventaris
    {
        return Inventaris::find($id);
    }

    /**
     * Create a new Inventaris
     */
    public function createInventaris(array $data): Inventaris
    {
        return DB::transaction(function () use ($data) {
            $inventaris = Inventaris::create($data);

            logActivity('inventaris_management', "Membuat inventaris baru: {$inventaris->nama_alat}");

            return $inventaris;
        });
    }

    /**
     * Update an existing Inventaris
     */
    public function updateInventaris(string $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $inventaris = $this->findOrFail($id);
            $oldName    = $inventaris->nama_alat;

            $inventaris->update($data);

            logActivity(
                'inventaris_management',
                "Memperbarui inventaris: {$oldName}" . ($oldName !== $inventaris->nama_alat ? " menjadi {$inventaris->nama_alat}" : "")
            );

            return true;
        });
    }

    /**
     * Delete an Inventaris
     */
    public function deleteInventaris(string $id): bool
    {
        return DB::transaction(function () use ($id) {
            $inventaris = $this->findOrFail($id);
            $name       = $inventaris->nama_alat;

            $inventaris->delete();

            logActivity('inventaris_management', "Menghapus inventaris: {$name}");

            return true;
        });
    }

    /**
     * Export Inventaris to Excel
     */
    public function exportInventaris(array $filters, array $columns)
    {
        // Logic handled by Export class, but Service can trigger it or prepare data.
        // For consistency with Controller, let's keep the Export class usage here or in Controller.
        // Usually, Service returns data or the Export object.
        // Let's return the Export object.
        return new InventarisExport($filters, $columns);
    }

    /**
     * Get unassigned items for a specific Lab (helper for LabInventaris)
     */
    public function getUnassignedForLab(string $labId, ?string $search = null, int $limit = 5)
    {
        return Inventaris::select('inventaris_id', 'nama_alat', 'jenis_alat')
            ->whereDoesntHave('labInventaris', function ($query) use ($labId) {
                $query->where('lab_id', $labId);
            })
            ->when($search, function ($query, $search) {
                return $query->where('nama_alat', 'LIKE', "%{$search}%")
                    ->orWhere('jenis_alat', 'LIKE', "%{$search}%");
            })
            ->limit($limit)
            ->get();
    }

    protected function findOrFail(string $id): Inventaris
    {
        $model = Inventaris::find($id);
        if (! $model) {
            throw new \Exception("Inventaris dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
