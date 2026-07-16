<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    public function index(Request $request) {
        $user = $request->user();
        $page = $request->query('page', 1);
        $notifications = Cache::tags(["user:{$user->id}:notifications"])
            ->remember("list:page:{$page}", now()->addMinutes(60), function () use ($user) {
                return $user->notifications()->paginate(15);
            });
        return view('notifications.index', ['notifications' => $notifications]);
    }

    public function markAsRead(Request $request, DatabaseNotification $notification) {
        $user = $request->user();
        if($notification->notifiable_id !== $user->id) {
            abort(403);
        }
        $notification->markAsRead();

        Cache::tags(["user:{$user->id}:notifications"])->flush();

        return back()->with('success', 'Notification marked as read');
    }

    public function markAsReadAll(Request $request) {
        $user = $request->user();
        $user->unreadNotifications->markAsRead();

        Cache::tags(["user:{$user->id}:notifications"])->flush(); Cache::tags(["user:{$user->id}:notifications"])->flush();

        return back()->with('success', 'All notifications marked as read');
    }
    public function destroy(Request $request, DatabaseNotification $notification) {
        $user = $request->user();
       if($notification->notifiable_id !== $user->id) {
           abort(403);
       }
       $notification->delete();

        Cache::tags(["user:{$user->id}:notifications"])->flush();

       return back()->with('success', 'Notification deleted successfully');
    }

    public function clearAll(Request $request) {
        $user = $request->user();
        $user->notifications()->delete();

        Cache::tags(["user:{$user->id}:notifications"])->flush();

        return back()->with('success', 'All notifications deleted successfully');
    }
}
