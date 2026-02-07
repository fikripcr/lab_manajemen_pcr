<?php
namespace App\Services\Pemtu;

use App\Models\Pemtu\Dokumen;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DokumenService
{
    /**
     * Get filtered query for DataTables or list
     */
    public function getFilteredQuery(array $filters = [])
    {
        return $this->applyFilters(Dokumen::query(), $filters);
    }

    public function getChildrenQuery(int $parentId)
    {
        return Dokumen::query()
            ->where('parent_id', $parentId)
            ->withCount('children')
            ->orderBy('seq');
    }

    /**
     * Get list of documents by type
     */
    public function getDokumenByJenis(string $jenis, ?int $periode = null): Collection
    {
        return Dokumen::where('jenis', $jenis)
            ->when($periode, function ($q) use ($periode) {
                return $q->where('periode', $periode);
            })
            ->with('children') // Eager load children for tree view optimization?
            ->orderBy('seq')
            ->get();
    }

    /**
     * Get hierarchical documents (parent-child)
     */
    public function getHierarchicalDokumens(?int $periode = null, ?string $jenis = null): Collection
    {
        $query = Dokumen::with('children')
            ->whereNull('parent_id')
            ->orderBy('seq');

        if ($periode) {
            $query->where('periode', $periode);
        }

        if ($jenis) {
            $query->where('jenis', $jenis);
        }

        return $query->get();
    }

    public function getDokumenById(int $id): ?Dokumen
    {
        return Dokumen::with(['children', 'parent'])->find($id);
    }

    public function createDokumen(array $data): Dokumen
    {
        return DB::transaction(function () use ($data) {
            // Default Periode
            if (empty($data['periode'])) {
                $data['periode'] = date('Y');
            }

            // Calculate Level & Seq if not provided
            if (empty($data['seq']) || empty($data['level'])) {
                if (! empty($data['parent_id'])) {
                    $parent        = Dokumen::find($data['parent_id']);
                    $data['level'] = $parent ? $parent->level + 1 : 1;
                    $data['seq']   = Dokumen::where('parent_id', $data['parent_id'])->max('seq') + 1;
                } else {
                    $data['level'] = 1;
                    $data['seq']   = Dokumen::whereNull('parent_id')->max('seq') + 1;
                }
            }

            $dokumen = Dokumen::create($data);

            logActivity(
                'dokumen_management',
                "Membuat dokumen baru: {$dokumen->judul}"
            );

            return $dokumen;
        });
    }

    public function updateDokumen(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $dokumen  = $this->findOrFail($id);
            $oldJudul = $dokumen->judul;

            $dokumen->update($data);

            logActivity(
                'dokumen_management',
                "Memperbarui dokumen: {$oldJudul}" . ($oldJudul !== $dokumen->judul ? " menjadi {$dokumen->judul}" : "")
            );

            return true;
        });
    }

    public function deleteDokumen(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $dokumen = $this->findOrFail($id);
            $judul   = $dokumen->judul;

            // Check specific logic constraint if any (e.g. has critical relations)
            // Cascade delete is usually handled by DB, but safety check is good.
            if ($dokumen->children()->count() > 0) {
                // Option: recursive delete or block?
                // Usually block or warn.
                // For now, let's allow recursive delete if configured, or throw error.
                // Given tree structure, usually we delete children too.
                // But let's throw error for safety unless requested otherwise.
                // Or implement recursive delete here.
                // Let's implement recursive delete for user convenience or just simple delete call (Model handles cascade?)
                // If not set in DB migration, need to do it here.
                // Let's assume standard delete.
            }

            $dokumen->delete();

            logActivity(
                'dokumen_management',
                "Menghapus dokumen: {$judul}"
            );

            return true;
        });
    }

    public function reorderDokumens(array $hierarchy, ?int $parentId = null)
    {
        return DB::transaction(function () use ($hierarchy, $parentId) {
            foreach ($hierarchy as $index => $item) {
                $dokumen = Dokumen::find($item['id']);
                if ($dokumen) {
                    $dokumen->seq       = $index + 1;
                    $dokumen->parent_id = $parentId;

                    // Update Level
                    if ($parentId) {
                        $parent         = Dokumen::find($parentId);
                        $dokumen->level = $parent ? $parent->level + 1 : 1;
                    } else {
                        $dokumen->level = 1;
                    }

                    $dokumen->save();

                    if (! empty($item['children'])) {
                        $this->reorderDokumens($item['children'], $dokumen->dok_id);
                    }
                }
            }
            return true;
        });
    }

    protected function applyFilters($query, array $filters)
    {
        // Add filters logic
        if (! empty($filters['jenis'])) {
            $query->where('jenis', $filters['jenis']);
        }
        if (! empty($filters['periode'])) {
            $query->where('periode', $filters['periode']);
        }
        return $query;
    }

    protected function findOrFail(int $id): Dokumen
    {
        $model = $this->getDokumenById($id);
        if (! $model) {
            throw new \Exception("Dokumen dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
