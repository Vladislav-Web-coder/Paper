<?php

namespace App\Services;

class FolderService
{

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
