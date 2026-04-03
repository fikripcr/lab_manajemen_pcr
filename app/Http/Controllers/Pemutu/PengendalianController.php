<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PengendalianRequest;
use App\Http\Requests\Pemutu\RtmRequest;
use App\Http\Requests\Pemutu\UpdateMatrixRequest;
use App\Http\Requests\Pemutu\ValidasiPengendalianRequest;
use App\Models\Event\Rapat;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Hr\StrukturOrganisasiService;
use App\Services\Pemutu\DokumenService;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\IndikatorOrgUnitService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;

class PengendalianController extends Controller
{
    public function __construct(
        protected PeriodeSpmiService $periodeSpmiService,
        protected IndikatorService $indikatorService,
        protected IndikatorOrgUnitService $indikatorOrgUnitService,
        protected StrukturOrganisasiService $strukturOrganisasiService,
        protected DokumenService $dokumenService,
        protected \App\Services\Pemutu\PegawaiService $pegawaiService,
    ) {}

    /**
     * Daftar periode SPMI untuk Pengendalian.
     */
    public function index()
    {
        // Bypass old period selection — use global siklus from session
        $siklus = $this->periodeSpmiService->getSiklusData();

        // Active Kelompok (Akademik / Non Akademik) from session
        $activeKelompok = session('pemutu_active_kelompok', 'akademik');
        $periode        = $siklus[$activeKelompok] ?? null;

        $rapat = null;
        if ($periode) {
            // Load the latest RTM rapat
            $rapat = $periode->latest_rtm_pengendalian;
            if ($rapat) {
                $rapat->load(['agendas', 'pesertas.user', 'ketua_user', 'notulen_user', 'author_user']);
            }
        }

        $rootDoks = $this->dokumenService->getRootsByPeriode($siklus['tahun']);

        $data = [
            'pageTitle'      => 'Pengendalian',
            'siklus'         => $siklus,
            'activeKelompok' => $activeKelompok,
            'periode'        => $periode,
            'rapat'          => $rapat,
            'rootDoks'       => $rootDoks,
            'units'          => $this->strukturOrganisasiService->getHierarchicalList(),
            'users'          => $this->pegawaiService->getUsersWithPegawaiData(),
        ];

        return view('pages.pemutu.pengendalian.index', $data);
    }

    /**
     * DataTable AJAX: daftar Indikator untuk satu periode.
     */
    public function data(PeriodeSpmi $periode, Request $request)
    {
        $filters = parseSpmiFilters($request, ['unit_id', 'pengend_status', 'pengend_important_matrix', 'pengend_urgent_matrix', 'dok_id']);

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
            ->addColumn('analisis', function ($row) {
                return pemutuDtColAnalisisPengend($row);
            })
            ->addColumn('action', function ($row) use ($periode) {
                $pivot        = $row->orgUnits->first()?->pivot;
                $indikorgunit = $pivot?->indikorgunit_id;

                if (! $indikorgunit) {
                    return '<span class="text-muted small">-</span>';
                }

                $encId       = encryptId($indikorgunit);
                $urlIsi      = route('pemutu.pengendalian.edit-modal', $encId);
                $urlValidasi = route('pemutu.pengendalian.validasi-modal', $encId);
                $periodeInfo = pemutuPeriodeStatus($periode->pengendalian_awal, $periode->pengendalian_akhir);

                if ($periodeInfo['is_active']) {
                    return '<div class="d-flex flex-column gap-1">'
                        . '<button class="btn btn-sm btn-primary ajax-modal-btn" data-modal-size="modal-lg" data-url="' . $urlIsi . '"><i class="ti ti-pencil me-1"></i>Isi</button>'
                        . '<button class="btn btn-sm btn-outline-purple ajax-modal-btn" data-modal-size="modal-lg" data-url="' . $urlValidasi . '"><i class="ti ti-crown me-1"></i>Validasi</button>'
                        . '</div>';
                } else {
                    return '<div class="d-flex flex-column gap-1">'
                        . '<button class="btn btn-sm btn-outline-secondary ajax-modal-btn" data-modal-size="modal-lg" data-url="' . $urlIsi . '?readonly=1"><i class="ti ti-eye me-1"></i>Detail</button>'
                        . '<button class="btn btn-sm btn-outline-secondary ajax-modal-btn" data-modal-size="modal-lg" data-url="' . $urlValidasi . '?readonly=1"><i class="ti ti-eye me-1"></i>Detail Validasi</button>'
                        . '</div>';
                }
            })
            ->filterColumn('indikator', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('indikator', 'like', "%{$keyword}%")
                        ->orWhere('no_indikator', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['no', 'indikator_full', 'target', 'status_ami', 'status_pengend', 'eisenhower_matrix', 'analisis', 'action'])
            ->make(true);
    }

    /**
     * Modal isi pengendalian (Auditee/Unit).
     */
    public function editModal(IndikatorOrgUnit $indOrg)
    {
        $indOrg->load(['indikator.labels', 'orgUnit']);
        $hasilMap = IndikatorOrgUnit::$hasilAkhirLabels;

        return view('pages.pemutu.pengendalian.isi-modal', compact('indOrg', 'hasilMap'));
    }

    /**
     * Simpan data pengendalian dari unit/auditee.
     */
    public function update(PengendalianRequest $request, string $id)
    {
        $this->indikatorOrgUnitService->updatePivotData($id, $request->validated(), 'Submit pengendalian');

        return jsonSuccess('Data pengendalian berhasil disimpan.');
    }

    /**
     * Modal validasi pengendalian (Atasan/Pemutu).
     */
    public function validasiModal(IndikatorOrgUnit $indOrg)
    {
        $indOrg->load(['indikator.labels', 'orgUnit']);
        $hasilMap  = IndikatorOrgUnit::$hasilAkhirLabels;
        $statusMap = [
            'tetap'        => ['label' => 'Dipertahankan', 'color' => 'success'],
            'penyesuaian'  => ['label' => 'Disesuaikan', 'color' => 'warning'],
            'ditingkatkan' => ['label' => 'Ditingkatkan', 'color' => 'blue'],
            'nonaktif'     => ['label' => 'Di-nonaktifkan', 'color' => 'danger'],
        ];

        return view('pages.pemutu.pengendalian.validasi-modal', compact('indOrg', 'hasilMap', 'statusMap'));
    }

    /**
     * Simpan validasi atasan.
     */
    public function validasi(ValidasiPengendalianRequest $request, string $id)
    {
        $this->indikatorOrgUnitService->updatePivotData($id, $request->validated(), 'Validasi pengendalian atasan');

        return jsonSuccess('Validasi pengendalian berhasil disimpan.');
    }

    /**
     * Update hanya Eisenhower Matrix field (inline AJAX dari DataTable).
     */
    public function updateMatrix(UpdateMatrixRequest $request, string $id)
    {
        $this->indikatorOrgUnitService->updatePivotData($id, $request->only(['pengend_important_matrix', 'pengend_urgent_matrix']));

        return jsonSuccess('Matrix berhasil diperbarui.');
    }

    // ─── RTM Methods ──────────────────────────────────────────────

    public function createRtm(PeriodeSpmi $periode)
    {
        $users = $this->pegawaiService->getUsersWithPegawaiData();

        return view('pages.pemutu.pengendalian.rtm-form', compact('periode', 'users'));
    }

    /**
     * Store RTM baru (membuat Rapat + link via RapatEntitas + default agendas).
     */
    public function storeRtm(RtmRequest $request, PeriodeSpmi $periode)
    {
        $this->periodeSpmiService->createRtm($periode, 'Pengendalian', $request->validated());

        return jsonSuccess('RTM berhasil dibuat dengan agenda default.', route('pemutu.pengendalian.index'));
    }

    /**
     * Form AJAX modal untuk edit data umum RTM.
     */
    public function editRtm(PeriodeSpmi $periode, Rapat $rapat)
    {
        $users = $this->pegawaiService->getUsersWithPegawaiData();

        return view('pages.pemutu.pengendalian.rtm-form', compact('periode', 'rapat', 'users'));
    }

    /**
     * Update data umum RTM.
     */
    public function updateRtm(RtmRequest $request, PeriodeSpmi $periode, Rapat $rapat)
    {
        $this->periodeSpmiService->updateRtm($rapat, $request->validated());

        return jsonSuccess('Data RTM berhasil diperbarui.');
    }
}
