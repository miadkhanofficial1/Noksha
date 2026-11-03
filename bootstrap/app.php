<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust all upstream reverse proxies (Render, Cloudflare, AWS ELB)
        $middleware->trustProxies(at: '*');

        $middleware->redirectTo(
            guests: '/login',
            users: \App\Providers\RouteServiceProvider::HOME,
        );

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'contributor' => \App\Http\Middleware\EnsureApprovedContributor::class,
            'EnsureApprovedContributor' => \App\Http\Middleware\EnsureApprovedContributor::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Gracefully handle CSRF token expiration instead of showing a raw 419 error page
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            return redirect()
                ->back()
                ->withInput($request->except('password', 'password_confirmation', '_token'))
                ->with('warning', 'Your session has expired. Please submit the form again.');
        });
    })->create();
