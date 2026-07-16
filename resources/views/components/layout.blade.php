<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Paper') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-sans flex flex-col min-h-screen">

<header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-8">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-500 tracking-tight flex items-center gap-2">
                <span class="p-1.5 bg-indigo-50 rounded-lg text-indigo-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </span>
                {{ config('app.name', 'Paper') }}
            </a>
        </div>

        <!-- Профиль, уведомления и переход в настройки -->
        <div class="flex items-center gap-4">
            @auth
                <x-notification-indicator />

                <a href="{{ route('settings.show') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 hover:border-gray-200 transition text-gray-600 hover:text-indigo-600 group shadow-sm/50">
                    <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600 transition">
                        {{ auth()->user()->name }}
                    </span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-600 group-hover:rotate-45 transition duration-300 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.767a1.123 1.123 0 00-.417 1.03c.004.074.006.148.006.222 0 .074-.002.148-.006.222a1.123 1.123 0 00.417 1.03l1.003.767a1.125 1.125 0 01.26 1.43l-1.296 2.247a1.125 1.125 0 01-1.37.49l-1.216-.456a1.125 1.125 0 00-1.07.124c-.073.044-.146.087-.22.128-.332.183-.582.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281a1.125 1.125 0 00-.646-.87c-.074-.04-.147-.083-.22-.127a1.124 1.124 0 00-1.075-.124l-1.217.456a1.125 1.125 0 01-1.37-.49l-1.296-2.247a1.125 1.125 0 01.26-1.43l1.003-.767a1.122 1.122 0 00.417-1.03a3.47 3.47 0 01-.006-.444c.004-.074.006-.148.006-.222c0-.074-.002-.148-.006-.222a1.122 1.122 0 00-.417-1.03l-1.003-.767a1.125 1.125 0 01-.26-1.43l1.296-2.247a1.125 1.125 0 011.37-.49l1.216.456c.356.133.751.072 1.076-.124.072-.041.146-.084.218-.128.333-.183.582-.495.645-.869l.214-1.28z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>
            @endauth
        </div>
    </div>
</header>

<!-- Основной контент (Растягивается, прижимая футер вниз) -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow w-full">

    <!-- Флеш-сообщение об успехе -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Флеш-сообщение об ошибке (Исправлено: добавлен закрывающий тег div) -->
    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{ $slot }}
</main>

<!-- Нижняя панель (Футер) -->
<footer class="bg-white border-t border-gray-200 mt-auto shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            <!-- Колонка 1: О нас -->
            <div class="space-y-3 md:col-span-2">
                <span class="text-base font-bold text-gray-900 tracking-tight block">About Us</span>
                <p class="text-sm text-gray-500 max-w-sm leading-relaxed">
                    {{ config('app.name', 'Paper') }} is a minimal, blazing-fast personal workspace designed to organize your thoughts, sync encrypted notes, and structure folders seamlessly.
                </p>
            </div>

            <!-- Колонка 2: Соцсети -->
            <div class="space-y-3">
                <span class="text-sm font-semibold text-gray-400 uppercase tracking-wider block">Social Networks</span>
                <ul class="space-y-2 text-sm font-medium">
                    <li>
                        <a href="https://github.com" target="_blank" rel="noopener" class="text-gray-600 hover:text-indigo-600 transition flex items-center gap-1.5">
                            GitHub
                        </a>
                    </li>
                    <li>
                        <a href="https://twitter.com" target="_blank" rel="noopener" class="text-gray-600 hover:text-indigo-600 transition flex items-center gap-1.5">
                            X / Twitter
                        </a>
                    </li>
                    <li>
                        <a href="https://telegram.org" target="_blank" rel="noopener" class="text-gray-600 hover:text-indigo-600 transition flex items-center gap-1.5">
                            Telegram
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Колонка 3: Поддержка -->
            <div class="space-y-3">
                <span class="text-sm font-semibold text-gray-400 uppercase tracking-wider block">Support</span>
                <ul class="space-y-2 text-sm font-medium">
                    <li>
                        <a href="#" class="text-gray-600 hover:text-indigo-600 transition block">Documentation</a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-600 hover:text-indigo-600 transition block">Help Center</a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
