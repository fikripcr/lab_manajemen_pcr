<?php

namespace App\Services\Pemutu;

use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\Pemutu\TimMutu;
use App\Models\Shared\StrukturOrganisasi;

class AmiService
{
    /**
     * Ambil query Indikator siap AMI untuk periode & unit tertentu.
     * Menggunakan IndikatorService agar DRY dengan fitur ED.
     * Mengembalikan query Builder untuk DataTable.
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
     * Ambil unit yang tersedia untuk AMI pada periode tertentu,
     * berdasarkan TimMutu yang terdaftar di periode tersebut.
     */
    public function getAvailableUnits(PeriodeSpmi $periode, ?int $pegawaiId = null): \Illuminate\Support\Collection
    {
        $query = TimMutu::with('orgUnit')
            ->where('periodespmi_id', $periode->periodespmi_id);

        if ($pegawaiId) {
            $query->where('pegawai_id', $pegawaiId);
        }

        return $query->get()->pluck('orgUnit')->filter();
    }

    /**
     * Ambil detail lengkap satu IndikatorOrgUnit untuk halaman AMI Detail.
     */
    public function getDetail(IndikatorOrgUnit $indOrg): array
    {
        $indOrg->load([
            'indikator.labels.type',
            'indikator.dokSubs.dokumen',
            'indikator.parent',
            'orgUnit',
            'diskusi.pengirim',
        ]);

        $indikator = $indOrg->indikator;
        $skala     = $indikator->skala ?? [];

        // Bangun tree breadcrumb hierarki indikator
        $breadcrumbs = [];
        $current     = $indikator;
        while ($current) {
            array_unshift($breadcrumbs, $current);
            $current = $current->parent;
        }

        $hasilAkhirLabels = IndikatorOrgUnit::$hasilAkhirLabels;

        return compact('indOrg', 'indikator', 'skala', 'breadcrumbs', 'hasilAkhirLabels');
    }

    /**
     * Submit penilaian AMI: simpan hasil akhir dan temuan.
     */
    public function submitPenilaian(IndikatorOrgUnit $indOrg, array $data): IndikatorOrgUnit
    {
        $indOrg->update([
            'ami_hasil_akhir'          => $data['ami_hasil_akhir'],
            'ami_hasil_temuan'         => $data['ami_hasil_temuan'] ?? null,
            'ami_hasil_temuan_sebab'   => $data['ami_hasil_temuan_sebab'] ?? null,
            'ami_hasil_temuan_akibat'  => $data['ami_hasil_temuan_akibat'] ?? null,
            'ami_hasil_temuan_rekom'   => $data['ami_hasil_temuan_rekom'] ?? null,
        ]);

        logActivity('pemutu', 'Submit penilaian AMI: indikator #' . $indOrg->indikator_id . ' unit #' . $indOrg->org_unit_id, $indOrg);

        return $indOrg;
    }
}

