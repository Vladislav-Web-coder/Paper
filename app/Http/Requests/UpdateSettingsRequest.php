<?php

namespace App\Http\Requests;

use App\Enums\AppLanguage;
use App\Enums\SettingsTab;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
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

            if(!$confirmedAt || $confirmedAt->diffInMinutes(now()) >= 10) {
                return false;
            }
        }

        return true;
    }

    protected function prepareForValidation()
    {
        $this->mergeIfMissing(['theme' => 'system']);

        if($this->input('current_tab') === SettingsTab::NOTIFICATIONS->value) {
            $this->merge([
                'notify_email' => $this->boolean('notify_email'),
                'subscribe_updates' => $this->boolean('subscribe_updates'),
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
            'theme' => ['required_if:current_tab' . SettingsTab::INTERFACE->value, 'string', 'in:system,light,dark'],
            'timezone' => ['required_if:current_tab' . SettingsTab::INTERFACE->value, 'string', 'timezone'],
            'language' => ['required_if:current_tab' . SettingsTab::INTERFACE->value, 'string', Rule::in(config('app.available_locales'), ['en'])],

            // Rules for Notifications tab
            'notify_email' => ['exclude_unless:current_tab' . SettingsTab::NOTIFICATIONS->value,'required', 'boolean'],
            'subscribe_updates' => ['exclude_unless:current_tab' . SettingsTab::NOTIFICATIONS->value,'required', 'boolean'],

            // Rules for Privacy tab
            'profile_visibility' => ['exclude_unless:current_tab' . SettingsTab::PRIVACY->value, 'required', 'string', 'in:private,public,friend'],
        ];
    }
}
