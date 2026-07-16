<?php

namespace App\Services;

use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Collection;

class SearchService
{
    public function searchNotesForUser(User $user, string $search, int $limit = 6): Collection
    {
        $words = array_filter(explode(' ', trim($search)));

        $processedSearch = collect($words)
            ->map(fn($word) => "{$word}:*")
            ->implode(' & ');

        if (empty($processedSearch)) {
            return collect();
        }

        return Note::with(['tags:id,name', 'folders:id,name'])
            ->where('user_id', $user->id)
            ->select(['id', 'name', 'created_at', 'content', 'is_pinned'])
            ->selectRaw("ts_rank_cd(search_vector, to_tsquery('simple', ?)) as rank", [$processedSearch])
            ->whereRaw("search_vector @@ to_tsquery('simple', ?)", [$processedSearch])
            ->orderByRaw("ts_rank_cd(search_vector, to_tsquery('simple', ?)) DESC", [$processedSearch])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
