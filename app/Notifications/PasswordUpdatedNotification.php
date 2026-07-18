<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $notifiable->settings->notification_channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Security Alert: Your password has been changed')
            ->greeting('Hello, ' . $notifiable->name . '!')
            ->line('This email confirms that the password for your account has been successfully changed.')
            ->line('If you made this change, no further action is required.')
            ->action('Go to Dashboard', url('/dashboard'))
            ->line('If you did NOT change your password, please contact our support team immediately!')
            ->salutation('Best regards, The Support Team.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Password Changed Successfully',
            'message' => 'Your account security has been updated. Password was changed on ' . now()->format('M d, Y \a\t H:i'),
            'type' => 'security',
        ];
    }
}
