<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SemesterController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:manage-semesters']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.admin.semesters.index');
    }

    public function data(Request $request)
    {
        $semesters = Semester::all();
        return DataTables::of($semesters)
            ->addIndexColumn()
            ->editColumn('is_active', function ($semester) {
                return $semester->is_active
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-secondary">Tidak Aktif</span>';
            })
            ->editColumn('start_date', function ($semester) {
                return $semester->start_date ? date('d M Y', strtotime($semester->start_date)) : '-';
            })
            ->editColumn('end_date', function ($semester) {
                return $semester->end_date ? date('d M Y', strtotime($semester->end_date)) : '-';
            })
            ->addColumn('action', function ($semester) {
                return '
                    <div class="d-flex">
                        <a href="' . route('semesters.show', $semester->semester_id) . '" class="btn btn-info btn-sm me-1" title="View">
                            <i class="bx bx-show"></i>
                        </a>
                        <a href="' . route('semesters.edit', $semester->semester_id) . '" class="btn btn-primary btn-sm me-1" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <form action="' . route('semesters.destroy', $semester->semester_id) . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirmDelete(this.form.action, \'Hapus Semester?\', \'Apakah Anda yakin ingin menghapus semester ini?\')">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
                    </div>';
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.semesters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|in:1,2',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        Semester::create($request->all());

        return redirect()->route('semesters.index')
            ->with('success', 'Semester created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $semester = Semester::findOrFail($id);
        return view('pages.admin.semesters.show', compact('semester'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $semester = Semester::findOrFail($id);
        return view('pages.admin.semesters.edit', compact('semester'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|in:1,2',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $semester = Semester::findOrFail($id);
        $semester->update($request->all());

        return redirect()->route('semesters.index')
            ->with('success', 'Semester updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);

        // Check if semester is used in any schedule
        if ($semester->jadwals->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete semester that is associated with schedules.');
        }

        $semester->delete();

        return redirect()->route('semesters.index')
            ->with('success', 'Semester deleted successfully.');
    }
}
