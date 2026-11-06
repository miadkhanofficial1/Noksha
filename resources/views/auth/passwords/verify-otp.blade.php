@extends('layouts.app')

@section('title', 'Verify OTP - Noksha Security')

@section('content')
<div class="min-h-[calc(100vh-160px)] flex items-center justify-center px-4 py-12 relative overflow-hidden">
    <!-- Ambient Security Glow Backdrop -->
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-96 h-96 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-lg relative z-10">
        <!-- Main Card -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-md transition-all">
            <!-- Header Section -->
            <div class="text-center mb-6">
                <a href="{{ route('home') }}" class="inline-block mb-4 transition-transform hover:scale-105">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Noksha" 
                         class="h-10 w-auto mx-auto object-contain">
                </a>

                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-xs font-semibold uppercase tracking-wider mb-3">
                    <i class="bi bi-key-fill text-xs"></i>
                    <span>Two-Step Verification</span>
                </div>

                <h1 class="text-2xl font-bold text-[#121826] tracking-tight">Enter Verification Code</h1>
                <p class="text-sm text-slate-500 mt-2">
                    We've sent a 6-digit security code to:
                </p>
                <div class="inline-flex items-center gap-2 mt-1 px-3 py-1 rounded-lg bg-slate-800/80 border border-slate-700/60 text-xs font-mono text-cyan-300">
                    <i class="bi bi-envelope-check"></i>
                    <span>{{ $maskedEmail }}</span>
                    <a href="{{ route('password.request') }}" class="text-slate-500 hover:text-[#121826] ml-1 underline decoration-slate-600 transition-colors" title="Change email address">
                        Change
                    </a>
                </div>
            </div>

            <!-- Mail Delivery Warning Banner -->
            @if (session('mail_delivery_warning'))
                <div class="mb-5 p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs shadow-lg shadow-amber-950/20">
                    <div class="flex items-start gap-2.5">
                        <i class="bi bi-exclamation-triangle-fill text-amber-400 text-base flex-shrink-0 mt-0.5"></i>
                        <div>
                            <div class="font-bold text-amber-200 uppercase tracking-wider text-[11px] mb-1">
                                Mail Delivery Notice
                            </div>
                            <div class="leading-relaxed text-slate-700">
                                {{ session('mail_delivery_warning') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Session Status / Errors -->
            @if (session('status'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-start gap-2.5">
                    <i class="bi bi-check-circle-fill text-sm flex-shrink-0 mt-0.5"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs">
                    <div class="flex items-start gap-2.5">
                        <i class="bi bi-exclamation-triangle-fill text-sm flex-shrink-0 mt-0.5"></i>
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- 6-Digit OTP Verification Form -->
            <form id="otpForm" action="{{ route('password.otp.verify') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="otp" id="fullOtpInput">

                <div>
                    <label class="block text-xs font-medium text-slate-700 text-center uppercase tracking-wider mb-3">
                        6-Digit Security Code
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
                </div>

                <!-- Expiry Countdown Indicator -->
                <div class="flex items-center justify-between text-xs text-slate-500 px-1">
                    <span class="flex items-center gap-1.5">
                        <i class="bi bi-clock-history text-slate-500"></i>
                        <span>Code expires in:</span>
                    </span>
                    <span id="expiryCountdown" class="font-mono font-semibold text-amber-400">
                        15:00
                    </span>
                </div>

                <!-- Verify Button -->
                <button type="submit" 
                        id="verifyBtn"
                        class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-cyan-500 hover:from-indigo-500 hover:to-cyan-400 text-[#121826] font-semibold text-sm shadow-lg shadow-indigo-600/25 hover:shadow-cyan-500/20 active:scale-[0.99] transition-all flex items-center justify-center gap-2">
                    <i class="bi bi-shield-check"></i>
                    <span>Verify Code & Continue</span>
                </button>
            </form>

            <!-- Resend OTP Action & Timer -->
            <div class="mt-6 pt-5 border-t border-slate-200/80 text-center">
                <form id="resendForm" action="{{ route('password.otp.resend') }}" method="POST" class="inline-block">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    
                    <div class="text-xs text-slate-500 flex items-center justify-center gap-2">
                        <span>Didn't receive the code?</span>
                        <button type="submit" 
                                id="resendBtn" 
                                @if ($cooldown > 0) disabled @endif
                                class="font-semibold text-cyan-400 hover:text-cyan-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:text-cyan-400 transition-colors">
                            Resend Code
                        </button>
                        <span id="resendTimerContainer" class="@if ($cooldown <= 0) hidden @endif text-slate-500 font-mono">
                            (<span id="resendCountdown">{{ $cooldown }}</span>s)
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Note -->
        <div class="mt-6 text-center">
            <a href="{{ route('password.request') }}" class="text-xs text-slate-500 hover:text-slate-500 inline-flex items-center gap-1.5 transition-colors">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Email Request</span>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('.otp-digit');
    const fullOtpInput = document.getElementById('fullOtpInput');
    const otpForm = document.getElementById('otpForm');
    const resendBtn = document.getElementById('resendBtn');
    const resendTimerContainer = document.getElementById('resendTimerContainer');
    const resendCountdown = document.getElementById('resendCountdown');
    const expiryCountdown = document.getElementById('expiryCountdown');

    // Focus first input automatically
    if (inputs.length > 0) {
        inputs[0].focus();
    }

    // Helper: Collect current value
    function getCombinedOtp() {
        let combined = '';
        inputs.forEach(input => {
            combined += input.value.trim();
        });
        return combined;
    }

    // Helper: Update hidden full OTP input
    function syncHiddenInput() {
        const val = getCombinedOtp();
        fullOtpInput.value = val;
        return val;
    }

    // 1. Input Box Navigation (Auto-advance & Backspace)
    inputs.forEach((input, index) => {
        // Filter input to only numeric
        input.addEventListener('input', function (e) {
            const val = this.value.replace(/[^0-9]/g, '');
            this.value = val.slice(0, 1);

            if (this.value) {
                // Advance to next input
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            }

            const combined = syncHiddenInput();
            if (combined.length === 6) {
                // Auto-submit form when all 6 digits entered
                otpForm.submit();
            }
        });

        // Keydown handling for Backspace & Left/Right navigation
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace') {
                if (!this.value && index > 0) {
                    inputs[index - 1].focus();
                    inputs[index - 1].value = '';
                    syncHiddenInput();
                    e.preventDefault();
                } else {
                    this.value = '';
                    syncHiddenInput();
                }
            } else if (e.key === 'ArrowLeft' && index > 0) {
                inputs[index - 1].focus();
            } else if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        // Auto-select text on focus
        input.addEventListener('focus', function () {
            this.select();
        });
    });

    // 2. Auto-Paste Support for 6 Digits
    document.getElementById('otpBoxContainer').addEventListener('paste', function (e) {
        e.preventDefault();
        const pasteData = (e.clipboardData || window.clipboardData).getData('text');
        const cleanDigits = pasteData.replace(/[^0-9]/g, '').slice(0, 6);

        if (cleanDigits.length > 0) {
            cleanDigits.split('').forEach((char, idx) => {
                if (idx < inputs.length) {
                    inputs[idx].value = char;
                }
            });

            syncHiddenInput();

            if (cleanDigits.length >= 6) {
                inputs[5].focus();
                otpForm.submit();
            } else {
                inputs[cleanDigits.length].focus();
            }
        }
    });

    // Before form submit, ensure fullOtpInput is populated
    otpForm.addEventListener('submit', function () {
        syncHiddenInput();
    });

    // 3. Expiration Countdown Timer (15 minutes window)
    const expiresAt = {{ $expiresAtTimestamp }} * 1000;
    function updateExpiryTimer() {
        const now = Date.now();
        const diffSeconds = Math.max(0, Math.floor((expiresAt - now) / 1000));

        const minutes = Math.floor(diffSeconds / 60);
        const seconds = diffSeconds % 60;

        expiryCountdown.textContent = 
            String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

        if (diffSeconds <= 0) {
            expiryCountdown.textContent = 'EXPIRED';
            expiryCountdown.classList.remove('text-amber-400');
            expiryCountdown.classList.add('text-rose-400');
        } else {
            setTimeout(updateExpiryTimer, 1000);
        }
    }
    updateExpiryTimer();

    // 4. Resend Cooldown Countdown Timer
    let cooldown = {{ $cooldown }};
    if (cooldown > 0) {
        const cooldownInterval = setInterval(function () {
            cooldown--;
            if (resendCountdown) {
                resendCountdown.textContent = cooldown;
            }

            if (cooldown <= 0) {
                clearInterval(cooldownInterval);
                if (resendBtn) {
                    resendBtn.removeAttribute('disabled');
                }
                if (resendTimerContainer) {
                    resendTimerContainer.classList.add('hidden');
                }
            }
        }, 1000);
    }
});
</script>
@endsection
