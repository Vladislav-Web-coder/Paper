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
                    placeholder: 'Type your note here (Markdown supported)...',
                    status: false,
                    forceSync: true,
                    toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "preview", "side-by-side", "fullscreen"]
                });
            }
        });
    </script>
@endpush
