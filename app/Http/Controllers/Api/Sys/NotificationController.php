<?php

namespace App\Http\Controllers\Api\Sys;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get count of unread notifications for the authenticated user
     */
    public function getCount()
    {
        $count = $this->notificationService->getUnreadCount();
        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * Get list of notifications for the authenticated user
     */
    public function getList(Request $request)
    {
        $filters = [
            'read_status' => $request->get('read_status'),
            'per_page' => $request->get('per_page', 10)
        ];

        $notifications = $this->notificationService->getNotificationList($filters);

        return response()->json([
            'data' => NotificationResource::collection($notifications->items()),
            'links' => [
                'first' => $notifications->url(1),
                'last' => $notifications->url($notifications->lastPage()),
                'prev' => $notifications->previousPageUrl(),
                'next' => $notifications->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'from' => $notifications->firstItem(),
                'last_page' => $notifications->lastPage(),
                'path' => $notifications->path(),
                'per_page' => $notifications->perPage(),
                'to' => $notifications->lastItem(),
                'total' => $notifications->total(),
            ],
        ]);
    }
}
