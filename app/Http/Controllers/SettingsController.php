<?php

namespace App\Http\Controllers;

use App\Enums\SettingsTab;
use App\Http\Requests\ConfirmPasswordRequest;
use App\Http\Requests\UpdateSettingsRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {}

    public function show(Request $request, ?string $tab = 'interface')
    {
        if(!in_array($tab, SettingsTab::values())) {
            return redirect()
                ->route('settings.show', SettingsTab::INTERFACE->value)
                ->with('error', 'Invalid tab');
        }

        if($tab === SettingsTab::PRIVACY->value) {
            $confirmedAt = session('last_confirmed_password_at');

            if(!$confirmedAt || Carbon::parse($confirmedAt)->diffInMinutes(now()) >= 10) {
                session(['url.intended' => route('settings.show', SettingsTab::PRIVACY->value)]);
                return view('settings.confirm_password');
            }
        }
        $user = $request->user();

        return view('settings.index', [
            'user' => $user,
            'currentTab' => $tab,
            'settings' => $user->settings,
        ]);
    }

    public function confirmPassword(ConfirmPasswordRequest $request)
    {
        session(['last_confirmed_password_at' => now()]);
        return redirect()->route('settings.show', SettingsTab::PRIVACY->value);
    }

    public function update(UpdateSettingsRequest $request)
    {
        return match ($request->input('current_tab')) {
            SettingsTab::INTERFACE->value => $this->updateInterface($request),
            SettingsTab::NOTIFICATIONS->value => $this->updateNotifications($request),
            SettingsTab::PRIVACY->value => $this->updatePrivacy($request),
            default => abort(400, 'Invalid settings tab'),
        };
    }

    public function updateInterface(UpdateSettingsRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        unset($data['current_tab']);

        $newSettings = $user->settings->update($data);
        $user->settings = $newSettings;
        $user->save();

        return redirect()
            ->route('settings.show', SettingsTab::INTERFACE->value)
            ->with('success', 'Interface settings updated successfully');
    }

    public function updateNotifications(UpdateSettingsRequest $request)
    {
        $data = $request->validated();;
        $user = $request->user();
        unset($data['current_tab']);

        $newSettings = $user->settings->update($data);
        $user->settings = $newSettings;
        $user->save();

        return redirect()
            ->route('settings.show', SettingsTab::NOTIFICATIONS->value)
            ->with('success', 'Notifications settings updated successfully');
    }

    protected function updatePrivacy(UpdateSettingsRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        unset($data['current_tab']);

        $newSettings = $user->settings->update($data);
        $user->settings = $newSettings;
        $user->save();

        return redirect()
            ->route('settings.show', SettingsTab::PRIVACY->value)
            ->with('success', 'Privacy settings updated successfully');
    }
}
