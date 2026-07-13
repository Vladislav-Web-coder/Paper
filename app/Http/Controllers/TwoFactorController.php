<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    public function __construct(public TwoFactorService $twoFactorService)
    {}

    public function showSetup(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->settings->two_factor_enabled) {
            return redirect()->route('settings.show', ['tab' => 'privacy'])
                ->with('error', 'Two-factor authentication is already enabled.');
        }

        $data = $this->twoFactorService->generateTwoFactorCode($user);

        return view('settings.two-factor-setup', [
            'qrCodeSvg' => $data['svg'],
            'secret' => $data['secret'],
            'recoveryCodes' => $data['codes'],
        ]);
    }

    public function enable(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = auth()->user();

        $activated = $this->twoFactorService->enableTwoFactor($user, $request->code);

        if (! $activated) {
            return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
        }

        return redirect()->route('settings.show', ['tab' => 'privacy'])
            ->with('success', 'Two-factor authentication has been successfully enabled!');
    }

    /**
     * Полное отключение 2FA и очистка защищенных колонок.
     */
    public function disable(): RedirectResponse
    {
        $user = auth()->user();

        $this->twoFactorService->disableTwoFactor($user);

        return redirect()->route('settings.show', ['tab' => 'privacy'])
            ->with('success', 'Two-factor authentication has been disabled.');
    }

    public function showLogin(): View|RedirectResponse
    {
        if (! session()->has('auth.2fa.attempted_user_id')) {
            auth()->logout();
            return redirect()->route('login');
        }

        if (session()->has('2fa_verified')) {
            return redirect()->intended('/dashboard');
        }

        return view('settings.two-factor-login');
    }

    public function verifyLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        if (! session()->has('auth.2fa.attempted_user_id')) {
            auth()->logout();
            return redirect()->route('login');
        }

        $user = auth()->user();

        $verified = $this->twoFactorService->verifyTwoFactor($user, $request->code);

        if (! $verified) {
            return back()->withErrors(['code' => 'Invalid authentication or recovery code.']);
        }

        session()->forget('auth.2fa.attempted_user_id');

        if (session()->has('2fa_remaining_codes_count')) {
            $remaining = session('2fa_remaining_codes_count');
            return redirect()->intended('/dashboard')
                ->with('success', "Successfully logged in using a recovery code. Remaining codes: {$remaining}");
        }

        return redirect()->intended('/dashboard');
    }
}
