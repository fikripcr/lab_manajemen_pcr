<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\Indikator;
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

    public function createIndikator(array $data)
    {
        return DB::transaction(function () use ($data) {
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
                foreach ($data['kpi_assignments'] as $assign) {
                    $indikator->pegawai()->create($assign);
                }
            }

            return $indikator;
        });
    }

    public function updateIndikator($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $indikator = Indikator::findOrFail($id);
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
                    foreach ($data['kpi_assignments'] as $assign) {
                        $indikator->pegawai()->create($assign);
                    }
                }
            } else {
                // If type changed from performa to something else, clear pegawai
                $indikator->pegawai()->delete();
            }

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

            return $indikator->delete();
        });
    }
}
