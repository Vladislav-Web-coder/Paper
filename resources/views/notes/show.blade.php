<x-app-layout>
    <x-slot name="title">{{ $note->name }} — {{ __('My Notes') }}</x-slot>

    <div class="space-y-6 max-w-4xl mx-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
            <a href="{{ route('notes.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition group">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Back to notes') }}
            </a>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <a href="{{ route('notes.edit', $note) }}" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-200 dark:hover:border-indigo-800 transition shadow-sm">
                    {{ __('Edit note') }}
                </a>

                <form action="{{ route('notes.destroy', $note) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this note?') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 text-rose-600 dark:text-rose-400 px-4 py-2 rounded-xl text-sm font-medium hover:bg-rose-100/70 dark:hover:bg-rose-950/60 transition shadow-sm cursor-pointer">
                        {{ __('Delete') }}
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-gray-100 dark:border-gray-700/50">
                <div class="flex flex-wrap items-center gap-2">
                    @if(!$note->folders->isEmpty())
                        @foreach($note->folders as $folder)
                            <span class="bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 text-xs font-medium px-2.5 py-1 rounded-xl flex items-center gap-1 border border-gray-200/50 dark:border-gray-700/50">
                                📁 {{ $folder->name }}
                            </span>
                        @endforeach
                    @else
                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">{{ __('No folder') }}</span>
                    @endif

                    <span class="text-gray-300 dark:text-gray-600 hidden sm:inline">•</span>

                    @if(!$note->tags->isEmpty())
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($note->tags as $tag)
                                <span class="bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 text-xs font-semibold px-2.5 py-1 rounded-full border border-indigo-100/50 dark:border-indigo-900/30">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="text-xs text-gray-400 dark:text-gray-500 font-medium" title="{{ $note->created_at->format('d.m.Y H:i') }}">
                    {{ __('Created :time', ['time' => $note->created_at->diffForHumans()]) }}
                </div>
            </div>

            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight leading-tight">
                    {{ $note->name }}
                </h1>
            </div>

            <div class="prose prose-indigo dark:prose-invert max-w-none text-gray-800 dark:text-gray-300 leading-relaxed text-sm">
                {!! Str::markdown(e($note->content)) !!}
            </div>
        </div>
    </div>
</x-app-layout>
