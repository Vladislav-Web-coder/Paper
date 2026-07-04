<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Services\NoteService;
use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function __construct(public NoteService $noteService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Note::class);
        $user = $request->user();

        $page = $request->page ?? 1;

        // Cache only note Ids for current page
        $noteIds = Cache::remember('user:{$user->id}:notes:ids:page:{$page}', now()->addMinutes(10), function () use ($user) {
            return $user->notes()
                ->latest()
                ->paginate(30)
                ->pluck('id')
                ->toArray();
        });

        $notes = Note::select(['id', 'name', 'created_at'])
            ->whereIn('id', $noteIds)
            ->latest()
            ->get();
        return view ('notes.index', ['notes' => $notes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', Note::class);
        $user = $request->user();
        $folders = $user->folders()->pluck('name','id');
        $tags = $user->tags()->pluck('name','id');
        return view('notes.create', [
            'folders' => $folders,
            'tags' => $tags
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $note = $this->noteService->createNote($data);

        return redirect()
            ->route('notes.show', $note)
            ->with('success', 'Note created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $noteInfo = Note::select('id', 'user_id')->findOrFail($id);
        Gate::authorize('view', $noteInfo);
        $note = Cache::remember('user:{$user->id}:note:{$id}', now()->addMinutes(10), function () use ($id) {
            return Note::findOrFail($id)->toArray();
        });

        return view('notes.show', ['note' => $note]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note): View
    {
        Gate::authorize('update', $note);
        $note->with('tags', 'folders');

        return view('notes.edit', ['note' => $note]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, $id)
    {
        $data = $request->validated();
        $note = $this->noteService->updateNote($data, $id);

        return redirect()
            ->route('notes.show', $note)
            ->with('success', 'Note updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Note $note): RedirectResponse
    {
        Gate::authorize('delete', $note);

        $user = $request->user();
        $folders = $user->folders()->pluck('id');

        foreach ($folders as $folder) {
            $this->noteService->clearFolderCacheByFolderId($user->id, $folder);
        }

        $note->delete();


        return redirect()
            ->route('notes.index')
            ->with('success', 'Note deleted successfully.');
    }
}
