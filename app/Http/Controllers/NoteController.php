<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Tag;
use App\Services\NoteService;
use App\Models\Note;
use App\Models\Folder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\Mime\HtmlToTextConverter\LeagueHtmlToMarkdownConverter;

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
        $page = $request->query('page', 1);
        $perPage = 12;

        // Cache only note Ids for current page
        $cachedData = Cache::tags(["user:{$user->id}:notes"])->remember("page:{$page}", now()->addMinutes(10), function () use ($user, $perPage) {
            $paginator = $user->notes()
                ->select(['notes.id', 'notes.name', 'notes.content', 'notes.created_at'])
                ->with(['tags:id,name', 'folders:id,name'])
                ->latest()
                ->paginate($perPage);
            return [
                'items' => $paginator->getCollection()->toArray(),
                'total' => $paginator->total(),
            ];
        });

        $notesCollection = Note::hydrate($cachedData['items']);

        $notesCollection->each(function ($note) {
            $rawTags = $note->getAttribute('tags') ?? [];
            $rawFolders = $note->getAttribute('folders') ?? [];

            $note->setRelations([
                'tags' => Tag::hydrate($rawTags),
                'folders' => Folder::hydrate($rawFolders),
            ]);
            unset($note->folders, $note->tags);
        });

        $notes = new LengthAwarePaginator(
            $notesCollection,
            $cachedData['total'],
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
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
    public function show(Request $request, Note $note)
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
    public function pin(Request $request,Note $note): RedirectResponse
    {
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
