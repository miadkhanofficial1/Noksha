<?php

namespace App\Console\Commands;

use App\Services\BrevoMailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
    public function handle(BrevoMailService $brevoMailService): int
    {
        $email = $this->argument('email');
        $otp = $this->argument('otp');
        $name = $this->option('name') ?: 'Noksha Member';

        $result = $brevoMailService->sendOtpMail($email, $otp, $name);

        if ($result['success']) {
            $this->info("OTP verification email dispatched to {$email} via {$result['transport']}.");
            return Command::SUCCESS;
        }

        $this->error("OTP verification email failed for {$email}: {$result['error']}");
        return Command::FAILURE;
    }
}
