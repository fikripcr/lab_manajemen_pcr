<?php

namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\DuplikasiRequest;
use App\Http\Requests\Pemutu\PeningkatanRtmRequest;
use App\Models\Event\Rapat;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Hr\StrukturOrganisasiService;
use App\Services\Pemutu\DokumenService;
use App\Services\Pemutu\DuplikasiService;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\IndikatorOrgUnitService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PeningkatanController extends Controller
{
    public function __construct(
        protected PeriodeSpmiService $periodeSpmiService,
        protected DuplikasiService $duplikasiService,
        protected IndikatorService $indikatorService,
        protected IndikatorOrgUnitService $indikatorOrgUnitService,
        protected StrukturOrganisasiService $strukturOrganisasiService,
        protected DokumenService $dokumenService,
        protected \App\Services\Pemutu\PegawaiService $pegawaiService,
    ) {}

    /**
     * Daftar periode SPMI untuk Peningkatan.
     */
    public function index()
    {
        $siklus = $this->periodeSpmiService->getSiklusData();
        $users = $this->pegawaiService->getUsersWithPegawaiData();

        // Active Kelompok (Akademik / Non Akademik) from session
        $activeKelompok = session('pemutu_active_kelompok', 'akademik');
        $periode = $siklus[$activeKelompok] ?? null;

        $rapat = null;
        $hasDuplicated = false;

        if ($periode) {
            // Latest RTM Peningkatan
            $rapat = $periode->latest_rtm_peningkatan;
            if ($rapat) {
                $rapat->load(['agendas', 'pesertas.user', 'ketua_user', 'notulen_user', 'author_user']);
            }

            // Duplication check
            $hasDuplicated = Indikator::where('origin_from', 'peningkatan_'.$periode->periode)->exists();
        }

        $rootDoks = $this->dokumenService->getRootsByPeriode($siklus['tahun']);

        // Staging count: how many target-period docs are still staging
        $targetPeriode = $periode ? $periode->periode + 1 : null;
        $stagingCount = 0;
        if ($targetPeriode) {
            $stagingCount = Dokumen::where('periode', $targetPeriode)
                ->where('std_is_staging', true)
                ->count();
        }

        $data = [
            'pageTitle'      => 'Peningkatan',
            'siklus'         => $siklus,
            'activeKelompok' => $activeKelompok,
            'periode'        => $periode,
            'rapat'          => $rapat,
            'hasDuplicated'  => $hasDuplicated,
            'rootDoks'       => $rootDoks,
            'users'          => $users,
            'units'          => $this->strukturOrganisasiService->getHierarchicalList(),
            'stagingCount'   => $stagingCount,
        ];

        return view('pages.pemutu.peningkatan.index', $data);
    }

    // ─── RTM Methods ──────────────────────────────────────────────

    public function createRtm(PeriodeSpmi $periode)
    {
        $users = $this->pegawaiService->getUsersWithPegawaiData();

        return view('pages.pemutu.peningkatan.rtm-form', compact('periode', 'users'));
    }

    public function storeRtm(PeningkatanRtmRequest $request, PeriodeSpmi $periode)
    {
        $this->periodeSpmiService->createRtm($periode, 'Peningkatan', $request->validated());

        return jsonSuccess('RTM Peningkatan berhasil dibuat.', route('pemutu.peningkatan.index'));
    }

    public function editRtm(PeriodeSpmi $periode, Rapat $rapat)
    {
        $users = $this->pegawaiService->getUsersWithPegawaiData();

        return view('pages.pemutu.peningkatan.rtm-form', compact('periode', 'rapat', 'users'));
    }

    public function updateRtm(PeningkatanRtmRequest $request, PeriodeSpmi $periode, Rapat $rapat)
    {
        $this->periodeSpmiService->updateRtm($rapat, $request->validated());

        return jsonSuccess('Data RTM Peningkatan berhasil diperbarui.', route('pemutu.peningkatan.index'));
    }

    // ─── Duplikasi Methods ────────────────────────────────────────

    /**
     * API: Daftar standar (dokumen root) yang memiliki indikator matching kelompok.
     * Return standar lama + standar baru (jika sudah ada di target).
     */
    public function standarList(Request $request, PeriodeSpmi $periode)
    {
        $targetPeriode = (int) $request->input('target_periode', $periode->periode + 1);
        $kelompok = $periode->jenis_periode; // 'Akademik' atau 'Non Akademik'

        // Standar lama: semua root Dokumen di periode ini yang punya indikator dgn kelompok tsb
        $rootDoks = Dokumen::whereNull('parent_id')
            ->where('periode', $periode->periode)
            ->orderBy('seq')
            ->orderBy('judul')
            ->get();

        $standarLama = collect();
        foreach ($rootDoks as $dok) {
            // Kumpulkan semua dok_id dalam tree ini (root + semua descendant)
            $treeIds = $this->duplikasiService->collectDokumenTreeIds($dok->dok_id);

            // Hitung indikator kelompok yg terkait dgn DokSub di tree ini
            $indikatorCount = $this->duplikasiService->countIndikatorByDokumenTree($treeIds, $kelompok);

            if ($indikatorCount === 0) {
                continue;
            }
            // Skip jika tidak ada indikator kelompok ini

            // Cek apakah sudah diduplikasi
            $alreadyDuplicated = Dokumen::where('judul', $dok->judul)
                ->where('jenis', $dok->jenis)
                ->where('level', $dok->level)
                ->where('periode', $targetPeriode)
                ->exists();

            $standarLama->push([
                'id' => encryptId($dok->dok_id),
                'dok_id' => encryptId($dok->dok_id),
                'encrypted_id' => encryptId($dok->dok_id),
                'kode' => $dok->kode,
                'judul' => $dok->judul,
                'indikator_count' => $indikatorCount,
                'already_duplicated' => $alreadyDuplicated,
            ]);
        }

        // Standar baru: root Dokumen di target periode dgn indikator kelompok yang sama
        $newRootDoks = Dokumen::whereNull('parent_id')
            ->where('periode', $targetPeriode)
            ->orderBy('seq')
            ->orderBy('judul')
            ->get();

        $standarBaru = collect();
        foreach ($newRootDoks as $dok) {
            $treeIds = $this->duplikasiService->collectDokumenTreeIds($dok->dok_id);
            $indikatorCount = $this->duplikasiService->countIndikatorByDokumenTree($treeIds, $kelompok);

            // Tampilkan juga yang belum punya indikator (dokumen sudah ada tapi indikator belum dicopy)
            $standarBaru->push([
                'id' => encryptId($dok->dok_id),
                'dok_id' => encryptId($dok->dok_id),
                'encrypted_id' => encryptId($dok->dok_id),
                'kode' => $dok->kode,
                'judul' => $dok->judul,
                'indikator_count' => $indikatorCount,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'kelompok' => $kelompok,
                'old_periode' => $periode->periode,
                'new_periode' => $targetPeriode,
                'standar_lama' => $standarLama->values(),
                'standar_baru' => $standarBaru->values(),
            ],
        ]);
    }


    /**
     * Jalankan proses duplikasi standar tertentu ke periode baru.
     */
    public function duplicateStandar(DuplikasiRequest $request, PeriodeSpmi $periode)
    {
        $validated = $request->validated();
        $targetPeriode = (int) $validated['target_periode'];
        $selectedDokIds = array_map('decryptIdIfEncrypted', $validated['selected_dok_ids']);

        $stats = $this->duplikasiService->duplicateSelected($selectedDokIds, $periode->periode, $targetPeriode);

        $message = 'Duplikasi berhasil! '
            ."Dokumen baru: {$stats['dokumen_cloned']}, reuse: {$stats['dokumen_reused']}, "
            ."Indikator: {$stats['indikator_cloned']} "
            ."(skip nonaktif: {$stats['indikator_skipped_nonaktif']}, skip KPI: {$stats['indikator_skipped_kpi']}), "
            ."OrgUnit: {$stats['orgunit_cloned']}";

        return jsonSuccess($message, route('pemutu.peningkatan.index'));
    }

    public function reviewData(Request $request, PeriodeSpmi $periode)
    {
        $filters = parseSpmiFilters($request, ['pengend_status', 'pengend_important_matrix', 'pengend_urgent_matrix', 'dok_id', 'unit_id']);
        $query = $this->indikatorOrgUnitService->getPeningkatanReviewQuery($periode, $filters);

        return DataTables::of($query)
            ->addColumn('no', function ($row) {
                return pemutuDtColNo($row);
            })
            ->addColumn('indikator_full', function ($row) {
                return pemutuDtColIndikator($row);
            })
            ->addColumn('target', function ($row) {
                return pemutuDtColTarget($row);
            })
            ->addColumn('status_badge', function ($row) {
                return pemutuDtColStatusPeningkatan($row);
            })
            ->addColumn('dokumen_standar', function ($row) {
                return '<span class="text-muted small"><i class="ti ti-folder me-1"></i> '.e($row->dokumen_judul ?? 'Tanpa Dokumen').'</span>';
            })
            ->addColumn('keterangan_perubahan', function ($row) {
                $parts = [];

                if ($row->prev_pengend_analisis_atsn) {
                    $parts[] = '<small class="text-muted">Analisis Atasan:</small> '.$row->prev_pengend_analisis_atsn;
                }
                if ($row->target_lama && $row->target_lama !== $row->target_baru) {
                    $parts[] = '<small class="text-muted">Target lama:</small> '.e($row->target_lama).' → '.e($row->target_baru);
                }

                return pemutuTextScroll($parts ? implode('<br>', $parts) : null);
            })
            ->filterColumn('indikator', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('indikator', 'like', "%{$keyword}%")
                        ->orWhere('no_indikator', 'like', "%{$keyword}%")
                        ->orWhereHas('orgUnit', function ($sq) use ($keyword) {
                            $sq->where('name', 'like', "%{$keyword}%")
                                ->orWhere('code', 'like', "%{$keyword}%");
                        });
                });
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('pemutu.indikator.edit', [
                    'indikator'   => encryptId($row->indikator_id),
                    'redirect_to' => url()->full(),
                ]);

                $historyUrl = route('pemutu.peningkatan.history', encryptId($row->indikorgunit_id));

                $html = '<div class="d-flex gap-1">';
                $html .= '<a href="' . $editUrl . '" class="btn btn-sm btn-ghost-primary" title="Edit Indikator">
                            <i class="ti ti-pencil"></i>
                          </a>';
                $html .= '<button type="button" class="btn btn-sm btn-ghost-info ajax-modal-btn" 
                            data-url="' . $historyUrl . '" 
                            data-modal-title="Riwayat Perubahan Indikator"
                            data-modal-size="modal-xl">
                            <i class="ti ti-history"></i>
                          </button>';
                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['no', 'indikator_full', 'target', 'status_badge', 'dokumen_standar', 'keterangan_perubahan', 'action'])
            ->make(true);
    }

    // ─── Review Edit Methods ─────────────────────────────────────

    /**
     * View history/comparison of duplicated indicator (unit specific).
     */
    public function history(string $id)
    {
        $id = decryptIdIfEncrypted($id);
        $indikOrg = $this->indikatorOrgUnitService->getIndikatorOrgUnitForHistory($id);
        
        $comparison = $this->indikatorOrgUnitService->getUnitComparisonData($indikOrg);

        return view('pages.pemutu.peningkatan._history_comparison', compact('indikOrg', 'comparison'));
    }

    /**
     * Form edit indikator review (modal AJAX).
     */
    public function editReviewItem(string $id)
    {
        $data = $this->indikatorOrgUnitService->getPeningkatanReviewEditData($id);

        return view('pages.pemutu.peningkatan._review_edit_modal', $data);
    }

    /**
     * Simpan perubahan indikator review (target & indikator text).
     */
    public function updateReviewItem(Request $request, string $id)
    {
        $request->validate([
            'indikator' => 'nullable|string|max:1000',
            'target'    => 'nullable|string|max:500',
        ]);

        $this->indikatorOrgUnitService->updatePeningkatanReviewItem($id, $request->only(['target', 'indikator']));

        return jsonSuccess('Indikator berhasil diperbarui.');
    }

    // ─── Staging Approve Methods ─────────────────────────────────

    /**
     * Final Approve: publish semua standar staging untuk periode ini.
     * Set std_is_staging = false pada semua dokumen staging di target periode.
     */
    public function approveStaging(Request $request, PeriodeSpmi $periode)
    {
        $targetPeriode = $periode->periode + 1;

        $updated = Dokumen::where('periode', $targetPeriode)
            ->where('std_is_staging', true)
            ->update(['std_is_staging' => false]);

        logActivity('pemutu', "Approve staging peningkatan: {$updated} dokumen dipublish ke periode {$targetPeriode}");

        return jsonSuccess("{$updated} standar berhasil dipublish. Indikator kini tampil di seluruh modul PPEPP.", route('pemutu.peningkatan.index'));
    }

    /**
     * API: Get staging status (berapa dokumen masih staging).
     */
    public function getStagingStatus(PeriodeSpmi $periode)
    {
        $targetPeriode = $periode->periode + 1;

        $stagingCount = Dokumen::whereNull('parent_id')
            ->where('periode', $targetPeriode)
            ->where('std_is_staging', true)
            ->count();

        $publishedCount = Dokumen::whereNull('parent_id')
            ->where('periode', $targetPeriode)
            ->where(function ($q) {
                $q->where('std_is_staging', false)
                    ->orWhereNull('std_is_staging');
            })
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'staging_count' => $stagingCount,
                'published_count' => $publishedCount,
                'target_periode' => $targetPeriode,
            ],
        ]);
    }

    public function deleteStandarTarget(Request $request, PeriodeSpmi $periode, Dokumen $dokumen)
    {
        // Hanya boleh hapus jika dokumen ini benar-benar di periode target saat ini
        if ($dokumen->periode != $request->target_periode) {
            return response()->json(['success' => false, 'message' => 'Dokumen ini tidak berada di periode target yang diminta.']);
        }

        $deletedCount = $this->duplikasiService->deleteDuplicatedTree($dokumen->dok_id);

        return response()->json([
            'success' => true,
            'message' => "Standar beserta seluruh sub dan indikatornya berhasil dihapus. ($deletedCount Dokumen terhapus)",
        ]);
    }

    /**
     * Hapus *Multiple* Standar (beserta seluruh anak dokumen dan indikatornya) secara bersamaan.
     * Dipanggil dari tombol "Hapus Terpilih" di panel Standar Baru.
     */
    public function deleteStandarTargetBulk(Request $request, PeriodeSpmi $periode)
    {
        $request->validate([
            'target_periode' => 'required|integer',
            'selected_dok_ids' => 'required|array|min:1',
            'selected_dok_ids.*' => 'required|string',
        ]);

        $dokIds = array_map('decryptIdIfEncrypted', $request->selected_dok_ids);
        $totalDeletedCount = 0;

        foreach ($dokIds as $dokId) {
            $dokumen = Dokumen::find($dokId);

            // Bypass if not found or wrong period
            if ($dokumen && $dokumen->periode == $request->target_periode) {
                $count = $this->duplikasiService->deleteDuplicatedTree($dokumen->dok_id);
                $totalDeletedCount += $count;
            }
        }

        return response()->json([
            'success' => true,
            'message' => count($dokIds)." Standar terpilih (dan anak-anaknya) berhasil dihapus. Total $totalDeletedCount Dokumen terhapus bersih dari sistem.",
        ]);
    }
}
