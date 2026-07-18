<x-layout>
    <x-slot:title>{{ __('Two-Factor Challenge - :name', ['name' => config('app.name')]) }}</x-slot:title>

    <div class="max-w-md mx-auto py-6 px-4 sm:px-0">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6 sm:p-8 space-y-6">

            <!-- Иконка и Заголовок -->
            <div class="text-center space-y-2">
                <div class="inline-flex p-3 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-xl mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">{{ __('Security Check Required') }}</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 max-w-xs mx-auto leading-relaxed">{{ __('Please enter the 6-digit authentication code from your app, or use an emergency 8-character recovery code.') }}</p>
            </div>

            <!-- Форма авторизации по 2FA -->
            <form action="{{ route('2fa.verify') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="code" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Authentication / Recovery Code') }}</label>
                    <input type="text"
                           id="code"
                           name="code"
                           required
                           autofocus
                           placeholder="{{ __('000000 or XXXX-XXXX') }}"
                           class="w-full px-4 py-2.5 rounded-xl border @error('code') border-rose-300 dark:border-rose-900 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 dark:border-gray-700 focus:ring-indigo-500 focus:border-indigo-500 @enderror bg-gray-50/50 dark:bg-gray-900 text-center font-medium text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 transition placeholder:text-gray-400 dark:placeholder:text-gray-500 uppercase"
                    >
                    @error('code')
                    <p class="text-xs text-rose-500 dark:text-rose-400 mt-1.5 font-medium">
                        {{ __($message) }}
                    </p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                    {{ __('Verify and Continue') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </form>

            <!-- Кнопка отмены/выхода -->
            <div class="border-t border-gray-100 dark:border-gray-700/50 pt-4 text-center">
                <form action="{{ route('logout') }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="text-xs text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 font-medium transition underline underline-offset-4 decoration-dashed cursor-pointer">
                        {{ __('Log out from account') }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-layout>
