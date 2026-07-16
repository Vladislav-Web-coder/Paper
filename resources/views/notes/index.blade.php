<x-layout>
    <x-slot name="title">My Notes</x-slot>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">My notes</h1>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <a href="{{ route('notes.create') }}" class="whitespace-nowrap bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm">
                + Create note
            </a>

            <form action="{{ url()->current() }}" method="GET" class="w-full md:w-80">
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search your notes..."
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
    </div>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">
                {{ request('search') ? 'Search results' : 'All notes' }}
                <span class="text-sm font-normal text-gray-400 ml-1">
                    <!-- ИСПРАВЛЕНИЕ: Выводим количество в зависимости от типа данных -->
                    ({{ $notes instanceof \Illuminate\Pagination\LengthAwarePaginator ? $notes->total() : $notes->count() }})
                </span>
            </h2>
        </div>

        @if($notes->isEmpty())
            <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-gray-500 text-sm">
                Notes not found.
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
            .pagination-indigo nav span,
            .pagination-indigo nav a {
                border-radius: 0.75rem !important;
                font-size: 0.875rem !important;
                transition: all 0.2s;
            }
            .pagination-indigo nav a:hover {
                color: #4f46e5 !important;
                border-color: #6366f1 !important;
                background-color: #f5f3ff !important;
            }
            .pagination-indigo nav [aria-current="page"] span,
            .pagination-indigo nav span[aria-current="page"] {
                background-color: #4f46e5 !important;
                border-color: #4f46e5 !important;
                color: #ffffff !important;
            }
            .pagination-indigo nav a,
            .pagination-indigo nav span {
                margin: 0 0.125rem;
            }
        </style>
    @endpush
</x-layout>
