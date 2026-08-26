<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('warning', 'Please log in to access administrative pages.');
        }

        $userRole = auth()->user()->role ?? 'user';
        if (!in_array($userRole, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized access. Super Admin privileges required.');
        }

        return $next($request);
    }
}
