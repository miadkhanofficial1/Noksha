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
            return redirect('/')->with('error', 'Unauthorized access. Administrative security checkpoint triggered.');
        }

        $user = auth()->user();
        if (!$user->isAdmin() && !in_array($user->role ?? 'user', ['admin', 'super_admin'])) {
            return redirect('/')->with('error', 'Access denied. You do not possess administrative privileges.');
        }

        return $next($request);
    }
}
