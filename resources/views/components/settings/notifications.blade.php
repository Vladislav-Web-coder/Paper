@props(['settings'])

<form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
    @csrf
    @method('PATCH')

    <input type="hidden" name="current_tab" value="notifications">

    <!-- Секция типов уведомлений (уже была у вас) -->
    <div>
        <h3 class="text-base font-bold text-gray-900 mb-1">Notification Settings</h3>
        <p class="text-xs text-gray-500">Choose what updates you want to receive.</p>
    </div>

    <div class="space-y-4 border-t border-gray-100 pt-4">
        <label class="flex items-start gap-3 cursor-pointer group">
            <input type="hidden" name="notify_email" value="0">
            <input type="checkbox" name="notify_email" value="1" {{ old('notify_email', $settings->notify_email ?? false) ? 'checked' : '' }} class="mt-1 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <div>
                <span class="block text-sm font-medium text-gray-700 group-hover:text-gray-900 transition">Email Digests</span>
                <span class="block text-xs text-gray-400">Receive weekly summaries of your activity and notes.</span>
            </div>
        </label>

        <label class="flex items-start gap-3 cursor-pointer group">
            <input type="hidden" name="subscribe_updates" value="0">
            <input type="checkbox" name="subscribe_updates" value="1" {{ old('subscribe_updates', $settings->subscribe_updates ?? false) ? 'checked' : '' }} class="mt-1 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <div>
                <span class="block text-sm font-medium text-gray-700 group-hover:text-gray-900 transition">Browser Push Notifications</span>
                <span class="block text-xs text-gray-400">Get instant alerts on actions inside shared folders.</span>
            </div>
        </label>
    </div>

    <!-- НОВАЯ СЕКЦИЯ: Выбор каналов доставки уведомлений -->
    <div class="pt-6 border-t border-gray-100">
        <h3 class="text-base font-bold text-gray-900 mb-1">Delivery Channels</h3>
        <p class="text-xs text-gray-500 mb-4">Where should we deliver your instant alerts?</p>

        {{-- Страховка от пустого сабмита: если все чекбоксы сняты, отправится пустой массив --}}
        <input type="hidden" name="notification_channels" value="">

        <div class="space-y-4">
            @foreach(\App\Enums\NotificationChannel::cases() as $channel)
                @php
                    $isChecked = in_array($channel->value, old('notification_channels', $settings->notification_channels));
                @endphp

                <label class="flex items-start gap-3 cursor-pointer group">
                    <input type="checkbox"
                           name="notification_channels[]"
                           value="{{ $channel->value }}"
                           {{ $isChecked ? 'checked' : '' }}
                           class="mt-1 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <div>
                                        <span class="block text-sm font-medium text-gray-700 group-hover:text-gray-900 transition">
                                            {{ ucfirst($channel->value) }}
                                        </span>
                    </div>
                </label>
            @endforeach

        </div>
        @error('notification_channels')
        <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
        @enderror
        @error('notification_channels.*')
        <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end pt-4 border-t border-gray-100">
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm">
            Save Preferences
        </button>
    </div>
</form>
