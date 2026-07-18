<x-app-layout>
    <x-slot name="title">{{ __('Account Settings') }}</x-slot>

    <!-- Шапка страницы -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">{{ __('Settings') }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Manage your account preferences and application layout.') }}</p>
    </div>

    <div id="settings-container" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">

        <nav class="flex flex-row md:flex-col gap-1 overflow-x-auto md:overflow-x-visible pb-3 md:pb-0 border-b border-gray-100 md:border-b-0 dark:border-gray-700/50 whitespace-nowrap">
            <!-- Таб Интерфейса -->
            <a href="{{ route('settings.show', 'interface') }}"
               class="nav-tab flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium rounded-xl transition {{ $currentTab === 'interface' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-100' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                {{ __('Interface') }}
            </a>

            <!-- Таб Уведомлений -->
            <a href="{{ route('settings.show', 'notifications') }}"
               class="nav-tab flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium rounded-xl transition {{ $currentTab === 'notifications' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-100' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                {{ __('Notifications') }}
            </a>

            <!-- Таб Приватности -->
            <a href="{{ route('settings.show', 'privacy') }}"
               class="nav-tab flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium rounded-xl transition {{ $currentTab === 'privacy' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-100' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                {{ __('Privacy & Security') }}
            </a>

            <form action="{{ route('logout') }}" method="POST" class="block w-full">
                @csrf
                <button type="submit" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium rounded-xl text-gray-600 dark:text-gray-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 hover:text-rose-600 dark:hover:text-rose-400 transition w-full text-left cursor-pointer">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    {{ __('Logout') }}
                </button>
            </form>
        </nav>

        <!-- Правая панель: Формы контента -->
        <div class="md:col-span-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
            @if($currentTab === 'interface')
                <x-settings.interface :settings="$settings" />
            @elseif($currentTab === 'notifications')
                <x-settings.notifications :settings="$settings" />
            @elseif($currentTab === 'privacy')
                <x-settings.privacy :settings="$settings" :sessions="$sessions" />
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const container = document.getElementById('settings-container');
                let isDirty = false;

                container.addEventListener('input', () => isDirty = true);
                container.addEventListener('change', () => isDirty = true);

                container.addEventListener('submit', () => isDirty = false);

                document.querySelectorAll('.nav-tab').forEach(tab => {
                    tab.addEventListener('click', function (e) {
                        if (this.classList.contains('bg-indigo-50') || this.classList.contains('dark:bg-indigo-950/40')) return;

                        if (isDirty) {
                            const confirmLeave = confirm("You have unsaved changes. Are you sure you want to leave?");
                            if (!confirmLeave) {
                                e.preventDefault();
                            }
                        }
                    });
                });

                window.addEventListener('beforeunload', function (e) {
                    if (isDirty) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
