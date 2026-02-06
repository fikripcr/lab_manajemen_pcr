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

    public function paginate(Request $request)
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
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('tahun_ajaran', 'like', '%' . $searchValue . '%')
                            ->orWhere('semester', 'like', '%' . $searchValue . '%')
                            ->orWhereRaw("CASE WHEN is_active = 1 THEN 'Aktif' ELSE 'Tidak Aktif' END LIKE ?", ['%' . $searchValue . '%']);
                    });
                }
            })
            ->editColumn('is_active', function ($semester) {
                return $semester->is_active
                    ? '<span class="badge bg-label-success">Aktif</span>'
                    : '<span class="badge bg-label-secondary">Tidak Aktif</span>';
            })
            ->editColumn('start_date', function ($semester) {
                return $semester->start_date ? formatTanggalIndo($semester->start_date) : '-';
            })
            ->editColumn('end_date', function ($semester) {
                return $semester->end_date ? formatTanggalIndo($semester->end_date) : '-';
            })
            ->addColumn('action', function ($semester) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => 'javascript:void(0)',
                    'editClass' => 'edit-semester',
                    'editData'  => ['id' => $semester->encrypted_semester_id],
                    'viewUrl'   => route('semesters.show', $semester->encrypted_semester_id),
                    'deleteUrl' => route('semesters.destroy', $semester->encrypted_semester_id),
                ])->render();
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
        $realId   = decryptId($id);
        $semester = Semester::findOrFail($realId);
        return view('pages.admin.semesters.show', compact('semester'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId   = decryptId($id);
        $semester = Semester::findOrFail($realId);
        return view('pages.admin.semesters.edit', compact('semester'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SemesterRequest $request, $id)
    {
        $realId   = decryptId($id);
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
        $realId   = decryptId($id);
        $semester = Semester::findOrFail($realId);
        return view('pages.admin.semesters.edit-ajax', compact('semester'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $realId   = decryptId($id);
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
