<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\AmiRequest;
use App\Http\Requests\Pemutu\RtpRequest;
use App\Http\Requests\Pemutu\TeRequest;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Pemutu\AmiService;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AmiController extends Controller
{
    public function __construct(
        protected AmiService $AmiService,
        protected PeriodeSpmiService $PeriodeSpmiService,
        protected IndikatorService $IndikatorService,
    ) {}

    /**
     * Daftar periode SPMI untuk AMI.
     */
    public function index()
    {
        $periodes = $this->PeriodeSpmiService->getPeriodes();

        return view('pages.pemutu.ami.index', compact('periodes'));
    }

    /**
     * Daftar indikator dalam satu periode (list page, bukan modal).
     */
    public function show(PeriodeSpmi $periode, Request $request)
    {
        $unitId = $request->input('unit_id');

        return view('pages.pemutu.ami.show', compact('periode', 'unitId'));
    }

    /**
     * DataTable AJAX: daftar Indikator untuk satu periode yang sudah isi ED.
     * Mengikuti pola ED: query dari Indikator, akses data pivot via orgUnits->first()->pivot.
     */
    public function data(PeriodeSpmi $periode, Request $request)
    {
        $unitId = $request->input('unit_id') ? decryptIdIfEncrypted($request->input('unit_id')) : null;
        $query  = $this->AmiService->getIndikatorQuery($periode, $unitId);

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('indikator_info', function ($row) {
                $kode   = $row->no_indikator ?? '-';
                $nama   = $row->indikator ?? '-';
                $labels = $row->labels->map(fn($l) => '<span class="badge bg-' . ($l->color ?? 'secondary') . '-lt text-' . ($l->color ?? 'secondary') . '">' . e($l->name) . '</span>')->implode(' ');

                return '<div class="indicator-scroll">
                    <div class="fw-bold text-primary">' . e($kode) . '</div>
                    <div class="text-wrap">' . e($nama) . '</div>
                    <div class="mt-1">' . $labels . '</div>
                </div>';
            })
            ->addColumn('status_ed', function ($row) {
                $pivot = $row->orgUnits->first()?->pivot;
                if ($pivot?->ed_capaian) {
                    $skalaLabel = $pivot->ed_skala !== null
                        ? '<span class="badge bg-blue-lt text-blue ms-1">Skala ' . $pivot->ed_skala . '</span>'
                        : '';

                    return '<span class="badge bg-success-lt text-success"><i class="ti ti-check me-1"></i>ED Diisi</span>' . $skalaLabel;
                }

                return '<span class="badge bg-secondary-lt text-secondary">Belum Diisi</span>';
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

                return '<span class="badge bg-warning-lt text-warning"><i class="ti ti-clock me-1"></i>Belum Dinilai</span>';
            })
            ->addColumn('action', function ($row) {
                $pivot        = $row->orgUnits->first()?->pivot;
                $indikorgunit = $pivot?->indikorgunit_id;

                return $indikorgunit
                    ? '<a href="' . route('pemutu.ami.detail', encryptId($indikorgunit)) . '" class="btn btn-sm btn-primary"><i class="ti ti-zoom-scan me-1"></i>Isi</a>'
                    : '<span class="text-muted small">-</span>';
            })
            ->addColumn('rtp', function ($row) {
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
            ->rawColumns(['indikator_info', 'status_ed', 'status_ami', 'action', 'rtp'])
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

        // Ambil indikator KTS dari periode tahun lalu
        $query = $this->IndikatorService->getByOrgUnit(null, [
            'kelompok_indikator' => $prevPeriod->jenis_periode,
            'tahun_dokumen'      => $prevPeriod->periode,
            'ami_hasil_akhir'    => 0, // KTS
        ]);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('indikator_info', function ($row) {
                return '<strong>' . ($row->no_indikator ?? '-') . '</strong><br>' . $row->indikator;
            })
            ->addColumn('rtp', function ($row) {
                return $row->orgUnits->first()->pivot->ami_rtp_isi ?? '<span class="text-muted fst-italic">Tidak ada RTP</span>';
            })
            ->addColumn('ptp', function ($row) {
                return $row->orgUnits->first()->pivot->ed_ptp_isi ?? '<span class="text-muted fst-italic">Belum diisi</span>';
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
            ->rawColumns(['indikator_info', 'rtp', 'ptp', 'te', 'action'])
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
}
