<?php

namespace App\Observers;

use App\Models\Note;
use Illuminate\Support\Facades\Cache;

class NoteObserver
{
    /**
     * Handle the Note "created" event.
     */
    public function created(Note $note): void
    {
        $this->clearGeneralIndexCache($note->user_id);
    }

    /**
     * Handle the Note "updated" event.
     */
    public function updated(Note $note): void
    {
        Cache::forget("user:{$note->user_id}:note:{$note->id}");
        $this->clearGeneralIndexCache($note->user_id);
    }

    /**
     * Handle the Note "deleted" event.
     */
    public function deleted(Note $note): void
    {
        Cache::forget("user:{$note->user_id}:note:{$note->id}");
        $this->clearGeneralIndexCache($note->user_id);
    }
    private function clearGeneralIndexCache(int $userId): void
    {
        $redis = redis();
        $prefix = config('database.redis.options.prefix', '');
        $mask = $prefix . "user:{$userId}:notes:ids:page:*";

        $keys = $redis->keys($mask);
        if (!empty($keys)) {
            foreach ($keys as $key) {
                Cache::forget(str_replace($prefix, '', $key));
            }
        }
    }
}
