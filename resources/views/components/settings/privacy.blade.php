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

    <div>
        <div class="mb-4">
            <h3 class="text-base font-bold text-gray-900 mb-1">Email Address</h3>
            <p class="text-xs text-gray-500">Update your account email address. This requires 2FA verification.</p>
        </div>

        <form action="{{ route('settings.email.change.request') }}" method="POST" class="space-y-4 border-t border-gray-100 pt-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="new_email" class="block text-sm font-medium text-gray-700 mb-1">New Email Address</label>
                    <input type="email" id="new_email" name="new_email" value="{{ old('new_email') }}" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition" required>
                    @error('new_email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                @if(auth()->user()->settings->two_factor_enabled)
                    <div class="sm:col-span-2">
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">2FA Verification Code</label>
                        <input type="text" id="code" name="code" placeholder="123456" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition" required>
                        @error('code') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                @else
                    <div class="sm:col-span-2">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm with Your Password</label>
                        <input type="password" id="current_password" name="current_password" placeholder="Enter current password" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition" required>
                        @error('current_password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition">
                    Update Email
                </button>
            </div>
        </form>
    </div>

    <!-- Секция 2: Изменение пароля -->
    <form action="{{ route('password.update') }}" method="POST" class="space-y-6 pt-6 border-t border-gray-100">
        @csrf
        @method('PUT')

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
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
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
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
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
                Update Password
            </button>
        </div>
    </form>
</div>
