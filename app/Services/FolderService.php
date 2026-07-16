<?php

namespace App\Services;

use App\Models\Folder;
use Illuminate\Support\Facades\DB;

class FolderService
{
    public function createFolder($data): Folder
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
    public function updateFolder(array $data, Folder $folder): Folder
    {
        return DB::transaction(function () use ($data, $folder) {
            $user = auth()->user();
            $folderFields = collect($data)
                ->only(['name', 'description'])
                ->filter(fn($item) => $item !== null)
                ->toArray();
            if(!empty($folderFields)) {
                $folder->update($folderFields);
            }

            return $folder;
        });
    }
    public function ensureFolderExists(array $folders, $user): array
    {
        $folders = array_unique(array_filter($folders));

        if (empty($folders)) {
            return [];
        }

        $existingFolderNames = $user->folders()
            ->whereIn('name', $folders)
            ->pluck('name')
            ->toArray();

        $newFolderNames = array_diff($folders, $existingFolderNames);

        if (!empty($newFolderNames)) {
            $insertData = collect($newFolderNames)->map(fn ($name) => [
                'name' => $name,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            $user->folders()->insert($insertData);
        }

        return $user->folders()
            ->whereIn('name', $folders)
            ->pluck('id')
            ->toArray();
    }
}
