<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class NotificationIndicator extends Component
{
    public bool $hasUnread = false;
    public function __construct()
    {
        if(auth()->check()) {
            $user = auth()->user();

            $this->hasUnread = Cache::tags("user:{$user->id}:notifications")
                ->remember('has_unread', now()->addMinutes(60), function () use ($user) {
                    return $user->unreadNotifications()->exists();
                });
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.notification-indicator');
    }
}
