<x-app-layout>
    <x-slot name="title">{{ __('Folder') }}: {{ $folder->name }}</x-slot>

    <!-- Шапка страницы и действия -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('folders.index') }}" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 flex items-center gap-1 mb-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                {{ __('Back to folders') }}
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 tracking-tight flex items-center gap-2">
                <span class="text-gray-400 dark:text-gray-500">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </span>
                {{ $folder->name }}
            </h1>
            @if($folder->description)
                <div class="prose prose-indigo dark:prose-invert max-w-none text-gray-800 dark:text-gray-300 leading-relaxed text-sm mt-2">
                    {!! Str::markdown(e($folder->description)) !!}
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto justify-end flex-wrap sm:flex-nowrap">
            <!-- Кнопка удаления папки -->
            <form action="{{ route('folders.destroy', $folder) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this folder? All notes inside will remain but will lose their folder assignment.') }}');" class="w-full sm:w-auto">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full sm:w-auto whitespace-nowrap bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 text-rose-600 dark:text-rose-400 px-4 py-2 rounded-xl text-sm font-medium hover:bg-rose-100/70 dark:hover:bg-rose-950/60 transition shadow-sm cursor-pointer flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    {{ __('Delete') }}
                </button>
            </form>

            <!-- Кнопка редактирования папки -->
            <a href="{{ route('folders.edit', $folder) }}" class="w-full sm:w-auto text-center whitespace-nowrap bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-200 dark:hover:border-indigo-800 transition shadow-sm">
                {{ __('Edit folder') }}
            </a>

            <!-- Кнопка создания заметки -->
            <a href="{{ route('notes.create', ['folderId' => $folder->id]) }}" class="w-full sm:w-auto text-center whitespace-nowrap bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm">
                {{ __('+ Create note here') }}
            </a>
        </div>
    </div>

    <!-- Основной контент страницы -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                {{ __('Notes in this folder') }}
                <span class="text-sm font-normal text-gray-400 dark:text-gray-500 ml-1">({{ $notes->total() }})</span>
            </h2>
        </div>

        @if($notes->isEmpty())
            <div class="p-8 text-center bg-gray-50 dark:bg-gray-800/40 border border-dashed border-gray-200 dark:border-gray-700 rounded-2xl text-gray-500 dark:text-gray-400 text-sm">
                {{ __('This folder is empty. No notes found.') }}
            </div>
        @else
            <!-- Сетка заметок -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($notes as $note)
                    <div class="relative group">
                        <!-- Сама карточка заметки -->
                        <x-note-card :note="$note" />

                        <!-- Кнопка быстрого предпросмотра -->
                        <button
                            type="button"
                            class="js-btn-preview absolute top-4 right-4 p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-200 dark:hover:border-indigo-800 shadow-sm transition opacity-0 group-hover:opacity-100 z-10"
                            data-title="{{ $note->name ?? __('Untitled Note') }}"
                            data-date="{{ $note->created_at->format('d.m.Y H:i') }}"
                            data-content="{{ $note->content }}"
                            title="{{ __('Quick Preview') }}"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>

            <!-- Блок кастомной пагинации -->
            <div class="mt-8 pagination-indigo">
                {{ $notes->links() }}
            </div>
        @endif
    </div>

    <!-- Модальное окно предпросмотра -->
    <div id="previewModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0 relative">
            <div class="fixed inset-0 bg-black/60 dark:bg-black/80 transition-opacity duration-300" onclick="closePreview()"></div>
            <div class="relative inline-block align-middle bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-700 z-10">
                <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-5">
                    <div class="flex justify-between items-start mb-4 gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 tracking-tight" id="modalTitle">{{ __('Note Title') }}</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" id="modalDate">{{ __('Date') }}</p>
                        </div>
                        <button type="button" onclick="closePreview()" class="p-1 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-300 border-t border-gray-100 dark:border-gray-700 pt-4 max-h-[50vh] overflow-y-auto whitespace-pre-wrap leading-relaxed" id="modalContent">
                        {{ __('Note content...') }}
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 flex justify-end rounded-b-2xl border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="closePreview()" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = document.getElementById('previewModal');
                const modalTitle = document.getElementById('modalTitle');
                const modalDate = document.getElementById('modalDate');
                const modalContent = document.getElementById('modalContent');

                document.querySelectorAll('.js-btn-preview').forEach(button => {
                    button.addEventListener('click', function () {
                        modalTitle.textContent = this.getAttribute('data-title');
                        modalDate.textContent = this.getAttribute('data-date');
                        modalContent.textContent = this.getAttribute('data-content');
                        modal.classList.remove('hidden');
                        document.body.classList.add('overflow-hidden');
                    });
                });
            });

            function closePreview() {
                document.getElementById('previewModal').classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        </script>
    @endpush

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
                border-color: #e5e7eb !important;
                color: #4b5563 !important;
            }
            .pagination-indigo nav a:hover {
                color: #4f46e5 !important;
                border-color: #6366f1 !important;
                background-color: #f5f3ff !important;

            }
            .pagination-indigo nav [aria-current="page"] span,
            .pagination-indigo nav span[aria-current="page"] {
                background-color: #4f46e5 !important; /* bg-indigo-600 */
                border-color: #4f46e5 !important;
                color: #ffffff !important;
            }

            /* 3. Стили для ТЕМНОЙ темы (активируются через класс .dark на теге html) */
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
