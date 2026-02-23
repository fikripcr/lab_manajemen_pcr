<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\IndikatorPegawai;
use App\Models\Pemutu\PeriodeKpi;
use Illuminate\Support\Facades\Storage;

class EvaluasiKpiService
{
    /**
     * Ambil daftar PeriodeKpi dengan count pengisian per periode.
     */
    public function getPeriodes(int $perPage = 12): array
    {
        $periodes = PeriodeKpi::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->paginate($perPage);

        $periodeIds  = $periodes->pluck('periode_kpi_id');
        $totalCounts = IndikatorPegawai::whereIn('periode_kpi_id', $periodeIds)
            ->selectRaw('periode_kpi_id, COUNT(*) as total')
            ->groupBy('periode_kpi_id')
            ->pluck('total', 'periode_kpi_id');
        $filledCounts = IndikatorPegawai::whereIn('periode_kpi_id', $periodeIds)
            ->whereNotNull('realization')->where('realization', '!=', '')
            ->selectRaw('periode_kpi_id, COUNT(*) as total')
            ->groupBy('periode_kpi_id')
            ->pluck('total', 'periode_kpi_id');

        return compact('periodes', 'totalCounts', 'filledCounts');
    }

    /**
     * Bangun data informasi tambahan untuk form edit modal.
     */
    public function getEditData(IndikatorPegawai $indikatorPegawai): array
    {
        $indikator = $indikatorPegawai->indikator;

        // Hierarki Indikator
        $breadcrumbs = [];
        $current     = $indikator;
        while ($current) {
            array_unshift($breadcrumbs, compact('current'));
            $current = $current->parent;
        }

        // Pohon Dokumen Induk
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
                'judul' => $firstDokSub->judul,
                'kode'  => '',
                'type'  => 'dok_sub',
            ]);
            $currDok = $firstDokSub->dokumen;
            while ($currDok) {
                array_unshift($indukDokumenTree, [
                    'judul' => $currDok->judul,
                    'kode'  => $currDok->kode,
                    'type'  => 'dokumen',
                ]);
                $currDok = $currDok->parent;
            }
        }

        // Existing Links
        $edLinks = [];
        if (! empty($indikatorPegawai->kpi_links)) {
            $edLinks = json_decode($indikatorPegawai->kpi_links, true) ?? [];
        }

        return compact('indikator', 'indikatorPegawai', 'breadcrumbs', 'edLinks', 'indukDokumenTree');
    }

    /**
     * Perbarui data evaluasi KPI: capaian, analisis, lampiran, dan links.
     */
    public function update(IndikatorPegawai $indikatorPegawai, array $data, $attachmentFile = null): IndikatorPegawai
    {
        // Bangun array links dari input nama+url
        $linksArray = [];
        if (! empty($data['kpi_links_name']) && is_array($data['kpi_links_name'])) {
            foreach ($data['kpi_links_name'] as $i => $name) {
                $url = $data['kpi_links_url'][$i] ?? null;
                if (! empty($name) && ! empty($url)) {
                    $linksArray[] = ['name' => $name, 'url' => $url];
                }
            }
        }

        $updatePayload = [
            'realization'  => $data['realization'] ?? null,
            'kpi_analisis' => $data['kpi_analisis'] ?? null,
            'kpi_links'    => ! empty($linksArray) ? json_encode($linksArray) : null,
            'status'       => 'submitted',
        ];

        if ($attachmentFile) {
            if ($indikatorPegawai->attachment && Storage::exists($indikatorPegawai->attachment)) {
                Storage::delete($indikatorPegawai->attachment);
            }
            $updatePayload['attachment'] = $attachmentFile->store('public/pemutu/kpi_evidence');
        }

        $indikatorPegawai->update($updatePayload);

        logActivity('pemutu', 'Memperbarui evaluasi KPI: ' . ($indikatorPegawai->indikator->no_indikator ?? '-'));

        return $indikatorPegawai;
    }
}
