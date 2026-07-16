<?php

namespace App\Services;

use App\Models\Note;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class DashboardService
{
    public function __construct(protected SearchService $searchService)
    {}

    public function getDashboardData(User $user): array
    {
        $counts = Cache::tags(["user:{$user->id}:notes", "user:{$user->id}:folders"])
            ->remember("user_{$user->id}_dashboard_counts", now()->addMinutes(10), function () use ($user) {
                return [
                    'notes_count'   => $user->notes()->count(),
                    'folders_count' => $user->folders()->count(),
                ];
            });

        $noteIds = Cache::tags(["user:{$user->id}:notes"])
            ->remember("user_{$user->id}_dashboard_note_ids", now()->addMinutes(10), function () use ($user) {
                return [
                    'pinned' => Note::where('user_id', $user->id)->where('is_pinned', true)->latest()->limit(4)->pluck('id')->toArray(),
                    'recent' => Note::where('user_id', $user->id)->where('is_pinned', false)->latest()->limit(6)->pluck('id')->toArray(),
                ];
            });

        $folderIds = Cache::tags(["user:{$user->id}:folders"])
            ->remember("user_{$user->id}_dashboard_folder_ids", now()->addMinutes(10), function () use ($user) {
                return Folder::where('user_id', $user->id)->latest()->limit(6)->pluck('id')->toArray();
            });

        return [
            'notes_count'   => $counts['notes_count'],
            'folders_count' => $counts['folders_count'],
            'pinned_notes'  => $this->getNotesByIds($noteIds['pinned']),
            'notes'         => $this->getNotesByIds($noteIds['recent']),
            'folders'       => $this->getFoldersByIds($folderIds),
        ];
    }

    public function searchNotes(User $user, string $search): array
    {
        $notes = $this->searchService->searchNotesForUser($user, $search, limit: 6);

        $folderIds = Cache::tags(["user:{$user->id}:folders"])
            ->remember("user_{$user->id}_dashboard_folder_ids", now()->addMinutes(10), function () use ($user) {
                return Folder::where('user_id', $user->id)->latest()->limit(6)->pluck('id')->toArray();
            });

        return [
            'notes_count'   => $user->notes()->count(),
            'folders_count' => $user->folders()->count(),
            'pinned_notes'  => collect(),
            'notes'         => $notes,
            'folders'       => $this->getFoldersByIds($folderIds),
        ];
    }
    private function getNotesByIds(array $ids): Collection
    {
        if (empty($ids)) {
            return collect();
        }

        return Note::with(['tags:id,name', 'folders:id,name'])
            ->whereIn('id', $ids)
            ->latest()
            ->get();
    }
    private function getFoldersByIds(array $ids): Collection
    {
        if (empty($ids)) {
            return collect();
        }

        return Folder::whereIn('id', $ids)
            ->latest()
            ->get();
    }
}
