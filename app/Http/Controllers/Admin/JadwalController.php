<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JadwalRequest;
use App\Imports\JadwalImport;
use App\Models\JadwalKuliah;
use App\Models\Lab;
use App\Models\MataKuliah;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class JadwalController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:manage-jadwal']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.admin.jadwal.index');
    }

    /**
     * Process datatables ajax request.
     */
    public function paginate(Request $request)
    {
        $jadwals = JadwalKuliah::select([
            'jadwal_kuliah.jadwal_kuliah_id',
            'jadwal_kuliah.semester_id',
            'jadwal_kuliah.mata_kuliah_id',
            'jadwal_kuliah.dosen_id',
            'jadwal_kuliah.hari',
            'jadwal_kuliah.jam_mulai',
            'jadwal_kuliah.jam_selesai',
            'jadwal_kuliah.lab_id',
            'jadwal_kuliah.created_at',
            'jadwal_kuliah.updated_at',
            'jadwal_kuliah.deleted_at',
            'semesters.tahun_ajaran',
            'semesters.semester as semester_nama',
            'mata_kuliahs.kode_mk',
            'mata_kuliahs.nama_mk',
            'users.name as dosen_name',
            'labs.name as lab_name',
        ])->with(['semester', 'mataKuliah', 'dosen', 'lab'])
            ->leftJoin('semesters', 'jadwal_kuliah.semester_id', '=', 'semesters.semester_id')
            ->leftJoin('mata_kuliahs', 'jadwal_kuliah.mata_kuliah_id', '=', 'mata_kuliahs.mata_kuliah_id')
            ->leftJoin('users', 'jadwal_kuliah.dosen_id', '=', 'users.id')
            ->leftJoin('labs', 'jadwal_kuliah.lab_id', '=', 'labs.lab_id')
            ->whereNull('jadwal_kuliah.deleted_at');

        // Apply filters if present
        if ($request->filled('hari')) {
            $jadwals->where('jadwal_kuliah.hari', $request->hari);
        }

        if ($request->filled('dosen')) {
            $jadwals->where('users.name', 'like', '%' . $request->dosen . '%');
        }

        return DataTables::of($jadwals)
            ->addIndexColumn();
        return DataTables::of($jadwals)
            ->addIndexColumn()
            ->filter(function ($query) use ($request) {
                // Global search functionality
                if ($request->has('search') && $request->search['value'] != '') {
                    $searchValue = $request->search['value'];
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('jadwal_kuliah.hari', 'like', '%' . $searchValue . '%')
                            ->orWhere('mata_kuliahs.kode_mk', 'like', '%' . $searchValue . '%')
                            ->orWhere('mata_kuliahs.nama_mk', 'like', '%' . $searchValue . '%')
                            ->orWhere('users.name', 'like', '%' . $searchValue . '%')
                            ->orWhere('labs.name', 'like', '%' . $searchValue . '%')
                            ->orWhere('semesters.tahun_ajaran', 'like', '%' . $searchValue . '%');
                    });
                }
            })
            ->addColumn('tanggal', function ($jadwal) {
                return $jadwal->hari;
            })
            ->addColumn('waktu_mulai', function ($jadwal) {
                return date('H:i', strtotime($jadwal->jam_mulai));
            })
            ->addColumn('waktu_selesai', function ($jadwal) {
                return date('H:i', strtotime($jadwal->jam_selesai));
            })
            ->addColumn('mata_kuliah_nama', function ($jadwal) {
                if ($jadwal->mata_kuliah_id && $jadwal->mataKuliah) {
                    return '<span class="fw-medium">' . $jadwal->mataKuliah->kode_mk . '</span> - ' . $jadwal->mataKuliah->nama_mk;
                }
                return '-';
            })
            ->addColumn('dosen_nama', function ($jadwal) {
                return $jadwal->dosen ? $jadwal->dosen->name : '-';
            })
            ->addColumn('ruang_nama', function ($jadwal) {
                return $jadwal->lab ? $jadwal->lab->name : '-';
            })
            ->addColumn('semester_nama_display', function ($jadwal) {
                if ($jadwal->semester_id) {
                    return $jadwal->tahun_ajaran . ' - ' . ($jadwal->semester_nama == 1 ? 'Ganjil' : 'Genap');
                }
                return '-';
            })
            ->addColumn('action', function ($jadwal) {
                $encryptedId = $jadwal->encrypted_jadwal_kuliah_id;
                return view('components.sys.datatables-actions', [
                    'editUrl'   => route('jadwal.edit', $encryptedId),
                    'viewUrl'   => route('jadwal.show', $encryptedId),
                    'deleteUrl' => route('jadwal.destroy', $encryptedId),
                ])->render();
            })
            ->rawColumns(['mata_kuliah_nama', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $semesters   = Semester::all();
        $mataKuliahs = MataKuliah::all();
        $dosens      = User::whereHas('roles', function ($query) {
            $query->where('name', 'dosen');
        })->get();
        $labs = Lab::all();

        return view('pages.admin.jadwal.create', compact('semesters', 'mataKuliahs', 'dosens', 'labs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JadwalRequest $request)
    {
        \DB::beginTransaction();
        try {
            JadwalKuliah::create($request->validated());

            \DB::commit();

            return redirect()->route('jadwal.index')
                ->with('success', 'Jadwal berhasil dibuat.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat jadwal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $realId = decryptId($id);

        $jadwal = JadwalKuliah::with(['semester', 'mataKuliah', 'dosen', 'lab'])->findOrFail($realId);
        return view('pages.admin.jadwal.show', compact('jadwal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId = decryptId($id);

        $jadwal      = JadwalKuliah::findOrFail($realId);
        $semesters   = Semester::all();
        $mataKuliahs = MataKuliah::all();
        $dosens      = User::whereHas('roles', function ($query) {
            $query->where('name', 'dosen');
        })->get();
        $labs = Lab::all();

        return view('pages.admin.jadwal.edit', compact('jadwal', 'semesters', 'mataKuliahs', 'dosens', 'labs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JadwalRequest $request, $id)
    {
        $realId = decryptId($id);
        $jadwal = JadwalKuliah::findOrFail($realId);

        \DB::beginTransaction();
        try {
            $jadwal->update($request->validated());

            \DB::commit();

            return redirect()->route('jadwal.index')
                ->with('success', 'Jadwal berhasil diperbarui.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui jadwal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $realId = decryptId($id);
        $jadwal = JadwalKuliah::findOrFail($realId);

        // Check if jadwal is used in any PC assignments or logs
        if ($jadwal->pcAssignments->count() > 0 || $jadwal->logPenggunaanPcs->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete jadwal that is associated with PC assignments or usage logs.');
        }

        $jadwal->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dihapus.',
            ]);
        }

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Show import form
     */
    public function showImport()
    {
        return view('pages.admin.jadwal.import');
    }

    /**
     * Import jadwal from Excel file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        try {
            Excel::import(new JadwalImport, $request->file('file'));

            return redirect()->route('jadwal.index')
                ->with('success', 'Jadwal berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengimpor jadwal: ' . $e->getMessage())
                ->withInput();
        }
    }
}
