<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SysTestNotification;
use App\Services\Sys\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class NotificationsController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        return view('pages.sys.notifications.index');
    }

    /**
     * Process datatables ajax request.
     */
    public function paginate(Request $request)
    {
        $filters = [
            'read_status' => $request->get('read_status'),
            'type'        => $request->get('type'),
            'date_from'   => $request->get('date_from'),
            'date_to'     => $request->get('date_to'),
        ];

        $query = $this->notificationService->getFilteredQueryForUser($filters);

        return DataTables::of($query)
            ->addColumn('checkbox', function ($notification) {
                return '<div class="form-check">
                            <input class="form-check-input notification-checkbox" type="checkbox" value="' . $notification->id . '" id="checkbox_' . $notification->id . '">
                            <label class="form-check-label" for="checkbox_' . $notification->id . '"></label>
                        </div>';
            })
            ->addColumn('status', function ($notification) {
                if (is_null($notification->read_at)) {
                    return '<span class="badge bg-label-primary">Unread</span>';
                } else {
                    return '<span class="badge bg-label-success">Read</span>';
                }
            })
            ->addColumn('title', function ($notification) {
                return $notification->data['title'] ?? 'Notification';
            })
            ->addColumn('body', function ($notification) {
                return Str::limit($notification->data['body'] ?? 'New notification', 50);
            })
            ->addColumn('created_at', function ($notification) {
                return formatTanggalIndo($notification->created_at);
            })
            ->addColumn('action', function ($notification) {
                if (is_null($notification->read_at)) {
                    return '
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-icon btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('notifications.mark-as-read', $notification->id) . '">
                                    <i class="bx bx-check me-1"></i> Mark as Read
                                </a>
                            </div>
                        </div>';
                } else {
                    return '<span class="text-muted">-</span>';
                }
            })
            ->rawColumns(['checkbox', 'status', 'action'])
            ->make(true);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $result = $this->notificationService->markAsReadById($id);

        if (! $result) {
            return redirect()->back()->with('error', 'Notifikasi tidak ditemukan.');
        }

        logActivity('notification', 'Notification marked as read');

        return redirect()->back()->with('success', 'Notifikasi telah ditandai sebagai telah dibaca.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $userId       = auth()->id();
        $count        = Auth::user()->unreadNotifications->count();
        $updatedCount = $this->notificationService->markAllAsReadForUser($userId);

        logActivity('notification', $updatedCount . ' notifications marked as read by user: ' . auth()->user()->name . ' (ID: ' . auth()->id() . ')');

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi (' . $updatedCount . ') telah ditandai sebagai telah dibaca!',
        ]);
    }

    /**
     * Mark selected notifications as read.
     */
    public function markSelectedAsRead(Request $request)
    {
        $selectedIds = $request->input('ids', []);

        if (empty($selectedIds)) {
            return apiResponse(null, 'Tidak ada notifikasi yang dipilih.', 400, 'error');
        }

        $userId       = auth()->id();
        $updatedCount = $this->notificationService->markSelectedAsRead($selectedIds, $userId);

        logActivity('notification', $updatedCount . ' selected notifications marked as read by user: ' . auth()->user()->name . ' (ID: ' . auth()->id() . ')');

        return apiSuccess(['updated_count' => $updatedCount], $updatedCount . ' notifikasi telah ditandai sebagai telah dibaca.');
    }

    /**
     * Get user's unread notifications count (for AJAX requests).
     */
    public function getUnreadCount()
    {
        $userId = auth()->id();
        $count  = $this->notificationService->getUnreadCount($userId);

        return apiSuccess(['count' => $count]);
    }

    /**
     * Get notifications data for dropdown (API endpoint)
     */
    public function getDropdownData(Request $request)
    {
        $limit  = $request->get('limit', 5);
        $userId = auth()->id();

        $notifications = $this->notificationService->getDropdownData($userId, $limit);

        return apiSuccess(['data' => $notifications]);
    }

    /**
     * Get notification counts (total, unread, read)
     */
    public function getNotificationCounts()
    {
        $userId = auth()->id();
        $counts = $this->notificationService->getNotificationCounts($userId);

        return apiSuccess(['counts' => $counts]);
    }

    /**
     * Update notification (currently not implemented)
     */
    public function update(Request $request, $id = null)
    {
        //
    }

    /**
     * Send notification (unified function for email/database)
     */
    public function send(Request $request)
    {
        $request->validate([
            'type'               => 'required|in:database,email',
            // user_id is optional when sending to authenticated user
            'user_id'            => 'nullable',
            'notification_class' => 'required|string', // Notification class name
        ]);

        $type              = $request->input('type');
        $userId            = $request->input('user_id');
        $notificationClass = $request->input('notification_class');

        // Determine recipient - if no user_id provided, use authenticated user
        if ($userId) {
            // Try to decrypt the user ID first
            $decryptedId = null;
            try {
                $decryptedId = decryptId($userId, false);
            } catch (\Exception $e) {
                // If decryption fails, we'll try to find by the original value
                $decryptedId = null;
            }

            if ($decryptedId !== null) {
                $recipient = User::findOrFail($decryptedId);
            } else {
                $recipient = User::findOrFail($userId);
            }
        } else {
            // Use authenticated user if no user_id provided
            $recipient = auth()->user();
        }

        // Get the notification class - for now using TestNotification
        if ($notificationClass === 'SysTestNotification') {
            $channel      = $type === 'email' ? 'mail' : 'database';
            $notification = new \App\Notifications\SysTestNotification($channel);
        } else {
            // Handle other notification classes as needed
            $notification = new $notificationClass();
        }

        try {
            if ($type === 'email') {
                // Verify the recipient has an email address
                if (empty($recipient->email)) {
                    return apiError('Recipient does not have an email address.', 400);
                }

                $recipient->notify($notification);
                $message = 'Email notification sent successfully to ' . $recipient->email;
            } else {
                $recipient->notify($notification);
                $message = 'Database notification sent successfully to ' . $recipient->name;
            }

            // Log the notification sending
            logActivity('notification', "Notification ({$type}) sent to user: " . $recipient->name . ' (ID: ' . $recipient->id . ') by user: ' . auth()->user()->name . ' (ID: ' . auth()->id() . ')');

            return apiSuccess(null, $message);
        } catch (\Exception $e) {
            \Log::error('Notification sending failed: ' . $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine());
            return apiError('Failed to send notification: ' . $e->getMessage(), 500);
        }
    }
}
