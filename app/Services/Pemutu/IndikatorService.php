<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeKpi;
use App\Models\Pemutu\PeriodeSpmi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class IndikatorService
{
    public function getFilteredQuery(array $filters)
    {
        $query = Indikator::with(['dokSubs.dokumen', 'labels.type', 'parent', 'orgUnits']);

        if (! empty($filters['dokumen_id'])) {
            $dokId = decryptIdIfEncrypted($filters['dokumen_id']);
            $query->whereHas('dokSubs.dokumen', function ($q) use ($dokId) {
                $q->where('dok_id', $dokId);
            });
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['parent_id'])) {
            $query->where('parent_id', decryptIdIfEncrypted($filters['parent_id']));
        }

        // Filter by year (Dokumen.periode)
        if (! empty($filters['periode'])) {
            $query->whereHas('dokSubs.dokumen', function ($q) use ($filters) {
                $q->where('periode', $filters['periode']);
            });
        }

        return $query->orderBy('no_indikator', 'asc');
    }

    public function getIndikatorById($id)
    {
        return Indikator::with(['dokSubs.dokumen', 'labels.type', 'orgUnits', 'pegawai.pegawai', 'parent'])->find($id);
    }

    /**
     * Ambil query Indikator berdasarkan satu Unit Organisasi.
     * Digunakan oleh fitur Evaluasi Diri (ED) dan Audit Mutu Internal (AMI).
     *
     * @param int|null $unitId ID dari StrukturOrganisasi (opsional)
     * @param bool $withAmiFilter Jika true, hanya tampilkan yang sudah mengisi ED capaian (untuk AMI)
     * @param array $filters Filter tambahan (opsional) seperti 'kelompok_indikator' atau 'tahun_dokumen'
     */
    public function getByOrgUnit(?int $unitId = null, array $filters = [])
    {
        $query = Indikator::with(['orgUnits' => function ($q) use ($unitId) {
            if ($unitId) {
                $q->where('pemutu_indikator_orgunit.org_unit_id', $unitId);
            }
            $q->withPivot([
                'indikorgunit_id',
                'target',
                'ed_capaian',
                'ed_analisis',
                'ed_attachment',
                'ed_links',
                'ed_skala',
                'ami_hasil_akhir',
                'ami_hasil_temuan',
                'ami_hasil_temuan_sebab',
                'ami_hasil_temuan_akibat',
                'ami_hasil_temuan_rekom',
                'pengend_status',
                'pengend_target',
                'pengend_analisis',
                'pengend_penyesuaian',
                'pengend_important_matrix',
                'pengend_urgent_matrix',
            ]);
        }, 'labels', 'parent']);

        $query->whereHas('orgUnits', function ($q) use ($unitId) {
            if ($unitId) {
                $q->where('pemutu_indikator_orgunit.org_unit_id', $unitId);
            }
        });

        // Terapkan filter tambahan jika ada
        if (! empty($filters['kelompok_indikator'])) {
            $query->where('kelompok_indikator', $filters['kelompok_indikator']);
        }

        if (! empty($filters['tahun_dokumen'])) {
            $query->whereHas('dokSubs.dokumen', function ($q) use ($filters) {
                $q->where('periode', $filters['tahun_dokumen']);
            });
        }

        return $query->orderBy('no_indikator', 'asc');
    }

    public function createIndikator(array $data)
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
                $periodeData = $this->getKpiPeriodeData($indikator);
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

    public function updateIndikator($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $indikator = Indikator::findOrFail($id);

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
                    $periodeData = $this->getKpiPeriodeData($indikator);

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

    public function deleteIndikator($id)
    {
        return DB::transaction(function () use ($id) {
            $indikator = Indikator::findOrFail($id);

            // Delete related data through relationships
            $indikator->dokSubs()->detach();
            $indikator->orgUnits()->detach();
            $indikator->labels()->detach();
            $indikator->pegawai()->delete();

            logActivity('pemutu', "Menghapus indikator: {$indikator->no_indikator}", $indikator);

            return $indikator->delete();
        });
    }

    private function getKpiPeriodeData(Indikator $indikator)
    {
        $activePeriode = PeriodeKpi::where('is_active', 1)->first();
        $kpiYear       = $activePeriode ? $activePeriode->tahun : null;
        $periodeId     = $activePeriode ? $activePeriode->periode_kpi_id : null;

        if (! $kpiYear) {
            try {
                // Determine year from Standar's Dokumen Periode (for 'performa' indicators, the parent should be 'standar')
                $parentStandar = $indikator->parent;
                if ($parentStandar) {
                    $firstDokSub = $parentStandar->dokSubs()->first();
                    if ($firstDokSub && $firstDokSub->dokumen) {
                        $periodeStr = $firstDokSub->dokumen->periode;
                        // Extract a year-like format (e.g. 2024 from '2024-2029')
                        if (preg_match('/\b(20\d{2})\b/', $periodeStr, $matches)) {
                            $kpiYear = $matches[1];
                        }
                    }
                }
            } catch (\Exception $e) {
                // Ignore exception, fallback downstream
            }
        }

        return [
            'periode_kpi_id' => $periodeId,
            'year'           => $kpiYear ?: date('Y'),
        ];
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
            ->max('no_indikator');

        if (! $maxNo) {
            return $prefix . '0001';
        }

        $sequence     = (int) substr($maxNo, 2);
        $nextSequence = str_pad($sequence + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $nextSequence;
    }
    /**
     * Get a unified query for Indicators and their OrgUnit pivot data.
     * Standardized for ED, AMI, and Pengendalian.
     */
    public function getUnifiedSpmiQuery(PeriodeSpmi $periode, ?int $unitId = null, array $additionalFilters = []): Builder
    {
        $query = Indikator::with(['orgUnits' => function ($q) use ($unitId) {
            if ($unitId) {
                $q->where('pemutu_indikator_orgunit.org_unit_id', $unitId);
            }
            $q->withPivot([
                'indikorgunit_id',
                'target',
                'ed_capaian',
                'ed_analisis',
                'ed_attachment',
                'ed_links',
                'ed_skala',
                'ed_ptp_isi',
                'ami_hasil_akhir',
                'ami_hasil_temuan',
                'ami_hasil_temuan_sebab',
                'ami_hasil_temuan_akibat',
                'ami_hasil_temuan_rekom',
                'ami_rtp_isi',
                'ami_rtp_tgl_pelaksanaan',
                'ami_te_isi',
                'pengend_status',
                'pengend_target',
                'pengend_analisis',
                'pengend_penyesuaian',
                'pengend_important_matrix',
                'pengend_urgent_matrix',
                'prev_indikorgunit_id',
            ]);
        }, 'labels.type', 'parent', 'dokSubs.dokumen.parent']);

        $query->whereHas('orgUnits', function ($q) use ($unitId, $additionalFilters) {
            if ($unitId) {
                $q->where('pemutu_indikator_orgunit.org_unit_id', $unitId);
            }

            // Status ED: 'isi' or 'kosong'
            if (! empty($additionalFilters['ed_status'])) {
                if ($additionalFilters['ed_status'] === 'isi') {
                    $q->whereNotNull('ed_capaian')->where('ed_capaian', '!=', '');
                } elseif ($additionalFilters['ed_status'] === 'kosong') {
                    $q->where(function ($sq) {
                        $sq->whereNull('ed_capaian')->orWhere('ed_capaian', '');
                    });
                }
            }

            // Filter by ED Skala (exact or array)
            if (isset($additionalFilters['ed_skala'])) {
                $val = $additionalFilters['ed_skala'];
                is_array($val) ? $q->whereIn('ed_skala', $val) : $q->where('ed_skala', $val);
            }

            // Filter by AMI Hasil Akhir (exact or array)
            if (isset($additionalFilters['ami_hasil_akhir'])) {
                $val = $additionalFilters['ami_hasil_akhir'];
                is_array($val) ? $q->whereIn('ami_hasil_akhir', $val) : $q->where('ami_hasil_akhir', $val);
            }

            // Filter Pengendalian Status
            if (! empty($additionalFilters['pengend_status'])) {
                $val = $additionalFilters['pengend_status'];
                is_array($val) ? $q->whereIn('pengend_status', $val) : $q->where('pengend_status', $val);
            }
        });

        // Current Periode Context
        $query->where('kelompok_indikator', $periode->jenis_periode);

        $query->whereHas('dokSubs.dokumen', function ($q) use ($periode, $additionalFilters) {
            $q->where('periode', $periode->periode);

            // Filter specific document (Standar/Root) if provided
            if (! empty($additionalFilters['dok_id'])) {
                $dokId = decryptIdIfEncrypted($additionalFilters['dok_id']);
                // Check if it matches dok_id or its parents (if we want to filter by root)
                // For simplicity, we filter by the specific dok_id linked to the indicator
                $q->where('dok_id', $dokId);
            }
        });

        // Filter by Indikator Type
        if (! empty($additionalFilters['indikator_type'])) {
            $query->where('type', $additionalFilters['indikator_type']);
        }

        return $query->orderBy('no_indikator', 'asc');
    }

    /**
     * Get data for Peningkatan (Review Duplication).
     */
    public function getPeningkatanReviewQuery(PeriodeSpmi $periode): Builder
    {
        return IndikatorOrgUnit::query()
            ->join('pemutu_indikator', 'pemutu_indikator.indikator_id', '=', 'pemutu_indikator_orgunit.indikator_id')
            ->leftJoin('pemutu_indikator_orgunit as prev_ou', 'pemutu_indikator_orgunit.prev_indikorgunit_id', '=', 'prev_ou.indikorgunit_id')
            ->leftJoin('struktur_organisasi as org', 'pemutu_indikator_orgunit.org_unit_id', '=', 'org.orgunit_id')
            ->leftJoin('pemutu_indikator_doksub as ids', 'pemutu_indikator.indikator_id', '=', 'ids.indikator_id')
            ->leftJoin('pemutu_dok_sub as ds', 'ds.doksub_id', '=', 'ids.doksub_id')
            ->leftJoin('pemutu_dokumen as d', 'd.dok_id', '=', 'ds.dok_id')
            ->where('pemutu_indikator.origin_from', 'peningkatan_' . $periode->periode)
            ->select([
                'pemutu_indikator_orgunit.indikorgunit_id',
                'pemutu_indikator.no_indikator',
                DB::raw('CONCAT("<div class=\"indicator-scroll\">", pemutu_indikator.indikator, "</div>") as nama_indikator'),
                'pemutu_indikator.type',
                'org.name as unit_name',
                'pemutu_indikator_orgunit.target as target_baru',
                'prev_ou.target as target_lama',
                'prev_ou.pengend_status as prev_pengend_status',
                'prev_ou.pengend_target as prev_pengend_target',
                'prev_ou.pengend_penyesuaian as prev_pengend_penyesuaian',
                'd.judul as dokumen_judul',
            ])
            ->groupBy([
                'pemutu_indikator_orgunit.indikorgunit_id',
                'pemutu_indikator.no_indikator',
                'pemutu_indikator.indikator',
                'pemutu_indikator.type',
                'org.name',
                'pemutu_indikator_orgunit.target',
                'prev_ou.target',
                'prev_ou.pengend_status',
                'prev_ou.pengend_target',
                'prev_ou.pengend_penyesuaian',
                'd.judul',
            ])
            ->orderBy('pemutu_indikator.no_indikator')
            ->orderBy('org.name');
    }
}
