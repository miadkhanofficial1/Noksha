<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\BrevoMailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    protected BrevoMailService $brevoMailService;

    public function __construct(BrevoMailService $brevoMailService)
    {
        $this->brevoMailService = $brevoMailService;
    }

    /**
     * Display the email submission form to request a password reset OTP.
     */
    public function create()
    {
        return view('auth.passwords.email');
    }

    /**
     * Generate 6-digit OTP, store in database, and dispatch via Brevo service with resilient logging.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower(trim($request->email));

        // 1. Validate user existence
        $user = User::where('email', $email)->select('id', 'name', 'email')->first();
        if (!$user) {
            return back()->withInput()->withErrors([
                'email' => "We can't find a user with that email address."
            ]);
        }

        // 2. Generate secure 6-digit numeric OTP and cryptographically hash with HMAC-SHA256
        $otp = (string) random_int(100000, 999999);
        $hashedOtp = hash_hmac('sha256', $otp, config('app.key'));

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

        // Requirement: Ensure generated 6-digit OTP is also written to storage/logs/laravel.log
        Log::info("Noksha Security: Generated 6-digit OTP [{$otp}] for {$email} (Expires in 15 minutes)");

        // 3. Store session state BEFORE dispatching
        session([
            'password_reset_email' => $email,
            'password_reset_otp_sent_at' => Carbon::now()->timestamp,
        ]);

        // 4. Dispatch email via BrevoMailService (API or SMTP with 10s timeout & stream options)
        $deliveryResult = $this->brevoMailService->sendOtpMail($user->email, $otp, $user->name);

        if ($deliveryResult['success']) {
            session([
                'status' => 'A 6-digit verification code has been dispatched to your email address.'
            ]);
        } else {
            // Requirement: Display a clear banner/flash message on verify-otp page instead of pretending it succeeded
            session([
                'mail_delivery_warning' => 'We were unable to deliver the email to your inbox (' . ($deliveryResult['error'] ?? 'Mail server connection issue') . '). For immediate testing/recovery, your 6-digit code has been logged to storage/logs/laravel.log.',
            ]);
        }

        // 5. Redirect user to the OTP view without infinite loading
        return redirect()->route('password.otp.view');
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

        // Check hash (supports both HMAC-SHA256 and Bcrypt)
        $isMatch = str_starts_with($record->token, '$2y$')
            ? Hash::check($submittedOtp, $record->token)
            : hash_equals($record->token, hash_hmac('sha256', $submittedOtp, config('app.key')));

        if (!$isMatch) {
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

        $user = User::where('email', $email)->select('id', 'name', 'email')->first();
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
        $hashedOtp = hash_hmac('sha256', $otp, config('app.key'));

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

        // Log OTP to storage/logs/laravel.log
        Log::info("Noksha Security: Resent 6-digit OTP [{$otp}] for {$email} (Expires in 15 minutes)");

        session([
            'password_reset_email' => $email,
            'password_reset_otp_sent_at' => Carbon::now()->timestamp,
        ]);

        // Dispatch via Brevo service
        $deliveryResult = $this->brevoMailService->sendOtpMail($user->email, $otp, $user->name);

        if ($deliveryResult['success']) {
            return back()->with('status', 'A new 6-digit verification code has been dispatched to your email.');
        }

        return back()->with('mail_delivery_warning', 'We could not reach the mail server to deliver your code (' . ($deliveryResult['error'] ?? 'Delivery failure') . '). For immediate verification, your new OTP has been recorded in storage/logs/laravel.log.');
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
}
