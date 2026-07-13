<?php

namespace App\Http\Requests;

use App\Enums\AppLanguage;
use App\Enums\NotificationChannel;
use App\Enums\SettingsTab;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(!auth()->check()) {
            return false;
        }

        if($this->input('current_tab') === SettingsTab::PRIVACY->value) {
            $confirmedAt = session('last_confirmed_password_at');

            if(!$confirmedAt || Carbon::parse($confirmedAt)->diffInMinutes(now()) >= 10) {
                return false;
            }
        }

        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('current_tab') === SettingsTab::INTERFACE->value) {
            $this->mergeIfMissing([
                'theme' => 'system',
                'greeting' => 'Hello Boss'
            ]);
        }
        if($this->input('current_tab') === SettingsTab::NOTIFICATIONS->value) {
            $channels = is_array($this->input('notification_channels'))
                ? array_filter($this->input('notification_channels'))
                : [];
            $this->merge([
                'notify_email' => $this->boolean('notify_email'),
                'subscribe_updates' => $this->boolean('subscribe_updates'),
                'notifications_channels' => $channels,
            ]);
        }
        if($this->input('current_tab') === SettingsTab::PRIVACY->value) {
            $this->merge([
                'profile_visibility' => $this->boolean('profile_visibility'),
                'two_factor_enabled' => $this->boolean('two_factor_enabled'),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_tab' => ['required', 'string', new Enum(SettingsTab::class)],

            // Rules for Interface tab
            'theme' => [
                'exclude_unless:current_tab,' . SettingsTab::INTERFACE->value,
                'string',
                'in:system,light,dark'
            ],
            'timezone' => [
                'exclude_unless:current_tab,' . SettingsTab::INTERFACE->value,
                'string',
                'timezone'
            ],
            'language' => [
                'exclude_unless:current_tab,' . SettingsTab::INTERFACE->value,
                'string',
                new Enum(AppLanguage::class)
            ],
            'greeting' => [
                'exclude_unless:current_tab,' . SettingsTab::INTERFACE->value,
                'string'
            ],

            // Rules for Notifications tab
            'notify_email' => ['exclude_unless:current_tab,' . SettingsTab::NOTIFICATIONS->value, 'boolean'],
            'subscribe_updates' => ['exclude_unless:current_tab,' . SettingsTab::NOTIFICATIONS->value, 'boolean'],
            'notification_channels' => [
                'exclude_unless:current_tab,' . SettingsTab::NOTIFICATIONS->value,
                'array'
            ],
            'notification_channels.*' => [
                'exclude_unless:current_tab,' . SettingsTab::NOTIFICATIONS->value,
                'string',
                new Enum(NotificationChannel::class)
            ],

            // Rules for Privacy tab
            'profile_visibility' => ['exclude_unless:current_tab,' . SettingsTab::PRIVACY->value, 'boolean'],
            'two_factor_enabled' => ['exclude_unless:current_tab,' . SettingsTab::PRIVACY->value, 'boolean'],
        ];
    }
}
