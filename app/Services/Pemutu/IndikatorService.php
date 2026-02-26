<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\PeriodeKpi;
use Illuminate\Support\Facades\DB;

class IndikatorService
{
    public function getFilteredQuery(array $filters)
    {
        $query = Indikator::with(['dokSubs.dokumen', 'labels.type', 'parent', 'orgUnits']);

        if (! empty($filters['dokumen_id'])) {
            $query->whereHas('dokSubs.dokumen', function ($q) use ($filters) {
                $q->where('dok_id', $filters['dokumen_id']);
            });
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['parent_id'])) {
            $query->where('parent_id', $filters['parent_id']);
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
        if (!empty($filters['kelompok_indikator'])) {
            $query->where('kelompok_indikator', $filters['kelompok_indikator']);
        }
        
        if (!empty($filters['tahun_dokumen'])) {
            $query->whereHas('dokSubs.dokumen', function ($q) use ($filters) {
                $q->where('periode', $filters['tahun_dokumen']);
            });
        }

        return $query->orderBy('no_indikator', 'asc');
    }

    public function createIndikator(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Handle skala: filter null, encode ke JSON
            if (!empty($data['skala'])) {
                $filteredSkala = array_filter($data['skala'], fn($v) => !is_null($v) && $v !== '');
                $data['skala'] = !empty($filteredSkala) ? $filteredSkala : null;
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
                $filteredSkala = array_filter($data['skala'], fn($v) => !is_null($v) && $v !== '');
                $data['skala'] = !empty($filteredSkala) ? $filteredSkala : null;
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
}
