<x-layout>
    <x-slot name="title">{{ __('Confirm Password') }}</x-slot>

    <div class="max-w-md mx-auto my-12">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
            <div class="text-center mb-6">
                <div class="inline-flex p-3 bg-indigo-50 text-indigo-600 rounded-xl mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">{{ __('Secure Area') }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ __('Please confirm your password to access privacy settings.') }}</p>
            </div>

            <form action="{{ route('settings.confirm_password') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="{{ __('Enter your account password') }}"
                           class="w-full px-4 py-2.5 bg-white border @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-1 transition"
                    >
                    @error('password')
                    <p class="text-xs text-red-600 mt-1">{{ __($message) }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between gap-3 pt-2">
                    <a href="{{ route('settings.show', 'interface') }}" class="w-full text-center bg-gray-50 border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-100 transition shadow-sm">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-sm">
                        {{ __('Confirm') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
