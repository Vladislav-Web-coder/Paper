<x-app-layout>
    <x-slot name="title">Create Note</x-slot>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Create New Note</h1>

        <form action="{{ route('notes.store') }}" method="POST">
            @csrf

            <!-- Название заметки -->
            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                       placeholder="Enter note title..." required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Выбор Папок -->
            <div class="mb-5">
                <label for="folders" class="block text-sm font-medium text-gray-700 mb-2">Folders</label>
                <select name="folders[]" id="folders" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" multiple>
                    @foreach($folders as $id => $name)
                        <option value="{{ $id }}" {{ in_array($id, old('folders', [])) ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <p class="text-gray-400 text-xs mt-1">Hold Cmd/Ctrl to select multiple folders.</p>
            </div>

            <!-- Выбор Тегов -->
            <div class="mb-5">
                <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                <select name="tags[]" id="tags" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" multiple>
                    @foreach($tags as $id => $name)
                        <option value="{{ $id }}" {{ in_array($id, old('tags', [])) ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Редактор EasyMDE через компонент -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                <x-markdown-editor name="content" :value="old('content')" />
                @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Кнопки управления -->
            <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('notes.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200">
                    Save Note
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
