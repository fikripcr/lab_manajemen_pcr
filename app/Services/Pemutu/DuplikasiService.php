<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use Illuminate\Support\Facades\DB;

class DuplikasiService
{
    /**
     * Mapping lama → baru untuk setiap entity selama proses duplikasi.
     */
    protected array $dokMap    = [];
    protected array $doksubMap = [];
    protected array $indikMap  = [];

    /**
     * Statistik hasil duplikasi.
     */
    protected array $stats = [
        'dokumen_cloned'             => 0,
        'dokumen_reused'             => 0,
        'doksub_cloned'              => 0,
        'doksub_reused'              => 0,
        'indikator_cloned'           => 0,
        'indikator_skipped_nonaktif' => 0,
        'indikator_skipped_kpi'      => 0,
        'orgunit_cloned'             => 0,
        'orgunit_skipped'            => 0,
        'label_cloned'               => 0,
    ];

    /**
     * Duplikasi standar-standar tertentu (by dok_id) ke periode tujuan.
     * Jika dokumen target sudah ada (matching judul+jenis), re-use dokumen tersebut
     * dan hanya duplikasi indikator-nya.
     *
     * @param array $selectedDokIds  Daftar dok_id yang dipilih untuk diduplikasi
     * @param int   $oldPeriode      Periode (tahun) sumber
     * @param int   $newPeriode      Periode (tahun) tujuan
     * @return array                 Statistik hasil duplikasi
     */
    public function duplicateSelected(array $selectedDokIds, int $oldPeriode, int $newPeriode): array
    {
        return DB::transaction(function () use ($selectedDokIds, $oldPeriode, $newPeriode) {
            // 1. Clone/reuse selected dokumens
            foreach ($selectedDokIds as $oldDokId) {
                $oldDok = Dokumen::find($oldDokId);
                if (! $oldDok) {
                    continue;
                }

                $this->cloneDokumenTree($oldDok, null, null, $newPeriode);
            }

            // 2. Clone Indikator + pivot + orgunit for selected dokumens
            $this->cloneIndikators($oldPeriode, $newPeriode);

            logActivity('pemutu', "Duplikasi standar dari periode {$oldPeriode} ke {$newPeriode}. "
                . "Dok baru: {$this->stats['dokumen_cloned']}, Dok reuse: {$this->stats['dokumen_reused']}, "
                . "Indikator: {$this->stats['indikator_cloned']}, OrgUnit: {$this->stats['orgunit_cloned']}");

            return $this->stats;
        });
    }

    /**
     * Clone satu Dokumen beserta DokSub-nya, lalu recurse ke children.
     * Jika dokumen sudah ada di target periode (match judul+jenis), re-use.
     */
    protected function cloneDokumenTree(Dokumen $oldDok, ?int $newParentId, ?int $newParentDoksubId, int $newPeriode): void
    {
        // Cek apakah dokumen sudah ada di target periode
        $existingDok = Dokumen::where('periode', $newPeriode)
            ->where('judul', $oldDok->judul)
            ->where('jenis', $oldDok->jenis)
            ->where('level', $oldDok->level)
            ->first();

        if ($existingDok) {
            // Re-use existing dokumen, just map it
            $newDok                        = $existingDok;
            $this->dokMap[$oldDok->dok_id] = $newDok->dok_id;
            $this->stats['dokumen_reused']++;

            // Map existing DokSub too (match by judul)
            $oldDokSubs = DokSub::where('dok_id', $oldDok->dok_id)->orderBy('seq')->get();
            foreach ($oldDokSubs as $oldSub) {
                $existingSub = DokSub::where('dok_id', $newDok->dok_id)
                    ->where('judul', $oldSub->judul)
                    ->first();

                if ($existingSub) {
                    $this->doksubMap[$oldSub->doksub_id] = $existingSub->doksub_id;
                    $this->stats['doksub_reused']++;
                } else {
                    // DokSub doesn't exist yet, create it
                    $newSub = DokSub::create([
                        'dok_id'                => $newDok->dok_id,
                        'judul'                 => $oldSub->judul,
                        'kode'                  => $oldSub->kode,
                        'isi'                   => $oldSub->isi,
                        'seq'                   => $oldSub->seq,
                        'is_hasilkan_indikator' => $oldSub->is_hasilkan_indikator,
                    ]);
                    $this->doksubMap[$oldSub->doksub_id] = $newSub->doksub_id;
                    $this->stats['doksub_cloned']++;
                }

                // Check for child dokumens under this doksub
                $childDokumens = Dokumen::where('parent_doksub_id', $oldSub->doksub_id)->orderBy('seq')->get();
                foreach ($childDokumens as $childDok) {
                    $this->cloneDokumenTree($childDok, $newDok->dok_id, $this->doksubMap[$oldSub->doksub_id], $newPeriode);
                }
            }
        } else {
            // Create new dokumen
            $newDok = Dokumen::create([
                'parent_id'            => $newParentId,
                'parent_doksub_id'     => $newParentDoksubId,
                'jenis'                => $oldDok->jenis,
                'level'                => $oldDok->level,
                'seq'                  => $oldDok->seq,
                'judul'                => $oldDok->judul,
                'isi'                  => $oldDok->isi,
                'kode'                 => $oldDok->kode,
                'periode'              => $newPeriode,
                'std_is_staging'       => false,
                'std_amirtn_id'        => $oldDok->std_amirtn_id,
                'std_jeniskriteria_id' => $oldDok->std_jeniskriteria_id,
            ]);

            $this->dokMap[$oldDok->dok_id] = $newDok->dok_id;
            $this->stats['dokumen_cloned']++;

            // Clone DokSub
            $oldDokSubs = DokSub::where('dok_id', $oldDok->dok_id)->orderBy('seq')->get();
            foreach ($oldDokSubs as $oldSub) {
                $newSub = DokSub::create([
                    'dok_id'                => $newDok->dok_id,
                    'judul'                 => $oldSub->judul,
                    'kode'                  => $oldSub->kode,
                    'isi'                   => $oldSub->isi,
                    'seq'                   => $oldSub->seq,
                    'is_hasilkan_indikator' => $oldSub->is_hasilkan_indikator,
                ]);

                $this->doksubMap[$oldSub->doksub_id] = $newSub->doksub_id;
                $this->stats['doksub_cloned']++;

                // Children under this doksub
                $childDokumens = Dokumen::where('parent_doksub_id', $oldSub->doksub_id)->orderBy('seq')->get();
                foreach ($childDokumens as $childDok) {
                    $this->cloneDokumenTree($childDok, $newDok->dok_id, $newSub->doksub_id, $newPeriode);
                }
            }

            // Direct children via parent_id (no parent_doksub_id)
            $directChildren = Dokumen::where('parent_id', $oldDok->dok_id)
                ->whereNull('parent_doksub_id')
                ->orderBy('seq')
                ->get();
            foreach ($directChildren as $childDok) {
                $this->cloneDokumenTree($childDok, $newDok->dok_id, null, $newPeriode);
            }
        }
    }

    /**
     * Clone semua Indikator yang terhubung ke DokSub yang sudah dimapped.
     * Skip KPI (type=performa) dan nonaktif.
     */
    protected function cloneIndikators(int $oldPeriode, int $newPeriode): void
    {
        $oldDoksubIds = array_keys($this->doksubMap);

        if (empty($oldDoksubIds)) {
            return;
        }

        // Ambil semua indikator_id yang terhubung ke doksub lama
        $indikatorIds = DB::table('pemutu_indikator_doksub')
            ->whereIn('doksub_id', $oldDoksubIds)
            ->pluck('indikator_id')
            ->unique()
            ->toArray();

        if (empty($indikatorIds)) {
            return;
        }

        $indikators = Indikator::whereIn('indikator_id', $indikatorIds)
            ->with(['dokSubs', 'labels', 'indikatorOrgUnits'])
            ->get();

        // First pass: clone indikators
        foreach ($indikators as $oldIndik) {
            // Skip KPI (performa)
            if ($oldIndik->type === 'performa') {
                $this->stats['indikator_skipped_kpi']++;
                continue;
            }

            // Skip nonaktif
            if ($oldIndik->peningkat_nonaktif_indik == 1) {
                $this->stats['indikator_skipped_nonaktif']++;
                continue;
            }

            // Skip if already duplicated (has prev_indikator_id pointing to this)
            $alreadyCloned = Indikator::where('prev_indikator_id', $oldIndik->indikator_id)->exists();
            if ($alreadyCloned) {
                continue;
            }

            $oldNo = $oldIndik->no_indikator;
            $newNo = $oldNo;
            if ($oldNo && strlen($oldNo) == 6 && is_numeric($oldNo)) {
                $newPrefix = substr((string) $newPeriode, -2);
                $newNo     = $newPrefix . substr($oldNo, 2);
            }

            $newIndik = Indikator::create([
                'type'                     => $oldIndik->type,
                'kelompok_indikator'       => $oldIndik->kelompok_indikator,
                'parent_id'                => null,
                'prev_indikator_id'        => $oldIndik->indikator_id,
                'no_indikator'             => $newNo,
                'indikator'                => $oldIndik->indikator,
                'target'                   => $oldIndik->target,
                'unit_ukuran'              => $oldIndik->unit_ukuran,
                'jenis_indikator'          => $oldIndik->jenis_indikator,
                'jenis_data'               => $oldIndik->jenis_data,
                'periode_jenis'            => $oldIndik->periode_jenis,
                'periode_mulai'            => $oldIndik->periode_mulai,
                'periode_selesai'          => $oldIndik->periode_selesai,
                'seq'                      => $oldIndik->seq,
                'level_risk'               => $oldIndik->level_risk,
                'origin_from'              => 'peningkatan_' . $oldPeriode,
                'hash'                     => $oldIndik->hash,
                'peningkat_nonaktif_indik' => null,
                'skala'                    => $oldIndik->skala,
                'keterangan'               => $oldIndik->keterangan,
            ]);

            $this->indikMap[$oldIndik->indikator_id] = $newIndik->indikator_id;
            $this->stats['indikator_cloned']++;

            // Clone pivot: indikator_doksub (re-link to new doksub)
            foreach ($oldIndik->dokSubs as $oldDokSub) {
                if (isset($this->doksubMap[$oldDokSub->doksub_id])) {
                    DB::table('pemutu_indikator_doksub')->insert([
                        'indikator_id'          => $newIndik->indikator_id,
                        'doksub_id'             => $this->doksubMap[$oldDokSub->doksub_id],
                        'is_hasilkan_indikator' => $oldDokSub->pivot->is_hasilkan_indikator ?? false,
                        'created_at'            => now(),
                        'updated_at'            => now(),
                    ]);
                }
            }

            // Clone pivot: indikator_label
            foreach ($oldIndik->labels as $label) {
                DB::table('pemutu_indikator_label')->insert([
                    'indikator_id' => $newIndik->indikator_id,
                    'label_id'     => $label->label_id,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
                $this->stats['label_cloned']++;
            }

            // Clone IndikatorOrgUnit
            foreach ($oldIndik->indikatorOrgUnits as $oldOrgUnit) {
                if ($oldOrgUnit->pengend_status === 'nonaktif') {
                    $this->stats['orgunit_skipped']++;
                    continue;
                }

                IndikatorOrgUnit::create([
                    'indikator_id'             => $newIndik->indikator_id,
                    'org_unit_id'              => $oldOrgUnit->org_unit_id,
                    'prev_indikorgunit_id'     => $oldOrgUnit->indikorgunit_id,
                    'target'                   => $oldOrgUnit->target,
                    'ed_capaian'               => null, 'ed_analisis'             => null,
                    'ed_attachment'            => null, 'ed_links'                => null, 'ed_skala' => null,
                    'ami_hasil_akhir'          => null, 'ami_hasil_temuan'        => null,
                    'ami_hasil_temuan_sebab'   => null, 'ami_hasil_temuan_akibat' => null,
                    'ami_hasil_temuan_rekom'   => null,
                    'pengend_status'           => null, 'pengend_target'          => null,
                    'pengend_analisis'         => null, 'pengend_penyesuaian'     => null,
                    'pengend_important_matrix' => null, 'pengend_urgent_matrix'   => null,
                ]);
                $this->stats['orgunit_cloned']++;
            }
        }

        // Second pass: resolve parent_id
        foreach ($this->indikMap as $oldId => $newId) {
            $oldIndik = Indikator::find($oldId);
            if ($oldIndik && $oldIndik->parent_id && isset($this->indikMap[$oldIndik->parent_id])) {
                Indikator::where('indikator_id', $newId)
                    ->update(['parent_id' => $this->indikMap[$oldIndik->parent_id]]);
            }
        }
    }

    /**
     * Hapus sebuah branch/tree dokumen hasil duplikasi beserta indikator-indikatornya.
     * Ini digunakan jika user ingin 'undo' duplikasi standar tertentu di periode target.
     *
     * @param int $rootDokId ID Dokumen (Standar) teratas yang akan dihapus
     * @return int Jumlah dokumen anak beserta parent yang berhasil dihapus
     */
    public function deleteDuplicatedTree(int $rootDokId): int
    {
        return DB::transaction(function () use ($rootDokId) {
            $deletedDokCount = 0;

            // 1. Kumpulkan semua dok_id dalam tree ini
            $allDokIds = $this->collectDokumenTreeIds($rootDokId);

            if (empty($allDokIds)) {
                return 0;
            }

            // 2. Kumpulkan semua doksub_id dari dokumen-dokumen ini
            $allDoksubIds = DokSub::whereIn('dok_id', $allDokIds)->pluck('doksub_id')->toArray();

            // 3. Kumpulkan semua indikator_id yang terhubung hanya ke doksub_id ini
            // dan memiliki prefix origin_from 'peningkatan_' (sebagai pengaman tambahan supaya tidak hapus indikator master)
            if (! empty($allDoksubIds)) {
                $indikatorIds = DB::table('pemutu_indikator_doksub')
                    ->whereIn('doksub_id', $allDoksubIds)
                    ->pluck('indikator_id')
                    ->unique()
                    ->toArray();

                if (! empty($indikatorIds)) {
                    // Filter indikator yang benar-benar hasil duplikasi peningkatan
                    $indikatorsToDelete = Indikator::whereIn('indikator_id', $indikatorIds)
                        ->where('origin_from', 'like', 'peningkatan_%')
                        ->pluck('indikator_id')
                        ->toArray();

                    if (! empty($indikatorsToDelete)) {
                        // Hapus pivot dan relasi indikator
                        DB::table('pemutu_indikator_orgunit')->whereIn('indikator_id', $indikatorsToDelete)->delete();
                        DB::table('pemutu_indikator_doksub')->whereIn('indikator_id', $indikatorsToDelete)->delete();
                        DB::table('pemutu_indikator_label')->whereIn('indikator_id', $indikatorsToDelete)->delete();

                        // Hapus indikatornya (force delete)
                        Indikator::whereIn('indikator_id', $indikatorsToDelete)->forceDelete();
                    }
                }
            }

            // 4. Hapus DokSub dan Dokumen dari bawah ke atas (reverse order untuk menghindari constraint issues jika DB punya FK strict)
            // Walaupun Laravel biasanya cascade kalau didefinisikan, kita hapus manual untuk kepastian (force delete)
            if (! empty($allDoksubIds)) {
                DokSub::whereIn('doksub_id', $allDoksubIds)->forceDelete();
            }

            // Delete dokumen (force delete)
            $deletedDokCount = Dokumen::whereIn('dok_id', $allDokIds)->forceDelete();

            return $deletedDokCount;
        });
    }

    /**
     * Kumpulkan semua dok_id di bawah root (termasuk root itu sendiri).
     */
    protected function collectDokumenTreeIds(int $rootId): array
    {
        $ids      = [$rootId];
        $children = Dokumen::where('parent_id', $rootId)->pluck('dok_id');

        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->collectDokumenTreeIds($childId));
        }

        return $ids;
    }
}
