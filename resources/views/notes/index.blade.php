<x-layout>
    <x-slot name="title">My Notes</x-slot>

    <!-- Шапка страницы и поиск -->
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

    <!-- Основной контент страницы -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">
                {{ request('search') ? 'Search results' : 'All notes' }}
                <span class="text-sm font-normal text-gray-400 ml-1">({{ $notes->total() }})</span>
            </h2>
        </div>

        @if($notes->isEmpty())
            <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-gray-500 text-sm">
                Notes not found.
            </div>
        @else
            <!-- Сетка заметок -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($notes as $note)
                    <div class="relative group">
                        <x-note-card :note="$note" />
                        <!-- Блок управления в верхнем правом углу карточки -->
                        <div class="absolute top-4 right-4 flex items-center gap-1.5 z-10">

                            <!-- Кнопка закрепления (Pin) -->
                            <form action="{{ route('notes.pin', $note->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button
                                    type="submit"
                                    class="p-2 border rounded-xl shadow-sm transition {{ $note->is_pinned ? 'bg-indigo-50 border-indigo-200 text-indigo-600 opacity-100' : 'bg-white border-gray-200 text-gray-400 hover:text-indigo-600 hover:border-indigo-200 opacity-0 group-hover:opacity-100' }}"
                                    title="{{ $note->is_pinned ? 'Unpin note' : 'Pin note' }}"
                                >
                                    <svg class="w-4 h-4 {{ $note->is_pinned ? 'fill-current rotate-45' : 'fill-none' }} transition duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </button>
                            </form>
                            <!-- Кнопка быстрого предпросмотра -->
                            <button
                                type="button"
                                class="js-btn-preview p-2 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-indigo-600 hover:border-indigo-200 shadow-sm transition opacity-0 group-hover:opacity-100"
                                data-title="{{ $note->name }}"
                                data-date="{{ $note->created_at->format('d.m.Y H:i') }}"
                                data-content="{{ $note->content }}"
                                title="Quick Preview"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
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

            <!-- Задний фон: красивое полупрозрачное черное затемнение -->
            <div class="fixed inset-0 bg-black/60 transition-opacity duration-300" onclick="closePreview()"></div>

            <!-- Контент модального окна (Гарантированно четкий и контрастный) -->
            <div class="relative inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-gray-100 z-10">
                <div class="bg-white px-6 pt-6 pb-5">
                    <div class="flex justify-between items-start mb-4 gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 tracking-tight" id="modalTitle">Note Title</h3>
                            <p class="text-xs text-gray-400 mt-1" id="modalDate">Date</p>
                        </div>
                        <button type="button" onclick="closePreview()" class="p-1 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-50 transition shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Тело заметки -->
                    <div class="text-sm text-gray-600 border-t border-gray-100 pt-4 max-h-[50vh] overflow-y-auto whitespace-pre-wrap leading-relaxed" id="modalContent">
                        Note content...
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end rounded-b-2xl border-t border-gray-100">
                    <button type="button" onclick="closePreview()" class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50 transition shadow-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Скрипт управления модальным окном -->
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.js-btn-preview').forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault(); // Предотвращаем любые переходы, если кнопка внутри ссылки

                        const title = this.dataset.title;
                        const date = this.dataset.date;
                        const content = this.dataset.content;

                        document.getElementById('modalTitle').innerText = title;
                        document.getElementById('modalDate').innerText = date;
                        document.getElementById('modalContent').innerText = content;

                        const modal = document.getElementById('previewModal');
                        modal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                    });
                });
            });

            function closePreview() {
                const modal = document.getElementById('previewModal');
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closePreview();
                }
            });
        </script>
    @endpush

    <!-- Стили для пагинатора -->
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
