<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\DokSub;
use Illuminate\Database\Eloquent\Builder;

class DokumenService
{
    /**
     * Search for document sub-items (DokSub) based on query string.
     *
     * @param string|null $query
     * @param int $perPage
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
     * @param \Illuminate\Support\Collection $items
     * @return array
     */
    public function formatForSelect2($items)
    {
        $grouped = [];

        foreach ($items as $item) {
            $groupName = '[' . strtoupper($item->dokumen->jenis ?? 'DOC') . '] ' . \Str::limit($item->dokumen->judul ?? '-', 60);

            if (! isset($grouped[$groupName])) {
                $grouped[$groupName] = [
                    'text'     => $groupName,
                    'children' => [],
                ];
            }

            $grouped[$groupName]['children'][] = [
                'id'   => $item->encrypted_doksub_id,
                'text' => $item->judul . ($item->kode ? " ({$item->kode})" : ''),
            ];
        }

        return [
            'results' => array_values($grouped),
        ];
    }

    /**
     * Get array of Kebijakan Dokumens (Visi, Misi, RJP, Renstra, Renop) by Periode
     */
    public function getKebijakanByPeriode(int $periode): array
    {
        $jenisList = pemutuKebijakanJenisList();

        $dokumens = \App\Models\Pemutu\Dokumen::with(['dokSubs.childDokumens', 'dokSubs.mappedTo.dokumen'])
            ->whereIn('jenis', $jenisList)
            ->where('periode', $periode)
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
    public function getMappablePoinOptions(string $sourceJenis, int $periode): \Illuminate\Support\Collection
    {
        $targetJenis = pemutuMappableJenis($sourceJenis);

        if (empty($targetJenis)) {
            return collect();
        }

        $targetDokumens = \App\Models\Pemutu\Dokumen::whereIn('jenis', $targetJenis)
            ->where('periode', $periode)
            ->pluck('dok_id');

        if ($targetDokumens->isEmpty()) {
            return collect();
        }

        return DokSub::whereIn('dok_id', $targetDokumens)
            ->orderBy('seq')
            ->get();
    }
}
