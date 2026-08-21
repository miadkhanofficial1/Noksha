@extends('layouts.app')

@section('title', 'Forgot Password - Noksha (পাসওয়ার্ড ভুলে গেছেন)')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="noksha-card p-4 p-md-5 shadow-sm">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center mb-3 rounded-circle bg-secondary bg-opacity-10 text-secondary p-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-key-fill fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Reset Password <span class="text-primary fs-5">(পাসওয়ার্ড রিসেট)</span></h3>
                    <p class="text-muted small">Enter your email address to receive a password reset link</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success rounded-3 mb-4 small" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3 mb-4 small" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold text-dark">
                            Email Address <span class="text-primary">(ইমেইল ঠিকানা)</span> <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control rounded-3 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                    </div>

                    <button type="submit" class="btn btn-noksha w-100 py-2.5 rounded-3 mb-3">
                        Send Password Reset Link / রিসেট লিংক পাঠান
                    </button>

                    <div class="text-center text-muted small">
                        Remembered password? 
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Back to Login / লগইনে ফিরে যান</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
