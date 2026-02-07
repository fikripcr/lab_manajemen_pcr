<?php
namespace App\Services\Pemtu;

use App\Models\Pemtu\DokSub;
use Illuminate\Support\Facades\DB;

class DokSubService
{
    public function getFilteredQuery(array $filters = [])
    {
        $query = DokSub::query();

        if (! empty($filters['dok_id'])) {
            $query->where('dok_id', $filters['dok_id']);
        }

        return $query->orderBy('seq');
    }

    public function getDokSubById(int $id): ?DokSub
    {
        return DokSub::with(['dokumen.parent', 'childDokumens'])->find($id);
    }

    public function createDokSub(array $data): DokSub
    {
        return DB::transaction(function () use ($data) {
            $dokSub = DokSub::create($data);

            logActivity(
                'doksub_management',
                "Membuat sub-dokumen baru: {$dokSub->judul}"
            );

            return $dokSub;
        });
    }

    public function updateDokSub(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $dokSub   = $this->findOrFail($id);
            $oldJudul = $dokSub->judul;

            $dokSub->update($data);

            logActivity(
                'doksub_management',
                "Memperbarui sub-dokumen: {$oldJudul}" . ($oldJudul !== $dokSub->judul ? " menjadi {$dokSub->judul}" : "")
            );

            return true;
        });
    }

    public function deleteDokSub(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $dokSub = $this->findOrFail($id);
            $judul  = $dokSub->judul;

            $dokSub->delete();

            logActivity(
                'doksub_management',
                "Menghapus sub-dokumen: {$judul}"
            );

            return true;
        });
    }

    protected function findOrFail(int $id): DokSub
    {
        $model = DokSub::find($id);
        if (! $model) {
            throw new \Exception("Sub-Dokumen dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
