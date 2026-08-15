<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SendNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // كانت send
    public function store(SendNotificationRequest $request)
    {
        $notification = $this->notificationService->send($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال الإشعار بنجاح',
            'data' => new NotificationResource($notification),
        ]);
    }

    // كانت index
    public function index()
    {
        $userId = Auth::id();
        $notifications = $this->notificationService->getUserNotifications($userId);

        return response()->json([
            'success' => true,
            'data' => NotificationResource::collection($notifications),
        ]);
    }

    // كانت unread
    public function getUnread()
    {
        $userId = Auth::id();
        $notifications = $this->notificationService->getUnreadNotifications($userId);

        return response()->json([
            'success' => true,
            'data' => NotificationResource::collection($notifications),
        ]);
    }

    // كانت markAsRead
    public function markAsRead($id)
    {
        $userId = Auth::id();
        $result = $this->notificationService->markAsRead($id, $userId);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'الإشعار غير موجود',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الإشعار كمقروء',
        ]);
    }

    // كانت markAllAsRead
    public function markAllAsRead()
    {
        $userId = Auth::id();
        $count = $this->notificationService->markAllAsRead($userId);

        return response()->json([
            'success' => true,
            'message' => "تم تحديد {$count} إشعار كمقروء",
        ]);
    }

    // كانت destroy
    public function destroy($id)
    {
        $userId = Auth::id();
        $result = $this->notificationService->delete($id, $userId);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'الإشعار غير موجود',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الإشعار بنجاح',
        ]);
    }

    // كانت destroyAll
    public function destroyAll()
    {
        $userId = Auth::id();
        $count = $this->notificationService->deleteAll($userId);

        return response()->json([
            'success' => true,
            'message' => "تم حذف {$count} إشعار",
        ]);
    }

    // كانت unreadCount
    public function getUnreadCount()
    {
        $userId = Auth::id();
        $count = $this->notificationService->getUnreadCount($userId);

        return response()->json([
            'success' => true,
            'unread_count' => $count,
        ]);
    }
}