<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApprovedContributor
{
    /**
     * Handle an incoming request.
     * Ensure the authenticated user is an approved contributor or administrator.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('warning', 'Please log in to access creator features.');
        }

        $user = auth()->user();

        if (!$user->isApprovedContributor()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be an approved contributor to access seller tools.',
                ], 403);
            }

            return redirect()->route('contributor.apply')
                ->with('warning', 'You must be an approved contributor to access seller tools.')
                ->with('error', 'You must be an approved contributor to access seller tools.');
        }

        return $next($request);
    }
}
