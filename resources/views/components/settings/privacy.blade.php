@props(['settings', 'sessions' => []])
<div class="space-y-8">
    <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        <input type="hidden" name="current_tab" value="privacy">
        <div>
            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-1">{{ __('Privacy Options') }}</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Configure your account isolation level and public data visibility.') }}</p>
        </div>

        <div class="space-y-4 border-t border-gray-100 dark:border-gray-700/50 pt-4">
            <input type="hidden" name="profile_visibility" value="0">

            <label class="flex items-start gap-3 cursor-pointer group">
                <input type="hidden" name="profile_visibility" value="0">
                <input type="checkbox"
                       name="profile_visibility"
                       id="profile_visibility"
                       value="1"
                       {{ old('profile_visibility', $settings->profile_visibility ?? true) ? 'checked' : ''}}
                       class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-indigo-600 focus:ring-indigo-500"
                >
                <div>
                    <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-gray-100 transition">{{ __('Private Workspace') }}</span>
                    <span class="block text-xs text-gray-400 dark:text-gray-500">{{ __('Restricts search engine indexation. Only invited accounts can inspect elements inside root folders.') }}</span>
                </div>
            </label>
            @error('profile_visibility')
            <p class="text-xs text-red-600 dark:text-red-400 pl-7 mt-1">{{ $message }}</p>
            @enderror

            <div class="pt-6 border-t border-gray-100 dark:border-gray-700/50">
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-1">{{ __('Telegram Integration') }}</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">{{ __('Connect your Telegram account to receive instant notifications.') }}</p>

                @if(auth()->user()->telegram_chat_id)
                    <div class="flex items-center justify-between bg-indigo-50/60 dark:bg-indigo-950/40 p-4 rounded-xl border border-indigo-100 dark:border-indigo-900/60">
                        <div>
                            <span class="block text-sm font-medium text-indigo-800 dark:text-indigo-300">{{ __('Telegram is connected') }}</span>
                            <span class="block text-xs text-green-600 dark:text-green-400/80">{{ __('You can now enable the Telegram delivery channel in Notification tab.') }}</span>
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800/40 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50">
                        <div>
                            <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Link Telegram Account') }}</span>
                            <span class="block text-xs text-gray-400 dark:text-gray-500">{{ __('Click the button and press "Start" in the opened bot.') }}</span>
                        </div>
                        <a href="{{ route('telegram.connect') }}" target="_blank" rel="noopener" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm flex items-center gap-1.5 shrink-0">
                            {{ __('Connect Bot') }}
                        </a>
                    </div>
                @endif
            </div>

            <div class="pt-6 border-t border-gray-100 dark:border-gray-700/50">
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-1">{{ __('Two-Factor Authentication') }}</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">{{ __('Add an extra layer of security to your account.') }}</p>

                @if($settings->two_factor_enabled)
                    <div class="flex items-center justify-between bg-green-50/60 dark:bg-emerald-950/30 p-4 rounded-xl border border-green-100 dark:border-emerald-900/50">
                        <div>
                            <span class="block text-sm font-medium text-green-800 dark:text-emerald-400">{{ __('2FA is currently active') }}</span>
                            <span class="block text-xs text-green-600 dark:text-emerald-500/80">{{ __('Your account is secured with time-based one-time passwords.') }}</span>
                        </div>
                        <form action="{{ route('2fa.disable') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-50 dark:bg-rose-950/40 hover:bg-red-100 dark:hover:bg-rose-950/70 text-red-600 dark:text-rose-400 px-3 py-1.5 rounded-lg text-xs font-medium transition shrink-0">
                                {{ __('Disable 2FA') }}
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800/40 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50">
                        <div>
                            <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Two-Factor Authentication') }}</span>
                            <span class="block text-xs text-gray-400 dark:text-gray-500">{{ __('Protect your account using an authenticator app.') }}</span>
                        </div>
                        <a href="{{ route('2fa.setup') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm shrink-0">
                            {{ __('Enable 2FA') }}
                        </a>
                    </div>
                @endif
            </div>

            <!-- Статус верификации сессии -->
            <div class="p-3.5 bg-amber-50/60 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/40 rounded-xl text-xs text-amber-800 dark:text-amber-400 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-amber-600 dark:text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <span class="font-semibold block mb-0.5">{{ __('Session Verified') }}</span>
                    {{ __('High-security mode is active. You confirmed your password less than 10 minutes ago. Access token is temporarily unlocked.') }}
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700/50">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition shadow-sm w-full sm:w-auto">
                {{ __('Save Privacy Settings') }}
            </button>
        </div>
    </form>

    <div class="pt-6 border-t border-gray-100 dark:border-gray-700/50">
        <div class="mb-4">
            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-1">{{ __('Email Address') }}</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Update your account email address. This requires 2FA verification.') }}</p>
        </div>

        <form action="{{ route('settings.email.change.request') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="new_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('New Email Address') }}</label>
                    <input type="email" id="new_email" name="new_email" value="{{ old('new_email') }}" class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition" required>
                    @error('new_email') <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                @if(auth()->user()->settings->two_factor_enabled)
                    <div class="sm:col-span-2">
                        <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('2FA Verification Code') }}</label>
                        <input type="text" id="code" name="code" placeholder="123456" class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition" required>
                        @error('code') <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition shadow-sm w-full sm:w-auto">
                    {{ __('Change Email') }}
                </button>
            </div>
        </form>
    </div>
</div>
