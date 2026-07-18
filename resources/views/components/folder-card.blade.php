@props(['folder'])
<div class="p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:border-amber-300 dark:hover:border-amber-400 hover:shadow-sm transition duration-200 flex items-center justify-between group">
    <div class="flex items-center gap-3">
        <div class="p-2.5 bg-amber-50 dark:bg-amber-950/40 rounded-lg text-amber-600 dark:text-amber-400 group-hover:bg-amber-100 dark:group-hover:bg-amber-950/70 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
        </div>
        <div>
            <h4 class="font-medium text-gray-900 dark:text-gray-100 line-clamp-1">{{ $folder->name }}</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $folder->created_at->format('d.m.Y') }}</p>
        </div>
    </div>
    <a href="/folders/{{ $folder->id }}" class="text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>
