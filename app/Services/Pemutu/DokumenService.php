<?php

namespace App\Services\Pemutu;

use App\Models\Pemutu\DokSub;
use Illuminate\Database\Eloquent\Builder;

class DokumenService
{
    /**
     * Ambil dokumen standar utama (root) untuk filter.
     */
    public function getRootsByPeriode(int|string $tahun, string $jenis = 'standar'): \Illuminate\Support\Collection
    {
        return \App\Models\Pemutu\Dokumen::whereNull('parent_id')
            ->where('periode', $tahun)
            ->where('jenis', $jenis)
            ->orderBy('seq')
            ->get();
    }

    /**
     * Search for document sub-items (DokSub) based on query string.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function searchDokSub(?string $query, int $perPage = 30)
    {
        $search = DokSub::with('dokumen');

        if (! empty($query)) {
            $search->where(function (Builder $q) use ($query) {
                $q->where('judul', 'like', "%{$query}%")
                    ->orWhere('kode', 'like', "%{$query}%")
                    ->orWhereHas('dokumen', function (Builder $qq) use ($query) {
                        $qq->where('judul', 'like', "%{$query}%")
                            ->orWhere('kode', 'like', "%{$query}%");
                    });
            });
        }

        return $search->limit($perPage)->get();
    }

    /**
     * Format DokSub items for Select2 AJAX response with grouping.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @return array
     */
    public function formatForSelect2($items)
    {
        $grouped = [];

        foreach ($items as $item) {
            $groupName = '['.strtoupper($item->dokumen->jenis ?? 'DOC').'] '.\Str::limit($item->dokumen->judul ?? '-', 60);

            if (! isset($grouped[$groupName])) {
                $grouped[$groupName] = [
                    'text' => $groupName,
                    'children' => [],
                ];
            }

            $grouped[$groupName]['children'][] = [
                'id' => $item->encrypted_doksub_id,
                'text' => $item->judul.($item->kode ? " ({$item->kode})" : ''),
            ];
        }

        return [
            'results' => array_values($grouped),
        ];
    }

    /**
     * Get array of Kebijakan Dokumens (Visi, Misi, RJP, Renstra, Renop) by Periode
     */
    public function getKebijakanByPeriode(int $year): array
    {
        $jenisList = pemutuKebijakanJenisList();

        $dokumens = \App\Models\Pemutu\Dokumen::with(['dokSubs.childDokumens', 'dokSubs.mappedTo.dokumen'])
            ->whereIn('jenis', $jenisList)
            ->where('periode', $year)
            ->get()
            ->keyBy('jenis');

        $result = [];
        foreach ($jenisList as $jenis) {
            $result[$jenis] = $dokumens->get($jenis);
        }

        return $result;
    }

    /**
     * Get Mappable Poin Options for a given Jenis Kebijakan
     */
    public function getMappablePoinOptions(string $sourceJenis, int $year): \Illuminate\Support\Collection
    {
        $targetJenis = pemutuMappableJenis($sourceJenis);

        if (empty($targetJenis)) {
            return collect();
        }

        $targetDokumens = \App\Models\Pemutu\Dokumen::whereIn('jenis', $targetJenis)
            ->where('periode', $year)
            ->pluck('dok_id');

        if ($targetDokumens->isEmpty()) {
            return collect();
        }

        return DokSub::whereIn('dok_id', $targetDokumens)
            ->orderBy('seq')
            ->get();
    }

    /**
     * Get Mappable Parent Dokumen Options for a given Jenis (e.g. Formulir mapping to Standar/Manual)
     */
    public function getMappableDokumenOptions(string $sourceJenis, int $year): \Illuminate\Support\Collection
    {
        $targetJenis = pemutuMappableJenis($sourceJenis);

        if (empty($targetJenis)) {
            return collect();
        }

        return \App\Models\Pemutu\Dokumen::whereIn('jenis', $targetJenis)
            ->where('periode', $year)
            ->orderBy('kode')
            ->orderBy('judul')
            ->get();
    }

    /**
     * Get unique standard documents for a given year.
     */
    public function getStandardDocumentsByYear(string $year): array
    {
        return \App\Models\Pemutu\Dokumen::where('jenis', 'standar')
            ->where('periode', 'like', "%{$year}%")
            ->whereNull('parent_id')
            ->orderBy('judul')
            ->get()
            ->unique('judul')
            ->pluck('judul', 'dok_id')
            ->toArray();
    }

    /**
     * Get distinct periods (years) across all documents.
     */
    public function getDistinctPeriods(): \Illuminate\Support\Collection
    {
        return \App\Models\Pemutu\Dokumen::select('periode')
            ->whereNotNull('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode');
    }

    /**
     * Get documents by type and optionally by period.
     */
    public function getDokumenByJenis(string $jenis, ?int $periode = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = \App\Models\Pemutu\Dokumen::where('jenis', $jenis)
            ->whereNull('parent_id')
            ->orderBy('seq');

        if ($periode) {
            $query->where('periode', $periode);
        }

        return $query->get();
    }

    /**
     * Get a single document by ID.
     */
    public function getDokumenById(int $id): ?\App\Models\Pemutu\Dokumen
    {
        return \App\Models\Pemutu\Dokumen::find($id);
    }

    /**
     * Get a single DokSub by ID.
     */
    public function getDokSubById(int $id): ?DokSub
    {
        return DokSub::find($id);
    }

    /**
     * Get hierarchical documents for dropdown selection.
     */
    public function getHierarchicalDokumens(): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Pemutu\Dokumen::with('children')
            ->whereNull('parent_id')
            ->orderBy('jenis')
            ->orderBy('seq')
            ->get();
    }

    /**
     * Create a new document.
     */
    public function createDokumen(array $data): \App\Models\Pemutu\Dokumen
    {
        return \App\Models\Pemutu\Dokumen::create($data);
    }

    /**
     * Update an existing document.
     */
    public function updateDokumen(int $id, array $data): bool
    {
        $dokumen = $this->getDokumenById($id);

        return $dokumen ? $dokumen->update($data) : false;
    }

    /**
     * Delete a document.
     */
    public function deleteDokumen(int $id): bool
    {
        $dokumen = $this->getDokumenById($id);

        return $dokumen ? $dokumen->delete() : false;
    }

    /**
     * Reorder documents based on nested hierarchy.
     */
    public function reorderDokumens(array $hierarchy, ?int $parentId = null): void
    {
        foreach ($hierarchy as $index => $item) {
            $id = decryptIdIfEncrypted($item['id']);
            \App\Models\Pemutu\Dokumen::where('dok_id', $id)->update([
                'parent_id' => $parentId,
                'seq' => $index + 1,
            ]);

            if (isset($item['children']) && count($item['children']) > 0) {
                $this->reorderDokumens($item['children'], $id);
            }
        }
    }

    /**
     * Get children query for DataTables (DokSub based).
     */
    public function getDokSubChildrenQuery(int $dokumenId): \Illuminate\Database\Eloquent\Builder
    {
        return DokSub::withCount(['childDokumens', 'indikators'])
            ->where('dok_id', $dokumenId)
            ->orderBy('seq');
    }

    /**
     * Get children query for DataTables (Standard).
     */
    public function getChildrenQuery(int $parent_id): \Illuminate\Database\Eloquent\Builder
    {
        return \App\Models\Pemutu\Dokumen::withCount('children')
            ->with(['dokSubs.indikators' => function ($q) {
                $q->where('type', 'renop');
            }])
            ->where('parent_id', $parent_id)
            ->orderBy('seq');
    }

    /**
     * Get accumulated indicators for a Renop document.
     */
    public function getRenopIndicators(\App\Models\Pemutu\Dokumen $dokumen): \Illuminate\Support\Collection
    {
        $doksubs = $dokumen->dokSubs;
        $indicators = collect();
        foreach ($doksubs as $doksub) {
            $indicators = $indicators->merge($doksub->indikators);
        }

        return $indicators;
    }
}
