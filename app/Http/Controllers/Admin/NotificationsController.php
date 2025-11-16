<?php

namespace App\Http\Controllers\Admin;

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

        return view('pages.admin.notifications.index', compact('notifications'));
    }

    /**
     * Process datatables ajax request.
     */
    public function data()
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
                return $notification->created_at->format('d M Y H:i');
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

        return redirect()->back()->with('success', 'Notifikasi telah ditandai sebagai telah dibaca.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

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

        // Send the test notification to the authenticated user
        $user->notify(new TestNotification());

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
        $recipient->notify(new TestNotification());

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dikirim ke ' . $recipient->name . '!'
        ]);
    }
}
