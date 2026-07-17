<x-layout>
    <x-slot name="title">{{ __('Dashboard') }}</x-slot>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ __($greeting) }}</h1>
        </div>
        <form action="{{ url()->current() }}" method="GET" class="w-full md:w-80">
            <div class="relative">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="{{ __('Searching your papers...') }}"
                       class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                @if(request('search'))
                    <a href="{{ url()->current() }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-gray-400 hover:text-gray-600">
                        {{ __('Clear') }}
                    </a>
                @endif
            </div>
        </form>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-10">
        <div class="group relative p-6 bg-white border border-gray-200 rounded-2xl flex items-center justify-between shadow-sm hover:border-indigo-200 transition duration-200">
            <div class="flex items-center justify-between w-full pr-12">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">{{ __('Just the notes:') }}</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $notes_count }}</h3>
                </div>
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:scale-95 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('notes.create') }}"
               class="absolute right-4 p-2.5 bg-indigo-600 text-white rounded-xl shadow-md hover:bg-indigo-700 transition duration-200 opacity-0 group-hover:opacity-100 flex items-center justify-center"
               title="{{ __('Create new note') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </a>
        </div>
        <div class="group relative p-6 bg-white border border-gray-200 rounded-2xl flex items-center justify-between shadow-sm hover:border-amber-200 transition duration-200">
            <div class="flex items-center justify-between w-full pr-12">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">{{ __('Just the folders') }}</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $folders_count }}</h3>
                </div>
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl group-hover:scale-95 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('folders.create') }}"
               class="absolute right-4 p-2.5 bg-amber-500 text-white rounded-xl shadow-md hover:bg-amber-600 transition duration-200 opacity-0 group-hover:opacity-100 flex items-center justify-center"
               title="{{ __('Create new folder') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </a>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            @if(!request('search'))
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.963 14.804A4.001 4.001 0 007.75 19.137M12 14.502c.333.115.682.176 1.037.176.772 0 1.503-.277 2.074-.775m-3.111.6c.015.424.161.83.421 1.157m3.111-2.157c.307-.406.49-.912.49-1.46c0-1.218-.895-2.22-2.073-2.41m2.073 2.41c-.247.327-.58.583-.963.738m0 0A4.002 4.002 0 0112 7.502M15.5 14c.732 0 1.403-.26 1.926-.69M15.5 14V7.5M12 7.5c0-.663.537-1.2 1.2-1.2.536 0 .984.35 1.137.83M12 7.5v6.5m3.5-6.5C15.5 6.67 14.83 6 14 6" />
                            </svg>
                            {{ __('Pinned notes') }}
                        </h2>
                    </div>
                    @if($pinned_notes->isEmpty())
                        <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-gray-400 text-sm">
                            {{ __('No pinned notes yet. Pin important notes to see them here.') }}
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($pinned_notes as $note)
                                <x-note-card :note="$note" />
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">
                        {{ request('search') ? __('Search results') : __('Recent notes') }}
                    </h2>
                    <a href="{{ route('notes.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">{{ __('All notes →') }}</a>
                </div>
                @if($notes->isEmpty())
                    <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-gray-500 text-sm">
                        {{ __('No notes found.') }}
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($notes as $note)
                            <x-note-card :note="$note" />
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="space-y-8">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">{{ __('Recent folders') }}</h2>
                    <a href="{{ route('folders.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">{{ __('All folders →') }}</a>
                </div>
                @if($folders->isEmpty())
                    <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-gray-500 text-sm">
                        {{ __("You don't have any folders yet.") }}
                    </div>
                @else
                    <div class="flex flex-col gap-3">
                        @foreach($folders as $folder)
                            <div class="p-3 bg-white border border-gray-200 rounded-xl shadow-sm flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700 truncate">{{ $folder->name }}</span>
                                <a href="{{ route('folders.show', $folder->id) }}" class="text-xs text-indigo-600 hover:underline shrink-0 ml-2">→</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="space-y-4 border-t border-gray-100 pt-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">{{ __('Recent tags') }}</h2>
                    <a href="{{ route('tags.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">{{ __('All tags →') }}</a>
                </div>
                @if($tags->isEmpty())
                    <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-gray-500 text-sm">
                        {{ __("You don't have any tags yet.") }}
                    </div>
                @else
                    <div class="flex flex-wrap gap-2 p-4 bg-gray-50/50 border border-gray-200 rounded-2xl">
                        @foreach($tags as $tag)
                            <a href="{{ route('tags.show', $tag->id) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-150 hover:border-indigo-300 hover:bg-indigo-50/50 rounded-full text-xs font-semibold text-gray-600 transition duration-150 shadow-sm">
                                <span class="text-indigo-500 font-bold">#</span>
                                <span>{{ $tag->name }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>

