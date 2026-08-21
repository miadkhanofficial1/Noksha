<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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

        return redirect()->route('home')->with('status', 'Email verified successfully! Welcome to Noksha. / ইমেইল সফলভাবে যাচাই করা হয়েছে! নকশায় আপনাকে স্বাগতম।');
    }

    /**
     * Send a new email verification notification.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home'));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Verification link sent! Please check your inbox. / যাচাইকরণ লিংক পাঠানো হয়েছে! অনুগ্রহ করে আপনার ইনবক্স চেক করুন।');
    }
}
