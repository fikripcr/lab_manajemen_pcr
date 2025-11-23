<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogsService;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;

class ActivityLogController extends Controller
{
    public function __construct(
        protected ActivityLogsService $activityLogService
    ) {
        // $this->middleware(['permission:view activity logs']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            return view('pages.sys.activity-log.index');
        } catch (\Exception $e) {
            \Log::error('Error in ActivityLogController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat halaman log aktivitas.');
        }
    }

    /**
     * Process datatables ajax request for activity logs.
     */
    public function paginate(Request $request)
    {
        try {
            $filters = $this->activityLogService->buildFiltersFromRequest($request);
            $query   = $this->activityLogService->getFilteredQuery();

            return DataTables::of($query)
                ->addIndexColumn()
                ->order(function ($query) {
                    $query->latest('created_at');
                })
                ->editColumn('created_at', function ($activity) {
                    return formatTanggalIndo($activity->created_at);
                })
                ->editColumn('causer.name', function ($activity) {
                    return $activity->causer_name ?? 'System';
                })
                ->editColumn('description', function ($activity) {
                    $desc = htmlspecialchars($activity->description, ENT_QUOTES, 'UTF-8');
                    return '<span title="' . $desc . '">' .
                    substr($desc, 0, 100) . (strlen($desc) > 100 ? '...' : '') .
                        '</span>';
                })
                ->addColumn('action', function ($activity) {
                    return '
                        <div class="d-flex align-items-center">
                            <a class="text-success me-2" href="#"
                               data-bs-toggle="modal"
                               data-bs-target="#activityDetailModal"
                               data-activity-id="' . $activity->id . '"
                               title="View Details">
                                <i class="bx bx-show"></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['description', 'action'])
                ->make(true);

        } catch (\Exception $e) {
            logError($e, 'error', $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data log aktivitas.',
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $activity = Activity::findOrFail($id);
            $activity->delete();

            return apiSuccess([], 'Log aktivitas berhasil dihapus.', 200);

        } catch (\Exception $e) {
            return apiError($e->getMessage(), $e->getCode());
        }
    }
}
