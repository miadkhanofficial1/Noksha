@extends('layouts.app')

@section('title', 'Verify Email - Noksha (ইমেইল যাচাই করুন)')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="noksha-card p-4 p-md-5 shadow-sm text-center">
                <div class="d-inline-flex align-items-center justify-content-center mb-3 rounded-circle bg-primary bg-opacity-10 text-primary p-4" style="width: 80px; height: 80px;">
                    <i class="bi bi-envelope-check-fill display-5"></i>
                </div>

                <h3 class="fw-bold text-dark mb-2">Verify Your Email Address <span class="text-primary fs-4">(ইমেইল যাচাই করুন)</span></h3>
                <p class="text-muted mb-4">
                    Thanks for registering with <strong>Noksha (নকশা)</strong>! Before getting started, please check your email for a verification link.
                </p>

                @if (session('status'))
                    <div class="alert alert-success rounded-3 mb-4 small" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="d-grid gap-3">
                    <form action="{{ route('verification.send') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-noksha w-100 py-2.5 rounded-3 fw-semibold">
                            <i class="bi bi-send me-1"></i> Resend Verification Email / আবার যাচাইকরণ ইমেইল পাঠান
                        </button>
                    </form>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary w-100 py-2 rounded-3">
                            <i class="bi bi-box-arrow-right me-1"></i> Log Out / লগআউট করুন
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
