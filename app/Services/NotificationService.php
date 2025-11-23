<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationService
{
    /**
     * Get count of unread notifications for the authenticated user
     */
    public function getUnreadCount(): int
    {
        $user = auth()->user();
        if (!$user) {
            return 0;
        }

        return $user->unreadNotifications()->count();
    }

    /**
     * Get list of notifications for the authenticated user
     */
    public function getNotificationList(array $filters = []): LengthAwarePaginator
    {
        $user = Auth::user();
        if (!$user) {
            // Return empty paginator if no user is authenticated
            return collect([])->paginate(10);
        }

        $query = $user->notifications();

        // Apply filters
        if (isset($filters['read_status'])) {
            if ($filters['read_status'] === 'read') {
                $query->whereNotNull('read_at');
            } elseif ($filters['read_status'] === 'unread') {
                $query->whereNull('read_at');
            }
        }

        $perPage = $filters['per_page'] ?? 10;

        return $query->latest()->paginate($perPage);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(string $notificationId): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        $notification = $user->notifications()->where('id', $notificationId)->first();

        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        $user->unreadNotifications->markAsRead();
        return true;
    }
}
