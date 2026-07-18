<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFolderRequest;
use App\Http\Requests\UpdateFolderRequest;
use App\Models\Folder;
use App\Models\Note;
use App\Models\Tag;
use App\Services\FolderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class FolderController extends Controller
{
    public function __construct(protected FolderService $folderService)
    {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Folder::class);

        $user = $request->user();
        $page = $request->query('page', 1);
        $perPage = 12;

        $cachedData = Cache::tags(["user:{$user->id}:folders"])
            ->remember("page:{$page}", now()->addMinutes(10), function () use ($user, $perPage) {
                $paginator = $user->folders()->latest()->paginate($perPage);

                return [
                    'ids'   => $paginator->pluck('id')->toArray(),
                    'total' => $paginator->total(),
                ];
            });

        $foldersCollection = empty($cachedData['ids'])
            ? collect()
            : Folder::whereIn('id', $cachedData['ids'])
                ->latest()
                ->get();

        $folders = new LengthAwarePaginator(
            $foldersCollection,
            $cachedData['total'],
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('folders.index', ['folders' => $folders]);
    }


    public function create(): View
    {
        Gate::authorize('create', Folder::class);

        return view('folders.create');
    }
    public function store(StoreFolderRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $folder = $this->folderService->createFolder($data);
        return redirect()
            ->route('folders.show', $folder);
    }
    public function show(Request $request, Folder $folder): View
    {
        Gate::authorize('view', $folder);

        $user = $request->user();
        $page = $request->query('page', 1);
        $perPage = 12;

        $cachedData = Cache::tags(["user:{$user->id}:notes"])
            ->remember("user_{$user->id}_folder_{$folder->id}_page_{$page}", now()->addMinutes(30), function () use ($folder, $perPage) {
                $paginator = $folder->notes()->latest()->paginate($perPage);

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

        return view('folders.show', [
            'notes'  => $notes,
            'folder' => $folder
        ]);
    }

    public function edit(Folder $folder): View
    {
        Gate::authorize('update', $folder);
        return view('folders.edit', ['folder' => $folder]);

    }
    public function update(UpdateFolderRequest $request, Folder $folder): RedirectResponse
    {
        $data = $request->validated();
        $folder = $this->folderService->updateFolder($data, $folder);

        return redirect()
            ->route('folders.show', $folder)
            ->with('success', 'Folder updated successfully.');
    }

    public function destroy(Folder $folder): RedirectResponse
    {
        Gate::authorize('delete', $folder);

        $folder->delete();

        return redirect()
            ->route('folders.index')
            ->with('success', 'Folder deleted successfully.');
    }
}
