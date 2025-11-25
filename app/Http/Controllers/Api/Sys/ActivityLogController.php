<?php

namespace App\Http\Controllers\Api\Sys;

use App\Http\Controllers\Controller;
use App\Services\Sys\ActivityLogsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends Controller
{
    protected $activityLogsService;

    public function __construct(ActivityLogsService $activityLogsService)
    {
        $this->activityLogsService = $activityLogsService;
    }

    /**
     * Get paginated list of activity logs
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $filters = $this->activityLogsService->buildFiltersFromRequest($request);
            $activities = $this->activityLogsService->getActivitiesList($filters);

            return apiSuccess([
                'data' => $activities->items(),
                'links' => [
                    'first' => $activities->url(1),
                    'last' => $activities->url($activities->lastPage()),
                    'prev' => $activities->previousPageUrl(),
                    'next' => $activities->nextPageUrl(),
                ],
                'meta' => [
                    'current_page' => $activities->currentPage(),
                    'from' => $activities->firstItem(),
                    'last_page' => $activities->lastPage(),
                    'path' => $activities->path(),
                    'per_page' => $activities->perPage(),
                    'to' => $activities->lastItem(),
                    'total' => $activities->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return apiError('Failed to retrieve activity logs: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get a specific activity log by ID
     */
    public function detail($id): JsonResponse
    {
        try {
            $activity = $this->activityLogsService->getActivityById($id);

            if (!$activity) {
                return apiError('Activity log not found', 404);
            }

            return apiSuccess([
                'activity' => $activity
            ]);
        } catch (\Exception $e) {
            return apiError('Failed to retrieve activity: ' . $e->getMessage(), 500);
        }
    }
}
