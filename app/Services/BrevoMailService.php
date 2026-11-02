<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class BrevoMailService
{
    /**
     * Send 6-digit password reset OTP email using Brevo's HTTPS REST API (Port 443).
     *
     * @param string $email
     * @param string $otp
     * @param string|null $recipientName
     * @return array{success: bool, transport: string, error: string|null, messageId: string|null}
     */
    public function sendOtpMail(string $email, string $otp, ?string $recipientName = null): array
    {
        $apiKey = env('BREVO_API_KEY') 
            ?: config('services.brevo.key') 
            ?: env('MAIL_PASSWORD') 
            ?: config('mail.mailers.smtp.password');

        if (empty($apiKey) || $apiKey === 'apnar_brevo_smtp_master_key') {
            $errorMsg = 'BREVO_API_KEY is not configured (or is using placeholder key). Please set BREVO_API_KEY in .env or Render environment.';
            Log::warning("Brevo HTTPS API: {$errorMsg} for recipient {$email}");

            return [
                'success' => false,
                'transport' => 'brevo_https_api',
                'error' => $errorMsg,
                'messageId' => null,
            ];
        }

        $senderEmail = config('mail.from.address', 'miadkhan.official1@gmail.com');
        $senderName = config('mail.from.name', 'Noksha Security');

        $htmlContent = "<div style='background:#0f172a;color:#fff;padding:24px;border-radius:8px;font-family:sans-serif;'>"
            . "<h2 style='margin-top:0;color:#f8fafc;'>Noksha Verification Code</h2>"
            . "<p style='color:#94a3b8;font-size:14px;'>Your 6-digit verification code is: <strong style='font-size:26px;color:#38bdf8;letter-spacing:4px;display:inline-block;margin-left:8px;'>{$otp}</strong></p>"
            . "<p style='color:#64748b;font-size:12px;margin-bottom:0;'>This code expires in 15 minutes. If you did not request this code, please ignore this email.</p>"
            . "</div>";

        $payload = [
            'sender' => [
                'name' => $senderName,
                'email' => $senderEmail,
            ],
            'to' => [
                ['email' => $email],
            ],
            'subject' => 'Noksha Security — 6-Digit OTP Code',
            'htmlContent' => $htmlContent,
        ];

        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post('https://api.brevo.com/v3/smtp/email', $payload);

            if ($response->successful()) {
                $data = $response->json();
                $messageId = $data['messageId'] ?? null;
                Log::info("Brevo HTTPS API: OTP email successfully delivered to {$email} (Message ID: {$messageId})");

                return [
                    'success' => true,
                    'transport' => 'brevo_https_api',
                    'error' => null,
                    'messageId' => $messageId,
                ];
            }

            $errorMessage = "Brevo API HTTP {$response->status()}: " . $response->body();
            Log::error("Brevo HTTPS API Failure: {$errorMessage}", [
                'email' => $email,
                'status' => $response->status(),
            ]);

            return [
                'success' => false,
                'transport' => 'brevo_https_api',
                'error' => $errorMessage,
                'messageId' => null,
            ];
        } catch (Throwable $e) {
            $errorMessage = "Brevo HTTPS API Connection Error: " . $e->getMessage();
            Log::error("OTP Mail Failure: {$errorMessage}", [
                'email' => $email,
                'exception' => $e,
            ]);

            return [
                'success' => false,
                'transport' => 'brevo_https_api',
                'error' => $errorMessage,
                'messageId' => null,
            ];
        }
    }
}
