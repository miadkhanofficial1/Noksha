<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;

class ForgotPasswordController extends Controller
{
    /**
     * Display the form to request a password reset link.
     */
    public function create()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // 1. Validate whether the user with given email actually exists first
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => "We can't find a user with that email address."]);
        }

        // 2. Safeguard: ensure password_reset_tokens table exists dynamically
        try {
            if (!Schema::hasTable('password_reset_tokens')) {
                Schema::create('password_reset_tokens', function (Blueprint $table) {
                    $table->string('email')->primary();
                    $table->string('token');
                    $table->timestamp('created_at')->nullable();
                });
            }
        } catch (\Throwable $e) {
            Log::warning('Dynamic password_reset_tokens table check warning: ' . $e->getMessage());
        }

        // 3. Graceful send reset link with try-catch safeguard
        try {
            $status = Password::sendResetLink($request->only('email'));
        } catch (\Throwable $e) {
            Log::error('Password reset email dispatch failed: ' . $e->getMessage(), [
                'email' => $request->email,
                'exception' => $e,
            ]);

            return back()->withErrors([
                'email' => 'Unable to send password reset email at this moment. Please verify your connection or try again shortly.'
            ]);
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
