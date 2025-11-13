<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JadwalRequest;
use App\Models\Jadwal;
use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\User;
use App\Models\Lab;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JadwalImport;
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
    public function data(Request $request)
    {
        $jadwals = Jadwal::select('jadwals.*')
            ->leftJoin('semesters', 'jadwals.semester_id', '=', 'semesters.semester_id')
            ->leftJoin('mata_kuliahs', 'jadwals.mata_kuliah_id', '=', 'mata_kuliahs.id')
            ->leftJoin('users', 'jadwals.dosen_id', '=', 'users.id')
            ->leftJoin('labs', 'jadwals.lab_id', '=', 'labs.lab_id');

        // Apply filters if present
        if ($request->filled('hari')) {
            $jadwals->where('jadwals.hari', $request->hari);
        }

        if ($request->filled('dosen')) {
            $jadwals->where('users.name', 'like', '%' . $request->dosen . '%');
        }

        return DataTables::of($jadwals)
            ->addIndexColumn()
            ->filter(function ($query) use ($request) {
                // Global search functionality
                if ($request->has('search') && $request->search['value'] != '') {
                    $searchValue = $request->search['value'];
                    $query->where(function($q) use ($searchValue) {
                        $q->where('jadwals.hari', 'like', '%' . $searchValue . '%')
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
                return $jadwal->jam_mulai ? $jadwal->jam_mulai->format('H:i') : '-';
            })
            ->addColumn('waktu_selesai', function ($jadwal) {
                return $jadwal->jam_selesai ? $jadwal->jam_selesai->format('H:i') : '-';
            })
            ->addColumn('mata_kuliah.nama', function ($jadwal) {
                if ($jadwal->mata_kuliah_id && $jadwal->mataKuliah) {
                    return $jadwal->mataKuliah->kode_mk . ' - ' . $jadwal->mataKuliah->nama_mk;
                }
                return '-';
            })
            ->addColumn('dosen.nama', function ($jadwal) {
                if ($jadwal->dosen_id && $jadwal->dosen) {
                    return $jadwal->dosen->name;
                }
                return '-';
            })
            ->addColumn('ruang', function ($jadwal) {
                if ($jadwal->lab_id && $jadwal->lab) {
                    return $jadwal->lab->name;
                }
                return '-';
            })
            ->addColumn('semester.tahun_ajaran', function ($jadwal) {
                if ($jadwal->semester_id && $jadwal->semester) {
                    return $jadwal->semester->tahun_ajaran . ' - ' . ($jadwal->semester->semester == 1 ? 'Ganjil' : 'Genap');
                }
                return '-';
            })
            ->addColumn('action', function ($jadwal) {
                return '
                    <div class="d-flex align-items-center">
                        <a class="text-success me-2" href="' . route('jadwal.edit', $jadwal->id) . '" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-icon btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('jadwal.show', $jadwal->id) . '">
                                    <i class="bx bx-show me-1"></i> View
                                </a>
                                <form action="' . route('jadwal.destroy', $jadwal->id) . '" method="POST" class="d-inline">
                                    ' . csrf_field() . '
                                    ' . method_field('DELETE') . '
                                    <button type="submit" class="dropdown-item text-danger" title="Delete" onclick="return confirmDelete(this.form.action, \'Hapus Jadwal?\', \'Apakah Anda yakin ingin menghapus jadwal ini?\')">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $semesters = Semester::all();
        $mataKuliahs = MataKuliah::all();
        $dosens = User::whereHas('roles', function($query) {
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
            Jadwal::create($request->validated());

            \DB::commit();

            return redirect()->route('jadwal.index')
                ->with('success', 'Jadwal created successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to create jadwal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jadwal = Jadwal::with(['semester', 'mataKuliah', 'dosen', 'lab'])->findOrFail($id);
        return view('pages.admin.jadwal.show', compact('jadwal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $semesters = Semester::all();
        $mataKuliahs = MataKuliah::all();
        $dosens = User::whereHas('roles', function($query) {
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
        $jadwal = Jadwal::findOrFail($id);

        \DB::beginTransaction();
        try {
            $jadwal->update($request->validated());

            \DB::commit();

            return redirect()->route('jadwal.index')
                ->with('success', 'Jadwal updated successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to update jadwal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        // Check if jadwal is used in any PC assignments or logs
        if ($jadwal->pcAssignments->count() > 0 || $jadwal->logPenggunaanPcs->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete jadwal that is associated with PC assignments or usage logs.');
        }

        $jadwal->delete();

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal deleted successfully.');
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
                ->with('success', 'Jadwal imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error importing jadwal: ' . $e->getMessage())
                ->withInput();
        }
    }
}
