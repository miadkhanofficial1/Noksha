<?php

namespace App\Jobs;

use App\Mail\PasswordResetOtpMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPasswordResetOtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $email;
    public string $otp;
    public ?string $name;
    public int $expiresInMinutes;

    /**
     * Create a new job instance.
     */
    public function __construct(string $email, string $otp, ?string $name = null, int $expiresInMinutes = 15)
    {
        $this->email = $email;
        $this->otp = $otp;
        $this->name = $name;
        $this->expiresInMinutes = $expiresInMinutes;
    }

    /**
     * Execute the job with resilient try-catch boundary.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->email)->send(
                new PasswordResetOtpMail($this->otp, null, $this->expiresInMinutes, $this->name)
            );
            Log::info("Password reset OTP email dispatched successfully to: {$this->email}");
        } catch (\Throwable $e) {
            Log::error('Background OTP email dispatch failed: ' . $e->getMessage(), [
                'email' => $this->email,
                'exception' => $e,
            ]);
        }
    }
}
