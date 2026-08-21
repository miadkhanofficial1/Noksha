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
            'username.unique' => 'This username is already taken. / এই ইউজারনেমটি ইতিমধ্যে নেওয়া হয়েছে।',
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes and underscores. / ইউজারনেমে কেবল অক্ষর, সংখ্যা, ড্যাশ এবং আন্ডারস্কোর থাকতে পারে।',
            'email.unique' => 'This email address is already registered. / এই ইমেইল ঠিকানাটি ইতিমধ্যে নিবন্ধিত।',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role = user (Buyer & Contributor)
            'trust_score' => 100.00,
            'is_verified' => false,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('status', 'Registration successful! Please verify your email address to unlock full marketplace features. / নিবন্ধন সফল হয়েছে! পুরো মার্কেটপ্লেস সুবিধা পেতে আপনার ইমেইল যাচাই করুন।');
    }
}
