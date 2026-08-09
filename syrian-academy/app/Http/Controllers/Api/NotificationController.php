<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use App\Events\NotificationSent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return response()->json([
            'status' => true,
            'data' => $notifications
        ]);
    }

    public function unread()
    {
        $unread = Auth::user()->unreadNotifications()->get();
        return response()->json([
            'status' => true,
            'count' => $unread->count(),
            'data' => $unread
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
        ]);

        // بث الإشعار في الوقت الفعلي
        broadcast(new NotificationSent($notification));

        return response()->json([
            'status' => true,
            'message' => 'Notification created successfully',
            'data' => $notification
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $notification->markAsRead();

        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'All notifications marked as read'
        ]);
    }
}