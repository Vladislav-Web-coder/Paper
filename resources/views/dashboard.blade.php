<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <!-- Шапка страницы и поиск -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $greeting }}</h1>
        </div>
        <form action="{{ url()->current() }}" method="GET" class="w-full md:w-80">
            <div class="relative">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Searching your papers..."
                       class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                @if(request('search'))
                    <a href="{{ url()->current() }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-gray-400 hover:text-gray-600">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Карточки статистики -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-10">
        <div class="p-6 bg-white border border-gray-200 rounded-2xl flex items-center justify-between shadow-sm">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Just the notes:</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $notes_count }}</h3>
            </div>
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-2xl flex items-center justify-between shadow-sm">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Just the folders</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $folders_count }}</h3>
            </div>
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Основная сетка -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Левая колонка (Заметки) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Блок ЗАКРЕПЛЕННЫХ заметок (Показывается всегда, когда нет активного поиска) -->
            @if(!request('search'))
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <!-- Иконка канцелярской кнопки в стиле вашего UI -->
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.963 14.804A4.001 4.001 0 007.75 19.137M12 14.502c.333.115.682.176 1.037.176.772 0 1.503-.277 2.074-.775m-3.111.6c.015.424.161.83.421 1.157m3.111-2.157c.307-.406.49-.912.49-1.46c0-1.218-.895-2.22-2.073-2.41m2.073 2.41c-.247.327-.58.583-.963.738m0 0A4.002 4.002 0 0112 7.502M15.5 14c.732 0 1.403-.26 1.926-.69M15.5 14V7.5M12 7.5c0-.663.537-1.2 1.2-1.2.536 0 .984.35 1.137.83M12 7.5v6.5m3.5-6.5C15.5 6.67 14.83 6 14 6" />
                            </svg>
                            Pinned notes
                        </h2>
                    </div>

                    @if($pinned_notes->isEmpty())
                        <!-- Пустое состояние для закрепленных заметок -->
                        <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-gray-400 text-sm">
                            No pinned notes yet. Pin important notes to see them here.
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

            <!-- Блок ПОСЛЕДНИХ или НАЙДЕННЫХ заметок -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">
                        {{ request('search') ? 'Search results' : 'Recent notes' }}
                    </h2>
                    <a href="{{ route('notes.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">All notes →</a>
                </div>

                @if($notes->isEmpty())
                    <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-gray-500 text-sm">
                        No notes found.
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

        <!-- Правая колонка (Папки) -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Recent folders</h2>
                <a href="/folders" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">All folders →</a>
            </div>

            @if($folders->isEmpty())
                <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-gray-500 text-sm">
                    You don't have any folders yet.
                </div>
            @else
                <div class="flex flex-col gap-3">
                    @foreach($folders as $folder)
                        <x-folder-card :folder="$folder" />
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
