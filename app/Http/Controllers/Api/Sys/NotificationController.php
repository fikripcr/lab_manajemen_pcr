<?php
namespace App\Http\Controllers\Api\Sys;

use App\Http\Controllers\Controller;
use App\Services\Sys\NotificationService;
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
        $count = $this->notificationService->getUnreadCount(auth()->id());
        return jsonSuccess(['data' => ['count' => $count]]);
    }

    /**
     * Get list of notifications for the authenticated user
     */
    public function getList(Request $request)
    {
        $limit  = $request->get('limit', 10);
        $userId = auth()->id();

        $notifications = $this->notificationService->getDropdownData($userId, $limit);

        return jsonSuccess(['data' => $notifications]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $userId       = auth()->id();
        $updatedCount = $this->notificationService->markAllAsReadForUser($userId);

        return jsonSuccess([
            'message' => 'All notifications marked as read',
            'data'    => ['updated_count' => $updatedCount],
        ]);
    }
}
