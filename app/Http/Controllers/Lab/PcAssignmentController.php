<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\PcAssignmentRequest;
use App\Models\Lab\JadwalKuliah;
use App\Models\Lab\PcAssignment;
use App\Models\User;
use App\Services\Lab\PcAssignmentService;
use Yajra\DataTables\DataTables;

class PcAssignmentController extends Controller
{
    public function __construct(protected PcAssignmentService $pcAssignmentService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index(JadwalKuliah $jadwal)
    {
        $jadwal->load(['mataKuliah', 'dosen', 'lab']);

        return view('pages.lab.pc-assignments.index', compact('jadwal'));
    }

    /**
     * Get data for DataTables
     */
    public function data(JadwalKuliah $jadwal)
    {
        $assignments = $this->pcAssignmentService->getAssignmentsByJadwalQuery($jadwal->jadwal_kuliah_id);

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
    public function create(JadwalKuliah $jadwal)
    {
        $jadwal->load(['lab']);
        $mahasiswas = User::whereHas('roles', function ($q) {
            $q->where('name', 'mahasiswa');
        })->orderBy('name')->get();

        $totalPc     = 40;
        $assignedPcs = PcAssignment::where('jadwal_id', $jadwal->jadwal_kuliah_id)
            ->where('is_active', true)
            ->pluck('nomor_pc')
            ->toArray();

        $assignment = new PcAssignment();
        return view('pages.lab.pc-assignments.create-edit-ajax', compact('jadwal', 'mahasiswas', 'totalPc', 'assignedPcs', 'assignment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PcAssignmentRequest $request, JadwalKuliah $jadwal)
    {
        try {
            $this->pcAssignmentService->createAssignment($jadwal, $request->validated());
            return jsonSuccess('Assignment berhasil dibuat.', route('lab.jadwal.assignments.index', $jadwal->encrypted_jadwal_kuliah_id));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal membuat assignment: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalKuliah $jadwal, PcAssignment $assignment)
    {
        try {
            $this->pcAssignmentService->deleteAssignment($assignment);
            return jsonSuccess('Assignment berhasil dihapus.', route('lab.jadwal.assignments.index', $jadwal->encrypted_jadwal_kuliah_id));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus assignment: ' . $e->getMessage());
        }
    }
}
