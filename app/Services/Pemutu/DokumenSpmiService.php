<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DokumenSpmiService
{
    /**
     * Get Dokumen with hierarchy for the Tree View
     */
    public function getDokumenByJenis(string $jenis, ?int $periode = null): Collection
    {
        return Dokumen::where('jenis', $jenis)
            ->when($periode, function ($q) use ($periode) {
                return $q->where('periode', $periode);
            })
            ->with(['children', 'dokSubs.childDokumens'])
            ->orderBy('seq')
            ->get();
    }

    public function getHierarchicalDokumens(): Collection
    {
        return Dokumen::whereNull('parent_id')->orderBy('seq')->get();
    }

    // ==========================================
    // DOKUMEN LOGIC
    // ==========================================
    public function createDokumen(array $data): Dokumen
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['periode'])) {
                $data['periode'] = date('Y');
            }

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

            // Auto-create default sub-documents (points) for Standar and Kebijakan
            if (in_array(strtolower($dokumen->jenis), ['standar', 'kebijakan'])) {
                if (function_exists('pemutuDefaultSubDocuments')) {
                    $defaultPoints = pemutuDefaultSubDocuments($dokumen->jenis);
                    foreach ($defaultPoints as $idx => $judul) {
                        // is_hasilkan_indikator: True ONLY for Standar point #5
                        $isHasilkan = (strtolower($dokumen->jenis) === 'standar' && $judul === 'Pernyataan Isi Standar / Indikator Capaian');
                        
                        // Generate Kode if document has one
                        $subKode = null;
                        if ($dokumen->kode) {
                            $subKode = $dokumen->kode . '.' . ($idx + 1);
                        }

                        $dokumen->dokSubs()->create([
                            'judul'                 => $judul,
                            'kode'                  => $subKode,
                            'seq'                   => $idx + 1,
                            'is_hasilkan_indikator' => $isHasilkan,
                            'created_by'            => $data['created_by'] ?? (auth()->id() ?? 1),
                        ]);
                    }
                }
            }

            logActivity('dokumen_spmi', "Membuat dokumen: {$dokumen->judul}");
            return $dokumen;
        });
    }

    public function updateDokumen(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $dokumen = Dokumen::findOrFail($id);
            $dokumen->update($data);
            logActivity('dokumen_spmi', "Memperbarui dokumen: {$dokumen->judul}");
            return true;
        });
    }

    public function deleteDokumen(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $dokumen = Dokumen::findOrFail($id);
            $judul   = $dokumen->judul;

            $this->performRecursiveDelete($dokumen);

            logActivity('dokumen_spmi', "Menghapus dokumen: {$judul}");
            return true;
        });
    }

    /**
     * Recursive deletion helper for Dokumen hierarchy.
     */
    private function performRecursiveDelete(Dokumen $dokumen)
    {
        // 1. Delete all DokSubs (points)
        foreach ($dokumen->dokSubs as $sub) {
            // Delete child documents rooted at this point
            foreach ($sub->childDokumens as $child) {
                $this->performRecursiveDelete($child);
            }
            $sub->delete();
        }

        // 2. Delete all child documents
        foreach ($dokumen->children as $child) {
            $this->performRecursiveDelete($child);
        }

        // 3. Delete the document itself
        $dokumen->delete();
    }

    // ==========================================
    // POIN (DOK-SUB) LOGIC
    // ==========================================
    public function createPoin(array $data): DokSub
    {
        return DB::transaction(function () use ($data) {
            $data['is_hasilkan_indikator'] = isset($data['is_hasilkan_indikator']) ? (bool) $data['is_hasilkan_indikator'] : false;
            if (empty($data['seq'])) {
                $data['seq'] = DokSub::where('dok_id', $data['dok_id'])->max('seq') + 1;
            }
            $poin = DokSub::create($data);
            logActivity('dokumen_spmi', "Membuat Poin: {$poin->judul}");
            return $poin;
        });
    }

    public function updatePoin(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $poin                          = DokSub::findOrFail($id);
            $data['is_hasilkan_indikator'] = isset($data['is_hasilkan_indikator']) ? (bool) $data['is_hasilkan_indikator'] : false;
            $poin->update($data);
            logActivity('dokumen_spmi', "Memperbarui Poin: {$poin->judul}");
            return true;
        });
    }

    public function deletePoin(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $poin  = DokSub::findOrFail($id);
            $judul = $poin->judul;
            $poin->delete();
            logActivity('dokumen_spmi', "Menghapus Poin: {$judul}");
            return true;
        });
    }

    // ==========================================
    // INDIKATOR LOGIC
    // ==========================================
    public function createIndikator(array $data): Indikator
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['urutan'])) {
                $data['urutan'] = Indikator::count() + 1;
            }
            $indikator = Indikator::create($data);

            if (! empty($data['doksub_ids'])) {
                $indikator->dokSubs()->sync($data['doksub_ids']);
            }

            logActivity('dokumen_spmi', "Membuat Indikator: {$indikator->indikator}");
            return $indikator;
        });
    }

    public function updateIndikator(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $indikator = Indikator::findOrFail($id);
            $indikator->update($data);
            logActivity('dokumen_spmi', "Memperbarui Indikator: {$indikator->indikator}");
            return true;
        });
    }

    public function deleteIndikator(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $indikator = Indikator::findOrFail($id);
            $nama      = $indikator->indikator;
            $indikator->delete();
            logActivity('dokumen_spmi', "Menghapus Indikator: {$nama}");
            return true;
        });
    }
}
