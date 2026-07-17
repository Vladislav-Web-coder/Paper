<x-app-layout>
    <x-slot name="title">{{ __('Tag') }}: #{{ $tag->name }}</x-slot>

    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Шапка страницы -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl font-bold text-xl shadow-inner">
                    #
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">{{ $tag->name }}</h1>
                    <p class="text-xs text-gray-500 mt-0.5">{{ __('Filter results for this specific tag') }}</p>
                </div>
            </div>

            <!-- Кнопка удаления тега -->
            <form action="{{ route('tags.destroy', $tag->id) }}" method="POST"
                  onsubmit="return confirm('{{ __('Are you sure you want to completely delete this tag? It will be removed from all notes.') }}');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full sm:w-auto px-4 py-2 border border-rose-200 text-rose-600 rounded-xl text-sm font-medium hover:bg-rose-50 transition duration-200 shadow-sm flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    {{ __('Delete Tag Completely') }}
                </button>
            </form>
        </div>

        <!-- Вывод заметок -->
        <div class="space-y-4">
            <h2 class="text-lg font-bold text-gray-900">{{ __('Notes tagged with #:name', ['name' => $tag->name]) }}</h2>

            @if($notes->isEmpty())
                <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-gray-500 text-sm">
                    {{ __('No notes found with this tag.') }}
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($notes as $note)
                        <x-note-card :note="$note" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
