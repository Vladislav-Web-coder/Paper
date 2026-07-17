<x-app-layout>
    <x-slot name="title">{{ __('Create Note') }}</x-slot>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Create New Note') }}</h1>

        <form action="{{ route('notes.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Title') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                       placeholder="{{ __('Enter note title...') }}" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p> @enderror
            </div>

            <div class="mb-5 p-4 bg-gray-50/50 border border-gray-200 rounded-xl">
                <label class="block text-sm font-semibold text-gray-900 mb-3">{{ __('Folders') }}</label>

                @if(!empty($folders) && count($folders) > 0)
                    <div class="mb-3">
                        <label class="block text-xs text-gray-500 mb-1.5">{{ __('Select existing folders:') }}</label>
                        <div class="w-full h-36 px-3 py-2 bg-white border border-gray-300 rounded-lg overflow-y-auto space-y-1.5 shadow-inner">
                            @foreach($folders as $id => $name)
                                <label class="flex items-center space-x-2.5 p-1 hover:bg-gray-50 rounded cursor-pointer transition select-none">
                                    <input type="checkbox" name="folder_name[]" value="{{ $name }}"
                                           {{ in_array($name, old('folder_name', request('folderId') && isset($folders[request('folderId')]) && $folders[request('folderId')] === $name ? [$name] : [])) ? 'checked' : '' }}
                                           class="h-4 w-4 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <span class="text-sm text-gray-700 truncate">{{ $name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="mb-3 p-3 bg-white border border-dashed border-gray-200 rounded-lg text-center text-xs text-gray-400">
                        {{ __("You don't have any folders yet. You can create your first folders using the field below!") }}
                    </div>
                @endif

                <div>
                    <label for="new_folders" class="block text-xs text-gray-500 mb-1.5">{{ __('Or type new folders:') }}</label>
                    <input type="text" name="new_folders" id="new_folders"
                           value="{{ old('new_folders') }}"
                           class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="{{ __('NewFolder1, NewFolder2 (separate with commas)') }}">
                </div>
                @error('folder_name.*') <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p> @enderror
            </div>

            <div class="mb-5 p-4 bg-gray-50/50 border border-gray-200 rounded-xl">
                <label class="block text-sm font-semibold text-gray-900 mb-3">{{ __('Tags') }}</label>

                @if(!empty($tags) && count($tags) > 0)
                    <div class="mb-3">
                        <label class="block text-xs text-gray-500 mb-1.5">{{ __('Select existing tags:') }}</label>
                        <div class="w-full h-36 px-3 py-2 bg-white border border-gray-300 rounded-lg overflow-y-auto space-y-1.5 shadow-inner">
                            @foreach($tags as $id => $name)
                                <label class="flex items-center space-x-2.5 p-1 hover:bg-indigo-50/30 rounded cursor-pointer transition select-none">
                                    <input type="checkbox" name="tags_name[]" value="{{ $name }}"
                                           {{ in_array($name, old('tags_name', [])) ? 'checked' : '' }}
                                           class="h-4 w-4 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <span class="text-sm text-gray-700 font-medium">#{{ $name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="mb-3 p-3 bg-white border border-dashed border-gray-200 rounded-lg text-center text-xs text-gray-400">
                        {{ __("You don't have any tags yet. You can create your first tags using the field below!") }}
                    </div>
                @endif

                <div>
                    <label for="new_tags" class="block text-xs text-gray-500 mb-1.5">{{ __('Or type new tags:') }}</label>
                    <input type="text" name="new_tags" id="new_tags"
                           value="{{ old('new_tags') }}"
                           class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="{{ __('urgent, personal (separate with commas)') }}">
                </div>
                @error('tags_name.*') <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Content') }}</label>
                <x-markdown-editor name="content" :value="old('content')" />
                @error('content') <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('notes.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200">
                    {{ __('Save Note') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
