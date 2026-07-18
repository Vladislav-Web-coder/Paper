<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class SetUserTimeZone
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $matchedTimezone = null;
        $validTimezones = timezone_identifiers_list();

        if($user && isset($user->settings->timezone)) {
            $matchedTimezone = $user->settings->timezone;
        }
        if(!$matchedTimezone || !in_array($matchedTimezone, $validTimezones)) {
            $matchedTimezone = $request->cookie('browser_timezone');
        }
        if($matchedTimezone && in_array($matchedTimezone, $validTimezones)) {
            date_default_timezone_set($matchedTimezone);
            Carbon::setTestNow(now()->setTimezone($matchedTimezone));
            Config::set('app.timezone', $matchedTimezone);
        }
        else {
            date_default_timezone_set(config('app.timezone', 'UTC'));
        }
        return $next($request);
    }
}
