<?php

namespace App\Http\Middleware;

use App\Enums\AppLanguage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;
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
       $supportedLanguage = AppLanguage::values();
       $fallbackLanguage = config('app.fallback_locale', 'en');
        $user = $request->user();

       if($user && isset($user->settings->language)) {
           App::setLocale($user->settings->language);
           return $next($request);
       }

       $agent = new Agent();
       $browserLanguage = $agent->languages();
       $matchedLocale = $fallbackLanguage;

       foreach ($browserLanguage as $lang) {
           $cleanLang = strtolower(substr($lang, 0, 2));

           if(in_array($cleanLang, $supportedLanguage)) {
               $matchedLocale = $cleanLang;
               break;
           }
       }

       Session::put('locale', $matchedLocale);
       App::setLocale($matchedLocale);

        return $next($request);
    }
}
