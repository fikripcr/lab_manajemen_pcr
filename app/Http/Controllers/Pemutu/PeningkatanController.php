<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\DuplikasiRequest;
use App\Http\Requests\Pemutu\PeningkatanRtmRequest;
use App\Models\Event\Rapat;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Pemutu\DuplikasiService;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\PelaksanaanService;
use App\Services\Pemutu\PeningkatanService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PeningkatanController extends Controller
{
    public function __construct(
        protected PeningkatanService $PeningkatanService,
        protected PeriodeSpmiService $PeriodeSpmiService,
        protected DuplikasiService $DuplikasiService,
        protected PelaksanaanService $PelaksanaanService,
        protected IndikatorService $IndikatorService,
    ) {}

    /**
     * Daftar periode SPMI untuk Peningkatan.
     */
    public function index()
    {
        $siklus = $this->PeriodeSpmiService->getSiklusData();
        $users = $this->PelaksanaanService->getUsersForSelect();

        $data = [
            'pageTitle' => 'Peningkatan',
            'siklus'    => $siklus,
            'users'     => $users,
        ];

        // Resolve units, RTM, and duplication status for both periods
        foreach (['akademik', 'non_akademik'] as $type) {
            $periode = $siklus[$type];
            $units   = collect();
            $rapat   = null;
            $hasDuplicated = false;

            if ($periode) {
                // Resolved user units for filtering
                $units = \App\Models\Pemutu\TimMutu::where('periodespmi_id', $periode->periodespmi_id)
                    ->with('orgUnit')
                    ->get()
                    ->pluck('orgUnit')
                    ->filter()
                    ->unique('orgunit_id');

                // Latest RTM Peningkatan
                $rapat = $periode->latest_rtm_peningkatan;
                if ($rapat) {
                    $rapat->load(['agendas', 'pesertas.user', 'ketua_user', 'notulen_user', 'author_user']);
                }

                // Duplication check
                $hasDuplicated = Indikator::where('origin_from', 'peningkatan_' . $periode->periode)->exists();
            }

            $data[$type . 'Units'] = $units;
            $data[$type . 'Rapat'] = $rapat;
            $data[$type . 'HasDuplicated'] = $hasDuplicated;
            $data[$type . 'RootDoks'] = \App\Models\Pemutu\Dokumen::whereNull('parent_id')
                ->where('periode', $siklus['tahun'])
                ->orderBy('seq')
                ->get();
        }

        return view('pages.pemutu.peningkatan.index', $data);
    }


    // ─── RTM Methods ──────────────────────────────────────────────

    public function createRtm(PeriodeSpmi $periode)
    {
        $users = $this->PelaksanaanService->getUsersForSelect();

        return view('pages.pemutu.peningkatan.rtm-form', compact('periode', 'users'));
    }

    public function storeRtm(PeningkatanRtmRequest $request, PeriodeSpmi $periode)
    {
        $this->PeningkatanService->createRtm($periode, $request->validated());

        return jsonSuccess('RTM Peningkatan berhasil dibuat.', route('pemutu.peningkatan.index'));
    }

    public function editRtm(PeriodeSpmi $periode, Rapat $rapat)
    {
        $users = $this->PelaksanaanService->getUsersForSelect();

        return view('pages.pemutu.peningkatan.rtm-form', compact('periode', 'rapat', 'users'));
    }

    public function updateRtm(PeningkatanRtmRequest $request, PeriodeSpmi $periode, Rapat $rapat)
    {
        $this->PeningkatanService->updateRtm($rapat, $request->validated());

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
        $kelompok      = $periode->jenis_periode; // 'Akademik' atau 'Non Akademik'

        // Standar lama: semua root Dokumen di periode ini yang punya indikator dgn kelompok tsb
        $rootDoks = Dokumen::whereNull('parent_id')
            ->where('periode', $periode->periode)
            ->orderBy('seq')
            ->orderBy('judul')
            ->get();

        $standarLama = collect();
        foreach ($rootDoks as $dok) {
            // Kumpulkan semua dok_id dalam tree ini (root + semua descendant)
            $treeIds = $this->collectDokumenTreeIds($dok->dok_id);

            // Hitung indikator kelompok yg terkait dgn DokSub di tree ini
            $indikatorCount = \DB::table('pemutu_indikator_doksub as ids')
                ->join('pemutu_dok_sub as ds', 'ds.doksub_id', '=', 'ids.doksub_id')
                ->join('pemutu_indikator as i', 'i.indikator_id', '=', 'ids.indikator_id')
                ->whereIn('ds.dok_id', $treeIds)
                ->where('i.kelompok_indikator', $kelompok)
                ->where('i.type', '!=', 'performa')
                ->distinct('ids.indikator_id')
                ->count('ids.indikator_id');

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
                'id'                 => encryptId($dok->dok_id),
                'dok_id'             => encryptId($dok->dok_id),
                'encrypted_id'       => encryptId($dok->dok_id),
                'kode'               => $dok->kode,
                'judul'              => $dok->judul,
                'indikator_count'    => $indikatorCount,
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
            $treeIds        = $this->collectDokumenTreeIds($dok->dok_id);
            $indikatorCount = \DB::table('pemutu_indikator_doksub as ids')
                ->join('pemutu_dok_sub as ds', 'ds.doksub_id', '=', 'ids.doksub_id')
                ->join('pemutu_indikator as i', 'i.indikator_id', '=', 'ids.indikator_id')
                ->whereIn('ds.dok_id', $treeIds)
                ->where('i.kelompok_indikator', $kelompok)
                ->where('i.type', '!=', 'performa')
                ->distinct('ids.indikator_id')
                ->count('ids.indikator_id');

            // Tampilkan juga yang belum punya indikator (dokumen sudah ada tapi indikator belum dicopy)
            $standarBaru->push([
                'id'              => encryptId($dok->dok_id),
                'dok_id'          => encryptId($dok->dok_id),
                'encrypted_id'    => encryptId($dok->dok_id),
                'kode'            => $dok->kode,
                'judul'           => $dok->judul,
                'indikator_count' => $indikatorCount,
            ]);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'kelompok'     => $kelompok,
                'old_periode'  => $periode->periode,
                'new_periode'  => $targetPeriode,
                'standar_lama' => $standarLama->values(),
                'standar_baru' => $standarBaru->values(),
            ],
        ]);
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

    /**
     * Jalankan proses duplikasi standar tertentu ke periode baru.
     */
    public function duplicateStandar(DuplikasiRequest $request, PeriodeSpmi $periode)
    {
        $validated      = $request->validated();
        $targetPeriode  = (int) $validated['target_periode'];
        $selectedDokIds = array_map('decryptIdIfEncrypted', $validated['selected_dok_ids']);

        $stats = $this->DuplikasiService->duplicateSelected($selectedDokIds, $periode->periode, $targetPeriode);

        $message = "Duplikasi berhasil! "
            . "Dokumen baru: {$stats['dokumen_cloned']}, reuse: {$stats['dokumen_reused']}, "
            . "Indikator: {$stats['indikator_cloned']} "
            . "(skip nonaktif: {$stats['indikator_skipped_nonaktif']}, skip KPI: {$stats['indikator_skipped_kpi']}), "
            . "OrgUnit: {$stats['orgunit_cloned']}";

        return jsonSuccess($message, route('pemutu.peningkatan.index'));
    }

    public function reviewData(Request $request, PeriodeSpmi $periode)
    {
        $filters = $request->only(['pengend_status', 'pengend_important_matrix', 'pengend_urgent_matrix', 'dok_id', 'unit_id']);
        $query   = $this->IndikatorService->getPeningkatanReviewQuery($periode, $filters);

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
                return '<span class="text-muted small"><i class="ti ti-folder me-1"></i> ' . e($row->dokumen_judul ?? 'Tanpa Dokumen') . '</span>';
            })
            ->addColumn('keterangan_perubahan', function ($row) {
                $parts = [];

                if ($row->prev_pengend_analisis_atsn) {
                    $parts[] = '<small class="text-muted">Analisis Atasan:</small> ' . $row->prev_pengend_analisis_atsn;
                }
                if ($row->target_lama && $row->target_lama !== $row->target_baru) {
                    $parts[] = '<small class="text-muted">Target lama:</small> ' . e($row->target_lama) . ' → ' . e($row->target_baru);
                }

                return $parts ? implode('<br>', $parts) : '<span class="text-muted">—</span>';
            })
            ->rawColumns(['no', 'indikator_full', 'target', 'status_badge', 'dokumen_standar', 'keterangan_perubahan'])
            ->make(true);
    }

    public function deleteStandarTarget(Request $request, PeriodeSpmi $periode, Dokumen $dokumen)
    {
        // Hanya boleh hapus jika dokumen ini benar-benar di periode target saat ini
        if ($dokumen->periode != $request->target_periode) {
            return response()->json(['success' => false, 'message' => 'Dokumen ini tidak berada di periode target yang diminta.']);
        }

        $deletedCount = $this->DuplikasiService->deleteDuplicatedTree($dokumen->dok_id);

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
            'target_periode'     => 'required|integer',
            'selected_dok_ids'   => 'required|array|min:1',
            'selected_dok_ids.*' => 'required|string',
        ]);

        $dokIds            = array_map('decryptIdIfEncrypted', $request->selected_dok_ids);
        $totalDeletedCount = 0;

        foreach ($dokIds as $dokId) {
            $dokumen = Dokumen::find($dokId);

            // Bypass if not found or wrong period
            if ($dokumen && $dokumen->periode == $request->target_periode) {
                $count              = $this->DuplikasiService->deleteDuplicatedTree($dokumen->dok_id);
                $totalDeletedCount += $count;
            }
        }

        return response()->json([
            'success' => true,
            'message' => count($dokIds) . " Standar terpilih (dan anak-anaknya) berhasil dihapus. Total $totalDeletedCount Dokumen terhapus bersih dari sistem.",
        ]);
    }
}
