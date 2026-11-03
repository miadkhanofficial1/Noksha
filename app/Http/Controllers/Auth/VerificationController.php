<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    /**
     * Display the email verification notice.
     */
    public function notice(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(route('home'))
            : view('auth.verify');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('home')->with('status', 'Email verified successfully! Welcome to Noksha.');
    }

    /**
     * Send a new email verification notification.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home'));
        }

        try {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('status', 'Verification link sent! Please check your inbox.');
        } catch (\Throwable $e) {
            Log::warning('Resend email verification notification failed: ' . $e->getMessage());
            return back()->with('warning', 'Unable to send verification email right now. Please try again later.');
        }
    }
}
