<?php

namespace App\Observers;

use App\Models\Folder;
use Illuminate\Support\Facades\Cache;

class FolderObserver
{
    /**
     * Handle the Folder "created" event.
     */
    public function created(Folder $folder): void
    {
        $userId = $folder->user_id;
        $this->clearFoldersIndexCache($userId);
    }

    /**
     * Handle the Folder "updated" event.
     */
    public function updated(Folder $folder): void
    {
        $userId = $folder->user_id;
        $folderId = $folder->id;

        $this->clearFoldersIndexCache($userId);
        $this->clearFoldersShowCache($userId, $folderId);
    }

    /**
     * Handle the Folder "deleted" event.
     */
    public function deleted(Folder $folder): void
    {
        $userId = $folder->user_id;
        $folderId = $folder->id;

        $this->clearFoldersIndexCache($userId);
        $this->clearFoldersShowCache($userId, $folderId);
    }

    private function clearFoldersIndexCache(int $userId): void
    {
        Cache::tags(["user:{$userId}:folders:index"])->flush();
    }
    private function clearFoldersShowCache(int $userId, int $folderId): void
    {
        Cache::tags(["user:{$userId}:folder:{$folderId}"])->flush();
    }
}
