<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TwoFactorEnabledNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public function via(mixed $notifiable): array
    {
        $channels = $notifiable->settings->notification_channels;

        $channels = array_map(function ($channel) {
            return $channel === 'telegram' ? TelegramChannel::class : $channel;
        }, $channels);

        if (! in_array('database', $channels, true)) {
            $channels[] = 'database';
        }

        return $channels;
    }

    /**
     * Отправка по Email.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Two-Factor Authentication Enabled')
            ->greeting('Security Alert')
            ->line('Two-factor authentication (2FA) has been successfully enabled for your ' . config('app.name', 'Paper') . ' account.')
            ->line('If this wasn\'t you, please change your password and contact support immediately.')
            ->action('Review Account Security', route('settings.show', ['tab' => 'privacy']));
    }

    /**
     * Отправка в привязанный Telegram-бот.
     */
    public function toTelegram(mixed $notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to($notifiable->telegram_chat_id)
            ->line("🔒 *Security Notification*")
            ->line("Hello, {$notifiable->name}.")
            ->line("Two-factor authentication has been *enabled* for your account.")
            ->button('Open Settings', route('settings.show', ['tab' => 'privacy']));
    }

    /**
     * Сохранение в системную ленту уведомлений (компонент NotificationController).
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'title' => '2FA Activated',
            'message' => 'Two-factor authentication has been enabled on your account.',
            'action_url' => route('settings.show', ['tab' => 'privacy']),
        ];
    }
}
