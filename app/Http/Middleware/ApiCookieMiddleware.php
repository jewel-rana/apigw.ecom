<?php

namespace App\Http\Middleware;

use App\Helpers\CommonHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        Log::info('guest_unique_id in request: ' . $request->cookie('guest_unique_id'));

        $response = $next($request);

        $guestId = $request->cookie('guest_unique_id', $request->header('X-GUEST-ID'));

        if (!$guestId) {
            $guestId = CommonHelper::generateUniqueUUID();

            $request->merge(['guest_unique_id' => $guestId]);

            $response->withCookie(
                cookie('guest_unique_id', $guestId, 60 * 24 * 30)
            );
        }

        return $response;
    }
}
