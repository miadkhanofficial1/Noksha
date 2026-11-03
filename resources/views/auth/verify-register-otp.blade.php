@extends('layouts.app')

@section('title', 'Verify Registration Code - Noksha Security')

@section('content')
<div class="min-h-[calc(100vh-160px)] flex items-center justify-center px-4 py-12 relative overflow-hidden">
    <!-- Ambient Security Glow Backdrop -->
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-96 h-96 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-lg relative z-10">
        <!-- Main Card -->
        <div class="bg-slate-900/90 border border-slate-800/90 rounded-2xl p-6 sm:p-8 shadow-2xl shadow-black/50 backdrop-blur-xl transition-all">
            <!-- Header Section -->
            <div class="text-center mb-6">
                <a href="{{ route('home') }}" class="inline-block mb-4 transition-transform hover:scale-105">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Noksha" 
                         class="h-10 w-auto mx-auto object-contain">
                </a>

                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-xs font-semibold uppercase tracking-wider mb-3">
                    <i class="bi bi-shield-check text-xs"></i>
                    <span>Two-Step Registration</span>
                </div>

                <h1 class="text-2xl font-bold text-white tracking-tight">Verify Your Email</h1>
                <p class="text-sm text-slate-400 mt-2">
                    We've sent a 6-digit confirmation code to:
                </p>
                <div class="inline-flex items-center gap-2 mt-1 px-3 py-1 rounded-lg bg-slate-800/80 border border-slate-700/60 text-xs font-mono text-cyan-300">
                    <i class="bi bi-envelope-check"></i>
                    <span>{{ $maskedEmail }}</span>
                    <a href="{{ route('register') }}" class="text-slate-400 hover:text-white ml-1 underline decoration-slate-600 transition-colors" title="Change email or edit details">
                        Change
                    </a>
                </div>
            </div>

            <!-- Mail Delivery Notice Banner (fallback info if external mail delivery failed) -->
            @if (session('mail_delivery_warning'))
                <div class="mb-5 p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs shadow-lg shadow-amber-950/20">
                    <div class="flex items-start gap-2.5">
                        <i class="bi bi-exclamation-triangle-fill text-amber-400 text-base flex-shrink-0 mt-0.5"></i>
                        <div>
                            <div class="font-bold text-amber-200 uppercase tracking-wider text-[11px] mb-1">
                                Delivery Notice
                            </div>
                            <div class="leading-relaxed text-slate-300">
                                {{ session('mail_delivery_warning') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Session Status Messages -->
            @if (session('status'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-start gap-2.5">
                    <i class="bi bi-check-circle-fill text-sm flex-shrink-0 mt-0.5"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-5 p-3.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs flex items-start gap-2.5">
                    <i class="bi bi-exclamation-triangle-fill text-sm flex-shrink-0 mt-0.5"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs">
                    <div class="flex items-start gap-2.5">
                        <i class="bi bi-exclamation-circle-fill text-sm flex-shrink-0 mt-0.5"></i>
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- 6-Digit OTP Verification Form -->
            <form id="otpForm" action="{{ route('register.verify.otp.submit') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="otp" id="fullOtpInput">

                <div>
                    <label class="block text-xs font-medium text-slate-300 text-center uppercase tracking-wider mb-3">
                        Enter 6-Digit Security Code
                    </label>

                    <!-- Split 6-box input -->
                    <div class="flex justify-center items-center gap-2 sm:gap-3" id="otpBoxContainer">
                        @for ($i = 0; $i < 6; $i++)
                            <input type="text" 
                                   name="digit[]" 
                                   data-index="{{ $i }}"
                                   inputmode="numeric" 
                                   pattern="[0-9]*" 
                                   maxlength="1" 
                                   autocomplete="one-time-code"
                                   class="otp-digit w-11 h-14 sm:w-13 sm:h-16 text-center text-2xl font-bold font-mono text-cyan-300 bg-slate-800/90 border border-slate-700/80 rounded-xl focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 outline-none transition-all caret-cyan-400 select-all shadow-inner"
                                   @if ($i === 0) autofocus @endif>
                        @endfor
                    </div>
                    <p class="text-xs text-slate-400 text-center mt-3">
                        <i class="bi bi-clock-history mr-1"></i> Code valid for <span class="text-cyan-400 font-semibold">10 minutes</span>
                    </p>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        id="verifySubmitBtn"
                        class="w-full py-3.5 px-4 rounded-xl font-semibold text-sm text-white bg-gradient-to-r from-cyan-500 via-indigo-600 to-indigo-700 hover:from-cyan-400 hover:to-indigo-600 focus:ring-2 focus:ring-cyan-400/50 shadow-lg shadow-indigo-900/40 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="bi bi-shield-check text-base"></i>
                    <span>Verify & Complete Registration</span>
                </button>
            </form>

            <!-- Resend Section -->
            <div class="mt-8 pt-6 border-t border-slate-800/80 text-center">
                <p class="text-xs text-slate-400 mb-3">
                    Didn't receive the email? Check your spam folder or request a new code:
                </p>

                <form action="{{ route('register.resend.otp') }}" method="POST" id="resendForm">
                    @csrf
                    <button type="submit" 
                            id="resendBtn"
                            class="inline-flex items-center gap-2 text-xs font-semibold text-cyan-400 hover:text-cyan-300 transition-colors disabled:text-slate-500 disabled:cursor-not-allowed">
                        <i class="bi bi-arrow-clockwise"></i>
                        <span id="resendBtnText">Resend Verification Code</span>
                    </button>
                </form>
            </div>

            <!-- Back to Register Footer Link -->
            <div class="mt-6 text-center">
                <a href="{{ route('register') }}" class="text-xs text-slate-400 hover:text-white transition-colors inline-flex items-center gap-1.5">
                    <i class="bi bi-arrow-left"></i>
                    <span>Start over with different details</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const digits = Array.from(document.querySelectorAll('.otp-digit'));
    const fullOtpInput = document.getElementById('fullOtpInput');
    const form = document.getElementById('otpForm');
    const submitBtn = document.getElementById('verifySubmitBtn');
    const resendBtn = document.getElementById('resendBtn');
    const resendBtnText = document.getElementById('resendBtnText');

    function updateFullOtp() {
        const otpValue = digits.map(d => d.value).join('');
        fullOtpInput.value = otpValue;
        return otpValue;
    }

    digits.forEach((digit, index) => {
        digit.addEventListener('input', function (e) {
            const val = this.value.replace(/[^0-9]/g, '');
            this.value = val ? val[val.length - 1] : '';

            if (this.value && index < digits.length - 1) {
                digits[index + 1].focus();
                digits[index + 1].select();
            }

            const currentOtp = updateFullOtp();
            if (currentOtp.length === 6) {
                form.submit();
            }
        });

        digit.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                digits[index - 1].focus();
                digits[index - 1].select();
            } else if (e.key === 'ArrowLeft' && index > 0) {
                digits[index - 1].focus();
            } else if (e.key === 'ArrowRight' && index < digits.length - 1) {
                digits[index + 1].focus();
            }
        });

        digit.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasteData = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
            if (!pasteData) return;

            for (let i = 0; i < digits.length; i++) {
                if (i < pasteData.length) {
                    digits[i].value = pasteData[i];
                }
            }

            const focusIndex = Math.min(pasteData.length, digits.length - 1);
            digits[focusIndex].focus();
            
            const fullOtp = updateFullOtp();
            if (fullOtp.length === 6) {
                form.submit();
            }
        });
    });

    form.addEventListener('submit', function (e) {
        const otpValue = updateFullOtp();
        if (otpValue.length !== 6) {
            e.preventDefault();
            alert('Please enter all 6 digits of your verification code.');
            const emptyIdx = digits.findIndex(d => !d.value);
            if (emptyIdx !== -1) digits[emptyIdx].focus();
        } else {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⟳</span> Verifying...';
        }
    });

    // 60-Second Resend Countdown Timer
    let cooldownSeconds = {{ (int) ($resendCooldown ?? 0) }};
    function updateCountdown() {
        if (cooldownSeconds > 0) {
            resendBtn.disabled = true;
            resendBtnText.textContent = `Resend Code in ${cooldownSeconds}s`;
            cooldownSeconds--;
            setTimeout(updateCountdown, 1000);
        } else {
            resendBtn.disabled = false;
            resendBtnText.textContent = 'Resend Verification Code';
        }
    }
    updateCountdown();
});
</script>
@endsection
