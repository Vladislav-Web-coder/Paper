<?php

namespace App\Services;

use App\Models\Note;
use Illuminate\Support\Facades\DB;

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
            ]);
            if(!empty($data['folder_name'])) {
                $folder_ids = $this->folderService->ensureFolderExists($data['folder_name'], $user);
                $note->folders()->attach($folder_ids);
            }
            if(!empty($data['tags_name'])) {
                $tag_ids = $this->ensureTagsExist($data['tags_name'], $user);
                $note->tags()->attach($tag_ids);
            }
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
            if(array_key_exists('folder_name', $data)) {
                $folder_ids = !empty($data['folder_name']) ? $this->folderService->ensureFolderExists($data['folder_name'], $user) : [];
                $note->folders()->sync($folder_ids);
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
}
