@extends('layouts.app')

@section('title', 'Checkout - Noksha')

@section('content')

<!-- CUSTOM CHECKOUT STYLES (LIGHT THEME) -->
<style>
    .checkout-bg {
        background: #ffffff; /* pure white */
        min-height: 100vh;
        color: #121826;
    }

    .checkout-card-figma {
        background: #ffffff !important;
        border-radius: 1.25rem !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
    }

    .glass-summary-card {
        background: #ffffff !important;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid #e2e8f0 !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.07) !important;
        position: sticky;
        top: 100px;
    }

    .table-checkout {
        color: #121826;
        margin-bottom: 0;
    }

    .table-checkout th {
        background: transparent;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.85rem 1rem;
    }

    .table-checkout td {
        background: transparent;
        color: #1e293b;
        font-size: 0.875rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        padding: 1rem;
    }

    .table-checkout tr:hover td {
        background: #f8fafc;
    }

    /* Radio Card Selector for Payment Options */
    .payment-option-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        cursor: pointer;
        transition: all 0.25s ease;
        background: #f8fafc;
        color: #121826;
    }

    .payment-option-card:hover {
        border-color: rgba(99, 102, 241, 0.5);
        background: #ffffff;
        transform: translateY(-2px);
    }

    .form-check-input:checked + .payment-option-label .payment-option-card {
        border-color: #20165c !important;
        background: rgba(32, 22, 92, 0.06) !important;
        box-shadow: 0 0 0 3px rgba(32, 22, 92, 0.2) !important;
    }

    .btn-purple-cta {
        background: #20165c;
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 8px 20px -4px rgba(108, 76, 241, 0.4);
    }

    .btn-purple-cta:hover {
        background: #181045;
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
            <ol class="breadcrumb small fw-semibold text-slate-400 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-[#20165c] hover:text-indigo-300"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none text-[#20165c] hover:text-indigo-300">Cart</a></li>
                <li class="breadcrumb-item active text-slate-800" aria-current="page">Checkout</li>
            </ol>
        </nav>

        <!-- PAGE HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(32, 22, 92, 0.08); color: #20165c; border: 1px solid rgba(32, 22, 92, 0.2);">
                    <i class="bi bi-shield-lock-fill me-1"></i> Secure Digital Checkout
                </span>
                <h2 class="display-6 fw-extrabold text-[#121826] mt-1 mb-0">Complete Your Purchase</h2>
            </div>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <div class="row g-4 g-lg-5">
                <!-- LEFT COLUMN: BILLING SUMMARY & PAYMENT METHOD (8 COLUMNS) -->
                <div class="col-12 col-lg-8">
                    
                    <!-- 1. Selected Resources Items -->
                    <div class="card checkout-card-figma p-4 mb-4">
                        <h5 class="fw-extrabold text-[#121826] mb-3 pb-2 border-bottom border-slate-200 d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-bag-check text-[#20165c] me-2"></i>1. Selected Resources ({{ $cartItems->count() }})</span>
                            <a href="{{ route('cart.index') }}" class="extra-small fw-bold text-[#20165c] hover:text-indigo-300 text-decoration-none">Edit Cart</a>
                        </h5>

                        <div class="table-responsive">
                            <table class="table table-checkout align-middle mb-0">
                                <thead>
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
                                                        <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 60px; height: 42px; background: #f8fafc; border: 1px solid rgba(148, 163, 184, 0.12);">
                                                            @if($res->preview_image)
                                                                <img src="{{ asset('storage/' . $res->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $res->title }}">
                                                            @else
                                                                <div class="w-100 h-100 card-grad-1 p-2 text-white text-center"><i class="bi bi-box"></i></div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-[#121826] small text-truncate" style="max-width: 260px;">{{ $res->title }}</div>
                                                            <div class="extra-small text-slate-400 font-monospace">Format: {{ strtoupper($res->file_type ?? 'ZIP') }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge rounded-pill px-2.5 py-1 fw-bold extra-small" style="background: rgba(32, 22, 92, 0.08); color: #20165c; border: 1px solid rgba(32, 22, 92, 0.2);">
                                                        {{ $res->category ? $res->category->name : 'General' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-extrabold text-[#f47402] small font-monospace">
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
                        <h5 class="fw-extrabold text-[#121826] mb-3 pb-2 border-bottom border-slate-200">
                            <i class="bi bi-credit-card-2-front text-[#20165c] me-2"></i>2. Choose Payment Method
                        </h5>

                        <div class="row g-3">
                            <!-- bKash -->
                            <div class="col-6 col-md-4">
                                <input type="radio" class="form-check-input d-none" name="payment_method" id="pay_bkash" value="bkash" checked>
                                <label class="payment-option-label w-100" for="pay_bkash">
                                    <div class="payment-option-card text-center">
                                        <div class="p-2 rounded-circle bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
                                            <i class="bi bi-phone-vibrate fs-4" style="color: #EC4899;"></i>
                                        </div>
                                        <div class="fw-extrabold text-white small">bKash</div>
                                        <div class="extra-small text-slate-400">Mobile Wallet</div>
                                    </div>
                                </label>
                            </div>

                            <!-- Nagad -->
                            <div class="col-6 col-md-4">
                                <input type="radio" class="form-check-input d-none" name="payment_method" id="pay_nagad" value="nagad">
                                <label class="payment-option-label w-100" for="pay_nagad">
                                    <div class="payment-option-card text-center">
                                        <div class="p-2 rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
                                            <i class="bi bi-wallet2 fs-4" style="color: #F97316;"></i>
                                        </div>
                                        <div class="fw-extrabold text-white small">Nagad</div>
                                        <div class="extra-small text-slate-400">Digital Money</div>
                                    </div>
                                </label>
                            </div>

                            <!-- Rocket -->
                            <div class="col-6 col-md-4">
                                <input type="radio" class="form-check-input d-none" name="payment_method" id="pay_rocket" value="rocket">
                                <label class="payment-option-label w-100" for="pay_rocket">
                                    <div class="payment-option-card text-center">
                                        <div class="p-2 rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
                                            <i class="bi bi-rocket-takeoff fs-4 text-primary"></i>
                                        </div>
                                        <div class="fw-extrabold text-white small">Rocket</div>
                                        <div class="extra-small text-slate-400">DBBL Mobile</div>
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
                                        <div class="fw-extrabold text-white small">Card</div>
                                        <div class="extra-small text-slate-400">Visa / MasterCard</div>
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
                                        <div class="fw-extrabold text-white small">Instant Demo</div>
                                        <div class="extra-small text-slate-400">Direct Download</div>
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
                        <h5 class="fw-extrabold text-[#121826] mb-3 pb-2 border-bottom border-slate-200">Order Summary</h5>
                        
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-slate-400 small">Subtotal</span>
                            <span class="fw-bold text-[#121826] font-monospace">৳{{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-slate-400 small">Instant Delivery</span>
                            <span class="badge bg-success bg-opacity-20 text-success fw-bold extra-small border border-success border-opacity-25">Free</span>
                        </div>

                        <div class="pt-3 border-top border-slate-200 d-flex align-items-center justify-content-between mb-4">
                            <span class="fw-extrabold text-[#121826] fs-5">Total Amount</span>
                            <span class="display-6 fw-extrabold text-[#f47402] font-monospace">৳{{ number_format($total, 2) }}</span>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-purple-cta rounded-pill py-3.5 fw-bold fs-6 shadow-lg">
                                <i class="bi bi-shield-check me-2"></i> Place Order Now
                            </button>
                        </div>

                        <div class="extra-small text-slate-400 text-center lh-sm">
                            <i class="bi bi-lock-fill text-success me-1"></i> By placing an order, you accept Noksha Marketplace Terms and Commercial Rights License.
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>
@endsection