<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\DokSub;
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
            // Auto Sequence
            if (! isset($data['seq'])) {
                $lastSeq     = DokSub::where('dok_id', $data['dok_id'])->max('seq');
                $data['seq'] = $lastSeq ? $lastSeq + 1 : 1;
            }

            $dokSub = DokSub::create($data);

            logActivity(
                'pemutu',
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
                'pemutu',
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
                'pemutu',
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
