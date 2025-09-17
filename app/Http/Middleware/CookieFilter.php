<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CookieFilter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // extract cookie and set it in authorization header
        $cookie = $request->cookie('experts_platform_token');
        if($cookie)
        {
            // dd('cookie found in middleware: '.$cookie);
            $request->headers->set('Authorization', 'Bearer '.$cookie);
        }
        return $next($request);
    }
}
