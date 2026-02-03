<?php

namespace App\Http\Middleware;

use Closure;

class Authorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $ability,$guard = 'admin')
    {
        if(!auth($guard)->user()->hasAccess($ability)){
            abort(403, 'This action is unauthorized.');
        }

        return $next($request);
    }
}
