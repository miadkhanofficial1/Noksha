@extends('layouts.app')

@section('title', __('auth.register') . ' - Noksha')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="noksha-card p-4 p-md-5 shadow-sm">
                <!-- Header Title -->
                <div class="text-center mb-4 overflow-hidden">
                    <a href="{{ route('home') }}" class="d-block text-center text-decoration-none">
                        <img src="{{ asset('images/logo.png') }}" 
                             alt="Noksha" 
                             height="48"
                             style="height: 48px; width: auto; max-height: 48px; object-fit: contain; margin: 0 auto 1.5rem auto; display: block;">
                    </a>
                    <h2 class="fw-bold text-dark dark:text-white mb-1">{{ __('auth.sign_up') }}</h2>
                    <p class="text-muted small">Join Noksha as a Creator & Buyer</p>
                </div>

                <!-- Alert Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                        <ul class="mb-0 small ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Google OAuth Button Placeholder -->
                <div class="mb-4">
                    <a href="{{ route('auth.google') }}" class="btn btn-outline-dark w-100 py-2 rounded-3 d-flex align-items-center justify-content-center gap-2 fw-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                            <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C12.955 4 4 12.955 4 24s8.955 20 20 20s20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/>
                            <path fill="#FF3D00" d="m6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C16.318 4 9.656 8.337 6.306 14.691z"/>
                            <path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/>
                            <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
                        </svg>
                        <span>{{ __('auth.google_sign_in') }}</span>
                    </a>
                </div>

                <div class="d-flex align-items-center my-3">
                    <hr class="flex-grow-1 text-muted opacity-25">
                    <span class="px-3 text-muted small fw-semibold">{{ __('auth.or') }}</span>
                    <hr class="flex-grow-1 text-muted opacity-25">
                </div>

                <!-- Registration Form -->
                <form action="{{ route('register.store') }}" method="POST">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-dark">
                            {{ __('auth.name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control rounded-3 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Miad Khan" required autofocus>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold text-dark">
                            {{ __('auth.username') }} <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">@</span>
                            <input type="text" class="form-control rounded-end-3 @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="e.g. miadkhan" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-dark">
                            {{ __('auth.email') }} <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control rounded-3 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required>
                    </div>

                    <!-- Phone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold text-dark">
                            {{ __('auth.phone') }} <span class="text-muted">(Optional)</span>
                        </label>
                        <input type="text" class="form-control rounded-3 @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+8801700000000">
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold text-dark">
                            {{ __('auth.password') }} <span class="text-danger">*</span>
                        </label>
                        <input type="password" class="form-control rounded-3 @error('password') is-invalid @enderror" id="password" name="password" placeholder="At least 8 characters" required>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold text-dark">
                            {{ __('auth.confirm_password') }} <span class="text-danger">*</span>
                        </label>
                        <input type="password" class="form-control rounded-3" id="password_confirmation" name="password_confirmation" placeholder="Re-enter password" required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-noksha w-100 py-2.5 rounded-3 mb-3 fs-6">
                        {{ __('auth.register') }}
                    </button>

                    <!-- Footer Link -->
                    <div class="text-center text-muted small">
                        {{ __('auth.already_registered') }} 
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">{{ __('auth.login_now') }}</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
