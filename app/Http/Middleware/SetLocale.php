<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request and set application locale from session.
     * Default language: Bangla ('bn').
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', config('app.locale', 'bn'));
        
        if (in_array($locale, ['bn', 'en'])) {
            app()->setLocale($locale);
        } else {
            app()->setLocale('bn');
        }

        return $next($request);
    }
}
