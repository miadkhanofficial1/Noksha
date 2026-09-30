<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'username.unique' => 'This username is already taken.',
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes and underscores.',
            'email.unique' => 'This email address is already registered.',
        ]);

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

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('status', 'Registration successful! Please verify your email address to unlock full marketplace features.');
    }
}
