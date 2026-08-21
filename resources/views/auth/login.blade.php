@extends('layouts.app')

@section('title', 'Sign In - Noksha (লগইন - নকশা)')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="noksha-card p-4 p-md-5 shadow-sm">
                <!-- Header Title -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center mb-3 noksha-logo-badge" style="width: 54px; height: 54px; font-size: 1.5rem;">
                        ন
                    </div>
                    <h2 class="fw-bold text-dark mb-1">Welcome Back <span class="text-primary">(স্বাগতম)</span></h2>
                    <p class="text-muted small">Sign in to your Noksha account</p>
                </div>

                <!-- Session Flash Messages -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 small" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show rounded-3 mb-4 small" role="alert">
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 small" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Google OAuth Button -->
                <div class="mb-4">
                    <a href="{{ route('auth.google') }}" class="btn btn-outline-dark w-100 py-2 rounded-3 d-flex align-items-center justify-content-center gap-2 fw-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                            <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C12.955 4 4 12.955 4 24s8.955 20 20 20s20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/>
                            <path fill="#FF3D00" d="m6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C16.318 4 9.656 8.337 6.306 14.691z"/>
                            <path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/>
                            <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
                        </svg>
                        <span>Log in with Google / গুগল দিয়ে লগইন করুন</span>
                    </a>
                </div>

                <div class="d-flex align-items-center my-3">
                    <hr class="flex-grow-1 text-muted opacity-25">
                    <span class="px-3 text-muted small fw-semibold">OR LOGIN WITH CREDENTIALS</span>
                    <hr class="flex-grow-1 text-muted opacity-25">
                </div>

                <!-- Login Form -->
                <form action="{{ route('login.store') }}" method="POST">
                    @csrf

                    <!-- Email or Username -->
                    <div class="mb-3">
                        <label for="login" class="form-label fw-semibold text-dark">
                            Email or Username <span class="text-primary">(ইমেইল বা ইউজারনেম)</span> <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control rounded-3 @error('login') is-invalid @enderror" id="login" name="login" value="{{ old('login') }}" placeholder="name@example.com or username" required autofocus>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <label for="password" class="form-label fw-semibold text-dark mb-0">
                                Password <span class="text-primary">(পাসওয়ার্ড)</span> <span class="text-danger">*</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="small text-primary text-decoration-none fw-semibold">
                                Forgot password?
                            </a>
                        </div>
                        <input type="password" class="form-control rounded-3 mt-1 @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter password" required>
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label small text-muted" for="remember">
                            Remember me on this device / আমাকে মনে রাখুন
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-noksha w-100 py-2.5 rounded-3 mb-3 fs-6">
                        Sign In / লগইন করুন
                    </button>

                    <!-- Footer Link -->
                    <div class="text-center text-muted small">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Register Now / অ্যাকাউন্ট তৈরি করুন</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
