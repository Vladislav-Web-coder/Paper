@props([
    'name' => 'content',
    'value' => '',
    'height' => '350px'
])

<div class="markdown-editor-wrapper">
    <textarea name="{{ $name }}" id="editor-{{ $name }}">{{ $value }}</textarea>
</div>

@push('styles')
    <style>
        /* --- СВЕТЛАЯ ТЕМА --- */
        .EasyMDEContainer .CodeMirror {
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            border-color: #D1D5DB;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            min-height: {{ $height }};
            height: {{ $height }};
        }
        .EasyMDEContainer .editor-toolbar {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            border-color: #D1D5DB;
            background-color: #F9FAFB;
        }

        /* --- ТЕМНАЯ ТЕМА (активируется классом .dark на теге <html>) --- */
        .dark .EasyMDEContainer .CodeMirror {
            background-color: #111827 !important; /* bg-gray-900 */
            color: #f3f4f6 !important;            /* text-gray-100 */
            border-color: #374151 !important;     /* border-gray-700 */
        }

        /* Окно превью встроенного редактора */
        .dark .EasyMDEContainer .editor-preview {
            background-color: #111827 !important;
            color: #f3f4f6 !important;
        }

        /* Тулбар управления форматированием */
        .dark .EasyMDEContainer .editor-toolbar {
            background-color: #1f2937 !important; /* bg-gray-800 */
            border-color: #374151 !important;     /* border-gray-700 */
        }

        /* Кнопки в тулбаре */
        .dark .EasyMDEContainer .editor-toolbar button {
            color: #9ca3af !important;            /* text-gray-400 */
        }

        .dark .EasyMDEContainer .editor-toolbar button:hover,
        .dark .EasyMDEContainer .editor-toolbar button.active {
            background-color: #374151 !important; /* bg-gray-700 */
            color: #ffffff !important;
        }

        /* Разделительные линии в тулбаре */
        .dark .EasyMDEContainer .editor-toolbar .separator {
            border-left-color: #374151 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const element = document.getElementById('editor-{{ $name }}');
            if (element && window.EasyMDE) {
                new window.EasyMDE({
                    element: element,
                    autoDownloadFontAwesome: true,
                    spellChecker: false,
                    placeholder: 'Type here (Markdown supported)...',
                    status: false,
                    forceSync: true,
                    toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "preview", "side-by-side", "fullscreen"]
                });
            }
        });
    </script>
@endpush
