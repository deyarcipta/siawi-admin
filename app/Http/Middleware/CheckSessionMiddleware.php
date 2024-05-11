<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (Illuminate\Http\Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    if ($request->path() !== 'login' && !$request->session()->has('user')) {
        return redirect('/login');
    }
    
    return $next($request);
}
}
