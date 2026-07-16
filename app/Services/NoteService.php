<?php

namespace App\Services;

use App\Models\Note;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class NoteService
{
    public function __construct(protected FolderService $folderService){}

    public function createNote(array $data): Note
    {
        return DB::transaction(function () use ($data) {
            $user = auth()->user();
            $note = $user->notes()->create([
                'name' => $data['name'],
                'content' => $data['content'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            if(!empty($data['folder_name'])) {
                $folder_ids = $this->folderService->ensureFolderExists($data['folder_name'], $user);
                $note->folders()->attach($folder_ids);

                foreach ($folder_ids as $folder_id) {
                    $this->clearFolderCacheByFolderId($user->id, $folder_id);
                }
            }
            if(!empty($data['tags_name'])) {
                $tag_ids = $this->ensureTagsExist($data['tags_name'], $user);
                $note->tags()->attach($tag_ids);
            }
            $note->updateSearchVector();
            return $note;
        });
    }

    public function updateNote(array $data, $id): Note
    {
        return DB::transaction(function () use ($data, $id) {
            $user = auth()->user();
            $note = $user->notes()->findOrFail($id);

            $noteFields = collect($data)
                ->only(['name', 'content'])
                ->filter(fn($item) => $item !== '' && $item !== null)
                ->toArray();

            if(!empty($noteFields)) {
                $note->update($noteFields);

            }
            if(array_key_exists('folders', $data)) {
                $folder_ids = !empty($data['folders'])
                    ? $this->folderService->ensureFolderExists($data['folders'], $user)
                    : [];

                $currentFolderIds = $note->folders()->pluck('id')->toArray();

                $note->folders()->sync($folder_ids);

                $impactedFolders = array_unique(array_merge($currentFolderIds, $folder_ids));

                foreach ($impactedFolders as $folderId) {
                    $this->clearFolderCacheByFolderId($user->id, $folderId);
                }
            }

            if(array_key_exists('tags_name', $data)) {
                $tag_ids = !empty($data['tags_name']) ? $this->ensureTagsExist($data['tags_name'], $user) : [];
                $note->tags()->sync($tag_ids);
            }
            return $note;
        });
    }
    private function ensureTagsExist(array $tags, $user): array {
        $tags = array_unique($tags);
        $existingTags = $user->tags()
            ->whereIn('name', $tags)
            ->pluck('name')
            ->toArray();
        $missingNames = array_diff($tags, $existingTags);
        if(!empty($missingNames)) {
            $insertData = collect($missingNames)->map(fn($name) => [
                'name' => $name,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            $user->tags()->insert($insertData);

        }
        return $user->tags()->whereIn('name', $tags)->pluck('id')->toArray();
    }

    public function clearFolderCacheByFolderId(int $userId, int $folderId): void
    {
        $redis = Redis::connection();
        $prefix = config('database.redis.options.prefix', '');
        $mask = "{$prefix}user:{$userId}:folder:{$folderId}:page:*";

        $key = $redis->keys($mask);

        if(!empty($key)) {
            Cache::forget(str_replace($prefix, '', $key));
        }
    }
}
