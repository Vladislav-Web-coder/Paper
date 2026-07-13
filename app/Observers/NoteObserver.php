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
        $this->clearNotesIndexCache($note->user_id);
    }

    /**
     * Handle the Note "updated" event.
     */
    public function updated(Note $note): void
    {
        $this->clearNotesIndexCache($note->user_id);
    }

    /**
     * Handle the Note "deleted" event.
     */
    public function deleted(Note $note): void
    {
        $this->clearNotesIndexCache($note->user_id);
    }
    private function clearNotesIndexCache(int $userId): void
    {
        Cache::tags(["user:{$userId}:notes"])->flush();
    }
}
