@props(['note'])

<div class="relative group p-5 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-100 transition duration-200 flex flex-col justify-between min-h-[190px]">

    <div class="mb-4">
        <div class="pr-20">
            <a href="{{ route('notes.show', $note->id) }}" class="block group/title">
                <h4 class="font-bold text-gray-900 group-hover/title:text-indigo-600 transition line-clamp-1 tracking-tight text-base">
                    {{ $note->name ?? __('Untitled Note') }}
                </h4>
            </a>

            <p class="text-xs text-gray-400 mt-1 font-medium">
                {{ $note->created_at->format('d.m.Y H:i') }}
            </p>
        </div>

        <!-- Очистка текста от символов Markdown разметки -->
        <p class="text-sm text-gray-500 mt-3 line-clamp-3 leading-relaxed">
            @if(!empty($note->content))
                {{ trim(preg_replace('/([\#\*\_\[\]\-\>\`\~])/', '', $note->content)) }}
            @else
                {{ __('No additional content...') }}
            @endif
        </p>
    </div>

    <div class="space-y-3 pt-3 border-t border-gray-50 text-xs text-gray-400">
        <!-- ОТОБРАЖЕНИЕ ТЕГОВ -->
        @if($note->tags && $note->tags->isNotEmpty())
            <div class="flex flex-wrap gap-1.5 max-h-[48px] overflow-hidden">
                @foreach($note->tags as $tag)
                    <a href="{{ route('tags.show', $tag->id) }}" class="inline-flex items-center px-2 py-0.5 bg-gray-50 border border-gray-150 rounded-full font-medium text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition text-[11px]">
                        <span class="text-indigo-400 font-bold mr-0.5">#</span>{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <!-- ОТОБРАЖЕНИЕ ПАПОК -->
        <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5 font-medium">
            @if($note->folders && $note->folders->isNotEmpty())
                @foreach($note->folders as $folder)
                    <a href="{{ route('folders.show', $folder->id) }}" class="flex items-center gap-1.5 hover:text-indigo-600 transition text-gray-500">
                        <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        <span class="max-w-[120px] truncate">{{ $folder->name }}</span>
                    </a>
                @endforeach
            @else
                <span class="flex items-center gap-1.5 font-normal italic text-gray-300">
                    <svg class="w-3.5 h-3.5 text-gray-200 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    {{ __('Without folder') }}
                </span>
            @endif
        </div>
    </div>

    <div class="absolute top-4 right-4 flex items-center gap-1.5 z-10">
        <form action="{{ route('notes.pin', $note->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="p-2 border rounded-xl shadow-sm transition duration-200 opacity-0 group-hover:opacity-100 {{ $note->is_pinned ? 'bg-indigo-50 border-indigo-200 text-indigo-600 hover:bg-indigo-100 hover:text-indigo-700' : 'bg-white border-gray-200 text-gray-400 hover:text-indigo-600 hover:border-indigo-200' }}">
                <svg class="w-4 h-4 transition duration-200 {{ $note->is_pinned ? 'fill-indigo-600 text-indigo-600 rotate-45' : 'fill-none text-gray-400 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.963 14.804A4.001 4.001 0 007.75 19.137M12 14.502c.333.115.682.176 1.037.176.772 0 1.503-.277 2.074-.775m-3.111.6c.015.424.161.83.421 1.157m3.111-2.157c.307-.406.49-.912.49-1.46c0-1.218-.895-2.22-2.073-2.41m2.073 2.41c-.247.327-.58.583-.963.738m0 0A4.002 4.002 0 0112 7.502M15.5 14c.732 0 1.403-.26 1.926-.69M15.5 14V7.5M12 7.5c0-.663.537-1.2 1.2-1.2.536 0 .984.35 1.137.83M12 7.5v6.5m3.5-6.5C15.5 6.67 14.83 6 14 6" />
                </svg>
            </button>
        </form>

        <button type="button" class="js-btn-preview p-2 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-indigo-600 hover:border-indigo-200 shadow-sm transition duration-200 opacity-0 group-hover:opacity-100"
                data-title="{{ $note->name ?? __('Untitled Note') }}"
                data-date="{{ $note->created_at->format('d.m.Y H:i') }}"
                data-content="{{ $note->content ?? __('No additional content...') }}"
                title="{{ __('Quick Preview') }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </button>
    </div>
</div>


<div id="previewModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0 relative">
        <div class="fixed inset-0 bg-black/60 transition-opacity duration-300" onclick="closePreview()"></div>
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
                <div class="text-sm text-gray-600 border-t border-gray-100 pt-4 max-h-[50vh] overflow-y-auto whitespace-pre-wrap leading-relaxed prose prose-indigo max-w-none" id="modalContent">
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

@push('scripts')
    @push('scripts')
        <script>
            if (typeof window.closePreview !== 'function') {
                window.closePreview = function() {
                    const modal = document.getElementById('previewModal');
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                    document.body.style.overflow = '';
                };
            }

            // Инициализируем слушатели кнопок и клавиш строго один раз на страницу
            if (typeof window.notePreviewScriptsInitialized === 'undefined') {
                window.notePreviewScriptsInitialized = true;

                // Функция привязки событий к кнопкам превью
                const initNotePreviews = () => {
                    document.querySelectorAll('.js-btn-preview').forEach(button => {
                        // Проверяем, чтобы не вешать слушатель дважды
                        if (button.dataset.listenerAttached) return;
                        button.dataset.listenerAttached = 'true';

                        button.addEventListener('click', function (e) {
                            e.preventDefault();

                            const title = this.dataset.title || 'Untitled Note';
                            const date = this.dataset.date || '';
                            const content = this.dataset.content || '';

                            // Заполняем текстовые поля в модалке
                            document.getElementById('modalTitle').innerText = title;
                            document.getElementById('modalDate').innerText = date;

                            const modalContent = document.getElementById('modalContent');

                            // Парсим Markdown через скомпилированную в npm библиотеку window.marked
                            if (window.marked && typeof window.marked.parse === 'function') {
                                modalContent.innerHTML = window.marked.parse(content);
                            } else {
                                // Если marked еще не собрался, выводим как обычный текст
                                modalContent.innerText = content || 'No additional content...';
                            }

                            // Показываем модальное окно
                            const modal = document.getElementById('previewModal');
                            if (modal) {
                                modal.classList.remove('hidden');
                                document.body.style.overflow = 'hidden';
                            }
                        });
                    });
                };

                // Запускаем при первой загрузке страницы
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initNotePreviews);
                } else {
                    initNotePreviews();
                }

                // Слушатель закрытия модалки по клавише Escape
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        window.closePreview();
                    }
                });
            }
        </script>
    @endpush
@endpush
