<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sys\NotificationSendRequest;
use App\Models\User;
use App\Notifications\SysTestNotification;
use App\Services\Sys\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class NotificationsController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {}
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
    public function data(Request $request)
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
                    return view('components.tabler.datatables-actions', [
                        'customActions' => [
                            [
                                'label' => 'Mark as Read',
                                'url'   => route('notifications.mark-as-read', $notification->id),
                                'icon'  => 'check',
                                'class' => '',
                            ],
                        ],
                    ])->render();
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
            return redirect()->back()->with('error', 'Notifikasi tidakditemukan . ');
        }

        logActivity('notification', 'Notification marked as read');

        return redirect()->back()->with('success', 'Notifikasi telahditandaisebagaitelahdibaca . ');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $userId       = auth()->id();
        $count        = Auth::user()->unreadNotifications->count();
        $updatedCount = $this->notificationService->markAllAsReadForUser($userId);

        logActivity('notification', $updatedCount . 'notifications marked as read by user: ' . auth()->user()->name . '(ID: ' . auth()->id() . ')');

        return jsonSuccess('Semua notifikasi (' . $updatedCount . ') telah ditandai sebagai telah dibaca!');
    }

    /**
     * Mark selected notifications as read.
     */
    public function markSelectedAsRead(Request $request)
    {
        $selectedIds = $request->input('ids', []);

        if (empty($selectedIds)) {
            return jsonError('Tidak ada notifikasi yang dipilih.', 400);
        }

        $userId       = auth()->id();
        $updatedCount = $this->notificationService->markSelectedAsRead($selectedIds, $userId);

        logActivity('notification', $updatedCount . 'selected notifications marked as read by user: ' . auth()->user()->name . '(ID: ' . auth()->id() . ')');

        return jsonSuccess([
            'message' => $updatedCount . ' notifikasi telah ditandai sebagai telah dibaca.',
            'data'    => ['updated_count' => $updatedCount],
        ]);
    }

    /**
     * Get user's unreadnotificationscount(for AJAX requests) .
     */
    public function getUnreadCount()
    {
        $userId = auth()->id();
        $count  = $this->notificationService->getUnreadCount($userId);

        return jsonSuccess(['data' => ['count' => $count]]);
    }

    /**
     * Get notifications data for dropdown (API endpoint)
     */
    public function getDropdownData(Request $request)
    {
        $limit  = $request->get('limit', 5);
        $userId = auth()->id();

        $notifications = $this->notificationService->getDropdownData($userId, $limit);

        return jsonSuccess(['data' => $notifications]);
    }

    /**
     * Get notification counts (total, unread, read)
     */
    public function getNotificationCounts()
    {
        $userId = auth()->id();
        $counts = $this->notificationService->getNotificationCounts($userId);

        return jsonSuccess(['data' => ['counts' => $counts]]);
    }

    /**
     * Update notification (currently not implemented)
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Send notification (unified function for email/database)
     */
    public function send(NotificationSendRequest $request)
    {
        $validated = $request->validated();

        $type              = $validated['type'];
        $userId            = $validated['user_id'];
        $notificationClass = $validated['notification_class'];

        // Determine recipient - if no user_id provided, use authenticated user
        if ($userId) {
            // Handle both User model (from route binding) and encrypted ID string
            if ($userId instanceof User) {
                $recipient = $userId;
            } else {
                $decryptedId = decryptIdIfEncrypted($userId);
                $recipient   = User::findOrFail($decryptedId);
            }
        } else {
            // Use authenticated user if no user_id provided
            $recipient = auth()->user();
        }

        // Get the notification class - for now using TestNotification
        if ($notificationClass === 'SysTestNotification') {
            $channel      = $type === 'email' ? 'mail' : 'database';
            $notification = new SysTestNotification($channel);
        } else {
            // Handle other notification classes as needed
            $notification = new $notificationClass();
        }

        if ($type === 'email') {
            // Verify the recipient has an email address
            if (empty($recipient->email)) {
                return jsonError('Recipient does not have an email address.', 400);
            }

            $recipient->notify($notification);
            $message = 'Email notification sent successfully to ' . $recipient->email;
        } else {
            $recipient->notify($notification);
            $message = 'Database notification sent successfully to ' . $recipient->name;
        }

        // Log the notification sending
        logActivity('notification', "Notification ({$type}) sent to user: " . $recipient->name . ' (ID: ' . $recipient->id . ') by user: ' . auth()->user()->name . ' (ID: ' . auth()->id() . ')');

        return jsonSuccess($message);
    }
}
