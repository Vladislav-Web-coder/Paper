<?php

namespace App\Services;

use App\Notifications\TwoFactorEnabledNotification;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Str;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class TwoFactorService
{
    public function generateTwoFactorCode($user): array
    {
        if (session()->has('auth.2fa_setup.secret')) {
            $secret = session('auth.2fa_setup.secret');
            $recoveryCodes = session('auth.2fa_setup.codes');
        } else {
            $secret = Google2FA::generateSecretKey();
            $recoveryCodes = collect(range(1, 8))->map(function () {
                return Str::upper(Str::random(4) . '-' . Str::random(4));
            })->toArray();
            session([
                'auth.2fa_setup.secret' => $secret,
                'auth.2fa_setup.codes' => $recoveryCodes
            ]);
        }

        $qrUrl = Google2FA::getQRCodeUrl(config('app.name'), $user->email, $secret);
        $renderer = new ImageRenderer(new RendererStyle(200), new SvgImageBackEnd());
        $writer = new Writer($renderer);

        return [
            'svg' => $writer->writeString($qrUrl),
            'secret' => $secret,
            'codes' => $recoveryCodes
        ];
    }

    public function enableTwoFactor($user, string $code): bool
    {
        if (!session()->has('auth.2fa_setup.secret')) {
            return false;
        }

        $secret = session('auth.2fa_setup.secret');
        $codes = session('auth.2fa_setup.codes');

        $valid = Google2FA::verifyKey($secret, $code);

        if (!$valid) {
            return false;
        }

        $user->two_factor_secret = $secret;
        $user->two_factor_recovery_codes = $codes;
        $user->settings = $user->settings->update(['two_factor_enabled' => true]);
        $user->save();
        $user->notify(new TwoFactorEnabledNotification());

        session()->forget(['auth.2fa_setup.secret', 'auth.2fa_setup.codes']);

        return true;
    }

    public function disableTwoFactor($user): void {
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->settings = $user->settings->update(['two_factor_enabled' => false]);
        $user->save();
    }
    public function verifyTwoFactor($user, $code) {
        $inputCode = Str::upper(trim($code));

        if(is_numeric($inputCode) && strlen($inputCode) == 6) {
            if(Google2FA::verifyKey($user->two_factor_secret, $inputCode)) {
                 session(['2fa_verified' => true]);
                return true;
            }
        }

        $recoveryCodes = $user->two_factor_recovery_codes ?? [];

        if(in_array($inputCode, $recoveryCodes, true)) {
            $updatedCodes = array_values(array_diff($recoveryCodes, [$inputCode]));
            $user->two_factor_recovery_codes = $updatedCodes;
            $user->save();

            session(['2fa_verified' => true]);

            session()->flash('2fa_remaining_codes_count', count($updatedCodes));
            return true;
        }
        return false;
    }
}
