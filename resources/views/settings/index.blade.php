<x-app-layout>
    <x-slot name="title">Account Settings</x-slot>

    <!-- Шапка страницы -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Settings</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your account preferences and application layout.</p>
    </div>



    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">

        <!-- Левая панель: Табы -->
        <nav class="flex flex-row md:flex-col gap-1 overflow-x-auto md:overflow-x-visible pb-3 md:pb-0 border-b border-gray-100 md:border-b-0 whitespace-nowrap">
            <!-- Таб Интерфейса -->
            <a href="{{ route('settings.show', 'interface') }}"
               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium rounded-xl transition {{ $currentTab === 'interface' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Interface
            </a>

            <!-- Таб Уведомлений -->
            <a href="{{ route('settings.show', 'notifications') }}"
               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium rounded-xl transition {{ $currentTab === 'notifications' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Notifications
            </a>

            <!-- Таб Приватности -->
            <a href="{{ route('settings.show', 'privacy') }}"
               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium rounded-xl transition {{ $currentTab === 'privacy' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Privacy & Security
            </a>

            <form action="{{ route('logout') }}" method="POST" class="block w-full">
                @csrf
                <button type="submit" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium rounded-xl text-gray-600 hover:bg-rose-50 hover:text-rose-600 transition w-full text-left">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>
        </nav>

        <!-- Правая панель: Формы контента -->
        <div class="md:col-span-3 bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

            <!-- ВКЛАДКА: ИНТЕРФЕЙС -->
            <!-- ================= ФОРМА: ИНТЕРФЕЙС ================= -->
            @if($currentTab === 'interface')
                <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Техническое поле таба -->
                    <input type="hidden" name="current_tab" value="interface">

                    <!-- ГАРАНТИРУЕМ НАЛИЧИЕ ВСЕХ ПОЛЕЙ ДЛЯ РЕКВЕСТА -->
                    <input type="hidden" name="timezone" value="{{ $settings->timezone ?? config('app.timezone', 'UTC') }}">
                    <input type="hidden" name="greeting" value="{{ $settings->greeting ?? 'Hello Boss' }}">

                    <div>
                        <h3 class="text-base font-bold text-gray-900 mb-1">Interface Preferences</h3>
                        <p class="text-xs text-gray-500">Customize how your application looks and feels.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                        <div>
                            <label for="theme" class="block text-sm font-medium text-gray-700 mb-1">Theme</label>
                            <select id="theme" name="theme" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                <option value="light" {{ old('theme', $settings->theme ?? '') === 'light' ? 'selected' : '' }}>Light Mode</option>
                                <option value="dark" {{ old('theme', $settings->theme ?? '') === 'dark' ? 'selected' : '' }}>Dark Mode</option>
                                <option value="system" {{ old('theme', $settings->theme ?? '') === 'system' ? 'selected' : '' }}>System Default</option>
                            </select>
                            @error('theme') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                            <select id="language" name="language" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                <option value="en" {{ old('language', $settings->language ?? '') === 'en' ? 'selected' : '' }}>English</option>
                                <option value="ru" {{ old('language', $settings->language ?? '') === 'ru' ? 'selected' : '' }}>Русский</option>
                            </select>
                            @error('language') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                            <select id="timezone" name="timezone" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition max-h-60">
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
                                    <optgroup label="{{ $regionName }}">
                                        @foreach(DateTimeZone::listIdentifiers($regionMask) as $tzIdentifier)
                                            <option value="{{ $tzIdentifier }}" {{ $currentTimezone === $tzIdentifier ? 'selected' : '' }}>
                                                {{ str_replace('_', ' ', substr($tzIdentifier, strlen($regionName) + 1)) ?: $tzIdentifier }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('timezone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="greeting" class="block text-sm font-medium text-gray-700 mb-1">Custom Greeting</label>
                            <input type="text"
                                   id="greeting"
                                   name="greeting"
                                   value="{{ old('greeting', $settings->greeting ?? 'Welcome back') }}"
                                   placeholder="e.g. Hello Boss"
                                   class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition"
                            >
                            @error('greeting') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm">
                            Save Changes
                        </button>
                    </div>
                </form>
            @endif

            <!-- ВКЛАДКА: УВЕДОМЛЕНИЯ -->
            @if($currentTab === 'notifications')
                <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="current_tab" value="notifications">

                    <!-- Секция типов уведомлений (уже была у вас) -->
                    <div>
                        <h3 class="text-base font-bold text-gray-900 mb-1">Notification Settings</h3>
                        <p class="text-xs text-gray-500">Choose what updates you want to receive.</p>
                    </div>

                    <div class="space-y-4 border-t border-gray-100 pt-4">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="hidden" name="notify_email" value="0">
                            <input type="checkbox" name="notify_email" value="1" {{ old('notify_email', $settings->notify_email ?? false) ? 'checked' : '' }} class="mt-1 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <span class="block text-sm font-medium text-gray-700 group-hover:text-gray-900 transition">Email Digests</span>
                                <span class="block text-xs text-gray-400">Receive weekly summaries of your activity and notes.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="hidden" name="subscribe_updates" value="0">
                            <input type="checkbox" name="subscribe_updates" value="1" {{ old('subscribe_updates', $settings->subscribe_updates ?? false) ? 'checked' : '' }} class="mt-1 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <span class="block text-sm font-medium text-gray-700 group-hover:text-gray-900 transition">Browser Push Notifications</span>
                                <span class="block text-xs text-gray-400">Get instant alerts on actions inside shared folders.</span>
                            </div>
                        </label>
                    </div>

                    <!-- НОВАЯ СЕКЦИЯ: Выбор каналов доставки уведомлений -->
                    <div class="pt-6 border-t border-gray-100">
                        <h3 class="text-base font-bold text-gray-900 mb-1">Delivery Channels</h3>
                        <p class="text-xs text-gray-500 mb-4">Where should we deliver your instant alerts?</p>

                        {{-- Страховка от пустого сабмита: если все чекбоксы сняты, отправится пустой массив --}}
                        <input type="hidden" name="notification_channels" value="">

                        <div class="space-y-4">
                            @foreach(\App\Enums\NotificationChannel::cases() as $channel)
                                @php
                                    $isChecked = in_array($channel->value, old('notification_channels', $settings->notification_channels));
                                @endphp

                                <label class="flex items-start gap-3 cursor-pointer group">
                                    <input type="checkbox"
                                           name="notification_channels[]"
                                           value="{{ $channel->value }}"
                                           {{ $isChecked ? 'checked' : '' }}
                                           class="mt-1 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <span class="block text-sm font-medium text-gray-700 group-hover:text-gray-900 transition">
                                            {{ ucfirst($channel->value) }}
                                        </span>
                                    </div>
                                </label>
                            @endforeach

                        </div>
                        @error('notification_channels')
                        <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                        @error('notification_channels.*')
                        <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm">
                            Save Preferences
                        </button>
                    </div>
                </form>
            @endif

            <!-- ВКЛАДКА: ПРИВАТНОСТЬ -->
            @if($currentTab === 'privacy')
                <div class="space-y-8">

                    <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="current_tab" value="privacy">
                        <div>
                            <h3 class="text-base font-bold text-gray-900 mb-1">Privacy Options</h3>
                            <p class="text-xs text-gray-500">Configure your account isolation level and public data visibility.</p>
                        </div>

                        <div class="space-y-4 border-t border-gray-100 pt-4">
                            <input type="hidden" name="profile_visibility" value="0">

                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="hidden" name="profile_visibility" value="0">
                                <input type="checkbox"
                                       name="profile_visibility"
                                       id="profile_visibility"
                                       value="1"
                                       {{ old('profile_visibility', $settings->profile_visibility ?? true) ? 'checked' : ''}}
                                       class="mt-1 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                >
                                <div>
                                    <span class="block text-sm font-medium text-gray-700 group-hover:text-gray-900 transition">Private Workspace</span>
                                    <span class="block text-xs text-gray-400">Restricts search engine indexation. Only invited accounts can inspect elements inside root folders.</span>
                                </div>
                            </label>
                            @error('profile_visibility')
                            <p class="text-xs text-red-600 pl-7 mt-1">{{ $message }}</p>
                            @enderror
                            <div class="pt-6 border-t border-gray-100">
                                <h3 class="text-base font-bold text-gray-900 mb-1">Telegram Integration</h3>
                                <p class="text-xs text-gray-500 mb-4">Connect your Telegram account to receive instant notifications.</p>

                                @if(auth()->user()->telegram_chat_id)
                                    <div class="flex items-center justify-between bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                                        <div>
                                            <span class="block text-sm font-medium text-indigo-800">Telegram is connected</span>
                                            <span class="block text-xs text-green-600">You can now enable the Telegram delivery channel in Notification tab.</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100">
                                        <div>
                                            <span class="block text-sm font-medium text-gray-700">Link Telegram Account</span>
                                            <span class="block text-xs text-gray-400">Click the button and press "Start" in the opened bot.</span>
                                        </div>
                                        <!-- Ссылка ведет на наш промежуточный роут, который сгенерирует и запишет токен -->
                                        <a href="{{ route('telegram.connect') }}" target="_blank" rel="noopener" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm flex items-center gap-1.5">
                                            Connect Bot
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="pt-6 border-t border-gray-100">
                                <h3 class="text-base font-bold text-gray-900 mb-1">Two-Factor Authentication</h3>
                                <p class="text-xs text-gray-500 mb-4">Add an extra layer of security to your account.</p>

                                @if($settings->two_factor_enabled)
                                    <div class="flex items-center justify-between bg-green-50 p-4 rounded-xl border border-green-100">
                                        <div>
                                            <span class="block text-sm font-medium text-green-800">2FA is currently active</span>
                                            <span class="block text-xs text-green-600">Your account is secured with time-based one-time passwords.</span>
                                        </div>
                                        <form action="{{ route('2fa.disable') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                                Disable 2FA
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100">
                                        <div>
                                            <span class="block text-sm font-medium text-gray-700">Two-Factor Authentication</span>
                                            <span class="block text-xs text-gray-400">Protect your account using an authenticator app.</span>
                                        </div>
                                        <a href="{{ route('2fa.setup') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm">
                                            Enable 2FA
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <!-- Статус верификации сессии -->
                            <div class="p-3.5 bg-amber-50/60 border border-amber-100 rounded-xl text-xs text-amber-800 flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <span class="font-semibold block mb-0.5">Session Verified</span>
                                    High-security mode is active. You confirmed your password less than 10 minutes ago. Access token is temporarily unlocked.
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition shadow-sm">
                                Save Privacy Settings
                            </button>
                        </div>
                    </form>

                    <!-- Секция 2: Изменение пароля -->
                    <form action="{{ route('settings.update', 'privacy') }}" method="POST" class="space-y-6 pt-6 border-t border-gray-100">
                        @csrf
                        @method('PATCH')

                        <div>
                            <h3 class="text-base font-bold text-gray-900 mb-1">Update Password</h3>
                            <p class="text-xs text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-4 max-w-xl">
                            <!-- Текущий пароль -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                <input type="password"
                                       id="current_password"
                                       name="current_password"
                                       autocomplete="current-password"
                                       class="w-full px-3 py-2.5 bg-white border @error('current_password') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-1 transition"
                                >
                                @error('current_password')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Новый пароль -->
                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <input type="password"
                                       id="new_password"
                                       name="new_password"
                                       autocomplete="new-password"
                                       class="w-full px-3 py-2.5 bg-white border @error('new_password') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-1 transition"
                                >
                                @error('new_password')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Подтверждение пароля -->
                            <div>
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <input type="password"
                                       id="new_password_confirmation"
                                       name="new_password_confirmation"
                                       autocomplete="new-password"
                                       class="w-full px-3 py-2.5 bg-white border @error('new_password_confirmation') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-1 transition"
                                >
                                @error('new_password_confirmation')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition shadow-sm">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
