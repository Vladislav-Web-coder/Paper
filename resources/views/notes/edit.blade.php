<x-app-layout>
    <x-slot name="title">Edit Note: {{ $note->name }}</x-slot>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Note</h1>

        <form action="{{ route('notes.update', $note->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Название заметки -->
            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <input type="text" name="name" id="name" value="{{ old('name', $note->name) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Выбор Папок -->
            <div class="mb-5">
                <label for="folders" class="block text-sm font-medium text-gray-700 mb-2">Folders</label>
                <select name="folders[]" id="folders" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" multiple>
                    @foreach($folders as $id => $name)
                        <option value="{{ $id }}" {{ in_array($id, old('folders', $note->folders->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Выбор Тегов -->
            <div class="mb-5">
                <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                <select name="tags[]" id="tags" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" multiple>
                    @foreach($tags as $id => $name)
                        <option value="{{ $id }}" {{ in_array($id, old('tags', $note->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Редактор EasyMDE через компонент -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                <x-markdown-editor name="content" :value="old('content', $note->content)" />
                @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Кнопки управления -->
            <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('notes.show', $note->id) }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200">
                    Update Note
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
