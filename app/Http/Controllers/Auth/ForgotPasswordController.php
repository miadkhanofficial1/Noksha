<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class ForgotPasswordController extends Controller
{
    /**
     * Display the email submission form to request a password reset OTP.
     */
    public function create()
    {
        return view('auth.passwords.email');
    }

    /**
     * Generate 6-digit OTP, store in database, and dispatch via Brevo SMTP.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower(trim($request->email));

        // 1. Validate user existence
        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->withInput()->withErrors([
                'email' => "We can't find a user with that email address."
            ]);
        }

        // 2. Ensure password_reset_tokens schema is ready
        $this->ensureSchemaReady();

        // 3. Generate secure 6-digit numeric OTP and hash it
        $otp = (string) random_int(100000, 999999);
        $hashedOtp = Hash::make($otp);

        try {
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => $hashedOtp,
                    'expires_at' => Carbon::now()->addMinutes(15),
                    'attempts' => 0,
                    'created_at' => Carbon::now(),
                ]
            );
        } catch (\Throwable $e) {
            Log::error('Failed to store password reset OTP: ' . $e->getMessage(), [
                'email' => $email,
            ]);

            return back()->withInput()->withErrors([
                'email' => 'Unable to initiate password reset at this moment. Please try again shortly.'
            ]);
        }

        // 4. Dispatch instant HTML email via configured Brevo SMTP (Port 465 SSL)
        try {
            Mail::to($user->email)->send(new PasswordResetOtpMail($otp, $user, 15));
        } catch (\Throwable $e) {
            Log::error('Password reset OTP email dispatch failed: ' . $e->getMessage(), [
                'email' => $email,
                'exception' => $e,
            ]);

            // Resilient fallback protection for cloud network timeouts
            return back()->withInput()->withErrors([
                'email' => 'Unable to dispatch verification code via email server right now. Please verify your connection or try again shortly.'
            ]);
        }

        // 5. Store session state and redirect to OTP verification screen
        session([
            'password_reset_email' => $email,
            'password_reset_otp_sent_at' => Carbon::now()->timestamp,
        ]);

        return redirect()->route('password.otp.view')->with(
            'status',
            'A 6-digit verification code has been dispatched to your email address.'
        );
    }

    /**
     * Display the 6-digit split OTP input view.
     */
    public function showVerifyOtp()
    {
        $email = session('password_reset_email');

        if (!$email) {
            return redirect()->route('password.request')->withErrors([
                'email' => 'Please enter your registered email address to receive a verification code.'
            ]);
        }

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        // Mask email for display (e.g., m***n@domain.com)
        $maskedEmail = $this->maskEmail($email);

        $sentAt = session('password_reset_otp_sent_at', Carbon::now()->timestamp);
        $elapsed = Carbon::now()->timestamp - $sentAt;
        $cooldown = max(0, 60 - $elapsed);

        $expiresAtTimestamp = $record && $record->expires_at
            ? Carbon::parse($record->expires_at)->timestamp
            : Carbon::now()->addMinutes(15)->timestamp;

        return view('auth.passwords.verify-otp', [
            'email' => $email,
            'maskedEmail' => $maskedEmail,
            'cooldown' => $cooldown,
            'expiresAtTimestamp' => $expiresAtTimestamp,
        ]);
    }

    /**
     * Verify the 6-digit split OTP input.
     */
    public function verifyOtp(Request $request)
    {
        $email = session('password_reset_email') ?? $request->input('email');

        if (!$email) {
            return redirect()->route('password.request')->withErrors([
                'email' => 'Verification session expired. Please enter your email address again.'
            ]);
        }

        // Support both single "otp" input or array of split "digit" inputs
        $submittedOtp = $request->input('otp');
        if (is_array($request->input('digit'))) {
            $digits = array_filter($request->input('digit'), fn($d) => $d !== null && $d !== '');
            $submittedOtp = implode('', $digits);
        }

        $submittedOtp = preg_replace('/\s+/', '', (string) $submittedOtp);

        if (strlen($submittedOtp) !== 6 || !ctype_digit($submittedOtp)) {
            return back()->withInput()->withErrors([
                'otp' => 'Please enter the complete 6-digit numeric verification code.'
            ]);
        }

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record) {
            return back()->withErrors([
                'otp' => 'No active verification code found for this email. Please request a new code.'
            ]);
        }

        // Check attempt limit (max 5)
        if ($record->attempts >= 5) {
            return back()->withErrors([
                'otp' => 'Maximum verification attempts exceeded. For your security, this code has been revoked. Please request a new code.'
            ]);
        }

        // Check expiration
        if ($record->expires_at && Carbon::parse($record->expires_at)->isPast()) {
            return back()->withErrors([
                'otp' => 'This verification code has expired. Please request a new code.'
            ]);
        }

        // Check hash
        if (!Hash::check($submittedOtp, $record->token)) {
            DB::table('password_reset_tokens')->where('email', $email)->increment('attempts');
            $remaining = max(0, 5 - ($record->attempts + 1));

            return back()->withErrors([
                'otp' => "Incorrect verification code. {$remaining} attempt(s) remaining."
            ]);
        }

        // OTP verified successfully: mark session as verified
        session([
            'password_reset_verified_email' => $email,
            'password_reset_verified_at' => Carbon::now()->timestamp,
        ]);

        return redirect()->route('password.reset.view')->with(
            'status',
            'Identity verified successfully! Please set your new password.'
        );
    }

    /**
     * Resend a fresh 6-digit OTP code to the user.
     */
    public function resendOtp(Request $request)
    {
        $email = session('password_reset_email') ?? $request->input('email');

        if (!$email) {
            return redirect()->route('password.request')->withErrors([
                'email' => 'Session expired. Please enter your email address.'
            ]);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('password.request')->withErrors([
                'email' => "We can't find a user with that email address."
            ]);
        }

        // Enforce 60-second cooldown
        $lastSent = session('password_reset_otp_sent_at', 0);
        $elapsed = Carbon::now()->timestamp - $lastSent;
        if ($elapsed < 60) {
            $remainingWait = 60 - $elapsed;
            return back()->withErrors([
                'otp' => "Please wait {$remainingWait} second(s) before requesting another code."
            ]);
        }

        // Generate new 6-digit OTP
        $otp = (string) random_int(100000, 999999);
        $hashedOtp = Hash::make($otp);

        try {
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => $hashedOtp,
                    'expires_at' => Carbon::now()->addMinutes(15),
                    'attempts' => 0,
                    'created_at' => Carbon::now(),
                ]
            );
        } catch (\Throwable $e) {
            Log::error('Failed to update password reset OTP on resend: ' . $e->getMessage());
            return back()->withErrors(['otp' => 'Unable to generate code right now. Please try again shortly.']);
        }

        // Dispatch email via Brevo SMTP inside safe try-catch
        try {
            Mail::to($user->email)->send(new PasswordResetOtpMail($otp, $user, 15));
        } catch (\Throwable $e) {
            Log::error('Resend OTP email delivery failed: ' . $e->getMessage(), [
                'email' => $email,
                'exception' => $e,
            ]);

            return back()->withErrors([
                'otp' => 'Unable to dispatch email at this moment. Please check your connection or try again shortly.'
            ]);
        }

        session([
            'password_reset_email' => $email,
            'password_reset_otp_sent_at' => Carbon::now()->timestamp,
        ]);

        return back()->with('status', 'A new 6-digit verification code has been dispatched to your email.');
    }

    /**
     * Mask email for display in UI.
     */
    protected function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1] ?? '';

        if (strlen($name) <= 2) {
            $maskedName = substr($name, 0, 1) . '*';
        } else {
            $maskedName = substr($name, 0, 1) . str_repeat('*', max(1, strlen($name) - 2)) . substr($name, -1);
        }

        return $maskedName . '@' . $domain;
    }

    /**
     * Ensure password_reset_tokens table and columns exist dynamically.
     */
    protected function ensureSchemaReady(): void
    {
        try {
            if (!Schema::hasTable('password_reset_tokens')) {
                Schema::create('password_reset_tokens', function (Blueprint $table) {
                    $table->string('email')->primary();
                    $table->string('token');
                    $table->timestamp('expires_at')->nullable();
                    $table->integer('attempts')->default(0);
                    $table->timestamp('created_at')->nullable();
                });
            } else {
                Schema::table('password_reset_tokens', function (Blueprint $table) {
                    if (!Schema::hasColumn('password_reset_tokens', 'expires_at')) {
                        $table->timestamp('expires_at')->nullable()->after('token');
                    }
                    if (!Schema::hasColumn('password_reset_tokens', 'attempts')) {
                        $table->integer('attempts')->default(0)->after('expires_at');
                    }
                });
            }
        } catch (\Throwable $e) {
            Log::warning('Password reset table safeguard notice: ' . $e->getMessage());
        }
    }
}
