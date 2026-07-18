<x-app-layout>
    <x-slot name="title">{{ __('Create Note') }}</x-slot>

    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">{{ __('Create New Folder') }}</h1>

        <form action="{{ route('folders.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Title') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="w-full px-4 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400 dark:placeholder-gray-500 @error('name') border-red-500 dark:border-red-400 @enderror"
                       placeholder="{{ __('Enter folder title...') }}" required>
                @error('name') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ __($message) }}</p> @enderror
            </div>

            <!-- Редактор EasyMDE через компонент -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Content') }}</label>
                <x-markdown-editor name="description" :value="old('description')" />
                @error('description') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ __($message) }}</p> @enderror
            </div>

            <!-- Кнопки управления -->
            <div class="flex justify-end gap-3 border-t border-gray-100 dark:border-gray-700/50 pt-6">
                <a href="{{ route('folders.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900 transition">
                    {{ __('Save Folder') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="title">{{ __('Create Note') }}</x-slot>

    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">{{ __('Create New Folder') }}</h1>

        <form action="{{ route('folders.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Title') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="w-full px-4 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400 dark:placeholder-gray-500 @error('name') border-red-500 dark:border-red-400 @enderror"
                       placeholder="{{ __('Enter folder title...') }}" required>
                @error('name') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ __($message) }}</p> @enderror
            </div>

            <!-- Редактор EasyMDE через компонент -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Content') }}</label>
                <x-markdown-editor name="description" :value="old('description')" />
                @error('description') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ __($message) }}</p> @enderror
            </div>

            <!-- Кнопки управления -->
            <div class="flex justify-end gap-3 border-t border-gray-100 dark:border-gray-700/50 pt-6">
                <a href="{{ route('folders.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900 transition">
                    {{ __('Save Folder') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
