<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string[]  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user role matches one of allowed roles (Multi-Role Support)
        if (!empty($roles) && !$user->hasAnyRole($roles)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }

            return redirect('/admin/dashboard')->with('failed', 'Anda tidak memiliki hak akses untuk membuka halaman tersebut.');
        }

        return $next($request);
    }
}
