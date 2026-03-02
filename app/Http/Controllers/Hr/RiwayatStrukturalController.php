<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\EndStrukturalRequest;
use App\Http\Requests\Hr\MassStrukturalRequest;
use App\Http\Requests\Hr\RiwayatStrukturalRequest;
use App\Models\Hr\OrgUnit;
use App\Models\Hr\Pegawai;
use App\Models\Hr\RiwayatJabStruktural;
use App\Services\Hr\RiwayatStrukturalService;

class RiwayatStrukturalController extends Controller
{
    public function __construct(protected RiwayatStrukturalService $strukturalService)
    {}

    public function index(Pegawai $pegawai = null)
    {
        if ($pegawai) {
            return view('pages.hr.data-diri.tabs.struktural', compact('pegawai'));
        }
        // Global (non-pegawai) view: show full list with DataTable
        return view('pages.hr.data-diri.tabs.struktural');
    }

    public function create(Pegawai $pegawai)
    {
        $units = OrgUnit::whereIn('type', ['jabatan_struktural', 'departemen', 'prodi'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $currentStruktural = $pegawai->latestStruktural;
        $struktural        = new RiwayatJabStruktural();

        return view('pages.hr.pegawai.struktural.create-edit-ajax', compact('pegawai', 'units', 'currentStruktural', 'struktural'));
    }

    public function store(RiwayatStrukturalRequest $request, Pegawai $pegawai)
    {
        $this->strukturalService->requestAddition($pegawai, $request->validated());
        return jsonSuccess('Pengajuan Struktural berhasil dikirim. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) . '#section-struktural');
    }

    public function edit(Pegawai $pegawai, RiwayatJabStruktural $struktural)
    {
        $units = OrgUnit::whereIn('type', ['jabatan_struktural', 'departemen', 'prodi'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.hr.pegawai.struktural.create-edit-ajax', compact('pegawai', 'struktural', 'units'));
    }

    public function update(RiwayatStrukturalRequest $request, Pegawai $pegawai, RiwayatJabStruktural $struktural)
    {
        $this->strukturalService->requestChange($pegawai, $request->validated(), $struktural);
        return jsonSuccess('Pengajuan Perubahan Struktural berhasil dikirim. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) . '#section-struktural');
    }

    public function destroy(Pegawai $pegawai, RiwayatJabStruktural $struktural)
    {
        $this->strukturalService->deleteStruktural($pegawai, $struktural);
        return jsonSuccess('Struktural berhasil dihapus.');
    }

    // End current assignment
    public function endAssignment(EndStrukturalRequest $request, Pegawai $pegawai, RiwayatJabStruktural $struktural)
    {
        $this->strukturalService->endStruktural($struktural, $request->validated()['tgl_akhir']);
        return jsonSuccess('Struktural berhasil diakhiri.');
    }

    // Mass Struktural Page
    public function massIndex()
    {
        $units = OrgUnit::with('children')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.hr.pegawai.mass-struktural', compact('units'));
    }

    // Mass Struktural Detail (AJAX)
    public function massDetail($unitId)
    {
        $unit = \App\Models\Shared\StrukturOrganisasi::findOrFail($unitId);

        $assignments = RiwayatJabStruktural::with('pegawai')
            ->where('org_unit_id', $unitId)
            ->orderByDesc('tgl_awal')
            ->get();

        return view('pages.hr.pegawai._mass_struktural_detail', compact('unit', 'assignments'));
    }

    // Mass Assign (AJAX)
    public function massAssign(MassStrukturalRequest $request)
    {
        $pegawai = Pegawai::findOrFail($request->pegawai_id);
        $this->strukturalService->addStruktural($pegawai, $request->validated());

        return jsonSuccess('Struktural berhasil ditambahkan.');
    }

    public function data(\Illuminate\Http\Request $request)
    {
        $query = RiwayatJabStruktural::with(['pegawai', 'orgUnit'])->select('hr_riwayat_jabstruktural.*');

        if ($request->has('pegawai_id')) {
            $query->where('pegawai_id', decryptIdIfEncrypted($request->pegawai_id));
        }

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('unit_struktural', function ($row) {
                $unitName = $row->orgUnit->name ?? '-';
                $unitType = ucfirst(str_replace('_', ' ', $row->orgUnit->type ?? ''));
                return '<div><strong>' . $unitName . '</strong></div><div class="text-muted small">' . $unitType . '</div>';
            })
            ->editColumn('tgl_awal', function ($row) {
                return $row->tgl_awal ? $row->tgl_awal->format('d M Y') : '-';
            })
            ->editColumn('tgl_akhir', function ($row) {
                return $row->tgl_akhir ? $row->tgl_akhir->format('d M Y') : '-';
            })
            ->addColumn('status', function ($row) {
                $isActive = is_null($row->tgl_akhir) || $row->tgl_akhir->isFuture();
                if ($isActive) {
                    return '<span class="status status-success"><span class="status-dot status-dot-animated"></span> Aktif</span>';
                }
                return '<span class="status status-secondary"><span class="status-dot"></span> Selesai</span>';
            })
            ->addColumn('action', function ($row) {
                $pegawaiId    = encryptId($row->pegawai_id);
                $strukturalId = $row->encrypted_riwayatjabstruktural_id;

                $editBtn = '<button type="button" class="btn btn-sm btn-icon btn-ghost-primary ajax-modal-btn"
                                data-url="' . route('hr.pegawai.struktural.edit', [$pegawaiId, $strukturalId]) . '"
                                data-modal-title="Edit Struktural" title="Edit">
                                <i class="ti ti-edit"></i>
                            </button>';

                $deleteBtn = '<button type="button" class="btn btn-sm btn-icon btn-ghost-danger ajax-delete"
                                data-url="' . route('hr.pegawai.struktural.destroy', [$pegawaiId, $strukturalId]) . '"
                                title="Hapus">
                                <i class="ti ti-trash"></i>
                            </button>';

                return '<div class="btn-list justify-content-end">' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['unit_struktural', 'status', 'action'])
            ->make(true);
    }
}
