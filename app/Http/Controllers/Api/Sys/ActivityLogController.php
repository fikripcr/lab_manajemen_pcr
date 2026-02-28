<?php
namespace App\Http\Controllers\Api\Sys;

use App\Http\Controllers\Controller;
use App\Services\Sys\ActivityLogsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct(protected ActivityLogsService $activityLogsService)
    {}

    /**
     * Get paginated list of activity logs
     */
    public function list(Request $request): JsonResponse
    {
        $filters    = $this->activityLogsService->buildFiltersFromRequest($request);
        $activities = $this->activityLogsService->getActivitiesList($filters);

        return jsonSuccess('Data retrieved', null, [
            'data'  => $activities->items(),
            'links' => [
                'first' => $activities->url(1),
                'last'  => $activities->url($activities->lastPage()),
                'prev'  => $activities->previousPageUrl(),
                'next'  => $activities->nextPageUrl(),
            ],
            'meta'  => [
                'current_page' => $activities->currentPage(),
                'from'         => $activities->firstItem(),
                'last_page'    => $activities->lastPage(),
                'path'         => $activities->path(),
                'per_page'     => $activities->perPage(),
                'to'           => $activities->lastItem(),
                'total'        => $activities->total(),
            ],
        ]);
    }

    /**
     * Get a specific activity log by ID
     */
    public function detail($id): JsonResponse
    {
        $activity = $this->activityLogsService->getActivityById($id);

        if (! $activity) {
            return jsonError('Activity log not found', 404);
        }

        return jsonSuccess('Data retrieved', null, [
            'activity' => $activity,
        ]);
    }
}
