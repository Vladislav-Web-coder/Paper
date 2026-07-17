<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $page = $request->query('page', 1);
        $perPage = 15;

        $jsonString = Cache::tags(["user:{$user->id}:notifications"])
            ->remember("user_{$user->id}_notifications_json_page_{$page}", now()->addMinutes(10), function () use ($user, $perPage) {
                $paginator = $user->notifications()->latest()->paginate($perPage);

                return json_encode([
                    'items' => $paginator->getCollection()->toArray(),
                    'total' => $paginator->total(),
                ]);
            });

        $cached = json_decode($jsonString, true);

        $notificationsCollection = collect($cached['items'])->map(function (array $attributes) {
            $notification = new DatabaseNotification();

            if (isset($attributes['data']) && is_array($attributes['data'])) {
                $attributes['data'] = json_encode($attributes['data']);
            }

            $notification->setRawAttributes($attributes, true);
            $notification->exists = true;
            return $notification;
        });

        $notifications = new LengthAwarePaginator(
            $notificationsCollection,
            $cached['total'],
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('notifications.index', [
            'notifications' => $notifications
        ]);
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
