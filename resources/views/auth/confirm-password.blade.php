<x-layout>
    <x-slot name="title">Confirm Password</x-slot>

    <div class="max-w-md mx-auto my-12">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

            <!-- Заголовок и описание -->
            <div class="text-center mb-6">
                <div class="inline-flex p-3 bg-indigo-50 text-indigo-600 rounded-xl mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">Confirm your password</h1>
                <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
                    This is a secure area of the application. Please confirm your password before continuing.
                </p>
            </div>

            <!-- Форма подтверждения -->
            <form action="{{ route('password.confirm') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Поле Пароль -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           autofocus
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 bg-white border @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-1 transition"
                    >
                    @error('password')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Кнопка действия -->
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-sm pt-2">
                    Confirm
                </button>
            </form>
        </div>
    </div>
</x-layout>
