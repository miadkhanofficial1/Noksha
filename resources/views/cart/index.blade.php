@extends('layouts.app')

@section('title', 'Shopping Cart - Noksha (নকশা)')

@section('content')

<!-- CUSTOM CART STYLES -->
<style>
    .cart-bg {
        background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%);
        min-height: 100vh;
    }

    .cart-card-figma {
        background: #ffffff;
        border-radius: 1.25rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.06) !important;
    }

    .glass-summary-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(108, 76, 241, 0.2) !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 20px 45px -10px rgba(108, 76, 241, 0.15) !important;
        position: sticky;
        top: 100px;
    }

    .btn-purple-cta {
        background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 6px 16px -4px rgba(108, 76, 241, 0.35);
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #5A3DE0 0%, #4327C6 100%);
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 12px 25px -4px rgba(108, 76, 241, 0.45);
        color: #ffffff !important;
    }

    .card-grad-1 { background: linear-gradient(135deg, #6366F1 0%, #A855F7 100%); }
    .card-grad-2 { background: linear-gradient(135deg, #3B82F6 0%, #06B6D4 100%); }
</style>

<div class="cart-bg py-4 py-lg-5">
    <div class="container py-2">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-muted mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">Shopping Cart</li>
            </ol>
        </nav>

        <!-- PAGE HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(108, 76, 241, 0.08); color: #6C4CF1;">
                    <i class="bi bi-cart-fill me-1"></i> Checkout Vault
                </span>
                <h2 class="display-6 fw-extrabold text-dark mt-1 mb-0">Your Shopping Cart</h2>
            </div>
            <span class="small fw-bold text-muted font-monospace">{{ $cartItems->count() }} items selected</span>
        </div>

        @if($cartItems->count() > 0)
            <div class="row g-4 g-lg-5">
                <!-- LEFT COLUMN: CART ITEMS LIST (8 COLUMNS) -->
                <div class="col-12 col-lg-8">
                    <div class="card cart-card-figma p-4 mb-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-uppercase extra-small text-muted fw-bold">
                                    <tr>
                                        <th>Resource</th>
                                        <th>Category</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th class="text-end">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        @php $res = $item->resource; @endphp
                                        @if($res)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 70px; height: 50px; background: #1E1B4B;">
                                                            @if($res->preview_image)
                                                                <img src="{{ asset('storage/' . $res->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $res->title }}">
                                                            @else
                                                                <div class="w-100 h-100 card-grad-1 p-2 text-white text-center"><i class="bi bi-box"></i></div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <a href="{{ route('resource.show', $res->slug ?? $res->id) }}" class="fw-bold text-dark text-decoration-none text-truncate d-block" style="max-width: 220px;" title="{{ $res->title }}">
                                                                {{ $res->title }}
                                                            </a>
                                                            <div class="extra-small text-muted font-monospace">by {{ $res->owner ? $res->owner->name : 'Noksha Creator' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1 fw-bold extra-small">
                                                        {{ $res->category ? $res->category->name : 'General' }}
                                                    </span>
                                                </td>
                                                <td class="fw-bold text-muted small font-monospace">1</td>
                                                <td>
                                                    <span class="fw-extrabold text-dark fs-6">
                                                        {{ $res->is_paid && $res->price > 0 ? '৳' . number_format($res->price, 2) : 'Free' }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <form action="{{ route('cart.destroy', $res->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle p-1.5" title="Remove item">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                        <a href="{{ route('home') }}#templates" class="btn btn-outline-secondary rounded-pill px-4 py-2.5 fw-bold">
                            <i class="bi bi-arrow-left me-1"></i> Continue Browsing
                        </a>
                    </div>
                </div>

                <!-- RIGHT COLUMN: ORDER SUMMARY (4 COLUMNS) -->
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
                            <span class="fw-extrabold text-dark fs-5">Total</span>
                            <span class="display-6 fw-extrabold text-primary">৳{{ number_format($total, 2) }}</span>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <a href="{{ route('checkout.index') }}" class="btn btn-purple-cta rounded-pill py-3 fw-bold fs-6">
                                <i class="bi bi-bag-check-fill me-2"></i> Proceed to Checkout
                            </a>
                        </div>

                        <div class="extra-small text-muted text-center">
                            <i class="bi bi-shield-lock-fill text-success me-1"></i> 256-bit Encrypted Digital Transaction
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- EMPTY STATE FOR CART -->
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4">
                <div class="p-4 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;">
                    <i class="bi bi-cart-x fs-1"></i>
                </div>
                <h4 class="fw-extrabold text-dark mb-2">Your Shopping Cart is Empty</h4>
                <p class="text-secondary small mb-4" style="max-width: 460px; margin: 0 auto;">
                    Looks like you haven't added any digital assets or templates to your cart yet. Explore our marketplace to find premium UI kits, vectors, and graphics.
                </p>
                <div>
                    <a href="{{ route('home') }}#templates" class="btn btn-purple-cta rounded-pill px-5 py-3 fw-bold">
                        <i class="bi bi-shop me-2"></i> Explore Design Templates
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
