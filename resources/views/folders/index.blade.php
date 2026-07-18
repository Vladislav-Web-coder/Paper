<x-app-layout>
    <x-slot name="title">{{ __('My Folders') }}</x-slot>

    <!-- Шапка страницы и действия -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">{{ __('My folders') }}</h1>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('folders.create') }}" class="w-full sm:w-auto text-center whitespace-nowrap bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm">
                {{ __('+ Create folder') }}
            </a>
        </div>
    </div>

    <!-- Основной контент страницы -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                {{ __('All folders') }}
                <span class="text-sm font-normal text-gray-400 dark:text-gray-500 ml-1">({{ $folders->total() }})</span>
            </h2>
        </div>

        @if($folders->isEmpty())
            <div class="p-8 text-center bg-gray-50 dark:bg-gray-800/40 border border-dashed border-gray-200 dark:border-gray-700 rounded-2xl text-gray-500 dark:text-gray-400 text-sm">
                {{ __('Folders not found.') }}
            </div>
        @else
            <!-- Сетка папок -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($folders as $folder)
                    <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-md transition relative group">

                        <!-- Иконка папки и имя -->
                        <div class="flex items-start gap-3 mb-3">
                            <div class="p-2.5 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-xl shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 truncate">
                                    <a href="{{ route('folders.show', $folder->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition stretched-link">
                                        {{ $folder->name }}
                                    </a>
                                </h3>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                    {{ $folder->created_at->format('d.m.Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Описание папки -->
                        @if($folder->description)
                            <div class="prose prose-indigo dark:prose-invert max-w-none text-gray-800 dark:text-gray-300 leading-relaxed text-sm">
                                {!! Str::markdown(e($folder->description)) !!}
                            </div>
                        @else
                            <p class="text-sm text-gray-400 dark:text-gray-500 italic">
                                {{ __('No description provided.') }}
                            </p>
                        @endif

                        <!-- Ссылка/Кнопка перехода -->
                        <div class="mt-4 pt-3 border-t border-gray-50 dark:border-gray-700/50 flex justify-end opacity-0 group-hover:opacity-100 transition">
                            <a href="{{ route('folders.show', $folder->id) }}" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 flex items-center gap-1">
                                {{ __('Open folder') }}
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Блок пагинации -->
            <div class="mt-8 pagination-indigo">
                {{ $folders->links() }}
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            /* Скругление углов для всех кнопок пагинации */
            .pagination-indigo nav span,
            .pagination-indigo nav a {
                border-radius: 0.75rem !important; /* Соответствует rounded-xl */
                font-size: 0.875rem !important;    /* Соответствует text-sm */
                transition: all 0.2s;
            }

            /* Базовая адаптация дефолтных кнопок Laravel пагинации под темный режим */
            .dark .pagination-indigo nav a,
            .dark .pagination-indigo nav span {
                background-color: #1f2937 !important; /* bg-gray-800 */
                border-color: #374151 !important;     /* border-gray-700 */
                color: #9ca3af !important;            /* text-gray-400 */
            }

            /* Стили при наведении на доступные страницы (светлая тема) */
            .pagination-indigo nav a:hover {
                color: #4f46e5 !important;         /* text-indigo-600 */
                border-color: #6366f1 !important;   /* border-indigo-500 */
                background-color: #f5f3ff !important; /* bg-indigo-50 */
            }

            /* Стили при наведении на доступные страницы (темная тема) */
            .dark .pagination-indigo nav a:hover {
                color: #818cf8 !important;         /* text-indigo-400 */
                border-color: #6366f1 !important;   /* border-indigo-500 */
                background-color: #312e81 !important; /* bg-indigo-950 */
            }

            /* Стиль для активной (текущей) страницы */
            .pagination-indigo nav [aria-current="page"] span,
            .pagination-indigo nav span[aria-current="page"] {
                background-color: #4f46e5 !important; /* bg-indigo-600 */
                border-color: #4f46e5 !important;     /* border-indigo-600 */
                color: #ffffff !important;            /* text-white */
            }

            /* Стиль для активной (текущей) страницы в темной теме */
            .dark .pagination-indigo nav [aria-current="page"] span,
            .dark .pagination-indigo nav span[aria-current="page"] {
                background-color: #6366f1 !important; /* bg-indigo-500 */
                border-color: #6366f1 !important;     /* border-indigo-500 */
                color: #ffffff !important;            /* text-white */
            }

            /* Корректировка отступов между кнопками, чтобы они не слипались */
            .pagination-indigo nav a,
            .pagination-indigo nav span {
                margin: 0 0.125rem;
            }
        </style>
    @endpush
</x-app-layout>
