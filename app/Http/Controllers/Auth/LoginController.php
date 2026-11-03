<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Determine if login input is an email or username
        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $fieldType => strtolower($request->login),
            'password' => $request->password,
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'login' => trans('auth.failed', [], 'en') ?: 'These credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();
        $request->session()->save();

        // Clear any stale intended URLs (e.g. /wallet) so successful login always lands cleanly on Homepage
        $request->session()->forget('url.intended');

        return $this->authenticated($request, Auth::user())
            ?: redirect()->route('home')->with('status', 'Welcome back to Noksha!');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin' || $user->is_admin) {
            return redirect()->route('admin.dashboard')->with(
                'status',
                'Welcome to Admin HQ, ' . ($user->name ?: 'Admin') . '!'
            );
        }

        return redirect()->intended(route('home'))->with(
            'status',
            'Welcome back, ' . ($user->name ?: 'Member') . '!'
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('status', 'You have been logged out successfully.');
    }
}
