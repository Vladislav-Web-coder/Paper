<?php

namespace App\ValueObject;

use App\Enums\NotificationChannel;
use Illuminate\Contracts\Support\Arrayable;

final readonly class UserSettings implements Arrayable
{
    public function __construct(
        public string $theme = 'system',
        public string $timezone = 'UTC',
        public string $language = 'en',
        public string $greeting = 'Welcome back',
        public bool $notify_email = false,
        public bool $subscribe_updates = false,
        public bool $profile_visibility = true,
        public array $notification_channels = ['mail', 'database'],
        public bool $two_factor_enabled = false,
    ) {}

    public static function fromArray(array $attributes): self
    {
        return new self(
            theme: $attributes['theme'] ?? 'system',
            timezone: $attributes['timezone'] ?? config('app.timezone', 'UTC'),
            language: $attributes['language'] ?? config('app.locale', 'en'),
            greeting: $attributes['greeting'] ?? 'Welcome back',
            notify_email: $attributes['notify_email'] ?? false,
            subscribe_updates: $attributes['subscribe_updates'] ?? false,
            profile_visibility: $attributes['profile_visibility'] ?? true,
            notification_channels: $attributes['notification_channels'] ?? ['mail', 'database'],
            two_factor_enabled: $attributes['two_factor_enabled'] ?? false,
        );
    }
    public function update(array $newAttributes): self
    {
        $merged = array_merge($this->toArray(), $newAttributes);

        return self::fromArray($merged);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'theme' => $this->theme,
            'timezone' => $this->timezone,
            'language' => $this->language,
            'greeting' => $this->greeting,
            'notify_email' => $this->notify_email,
            'subscribe_updates' => $this->subscribe_updates,
            'profile_visibility' => $this->profile_visibility,
            'notification_channels' => $this->notification_channels,
            'two_factor_enabled' => $this->two_factor_enabled,
        ];
    }
}
