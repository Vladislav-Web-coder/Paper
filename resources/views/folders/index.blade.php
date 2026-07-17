<x-layout>
    <x-slot name="title">{{ __('My Folders') }}</x-slot>

    <!-- Шапка страницы и действия -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ __('My folders') }}</h1>
        </div>
    </div>

    <!-- Основной контент страницы -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">
                {{ __('All folders') }}
                <span class="text-sm font-normal text-gray-400 ml-1">({{ $folders->total() }})</span>
            </h2>
        </div>

        @if($folders->isEmpty())
            <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-gray-500 text-sm">
                {{ __('Folders not found.') }}
            </div>
        @else
            <!-- Сетка папок -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($folders as $folder)
                    <div class="p-5 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition relative group">

                        <!-- Иконка папки и имя -->
                        <div class="flex items-start gap-3 mb-3">
                            <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-base font-bold text-gray-900 truncate">
                                    <a href="{{ route('folders.show', $folder->id) }}" class="hover:text-indigo-600 transition stretched-link">
                                        {{ $folder->name }}
                                    </a>
                                </h3>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $folder->created_at->format('d.m.Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Описание папки -->
                        @if($folder->description)
                            <div class="prose prose-indigo max-w-none text-gray-800 leading-relaxed">
                                {!! Str::markdown(e($folder->description)) !!}
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic">
                                {{ __('No description provided.') }}
                            </p>
                        @endif

                        <!-- Ссылка/Кнопка перехода -->
                        <div class="mt-4 pt-3 border-t border-gray-50 flex justify-end opacity-0 group-hover:opacity-100 transition">
                            <a href="{{ route('folders.show', $folder->id) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
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
</x-layout>
@push('styles')
        <style>
            /* Скругление углов для всех кнопок пагинации */
            .pagination-indigo nav span,
            .pagination-indigo nav a {
                border-radius: 0.75rem !important; /* Соответствует rounded-xl */
                font-size: 0.875rem !important;    /* Соответствует text-sm */
                transition: all 0.2s;
            }
            /* Стили при наведении на доступные страницы */
            .pagination-indigo nav a:hover {
                color: #4f46e5 !important;         /* text-indigo-600 */
                border-color: #6366f1 !important;   /* border-indigo-500 */
                background-color: #f5f3ff !important; /* bg-indigo-50 */
            }
            /* Стиль для активной (текущей) страницы */
            .pagination-indigo nav [aria-current="page"] span,
            .pagination-indigo nav span[aria-current="page"] {
                background-color: #4f46e5 !important; /* bg-indigo-600 */
                border-color: #4f46e5 !important;     /* border-indigo-600 */
                color: #ffffff !important;            /* text-white */
            }
            /* Корректировка отступов между кнопками, чтобы они не слипались */
            .pagination-indigo nav a,
            .pagination-indigo nav span {
                margin: 0 0.125rem;
            }
        </style>
    @endpush
</x-layout>
