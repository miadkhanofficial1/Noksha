@extends('layouts.app')

@section('title', 'Forgot Password - Noksha Security')

@section('content')
<div class="min-h-[calc(100vh-160px)] flex items-center justify-center px-4 py-12 relative overflow-hidden">
    <!-- Ambient Security Glow Backdrop -->
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-96 h-96 bg-indigo-600/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
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
                    <i class="bi bi-shield-lock-fill text-xs"></i>
                    <span>Secure Recovery</span>
                </div>

                <h1 class="text-2xl font-bold text-white tracking-tight">Forgot Password?</h1>
                <p class="text-sm text-slate-400 mt-1.5">
                    Enter your registered email address and we'll dispatch a 6-digit verification code.
                </p>
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

            <!-- Request Form -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-4" data-no-loader id="forgotPasswordForm">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-2">
                        Account Email Address <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               placeholder="you@example.com"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700/80 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all @error('email') border-rose-500/80 focus:border-rose-500 focus:ring-rose-500/20 @enderror">
                    </div>
                </div>

                <button type="submit" 
                        id="submitForgotBtn"
                        class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-cyan-500 hover:from-indigo-500 hover:to-cyan-400 text-white font-semibold text-sm shadow-lg shadow-indigo-600/25 hover:shadow-cyan-500/20 active:scale-[0.99] transition-all flex items-center justify-center gap-2">
                    <span id="submitBtnText">Send 6-Digit Verification Code</span>
                    <i class="bi bi-arrow-right" id="submitBtnIcon"></i>
                </button>
            </form>

            <script>
                document.getElementById('forgotPasswordForm')?.addEventListener('submit', function () {
                    const btn = document.getElementById('submitForgotBtn');
                    const text = document.getElementById('submitBtnText');
                    const icon = document.getElementById('submitBtnIcon');
                    if (btn && text) {
                        btn.classList.add('opacity-90', 'cursor-wait');
                        text.textContent = 'Dispatching Code...';
                        if (icon) {
                            icon.className = 'bi bi-arrow-repeat inline-block animate-spin';
                        }
                    }
                });
            </script>

            <!-- Bottom Return Link -->
            <div class="mt-6 pt-5 border-t border-slate-800/80 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-cyan-400 transition-colors">
                    <i class="bi bi-arrow-left"></i>
                    <span>Remembered your password? Back to Login</span>
                </a>
            </div>
        </div>

        <!-- Security Trust Note -->
        <div class="mt-6 text-center">
            <p class="text-[11px] text-slate-500 flex items-center justify-center gap-1.5">
                <i class="bi bi-shield-check text-cyan-400"></i>
                <span>Protected by Noksha Guard &bull; 256-bit encrypted recovery</span>
            </p>
        </div>
    </div>
</div>
@endsection
