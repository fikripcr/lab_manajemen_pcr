<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\SemesterRequest;
use App\Models\Lab\Semester;
use App\Services\Lab\SemesterService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SemesterController extends Controller
{
    public function __construct(protected SemesterService $semesterService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.lab.semesters.index');
    }

    public function paginate(Request $request)
    {
        $semesters = $this->semesterService->getFilteredQuery($request->all());

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
                    'editUrl'   => route('lab.semesters.edit', $semester->encrypted_semester_id),
                    'editModal' => true,
                    'viewUrl'   => route('lab.semesters.show', $semester->encrypted_semester_id),
                    'deleteUrl' => route('lab.semesters.destroy', $semester->encrypted_semester_id),
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
        $semester = new Semester();
        return view('pages.lab.semesters.create-edit-ajax', compact('semester'));
    }

    /**
     * Show the form for creating a new semester via modal.
     */
    public function createModal()
    {
        $semester = new Semester();
        return view('pages.lab.semesters.create-edit-ajax', compact('semester'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SemesterRequest $request)
    {
        try {
            $this->semesterService->createSemester($request->validated());
            return jsonSuccess('Semester berhasil dibuat.', route('lab.semesters.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal membuat semester: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Semester $semester)
    {
        return view('pages.lab.semesters.show', compact('semester'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Semester $semester)
    {
        return view('pages.lab.semesters.create-edit-ajax', compact('semester'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SemesterRequest $request, Semester $semester)
    {
        try {
            $this->semesterService->updateSemester($semester, $request->validated());
            return jsonSuccess('Semester berhasil diperbarui.', route('lab.semesters.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui semester: ' . $e->getMessage());
        }
    }

    public function destroy(Semester $semester)
    {
        try {
            $this->semesterService->deleteSemester($semester);
            return jsonSuccess('Semester deleted successfully.', route('lab.semesters.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus semester: ' . $e->getMessage());
        }
    }
}
