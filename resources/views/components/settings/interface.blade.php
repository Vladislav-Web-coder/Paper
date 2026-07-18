@props(['settings'])

<form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
    @csrf
    @method('PATCH')

    <input type="hidden" name="current_tab" value="interface">

    <div>
        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-1">{{ __('Interface Preferences') }}</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Customize how your application looks and feels.') }}</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-gray-100 dark:border-gray-700/50 pt-4">
        <div>
            <label for="theme" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Theme') }}</label>
            <select id="theme" name="theme" onchange="previewTheme(this.value)" class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                <option value="light" {{ old('theme', $settings->theme ?? '') === 'light' ? 'selected' : '' }}>{{ __('Light Mode') }}</option>
                <option value="dark" {{ old('theme', $settings->theme ?? '') === 'dark' ? 'selected' : '' }}>{{ __('Dark Mode') }}</option>
                <option value="system" {{ old('theme', $settings->theme ?? '') === 'system' ? 'selected' : '' }}>{{ __('System Default') }}</option>
            </select>
            @error('theme') <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="language" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Language') }}</label>
            <select id="language" name="language" class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                <option value="en" {{ old('language', $settings->language ?? '') === 'en' ? 'selected' : '' }}>English</option>
                <option value="ru" {{ old('language', $settings->language ?? '') === 'ru' ? 'selected' : '' }}>Русский</option>
            </select>
            @error('language') <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Timezone') }}</label>
            <select id="timezone" name="timezone" class="w-full px-3 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition max-h-60">
                @php
                    $currentTimezone = old('timezone', $settings->timezone ?? config('app.timezone', 'UTC'));
                    $regions = [
                        'Africa' => DateTimeZone::AFRICA,
                        'America' => DateTimeZone::AMERICA,
                        'Antarctica' => DateTimeZone::ANTARCTICA,
                        'Asia' => DateTimeZone::ASIA,
                        'Atlantic' => DateTimeZone::ATLANTIC,
                        'Australia' => DateTimeZone::AUSTRALIA,
                        'Europe' => DateTimeZone::EUROPE,
                        'Indian' => DateTimeZone::INDIAN,
                        'Pacific' => DateTimeZone::PACIFIC,
                        'UTC' => DateTimeZone::UTC
                    ];
                @endphp

                @foreach($regions as $regionName => $regionMask)
                    <optgroup label="{{ __($regionName) }}" class="bg-white dark:bg-gray-800">
                        @foreach(DateTimeZone::listIdentifiers($regionMask) as $tzIdentifier)
                            <option value="{{ $tzIdentifier }}" {{ $currentTimezone === $tzIdentifier ? 'selected' : '' }}>
                                {{ str_replace('_', ' ', substr($tzIdentifier, strlen($regionName) + 1)) ?: $tzIdentifier }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            @error('timezone') <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="greeting" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Custom Greeting') }}</label>
            <input type="text"
                   id="greeting"
                   name="greeting"
                   value="{{ old('greeting', $settings->greeting ?? 'Welcome back') }}"
                   placeholder="{{ __('e.g. Hello Boss') }}"
                   class="w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition placeholder-gray-400 dark:placeholder-gray-500"
            >
            @error('greeting') <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700/50">
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm w-full sm:w-auto">
            {{ __('Save Changes') }}
        </button>
    </div>
</form>

@push('scripts')
    <script>
        function previewTheme(theme) {
            const html = document.documentElement;

            if (theme === 'dark') {
                html.classList.add('dark');
            } else if (theme === 'light') {
                html.classList.remove('dark');
            } else if (theme === 'system') {
                // Проверяем системные настройки ОС
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }
            }
        }
    </script>
@endpush
