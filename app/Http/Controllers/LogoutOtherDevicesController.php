<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LogoutOtherDevicesController extends Controller
{
    public function __construct(protected TwoFactorService $twoFactorService)
    {}

    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $is2faEnabled = (bool) $user->settings->two_factor_enabled;

        if ($is2faEnabled) {
            $request->validate(['code' => 'required|string']);
            $isValid = $this->twoFactorService->verifyTwoFactor($user, $request->code);
            if (!$isValid) {
                return back()->withErrors(['code_session' => 'Invalid 2FA code.']);
            }
        } else {
            $request->validate(['password' => 'required|string']);
            if (!Hash::check($request->password, $user->password)) {
                return back()->withErrors(['password_session' => 'Incorrect password.']);
            }
        }

        Auth::logoutOtherDevices($request->password ?? $request->code);

        return back()->with('success', 'All other sessions have been logged out.');
    }
}
