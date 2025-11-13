<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SemesterRequest;
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
        $semesters = Semester::query();

        // Apply filters if present
        if ($request->filled('status')) {
            if ($request->status === 'Aktif') {
                $semesters->where('is_active', 1);
            } elseif ($request->status === 'Tidak Aktif') {
                $semesters->where('is_active', 0);
            }
        }

        return DataTables::of($semesters)
            ->addIndexColumn()
            ->filter(function ($query) use ($request) {
                // Global search functionality
                if ($request->has('search') && $request->search['value'] != '') {
                    $searchValue = $request->search['value'];
                    $query->where(function($q) use ($searchValue) {
                        $q->where('tahun_ajaran', 'like', '%' . $searchValue . '%')
                          ->orWhere('semester', 'like', '%' . $searchValue . '%')
                          ->orWhereRaw("CASE WHEN is_active = 1 THEN 'Aktif' ELSE 'Tidak Aktif' END LIKE ?", ['%' . $searchValue . '%']);
                    });
                }
            })
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
    public function store(SemesterRequest $request)
    {
        \DB::beginTransaction();
        try {
            Semester::create($request->validated());

            \DB::commit();

            return redirect()->route('semesters.index')
                ->with('success', 'Semester created successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to create semester: ' . $e->getMessage())
                ->withInput();
        }
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
    public function update(SemesterRequest $request, $id)
    {
        $semester = Semester::findOrFail($id);

        \DB::beginTransaction();
        try {
            $semester->update($request->validated());

            \DB::commit();

            return redirect()->route('semesters.index')
                ->with('success', 'Semester updated successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to update semester: ' . $e->getMessage())
                ->withInput();
        }
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