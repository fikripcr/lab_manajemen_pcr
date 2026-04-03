<?php

namespace App\Services\Pemutu;

use App\Models\Hr\StrukturOrganisasi;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\TimMutu;
use App\Models\User;
use App\Services\Hr\StrukturOrganisasiService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class IndikatorService
{
    public function __construct(
        protected StrukturOrganisasiService $strukturOrganisasiService,
        protected IndikatorPegawaiService $indikatorPegawaiService,
    ) {}

    /**
     * Get filtered query for indicators (Master Data view).
     */
    public function getFilteredQuery(array $filters): Builder
    {
        $query = Indikator::with(['dokSubs.dokumen', 'dokSubs.mappedTo.dokumen', 'labels', 'parent', 'orgUnits', 'renstraPoin.dokumen']);

        if (! empty($filters['dokumen_id'])) {
            $dokId = decryptIdIfEncrypted($filters['dokumen_id']);
            $query->whereHas('dokSubs.dokumen', function ($q) use ($dokId) {
                $q->where('dok_id', $dokId);
            });
        }

        if (! empty($filters['type'])) {
            if ($filters['type'] === 'standar') {
                $query->whereIn('type', ['standar', 'renop']);
            } else {
                $query->where('type', $filters['type']);
            }
        }

        if (! empty($filters['kelompok_indikator'])) {
            $query->where('kelompok_indikator', $filters['kelompok_indikator']);
        }

        if (! empty($filters['parent_id'])) {
            $query->where('parent_id', decryptIdIfEncrypted($filters['parent_id']));
        }

        if (! empty($filters['renstra_poin_id'])) {
            $query->where('renstra_poin_id', decryptIdIfEncrypted($filters['renstra_poin_id']));
        }

        if (! empty($filters['label_ids']) || ! empty($filters['label_ids[]'])) {
            $labelIds = $filters['label_ids'] ?? $filters['label_ids[]'];
            $labelIds = is_array($labelIds) ? $labelIds : [$labelIds];
            $labelIds = array_filter(array_map('decryptIdIfEncrypted', $labelIds));
            if (! empty($labelIds)) {
                $query->whereHas('labels', function ($q) use ($labelIds) {
                    $q->whereIn('pemutu_label.label_id', $labelIds);
                });
            }
        }

        // Filter by year (Dokumen.periode)
        if (! empty($filters['periode'])) {
            $periode = $filters['periode'];
            $query->whereHas('dokSubs.dokumen', function ($q) use ($periode) {
                $q->where('periode', (int) $periode);
            });
        }

        return $query->orderBy('no_indikator', 'asc');
    }

    /**
     * Resolve Indikator by ID (including encrypted).
     */
    public function findIndikator(string|int $id): Indikator
    {
        return Indikator::findOrFail(decryptIdIfEncrypted($id));
    }

    /**
     * Tentukan Target Unit ID berdasarkan input request atau penugasan Tim Mutu.
     */
    public function getTargetUnitId(User $user, ?string $requestUnitId = null): int
    {
        if ($requestUnitId) {
            return decryptIdIfEncrypted($requestUnitId);
        }

        $userUnitIds = [];
        if ($user->pegawai) {
            $userUnitIds = TimMutu::where('pegawai_id', $user->pegawai->pegawai_id)
                ->pluck('org_unit_id')
                ->toArray();
        }

        if (! empty($userUnitIds)) {
            return (int) $userUnitIds[0];
        }

        return 0; // 0 represents "All Units" in this module's query logic
    }

    /**
     * Build Induk Dokumen Tree for UI breadcrumbs.
     */
    public function buildIndukDokumenTree(Indikator $indikator): array
    {
        $indukDokumenTree = [];
        $firstDokSub      = $indikator->dokSubs()->with('dokumen')->first();

        if (! $firstDokSub) {
            $parent = $indikator->parent;
            while ($parent && ! $firstDokSub) {
                $firstDokSub = $parent->dokSubs()->with('dokumen')->first();
                $parent      = $parent->parent;
            }
        }

        if ($firstDokSub) {
            array_unshift($indukDokumenTree, [
                'judul'     => $firstDokSub->judul,
                'kode'      => '',
                'type'      => 'dok_sub',
                'doksub_id' => $firstDokSub->doksub_id,
                'dok_id'    => $firstDokSub->dok_id,
            ]);
            $currDok = $firstDokSub->dokumen;
            while ($currDok) {
                array_unshift($indukDokumenTree, [
                    'judul'  => $currDok->judul,
                    'kode'   => $currDok->kode,
                    'type'   => 'dokumen',
                    'dok_id' => $currDok->dok_id,
                ]);
                $currDok = $currDok->parent;
            }
        }

        return $indukDokumenTree;
    }

    /**
     * Process links array to JSON string.
     */
    public function processLinks(array $data, string $nameKey = 'ed_links_name', string $urlKey = 'ed_links_url'): ?string
    {
        $linksArray = [];
        if (isset($data[$nameKey]) && is_array($data[$nameKey])) {
            $names = $data[$nameKey];
            $urls  = $data[$urlKey] ?? [];
            foreach ($names as $index => $name) {
                $url = $urls[$index] ?? null;
                if (! empty($name) && ! empty($url)) {
                    $linksArray[] = [
                        'name' => $name,
                        'url'  => $url,
                    ];
                }
            }
        }

        return ! empty($linksArray) ? json_encode($linksArray) : null;
    }

    /**
     * Build breadcrumb hierarchy for an indicator by traversing parent chain.
     */
    public function buildBreadcrumbs(Indikator $indikator): array
    {
        $breadcrumbs = [];
        $current     = $indikator;
        while ($current) {
            array_unshift($breadcrumbs, ['current' => $current]);
            $current = $current->parent;
        }

        return $breadcrumbs;
    }

    /**
     * Create a new Indikator (Master Data).
     */
    public function createIndikator(array $data): Indikator
    {
        return DB::transaction(function () use ($data) {
            // Handle automatic no_indikator generation if empty
            if (empty($data['no_indikator'])) {
                $year = null;

                // 1. Try to get year from Linked DokSub
                if (! empty($data['doksub_ids'])) {
                    $firstDokSub = \App\Models\Pemutu\DokSub::with('dokumen')->find($data['doksub_ids'][0]);
                    if ($firstDokSub && $firstDokSub->dokumen && $firstDokSub->dokumen->periode) {
                        if (preg_match('/\b(20\d{2})\b/', $firstDokSub->dokumen->periode, $matches)) {
                            $year = $matches[1];
                        }
                    }
                }

                // 2. Try to get year from Parent Indikator (YY prefix)
                if (! $year && ! empty($data['parent_id'])) {
                    $parent = Indikator::find($data['parent_id']);
                    if ($parent && $parent->no_indikator && strlen($parent->no_indikator) >= 2) {
                        $year = '20' . substr($parent->no_indikator, 0, 2);
                    }
                }

                if ($year) {
                    $data['no_indikator'] = $this->generateNoIndikator((int) $year);
                }
            }

            // Handle skala: filter null, encode ke JSON
            if (! empty($data['skala'])) {
                $filteredSkala = array_filter($data['skala'], fn($v) => ! is_null($v) && $v !== '');
                $data['skala'] = ! empty($filteredSkala) ? $filteredSkala : null;
            }

            $indikator = Indikator::create($data);

            // Handle many-to-many DokSub
            if (isset($data['doksub_ids'])) {
                $indikator->dokSubs()->sync($data['doksub_ids']);
            }

            // Sync Org Units
            if (isset($data['org_units'])) {
                $indikator->orgUnits()->sync($data['org_units']);
            }

            // Sync Labels
            if (isset($data['labels'])) {
                $indikator->labels()->sync($data['labels']);
            }

            // Handle KPI Assignments (only for type performa)
            if ($data['type'] === 'performa' && isset($data['kpi_assignments'])) {
                $periodeData = $this->indikatorPegawaiService->getKpiPeriodeData($indikator);
                foreach ($data['kpi_assignments'] as &$assign) {
                    $assign['periode_kpi_id'] = $periodeData['periode_kpi_id'];
                    $assign['year']           = $periodeData['year'];
                }
                foreach ($data['kpi_assignments'] as $assign) {
                    $indikator->pegawai()->create($assign);
                }
            }

            logActivity('pemutu', "Membuat indikator ({$data['type']}): {$indikator->no_indikator}", $indikator);

            return $indikator;
        });
    }

    /**
     * Update an existing Indikator.
     */
    public function updateIndikator(string|int $id, array $data): Indikator
    {
        return DB::transaction(function () use ($id, $data) {
            $indikator = Indikator::findOrFail(decryptIdIfEncrypted($id));

            // Handle skala: filter null, encode ke JSON
            if (isset($data['skala'])) {
                $filteredSkala = array_filter($data['skala'], fn($v) => ! is_null($v) && $v !== '');
                $data['skala'] = ! empty($filteredSkala) ? $filteredSkala : null;
            }

            $indikator->update($data);

            // Handle many-to-many DokSub
            if (isset($data['doksub_ids'])) {
                $indikator->dokSubs()->sync($data['doksub_ids']);
            }

            // Sync Org Units
            if (isset($data['org_units'])) {
                $indikator->orgUnits()->sync($data['org_units']);
            }

            // Sync Labels
            if (isset($data['labels'])) {
                $indikator->labels()->sync($data['labels']);
            }

            // Handle KPI Assignments (Performa only)
            if ($data['type'] === 'performa') {
                $indikator->pegawai()->delete();
                if (isset($data['kpi_assignments'])) {
                    $periodeData = $this->indikatorPegawaiService->getKpiPeriodeData($indikator);

                    foreach ($data['kpi_assignments'] as &$assign) {
                        $assign['periode_kpi_id'] = $periodeData['periode_kpi_id'];
                        $assign['year']           = $periodeData['year'];
                    }

                    foreach ($data['kpi_assignments'] as $assign) {
                        $indikator->pegawai()->create($assign);
                    }
                }
            } else {
                // If type changed from performa to something else, clear pegawai
                $indikator->pegawai()->delete();
            }

            logActivity('pemutu', "Memperbarui indikator ({$indikator->type}): {$indikator->no_indikator}", $indikator);

            return $indikator;
        });
    }

    /**
     * Soft-delete an Indikator.
     */
    public function deleteIndikator(string|int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $indikator = Indikator::findOrFail(decryptIdIfEncrypted($id));

            // Delete related data through relationships
            $indikator->dokSubs()->detach();
            $indikator->orgUnits()->detach();
            $indikator->labels()->detach();
            $indikator->pegawai()->delete();

            logActivity('pemutu', "Menghapus indikator: {$indikator->no_indikator}", $indikator);

            return $indikator->delete();
        });
    }

    /**
     * Generate automatic Indikator number in YYXXXX format.
     */
    public function generateNoIndikator(int $year): string
    {
        $prefix = substr((string) $year, -2); // E.g., "24" for 2024

        // Find the maximum sequence for the given year prefix
        $maxNo = Indikator::where('no_indikator', 'like', $prefix . '%')
            ->whereRaw('LENGTH(no_indikator) = 6')
            ->whereNull('deleted_at')
            ->max('no_indikator');

        if (! $maxNo) {
            return $prefix . '0001';
        }

        $sequence     = (int) substr($maxNo, 2);
        $nextSequence = str_pad($sequence + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $nextSequence;
    }
}
