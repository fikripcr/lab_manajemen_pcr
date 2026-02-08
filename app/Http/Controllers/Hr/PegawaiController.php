<?php
namespace App\Http\Controllers\Hr;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\PegawaiRequest;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PegawaiService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PegawaiController extends Controller
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->pegawaiService->getFilteredQuery($request);
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('components.tabler.datatables-actions', [
                        'viewUrl'    => route('hr.pegawai.show', $row->pegawai_id),
                        'editUrl'    => route('hr.pegawai.edit', $row->pegawai_id),
                        'deleteUrl'  => route('hr.pegawai.destroy', $row->pegawai_id),
                        'deleteName' => $row->nama, // Optional, if component supports it or we need it for JS
                    ])->render();
                })
                ->addColumn('nama_lengkap', function ($row) {
                    return $row->nama;
                })
                ->addColumn('posisi', function ($row) {
                    return $row->latestDataDiri->posisi->posisi ?? '-';
                })
                ->addColumn('unit', function ($row) {
                    return $row->latestDataDiri->departemen->departemen ?? ($row->latestDataDiri->prodi->nama_prodi ?? '-');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.hr.data-diri.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $posisi     = \App\Models\Hr\Posisi::where('is_active', 1)->get();
        $departemen = \App\Models\Hr\Departemen::where('is_active', 1)->get();
        $prodi      = \App\Models\Hr\Prodi::all(); // Assuming no is_active column or just all

        return view('pages.hr.pegawai.create', compact('posisi', 'departemen', 'prodi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(PegawaiRequest $request)
    {
        try {
            $this->pegawaiService->createPegawai($request->validated());
            return jsonSuccess('Pegawai berhasil ditambahkan', route('hr.pegawai.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pegawai = \App\Models\Hr\Pegawai::with([
            'latestDataDiri', 'historyDataDiri.approval',
            'keluarga.approval',
            'riwayatPendidikan.approval',
            'pengembanganDiri.approval',
            'latestStatusPegawai.statusPegawai',
            'latestJabatanFungsional.jabatanFungsional',
            'latestJabatanStruktural.jabatanStruktural',
            // Load History for Tables in 'Kepegawaian' tab
            'historyStatPegawai.statusPegawai',
            'historyStatAktifitas.statusAktifitas',
            'historyJabFungsional.jabatanFungsional',
            'historyJabStruktural.jabatanStruktural',
        ])->findOrFail($id);

        // Prepare pending changes if any
        $pendingChange = $pegawai->historyDataDiri
            ->where('latest_riwayatapproval_id', '!=', null)
            ->where('approval.status', 'Pending')
            ->first();

        // If generic tabs (Pendidikan, Keluarga) have pending items, we might want to flag them too
        // But for now, the main alert is for Data Diri.
        // We will handle specific alerts inside the tabs (already done in view).

        return view('pages.hr.pegawai.show', compact('pegawai', 'pendingChange'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pegawai = Pegawai::with('latestDataDiri')->findOrFail($id);

        $posisi     = \DB::table('hr_posisi')->select('posisi_id', 'posisi')->get();
        $departemen = \DB::table('hr_departemen')->select('departemen_id', 'departemen')->get();
        $prodi      = \DB::table('hr_prodi')->select('prodi_id', 'nama_prodi')->get();

        return view('pages.hr.pegawai.edit', compact('pegawai', 'posisi', 'departemen', 'prodi'));
    }

    /**
     * Update the specified resource in storage.
     * This creates a NEW history record + Pending Approval.
     */
    public function update(PegawaiRequest $request, $id)
    {
        try {
            $pegawai = Pegawai::findOrFail($id);
            // Request Change Logic
            $this->pegawaiService->requestDataDiriChange($pegawai, $request->validated());

            return jsonSuccess('Permintaan perubahan berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->pegawaiService->delete($id);
            return GlobalHelper::jsonSuccess('Pegawai berhasil dihapus');
        } catch (\Exception $e) {
            return GlobalHelper::jsonError($e->getMessage());
        }
    }
}
