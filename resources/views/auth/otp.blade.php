@extends('layouts.app')

@section('title', 'OTP Verification - Noksha (ওটিপি যাচাইকরণ)')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="noksha-card p-4 p-md-5 shadow-sm text-center">
                <div class="d-inline-flex align-items-center justify-content-center mb-3 rounded-circle bg-warning bg-opacity-10 text-warning p-3" style="width: 64px; height: 64px;">
                    <i class="bi bi-shield-lock-fill fs-2"></i>
                </div>

                <h3 class="fw-bold text-dark mb-1">OTP Verification <span class="text-primary fs-5">(ওটিপি যাচাইকরণ)</span></h3>
                <p class="text-muted small mb-4">Enter the 6-digit verification code sent to your device</p>

                @if (session('info'))
                    <div class="alert alert-info rounded-3 mb-4 small" role="alert">
                        {{ session('info') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3 mb-4 small" role="alert">
                        <ul class="mb-0 ps-3 text-start">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('otp.verify') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <input type="text" class="form-control form-control-lg text-center letter-spacing-2 fw-bold rounded-3" name="otp_code" maxlength="6" placeholder="0 0 0 0 0 0" required autofocus style="letter-spacing: 0.5rem; font-size: 1.5rem;">
                    </div>

                    <button type="submit" class="btn btn-noksha w-100 py-2.5 rounded-3 mb-3">
                        Verify Code / ওটিপি যাচাই করুন
                    </button>
                </form>

                <form action="{{ route('otp.send') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none small text-muted">
                        Didn't receive code? Resend OTP / আবার কোড পাঠান
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
