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
                $encryptedId = encryptId($semester->semester_id);
                return '
                    <div class="d-flex align-items-center">
                        <a class="btn btn-sm btn-icon btn-outline-primary me-1 edit-semester" href="javascript:void(0)" data-id="' . $encryptedId . '" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-icon btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('semesters.show',$encryptedId) . '">
                                    <i class="bx bx-show me-1"></i> View
                                </a>
                                <a href="javascript:void(0)" class="dropdown-item text-danger" onclick="confirmDelete(\'' . route('semesters.destroy',$encryptedId) . '\')">
                                    <i class="bx bx-trash me-1"></i> Delete
                                </a>
                            </div>
                        </div>
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
     * Show the form for creating a new semester via modal.
     */
    public function createModal()
    {
        return view('pages.admin.semesters.create-ajax');
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
                ->with('success', 'Semester berhasil dibuat.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat semester: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $realId = decryptId($id);
        $semester = Semester::findOrFail($realId);
        return view('pages.admin.semesters.show', compact('semester'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId = decryptId($id);
        $semester = Semester::findOrFail($realId);
        return view('pages.admin.semesters.edit', compact('semester'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SemesterRequest $request, $id)
    {
        $realId = decryptId($id);
        $semester = Semester::findOrFail($realId);

        \DB::beginTransaction();
        try {
            $semester->update($request->validated());

            \DB::commit();

            return redirect()->route('semesters.index')
                ->with('success', 'Semester berhasil diperbarui.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui semester: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource via modal.
     */
    public function editModal($id)
    {
        $realId = decryptId($id);
        $semester = Semester::findOrFail($realId);
        return view('pages.admin.semesters.edit-ajax', compact('semester'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $realId = decryptId($id);
        $semester = Semester::findOrFail($realId);

        // Check if semester is used in any schedule
        if ($semester->jadwals->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete semester that is associated with schedules.');
        }

        $semester->delete();

        return redirect()->route('semesters.index')
            ->with('success', 'Semester deleted successfully.');
    }
}
