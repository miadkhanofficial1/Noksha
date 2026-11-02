<?php

namespace App\Console\Commands;

use App\Mail\PasswordResetOtpMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOtpMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send-otp {email : Recipient email address} {otp : 6-digit verification code} {--name= : Recipient name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches the password reset OTP verification email in an isolated background process';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $otp = $this->argument('otp');
        $name = $this->option('name') ?: 'Noksha Member';

        try {
            Mail::to($email)->send(new PasswordResetOtpMail($otp, null, 15, $name));
            Log::info("Background OTP email successfully delivered to: {$email}");
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            Log::error("Background OTP email delivery failed for {$email}: " . $e->getMessage(), [
                'email' => $email,
                'exception' => $e,
            ]);
            return Command::FAILURE;
        }
    }
}
