<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationOtpMail;
use App\Services\BrevoMailService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    protected BrevoMailService $brevoMailService;

    public function __construct(BrevoMailService $brevoMailService)
    {
        $this->brevoMailService = $brevoMailService;
    }

    /**
     * Display the email verification notice / OTP entry view.
     * Automatically generates and dispatches an OTP if one is not active.
     */
    public function notice(Request $request)
    {
        $user = $request->user();

        if ($user && $user->hasVerifiedEmail()) {
            return redirect()->intended(route('home'));
        }

        $sessionOtp = session('email_verify_otp');
        $expiresAt = session('email_verify_otp_expires_at');

        // If no OTP exists or current one has expired, generate and dispatch a new one
        if (!$sessionOtp || !$expiresAt || now()->timestamp > $expiresAt) {
            $otp = (string) random_int(100000, 999999);

            session([
                'email_verify_otp' => $otp,
                'email_verify_otp_expires_at' => now()->addMinutes(10)->timestamp,
                'email_verify_otp_sent_at' => now()->timestamp,
            ]);
            $request->session()->save();

            $dispatchResult = $this->dispatchEmailVerificationOtp($user, $otp);

            if ($dispatchResult['success']) {
                session([
                    'status' => 'A 6-digit verification code has been dispatched to your email address.',
                ]);
            } else {
                session([
                    'mail_delivery_warning' => 'Notice: We could not deliver email directly (' . ($dispatchResult['error'] ?? 'Mail server connection issue') . '). For testing, your 6-digit code has been logged to storage/logs/laravel.log.',
                ]);
            }
            $request->session()->save();
        }

        $sentAt = session('email_verify_otp_sent_at', 0);
        $resendCooldown = max(0, 30 - (now()->timestamp - $sentAt));

        return view('auth.verify-email', [
            'email' => $user->email,
            'maskedEmail' => $this->maskEmail($user->email),
            'resendCooldown' => $resendCooldown,
        ]);
    }

    /**
     * Verify the entered 6-digit email OTP.
     */
    public function verifyOtp(Request $request)
    {
        $user = $request->user();

        if ($user && $user->hasVerifiedEmail()) {
            return redirect()->intended(route('home'));
        }

        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ], [
            'otp.required' => 'Please enter the 6-digit verification code.',
            'otp.size' => 'The verification code must be exactly 6 digits.',
        ]);

        $submittedOtp = trim((string) $request->input('otp'));
        $sessionOtp = session('email_verify_otp');
        $expiresAt = session('email_verify_otp_expires_at');

        if (empty($sessionOtp) || empty($expiresAt) || now()->timestamp > $expiresAt) {
            return back()->withErrors([
                'otp' => 'The verification code has expired. Please request a new code.',
            ]);
        }

        if (!hash_equals((string) $sessionOtp, $submittedOtp)) {
            return back()->withErrors([
                'otp' => 'The verification code entered is invalid. Please try again.',
            ]);
        }

        // OTP is valid - mark user email as verified
        $user->email_verified_at = now();
        $user->is_verified = true;
        $user->save();

        event(new Verified($user));

        // Clear verification session keys
        $request->session()->forget([
            'email_verify_otp',
            'email_verify_otp_expires_at',
            'email_verify_otp_sent_at',
            'mail_delivery_warning',
        ]);
        $request->session()->regenerate();
        $request->session()->save();

        return redirect()->route('home')->with('status', 'Email verified successfully! Welcome to Noksha.');
    }

    /**
     * Resend a fresh 6-digit email verification OTP.
     */
    public function resendOtp(Request $request)
    {
        $user = $request->user();

        if ($user && $user->hasVerifiedEmail()) {
            return redirect()->intended(route('home'));
        }

        $sentAt = session('email_verify_otp_sent_at', 0);
        if ((now()->timestamp - $sentAt) < 30) {
            return back()->with('warning', 'Please wait a moment before requesting another code.');
        }

        $otp = (string) random_int(100000, 999999);

        session([
            'email_verify_otp' => $otp,
            'email_verify_otp_expires_at' => now()->addMinutes(10)->timestamp,
            'email_verify_otp_sent_at' => now()->timestamp,
        ]);
        $request->session()->save();

        $dispatchResult = $this->dispatchEmailVerificationOtp($user, $otp);

        if ($dispatchResult['success']) {
            return back()->with('status', 'A fresh verification code has been dispatched to your email.');
        }

        return back()->with('mail_delivery_warning', 'Notice: Email delivery encountered an issue (' . ($dispatchResult['error'] ?? 'Connection error') . '). Your fresh 6-digit code has been logged to storage/logs/laravel.log.');
    }

    /**
     * Alias for resendOtp for legacy routes.
     */
    public function resend(Request $request)
    {
        return $this->resendOtp($request);
    }

    /**
     * Legacy link-based verification handler (signed URL backwards compatibility).
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        $user = $request->user();
        if ($user && !$user->is_verified) {
            $user->is_verified = true;
            $user->save();
        }

        return redirect()->route('home')->with('status', 'Email verified successfully! Welcome to Noksha.');
    }

    /**
     * Resilient mail dispatcher: logs OTP, attempts Brevo HTTPS REST API (Port 443),
     * and falls back to Laravel SMTP Mailable.
     */
    protected function dispatchEmailVerificationOtp($user, string $otp): array
    {
        $email = $user->email;
        $name = $user->name ?? 'Noksha Member';

        // 1. Write OTP to log for debugging and local testing verification
        Log::info("Noksha Security: Generated 6-digit email verification OTP [{$otp}] for {$email} (Expires in 10 minutes)");

        // 2. Dispatch via Brevo HTTPS REST API (Port 443 - reliable on cloud hosts)
        $apiResult = $this->brevoMailService->sendEmailVerificationOtpMail($email, $otp, $name, 10);
        if ($apiResult['success']) {
            return $apiResult;
        }

        // 3. Fallback to Laravel Mailable (SMTP)
        try {
            Mail::to($email)->send(new EmailVerificationOtpMail($otp, $name, 10));

            return [
                'success' => true,
                'transport' => 'laravel_mailable_smtp',
                'error' => null,
            ];
        } catch (\Throwable $e) {
            Log::warning('Email Verification OTP Mailable SMTP dispatch failed: ' . $e->getMessage(), [
                'email' => $email,
                'exception' => $e,
            ]);

            return [
                'success' => false,
                'transport' => 'failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Mask email address for UI display (e.g., m***n@example.com).
     */
    protected function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }

        $name = $parts[0];
        $domain = $parts[1];

        $len = strlen($name);
        if ($len <= 2) {
            $maskedName = substr($name, 0, 1) . '*';
        } else {
            $maskedName = substr($name, 0, 1) . str_repeat('*', max(3, $len - 2)) . substr($name, -1);
        }

        return $maskedName . '@' . $domain;
    }
}
