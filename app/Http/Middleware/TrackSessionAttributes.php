<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackSessionAttributes
{
    public function handle(Request $request, Closure $next): Response
    {
        $session = $request->session();

        if (!$session->has('ip_address') || $session->get('ip_address') !== $request->ip()) {
            $session->put('ip_address', $request->ip());
        }

        if (!$session->has('user_agent') || $session->get('user_agent') !== $request->userAgent()) {
            $session->put('user_agent', $request->userAgent());
        }

        if (!$session->has('last_activity')) {
            $session->put('last_activity', now()->timestamp);
        } else {
            // Обновляем время активности раз в минуту, чтобы не перегружать Redis на каждый клик
            if (now()->timestamp - $session->get('last_activity') > 60) {
                $session->put('last_activity', now()->timestamp);
            }
        }

        return $next($request);
    }
}
