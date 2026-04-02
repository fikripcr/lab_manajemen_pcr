<?php

namespace App\Services\Pemutu;

use App\Models\Pemutu\IndikatorOrgUnit;
use Illuminate\Support\Collection;

class PelaksanaanService
{
    public function __construct(
        protected IndikatorService $indikatorService,
    ) {}

    /**
     * Ambil riwayat pemantauan untuk satu IndikatorOrgUnit.
     */
    public function getMonitoringForIndikator(IndikatorOrgUnit $indOrg): Collection
    {
        return $this->indikatorService->getMonitoringHistory($indOrg->indikorgunit_id);
    }
}
