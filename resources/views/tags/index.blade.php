<x-app-layout>
    <x-slot name="title">{{ __('My Tags') }}</x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ __('My tags') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('Click on a tag to filter notes or manage it.') }}</p>
        </div>

        @if($tags->isEmpty())
            <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-gray-500 text-sm">
                {{ __("You haven't created any tags yet.") }}
            </div>
        @else
            <!-- Сетка пузырьков -->
            <div class="flex flex-wrap gap-3 p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">
                @foreach($tags as $tag)
                    <a href="{{ route('tags.show', $tag->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50 rounded-full text-sm font-medium text-gray-700 transition duration-200">
                        <span class="text-indigo-600 font-semibold">#</span>
                        <span>{{ $tag->name }}</span>
                        <span class="px-2 py-0.5 bg-white border border-gray-150 text-gray-400 text-xs rounded-full font-normal">
                            {{ $tag->notes_count }}
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
