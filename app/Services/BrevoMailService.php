<?php

namespace App\Services;

use App\Mail\PasswordResetOtpMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class BrevoMailService
{
    /**
     * Send 6-digit password reset OTP email using Brevo API or SMTP with resilient fallback.
     *
     * @param string $email
     * @param string $otp
     * @param string|null $recipientName
     * @return array{success: bool, transport: string, error: string|null, messageId: string|null}
     */
    public function sendOtpMail(string $email, string $otp, ?string $recipientName = null): array
    {
        $recipientName = $recipientName ?: 'Noksha Member';
        $apiKey = config('services.brevo.key') ?? env('BREVO_API_KEY');
        $smtpPassword = config('mail.mailers.smtp.password');

        // Check if Brevo API key is available (either via BREVO_API_KEY or if SMTP password is an API key starting with xkeysib-)
        if (!$apiKey && $smtpPassword && str_starts_with($smtpPassword, 'xkeysib-')) {
            $apiKey = $smtpPassword;
        }

        // 1. Direct, lightweight Brevo REST API dispatch (port 443 HTTPS, bypasses cloud port blocks)
        if (!empty($apiKey)) {
            try {
                $htmlContent = view('emails.password-reset-otp', [
                    'otp' => $otp,
                    'recipientName' => $recipientName,
                    'expiresInMinutes' => 15,
                ])->render();

                $response = Http::timeout(8)
                    ->withHeaders([
                        'api-key' => $apiKey,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ])
                    ->post('https://api.brevo.com/v3/smtp/email', [
                        'sender' => [
                            'name' => config('mail.from.name', 'Noksha Security'),
                            'email' => config('mail.from.address', 'miadkhan.official1@gmail.com'),
                        ],
                        'to' => [
                            ['email' => $email, 'name' => $recipientName],
                        ],
                        'subject' => "Your Noksha Verification Code: {$otp}",
                        'htmlContent' => $htmlContent,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $messageId = $data['messageId'] ?? null;
                    Log::info("Brevo API: OTP email successfully delivered to {$email} (Message ID: {$messageId})");

                    return [
                        'success' => true,
                        'transport' => 'brevo_api',
                        'error' => null,
                        'messageId' => $messageId,
                    ];
                }

                $apiError = "Brevo API HTTP " . $response->status() . ": " . $response->body();
                Log::warning("Brevo API dispatch failed, falling back to SMTP: " . $apiError);
            } catch (Throwable $e) {
                Log::warning("Brevo API connection failed: " . $e->getMessage() . ". Falling back to SMTP.");
            }
        }

        // 2. Brevo SMTP Dispatch with 10s connection timeout and stream options
        try {
            Mail::to($email)->send(new PasswordResetOtpMail($otp, null, 15, $recipientName));
            Log::info("Brevo SMTP: OTP email successfully dispatched to {$email}");

            return [
                'success' => true,
                'transport' => 'smtp',
                'error' => null,
                'messageId' => null,
            ];
        } catch (Throwable $e) {
            // Requirement: Wrap mail dispatch with detailed logging using Log::error("OTP Mail Failure: " . $e->getMessage())
            Log::error("OTP Mail Failure: " . $e->getMessage(), [
                'recipient' => $email,
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'username' => config('mail.mailers.smtp.username'),
                'exception' => $e,
            ]);

            return [
                'success' => false,
                'transport' => 'failed',
                'error' => $e->getMessage(),
                'messageId' => null,
            ];
        }
    }
}
