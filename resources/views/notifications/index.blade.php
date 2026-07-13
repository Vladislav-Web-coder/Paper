<x-layout>
    <x-slot name="title">Notifications</x-slot>

    <!-- Шапка страницы -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Notifications</h1>
            <p class="text-sm text-gray-500 mt-1">Stay updated with your shared folders, notes and account security status.</p>
        </div>

        @if(auth()->user()->unreadNotifications->isNotEmpty())
            <form action="{{ route('notifications.markAsReadAll') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full text-center whitespace-nowrap bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm">
                    Mark all as read
                </button>
            </form>
        @endif
        @if(auth()->user()->notifications->isNotEmpty())
            <form action="{{ route('notifications.clearAll') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure you want to delete all notifications?')" class="w-full text-center whitespace-nowrap bg-rose-50 border border-rose-100 text-rose-700 hover:bg-rose-100 px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm">
                    Clear all
                </button>
            </form>
        @endif
    </div>

    <!-- Основной контент -->
    <div class="space-y-4">
        @if($notifications->isEmpty())
            <div class="p-12 text-center bg-white border border-gray-200 rounded-2xl text-gray-400 text-sm shadow-sm flex flex-col items-center justify-center gap-3">
                <div class="p-3 bg-gray-50 text-gray-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-700 mb-0.5">Good job! You're all caught up</p>
                    <p class="text-xs text-gray-400">When you get new alerts, they will show up here.</p>
                </div>
            </div>
        @else
            <!-- Список уведомлений -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm divide-y divide-gray-100 overflow-hidden">
                @foreach($notifications as $notification)
                    <div class="p-4 flex items-start gap-4 transition hover:bg-gray-50/50 relative group {{ $notification->unread() ? 'bg-indigo-50/30' : '' }}">

                        <!-- Индикатор непрочитанного (Синяя точка) -->
                        @if($notification->unread())
                            <span class="absolute left-1.5 top-1/2 -translate-y-1/2 w-2 h-2 bg-indigo-600 rounded-full" title="Unread"></span>
                        @endif

                        <!-- Иконка уведомления на основе его типа -->
                        <div class="p-2.5 rounded-xl shrink-0 {{ $notification->unread() ? 'bg-indigo-50 text-indigo-600' : 'bg-gray-100 text-gray-500' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>

                        <!-- Текстовый блок -->
                        <div class="flex-grow min-w-0 pr-12">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-900 truncate">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </span>
                                <span class="text-xs text-gray-400 whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-0.5 leading-relaxed">
                                {{ $notification->data['message'] ?? '' }}
                            </p>

                            <!-- Кнопка перехода (Действие) -->
                            @if(isset($notification->data['action_url']))
                                <a href="{{ $notification->data['action_url'] }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-700 mt-2 transition">
                                    View changes
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endif
                        </div>

                        <!-- Кнопка "Отметить прочитанным" в углу (показывается при наведении) -->
                        @if($notification->unread())
                            <div class="absolute right-4 top-4 md:opacity-0 group-hover:opacity-100 transition duration-150">
                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" title="Mark as read" class="p-1.5 bg-white border border-gray-200 text-gray-400 hover:text-indigo-600 hover:border-indigo-200 rounded-lg shadow-sm transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endif

                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Delete notification" class="p-1.5 bg-white border border-gray-200 text-gray-400 hover:text-rose-600 hover:border-rose-200 rounded-lg shadow-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            <!-- Блок пагинации -->
            <div class="mt-6 pagination-indigo">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-layout>
