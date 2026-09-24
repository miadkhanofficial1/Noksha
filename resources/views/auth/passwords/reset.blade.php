@extends('layouts.app')

@section('title', 'Set New Password - Noksha (নতুন পাসওয়ার্ড নির্ধারণ)')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="noksha-card p-4 p-md-5 shadow-sm">
                <div class="text-center mb-4 overflow-hidden">
                    <a href="{{ route('home') }}" class="d-block text-center text-decoration-none">
                        <img src="{{ asset('images/logo.png') }}" 
                             alt="Noksha" 
                             height="48"
                             style="height: 48px; width: auto; max-height: 48px; object-fit: contain; margin: 0 auto 1.5rem auto; display: block;">
                    </a>
                    <h3 class="fw-bold text-dark dark:text-white mb-1">Set New Password <span class="text-primary fs-5">(নতুন পাসওয়ার্ড)</span></h3>
                    <p class="text-muted small">Please enter your new password below</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3 mb-4 small" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-dark">
                            Email Address <span class="text-primary">(ইমেইল ঠিকানা)</span> <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control rounded-3 @error('email') is-invalid @enderror" id="email" name="email" value="{{ $email ?? old('email') }}" required readonly>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold text-dark">
                            New Password <span class="text-primary">(নতুন পাসওয়ার্ড)</span> <span class="text-danger">*</span>
                        </label>
                        <input type="password" class="form-control rounded-3 @error('password') is-invalid @enderror" id="password" name="password" placeholder="At least 8 characters" required autofocus>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold text-dark">
                            Confirm New Password <span class="text-primary">(পাসওয়ার্ড নিশ্চিত করুন)</span> <span class="text-danger">*</span>
                        </label>
                        <input type="password" class="form-control rounded-3" id="password_confirmation" name="password_confirmation" placeholder="Re-enter new password" required>
                    </div>

                    <button type="submit" class="btn btn-noksha w-100 py-2.5 rounded-3 mb-3">
                        Reset Password / পাসওয়ার্ড পরিবর্তন করুন
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
