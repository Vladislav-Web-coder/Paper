<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class TagsController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Tag::class);

        $user = $request->user();
        $cachedTagsData = Cache::tags(["user:{$user->id}:notes"])
            ->remember("user_{$user->id}_all_tags", now()->addMinutes(30), function () use ($user) {
                return $user->tags()
                    ->select(['tags.id', 'tags.name'])
                    ->withCount('notes')
                    ->orderBy('name')
                    ->get()
                    ->map(fn($tag) => [
                        'id' => $tag->id,
                        'name' => $tag->name,
                        'notes_count' => $tag->notes_count,
                    ])
                    ->toArray();
            });

        $tags = collect($cachedTagsData)->map(function ($item) {
            return new \Illuminate\Support\Fluent($item);
        });
        return view('tags.index', ['tags' => $tags]);
    }
    public function show(Request $request, Tag $tag): View
    {
        Gate::authorize('view', $tag);

        $user = $request->user();
        $noteIds = Cache::tags(["user:{$user->id}:notes"])
            ->remember("user:{$user->id}:tag:{$tag->id}:notes", now()->addMinutes(30), function () use ($user, $tag) {
                return $tag->notes()
                    ->pluck('notes.id')
                    ->toArray();
            });

        $notes = empty($noteIds)
            ? collect()
            : Note::with('tags')
                ->whereIn('id', $noteIds)
                ->latest()
                ->get();
        return view('tags.show', ['tag' => $tag, 'notes' => $notes]);
    }
    public function destroy(Request $request, Tag $tag): RedirectResponse
    {
        Gate::authorize('delete', $tag);

        $user = $request->user();
        $tag->notes()->detach();
        $tag->delete();
        Cache::tags(["user:{$user->id}:notes"])->flush();

        return redirect()->route('tags.index')->with('success', 'Tag deleted');
    }
}
