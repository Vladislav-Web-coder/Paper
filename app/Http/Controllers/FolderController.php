<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFolderRequest;
use App\Http\Requests\UpdateFolderRequest;
use App\Models\Folder;
use App\Services\FolderService;
use Illuminate\Http\Request;
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
        $page = $request->page ?? 1;

        // Cache only folder Ids for current page
        $folderIds = Cache::remember('user:{$user->id}:folders:ids:page:{$page}', now()->addMinutes(10), function () use ($user) {
            return $user->folders()
                ->latest()
                ->paginate(30)
                ->pluck('id')
                ->toArray();
        });

        $folders = Folder::select(['id', 'name', 'description'])
            ->whereIn('id', $folderIds)
            ->latest()
            ->get();
        return view('folders.index', ['folders' => $folders]);
    }

    public function store(CreateFolderRequest $request)
    {
        $data = $request->validated();

        $folder = $this->folderService->createFolder($data);
        return redirect()
            ->route('folders.show', $folder);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $folder = Folder::select('id', 'user_id')->findOrFail($id);
        $page = $request->page ?? 1;

        Gate::authorize('view', $folder);

        $notes = Cache::remember('user:{$user->id}:folder:{$id}:page:{$page}', now()->addMinutes(30), function () use ($id) {
            $folder = Folder::findOrFail($id);
            return $folder->notes()
                ->select(['id', 'name', 'created_at'])
                ->latest()
                ->paginate(30);
        });

        return view('folders.show', [
            'notes' => $notes
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
