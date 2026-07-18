<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class LogoutOtherDevicesController extends Controller
{
    public function __construct(protected TwoFactorService $twoFactorService)
    {}

    public function __invoke(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $is2faEnabled = (bool) ($user->settings->two_factor_enabled ?? false);

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

        $currentSessionId = session()->getId();
        $redis = Redis::connection(config('session.connection', 'default'));
        $userSessionsKey = "user:sessions:{$user->id}";

        $allSessionIds = $redis->smembers($userSessionsKey);

        if (!empty($allSessionIds)) {
            $sessionCookieName = config('session.cookie', 'laravel_session');

            $otherSessionIds = array_filter($allSessionIds, function ($id) use ($currentSessionId) {
                return $id !== $currentSessionId;
            });

            if (!empty($otherSessionIds)) {
                $redisKeysToDelete = array_map(function ($id) use ($sessionCookieName) {
                    return "{$sessionCookieName}:{$id}";
                }, $otherSessionIds);

                $redis->del($redisKeysToDelete);

                $redis->srem($userSessionsKey, ...$otherSessionIds);
            }
        }

        Auth::logoutOtherDevices($request->password ?? $request->user()->password);

        return back()->with('success', 'All other sessions have been logged out.');
    }
}
