<x-app-layout>
    <x-slot name="title">{{ __('Forgot Password') }}</x-slot>

    <div class="max-w-md mx-auto my-12">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

            <!-- Заголовок и описание -->
            <div class="text-center mb-6">
                <div class="inline-flex p-3 bg-indigo-50 text-indigo-600 rounded-xl mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">{{ __('Forgot password?') }}</h1>
                <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
                    {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </p>
            </div>

            <!-- Статус успешной отправки ссылки (Session Status) -->
            @if (session('status'))
                <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-100 rounded-xl text-xs text-emerald-800 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>{{ __(session('status')) }}</div>
                </div>
            @endif

            <!-- Форма восстановления -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Поле Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email address') }}</label>
                    <div class="relative">
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
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

                <!-- Кнопка действия -->
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-sm flex items-center justify-center gap-2">
                    {{ __('Email Password Reset Link') }}
                </button>

                <!-- Ссылка возврата -->
                <div class="text-center pt-2">
                    <a href="{{ route('login') }}" class="text-xs font-medium text-gray-500 hover:text-indigo-600 transition">
                        {{ __('Back to sign in') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
