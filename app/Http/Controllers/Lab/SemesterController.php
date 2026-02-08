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
    protected $semesterService;

    public function __construct(SemesterService $semesterService)
    {
        $this->semesterService = $semesterService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
                    'editUrl'   => route('lab.semesters.edit-modal.show', $semester->encrypted_semester_id),
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
        return view('pages.lab.semesters.create');
    }

    /**
     * Show the form for creating a new semester via modal.
     */
    public function createModal()
    {
        return view('pages.lab.semesters.create-ajax');
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
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $realId   = decryptId($id);
        $semester = $this->semesterService->getSemesterById($realId);

        if (! $semester) {
            abort(404);
        }

        return view('pages.lab.semesters.show', compact('semester'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId   = decryptId($id);
        $semester = $this->semesterService->getSemesterById($realId);

        if (! $semester) {
            abort(404);
        }

        return view('pages.lab.semesters.edit', compact('semester'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SemesterRequest $request, $id)
    {
        $realId = decryptId($id);

        try {
            $this->semesterService->updateSemester($realId, $request->validated());

            return jsonSuccess('Semester berhasil diperbarui.', route('lab.semesters.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource via modal.
     */
    public function editModal($id)
    {
        $realId   = decryptId($id);
        $semester = $this->semesterService->getSemesterById($realId);

        if (! $semester) {
            return response()->json(['error' => 'Semester not found'], 404);
        }

        return view('pages.admin.semesters.edit-ajax', compact('semester'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $realId = decryptId($id);
            $this->semesterService->deleteSemester($realId);

            return jsonSuccess('Semester deleted successfully.', route('lab.semesters.index'));

        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
