<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PengendalianRequest;
use App\Http\Requests\Pemutu\RtmRequest;
use App\Http\Requests\Pemutu\UpdateMatrixRequest;
use App\Models\Event\Rapat;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\PelaksanaanService;
use App\Services\Pemutu\PengendalianService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PengendalianController extends Controller
{
    public function __construct(
        protected PengendalianService $PengendalianService,
        protected PeriodeSpmiService $PeriodeSpmiService,
        protected PelaksanaanService $PelaksanaanService,
        protected IndikatorService $IndikatorService,
    ) {}

    /**
     * Daftar periode SPMI untuk Pengendalian.
     */
    public function index()
    {
        // Bypass old period selection — use global siklus from session
        $siklus = $this->PeriodeSpmiService->getSiklusData();

        $data = [
            'pageTitle' => 'Pengendalian',
            'siklus'    => $siklus,
        ];

        $users = $this->PelaksanaanService->getUsersForSelect();

        // Resolve user units and rapat for both periods
        foreach (['akademik', 'non_akademik'] as $type) {
            $periode = $siklus[$type];
            $units   = collect();
            $rapat   = null;
            
            if ($periode) {
                // Resolved user units
                $units = \App\Models\Pemutu\TimMutu::where('periodespmi_id', $periode->periodespmi_id)
                    ->with('orgUnit')
                    ->get()
                    ->pluck('orgUnit')
                    ->filter()
                    ->unique('orgunit_id');

                // Load the latest RTM rapat
                $rapat = $periode->latest_rtm_pengendalian;
                if ($rapat) {
                    $rapat->load(['agendas', 'pesertas.user', 'ketua_user', 'notulen_user', 'author_user']);
                }
            }
            
            $data[$type . 'Units'] = $units;
            $data[$type . 'Rapat'] = $rapat;
            $data[$type . 'RootDoks'] = \App\Models\Pemutu\Dokumen::whereNull('parent_id')
                ->where('periode', $siklus['tahun'])
                ->orderBy('seq')
                ->get();
        }

        $data['users'] = $users;

        return view('pages.pemutu.pengendalian.index', $data);
    }


    /**
     * DataTable AJAX: daftar Indikator untuk satu periode.
     */
    public function data(PeriodeSpmi $periode, Request $request)
    {
        $unitId  = $request->input('unit_id') ? decryptIdIfEncrypted($request->input('unit_id')) : null;
        $filters = $request->only(['pengend_status', 'pengend_important_matrix', 'pengend_urgent_matrix', 'dok_id']);
        $query   = $this->IndikatorService->getUnifiedSpmiQuery($periode, $unitId, $filters);

        return datatables()->of($query)
            ->addColumn('no', function ($row) {
                return pemutuDtColNo($row);
            })
            ->addColumn('indikator_full', function ($row) {
                return pemutuDtColIndikator($row);
            })
            ->addColumn('target', function ($row) {
                return pemutuDtColTarget($row);
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
                return pemutuDtColStatusPengend($row);
            })
            ->addColumn('eisenhower_matrix', function ($row) {
                return pemutuDtColEisenhower($row);
            })
            ->addColumn('analisis', function ($row) {
                return pemutuDtColAnalisisPengend($row);
            })
            ->addColumn('action', function ($row) {
                $pivot        = $row->orgUnits->first()?->pivot;
                $indikorgunit = $pivot?->indikorgunit_id;
                $url          = route('pemutu.pengendalian.edit-modal', encryptId($indikorgunit));

                return $indikorgunit
                    ? '<button class="btn btn-sm btn-primary ajax-modal-btn"
                         data-modal-size="modal-lg"
                         data-url="' . $url . '">
                         Isi</button>'
                    : '<span class="text-muted small">-</span>';
            })
            ->filterColumn('indikator_info', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('indikator', 'like', "%{$keyword}%")
                        ->orWhere('no_indikator', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['no', 'indikator_full', 'target', 'status_ami', 'status_pengend', 'eisenhower_matrix', 'analisis', 'action'])
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

        return jsonSuccess('RTM berhasil dibuat dengan agenda default.', route('pemutu.pengendalian.index'));
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

        return jsonSuccess('Data RTM berhasil diperbarui.');
    }
}
