<?php
namespace App\Services\Sys;

use App\Models\Sys\Notification;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Get list of notifications with optional filters
     */
    public function getNotificationList(array $filters = []): LengthAwarePaginator
    {
        $query = Notification::with(['notifiable']);

        // Apply filters
        if (isset($filters['type'])) {
            $query->where('type', 'like', '%' . $filters['type'] . '%');
        }

        if (isset($filters['read_status'])) {
            if ($filters['read_status'] === 'read') {
                $query->whereNotNull('read_at');
            } elseif ($filters['read_status'] === 'unread') {
                $query->whereNull('read_at');
            }
        }

        if (isset($filters['date_from']) && isset($filters['date_to'])) {
            $query->whereBetween('created_at', [$filters['date_from'], $filters['date_to']]);
        }

        // Filter by user if provided
        if (isset($filters['user_id'])) {
            $query->where('notifiable_id', $filters['user_id'])
                ->where('notifiable_type', 'App\Models\User');
        }

        $perPage = $filters['per_page'] ?? 10;

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get unread notifications count for a specific user
     */
    public function getUnreadCount($userId = null): int
    {
        $query = Notification::query();

        if ($userId) {
            $query->where('notifiable_id', $userId)
                ->where('notifiable_type', 'App\Models\User');
        } else {
            // If no userId provided, return 0
            return 0;
        }

        return $query->whereNull('read_at')->count();
    }

    /**
     * Get a specific notification by ID
     */
    public function getNotificationById(string $notificationId): ?Notification
    {
        return Notification::with(['notifiable'])->find($notificationId);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(string $notificationId): bool
    {
        return DB::transaction(function () use ($notificationId) {
            $notification = Notification::find($notificationId);
            if (! $notification) {
                throw new \Exception("Notification with ID {$notificationId} not found.");
            }

            $notification->read_at = now();
            if (! $notification->save()) {
                throw new \Exception("Failed to update notification with ID {$notificationId} as read.");
            }

            return true;
        });
    }

    /**
     * Mark all notifications as read for a specific user
     */
    public function markAllAsRead($userId): bool
    {
        return DB::transaction(function () use ($userId) {
            return Notification::where('notifiable_id', $userId)
                ->where('notifiable_type', 'App\Models\User')
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        });
    }

    /**
     * Get filtered query for DataTables
     */
    public function getFilteredQuery(array $filters = [])
    {
        $query = Notification::with(['notifiable'])
            ->select('sys_notifications.*')
            ->orderBy('sys_notifications.created_at', 'desc');

        // Apply filters
        if (isset($filters['type'])) {
            $query->where('sys_notifications.type', 'like', '%' . $filters['type'] . '%');
        }

        if (isset($filters['read_status'])) {
            if ($filters['read_status'] === 'read') {
                $query->whereNotNull('sys_notifications.read_at');
            } elseif ($filters['read_status'] === 'unread') {
                $query->whereNull('sys_notifications.read_at');
            }
        }

        if (isset($filters['date_from']) && isset($filters['date_to'])) {
            $query->whereBetween('sys_notifications.created_at', [$filters['date_from'], $filters['date_to']]);
        }

        return $query;
    }

    /**
     * Count notifications with filters
     */
    public function countNotifications(array $filters = []): int
    {
        $query = Notification::query();

        if (isset($filters['type'])) {
            $query->where('type', 'like', '%' . $filters['type'] . '%');
        }

        if (isset($filters['read_status'])) {
            if ($filters['read_status'] === 'read') {
                $query->whereNotNull('read_at');
            } elseif ($filters['read_status'] === 'unread') {
                $query->whereNull('read_at');
            }
        }

        return $query->count();
    }

    /**
     * Get notification counts (total, unread, read) for user
     */
    public function getNotificationCounts($userId = null): array
    {
        if (! $userId) {
            $userId = auth()->id();
        }

        $user = User::find($userId);

        $total  = $user->notifications()->count();
        $unread = $user->unreadNotifications()->count();
        $read   = $total - $unread;

        return [
            'total'  => $total,
            'unread' => $unread,
            'read'   => $read,
        ];
    }

    /**
     * Mark notification as read by ID
     */
    public function markAsReadById(string $notificationId, $userId = null): bool
    {
        $query = Notification::where('id', $notificationId);

        if ($userId) {
            $query->where('notifiable_id', $userId)
                ->where('notifiable_type', 'App\Models\User');
        } else {
            $query->where('notifiable_id', auth()->id())
                ->where('notifiable_type', 'App\Models\User');
        }

        $notification = $query->first();
        if (! $notification) {
            throw new \Exception("Notification with ID {$notificationId} not found or does not belong to the specified user.");
        }

        $notification->markAsRead();
        return true;
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsReadForUser($userId = null): int
    {
        $query = Notification::whereNull('read_at');

        if ($userId) {
            $query->where('notifiable_id', $userId)
                ->where('notifiable_type', 'App\Models\User');
        } else {
            $query->where('notifiable_id', auth()->id())
                ->where('notifiable_type', 'App\Models\User');
        }

        return $query->update(['read_at' => now()]);
    }

    /**
     * Mark selected notifications as read
     */
    public function markSelectedAsRead(array $notificationIds, $userId = null): int
    {
        $query = Notification::whereIn('id', $notificationIds);

        if ($userId) {
            $query->where('notifiable_id', $userId)
                ->where('notifiable_type', 'App\Models\User');
        } else {
            $query->where('notifiable_id', auth()->id())
                ->where('notifiable_type', 'App\Models\User');
        }

        return $query->whereNull('read_at')->update(['read_at' => now()]);
    }

    /**
     * Get notifications for dropdown
     */
    public function getDropdownData($userId = null, int $limit = 5)
    {
        $query = Notification::select(['id', 'data', 'read_at', 'created_at']);

        if ($userId) {
            $query->where('notifiable_id', $userId);
        } else {
            $query->where('notifiable_id', auth()->id());
        }

        $notifications = $query->latest()->limit($limit)->get();

        return $notifications->map(function ($notification) {
            return [
                'id'         => $notification->id,
                'title'      => $notification->data['title'] ?? 'Notification',
                'body'       => $notification->data['body'] ?? 'New notification',
                'created_at' => formatTanggalIndo($notification->created_at),
                'is_unread'  => is_null($notification->read_at),
                'action_url' => route('notifications.mark-as-read', $notification->id),
            ];
        });
    }

    /**
     * Get filtered query for DataTables for authenticated user
     */
    public function getFilteredQueryForUser(array $filters = [])
    {
        $query = Notification::with(['notifiable'])
            ->select('sys_notifications.*')
            ->leftJoin('users', 'sys_notifications.notifiable_id', '=', 'users.id')
            ->orderBy('sys_notifications.created_at', 'desc');

        // Apply filters
        if (isset($filters['read_status'])) {
            if ($filters['read_status'] === 'read') {
                $query->whereNotNull('sys_notifications.read_at');
            } elseif ($filters['read_status'] === 'unread') {
                $query->whereNull('sys_notifications.read_at');
            }
        }

        if (isset($filters['type'])) {
            $query->where('sys_notifications.type', 'like', '%' . $filters['type'] . '%');
        }

        if (isset($filters['date_from']) && isset($filters['date_to'])) {
            $query->whereBetween('sys_notifications.created_at', [$filters['date_from'], $filters['date_to']]);
        }

        return $query->where('sys_notifications.notifiable_id', auth()->id())
            ->where('sys_notifications.notifiable_type', 'App\Models\User');
    }
}
