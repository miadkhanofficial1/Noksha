<?php

namespace App\Jobs;

use App\Services\BrevoMailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
     * Execute the job with resilient Brevo API / SMTP dispatch.
     */
    public function handle(BrevoMailService $brevoMailService): void
    {
        $result = $brevoMailService->sendOtpMail($this->email, $this->otp, $this->name);

        if (!$result['success']) {
            Log::error("SendPasswordResetOtpJob failed for {$this->email}: " . ($result['error'] ?? 'Unknown error'));
        }
    }
}
