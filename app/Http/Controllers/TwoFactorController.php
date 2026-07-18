<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    public function __construct(protected TwoFactorService $service)
    {}

    public function showSetup(): RedirectResponse|View
    {
        $user = auth()->user();

        if ($user->settings->two_factor_enabled) {
            return redirect()->route('settings.show', ['tab' => 'privacy'])->with('error', '2FA already enabled');
        }

        $data = $this->service->generateTwoFactorCode($user);
        return view('settings.two-factor-setup', [
            'qrCodeSvg' => $data['svg'],
            'recoveryCodes' => $data['codes'],
            'secret' => $data['secret'],
        ]);
    }
    public function enable(Request $request): RedirectResponse
    {
        $request->validate(['code' => 'required|digits:6']);
        $code = $request->code;
        $user = auth()->user();

        $activated = $this->service->enableTwoFactor($user, $code);
        if (!$activated) {
            return back()->withErrors(['code' => 'Invalid verification code']);
        }
        return redirect()->route('settings.show', ['tab' => 'privacy'])->with('success', 'Two factor authentication enabled.');
    }
    public function disable(): RedirectResponse
    {
        $user = auth()->user();
        $this->service->disableTwoFactor($user);
        return redirect()->route('settings.show', ['tab' => 'privacy'])->with('success', '2FA disabled.');
    }
    public function showLogin(): RedirectResponse|View
    {
        if(!session()->has('auth.2fa.attempted_user_id')) {
            auth()->logout();
            return redirect()->route('login');
        }
        if(session()->has('2fa_verified')) {
            return redirect()->intended(route('dashboard'));
        }
        return view('settings.two-factor-login');
    }
    public function verifyLogin(Request $request): RedirectResponse
    {
        $request->validate(['code' => 'required|string']);
        $user = auth()->user();

        $verified = $this->service->verifyTwoFactor($user, $request->code);

        if (!$verified) {
            return back()->withErrors(['code' => 'Invalid verification code']);
        }
        session()->forget('auth.2fa.attempted_user_id');

        if(session()->has('2fa_remaining_codes_count')) {
            $remaining = session('2fa_remaining_codes_count');
            return redirect()
                ->intended(route('dashboard'))
                ->with('success', "Logged in using a recovery code. Remaining codes: {$remaining}");
        }

        return redirect()->intended('/dashboard');
    }

}
