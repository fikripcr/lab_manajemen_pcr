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
}
