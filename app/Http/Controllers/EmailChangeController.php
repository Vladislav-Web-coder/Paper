<?php

namespace App\Http\Controllers;

use App\Notifications\EmailChangeRequestedNotification;
use App\Notifications\VerifyNewEmailNotification;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

class EmailChangeController extends Controller
{
    public function __construct(public TwoFactorService $twoFactorService)
    {}

    public function __invoke(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'new_email' => ['required', 'email', 'unique:users,email'],
        ];
        $is2faEnabled = $user->settings->two_factor_enabled;
        if($is2faEnabled) {
            $rules['code'] = ['required', 'string'];
        }
        else {
            $rules['current_password'] = ['required', 'string'];
        }
        $request->validate([
            $rules
        ]);

        if($is2faEnabled) {
            if(!$this->twoFactorService->verifyTwoFactor($user, $request->code)) {
                return back()->withErrors(['code' => 'Invalid 2FA verification code.']);
            }
        }
        else {
            if(!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
            }
        }
        $newEmail = $request->new_email;

        $confirmationUrl = URL::temporarySignedRoute(
            'settings.email.change.confirm',
            now()->addMinutes(30),
            ['user' => $user->id, 'new_email' => $newEmail]
        );

        Notification::route('mail', $newEmail)
            ->notify(new VerifyNewEmailNotification($confirmationUrl));

        $user->notify(new EmailChangeRequestedNotification($newEmail));

        return back()->with('success', 'A confirmation link has been sent to your new email address');
    }

}

