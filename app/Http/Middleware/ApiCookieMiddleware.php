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
        // Only if guest_cart_id not already set
        if (!$request->hasCookie('guest_unique_id')) {
            $guestId = (string)Str::uuid();

            Cookie::queue('guest_unique_id', $guestId, 60 * 24 * 30);
        }
        return $next($request);
    }
}
