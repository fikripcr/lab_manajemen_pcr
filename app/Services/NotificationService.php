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
        return Auth::user()->unreadNotifications()->count();
    }

    /**
     * Get list of notifications for the authenticated user
     */
    public function getNotificationList(array $filters = []): LengthAwarePaginator
    {
        $user = Auth::user();
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
        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();

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
        Auth::user()->unreadNotifications->markAsRead();
        return true;
    }
}
