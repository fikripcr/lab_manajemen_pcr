<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\EvaluasiDiriRequest;
use App\Http\Requests\Pemutu\PtpRequest;
use App\Services\Hr\StrukturOrganisasiService;
use App\Services\Pemutu\DokumenService;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\IndikatorOrgUnitService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EvaluasiDiriController extends Controller
{
    public function __construct(
        protected PeriodeSpmiService $periodeSpmiService,
        protected IndikatorService $indikatorService,
        protected IndikatorOrgUnitService $indikatorOrgUnitService,
        protected StrukturOrganisasiService $strukturOrganisasiService,
        protected DokumenService $dokumenService,
    ) {}

    public function index()
    {
        // Bypass old period selection — use global siklus from session
        $siklus = $this->periodeSpmiService->getSiklusData();

        // Active Kelompok (Akademik / Non Akademik) from session
        $activeKelompok = session('pemutu_active_kelompok', 'akademik');
        $periode        = $siklus[$activeKelompok] ?? null;

        // Fetch ONLY Standar documents for filter via DokumenService
        $rootDoks = $this->dokumenService->getRootsByPeriode($siklus['tahun']);

        $data = [
            'pageTitle'      => 'Evaluasi Diri',
            'siklus'         => $siklus,
            'activeKelompok' => $activeKelompok,
            'periode'        => $periode,
            'units'          => $this->strukturOrganisasiService->getHierarchicalList(),
            'rootDoks'       => $rootDoks,
        ];

        return view('pages.pemutu.evaluasi-diri.index', $data);
    }

    public function data(Request $request, string $periode)
    {
        $periodeModel = $this->periodeSpmiService->getById($periode);

        $filters = parseSpmiFilters($request, ['unit_id', 'ed_status', 'dok_id']);

        // Simpan unitId untuk digunakan di kolom action
        $unitId = $filters['unit_id'] ?? null;

        $query = $this->indikatorOrgUnitService->getUnifiedSpmiQuery($periodeModel, $filters);
        applySpmiDatatableSearch($query, $request);

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
            ->addColumn('capaian', function ($row) {
                return pemutuDtColCapaianSkalaEd($row);
            })
            ->addColumn('analisis', function ($row) {
                return pemutuDtColAnalisisEd($row);
            })
            ->addColumn('action', function ($row) use ($unitId, $periodeModel) {
                // If a specific unit is filtered, pass it. Otherwise try to get it from the pivot.
                $targetUnit = $unitId ?? ($row->orgUnits->first()->pivot->org_unit_id ?? '');
                $url        = route('pemutu.evaluasi-diri.edit', $row->encrypted_indikator_id);
                if ($targetUnit) {
                    $url .= '?unit_id=' . $targetUnit;
                }

                $periodeInfo = pemutuPeriodeStatus($periodeModel->ed_awal, $periodeModel->ed_akhir);
                if ($periodeInfo['is_active']) {
                    return '<button type="button" class="btn btn-sm btn-outline-primary ajax-modal-btn"
                        data-url="' . $url . '"
                        data-modal-title="Isi Evaluasi Diri"
                        data-modal-size="modal-xl">
                        <i class="ti ti-edit me-1"></i>Isi
                        </button>';
                } else {
                    $url .= str_contains($url, '?') ? '&readonly=1' : '?readonly=1';

                    return '<button type="button" class="btn btn-sm btn-outline-secondary ajax-modal-btn"
                        data-url="' . $url . '"
                        data-modal-title="Detail Evaluasi Diri"
                        data-modal-size="modal-xl">
                        <i class="ti ti-eye me-1"></i>Detail
                        </button>';
                }
            })
            ->filterColumn('indikator', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('indikator', 'like', "%{$keyword}%")
                        ->orWhere('no_indikator', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['no', 'indikator_full', 'target', 'capaian', 'analisis', 'action'])
            ->make(true);
    }

    public function edit(Request $request, string $id)
    {
        $targetUnitId             = $this->indikatorService->getTargetUnitId(auth()->user(), $request->input('unit_id'));
        $editData                 = $this->indikatorOrgUnitService->getEdDetail($id, $targetUnitId);
        $editData['targetUnitId'] = $targetUnitId; // Add this to fix the view error

        return view('pages.pemutu.evaluasi-diri.edit-ajax', $editData);
    }

    public function update(EvaluasiDiriRequest $request, string $id): JsonResponse
    {
        $targetUnitId = $this->indikatorService->getTargetUnitId(auth()->user(), $request->input('target_unit_id'));

        $this->indikatorOrgUnitService->saveEvaluasiDiri(
            $id,
            $targetUnitId,
            $request->validated() + $request->only(['ed_links_name', 'ed_links_url']),
            $request->file('filepond')
        );

        return jsonSuccess('Evaluasi Diri berhasil disimpan.');
    }

    public function uploadFile(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'files'   => 'required|array',
            'files.*' => 'file|max:20480',
        ]);

        $this->indikatorOrgUnitService->uploadEdAttachment($id, $request->file('files'));

        return jsonSuccess('File berhasil diunggah.', url()->previous());
    }

    public function deleteFile(Request $request, string $id, int $mediaId): JsonResponse
    {
        $success = $this->indikatorOrgUnitService->deleteEdAttachment($id, $mediaId);

        if (! $success) {
            return jsonError('File tidak ditemukan.');
        }

        return jsonSuccess('File berhasil dihapus.', url()->previous());
    }

    public function ptpData(Request $request, string $periode)
    {
        $periodeModel = $this->periodeSpmiService->getById($periode);
        $prevPeriod   = $this->periodeSpmiService->getPreviousPeriod($periodeModel);

        if (! $prevPeriod) {
            return DataTables::of(collect([]))->make(true);
        }

        // Ambil indikator KTS dari periode tahun lalu
        $filters = parseSpmiFilters($request, ['unit_id', 'dok_id', 'ptp_status']);
        $filters['ami_hasil_akhir'] = 0; // KTS
        $query = $this->indikatorOrgUnitService->getUnifiedSpmiQuery($prevPeriod, $filters);

        return DataTables::of($query)
            ->addColumn('no', function ($row) {
                return pemutuDtColNo($row);
            })
            ->addColumn('indikator_full', function ($row) {
                return pemutuDtColIndikator($row);
            })
            ->addColumn('rtp_isi', function ($row) {
                return pemutuTextScroll($row->orgUnits->first()->pivot->ami_rtp_isi);
            })
            ->addColumn('ptp_isi', function ($row) {
                return pemutuTextScroll($row->orgUnits->first()->pivot->ed_ptp_isi);
            })
            ->addColumn('action', function ($row) {
                $indOrgId = $row->orgUnits->first()->pivot->indikorgunit_id;

                return '<button type="button" class="btn btn-sm btn-outline-warning ajax-modal-btn"
                    data-url="' . route('pemutu.evaluasi-diri.ptp-edit', encryptId($indOrgId)) . '"
                    data-modal-title="Isi Pelaksanaan Tindakan Perbaikan (PTP)"
                    data-modal-size="modal-lg">
                    <i class="ti ti-edit me-1"></i>
                    Isi
                </button>';
            })
            ->filterColumn('indikator', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('indikator', 'like', "%{$keyword}%")
                        ->orWhere('no_indikator', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['no', 'indikator_full', 'rtp_isi', 'ptp_isi', 'action'])
            ->make(true);
    }

    public function editPtp(string $id)
    {
        // Still accepting encrypted ID for IndikatorOrgUnit
        $indOrg = $this->indikatorOrgUnitService->findIndikatorOrgUnit($id);

        return view('pages.pemutu.evaluasi-diri._ptp_form', compact('indOrg'));
    }

    public function updatePtp(PtpRequest $request, string $id): JsonResponse
    {
        $this->indikatorOrgUnitService->updatePtp($id, $request->validated());

        return jsonSuccess('Pelaksanaan Tindakan Perbaikan (PTP) berhasil disimpan.');
    }
}
