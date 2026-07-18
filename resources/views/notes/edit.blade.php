<x-app-layout>
    <x-slot name="title">{{ __('Edit Note: :name', ['name' => $note->name]) }}</x-slot>

    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">{{ __('Edit Note') }}</h1>

        <form action="{{ route('notes.update', $note->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Title') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name', $note->name) }}"
                       class="w-full px-4 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400 dark:placeholder-gray-500 @error('name') border-red-500 dark:border-red-400 @enderror" required>
                @error('name') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ __($message) }}</p> @enderror
            </div>

            <!-- Блок папок -->
            <div class="mb-5 p-4 bg-gray-50/50 dark:bg-gray-900/30 border border-gray-200 dark:border-gray-700 rounded-xl">
                <label class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">{{ __('Folders') }}</label>

                @if(!empty($folders) && count($folders) > 0)
                    <div class="mb-3">
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1.5">{{ __('Select existing folders:') }}</label>
                        <!-- h-auto max-h-36 адаптируется к количеству папок -->
                        <div class="w-full h-auto max-h-36 px-3 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg overflow-y-auto space-y-1.5 shadow-inner">
                            @foreach($folders as $id => $name)
                                @php
                                    $defaultFolders = $note->folders->pluck('name')->toArray();
                                    $isFolderChecked = in_array($name, old('folder_name', $defaultFolders));
                                @endphp
                                <label class="flex items-center space-x-2.5 p-1 hover:bg-gray-50 dark:hover:bg-gray-800 rounded cursor-pointer transition select-none">
                                    <input type="checkbox" name="folder_name[]" value="{{ $name }}"
                                           {{ $isFolderChecked ? 'checked' : '' }}
                                           class="h-4 w-4 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800">
                                    <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="mb-3 p-3 bg-white dark:bg-gray-900 border border-dashed border-gray-200 dark:border-gray-700 rounded-lg text-center text-xs text-gray-400 dark:text-gray-500">
                        {{ __("You don't have any folders yet. You can create your first folders using the field below!") }}
                    </div>
                @endif

                <div>
                    <label for="new_folders" class="block text-xs text-gray-500 dark:text-gray-400 mb-1.5">{{ __('Or type new folders:') }}</label>
                    <input type="text" name="new_folders" id="new_folders"
                           value="{{ old('new_folders') }}"
                           class="w-full px-4 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400 dark:placeholder-gray-500"
                           placeholder="{{ __('NewFolder1, NewFolder2 (separate with commas)') }}">
                </div>
                @error('folder_name.*') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ __($message) }}</p> @enderror
            </div>

            <!-- Блок тегов -->
            <div class="mb-5 p-4 bg-gray-50/50 dark:bg-gray-900/30 border border-gray-200 dark:border-gray-700 rounded-xl">
                <label class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">{{ __('Tags') }}</label>

                @if(!empty($tags) && count($tags) > 0)
                    <div class="mb-3">
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1.5">{{ __('Select existing tags:') }}</label>
                        <!-- h-auto max-h-36 адаптируется к количеству тегов -->
                        <div class="w-full h-auto max-h-36 px-3 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg overflow-y-auto space-y-1.5 shadow-inner">
                            @foreach($tags as $id => $name)
                                @php
                                    $defaultTags = $note->tags->pluck('name')->toArray();
                                    $isTagChecked = in_array($name, old('tags_name', $defaultTags));
                                @endphp
                                <label class="flex items-center space-x-2.5 p-1 hover:bg-indigo-50/30 dark:hover:bg-indigo-950/40 rounded cursor-pointer transition select-none">
                                    <input type="checkbox" name="tags_name[]" value="{{ $name }}"
                                           {{ $isTagChecked ? 'checked' : '' }}
                                           class="h-4 w-4 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800">
                                    <span class="text-sm text-gray-700 dark:text-gray-300 font-medium"><span class="text-indigo-500 dark:text-indigo-400">#</span>{{ $name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="mb-3 p-3 bg-white dark:bg-gray-900 border border-dashed border-gray-200 dark:border-gray-700 rounded-lg text-center text-xs text-gray-400 dark:text-gray-500">
                        {{ __("You don't have any tags yet. You can create your first tags using the field below!") }}
                    </div>
                @endif

                <div>
                    <label for="new_tags" class="block text-xs text-gray-500 dark:text-gray-400 mb-1.5">{{ __('Or type new tags:') }}</label>
                    <input type="text" name="new_tags" id="new_tags"
                           value="{{ old('new_tags') }}"
                           class="w-full px-4 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400 dark:placeholder-gray-500"
                           placeholder="{{ __('urgent, personal (separate with commas)') }}">
                </div>
                @error('tags_name.*') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ __($message) }}</p> @enderror
            </div>

            <!-- Content -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Content') }}</label>
                <x-markdown-editor name="content" :value="old('content', $note->content)" />
                @error('content') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ __($message) }}</p> @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 border-t border-gray-100 dark:border-gray-700/50 pt-6">
                <a href="{{ route('notes.show', $note->id) }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900 transition">
                    {{ __('Update Note') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
