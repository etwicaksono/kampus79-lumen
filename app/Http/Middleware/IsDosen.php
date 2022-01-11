<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsDosen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pre-Middleware Action

        if (Auth::user()->role != "dosen") {
            return response('Unauthorized.', 401);
        }

        $response = $next($request);

        // Post-Middleware Action

        return $response;
    }
}