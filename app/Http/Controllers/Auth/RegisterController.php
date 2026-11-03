<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('users', 'username')->whereNull('deleted_at'),
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'username.unique' => 'This username is already taken.',
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes and underscores.',
            'email.unique' => 'This email address is already registered.',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => strtolower($request->username),
                'email' => strtolower($request->email),
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'buyer', // Default role = buyer
                'contributor_status' => 'none',
                'active_mode' => 'buyer',
                'trust_score' => 100.00,
                'is_verified' => false,
                'ai_credits' => 0,
            ]);
        } catch (\Throwable $e) {
            Log::error('Registration user creation failed: ' . $e->getMessage(), [
                'exception' => $e,
                'username' => $request->username,
                'email' => $request->email,
            ]);

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['email' => 'Unable to complete registration. Please try again.']);
        }

        // Fire Registered event safely so that background transport/mail delivery issues do not fail user registration
        try {
            event(new Registered($user));
        } catch (\Throwable $e) {
            Log::warning('Registration email verification dispatch failed (SMTP/transport error): ' . $e->getMessage(), [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        }

        // Immediately authenticate the newly registered user
        Auth::login($user);

        // Regenerate session for security
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('status', 'Registration successful! Welcome to Noksha.');
    }
}
