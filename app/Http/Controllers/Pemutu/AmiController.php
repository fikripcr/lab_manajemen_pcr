<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\AmiRequest;
use App\Http\Requests\Pemutu\RtpRequest;
use App\Http\Requests\Pemutu\TeRequest;
use App\Models\Hr\StrukturOrganisasi;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Hr\StrukturOrganisasiService;
use App\Services\Pemutu\AmiService;
use App\Services\Pemutu\AmiExportService;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class AmiController extends Controller
{
    public function __construct(
        protected AmiService $AmiService,
        protected AmiExportService $AmiExportService,
        protected PeriodeSpmiService $PeriodeSpmiService,
        protected IndikatorService $IndikatorService,
        protected StrukturOrganisasiService $StrukturOrganisasiService,
    ) {}

    /**
     * Daftar periode SPMI untuk AMI.
     */
    public function index()
    {
        // Bypass old period selection — use global siklus from session
        $siklus = $this->PeriodeSpmiService->getSiklusData();

        // Fetch root documents for filter
        $rootDoks = \App\Models\Pemutu\Dokumen::whereNull('parent_id')
            ->where('periode', $siklus['tahun'])
            ->orderBy('seq')
            ->get();

        $data = [
            'pageTitle' => 'Audit Mutu Internal (AMI)',
            'siklus'    => $siklus,
            'units'     => $this->StrukturOrganisasiService->getHierarchicalList(),
            'rootDoks'  => $rootDoks,
        ];

        return view('pages.pemutu.ami.index', $data);
    }


    /**
     * DataTable AJAX: daftar Indikator untuk satu periode yang sudah isi ED.
     * Mengikuti pola ED: query dari Indikator, akses data pivot via orgUnits->first()->pivot.
     */
    public function data(PeriodeSpmi $periode, Request $request)
    {
        $unitId  = $request->input('unit_id') ? decryptIdIfEncrypted($request->input('unit_id')) : null;
        
        // SIMPLE LOGIC: If not 'all', add to filters
        $filters = [];
        foreach ($request->only(['ami_hasil_akhir', 'ed_status', 'dok_id', 'rtp_status']) as $key => $value) {
            if ($value !== null && $value !== '') {
                $filters[$key] = $value;
            }
        }
        
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
            ->addColumn('status_ed', function ($row) {
                return pemutuDtColStatusEd($row);
            })
            ->addColumn('status_ami', function ($row) {
                return pemutuDtColStatusAmi($row);
            })
            ->addColumn('action', function ($row) {
                $pivot        = $row->orgUnits->first()?->pivot;
                $indikorgunit = $pivot?->indikorgunit_id;

                return $indikorgunit
                    ? '<a href="' . route('pemutu.ami.detail', encryptId($indikorgunit)) . '" class="btn btn-sm btn-primary"><i class="ti ti-zoom-scan me-1"></i>Isi</a>'
                    : '<span class="text-muted small">-</span>';
            })
            ->addColumn('rtp_isi', function ($row) {
                return $row->orgUnits->first()?->pivot->ami_rtp_isi ?? '<span class="text-muted small">-</span>';
            })
            ->addColumn('rtp_tgl', function ($row) {
                $tgl = $row->orgUnits->first()?->pivot->ami_rtp_tgl_pelaksanaan;
                return $tgl ? formatTanggalIndo($tgl) : '<span class="text-muted small">-</span>';
            })
            ->addColumn('auditor_recom', function ($row) {
                return $row->orgUnits->first()?->pivot->ami_hasil_temuan_rekom ?? '<span class="text-muted small">-</span>';
            })
            ->addColumn('action_rtp', function ($row) {
                $pivot = $row->orgUnits->first()?->pivot;

                // Hanya muncul jika hasil AMI adalah KTS (0)
                if ($pivot?->ami_hasil_akhir === 0) {
                    $indikorgunit = $pivot->indikorgunit_id;
                    $hasRtp       = ! empty($pivot->ami_rtp_isi);
                    $btnClass     = $hasRtp ? 'btn-outline-warning' : 'btn-warning';
                    $icon         = $hasRtp ? 'ti-edit' : 'ti-plus';

                    return '<button class="btn btn-sm ' . $btnClass . ' btn-rtp ajax-modal-btn"
                        data-url="' . route('pemutu.ami.rtp-edit', encryptId($indikorgunit)) . '"
                        data-title="Isi Rencana Tindakan Perbaikan (RTP)">
                        <i class="ti ' . $icon . ' me-1"></i>Isi
                    </button>';
                }

                return '<span class="text-muted small">-</span>';
            })
            ->filterColumn('indikator_info', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('indikator', 'like', "%{$keyword}%")
                        ->orWhere('no_indikator', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['no', 'indikator_full', 'target', 'status_ed', 'status_ami', 'action', 'rtp_isi', 'rtp_tgl', 'auditor_recom', 'action_rtp'])
            ->make(true);
    }

    /**
     * Halaman detail AMI (non-modal).
     */
    public function detail(IndikatorOrgUnit $indOrg)
    {
        $data = $this->AmiService->getDetail($indOrg);

        return view('pages.pemutu.ami.detail', $data);
    }

    /**
     * Submit penilaian AMI.
     */
    public function submitNilai(AmiRequest $request, IndikatorOrgUnit $indOrg)
    {
        $this->AmiService->submitPenilaian($indOrg, $request->validated());

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
    public function updateRtp(RtpRequest $request, IndikatorOrgUnit $indOrg)
    {
        $this->AmiService->updateRtp($indOrg, $request->validated());

        return jsonSuccess('Rencana Tindakan Perbaikan (RTP) berhasil disimpan.');
    }

    /**
     * Data untuk tab Tinjauan Efektivitas (KTS Tahun Lalu).
     */
    public function teData(Request $request, PeriodeSpmi $periode)
    {
        // Cari periode tahun lalu dengan jenis yang sama
        $prevYear   = (int) $periode->periode - 1;
        $prevPeriod = PeriodeSpmi::where('periode', $prevYear)
            ->where('jenis_periode', $periode->jenis_periode)
            ->first();

        if (! $prevPeriod) {
            return DataTables::of(collect([]))->make(true);
        }

        $unitId  = $request->input('unit_id') ? decryptIdIfEncrypted($request->input('unit_id')) : null;
        
        // Ambil indikator KTS dari periode tahun lalu
        $query = $this->IndikatorService->getUnifiedSpmiQuery($prevPeriod, $unitId, [
            'ami_hasil_akhir' => 0, // KTS
            'dok_id'          => $request->input('dok_id'),
            'te_status'       => $request->input('te_status'),
        ]);

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
    public function updateTe(TeRequest $request, IndikatorOrgUnit $indOrg)
    {
        $this->AmiService->updateTe($indOrg, $request->validated());

        return jsonSuccess('Tinjauan Efektivitas (TE) berhasil disimpan.');
    }

    /**
     * Export PTK (Penemuan Temuan dan Ketidaksesuaian) - DOCX
     */
    public function exportPtk(Request $request, PeriodeSpmi $periode)
    {
        $unitId = $request->input('unit_id') ? decryptIdIfEncrypted($request->input('unit_id')) : null;
        $dokId = $request->input('dok_id') ? decryptIdIfEncrypted($request->input('dok_id')) : null;
        $edStatus = $request->input('ed_status');

        return $this->AmiExportService->exportPtk($periode, $unitId, $dokId, $edStatus);
    }

    /**
     * Export Temuan Audit - Excel (KTS only)
     */
    public function exportTemuanAudit(Request $request, PeriodeSpmi $periode)
    {
        $unitId = $request->input('unit_id') ? decryptIdIfEncrypted($request->input('unit_id')) : null;
        $dokId = $request->input('dok_id') ? decryptIdIfEncrypted($request->input('dok_id')) : null;
        $edStatus = $request->input('ed_status');

        $export = $this->AmiExportService->exportTemuanAudit($periode, $unitId, $dokId, $edStatus);
        $fileName = 'Temuan_Audit_KTS_' . date('Ymd_His') . '.xlsx';

        return Excel::download($export, $fileName);
    }

    /**
     * Export Temuan Positif - Excel (Terpenuhi & Terlampaui)
     */
    public function exportTemuanPositif(Request $request, PeriodeSpmi $periode)
    {
        $unitId = $request->input('unit_id') ? decryptIdIfEncrypted($request->input('unit_id')) : null;
        $dokId = $request->input('dok_id') ? decryptIdIfEncrypted($request->input('dok_id')) : null;
        $edStatus = $request->input('ed_status');

        $export = $this->AmiExportService->exportTemuanPositif($periode, $unitId, $dokId, $edStatus);
        $fileName = 'Temuan_Positif_' . date('Ymd_His') . '.xlsx';

        return Excel::download($export, $fileName);
    }
}
