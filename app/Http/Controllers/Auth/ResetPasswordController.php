<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ResetPasswordController extends Controller
{
    /**
     * Display the reset password form guarded by verified OTP session.
     */
    public function create(Request $request)
    {
        $verifiedEmail = session('password_reset_verified_email');
        $verifiedAt = session('password_reset_verified_at', 0);

        // Enforce 15-minute validity window on verified OTP session
        if (!$verifiedEmail || (Carbon::now()->timestamp - $verifiedAt) > 900) {
            return redirect()->route('password.request')->withErrors([
                'email' => 'Verification session has expired or is invalid. Please request a new verification code.'
            ]);
        }

        return view('auth.passwords.reset', [
            'email' => $verifiedEmail,
        ]);
    }

    /**
     * Handle the incoming new password request with strict Password History Guard.
     */
    public function store(Request $request)
    {
        $verifiedEmail = session('password_reset_verified_email');
        $verifiedAt = session('password_reset_verified_at', 0);

        if (!$verifiedEmail || (Carbon::now()->timestamp - $verifiedAt) > 900) {
            return redirect()->route('password.request')->withErrors([
                'email' => 'Verification session expired. Please verify your email again.'
            ]);
        }

        $request->validate([
            'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The password must be at least 8 characters long.',
        ]);

        $user = User::where('email', $verifiedEmail)->first();
        if (!$user) {
            return redirect()->route('password.request')->withErrors([
                'email' => "We can't find an account associated with this email address."
            ]);
        }

        // Strict Password History Guard: New password must NOT match current password
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Your new password cannot be the same as your existing password. Please choose a distinct, secure password.'
            ]);
        }

        // Update password and regenerate remember token
        try {
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        } catch (\Throwable $e) {
            Log::error('Failed to update user password: ' . $e->getMessage(), [
                'email' => $verifiedEmail,
                'exception' => $e,
            ]);

            return back()->withErrors([
                'password' => 'An unexpected error occurred while updating your password. Please try again.'
            ]);
        }

        // Step 4 (Completion): Invalidate OTP in DB and flush reset sessions
        try {
            DB::table('password_reset_tokens')->where('email', $verifiedEmail)->delete();
        } catch (\Throwable $e) {
            Log::warning('Notice: failed to delete password reset token record: ' . $e->getMessage());
        }

        session()->forget([
            'password_reset_email',
            'password_reset_otp_sent_at',
            'password_reset_verified_email',
            'password_reset_verified_at',
        ]);

        // Redirect directly to login with success flash message
        return redirect()->route('login')->with(
            'status',
            'Password reset successful! Please log in with your new password.'
        );
    }
}
