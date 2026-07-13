<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index(Request $request) {
        $user = $request->user();
        $notifications = $user->notifications()->paginate(15);
        return view('notifications.index', ['notifications' => $notifications]);
    }

    public function markAsRead(Request $request, DatabaseNotification $notification) {
        $user = $request->user();
        if($notification->notifiable_id !== $user->id) {
            abort(403);
        }
        $notification->markAsRead();
        return back()->with('success', 'Notification marked as read');
    }

    public function markAsReadAll(Request $request) {
        $user = $request->user();
        $user->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read');
    }
    public function destroy(Request $request, DatabaseNotification $notification) {
        $user = $request->user();
       if($notification->notifiable_id !== $user->id) {
           abort(403);
       }
       $notification->delete();
       return back()->with('success', 'Notification deleted successfully');
    }

    public function clearAll(Request $request) {
        $user = $request->user();
        $user->notifications()->delete();
        return back()->with('success', 'All notifications deleted successfully');
    }
}
