<?php

namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\AmiRequest;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Pemutu\AmiService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;

class AmiController extends Controller
{
    public function __construct(
        protected AmiService $AmiService,
        protected PeriodeSpmiService $PeriodeSpmiService,
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
        $unitId = $request->input('unit_id') ? (int) $request->input('unit_id') : null;
        $query  = $this->AmiService->getIndikatorQuery($periode, $unitId);

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('indikator_info', function ($row) {
                $kode   = $row->no_indikator ?? '-';
                $nama   = $row->indikator ?? '-';
                $labels = $row->labels->map(fn ($l) => '<span class="badge bg-' . ($l->color ?? 'secondary') . '-lt text-' . ($l->color ?? 'secondary') . '">' . e($l->name) . '</span>')->implode(' ');

                return '<div>
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
                    ? '<a href="' . route('pemutu.ami.detail', encryptId($indikorgunit)) . '" class="btn btn-sm btn-primary"><i class="ti ti-zoom-scan me-1"></i>Isi AMI</a>'
                    : '<span class="text-muted small">-</span>';
            })
            ->filterColumn('indikator_info', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('indikator', 'like', "%{$keyword}%")
                      ->orWhere('no_indikator', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['indikator_info', 'status_ed', 'status_ami', 'action'])
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
}
