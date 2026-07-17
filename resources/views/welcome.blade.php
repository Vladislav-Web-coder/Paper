<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paper — Smart Notebook</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (!document.cookie.includes('browser_timezone=')) {
            const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            if (timezone) {
                document.cookie = `browser_timezone=${encodeURIComponent(timezone)}; path=/; max-age=31536000; SameSite=Lax`;
                window.location.reload();
            }
        }
    </script>
</head>
<body class="bg-gray-50/50 text-gray-900 antialiased font-sans selection:bg-indigo-500 selection:text-white">

<header class="sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-gray-100 transition duration-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="{{ route('landing') }}" class="flex items-center gap-2.5 group">
            <div class="p-2 bg-indigo-600 text-white rounded-xl shadow-md group-hover:scale-95 transition duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="font-bold text-lg text-gray-900 tracking-tight">Paper<span class="text-indigo-600">.</span></span>
        </a>

        <div class="flex items-center gap-4">
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition">
                {{ __('landing.login') }}
            </a>
            <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm">
                {{ __('landing.get_started') }}
            </a>
        </div>
    </div>
</header>

<section class="relative pt-20 pb-16 lg:pt-28 lg:pb-24 overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 text-center space-y-6 relative z-10">
        <span class="inline-flex items-center px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full text-xs font-semibold text-indigo-700 tracking-wide uppercase">
                🚀 Version 1.0 Available
            </span>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight leading-none">
            {{ __('landing.title') }}
        </h1>

        <p class="text-base sm:text-lg text-gray-500 max-w-2xl mx-auto leading-relaxed">
            {{ __('landing.subtitle') }}
        </p>

        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-md flex items-center justify-center gap-2">
                {{ __('landing.get_started') }} →
            </a>
            <a href="#features" class="w-full sm:w-auto px-6 py-3 bg-white border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition shadow-sm flex items-center justify-center">
                {{ __('landing.features') }}
            </a>
        </div>
    </div>

    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-indigo-100/40 rounded-full blur-3xl pointer-events-none z-0"></div>
    <div class="absolute top-1/3 left-1/3 w-[300px] h-[300px] bg-amber-100/30 rounded-full blur-2xl pointer-events-none z-0"></div>
</section>

<section id="features" class="py-16 lg:py-24 bg-white border-y border-gray-100 scroll-mt-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-2">
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">{{ __('landing.features') }}</h2>
            <p class="text-sm text-gray-500">Everything you need to manage your personal digital archive built with passion.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div class="p-6 border border-gray-100 rounded-2xl bg-gray-50/50 shadow-sm flex gap-4 hover:border-indigo-100 transition duration-200">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl h-12 w-12 flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div class="space-y-1.5">
                    <h4 class="font-bold text-gray-900 text-base tracking-tight">{{ __('landing.feat_search_title') }}</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ __('landing.feat_search_desc') }}</p>
                </div>
            </div>

            <div class="p-6 border border-gray-100 rounded-2xl bg-gray-50/50 shadow-sm flex gap-4 hover:border-amber-100 transition duration-200">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl h-12 w-12 flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <div class="space-y-1.5">
                    <h4 class="font-bold text-gray-900 text-base tracking-tight">{{ __('landing.feat_folders_title') }}</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ __('landing.feat_folders_desc') }}</p>
                </div>
            </div>

            <div class="p-6 border border-gray-100 rounded-2xl bg-gray-50/50 shadow-sm flex gap-4 hover:border-indigo-100 transition duration-200">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl h-12 w-12 flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h14a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="space-y-1.5">
                    <h4 class="font-bold text-gray-900 text-base tracking-tight">{{ __('landing.feat_markdown_title') }}</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ __('landing.feat_markdown_desc') }}</p>
                </div>
            </div>

            <div class="p-6 border border-gray-100 rounded-2xl bg-gray-50/50 shadow-sm flex gap-4 hover:border-rose-100 transition duration-200">
                <div class="p-3 bg-rose-50 text-rose-600 rounded-xl h-12 w-12 flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="space-y-1.5">
                    <h4 class="font-bold text-gray-900 text-base tracking-tight">{{ __('landing.feat_privacy_title') }}</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ __('landing.feat_privacy_desc') }}</p>
                </div>
            </div>

        </div>
    </div>
</section>

<footer class="bg-gray-50 border-t border-gray-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-400 font-medium">
        <div>{{ __('landing.footer') }}</div>
        <div class="flex gap-4">
            <a href="#" class="hover:text-gray-600 transition">Privacy Policy</a>
            <a href="#" class="hover:text-gray-600 transition">Terms of Service</a>
        </div>
    </div>
</footer>

</body>
</html>
