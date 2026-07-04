<?php

namespace App\Services;

use App\Models\Folder;
use Illuminate\Support\Facades\DB;

class FolderService
{
    public function createFolder(array $data): Folder
    {
        $user = auth()->user();
        return DB::transaction(function () use ($data, $user) {
            return $user->folders()->firstOrCreate([
                'name' => $data['name'],
            ],
            [
                'description' => $data['description'],
                'create_at' => now(),
                'update_at' => now(),
            ]);
        });
    }
    public function updateFolder(array $data, $id): Folder
    {
        return DB::transaction(function () use ($data, $id) {
            $user = auth()->user();
            $folder = $user->folders()->findOrFail($id);
            $folderFields = collect($data)
                ->only(['name', 'content'])
                ->filter(fn($item) => $item !== '' && $item !== null)
                ->toArray();
            if(!empty($folderFields)) {
                $folder->update($folderFields);
            }

            return $folder;
        });

    }
    public function ensureFolderExists(array $folders, $user): array
    {
        $folders = array_unique($folders);

        $existingFolders = $user->folders()
            ->whereIn('name', $folders)
            ->pluck('name')
            ->toArray();
        if(!empty($existingFolders)) {
            $insertData = collect($folders)->map(fn ($existingFolder) => [
                'name' => $existingFolder,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            $user->folders()->insert($insertData);
        }
        return $user->folders()->whereIn('name', $folders)->pluck('id')->toArray();
    }
}
