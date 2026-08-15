<?php

namespace App\Services;

use App\Models\Notification;
use App\Events\NotificationSent;

class NotificationService
{
    public function send(array $data): Notification
    {
        $notification = Notification::create([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'message' => $data['message'],
            'is_read' => false,
            'read_at' => null,
        ]);

        broadcast(new NotificationSent($notification));

        return $notification;
    }

    public function getUserNotifications(int $userId)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUnreadNotifications(int $userId)
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function markAsRead(int $notificationId, int $userId): bool
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if (!$notification) {
            return false;
        }

        $notification->markAsRead();
        return true;
    }

    public function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    public function delete(int $notificationId, int $userId): bool
    {
        return Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->delete() > 0;
    }

    public function deleteAll(int $userId): int
    {
        return Notification::where('user_id', $userId)->delete();
    }

    public function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->count();
    }
}