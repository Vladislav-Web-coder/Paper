<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFolderRequest;
use App\Http\Requests\UpdateFolderRequest;
use App\Models\Folder;
use App\Models\Note;
use App\Models\Tag;
use App\Services\FolderService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class FolderController extends Controller
{
    public function __construct(FolderService $folderService)
    {}

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Folder::class);

        $user = $request->user();
        $page = $request->query('page', 1);
        $perPage = 12;

        // Cache only folder Ids for current page
        $cachedData = Cache::tags("user:{$user->id}:folders:index")->remember("page:{$page}", now()->addMinutes(10), function () use ($user, $perPage) {
            $paginator = $user->folders()
                ->select(['folders.id', 'folders.name', 'folders.description', 'folders.created_at'])
                ->latest()
                ->paginate($perPage);
            return [
                'items' => $paginator->getCollection()->toArray(),
                'total' => $paginator->total(),
            ];
        });

        $foldersCollection = Folder::hydrate($cachedData['items']);

        $folders = new LengthAwarePaginator(
            $foldersCollection,
            $cachedData['total'],
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        return view('folders.index', ['folders' => $folders]);
    }

    public function store(CreateFolderRequest $request)
    {
        $data = $request->validated();

        $folder = $this->folderService->createFolder($data);
        return redirect()
            ->route('folders.show', $folder);
    }

    public function show(Request $request, Folder $folder)
    {
        $user = $request->user();
        $page = $request->query('page', 1);
        $perPage = 12;

        Gate::authorize('view', $folder);

        $cachedData = Cache::tags(["user:{$user->id}:folder:{$folder->id}"])->remember("page:{$page}", now()->addMinutes(30), function () use ($folder, $perPage) {
            $paginator = $folder->notes()
                ->select(['notes.id', 'notes.name', 'notes.content','notes.created_at'])
                ->with('tags:id,name')
                ->latest()
                ->paginate($perPage);
            return [
                'items' => $paginator->getCollection()->toArray(),
                'total' => $paginator->total(),
            ];
        });

        $notesCollection = Note::hydrate($cachedData['items']);
        $notesCollection->each(function($note) {
            $rawTags = $note->getAttribute('tags');

            $note->setRelations([
                'tags' => Tag::hydrate($rawTags) ?? [],
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

        return view('folders.show', [
            'notes' => $notes,
            'folder' => $folder
        ]);
    }
    public function update(UpdateFolderRequest $request, $id)
    {
        $data = $request->validated();
        $folder = $this->folderService->updateFolder($data, $id);

        return redirect()
            ->route('folders.show', $folder)
            ->with('success', 'Folder updated successfully.');
    }

    public function destroy(Folder $folder)
    {
        Gate::authorize('delete', $folder);

        $folder->delete();

        return redirect()
            ->route('folders.index')
            ->with('success', 'Folder deleted successfully.');
    }
}
