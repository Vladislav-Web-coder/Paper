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
        $folderIds = [];

        foreach ($folders as $folder) {
            $name = trim($folder);
            if (empty($name)) continue;

            $folder = $user->folders()->firstOrCreate([
                'name' => $name
            ]);

            $folderIds[] = $folder->id;
        }

        return $folderIds;
    }
}
