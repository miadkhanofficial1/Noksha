<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Display the OTP verification form placeholder.
     */
    public function showForm()
    {
        return view('auth.otp');
    }

    /**
     * Send OTP code to user.
     */
    public function send(Request $request)
    {
        $user = $request->user();
        $code = $this->otpService->generateOtp($user);

        return back()->with('info', "OTP generated (Simulation Code: {$code}). Ready for SMS Gateway activation. / ওটিপি প্রস্তুত।");
    }

    /**
     * Verify submitted OTP code.
     */
    public function verify(Request $request)
    {
        $request->validate(['otp_code' => ['required', 'string', 'size:6']]);
        $user = $request->user();

        if ($this->otpService->verifyOtp($user, $request->otp_code)) {
            return redirect()->route('home')->with('status', 'OTP verified successfully! / ওটিপি সফলভাবে যাচাই করা হয়েছে।');
        }

        return back()->withErrors(['otp_code' => 'Invalid or expired OTP code. / অকার্যকর বা মেয়াদউত্তীর্ণ ওটিপি কোড।']);
    }
}
