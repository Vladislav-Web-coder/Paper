<x-layout>
    <x-slot name="title">{{ __('Folder') }}: {{ $folder->name }}</x-slot>

    <!-- Шапка страницы и действия -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('folders.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 flex items-center gap-1 mb-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                {{ __('Back to folders') }}
            </a>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
                <span class="text-gray-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </span>
                {{ $folder->name }}
            </h1>
            @if($folder->description)
                <div class="prose prose-indigo max-w-none text-gray-800 leading-relaxed">
                    {!! Str::markdown(e($folder->description)) !!}
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto self-end md:self-center">
            <a href="{{ route('notes.create', ['folderId' => $folder->id]) }}" class="whitespace-nowrap bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm">
                {{ __('+ Create note here') }}
            </a>
        </div>
    </div>

    <!-- Основной контент страницы -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">
                {{ __('Notes in this folder') }}
                <span class="text-sm font-normal text-gray-400 ml-1">({{ $notes->total() }})</span>
            </h2>
        </div>

        @if($notes->isEmpty())
            <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-gray-500 text-sm">
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
                            class="js-btn-preview absolute top-4 right-4 p-2 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-indigo-600 hover:border-indigo-200 shadow-sm transition opacity-0 group-hover:opacity-100 z-10"
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

            <!-- Задний фон -->
            <div class="fixed inset-0 bg-black/60 transition-opacity duration-300" onclick="closePreview()"></div>

            <!-- Контент модального окна -->
            <div class="relative inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-gray-100 z-10">
                <div class="bg-white px-6 pt-6 pb-5">
                    <div class="flex justify-between items-start mb-4 gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 tracking-tight" id="modalTitle">{{ __('Note Title') }}</h3>
                            <p class="text-xs text-gray-400 mt-1" id="modalDate">{{ __('Date') }}</p>
                        </div>
                        <button type="button" onclick="closePreview()" class="p-1 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-50 transition shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Тело заметки -->
                    <div class="text-sm text-gray-600 border-t border-gray-100 pt-4 max-h-[50vh] overflow-y-auto whitespace-pre-wrap leading-relaxed" id="modalContent">
                        {{ __('Note content...') }}
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end rounded-b-2xl border-t border-gray-100">
                    <button type="button" onclick="closePreview()" class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50 transition shadow-sm">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
<!-- Скрипт управления модальным окном -->
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
