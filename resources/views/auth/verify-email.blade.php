<x-layout>
    <x-slot name="title">{{ __('Verify Email') }}</x-slot>

    <div class="max-w-md mx-auto my-12">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

            <!-- Заголовок и описание -->
            <div class="text-center mb-6">
                <div class="inline-flex p-3 bg-indigo-50 text-indigo-600 rounded-xl mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">{{ __('Verify your email') }}</h1>
                <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.') }}
                </p>
            </div>

            <!-- Уведомление о повторной отправке ссылки -->
            @if (session('status') == 'verification-link-sent')
                <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-100 rounded-xl text-xs text-emerald-800 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>{{ __('A new verification link has been sent to the email address you provided during registration.') }}</div>
                </div>
            @endif

            <!-- Блок действий -->
            <div class="space-y-4">
                <!-- Форма повторной отправки -->
                <form action="{{ route('verification.send') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-sm flex items-center justify-center gap-2">
                        {{ __('Resend Verification Email') }}
                    </button>
                </form>

                <!-- Вспомогательные действия (Выход из аккаунта) -->
                <div class="flex items-center justify-center pt-2 border-t border-gray-100">
                    <form action="{{ route('logout') }}" method="POST" class="w-full text-center">
                        @csrf
                        <button type="submit" class="text-xs font-medium text-gray-500 hover:text-rose-600 transition focus:outline-none">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
