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

        $notes_count = $user->notes()->count();
        $folders_count = $user->folders()->count();
        $notes = Note::with('tags')->where('user_id', $user->id)
            ->latest()
            ->when($search, function ($query, $search) {
                return $query
                    ->select(['id', 'name', 'created_at'])
                    ->selectRaw("ts_rank_cd(search_vector, plainto_tsquery('simple', ?)) as rank", [$search])
                    ->whereRaw("search_vector @@ plainto_tsquery('simple', ?)", [$search])
                    ->orderBy('rank', 'desc')
                    ->orderBy('created_at', 'desc');
            }, function ($query) {
                return $query->select(['id', 'name', 'created_at'])->latest();
            })
            ->limit(5)
            ->get();

        $folders = Folder::where('user_id', $user->id)
            ->select(['id', 'name', 'created_at'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', [
            'notes_count' => $notes_count,
            'folders_count' => $folders_count,
            'notes' => $notes,
            'folders' => $folders,
        ]);
    }
}
