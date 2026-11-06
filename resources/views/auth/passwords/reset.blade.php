@extends('layouts.app')

@section('title', 'Set New Password - Noksha Security')

@section('content')
<div class="min-h-[calc(100vh-160px)] flex items-center justify-center px-4 py-12 relative overflow-hidden">
    <!-- Ambient Security Glow Backdrop -->
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-96 h-96 bg-indigo-600/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
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
                    <i class="bi bi-shield-check text-xs"></i>
                    <span>Identity Verified</span>
                </div>

                <h1 class="text-2xl font-bold text-[#121826] tracking-tight">Create New Password</h1>
                <p class="text-sm text-slate-500 mt-2">
                    Resetting password for:
                </p>
                <div class="inline-flex items-center gap-2 mt-1 px-3 py-1 rounded-lg bg-slate-800/80 border border-slate-700/60 text-xs font-mono text-cyan-300">
                    <i class="bi bi-person-check-fill text-xs text-emerald-400"></i>
                    <span>{{ $email }}</span>
                </div>
            </div>

            <!-- Session Status / Errors -->
            @if (session('status'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-start gap-2.5">
                    <i class="bi bi-check-circle-fill text-sm flex-shrink-0 mt-0.5"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
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

            <!-- Reset Password Form -->
            <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                @csrf

                <!-- New Password Field -->
                <div>
                    <label for="password" class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-2">
                        New Password <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="bi bi-lock"></i>
                        </div>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               required 
                               autofocus
                               placeholder="Minimum 8 characters"
                               class="w-full pl-10 pr-11 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700/80 text-[#121826] placeholder-slate-500 text-sm focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all @error('password') border-rose-500/80 focus:border-rose-500 focus:ring-rose-500/20 @enderror">
                        <button type="button" 
                                onclick="togglePasswordVisibility('password', 'toggleIcon1')"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-[#121826] transition-colors"
                                tabindex="-1">
                            <i class="bi bi-eye" id="toggleIcon1"></i>
                        </button>
                    </div>

                    <!-- Password Strength Meter -->
                    <div class="mt-2.5 space-y-1.5">
                        <div class="h-1.5 w-full bg-slate-800 rounded-full overflow-hidden flex gap-1">
                            <div id="strBar1" class="h-full w-1/3 bg-slate-700 rounded-full transition-all"></div>
                            <div id="strBar2" class="h-full w-1/3 bg-slate-700 rounded-full transition-all"></div>
                            <div id="strBar3" class="h-full w-1/3 bg-slate-700 rounded-full transition-all"></div>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-500 px-0.5">
                            <span>Strength: <span id="strengthLabel" class="text-slate-500 font-semibold">Enter password</span></span>
                            <span class="text-slate-500">Must be distinct from current</span>
                        </div>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-2">
                        Confirm New Password <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               required 
                               placeholder="Re-enter your new password"
                               class="w-full pl-10 pr-11 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700/80 text-[#121826] placeholder-slate-500 text-sm focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all">
                        <button type="button" 
                                onclick="togglePasswordVisibility('password_confirmation', 'toggleIcon2')"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-[#121826] transition-colors"
                                tabindex="-1">
                            <i class="bi bi-eye" id="toggleIcon2"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-cyan-500 hover:from-indigo-500 hover:to-cyan-400 text-[#121826] font-semibold text-sm shadow-lg shadow-indigo-600/25 hover:shadow-cyan-500/20 active:scale-[0.99] transition-all flex items-center justify-center gap-2">
                    <i class="bi bi-check-lg text-base"></i>
                    <span>Update Password & Secure Account</span>
                </button>
            </form>

            <!-- Bottom Cancel Link -->
            <div class="mt-6 pt-5 border-t border-slate-200/80 text-center">
                <a href="{{ route('login') }}" class="text-xs text-slate-500 hover:text-slate-700 inline-flex items-center gap-1.5 transition-colors">
                    <i class="bi bi-x-circle"></i>
                    <span>Cancel and return to Login</span>
                </a>
            </div>
        </div>

        <!-- Security Note -->
        <div class="mt-6 text-center">
            <p class="text-[11px] text-slate-500 flex items-center justify-center gap-1.5">
                <i class="bi bi-shield-lock-fill text-cyan-400"></i>
                <span>Enforcing Noksha Password History Protection</span>
            </p>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (!input || !icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const pwd = document.getElementById('password');
    const bar1 = document.getElementById('strBar1');
    const bar2 = document.getElementById('strBar2');
    const bar3 = document.getElementById('strBar3');
    const label = document.getElementById('strengthLabel');

    if (pwd) {
        pwd.addEventListener('input', function () {
            const val = this.value;
            let score = 0;

            if (val.length >= 8) score++;
            if (/[0-9]/.test(val) && /[a-zA-Z]/.test(val)) score++;
            if (/[^a-zA-Z0-9]/.test(val) || val.length >= 12) score++;

            // Reset
            bar1.className = 'h-full w-1/3 bg-slate-700 rounded-full transition-all';
            bar2.className = 'h-full w-1/3 bg-slate-700 rounded-full transition-all';
            bar3.className = 'h-full w-1/3 bg-slate-700 rounded-full transition-all';

            if (!val) {
                label.textContent = 'Enter password';
                label.className = 'text-slate-500 font-semibold';
            } else if (score === 1) {
                bar1.className = 'h-full w-1/3 bg-rose-500 rounded-full transition-all';
                label.textContent = 'Weak';
                label.className = 'text-rose-400 font-semibold';
            } else if (score === 2) {
                bar1.className = 'h-full w-1/3 bg-amber-500 rounded-full transition-all';
                bar2.className = 'h-full w-1/3 bg-amber-500 rounded-full transition-all';
                label.textContent = 'Medium';
                label.className = 'text-amber-400 font-semibold';
            } else {
                bar1.className = 'h-full w-1/3 bg-emerald-500 rounded-full transition-all';
                bar2.className = 'h-full w-1/3 bg-emerald-500 rounded-full transition-all';
                bar3.className = 'h-full w-1/3 bg-emerald-500 rounded-full transition-all';
                label.textContent = 'Strong';
                label.className = 'text-emerald-400 font-semibold';
            }
        });
    }
});
</script>
@endsection
