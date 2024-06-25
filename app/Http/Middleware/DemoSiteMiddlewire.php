<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoSiteMiddlewire
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('league_data') || session()->has('umpire_data') || session()->has('admin_data')) {
            return $next($request);
        } else {
            return redirect(env('LIVE_SITE') . 'advertisement');
        }
    }
}
