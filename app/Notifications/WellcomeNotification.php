<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(mixed $notifiable): array
    {
        return $notifiable->settings->notification_channels;
    }
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to ' . config('app.name', 'Paper') . '!')
            ->greeting($notifiable->settings->greeting . ', ' . $notifiable->name . '!')
            ->line('Thank you for registering. Paper is your minimal personal workspace designed to organize your thoughts and notes securely.')
            ->action('Go to Dashboard', url('/dashboard'))
            ->line('If you have any questions, feel free to reply to this email.');
    }
    public function toArray(mixed $notifiable): array
    {
        return [
            'title' => 'Welcome aboard!',
            'message' => 'Thank you for creating an account on ' . config('app.name', 'Paper') . '.',
            'action_url' => route('dashboard'),
        ];
    }
}
