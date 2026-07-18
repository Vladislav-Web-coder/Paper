<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Notifications\DatabaseNotification;

class NotificationPolicy
{
    public function markAsRead(User $user, DatabaseNotification $notification): Response
    {
        return $user->id === $notification->user_id ? Response::allow() : Response::denyAsNotFound();
    }
    public function delete(User $user, DatabaseNotification $notification): Response
    {
        return $user->id === $notification->user_id ? Response::allow() : Response::denyAsNotFound();
    }
    public function clearAll(User $user): bool
    {
        return true;
    }
}
