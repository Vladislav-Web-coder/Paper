<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailChangeRequestedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected string $newEmail)
    {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->error()
            ->subject('Security Notice: Email Change Requested')
            ->greeting('Hello, ' . $notifiable->name . '!')
            ->line('We received a request to change the email address for your account to: ' . $this->newEmail)
            ->line('A confirmation link has been sent to the new email address. The change will not take effect until that link is clicked.')
            ->line('**If you made this request**, you can safely ignore this email.')
            ->line('**WARNING:** If you did NOT request this change, your account may be compromised. Please secure your account and contact support immediately.')
            ->salutation('Best regards, The Paper Team.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Email Change Requested',
            'message' => 'A request to change your email to ' . $this->newEmail . ' was initiated.',
            'type' => 'security_alert',
        ];
    }
}
