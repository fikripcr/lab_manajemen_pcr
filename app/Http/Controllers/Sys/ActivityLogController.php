<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:view activity logs']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.sys.activity-log.index');
    }

    /**
     * Process datatables ajax request for activity logs.
     */
    public function paginate(Request $request)
    {
        $activities = Activity::with(['causer', 'subject'])
            ->select('sys_activity_log.*')
            ->leftJoin('users', 'sys_activity_log.causer_id', '=', 'users.id')
            ->orderBy('sys_activity_log.created_at', 'desc');

        if ($request->filled('log_name')) {
            $activities->where('sys_activity_log.log_name', $request->log_name);
        }

        if ($request->filled('subject_type')) {
            $activities->where('sys_activity_log.subject_type', $request->subject_type);
        }

        if ($request->filled('event')) {
            $activities->where('sys_activity_log.event', $request->event);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $activities->whereBetween('sys_activity_log.created_at', [$request->date_from, $request->date_to]);
        }

        return DataTables::of($activities)
            ->addIndexColumn()
            ->order(function ($query) {
                $query->latest('sys_activity_log.created_at'); // Sort by created_at DESC by default
            })
            ->filterColumn('description', function ($query, $keyword) {
                $query->where('sys_activity_log.description', 'like', "%{$keyword}%");
            })
            ->filterColumn('causer.name', function ($query, $keyword) {
                $query->whereHas('causer', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('log_name', function ($query, $keyword) {
                $query->where('sys_activity_log.log_name', 'like', "%{$keyword}%");
            })
            ->filterColumn('event', function ($query, $keyword) {
                $query->where('sys_activity_log.event', 'like', "%{$keyword}%");
            })
            ->editColumn('created_at', function ($activity) {
                return formatTanggalIndo($activity->created_at);
            })
            ->editColumn('causer.name', function ($activity) {
                if ($activity->causer) {
                    return $activity->causer->name;
                }
                return 'System';
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
            ->filter(function ($query) use ($request) {
                // Global search functionality
                if ($request->has('search') && $request->search['value'] != '') {
                    $searchValue = $request->search['value'];
                    $query->where(function($q) use ($searchValue) {
                        $q->where('sys_activity_log.description', 'like', '%' . $searchValue . '%')
                          ->orWhere('sys_activity_log.log_name', 'like', '%' . $searchValue . '%')
                          ->orWhere('sys_activity_log.event', 'like', '%' . $searchValue . '%')
                          ->orWhereHas('causer', function($q) use ($searchValue) {
                              $q->where('name', 'like', '%' . $searchValue . '%');
                          });
                    });
                }
            })
            ->rawColumns(['description', 'action'])
            ->make(true);
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
        $activity = Activity::with(['causer', 'subject'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'activity' => $activity,
            'properties' => json_decode($activity->properties, true) ?: []
        ]);
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
