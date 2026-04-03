<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\AmiRequest;
use App\Services\Pemutu\DokumenService;
use App\Http\Requests\Pemutu\RtpRequest;
use App\Http\Requests\Pemutu\TeRequest;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Hr\StrukturOrganisasiService;
use App\Services\Pemutu\AmiExportService;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\IndikatorOrgUnitService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class AmiController extends Controller
{
    public function __construct(
        protected AmiExportService $amiExportService,
        protected PeriodeSpmiService $periodeSpmiService,
        protected IndikatorService $indikatorService,
        protected IndikatorOrgUnitService $indikatorOrgUnitService,
        protected StrukturOrganisasiService $strukturOrganisasiService,
        protected DokumenService $dokumenService,
    ) {}

    /**
     * Daftar periode SPMI untuk AMI.
     */
    public function index()
    {
        // Bypass old period selection — use global siklus from session
        $siklus = $this->periodeSpmiService->getSiklusData();

        // Active Kelompok (Akademik / Non Akademik) from session
        $activeKelompok = session('pemutu_active_kelompok', 'akademik');
        $periode = $siklus[$activeKelompok] ?? null;

        // Fetch root documents for filter
        $rootDoks = $this->dokumenService->getRootsByPeriode($siklus['tahun']);

        $data = [
            'pageTitle'      => 'Audit Mutu Internal (AMI)',
            'siklus'         => $siklus,
            'activeKelompok' => $activeKelompok,
            'periode'        => $periode,
            'units'          => $this->strukturOrganisasiService->getHierarchicalList(),
            'rootDoks'       => $rootDoks,
        ];

        return view('pages.pemutu.ami.index', $data);
    }

    /**
     * DataTable AJAX: daftar Indikator untuk satu periode yang sudah isi ED.
     * Mengikuti pola ED: query dari Indikator, akses data pivot via orgUnits->first()->pivot.
     */
    public function data(PeriodeSpmi $periode, Request $request)
    {
        $filters = parseSpmiFilters($request, ['orgunit_id', 'ami_hasil_akhir', 'ed_status', 'dok_id', 'rtp_status', 'kelompok_indikator']);

        $query = $this->indikatorOrgUnitService->getUnifiedSpmiQuery($periode, $filters);
        applySpmiDatatableSearch($query, $request);

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
            ->addColumn('ed_capaian', function ($row) {
                return pemutuDtColCapaianSkalaEd($row);
            })
            ->addColumn('ed_analisis', function ($row) {
                return pemutuDtColAnalisisEd($row);
            })
            ->addColumn('ami_hasil', function ($row) {
                $pivot = $row->orgUnits->first()?->pivot;
                if (! $pivot) {
                    return '<span class="text-muted small">-</span>';
                }

                $textHtml = '';
                if (! empty($pivot->ami_hasil_temuan)) {
                    $textHtml .= '<div><strong>Temuan:</strong> ' . nl2br(e($pivot->ami_hasil_temuan)) . '</div>';
                }
                if (! empty($pivot->ami_hasil_temuan_sebab)) {
                    $textHtml .= '<div class="mt-3"><strong>Sebab:</strong> ' . nl2br(e($pivot->ami_hasil_temuan_sebab)) . '</div>';
                }
                if (! empty($pivot->ami_hasil_temuan_akibat)) {
                    $textHtml .= '<div class="mt-3"><strong>Akibat:</strong> ' . nl2br(e($pivot->ami_hasil_temuan_akibat)) . '</div>';
                }

                $scrollContent = pemutuTextScroll($textHtml ?: null);
                $statusHtml    = pemutuDtColStatusAmi($row);

                return '<div>' . $scrollContent .
                    '<div class="pt-2 border-top">' . $statusHtml . '</div>' .
                    '</div>';
            })
            ->addColumn('action', function ($row) use ($periode) {
                $pivot        = $row->orgUnits->first()?->pivot;
                $indikorgunit = $pivot?->indikorgunit_id;
                $periodeInfo  = pemutuPeriodeStatus($periode->ami_awal, $periode->ami_akhir);

                if ($indikorgunit) {
                    $url = route('pemutu.ami.detail', encryptId($indikorgunit));
                    if ($periodeInfo['is_active']) {
                        return '<a href="' . $url . '" class="btn btn-sm btn-primary"><i class="ti ti-edit me-1"></i>Isi</a>';
                    } else {
                        return '<a href="' . $url . '?readonly=1" class="btn btn-sm btn-outline-secondary"><i class="ti ti-eye me-1"></i>Detail</a>';
                    }
                }

                return '<span class="text-muted small">-</span>';
            })
            ->addColumn('rtp_isi', function ($row) {
                return pemutuTextScroll($row->orgUnits->first()?->pivot->ami_rtp_isi);
            })
            ->addColumn('rtp_tgl', function ($row) {
                $tgl = $row->orgUnits->first()?->pivot->ami_rtp_tgl_pelaksanaan;

                return $tgl ? formatTanggalIndo($tgl) : '<span class="text-muted small">-</span>';
            })
            ->addColumn('auditor_recom', function ($row) {
                $text = $row->orgUnits->first()?->pivot->ami_hasil_temuan_rekom;
                return pemutuTextScroll(! empty($text) ? nl2br(e($text)) : null);
            })
            ->addColumn('action_rtp', function ($row) use ($periode) {
                $pivot = $row->orgUnits->first()?->pivot;

                // Hanya muncul jika hasil AMI adalah KTS (0)
                if ($pivot?->ami_hasil_akhir === 0) {
                    $indikorgunit = $pivot->indikorgunit_id;
                    $hasRtp       = ! empty($pivot->ami_rtp_isi);
                    $periodeInfo  = pemutuPeriodeStatus($periode->ami_awal, $periode->ami_akhir);

                    if ($periodeInfo['is_active']) {
                        $btnClass = $hasRtp ? 'btn-outline-warning' : 'btn-warning';
                        $icon     = $hasRtp ? 'ti-edit' : 'ti-plus';

                        return '<button class="btn btn-sm ' . $btnClass . ' btn-rtp ajax-modal-btn"
                            data-url="' . route('pemutu.ami.rtp-edit', encryptId($indikorgunit)) . '"
                            data-title="Isi Rencana Tindakan Perbaikan (RTP)">
                            <i class="ti ' . $icon . ' me-1"></i>Isi
                        </button>';
                    } else {
                        return '<button class="btn btn-sm btn-outline-secondary btn-rtp ajax-modal-btn"
                            data-url="' . route('pemutu.ami.rtp-edit', encryptId($indikorgunit)) . '?readonly=1"
                            data-title="Detail RTP">
                            <i class="ti ti-eye me-1"></i>Detail
                        </button>';
                    }
                }

                return '<span class="text-muted small">-</span>';
            })
            ->filterColumn('indikator', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('indikator', 'like', "%{$keyword}%")
                        ->orWhere('no_indikator', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['no', 'indikator_full', 'target', 'ed_capaian', 'ed_analisis', 'ami_hasil', 'action', 'rtp_isi', 'rtp_tgl', 'auditor_recom', 'action_rtp'])
            ->make(true);
    }

    /**
     * Halaman detail AMI (non-modal).
     */
    public function detail(string $id)
    {
        $data = $this->indikatorOrgUnitService->getAmiDetail($id);

        return view('pages.pemutu.ami.detail', $data);
    }

    /**
     * Submit penilaian AMI.
     */
    public function submitNilai(AmiRequest $request, string $id)
    {
        $indOrg = $this->indikatorOrgUnitService->updatePivotData($id, $request->validated(), 'Submit penilaian AMI');

        return jsonSuccess('Penilaian AMI berhasil disimpan.', route('pemutu.ami.detail', $indOrg->encrypted_indorgunit_id));
    }

    /**
     * Modal Form: Isi Rencana Tindakan Perbaikan (RTP).
     */
    public function editRtp(IndikatorOrgUnit $indOrg)
    {
        return view('pages.pemutu.ami._rtp_form', compact('indOrg'));
    }

    /**
     * Simpan RTP.
     */
    public function updateRtp(RtpRequest $request, string $id)
    {
        $this->indikatorOrgUnitService->updatePivotData($id, $request->validated(), 'Update RTP AMI');

        return jsonSuccess('Rencana Tindakan Perbaikan (RTP) berhasil disimpan.');
    }

    /**
     * Data untuk tab Tinjauan Efektivitas (KTS Tahun Lalu).
     */
    public function teData(Request $request, PeriodeSpmi $periode)
    {
        // Cari periode tahun lalu dengan jenis yang sama
        $prevPeriod = $this->periodeSpmiService->getPreviousPeriod($periode);

        if (! $prevPeriod) {
            return DataTables::of(collect([]))->make(true);
        }

        // Ambil indikator KTS dari periode tahun lalu
        $filters = parseSpmiFilters($request, ['unit_id', 'dok_id', 'te_status']);
        $filters['ami_hasil_akhir'] = 0; // KTS
        $query = $this->indikatorOrgUnitService->getUnifiedSpmiQuery($prevPeriod, $filters);

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
            ->addColumn('rtp', function ($row) {
                return $row->orgUnits->first()->pivot->ami_rtp_isi ?? '<span class="text-muted fst-italic">-</span>';
            })
            ->addColumn('ptp', function ($row) {
                return $row->orgUnits->first()->pivot->ed_ptp_isi ?? '<span class="text-muted fst-italic">-</span>';
            })
            ->addColumn('te', function ($row) {
                return $row->orgUnits->first()->pivot->ami_te_isi ?? '<span class="text-muted fst-italic">Belum ditinjau</span>';
            })
            ->addColumn('action', function ($row) {
                $indOrgId = $row->orgUnits->first()->pivot->indikorgunit_id;

                return '<button type="button" class="btn btn-sm btn-info ajax-modal-btn"
                    data-url="' . route('pemutu.ami.te-edit', encryptId($indOrgId)) . '"
                    data-modal-title="Isi Tinjauan Efektivitas (TE)"
                    data-modal-size="modal-lg">
                    <i class="ti ti-check me-1"></i>Isi
                </button>';
            })
            ->rawColumns(['no', 'indikator_full', 'target', 'rtp', 'ptp', 'te', 'action'])
            ->make(true);
    }

    /**
     * Modal Form: Isi Tinjauan Efektivitas (TE).
     */
    public function editTe(IndikatorOrgUnit $indOrg)
    {
        return view('pages.pemutu.ami._te_form', compact('indOrg'));
    }

    /**
     * Simpan Tinjauan Efektivitas (TE).
     */
    public function updateTe(TeRequest $request, string $id)
    {
        // Add updateTe method to IndikatorService if not exists
        $indOrg = $this->indikatorOrgUnitService->findIndikatorOrgUnit($id);
        $indOrg->update(['ami_te_isi' => $request->validated()['ami_te_isi']]);

        return jsonSuccess('Tinjauan Efektivitas (TE) berhasil disimpan.');
    }

    /**
     * Export PTK (Penemuan Temuan dan Ketidaksesuaian) - DOCX
     */
    public function exportPtk(Request $request, PeriodeSpmi $periode)
    {
        $unitId   = $request->input('unit_id') ? decryptIdIfEncrypted($request->input('unit_id')) : null;
        $dokId    = $request->input('dok_id') ? decryptIdIfEncrypted($request->input('dok_id')) : null;
        $edStatus = $request->input('ed_status');

        return $this->AmiExportService->exportPtk($periode, $unitId, $dokId, $edStatus);
    }

    /**
     * Export Temuan Audit - Excel (KTS only)
     */
    public function exportTemuanAudit(Request $request, PeriodeSpmi $periode)
    {
        $unitId   = $request->input('unit_id') ? decryptIdIfEncrypted($request->input('unit_id')) : null;
        $dokId    = $request->input('dok_id') ? decryptIdIfEncrypted($request->input('dok_id')) : null;
        $edStatus = $request->input('ed_status');

        $export   = $this->AmiExportService->exportTemuanAudit($periode, $unitId, $dokId, $edStatus);
        $fileName = 'Temuan_Audit_KTS_' . date('Ymd_His') . '.xlsx';

        return Excel::download($export, $fileName);
    }

    /**
     * Export Temuan Positif - Excel (Terpenuhi & Terlampaui)
     */
    public function exportTemuanPositif(Request $request, PeriodeSpmi $periode)
    {
        $unitId   = $request->input('unit_id') ? decryptIdIfEncrypted($request->input('unit_id')) : null;
        $dokId    = $request->input('dok_id') ? decryptIdIfEncrypted($request->input('dok_id')) : null;
        $edStatus = $request->input('ed_status');

        $export   = $this->AmiExportService->exportTemuanPositif($periode, $unitId, $dokId, $edStatus);
        $fileName = 'Temuan_Positif_' . date('Ymd_His') . '.xlsx';

        return Excel::download($export, $fileName);
    }
}
