<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Services\Sys\ActivityLogsService;
use Exception;
use Illuminate\Http\Request;
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
    public function index()
    {
        return view('pages.sys.activity-log.index');
    }

    /**
     * Process datatables ajax request for activity logs.
     */
    public function paginate(Request $request)
    {
        try {
            // Build filters from request
            $filters = $this->activityLogService->buildFiltersFromRequest($request);

            // Use the service to get the filtered query
            $activities = $this->activityLogService->getFilteredQuery($filters);

            return DataTables::of($activities)
                ->addIndexColumn()
                ->order(function ($query) {
                    $query->latest('sys_activity_log.created_at'); // Sort by created_at DESC by default
                })
                ->editColumn('created_at', function ($activity) {
                    return formatTanggalIndo($activity->created_at);
                })
                ->editColumn('causer_name', function ($activity) {
                    return $activity->causer_name ?? 'System';
                })
                ->editColumn('description', function ($activity) {
                    $description = htmlspecialchars($activity->description, ENT_QUOTES, 'UTF-8');
                    return '<span title="' . $description . '">' . substr($description, 0, 100) . (strlen($description) > 100 ? '...' : '') . '</span>';
                })
                ->addColumn('action', function ($activity) {
                    return '
                        <div class="d-flex align-items-center">
                            <a class="text-success me-2" href="#" data-bs-toggle="modal" data-bs-target="#activityDetailModal" data-activity-id="' . $activity->id . '" title="View Details">
                                <i class="bx bx-show"></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['description', 'action'])
                ->make(true);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
        //
    }
}
