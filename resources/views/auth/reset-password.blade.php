<x-app-layout>
    <x-slot name="title">{{ __('Reset Password') }}</x-slot>

    <div class="max-w-md mx-auto my-12">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

            <!-- Заголовок -->
            <div class="text-center mb-6">
                <div class="inline-flex p-3 bg-indigo-50 text-indigo-600 rounded-xl mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">{{ __('Create new password') }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ __('Please enter your email and choose a strong password.') }}</p>
            </div>

            <!-- Форма сброса -->
            <form action="{{ route('password.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Токен сброса пароля (Обязательное скрытое поле Laravel) -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Поле Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email address') }}</label>
                    <div class="relative">
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $request->email) }}"
                               required
                               autocomplete="email"
                               placeholder="{{ __('you@example.com') }}"
                               class="w-full pl-10 pr-4 py-2.5 bg-white border @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-1 transition"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                    </div>
                    @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Поле Новый пароль -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('New password') }}</label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           autocomplete="new-password"
                           placeholder="{{ __('••••••••') }}"
                           class="w-full px-4 py-2.5 bg-white border @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-1 transition"
                    >
                    @error('password')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Поле Подтверждение пароля -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirm new password') }}</label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           required
                           autocomplete="new-password"
                           placeholder="{{ __('••••••••') }}"
                           class="w-full px-4 py-2.5 bg-white border @error('password_confirmation') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-1 transition"
                    >
                    @error('password_confirmation')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Кнопка действия -->
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-sm pt-2">
                    {{ __('Reset Password') }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
