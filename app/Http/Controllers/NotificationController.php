<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $notifications = $user->notifications()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(DatabaseNotification $notification): RedirectResponse
    {
        abort_if($notification->notifiable_id !== auth()->id(), 403);

        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }
    public function markAsReadAll(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(DatabaseNotification $notification): RedirectResponse
    {
        abort_if($notification->notifiable_id !== auth()->id(), 403);

        $notification->delete();

        return back()->with('success', 'Notification deleted.');
    }
    public function clearAll(): RedirectResponse
    {
        auth()->user()->notifications()->delete();

        return back()->with('success', 'Notification history cleared.');
    }
}
