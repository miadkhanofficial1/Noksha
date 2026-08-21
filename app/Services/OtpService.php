<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class OtpService
{
    /**
     * Generate and store an OTP code for a user.
     *
     * @param User $user
     * @param string $purpose
     * @return string
     */
    public function generateOtp(User $user, string $purpose = 'phone_verification'): string
    {
        // 6-digit numeric OTP code
        $code = (string) random_int(100000, 999999);
        $key = "otp_{$purpose}_{$user->id}";

        // Store OTP in cache for 10 minutes
        Cache::put($key, $code, now()->addMinutes(10));

        // Note: SMS Gateway integration (e.g. SSL Wireless, BulkSMS BD) to be attached here
        return $code;
    }

    /**
     * Verify an OTP code provided by a user.
     *
     * @param User $user
     * @param string $code
     * @param string $purpose
     * @return bool
     */
    public function verifyOtp(User $user, string $code, string $purpose = 'phone_verification'): bool
    {
        $key = "otp_{$purpose}_{$user->id}";
        $cachedCode = Cache::get($key);

        if ($cachedCode && $cachedCode === $code) {
            Cache::forget($key);
            return true;
        }

        return false;
    }
}
