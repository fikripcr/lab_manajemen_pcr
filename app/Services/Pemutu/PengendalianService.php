<?php

namespace App\Services\Pemutu;

use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;

class PengendalianService
{
    /**
     * Ambil query Indikator untuk periode pengendalian.
     * Menggunakan IndikatorService (DRY) seperti ED dan AMI.
     */
    public function getIndikatorQuery(PeriodeSpmi $periode, ?int $unitId = null)
    {
        $filters = [
            'kelompok_indikator' => $periode->jenis,
            'tahun_dokumen'      => $periode->tahun,
        ];

        return app(IndikatorService::class)->getByOrgUnit($unitId, $filters);
    }

    /**
     * Simpan data pengendalian: status dan analisis.
     */
    public function submitPengendalian(IndikatorOrgUnit $indOrg, array $data): IndikatorOrgUnit
    {
        $indOrg->update([
            'pengend_status'           => $data['pengend_status'],
            'pengend_analisis'         => $data['pengend_analisis'] ?? null,
            'pengend_important_matrix' => $data['pengend_important_matrix'] ?? null,
            'pengend_urgent_matrix'    => $data['pengend_urgent_matrix'] ?? null,
        ]);

        logActivity('pemutu', 'Submit pengendalian: indikator #' . $indOrg->indikator_id . ' unit #' . $indOrg->org_unit_id, $indOrg);

        return $indOrg;
    }

    /**
     * Update hanya Eisenhower Matrix (Important & Urgent) secara inline/AJAX.
     */
    public function updateMatrix(IndikatorOrgUnit $indOrg, array $data): IndikatorOrgUnit
    {
        $indOrg->update([
            'pengend_important_matrix' => $data['pengend_important_matrix'] ?? null,
            'pengend_urgent_matrix'    => $data['pengend_urgent_matrix'] ?? null,
        ]);

        return $indOrg;
    }
}
