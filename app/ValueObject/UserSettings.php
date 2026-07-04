<?php

namespace App\ValueObject;

use Illuminate\Contracts\Support\Arrayable;

final readonly class UserSettings implements Arrayable
{
    public string $theme;
    public string $timezone;
    public string $language;
    public bool $notify_email;
    public bool $subscribe_updates;
    public string $profile_visibility;

    public function __construct()
    {
        $this->theme = $attributes['theme'] ?? 'system';
        $this->timezone = $attributes['timezone'] ?? config('app.timezone', 'UTC');
        $this->language = $attributes['language'] ?? config('app.locale', 'en');

        $this->notify_email = $attributes['notify_email'] ?? false;
        $this->subscribe_updates = $attributes['subscribe_updates'] ?? false;

        $this->profile_visibility = $attributes['profile_visibility'] ?? 'private';
    }

    public function update(array $newAttributes): self
    {
        $merged = array_merge($this->toArray(), $newAttributes);

        return new self($merged);
    }

    /**
     * @inheritDoc
     */

    public function toArray()
    {
        return [
            'theme' => $this->theme,
            'timezone' => $this->timezone,
            'language' => $this->language,
            'notify_email' => $this->notify_email,
            'subscribe_updates' => $this->subscribe_updates,
            'profile_visibility' => $this->profile_visibility,
        ];
    }
}
