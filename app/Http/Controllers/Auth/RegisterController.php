<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationOtpMail;
use App\Models\User;
use App\Services\BrevoMailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    protected BrevoMailService $brevoMailService;

    public function __construct(BrevoMailService $brevoMailService)
    {
        $this->brevoMailService = $brevoMailService;
    }

    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Step 1: Validate registration inputs, hold pending data in session,
     * generate 6-digit OTP, dispatch email, and redirect to OTP verification screen.
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

        $email = strtolower(trim($request->email));
        $username = strtolower(trim($request->username));

        // Generate 6-digit numeric OTP
        $otp = (string) random_int(100000, 999999);

        // Store pending user payload and OTP in session (DO NOT save to database yet)
        session([
            'pending_user' => [
                'name' => $request->name,
                'username' => $username,
                'email' => $email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'buyer',
                'contributor_status' => 'none',
                'active_mode' => 'buyer',
                'trust_score' => 100.00,
                'is_verified' => true,
                'email_verified_at' => now(),
                'ai_credits' => 0,
            ],
            'register_otp' => $otp,
            'register_otp_expires_at' => now()->addMinutes(10)->timestamp,
            'register_otp_sent_at' => now()->timestamp,
        ]);
        $request->session()->save();

        // Dispatch OTP email
        $dispatchResult = $this->dispatchRegistrationOtp($email, $otp, $request->name);

        if ($dispatchResult['success']) {
            session([
                'status' => 'A 6-digit verification code has been dispatched to ' . $email . '. Please check your inbox.',
            ]);
        } else {
            session([
                'mail_delivery_warning' => 'Notice: We could not deliver email directly (' . ($dispatchResult['error'] ?? 'Mail server connection issue') . '). For immediate testing, your 6-digit code has been logged to storage/logs/laravel.log.',
            ]);
        }
        $request->session()->save();

        return redirect()->route('register.verify.otp');
    }

    /**
     * Step 2 (View): Display the 6-digit OTP verification screen.
     */
    public function showVerifyOtp(Request $request)
    {
        $pendingUser = session('pending_user');

        if (!$pendingUser) {
            return redirect()->route('register')->withErrors([
                'email' => 'Registration session expired or not found. Please enter your details to sign up.',
            ]);
        }

        $email = $pendingUser['email'];
        $maskedEmail = $this->maskEmail($email);
        $sentAt = session('register_otp_sent_at', now()->timestamp);
        $elapsed = now()->timestamp - $sentAt;
        $resendCooldown = max(0, 60 - $elapsed);

        return view('auth.verify-register-otp', [
            'email' => $email,
            'maskedEmail' => $maskedEmail,
            'resendCooldown' => $resendCooldown,
        ]);
    }

    /**
     * Step 2 (Submit): Verify OTP code, officially insert user into database,
     * log user in, and redirect smoothly to the public home page.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ], [
            'otp.required' => 'Please enter the 6-digit verification code.',
            'otp.size' => 'The verification code must be exactly 6 digits.',
        ]);

        $pendingUser = session('pending_user');

        if (!$pendingUser) {
            return redirect()->route('register')->withErrors([
                'email' => 'Your registration session has expired. Please submit the form again.',
            ]);
        }

        $savedOtp = session('register_otp');
        $expiresAt = session('register_otp_expires_at', 0);

        // 1. Check expiration (10 minutes)
        if (now()->timestamp > $expiresAt) {
            return back()->withErrors([
                'otp' => 'The verification code has expired. Please click "Resend Code" below to request a new code.',
            ]);
        }

        // 2. Validate OTP match with timing-attack resistant comparison
        $inputOtp = trim($request->otp);
        if (!hash_equals((string) $savedOtp, $inputOtp)) {
            return back()->withErrors([
                'otp' => 'The verification code you entered is incorrect. Please check your inbox and try again.',
            ]);
        }

        // 3. Final safety uniqueness check in database
        $conflict = User::withTrashed()
            ->where('email', $pendingUser['email'])
            ->orWhere('username', $pendingUser['username'])
            ->first();

        if ($conflict) {
            $request->session()->forget(['pending_user', 'register_otp', 'register_otp_expires_at', 'register_otp_sent_at']);
            $request->session()->save();

            return redirect()->route('login')->withErrors([
                'login' => 'An account with this email or username already exists. Please log in directly.',
            ]);
        }

        // 4. Officially create user in TiDB Cloud database
        try {
            $user = User::create($pendingUser);
        } catch (\Throwable $e) {
            Log::error('Registration DB insertion failed: ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $pendingUser['email'],
                'username' => $pendingUser['username'],
            ]);

            return back()->withErrors([
                'otp' => 'Database error creating your account. Please try submitting again.',
            ]);
        }

        // 5. Authenticate user immediately
        Auth::login($user);

        // 6. Clear pending registration session states
        $request->session()->forget([
            'pending_user',
            'register_otp',
            'register_otp_expires_at',
            'register_otp_sent_at',
            'mail_delivery_warning',
        ]);

        // 7. Regenerate and synchronously persist session to database
        $request->session()->regenerate();
        $request->session()->save();

        return redirect()->route('home')->with('status', 'Registration successful! Welcome to Noksha.');
    }

    /**
     * Resend a fresh 6-digit OTP code to the pending user's email.
     */
    public function resendOtp(Request $request)
    {
        $pendingUser = session('pending_user');

        if (!$pendingUser) {
            return redirect()->route('register')->withErrors([
                'email' => 'Registration session expired. Please enter your details to sign up.',
            ]);
        }

        // Throttle resend (30 seconds cooldown minimum)
        $sentAt = session('register_otp_sent_at', 0);
        if ((now()->timestamp - $sentAt) < 30) {
            return back()->with('warning', 'Please wait a moment before requesting another code.');
        }

        $otp = (string) random_int(100000, 999999);

        session([
            'register_otp' => $otp,
            'register_otp_expires_at' => now()->addMinutes(10)->timestamp,
            'register_otp_sent_at' => now()->timestamp,
        ]);
        $request->session()->save();

        $dispatchResult = $this->dispatchRegistrationOtp(
            $pendingUser['email'],
            $otp,
            $pendingUser['name']
        );

        if ($dispatchResult['success']) {
            return back()->with('status', 'A fresh 6-digit verification code has been dispatched to your email.');
        }

        return back()->with('mail_delivery_warning', 'Notice: Email delivery encountered an issue (' . ($dispatchResult['error'] ?? 'Connection error') . '). Your fresh 6-digit code has been logged to storage/logs/laravel.log.');
    }

    /**
     * Resilient mail dispatcher: logs OTP, attempts Brevo HTTPS REST API (Port 443),
     * and falls back to Laravel SMTP Mailable.
     */
    protected function dispatchRegistrationOtp(string $email, string $otp, ?string $recipientName = null): array
    {
        // 1. Write OTP to log for debugging and local testing verification
        Log::info("Noksha Security: Generated 6-digit registration OTP [{$otp}] for {$email} (Expires in 10 minutes)");

        // 2. Dispatch via Brevo HTTPS REST API (Port 443 - reliable on cloud hosts)
        $apiResult = $this->brevoMailService->sendRegistrationOtpMail($email, $otp, $recipientName, 10);
        if ($apiResult['success']) {
            return $apiResult;
        }

        // 3. Fallback to Laravel Mailable (SMTP)
        try {
            Mail::to($email)->send(new RegistrationOtpMail($otp, $recipientName, 10));

            return [
                'success' => true,
                'transport' => 'laravel_mailable_smtp',
                'error' => null,
            ];
        } catch (\Throwable $e) {
            Log::warning('Registration OTP Mailable SMTP dispatch failed: ' . $e->getMessage(), [
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
            $maskedName = substr($name, 0, 1) . str_repeat('*', max(1, $len - 2)) . substr($name, -1);
        }

        return $maskedName . '@' . $domain;
    }
}
