@props(['settings', 'sessions' => []])
<div class="space-y-8">
    <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        <input type="hidden" name="current_tab" value="privacy">
        <div>
            <h3 class="text-base font-bold text-gray-900 mb-1">{{ __('Privacy Options') }}</h3>
            <p class="text-xs text-gray-500">{{ __('Configure your account isolation level and public data visibility.') }}</p>
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
                    <span class="block text-sm font-medium text-gray-700 group-hover:text-gray-900 transition">{{ __('Private Workspace') }}</span>
                    <span class="block text-xs text-gray-400">{{ __('Restricts search engine indexation. Only invited accounts can inspect elements inside root folders.') }}</span>
                </div>
            </label>
            @error('profile_visibility')
            <p class="text-xs text-red-600 pl-7 mt-1">{{ $message }}</p>
            @enderror

            <div class="pt-6 border-t border-gray-100">
                <h3 class="text-base font-bold text-gray-900 mb-1">{{ __('Telegram Integration') }}</h3>
                <p class="text-xs text-gray-500 mb-4">{{ __('Connect your Telegram account to receive instant notifications.') }}</p>

                @if(auth()->user()->telegram_chat_id)
                    <div class="flex items-center justify-between bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                        <div>
                            <span class="block text-sm font-medium text-indigo-800">{{ __('Telegram is connected') }}</span>
                            <span class="block text-xs text-green-600">{{ __('You can now enable the Telegram delivery channel in Notification tab.') }}</span>
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div>
                            <span class="block text-sm font-medium text-gray-700">{{ __('Link Telegram Account') }}</span>
                            <span class="block text-xs text-gray-400">{{ __('Click the button and press "Start" in the opened bot.') }}</span>
                        </div>
                        <a href="{{ route('telegram.connect') }}" target="_blank" rel="noopener" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm flex items-center gap-1.5">
                            {{ __('Connect Bot') }}
                        </a>
                    </div>
                @endif
            </div>

            <div class="pt-6 border-t border-gray-100">
                <h3 class="text-base font-bold text-gray-900 mb-1">{{ __('Two-Factor Authentication') }}</h3>
                <p class="text-xs text-gray-500 mb-4">{{ __('Add an extra layer of security to your account.') }}</p>

                @if($settings->two_factor_enabled)
                    <div class="flex items-center justify-between bg-green-50 p-4 rounded-xl border border-green-100">
                        <div>
                            <span class="block text-sm font-medium text-green-800">{{ __('2FA is currently active') }}</span>
                            <span class="block text-xs text-green-600">{{ __('Your account is secured with time-based one-time passwords.') }}</span>
                        </div>
                        <form action="{{ route('2fa.disable') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                {{ __('Disable 2FA') }}
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div>
                            <span class="block text-sm font-medium text-gray-700">{{ __('Two-Factor Authentication') }}</span>
                            <span class="block text-xs text-gray-400">{{ __('Protect your account using an authenticator app.') }}</span>
                        </div>
                        <a href="{{ route('2fa.setup') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm">
                            {{ __('Enable 2FA') }}
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
                    <span class="font-semibold block mb-0.5">{{ __('Session Verified') }}</span>
                    {{ __('High-security mode is active. You confirmed your password less than 10 minutes ago. Access token is temporarily unlocked.') }}
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-100">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition shadow-sm">
                {{ __('Save Privacy Settings') }}
            </button>
        </div>
    </form>

    <div>
        <div class="mb-4">
            <h3 class="text-base font-bold text-gray-900 mb-1">{{ __('Email Address') }}</h3>
            <p class="text-xs text-gray-500">{{ __('Update your account email address. This requires 2FA verification.') }}</p>
        </div>

        <form action="{{ route('settings.email.change.request') }}" method="POST" class="space-y-4 border-t border-gray-100 pt-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="new_email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('New Email Address') }}</label>
                    <input type="email" id="new_email" name="new_email" value="{{ old('new_email') }}" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition" required>
                    @error('new_email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                @if(auth()->user()->settings->two_factor_enabled)
                    <div class="sm:col-span-2">
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">{{ __('2FA Verification Code') }}</label>
                        <input type="text" id="code" name="code" placeholder="123456" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition" required>
                        @error('code') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                @else
                    <div class="sm:col-span-2">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Current Password') }}</label>
                        <input type="password" id="current_password" name="current_password" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition" required>
                        @error('current_password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition">
                    {{ __('Update Email') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Секция: Активные сессии -->
    <div class="border-t border-gray-100 pt-6">
        <div class="mb-4">
            <h3 class="text-base font-bold text-gray-900 mb-1">{{ __('Active Sessions') }}</h3>
            <p class="text-xs text-gray-500">{{ __('Manage and logout your active sessions on other browsers and devices.') }}</p>
        </div>

        <!-- Список устройств -->
        <div class="space-y-3 mb-6">
            @foreach($sessions as $session)
                <div class="flex items-center justify-between p-3.5 border border-gray-100 rounded-xl bg-gray-50/50">
                    <div class="flex items-center gap-3">
                        <!-- Иконка устройства -->
                        <div class="p-2 bg-white rounded-lg border border-gray-100 text-gray-400">
                            @if(($session['platform'] ?? '') === 'Macintosh' || ($session['platform'] ?? '') === 'OS X' || ($session['platform'] ?? '') === 'Windows')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H13.5A2.25 2.25 0 0115.75 3.75V20.25A2.25 2.25 0 0113.5 22.5H10.5A2.25 2.25 0 018.25 20.25V3.75A2.25 2.25 0 0110.5 1.5Z" /></svg>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ __($session['browser'] ?? 'Unknown Browser') }} on {{ __($session['platform'] ?? 'Unknown OS') }}
                                </span>
                                @if($session['is_current_device'] ?? false)
                                    <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 text-[10px] font-medium rounded-full border border-indigo-100">{{ __('This device') }}</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500">
                                {{ $session['ip_address'] ?? __('Unknown IP') }} — {{ __('Active') }} {{ __($session['last_active'] ?? 'Just now') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Кнопка выхода со всех остальных устройств -->
        @if(count($sessions) > 1)
            <form action="{{ route('settings.sessions.logout') }}" method="POST" class="p-4 bg-rose-50/50 border border-rose-100 rounded-xl space-y-4">
                @csrf
                <div>
                    <h4 class="text-sm font-semibold text-rose-900">{{ __('Logout other devices') }}</h4>
                    <p class="text-xs text-rose-700 mt-0.5">{{ __('Please confirm your identity to revoke access from all other browser sessions.') }}</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 items-end">
                    @if(auth()->user()->settings->two_factor_enabled)
                        <div class="w-full sm:max-w-xs">
                            <input type="text" name="code" placeholder="{{ __('Enter 2FA Code') }}" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 transition" required>
                            @error('code_session') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    @else
                        <div class="w-full sm:max-w-xs">
                            <input type="password" name="password" placeholder="{{ __('Enter account password') }}" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 transition" required>
                            @error('password_session') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <button type="submit" class="px-4 py-2 bg-rose-600 text-white text-sm font-medium rounded-xl hover:bg-rose-700 transition shrink-0">
                        {{ __('Log Out Other Devices') }}
                    </button>
                </div>
            </form>
        @endif
    </div>

    <!-- Секция 2: Изменение пароля -->
    <form action="{{ route('password.update') }}" method="POST" class="space-y-6 pt-6 border-t border-gray-100">
        @csrf
        @method('PUT')

        <div>
            <h3 class="text-base font-bold text-gray-900 mb-1">{{ __('Update Password') }}</h3>
            <p class="text-xs text-gray-500">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
        </div>

        <div class="grid grid-cols-1 gap-4 max-w-xl">
            <!-- Текущий пароль -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Current Password') }}</label>
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
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('New Password') }}</label>
                <input type="password"
                       id="password"
                       name="password"
                       autocomplete="new-password"
                       class="w-full px-3 py-2.5 bg-white border @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-1 transition"
                >
                @error('password')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Подтверждение пароля -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirm New Password') }}</label>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       autocomplete="new-password"
                       class="w-full px-3 py-2.5 bg-white border @error('password_confirmation') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-1 transition"
                >
                @error('password_confirmation')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-100">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition shadow-sm">
                {{ __('Update Password') }}
            </button>
        </div>
    </form>
</div>
