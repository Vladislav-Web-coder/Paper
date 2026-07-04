<?php

namespace App\Http\Middleware;

use App\Enums\AppLanguage;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAppLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = config('app.fallback_locale');

        if(auth()->check() && auth()->user()->settings?->language) {
            $locale = auth()->user()->settings->language;
        }
        elseif (session()->has('locale')) {
            $locale = session('locale');
        }
        else {
            $browserLocale = $request->getPreferredLanguage(AppLanguage::options());

            if($browserLocale) {
                $locale = $browserLocale;
            }
        }
        app()->setLocale($locale);

        return $next($request);
    }
}
