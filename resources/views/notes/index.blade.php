<x-app-layout>
    <x-slot name="title">{{ __('My Notes') }}</x-slot>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">{{ __('My notes') }}</h1>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <a href="{{ route('notes.create') }}" class="whitespace-nowrap bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm">
                {{ __('+ Create note') }}
            </a>

            <form action="{{ url()->current() }}" method="GET" class="w-full md:w-80">
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="{{ __('Search your notes...') }}"
                           class="w-full pl-10 pr-4 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition placeholder-gray-400 dark:placeholder-gray-500"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    @if(request('search'))
                        <a href="{{ url()->current() }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                            {{ __('Clear') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                {{ request('search') ? __('Search results') : __('All notes') }}
                <span class="text-sm font-normal text-gray-400 dark:text-gray-500 ml-1">
                    ({{ $notes instanceof \Illuminate\Pagination\LengthAwarePaginator ? $notes->total() : $notes->count() }})
                </span>
            </h2>
        </div>

        @if($notes->isEmpty())
            <div class="p-8 text-center bg-gray-50 dark:bg-gray-800/40 border border-dashed border-gray-200 dark:border-gray-700 rounded-2xl text-gray-500 dark:text-gray-400 text-sm">
                {{ __('Notes not found.') }}
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($notes as $note)
                    <x-note-card :note="$note" />
                @endforeach
            </div>

            @if($notes instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-8 pagination-indigo">
                    {{ $notes->links() }}
                </div>
            @endif
        @endif
    </div>

    @push('styles')
        <style>
            /* 1. Общие базовые настройки кнопок пагинации */
            .pagination-indigo nav span,
            .pagination-indigo nav a {
                border-radius: 0.75rem !important; /* rounded-xl */
                font-size: 0.875rem !important;    /* text-sm */
                margin: 0 0.125rem;
                transition: all 0.2s ease-in-out;
            }

            /* 2. Стили для СВЕТЛОЙ темы */
            .pagination-indigo nav a {
                background-color: #ffffff !important;
                border-color: #e5e7eb !important; /* border-gray-200 */
                color: #4b5563 !important;        /* text-gray-600 */
            }
            .pagination-indigo nav a:hover {
                color: #4f46e5 !important;         /* text-indigo-600 */
                border-color: #6366f1 !important;   /* border-indigo-500 */
                background-color: #f5f3ff !important; /* bg-indigo-50 */
            }
            .pagination-indigo nav [aria-current="page"] span,
            .pagination-indigo nav span[aria-current="page"] {
                background-color: #4f46e5 !important; /* bg-indigo-600 */
                border-color: #4f46e5 !important;
                color: #ffffff !important;
            }

            /* 3. Стили для ТЕМНОЙ темы */
            .dark .pagination-indigo nav a,
            .dark .pagination-indigo nav span:not([aria-current="page"]) {
                background-color: #1f2937 !important; /* bg-gray-800 */
                border-color: #374151 !important;     /* border-gray-700 */
                color: #9ca3af !important;            /* text-gray-400 */
            }
            .dark .pagination-indigo nav a:hover {
                color: #818cf8 !important;         /* text-indigo-400 */
                border-color: #6366f1 !important;   /* border-indigo-500 */
                background-color: #312e81 !important; /* bg-indigo-950 */
            }
            .dark .pagination-indigo nav [aria-current="page"] span,
            .dark .pagination-indigo nav span[aria-current="page"] {
                background-color: #6366f1 !important; /* bg-indigo-500 */
                border-color: #6366f1 !important;
                color: #ffffff !important;
            }
        </style>
    @endpush
</x-app-layout>
