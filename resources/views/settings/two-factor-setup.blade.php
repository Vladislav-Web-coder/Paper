<x-layout>
    <x-slot:title>Setup Two-Factor Authentication - {{ config('app.name') }}</x-slot:title>

    <div class="max-w-md mx-auto">

        <!-- Кнопка возврата к настройкам -->
        <div class="mb-6">
            <a href="{{ route('settings.show', ['tab' => 'privacy']) }}" class="inline-flex items-center gap-2 text-xs font-medium text-gray-500 hover:text-indigo-600 transition group">
                <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Privacy Settings
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sm:p-8 space-y-6">

            <!-- Заголовок -->
            <div class="text-center space-y-2">
                <div class="inline-flex p-3 bg-indigo-50 text-indigo-600 rounded-xl mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">Enable Two-Factor Protection</h1>
                <p class="text-xs text-gray-500 max-w-xs mx-auto leading-relaxed">Scan the QR code with your authenticator application (Google Authenticator, Authy, etc.).</p>
            </div>

            <!-- Рендеринг QR-кода -->
            <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                    {!! $qrCodeSvg !!}
                </div>

                <!-- Текстовый секретный ключ -->
                <div class="mt-4 text-center w-full">
                    <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Can't scan? Enter this code manually:</span>
                    <code class="block mt-1 p-2 bg-white rounded-lg border border-gray-200 font-mono text-xs font-semibold text-gray-700 tracking-wider break-all select-all">
                        {{ $secret }}
                    </code>
                </div>
            </div>

            <!-- Блок резервных кодов восстановления -->
            <div class="space-y-3 pt-2">
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <div class="flex gap-2">
                        <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <span class="block text-xs font-bold text-amber-800">Save your recovery codes!</span>
                            <span class="block text-[11px] text-amber-700 leading-relaxed mt-0.5">If you lose your device, these codes are the ONLY way to access your account. Store them securely.</span>
                        </div>
                    </div>

                    <!-- Сетка кодов 2х4 -->
                    <div class="grid grid-cols-2 gap-2 mt-4 font-mono text-xs font-bold text-gray-700 text-center" id="recovery-codes-container">
                        @foreach($recoveryCodes as $code)
                            <div class="bg-white border border-amber-100 px-3 py-2 rounded-lg shadow-sm select-all tracking-wider code-item">
                                {{ $code }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Кнопка быстрого копирования всех кодов -->
                    <button type="button" id="copy-codes-btn" class="mt-3 w-full inline-flex items-center justify-center gap-1 text-xs font-semibold text-amber-800 hover:text-amber-950 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0A2.25 2.25 0 0113.5 3.75H10.5a2.25 2.25 0 01-2.166-1.512m7.332 0c.055.194.084.4.084.612v1.5a2.25 2.25 0 01-2.25 2.25H9A2.25 2.25 0 016.75 4.5v-1.5c0-.212.03-.418.084-.612m7.332 0c.346.102.637.311.83.597l.001.001c.142.208.225.46.225.733v15.15a2.25 2.25 0 01-2.25 2.25H9a2.25 2.25 0 01-2.25-2.25V3.888c0-.273.083-.525.224-.733l.001-.001a1.2 1.2 0 01.83-.597M12 10.5v6m3-3H9" />
                        </svg>
                        Copy All Codes
                    </button>
                </div>
            </div>

            <!-- Форма подтверждения для включения 2FA -->
            <form action="{{ route('2fa.enable') }}" method="POST" class="space-y-4 pt-2 border-t border-gray-100">
                @csrf
                <div>
                    <label for="code" class="block text-xs font-medium text-gray-700 mb-1.5">Enter Verification Code to Confirm</label>
                    <input type="text"
                           id="code"
                           name="code"
                           required
                           placeholder="Enter 6-digit code"
                           class="w-full px-4 py-2.5 rounded-xl border @error('code') border-rose-300 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 @enderror bg-gray-50/50 text-center font-medium text-sm focus:outline-none focus:ring-2 transition placeholder:text-gray-400"
                    >
                    @error('code')
                    <p class="text-xs text-rose-500 mt-1.5 font-medium">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <a href="{{ route('settings.show', ['tab' => 'privacy']) }}" class="w-1/2 text-center border border-gray-200 hover:bg-gray-50 text-gray-600 px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-sm">
                        Cancel
                    </a>
                    <button type="submit" class="w-1/2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-sm">
                        Activate 2FA
                    </button>
                </div>
            </form>

        </div>
    </div>
    @push('scripts')
        <script>
            document.getElementById('copy-codes-btn').addEventListener('click', function() {
                const codeElements = document.querySelectorAll('.code-item');
                const codesText = Array.from(codeElements).map(el => el.innerText.trim()).join('\n');

                navigator.clipboard.writeText(codesText).then(() => {
                    const originalText = this.innerHTML;
                    this.innerHTML = '✨ Copied to Clipboard!';
                    setTimeout(() => this.innerHTML = originalText, 2000);
                });
            });
        </script>
    @endpush
</x-layout>

