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
        $this->clearFoldersIndexCache($folder->user_id);
    }

    /**
     * Handle the Folder "updated" event.
     */
    public function updated(Folder $folder): void
    {
        $this->clearSingleFolderPagesCache($folder->user_id, $folder->id);

        $this->clearFoldersIndexCache($folder->user_id);
    }

    /**
     * Handle the Folder "deleted" event.
     */
    public function deleted(Folder $folder): void
    {
        $this->clearSingleFolderPagesCache($folder->user_id, $folder->id);

        $this->clearFoldersIndexCache($folder->user_id);
    }

    private function clearFoldersIndexCache(int $userId): void
    {
        $this->clearByMask("user:{$userId}:folders:ids:page:*");
    }
    private function clearSingleFolderPagesCache(int $userId, int $folderId): void
    {
        $this->clearByMask("user:{$userId}:folder:{$folderId}:page:*");
    }
    private function clearByMask(string $cacheMask): void
    {
        $redis = redis();
        $prefix = config('database.redis.options.prefix', '');

        $mask = $prefix . $cacheMask;
        $keys = $redis->key($mask);

        if (!empty($keys)) {
            foreach ($keys as $key) {
                Cache::forget(str_replace($prefix, '', $key));
            }
        }
    }
}
