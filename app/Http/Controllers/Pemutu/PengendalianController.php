<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PengendalianRequest;
use App\Http\Requests\Pemutu\RtmRequest;
use App\Http\Requests\Pemutu\UpdateMatrixRequest;
use App\Models\Event\Rapat;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Pemutu\PelaksanaanService;
use App\Services\Pemutu\PengendalianService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;

class PengendalianController extends Controller
{
    public function __construct(
        protected PengendalianService $PengendalianService,
        protected PeriodeSpmiService $PeriodeSpmiService,
        protected PelaksanaanService $PelaksanaanService,
    ) {}

    /**
     * Daftar periode SPMI untuk Pengendalian.
     */
    public function index()
    {
        $periodes = $this->PeriodeSpmiService->getPeriodes();

        return view('pages.pemutu.pengendalian.index', compact('periodes'));
    }

    /**
     * Halaman RTM + Pengendalian Standar (tabbed view).
     */
    public function show(PeriodeSpmi $periode, Request $request)
    {
        $unitId = $request->input('unit_id');

        // Load the latest RTM rapat (if exists) with its relations
        $rapat = $periode->latest_rtm_pengendalian;
        if ($rapat) {
            $rapat->load(['agendas', 'pesertas.user', 'ketua_user', 'notulen_user', 'author_user']);
        }

        $users = $this->PelaksanaanService->getUsersForSelect();

        return view('pages.pemutu.pengendalian.show', compact('periode', 'unitId', 'rapat', 'users'));
    }

    /**
     * DataTable AJAX: daftar Indikator untuk satu periode.
     */
    public function data(PeriodeSpmi $periode, Request $request)
    {
        $unitId = $request->input('unit_id') ? decryptIdIfEncrypted($request->input('unit_id')) : null;
        $query  = $this->PengendalianService->getIndikatorQuery($periode, $unitId);

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('indikator_info', function ($row) {
                $kode   = $row->no_indikator ?? '-';
                $nama   = $row->indikator ?? '-';
                $labels = $row->labels->map(fn($l) => '<span class="badge bg-' . ($l->color ?? 'secondary') . '-lt text-' . ($l->color ?? 'secondary') . '">' . e($l->name) . '</span>')->implode(' ');

                return '<div>
                    <div class="fw-bold text-primary">' . e($kode) . '</div>
                    <div class="text-wrap">' . e($nama) . '</div>
                    <div class="mt-1">' . $labels . '</div>
                </div>';
            })
            ->addColumn('status_ami', function ($row) {
                $pivot = $row->orgUnits->first()?->pivot;
                if ($pivot?->ami_hasil_akhir !== null) {
                    $map   = IndikatorOrgUnit::$hasilAkhirLabels;
                    $hasil = $map[$pivot->ami_hasil_akhir] ?? null;

                    return $hasil
                        ? '<span class="badge bg-' . $hasil['color'] . '-lt text-' . $hasil['color'] . '">' . $hasil['label'] . '</span>'
                        : '-';
                }

                return '<span class="badge bg-secondary-lt text-secondary">Belum AMI</span>';
            })
            ->addColumn('status_pengend', function ($row) {
                $pivot  = $row->orgUnits->first()?->pivot;
                $status = $pivot?->pengend_status;

                $map = [
                    'tetap'       => ['label' => 'Tetap', 'color' => 'success'],
                    'penyesuaian' => ['label' => 'Penyesuaian', 'color' => 'warning'],
                    'nonaktif'    => ['label' => 'Nonaktif', 'color' => 'danger'],
                ];

                if ($status && isset($map[$status])) {
                    $m = $map[$status];
                    return '<span class="badge bg-' . $m['color'] . '-lt text-' . $m['color'] . '">' . $m['label'] . '</span>';
                }

                return '<span class="badge bg-secondary-lt text-secondary">Belum Diisi</span>';
            })
            ->addColumn('eisenhower_matrix', function ($row) {
                $pivot     = $row->orgUnits->first()?->pivot;
                $important = $pivot?->pengend_important_matrix;
                $urgent    = $pivot?->pengend_urgent_matrix;

                $importantBadge = match ($important) {
                    'important'     => '<span class="badge bg-red-lt text-red">Important</span>',
                    'not_important' => '<span class="badge bg-secondary-lt text-secondary">Not Imp.</span>',
                    default         => '<span class="badge bg-light text-muted">-</span>',
                };
                $urgentBadge = match ($urgent) {
                    'urgent'     => '<span class="badge bg-orange-lt text-orange">Urgent</span>',
                    'not_urgent' => '<span class="badge bg-secondary-lt text-secondary">Not Urgent</span>',
                    default      => '<span class="badge bg-light text-muted">-</span>',
                };

                return '<div class="d-flex flex-column gap-1">' . $importantBadge . $urgentBadge . '</div>';
            })
            ->addColumn('analisis', function ($row) {
                $pivot    = $row->orgUnits->first()?->pivot;
                $analisis = $pivot?->pengend_analisis;
                if (! $analisis) {
                    return '<span class="text-muted small fst-italic">Belum diisi</span>';
                }
                // Strip HTML tags dan truncate
                $plain   = strip_tags($analisis);
                $preview = mb_strlen($plain) > 80 ? mb_substr($plain, 0, 80) . '…' : $plain;
                return '<span class="small text-muted" title="' . e($plain) . '">' . e($preview) . '</span>';
            })
            ->addColumn('action', function ($row) {
                $pivot        = $row->orgUnits->first()?->pivot;
                $indikorgunit = $pivot?->indikorgunit_id;

                return $indikorgunit
                    ? '<a href="#" class="btn btn-sm btn-primary ajax-modal-btn"
                         data-modal-target="#pengendalian-modal"
                         data-modal-size="modal-lg"
                         data-url="' . route('pemutu.pengendalian.edit-modal', encryptId($indikorgunit)) . '">
                         <i class="ti ti-pencil me-1"></i>Isi</a>'
                    : '<span class="text-muted small">-</span>';
            })
            ->filterColumn('indikator_info', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('indikator', 'like', "%{$keyword}%")
                        ->orWhere('no_indikator', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['indikator_info', 'status_ami', 'status_pengend', 'eisenhower_matrix', 'analisis', 'action'])
            ->make(true);
    }

    /**
     * Kembalikan view modal edit pengendalian (AJAX Modal).
     */
    public function editModal(IndikatorOrgUnit $indOrg)
    {
        $indOrg->load(['indikator.labels', 'orgUnit']);

        return view('pages.pemutu.pengendalian.edit-modal', compact('indOrg'));
    }

    /**
     * Simpan status + analisis pengendalian.
     */
    public function update(PengendalianRequest $request, IndikatorOrgUnit $indOrg)
    {
        $this->PengendalianService->submitPengendalian($indOrg, $request->validated());

        return jsonSuccess('Data pengendalian berhasil disimpan.');
    }

    /**
     * Update hanya Eisenhower Matrix field (inline AJAX dari DataTable).
     */
    public function updateMatrix(UpdateMatrixRequest $request, IndikatorOrgUnit $indOrg)
    {

        $this->PengendalianService->updateMatrix($indOrg, $request->only(['pengend_important_matrix', 'pengend_urgent_matrix']));

        return jsonSuccess('Matrix berhasil diperbarui.');
    }

    // ─── RTM Methods ──────────────────────────────────────────────

    /**
     * Form AJAX modal untuk membuat RTM baru.
     */
    public function createRtm(PeriodeSpmi $periode)
    {
        $users = $this->PelaksanaanService->getUsersForSelect();

        return view('pages.pemutu.pengendalian.rtm-form', compact('periode', 'users'));
    }

    /**
     * Store RTM baru (membuat Rapat + link via RapatEntitas + default agendas).
     */
    public function storeRtm(RtmRequest $request, PeriodeSpmi $periode)
    {
        $this->PengendalianService->createRtm($periode, $request->validated());

        return jsonSuccess('RTM berhasil dibuat dengan agenda default.', route('pemutu.pengendalian.show', $periode->encrypted_periodespmi_id));
    }

    /**
     * Form AJAX modal untuk edit data umum RTM.
     */
    public function editRtm(PeriodeSpmi $periode, Rapat $rapat)
    {
        $users = $this->PelaksanaanService->getUsersForSelect();

        return view('pages.pemutu.pengendalian.rtm-form', compact('periode', 'rapat', 'users'));
    }

    /**
     * Update data umum RTM.
     */
    public function updateRtm(RtmRequest $request, PeriodeSpmi $periode, Rapat $rapat)
    {
        $this->PengendalianService->updateRtm($rapat, $request->validated());

        return jsonSuccess('Data RTM berhasil diperbarui.', route('pemutu.pengendalian.show', $periode->encrypted_periodespmi_id));
    }
}
