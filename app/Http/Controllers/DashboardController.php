<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Folder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $search = $request->input('search');

        $greeting = $user->settings->greeting;
        $notes_count = $user->notes()->count();
        $folders_count = $user->folders()->count();

        $pinned_notes = collect();
        if (!$search) {
            $pinned_notes = Note::with('tags')
                ->where('user_id', $user->id)
                ->where('is_pinned', true)
                ->latest()
                ->limit(4)
                ->get();
        }

        $notes = Note::with('tags')
            ->where('user_id', $user->id)
            ->when(!$search, function ($query) {
                return $query->where('is_pinned', false);
            })
            ->latest()
            ->when($search, function ($query, $search) {
                return $query
                    ->select(['id', 'name', 'created_at'])
                    ->selectRaw("ts_rank_cd(search_vector, plainto_tsquery('simple', ?)) as rank", [$search])
                    ->whereRaw("search_vector @@ plainto_tsquery('simple', ?)", [$search])
                    ->orderByRaw("ts_rank_cd(search_vector, plainto_tsquery('simple', ?)) DESC", [$search])
                    ->orderBy('created_at', 'desc');
            })
            ->limit(6)
            ->get();

        $folders = Folder::where('user_id', $user->id)
            ->select(['id', 'name', 'created_at'])
            ->latest()
            ->limit(6)
            ->get();

        return view('dashboard', [
            'greeting' => $greeting,
            'notes_count' => $notes_count,
            'folders_count' => $folders_count,
            'pinned_notes' => $pinned_notes,
            'notes' => $notes,
            'folders' => $folders,
        ]);
    }
}
