@extends('layouts.app')

@section('title', 'Checkout - Noksha (নকশা)')

@section('content')

<!-- CUSTOM CHECKOUT FIGMA/DRIBBBLE STYLES -->
<style>
    .checkout-bg {
        background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%);
        min-height: 100vh;
    }

    .checkout-card-figma {
        background: #ffffff;
        border-radius: 1.25rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.06) !important;
    }

    .glass-summary-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(108, 76, 241, 0.2) !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 20px 45px -10px rgba(108, 76, 241, 0.18) !important;
        position: sticky;
        top: 100px;
    }

    /* Radio Card Selector for Payment Options */
    .payment-option-card {
        border: 2px solid rgba(108, 76, 241, 0.15);
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        background: #ffffff;
    }

    .payment-option-card:hover {
        border-color: #6C4CF1;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -4px rgba(108, 76, 241, 0.2);
    }

    .form-check-input:checked + .payment-option-label .payment-option-card {
        border-color: #6C4CF1 !important;
        background: rgba(108, 76, 241, 0.04);
        box-shadow: 0 0 0 4px rgba(108, 76, 241, 0.15);
    }

    .btn-purple-cta {
        background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 8px 20px -4px rgba(108, 76, 241, 0.4);
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #5A3DE0 0%, #4327C6 100%);
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 14px 28px -4px rgba(108, 76, 241, 0.5);
        color: #ffffff !important;
    }

    .card-grad-1 { background: linear-gradient(135deg, #6366F1 0%, #A855F7 100%); }
</style>

<div class="checkout-bg py-4 py-lg-5">
    <div class="container py-2">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-muted mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none text-primary">Cart</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">Checkout</li>
            </ol>
        </nav>

        <!-- PAGE HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(108, 76, 241, 0.08); color: #6C4CF1;">
                    <i class="bi bi-shield-lock-fill me-1"></i> Secure Digital Checkout
                </span>
                <h2 class="display-6 fw-extrabold text-dark mt-1 mb-0">Complete Your Purchase</h2>
            </div>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <div class="row g-4 g-lg-5">
                <!-- LEFT COLUMN: BILLING SUMMARY & PAYMENT METHOD (8 COLUMNS) -->
                <div class="col-12 col-lg-8">
                    
                    <!-- 1. Selected Resources Items -->
                    <div class="card checkout-card-figma p-4 mb-4">
                        <h5 class="fw-extrabold text-dark mb-3 pb-2 border-bottom d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-bag-check text-primary me-2"></i>1. Selected Resources ({{ $cartItems->count() }})</span>
                            <a href="{{ route('cart.index') }}" class="extra-small fw-bold text-primary text-decoration-none">Edit Cart</a>
                        </h5>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-uppercase extra-small text-muted fw-bold">
                                    <tr>
                                        <th>Resource Item</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        @php $res = $item->resource; @endphp
                                        @if($res)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 60px; height: 42px; background: #1E1B4B;">
                                                            @if($res->preview_image)
                                                                <img src="{{ asset('storage/' . $res->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $res->title }}">
                                                            @else
                                                                <div class="w-100 h-100 card-grad-1 p-2 text-white text-center"><i class="bi bi-box"></i></div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark small text-truncate" style="max-width: 260px;">{{ $res->title }}</div>
                                                            <div class="extra-small text-muted font-monospace">Format: {{ strtoupper($res->file_type ?? 'ZIP') }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1 fw-bold extra-small">
                                                        {{ $res->category ? $res->category->name : 'General' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-extrabold text-dark small">
                                                        {{ $res->is_paid && $res->price > 0 ? '৳' . number_format($res->price, 2) : 'Free' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 2. Payment Method Options -->
                    <div class="card checkout-card-figma p-4 mb-4">
                        <h5 class="fw-extrabold text-dark mb-3 pb-2 border-bottom">
                            <i class="bi bi-credit-card-2-front text-primary me-2"></i>2. Choose Demo Payment Method
                        </h5>

                        <div class="row g-3">
                            <!-- bKash -->
                            <div class="col-6 col-md-4">
                                <input type="radio" class="form-check-input d-none" name="payment_method" id="pay_bkash" value="bkash" checked>
                                <label class="payment-option-label w-100" for="pay_bkash">
                                    <div class="payment-option-card text-center">
                                        <div class="p-2 rounded-circle bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
                                            <i class="bi bi-phone-vibrate fs-4"></i>
                                        </div>
                                        <div class="fw-extrabold text-dark small">bKash</div>
                                        <div class="extra-small text-muted">Mobile Wallet</div>
                                    </div>
                                </label>
                            </div>

                            <!-- Nagad -->
                            <div class="col-6 col-md-4">
                                <input type="radio" class="form-check-input d-none" name="payment_method" id="pay_nagad" value="nagad">
                                <label class="payment-option-label w-100" for="pay_nagad">
                                    <div class="payment-option-card text-center">
                                        <div class="p-2 rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
                                            <i class="bi bi-wallet2 fs-4 text-warning"></i>
                                        </div>
                                        <div class="fw-extrabold text-dark small">Nagad</div>
                                        <div class="extra-small text-muted">Digital Money</div>
                                    </div>
                                </label>
                            </div>

                            <!-- Rocket -->
                            <div class="col-6 col-md-4">
                                <input type="radio" class="form-check-input d-none" name="payment_method" id="pay_rocket" value="rocket">
                                <label class="payment-option-label w-100" for="pay_rocket">
                                    <div class="payment-option-card text-center">
                                        <div class="p-2 rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
                                            <i class="bi bi-rocket-takeoff fs-4"></i>
                                        </div>
                                        <div class="fw-extrabold text-dark small">Rocket</div>
                                        <div class="extra-small text-muted">DBBL Mobile</div>
                                    </div>
                                </label>
                            </div>

                            <!-- Credit/Debit Card -->
                            <div class="col-6 col-md-4">
                                <input type="radio" class="form-check-input d-none" name="payment_method" id="pay_card" value="card">
                                <label class="payment-option-label w-100" for="pay_card">
                                    <div class="payment-option-card text-center">
                                        <div class="p-2 rounded-circle bg-info bg-opacity-10 text-info d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
                                            <i class="bi bi-credit-card fs-4 text-info"></i>
                                        </div>
                                        <div class="fw-extrabold text-dark small">Card</div>
                                        <div class="extra-small text-muted">Visa / MasterCard</div>
                                    </div>
                                </label>
                            </div>

                            <!-- Cash Demo -->
                            <div class="col-6 col-md-4">
                                <input type="radio" class="form-check-input d-none" name="payment_method" id="pay_cash" value="cash">
                                <label class="payment-option-label w-100" for="pay_cash">
                                    <div class="payment-option-card text-center">
                                        <div class="p-2 rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
                                            <i class="bi bi-cash-stack fs-4 text-success"></i>
                                        </div>
                                        <div class="fw-extrabold text-dark small">Cash / Demo</div>
                                        <div class="extra-small text-muted">Instant Access</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        @error('payment_method')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <!-- RIGHT COLUMN: STICKY ORDER SUMMARY (4 COLUMNS) -->
                <div class="col-12 col-lg-4">
                    <div class="card glass-summary-card p-4">
                        <h5 class="fw-extrabold text-dark mb-3 pb-2 border-bottom">Order Summary</h5>
                        
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small">Subtotal</span>
                            <span class="fw-bold text-dark font-monospace">৳{{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted small">Instant Delivery</span>
                            <span class="badge bg-success bg-opacity-10 text-success fw-bold extra-small">Free</span>
                        </div>

                        <div class="pt-3 border-top d-flex align-items-center justify-content-between mb-4">
                            <span class="fw-extrabold text-dark fs-5">Total Amount</span>
                            <span class="display-6 fw-extrabold text-primary">৳{{ number_format($total, 2) }}</span>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-purple-cta rounded-pill py-3.5 fw-bold fs-6 shadow-lg">
                                <i class="bi bi-shield-check me-2"></i> Place Order Now
                            </button>
                        </div>

                        <div class="extra-small text-muted text-center lh-sm">
                            <i class="bi bi-lock-fill text-success me-1"></i> By placing an order, you accept Noksha Marketplace Terms and Commercial Rights License.
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>
@endsection
