<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\JadwalKuliah;
use App\Models\Lab\PcAssignment;
use App\Models\User;
use App\Services\Lab\PcAssignmentService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PcAssignmentController extends Controller
{
    protected $PcAssignmentService;

    public function __construct(PcAssignmentService $PcAssignmentService)
    {
        $this->PcAssignmentService = $PcAssignmentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index($jadwalId)
    {
        $realJadwalId = decryptId($jadwalId);
        $jadwal       = JadwalKuliah::with(['mataKuliah', 'dosen', 'lab'])->findOrFail($realJadwalId);

        return view('pages.lab.pc-assignments.index', compact('jadwal'));
    }

    /**
     * Get data for DataTables
     */
    public function data($jadwalId)
    {
        $realJadwalId = decryptId($jadwalId);

        $assignments = $this->PcAssignmentService->getAssignmentsByJadwalQuery($realJadwalId);

        return DataTables::of($assignments)
            ->addIndexColumn()
            ->addColumn('mahasiswa_nama', function ($assignment) {
                return $assignment->user ? $assignment->user->name : '-';
            })
            ->addColumn('mahasiswa_npm', function ($assignment) {
                return $assignment->user ? $assignment->user->username : '-'; // Asumsi username = NPM
            })
            ->editColumn('is_active', function ($assignment) {
                return $assignment->is_active ?
                '<span class="badge bg-label-success">Active</span>' :
                '<span class="badge bg-label-secondary">Inactive</span>';
            })
            ->addColumn('action', function ($row) use ($jadwalId) {
                return view('pages.lab.pc-assignments._action', compact('row', 'jadwalId'))->render();
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($jadwalId)
    {
        $realJadwalId = decryptId($jadwalId);
        $jadwal       = JadwalKuliah::with(['lab'])->findOrFail($realJadwalId);

        // Ambil mahasiswa (User dengan role mahasiswa)
        $mahasiswas = User::whereHas('roles', function ($q) {
            $q->where('name', 'mahasiswa');
        })->orderBy('name')->get();

        // Generate list nomor PC yang tersedia (1 s/d misal 40)
        // Harusnya cek kapasitas Lab. Anggap default 40 check lab capacity nanti.
        $totalPc = 40;

        // Cek assignment yang sudah ada
        $assignedPcs = PcAssignment::where('jadwal_id', $realJadwalId)
            ->where('is_active', true)
            ->pluck('nomor_pc')
            ->toArray();

        return view('pages.lab.pc-assignments.create', compact('jadwal', 'mahasiswas', 'totalPc', 'assignedPcs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $jadwalId)
    {
        $realJadwalId = decryptId($jadwalId);
        $jadwal       = JadwalKuliah::findOrFail($realJadwalId);

        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'nomor_pc'    => 'required|integer|min:1',
            'nomor_loker' => 'nullable|integer',
        ]);

        try {
            $this->PcAssignmentService->createAssignment($realJadwalId, $jadwal->lab_id, $request->all());

            return jsonSuccess('Assignment berhasil dibuat.', route('lab.jadwal.assignments.index', $jadwalId));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500); // 422 if validation error logic in service
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($jadwalId, $id)
    {
        try {
            $realId = decryptId($id);
            $this->PcAssignmentService->deleteAssignment($realId);

            return jsonSuccess('Assignment berhasil dihapus.', route('lab.jadwal.assignments.index', $jadwalId));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
