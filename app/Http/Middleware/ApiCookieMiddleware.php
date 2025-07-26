<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ApiCookieMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$request->hasCookie('guest_unique_id')) {
            $guestId = (string) Str::uuid();

            $response->withCookie(
                cookie('guest_unique_id', $guestId, 60 * 24 * 30)
            );

            // Option 2: headers->setCookie (more control)
            // use Symfony\Component\HttpFoundation\Cookie;
            // $response->headers->setCookie(new Cookie('guest_unique_id', $guestId, time() + 60 * 60 * 24 * 30));
        }

        return $response;
    }
}
