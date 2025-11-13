<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $jadwals = Jadwal::with(['semester', 'mataKuliah', 'dosen', 'lab'])->select('*');

        return DataTables::of($jadwals)
            ->addIndexColumn()
            ->editColumn('semester', function ($jadwal) {
                if ($jadwal->semester) {
                    return $jadwal->semester->tahun_ajaran . ' - ' . ($jadwal->semester->semester == 1 ? 'Ganjil' : 'Genap');
                }
                return '-';
            })
            ->editColumn('mata_kuliah', function ($jadwal) {
                if ($jadwal->mataKuliah) {
                    return $jadwal->mataKuliah->kode_mk . ' - ' . $jadwal->mataKuliah->nama_mk;
                }
                return '-';
            })
            ->editColumn('dosen', function ($jadwal) {
                if ($jadwal->dosen) {
                    return $jadwal->dosen->name;
                }
                return '-';
            })
            ->editColumn('lab', function ($jadwal) {
                if ($jadwal->lab) {
                    return $jadwal->lab->name;
                }
                return '-';
            })
            ->addColumn('action', function ($jadwal) {
                return '
                    <div class="d-flex">
                        <a href="' . route('jadwal.show', $jadwal->id) . '" class="btn btn-info btn-sm me-1" title="View">
                            <i class="bx bx-show"></i>
                        </a>
                        <a href="' . route('jadwal.edit', $jadwal->id) . '" class="btn btn-primary btn-sm me-1" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <form action="' . route('jadwal.destroy', $jadwal->id) . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirmDelete(this.form.action, \'Hapus Jadwal?\', \'Apakah Anda yakin ingin menghapus jadwal ini?\')">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
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
    public function store(Request $request)
    {
        $request->validate([
            'semester_id' => 'required|exists:semesters,semester_id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:users,id',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'lab_id' => 'required|exists:labs,lab_id',
        ]);

        Jadwal::create($request->all());

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal created successfully.');
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
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'semester_id' => 'required|exists:semesters,semester_id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:users,id',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'lab_id' => 'required|exists:labs,lab_id',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal updated successfully.');
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
