<?php

namespace App\Http\Controllers;

use App\Enums\SettingsTab;
use App\Http\Requests\ConfirmPasswordRequest;
use App\Http\Requests\UpdateSettingsRequest;
use App\Services\SettingsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(protected SettingsService $settingsService)
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

        $additionalData = $this->settingsService->getTabData($user, $tab);

        return view('settings.index', [
            'currentTab' => $tab,
            'settings' => $user->settings,
        ], $additionalData);
    }

    public function confirmPassword(ConfirmPasswordRequest $request)
    {
        session(['last_confirmed_password_at' => now()]);
        return redirect()->route('settings.show', SettingsTab::PRIVACY->value);
    }

    public function update(UpdateSettingsRequest $request)
    {
        $tab = $request->current_tab;

        if(!in_array($tab, SettingsTab::values())) {
            abort(400, 'Invalid setting tab');
        }
        $user = $request->user();

        $this->settingsService->updateTabData($user, $tab, $request->validated());

        return redirect()
            ->route('settings.show', $tab)
            ->with('success', 'Settings updated successfully');
    }
}
