<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTwoFactor
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->settings->two_factor_enabled) {

            if (! session()->has('2fa_verified')) {

                session(['auth.2fa.attempted_user_id' => $user->id]);

                return redirect()->route('2fa.login');
            }
        }

        return $next($request);
    }
}
