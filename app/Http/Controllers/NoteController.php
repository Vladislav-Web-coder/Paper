<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Services\NoteService;
use App\Models\Note;
use App\Services\SearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\Mime\HtmlToTextConverter\LeagueHtmlToMarkdownConverter;

class NoteController extends Controller
{
    public function __construct(protected NoteService $noteService)
    {}

    public function index(Request $request, SearchService $searchService): View
    {
        Gate::authorize('viewAny', Note::class);

        $user = $request->user();
        $search = $request->input('search');

        if ($search) {
            $notes = $searchService->searchNotesForUser($user, $search, limit: 50);

            return view('notes.index', ['notes' => $notes]);
        }

        $page = $request->query('page', 1);
        $perPage = 12;

        $cachedData = Cache::tags(["user:{$user->id}:notes"])
            ->remember("page:{$page}", now()->addMinutes(10), function () use ($user, $perPage) {
                $paginator = $user->notes()->latest()->paginate($perPage);

                return [
                    'ids'   => $paginator->pluck('id')->toArray(),
                    'total' => $paginator->total(),
                ];
            });

        $notesCollection = empty($cachedData['ids'])
            ? collect()
            : Note::with(['tags:id,name', 'folders:id,name'])
                ->whereIn('id', $cachedData['ids'])
                ->latest()
                ->get();

        $notes = new LengthAwarePaginator(
            $notesCollection,
            $cachedData['total'],
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('notes.index', ['notes' => $notes]);
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
    public function show(Request $request, Note $note): View
    {
        Gate::authorize('view', $note);
        return view('notes.show', ['note' => $note]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Note $note): View
    {
        Gate::authorize('update', $note);
        $note->load(['tags', 'folders']);

        $user = $request->user();
        $folders = $user->folders()->pluck('name', 'id');
        $tags = $user->tags()->pluck('name', 'id');

        return view('notes.edit', [
            'note' => $note,
            'folders' => $folders,
            'tags' => $tags
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, $id): RedirectResponse
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
    public function pin(Request $request,Note $note): RedirectResponse
    {
        Gate::authorize('pin', $note);

        $user = $request->user();
        if($note->user_id !== $user->id) {
            abort(403);
        }
        $note->is_pinned = !$note->is_pinned;
        $note->save();

        $message = $note->is_pinned ? 'Note pinned successfully.' : 'Note unpinned successfully.';
        return back()->with('success', $message);
    }
}
