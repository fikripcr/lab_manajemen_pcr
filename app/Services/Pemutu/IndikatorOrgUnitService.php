<?php

namespace App\Services\Pemutu;

use App\Models\Hr\StrukturOrganisasi;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class IndikatorOrgUnitService
{
    public function __construct(protected IndikatorService $indikatorService)
    {}

    /**
     * Resolve IndikatorOrgUnit by ID (including encrypted).
     */
    public function findIndikatorOrgUnit(string|int $id): IndikatorOrgUnit
    {
        return IndikatorOrgUnit::findOrFail(decryptIdIfEncrypted($id));
    }

    /**
     * Retrieve IndikatorOrgUnit with history and unit info.
     */
    public function getIndikatorOrgUnitForHistory(string|int $id): IndikatorOrgUnit
    {
        return IndikatorOrgUnit::with(['indikator.prevIndikator', 'orgUnit'])->findOrFail(decryptIdIfEncrypted($id));
    }

    /**
     * Ambil data lengkap untuk form Edit Evaluasi Diri.
     */
    public function getEdDetail(Indikator|string $indikator, int $unitId): array
    {
        if (is_string($indikator)) {
            $indikator = $this->indikatorService->findIndikator($indikator);
        }

        $pivot = IndikatorOrgUnit::where('indikator_id', $indikator->indikator_id)
            ->where('org_unit_id', $unitId)
            ->first();

        $orgUnit = StrukturOrganisasi::find($unitId);
        $breadcrumbs = $this->indikatorService->buildBreadcrumbs($indikator);
        $indukDokumenTree = $this->indikatorService->buildIndukDokumenTree($indikator);
        $renstraPoin = $indikator->getResolvedRenstraPoin();

        $edLinks = [];
        if ($pivot && ! empty($pivot->ed_links)) {
            $edLinks = is_array($pivot->ed_links) ? $pivot->ed_links : (json_decode($pivot->ed_links, true) ?? []);
        }

        return compact('indikator', 'pivot', 'unitId', 'orgUnit', 'breadcrumbs', 'edLinks', 'indukDokumenTree', 'renstraPoin');
    }

    /**
     * Simpan data Evaluasi Diri (Update atau Insert).
     */
    public function saveEvaluasiDiri(Indikator|string $indikator, int $unitId, array $data, ?array $files = null): int
    {
        if (is_string($indikator)) {
            $indikator = $this->indikatorService->findIndikator($indikator);
        }

        $pivot = DB::table('pemutu_indikator_orgunit')
            ->where('indikator_id', $indikator->indikator_id)
            ->where('org_unit_id', $unitId)
            ->first();

        $updateData = [
            'ed_capaian'  => $data['ed_capaian'] ?? null,
            'ed_analisis' => $data['ed_analisis'] ?? null,
            'ed_skala'    => isset($data['ed_skala']) ? (int) $data['ed_skala'] : null,
            'updated_at'  => now(),
        ];

        // Process ed_links via shared helper
        $updateData['ed_links'] = $this->indikatorService->processLinks($data);

        if ($pivot) {
            DB::table('pemutu_indikator_orgunit')
                ->where('indikorgunit_id', $pivot->indikorgunit_id)
                ->update($updateData);
            $id = $pivot->indikorgunit_id;
        } else {
            $updateData['indikator_id'] = $indikator->indikator_id;
            $updateData['org_unit_id']  = $unitId;
            $updateData['target']       = '-';
            $updateData['created_at']   = now();
            $id = DB::table('pemutu_indikator_orgunit')->insertGetId($updateData);
        }

        $model = IndikatorOrgUnit::find($id);

        if (! empty($files) && $model) {
            foreach ($files as $file) {
                $model->addMedia($file)->toMediaCollection('ed_attachments');
            }
        }

        logActivity('pemutu', "Mengisi Evaluasi Diri: indikator #{$indikator->indikator_id} unit #{$unitId}", $model);

        return $id;
    }

    /**
     * Unggah file tambahan ke Evaluasi Diri.
     */
    public function uploadEdAttachment(string|int $id, array $files): IndikatorOrgUnit
    {
        $model = $this->findIndikatorOrgUnit($id);

        foreach ($files as $file) {
            $model->addMedia($file)->toMediaCollection('ed_attachments');
        }

        logActivity('pemutu', "Mengunggah " . count($files) . " file ke Evaluasi Diri ID: {$model->indikorgunit_id}");

        return $model;
    }

    /**
     * Hapus file dari Evaluasi Diri.
     */
    public function deleteEdAttachment(string|int $id, int $mediaId): bool
    {
        $model = $this->findIndikatorOrgUnit($id);
        $media = $model->getMedia('ed_attachments')->firstWhere('id', $mediaId);

        if (! $media) {
            return false;
        }

        $media->delete();
        logActivity('pemutu', "Menghapus file dari Evaluasi Diri ID: {$model->indikorgunit_id}");

        return true;
    }

    /**
     * Simpan Pelaksanaan Tindakan Perbaikan (PTP).
     */
    public function updatePtp(IndikatorOrgUnit|string $indOrg, array $data): bool
    {
        if (is_string($indOrg)) {
            $indOrg = $this->findIndikatorOrgUnit($indOrg);
        }

        $success = $indOrg->update([
            'ed_ptp_isi' => $data['ed_ptp_isi'],
        ]);

        if ($success) {
            logActivity('pemutu', "Mengisi PTP untuk indikorgunit ID: {$indOrg->indikorgunit_id}");
        }

        return $success;
    }

    /**
     * Unified Query for SPMI Modules (Evaluasi Diri, AMI, Pengendalian).
     */
    public function getUnifiedSpmiQuery(PeriodeSpmi $periode, array $additionalFilters = []): Builder
    {
        $unitId = null;
        if (isset($additionalFilters['unit_id'])) {
            $unitId = decryptIdIfEncrypted($additionalFilters['unit_id']);
            unset($additionalFilters['unit_id']);
        } elseif (isset($additionalFilters['orgunit_id'])) {
            $unitId = decryptIdIfEncrypted($additionalFilters['orgunit_id']);
            unset($additionalFilters['orgunit_id']);
        }

        $query = Indikator::query()
            ->with(['orgUnits' => function ($q) use ($unitId, $additionalFilters) {
                if ($unitId) {
                    $q->where('pemutu_indikator_orgunit.org_unit_id', $unitId);
                }
                $this->applyPivotFilters($q, $additionalFilters);
                $q->withPivot([
                    'indikorgunit_id', 'target', 'ed_capaian', 'ed_analisis', 'ed_links', 'ed_skala', 'ed_ptp_isi',
                    'ami_hasil_akhir', 'ami_hasil_temuan', 'ami_hasil_temuan_sebab', 'ami_hasil_temuan_akibat',
                    'ami_hasil_temuan_rekom', 'ami_rtp_isi', 'ami_rtp_tgl_pelaksanaan', 'ami_te_isi',
                    'pengend_status', 'pengend_status_atsn', 'pengend_analisis', 'pengend_analisis_atsn',
                    'pengend_important_matrix', 'pengend_important_matrix_atsn', 'pengend_urgent_matrix',
                    'pengend_urgent_matrix_atsn', 'prev_indikorgunit_id',
                ]);
            }, 'labels', 'parent', 'dokSubs.dokumen.parent']);

        $query->whereHas('orgUnits', function ($q) use ($unitId, $additionalFilters) {
            if ($unitId) {
                $q->where('pemutu_indikator_orgunit.org_unit_id', $unitId);
            }
            $this->applyPivotFilters($q, $additionalFilters);
        });

        $query->where('kelompok_indikator', $periode->jenis_periode);

        $query->whereHas('dokSubs.dokumen', function ($q) use ($periode, $additionalFilters) {
            $q->where('periode', $periode->periode);
            $q->where(function ($sq) {
                $sq->where('std_is_staging', false)->orWhereNull('std_is_staging');
            });

            if (! empty($additionalFilters['dok_id'])) {
                $dokId = decryptIdIfEncrypted($additionalFilters['dok_id']);
                $q->where(function ($sq) use ($dokId) {
                    $sq->where('dok_id', $dokId)->orWhere('parent_id', $dokId);
                });
            }
        });

        if (! empty($additionalFilters['indikator_type'])) {
            $query->where('type', $additionalFilters['indikator_type']);
        }

        return $query->orderBy('no_indikator', 'asc');
    }

    /**
     * Get a unified query for IndikatorOrgUnit (Unit-based view).
     */
    public function getIndikatorOrgUnitSpmiQuery(PeriodeSpmi $periode, ?int $unitId = null, array $additionalFilters = []): Builder
    {
        $query = IndikatorOrgUnit::with([
            'indikator' => fn($q) => $q->with(['parent', 'labels', 'dokSubs.dokumen']),
            'orgUnit',
        ])
            ->join('pemutu_indikator', 'pemutu_indikator_orgunit.indikator_id', '=', 'pemutu_indikator.indikator_id')
            ->where('pemutu_indikator.kelompok_indikator', $periode->jenis_periode)
            ->whereHas('indikator.dokSubs.dokumen', function ($q) use ($periode, $additionalFilters) {
                $q->where('periode', $periode->periode);
                $q->where(function ($sq) {
                    $sq->where('std_is_staging', false)->orWhereNull('std_is_staging');
                });
                if (! empty($additionalFilters['dok_id'])) {
                    $dokId = decryptIdIfEncrypted($additionalFilters['dok_id']);
                    $q->where(function ($sq) use ($dokId) {
                        $sq->where('dok_id', $dokId)->orWhere('parent_id', $dokId);
                    });
                }
            })
            ->whereNull('pemutu_indikator.deleted_at');

        if ($unitId) {
            $query->where('pemutu_indikator_orgunit.org_unit_id', $unitId);
        }

        $this->applyPivotFilters($query, $additionalFilters);

        return $query->select('pemutu_indikator_orgunit.*');
    }

    /**
     * Generate query for Peningkatan Review.
     */
    public function getPeningkatanReviewQuery(PeriodeSpmi $periode, array $filters = []): Builder
    {
        $query = IndikatorOrgUnit::query()
            ->join('pemutu_indikator', 'pemutu_indikator.indikator_id', '=', 'pemutu_indikator_orgunit.indikator_id')
            ->leftJoin('pemutu_indikator_orgunit as prev_ou', 'pemutu_indikator_orgunit.prev_indikorgunit_id', '=', 'prev_ou.indikorgunit_id')
            ->leftJoin('hr_struktur_organisasi as org', 'pemutu_indikator_orgunit.org_unit_id', '=', 'org.orgunit_id')
            ->leftJoin('pemutu_indikator_doksub as ids', 'pemutu_indikator.indikator_id', '=', 'ids.source_id')
            ->leftJoin('pemutu_dok_sub as ds', 'ds.doksub_id', '=', 'ids.doksub_id')
            ->leftJoin('pemutu_dokumen as d', 'd.dok_id', '=', 'ds.dok_id')
            ->where('pemutu_indikator.origin_from', 'peningkatan_' . $periode->periode)
            ->where('pemutu_indikator.kelompok_indikator', $periode->jenis_periode);

        if (! empty($filters['pengend_status'])) {
            if ($filters['pengend_status'] === 'empty') {
                $query->where(function ($q) {
                    $q->whereNull('prev_ou.pengend_status_atsn')->orWhere('prev_ou.pengend_status_atsn', '');
                });
            } else {
                $query->where('prev_ou.pengend_status_atsn', $filters['pengend_status']);
            }
        }

        if (! empty($filters['pengend_important_matrix'])) {
            $query->where('prev_ou.pengend_important_matrix_atsn', $filters['pengend_important_matrix']);
        }
        if (! empty($filters['pengend_urgent_matrix'])) {
            $query->where('prev_ou.pengend_urgent_matrix_atsn', $filters['pengend_urgent_matrix']);
        }
        if (! empty($filters['dok_id'])) {
            $dokId = decryptIdIfEncrypted($filters['dok_id']);
            $query->where(function ($q) use ($dokId) {
                $q->where('d.dok_id', $dokId)->orWhere('d.parent_id', $dokId);
            });
        }
        if (! empty($filters['unit_id'])) {
            $query->where('pemutu_indikator_orgunit.org_unit_id', decryptIdIfEncrypted($filters['unit_id']));
        }

        return $query->select([
            'pemutu_indikator_orgunit.indikorgunit_id', 'pemutu_indikator.indikator_id', 'pemutu_indikator.no_indikator',
            'pemutu_indikator.indikator', 'pemutu_indikator.type', 'org.name as unit_name',
            'pemutu_indikator_orgunit.target', 'pemutu_indikator_orgunit.target as target_baru',
            'prev_ou.target as target_lama', 'prev_ou.pengend_status_atsn as prev_pengend_status_atsn',
            'prev_ou.pengend_analisis_atsn as prev_pengend_analisis_atsn',
            'prev_ou.pengend_important_matrix_atsn as prev_important_atsn',
            'prev_ou.pengend_urgent_matrix_atsn as prev_urgent_atsn', 'd.judul as dokumen_judul',
        ])->groupBy([
            'pemutu_indikator_orgunit.indikorgunit_id', 'pemutu_indikator.indikator_id', 'pemutu_indikator.no_indikator',
            'pemutu_indikator.indikator', 'pemutu_indikator.type', 'org.name', 'pemutu_indikator_orgunit.target',
            'prev_ou.target', 'prev_ou.pengend_status_atsn', 'prev_ou.pengend_analisis_atsn',
            'prev_ou.pengend_important_matrix_atsn', 'prev_ou.pengend_urgent_matrix_atsn', 'd.judul',
        ])->orderBy('pemutu_indikator.no_indikator')->orderBy('org.name');
    }

    /**
     * Ambil detail lengkap satu IndikatorOrgUnit untuk halaman AMI Detail.
     */
    public function getAmiDetail(IndikatorOrgUnit|string $id): array
    {
        $indOrg = $this->findIndikatorOrgUnit($id);
        $indOrg->load(['indikator.labels', 'indikator.dokSubs.dokumen', 'indikator.parent', 'orgUnit', 'diskusi.pengirim']);

        $indikator = $indOrg->indikator;
        $breadcrumbs = $this->indikatorService->buildBreadcrumbs($indikator);
        $hasilAkhirLabels = IndikatorOrgUnit::$hasilAkhirLabels;

        return compact('indOrg', 'indikator', 'breadcrumbs', 'hasilAkhirLabels');
    }

    /**
     * Update pivot data (Generic PPEPP update).
     */
    public function updatePivotData(IndikatorOrgUnit|string $id, array $data, ?string $logMessage = null): IndikatorOrgUnit
    {
        $indOrg = is_string($id) ? $this->findIndikatorOrgUnit($id) : $id;

        // Auto-sync Superior/Atsan columns if not provided (Drafting mechanism)
        if (isset($data['pengend_status']) && ! isset($data['pengend_status_atsn'])) {
            $data['pengend_status_atsn'] = $data['pengend_status'];
        }
        if (isset($data['pengend_analisis']) && ! isset($data['pengend_analisis_atsn'])) {
            $data['pengend_analisis_atsn'] = $data['pengend_analisis'];
        }
        if (isset($data['pengend_important_matrix']) && ! isset($data['pengend_important_matrix_atsn'])) {
            $data['pengend_important_matrix_atsn'] = $data['pengend_important_matrix'];
        }
        if (isset($data['pengend_urgent_matrix']) && ! isset($data['pengend_urgent_matrix_atsn'])) {
            $data['pengend_urgent_matrix_atsn'] = $data['pengend_urgent_matrix'];
        }

        $indOrg->update($data);

        if ($logMessage) {
            logActivity('pemutu', "$logMessage: {$indOrg->indikorgunit_id}", $indOrg);
        }

        return $indOrg;
    }

    /**
     * Data for Peningkatan Review Edit modal.
     */
    public function getPeningkatanReviewEditData(string|int $id): array
    {
        $indOrg = IndikatorOrgUnit::with(['indikator', 'orgUnit'])->findOrFail(decryptIdIfEncrypted($id));
        return [
            'indOrg'    => $indOrg,
            'indikator' => $indOrg->indikator,
            'orgUnit'   => $indOrg->orgUnit,
        ];
    }

    /**
     * Update specific item in Peningkatan Review.
     */
    public function updatePeningkatanReviewItem(string|int $id, array $data): bool
    {
        $indOrg = $this->findIndikatorOrgUnit($id);
        
        DB::transaction(function () use ($indOrg, $data) {
            if (isset($data['target'])) {
                $indOrg->update(['target' => $data['target']]);
            }
            if (isset($data['indikator'])) {
                $indOrg->indikator->update(['indikator' => $data['indikator']]);
            }
        });

        logActivity('pemutu', "Update Peningkatan Review item: {$indOrg->indikorgunit_id}");
        return true;
    }

    /**
     * Comparison data for Peningkatan/History.
     */
    public function getUnitComparisonData(IndikatorOrgUnit $indikOrg): array
    {
        $prevIndikOrg = $indikOrg->prevIndikorgunit_id ? IndikatorOrgUnit::find($indikOrg->prev_indikorgunit_id) : null;
        $otherUnitsValue = IndikatorOrgUnit::where('indikator_id', $indikOrg->indikator_id)
            ->where('org_unit_id', '!=', $indikOrg->org_unit_id)
            ->whereNotNull('ed_capaian')
            ->get();

        return compact('prevIndikOrg', 'otherUnitsValue');
    }

    /**
     * Private helper to apply shared pivot filters.
     */
    protected function applyPivotFilters($query, array $filters): void
    {
        if (! empty($filters['ed_status'])) {
            if ($filters['ed_status'] === 'filled') {
                $query->whereNotNull('pemutu_indikator_orgunit.ed_capaian')->where('pemutu_indikator_orgunit.ed_capaian', '!=', '');
            } elseif ($filters['ed_status'] === 'empty') {
                $query->where(function ($q) {
                    $q->whereNull('pemutu_indikator_orgunit.ed_capaian')->orWhere('pemutu_indikator_orgunit.ed_capaian', '');
                });
            }
        }

        if (isset($filters['ami_hasil_akhir']) && $filters['ami_hasil_akhir'] !== '') {
            $val = $filters['ami_hasil_akhir'];
            if ($val === 'empty') {
                $query->where(function ($q) {
                    $q->whereNull('pemutu_indikator_orgunit.ami_hasil_akhir')->orWhere('pemutu_indikator_orgunit.ami_hasil_akhir', '');
                });
            } else {
                is_array($val) ? $query->whereIn('pemutu_indikator_orgunit.ami_hasil_akhir', $val) : $query->where('pemutu_indikator_orgunit.ami_hasil_akhir', $val);
            }
        }
    }
}
