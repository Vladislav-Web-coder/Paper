<x-app-layout>
    <x-slot name="title">{{ __('My Tags') }}</x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">{{ __('My tags') }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Click on a tag to filter notes or manage it.') }}</p>
        </div>

        @if($tags->isEmpty())
            <div class="p-8 text-center bg-gray-50 dark:bg-gray-800/40 border border-dashed border-gray-200 dark:border-gray-700 rounded-2xl text-gray-500 dark:text-gray-400 text-sm">
                {{ __("You haven't created any tags yet.") }}
            </div>
        @else
            <!-- Сетка пузырьков -->
            <div class="flex flex-wrap gap-3 p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                @foreach($tags as $tag)
                    <a href="{{ route('tags.show', $tag->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-800 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/30 rounded-full text-sm font-medium text-gray-700 dark:text-gray-300 transition duration-200">
                        <span class="text-indigo-600 dark:text-indigo-400 font-semibold">#</span>
                        <span>{{ $tag->name }}</span>
                        <span class="px-2 py-0.5 bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 text-gray-400 dark:text-gray-500 text-xs rounded-full font-normal">
                            {{ $tag->notes_count }}
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
