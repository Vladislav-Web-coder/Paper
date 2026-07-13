<x-app-layout>
    <x-slot name="title">{{ $note->name }} — My Notes</x-slot>

    <div class="space-y-6 max-w-4xl mx-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
            <a href="{{ route('notes.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-indigo-600 transition group">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to notes
            </a>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <a href="{{ route('notes.edit', $note) }}" class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-200 transition shadow-sm">
                    Edit note
                </a>

                <form action="{{ route('notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this note?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-rose-50 border border-rose-200 text-rose-600 px-4 py-2 rounded-xl text-sm font-medium hover:bg-rose-100/70 transition shadow-sm">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-gray-100">
                <div class="flex flex-wrap items-center gap-2">
                    @if(!$note->folders->isEmpty())
                        @foreach($note->folders as $folder)
                            <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2.5 py-1 rounded-xl flex items-center gap-1 border border-gray-200/50">
                                📁 {{ $folder->name }}
                            </span>
                        @endforeach
                    @else
                        <span class="text-xs text-gray-400 italic">No folder</span>
                    @endif

                    <span class="text-gray-300 hidden sm:inline">•</span>

                    @if(!$note->tags->isEmpty())
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($note->tags as $tag)
                                <span class="bg-indigo-50 text-indigo-600 text-xs font-semibold px-2.5 py-1 rounded-full border border-indigo-100/50">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="text-xs text-gray-400 font-medium" title="{{ $note->created_at->format('d.m.Y H:i') }}">
                    Created {{ $note->created_at->diffForHumans() }}
                </div>
            </div>

            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight leading-tight">
                    {{ $note->name }}
                </h1>
            </div>

            <div class="text-base text-gray-700 leading-relaxed whitespace-pre-wrap pt-2">
                {{ $note->content }}
            </div>
        </div>
    </div>
</x-app-layout>
