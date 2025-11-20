<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\TestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(10);

        return view('pages.sys.notifications.index', compact('notifications'));
    }

    /**
     * Process datatables ajax request.
     */
    public function paginate(Request $request)
    {
        $notifications = Auth::user()->notifications();

        return DataTables::of($notifications)
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
        $notification = Auth::user()->notifications()->where('id', $id)->firstOrFail();

        $notification->markAsRead();

        // Log the notification marking as read
        logActivity('notification', 'Notification marked as read', $notification);

        return redirect()->back()->with('success', 'Notifikasi telah ditandai sebagai telah dibaca.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $count = Auth::user()->unreadNotifications->count();
        Auth::user()->unreadNotifications->markAsRead();

        // Log the notification marking as read
        logActivity('notification', 'All notifications marked as read: ' . $count . ' notifications by user: ' . auth()->user()->name . ' (ID: ' . auth()->id() . ')');

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi telah ditandai sebagai telah dibaca!'
        ]);
    }

    /**
     * Mark selected notifications as read.
     */
    public function markSelectedAsRead(Request $request)
    {
        $selectedIds = $request->input('ids', []);

        if (empty($selectedIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada notifikasi yang dipilih.'
            ]);
        }

        // Validate that the selected notifications belong to the authenticated user
        $notifications = Auth::user()
            ->notifications()
            ->whereIn('id', $selectedIds)
            ->get();

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        $count = count($notifications);

        // Log the notification marking as read
        logActivity('notification', $count . ' selected notifications marked as read by user: ' . auth()->user()->name . ' (ID: ' . auth()->id() . ')');

        return response()->json([
            'success' => true,
            'message' => $count . ' notifikasi telah ditandai sebagai telah dibaca.'
        ]);
    }

    /**
     * Get user's unread notifications count (for AJAX requests).
     */
    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Handle the incoming request to send a test notification to the authenticated user.
     */
    public function sendTestNotification(Request $request)
    {
        $user = Auth::user();

        // Send the test notification to the authenticated user (database channel)
        $user->notify(new TestNotification('database'));

        // Log the notification sending
        logActivity('notification', 'Test notification sent to user: ' . $user->name . ' (ID: ' . $user->id . ')', $user);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dikirim!'
        ]);
    }

    /**
     * Send notification to a specific user.
     */
    public function sendToUser(Request $request, $user)
    {
        // Attempt to decrypt the ID first
        $decryptedId = decryptId($user, false); // Don't throw exception on failure
        if ($decryptedId !== null) {
            $recipient = User::findOrFail($decryptedId);
        } else {
            // If decryption failed, try to find by the original value
            $recipient = User::findOrFail($user);
        }

        // Send the test notification to the specific user
        $recipient->notify(new TestNotification('database')); // Default to database notification

        // Log the notification sending
        logActivity('notification', 'Test notification sent to user: ' . $recipient->name . ' (ID: ' . $recipient->id . ') by user: ' . auth()->user()->name . ' (ID: ' . auth()->id() . ')', $recipient);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dikirim ke ' . $recipient->name . '!'
        ]);
    }

    /**
     * Get notifications data for dropdown (API endpoint)
     */
    public function getDropdownData(Request $request)
    {
        $limit = $request->get('limit', 5);
        $user = Auth::user();

        // Get only the fields we need to optimize performance
        $notifications = $user->notifications()
            ->select('id', 'data', 'read_at', 'created_at')
            ->latest()
            ->limit($limit)
            ->get();

        $formattedNotifications = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->data['title'] ?? 'Notification',
                'body' => $notification->data['body'] ?? 'New notification',
                'created_at' => formatTanggalIndo($notification->created_at),
                'is_unread' => is_null($notification->read_at),
                'action_url' => route('notifications.mark-as-read', $notification->id)
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedNotifications
        ]);
    }

    /**
     * Get notification counts (total, unread, read)
     */
    public function getNotificationCounts()
    {
        $user = Auth::user();

        $total = $user->notifications()->count();
        $unread = $user->unreadNotifications->count();
        $read = $total - $unread;

        return response()->json([
            'success' => true,
            'counts' => [
                'total' => $total,
                'unread' => $unread,
                'read' => $read
            ]
        ]);
    }

    /**
     * Update notification (currently not implemented)
     */
    public function update(Request $request, $id = null)
    {
        // Currently not implemented - notifications are read/unread only
        return response()->json([
            'success' => false,
            'message' => 'Update operation is not supported for notifications. Use mark-as-read to update status.'
        ], 405); // Method not allowed
    }

    /**
     * Test notification function that can send to database or email based on parameters
     */
    public function testNotification(Request $request)
    {
        $request->validate([
            'type' => 'required|in:database,email',
            'user_id' => 'nullable'
        ]);

        $type = $request->input('type');

        // Check if user_id was provided and if it's an encrypted ID
        if ($request->has('user_id')) {
            // If user_id is passed, it's likely an encrypted ID
            $decryptedId = decryptId($request->input('user_id'), false);
            if ($decryptedId === null) {
                // If decryptId failed, treat the value as a plain ID
                $user = User::find($request->input('user_id'));
            } else {
                $user = User::find($decryptedId);
            }
        } else {
            // Use authenticated user if no user_id provided
            $user = auth()->user();
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        try {
            // Determine channel based on type
            $channel = $type === 'email' ? 'mail' : 'database';
            $notification = new TestNotification($channel);

            if ($type === 'email') {
                // Send email notification
                $user->notify($notification);

                return response()->json([
                    'success' => true,
                    'message' => 'Email notification sent successfully to ' . $user->email
                ]);
            } else {
                // Send database notification (default)
                $user->notify($notification);

                return response()->json([
                    'success' => true,
                    'message' => 'Database notification sent successfully to ' . $user->name
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification (unified function for email/database)
     */
    public function send(Request $request)
    {
        $request->validate([
            'type' => 'required|in:database,email',
            // user_id is optional when sending to authenticated user
            'user_id' => 'nullable',
            'notification_class' => 'required|string' // Notification class name
        ]);

        $type = $request->input('type');
        $userId = $request->input('user_id');
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
        if ($notificationClass === 'TestNotification') {
            $channel = $type === 'email' ? 'mail' : 'database';
            $notification = new \App\Notifications\TestNotification($channel);
        } else {
            // Handle other notification classes as needed
            $notification = new $notificationClass();
        }

        try {
            if ($type === 'email') {
                // Verify the recipient has an email address
                if (empty($recipient->email)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Recipient does not have an email address.'
                    ], 400);
                }

                $recipient->notify($notification);
                $message = 'Email notification sent successfully to ' . $recipient->email;
            } else {
                $recipient->notify($notification);
                $message = 'Database notification sent successfully to ' . $recipient->name;
            }

            // Log the notification sending
            logActivity('notification', "Notification ({$type}) sent to user: " . $recipient->name . ' (ID: ' . $recipient->id . ') by user: ' . auth()->user()->name . ' (ID: ' . auth()->id() . ')');

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            \Log::error('Notification sending failed: ' . $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ], 500);
        }
    }
}
