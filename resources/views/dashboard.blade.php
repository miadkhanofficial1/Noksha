@extends('layouts.app')

@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
    $currentLocale = app()->getLocale();
@endphp

@section('title', __('dashboard.page_title') . ' - Noksha')

@section('content')

<!-- SLEEK UNIFIED TOP-TABS DASHBOARD STYLES -->
<style>
    /* Top Header Bar */
    .dash-topbar {
        background: #ffffff;
        border-bottom: 1px solid rgba(124, 58, 237, 0.12);
        box-shadow: 0 4px 20px -5px rgba(79, 70, 229, 0.05);
    }

    /* Buyer / Seller Studio Pill Switcher */
    .mode-switch-pill {
        background: #0F172A;
        border-radius: 50rem;
        padding: 0.25rem;
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
    }

    .mode-switch-btn {
        border-radius: 50rem;
        padding: 0.45rem 1.15rem;
        font-size: 0.85rem;
        font-weight: 700;
        border: none;
        background: transparent;
        color: #94A3B8;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none !important;
    }

    .mode-switch-btn:hover {
        color: #FFFFFF;
    }

    .mode-switch-btn.active {
        background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);
        color: #FFFFFF !important;
        box-shadow: 0 4px 14px rgba(124, 58, 237, 0.4);
    }

    /* Horizontal Navigation Tabs Bar */
    .dash-nav-tabs-wrapper {
        background: #ffffff;
        border-bottom: 1px solid #E2E8F0;
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none;
    }

    .dash-nav-tabs-wrapper::-webkit-scrollbar {
        display: none;
    }

    .dash-nav-tab {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 1.25rem;
        font-size: 0.92rem;
        font-weight: 600;
        color: #64748B;
        border-bottom: 3px solid transparent;
        text-decoration: none !important;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .dash-nav-tab:hover {
        color: #7C3AED;
        border-bottom-color: rgba(124, 58, 237, 0.3);
    }

    .dash-nav-tab.active {
        color: #7C3AED !important;
        border-bottom-color: #7C3AED !important;
        font-weight: 700;
    }

    .dash-nav-tab.active .badge {
        background-color: #7C3AED !important;
        color: #FFFFFF !important;
    }

    /* Stat Cards */
    .stat-card-clean {
        background: #ffffff;
        border: 1px solid rgba(124, 58, 237, 0.12);
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 4px 16px -2px rgba(79, 70, 229, 0.05);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
    }

    .stat-card-clean:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -4px rgba(124, 58, 237, 0.12);
        border-color: rgba(124, 58, 237, 0.3);
    }

    .stat-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    /* Content Panes */
    .dash-pane {
        display: none;
        animation: fadeInPane 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .dash-pane.active {
        display: block;
    }

    @keyframes fadeInPane {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Clean Card Container */
    .clean-table-card {
        background: #ffffff;
        border: 1px solid rgba(124, 58, 237, 0.12);
        border-radius: 1.25rem;
        box-shadow: 0 8px 24px -4px rgba(79, 70, 229, 0.06);
        overflow: hidden;
    }

    .table-thumb {
        width: 48px;
        height: 48px;
        border-radius: 0.65rem;
        object-fit: cover;
        background: #F8F5FF;
    }

    /* Distinct Extension Buttons in Embedded Upload */
    .ext-toggle-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.95rem;
        border-radius: 0.65rem;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        user-select: none;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        background: #0F172A;
        border: 1px solid #334155;
        color: #94A3B8;
    }

    .ext-toggle-btn:hover {
        border-color: #64748B;
        color: #F8FAFC;
    }

    .ext-toggle-input:checked + .ext-toggle-btn {
        background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%) !important;
        border-color: transparent !important;
        color: #FFFFFF !important;
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(124, 58, 237, 0.4);
        outline: 2px solid rgba(167, 139, 250, 0.6);
        outline-offset: 1px;
    }

    .ext-toggle-btn .ext-check-svg {
        display: none;
        width: 14px;
        height: 14px;
    }

    .ext-toggle-input:checked + .ext-toggle-btn .ext-check-svg {
        display: inline-block;
    }

    /* Payout Gateway Card */
    .gateway-option-card {
        border: 2px solid #E2E8F0;
        border-radius: 0.85rem;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
    }

    .gateway-option-card:hover, .gateway-option-card.active {
        border-color: #7C3AED;
        background: rgba(124, 58, 237, 0.04);
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.15);
    }

    .btn-gradient-cta {
        background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 700;
        border-radius: 50rem;
        transition: all 0.25s ease;
        box-shadow: 0 6px 18px -2px rgba(124, 58, 237, 0.35);
    }

    .btn-gradient-cta:hover:not(:disabled) {
        background: linear-gradient(135deg, #6D28D9 0%, #4338CA 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 24px -2px rgba(124, 58, 237, 0.45);
    }

    .btn-gradient-cta:disabled {
        background: #CBD5E1 !important;
        color: #64748B !important;
        cursor: not-allowed;
        box-shadow: none !important;
    }

    /* Dark Mode Overrides */
    html.dark .dash-topbar,
    html.dark .dash-nav-tabs-wrapper,
    html.dark .stat-card-clean,
    html.dark .clean-table-card,
    html.dark .gateway-option-card {
        background: #1E293B !important;
        border-color: #334155 !important;
    }

    html.dark .text-dark {
        color: #F8FAFC !important;
    }

    html.dark .text-secondary,
    html.dark .text-muted {
        color: #94A3B8 !important;
    }
</style>

<!-- ========================================== -->
<!-- 1. TOP HEADER BAR: USER & ROLE & MODE -->
<!-- ========================================== -->
<div class="dash-topbar py-3.5 px-3 px-md-4">
    <div class="container-fluid max-w-7xl mx-auto">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            
            <!-- User Profile Avatar & Exact Status Badge -->
            <div class="d-flex align-items-center gap-3">
                <div class="position-relative">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle border border-2 border-primary" style="width: 48px; height: 48px; object-fit: cover;" alt="{{ $user->name }}">
                    @else
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.25rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    @if($isUserAdmin)
                        <span class="position-absolute bottom-0 end-0 badge bg-purple-600 rounded-circle p-1 border border-2 border-white" title="Admin">
                            <i class="bi bi-shield-fill-check text-white" style="font-size: 0.75rem;"></i>
                        </span>
                    @elseif($isUserContributor)
                        <span class="position-absolute bottom-0 end-0 badge bg-success rounded-circle p-1 border border-2 border-white" title="Verified Contributor">
                            <i class="bi bi-patch-check-fill text-warning" style="font-size: 0.75rem;"></i>
                        </span>
                    @endif
                </div>

                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h5 class="fw-extrabold text-dark mb-0">{{ $user->name }}</h5>
                        
                        <!-- STRICT USER ROLE BADGE -->
                        @if($isUserAdmin)
                            <span class="badge rounded-pill px-2.5 py-1 extra-small fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);">
                                <i class="bi bi-shield-lock-fill me-1"></i> {{ $userBadgeLabel }}
                            </span>
                        @elseif($isUserContributor)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                <i class="bi bi-patch-check-fill me-1"></i> {{ $userBadgeLabel }}
                            </span>
                            <!-- Glowing Golden & Green Badges (Option 3B) -->
                            <span class="badge rounded-pill px-2.5 py-1 extra-small fw-bold text-emerald-700 bg-emerald-50 border border-emerald-300 shadow-sm" style="box-shadow: 0 0 10px rgba(16, 185, 129, 0.25);">
                                <i class="bi bi-patch-check-fill text-emerald-500 me-1"></i> {{ __('dashboard.badge_verified_creator') }}
                            </span>
                            <span class="badge rounded-pill px-2.5 py-1 extra-small fw-bold text-amber-700 bg-amber-50 border border-amber-300 shadow-sm" style="box-shadow: 0 0 10px rgba(245, 158, 11, 0.25);">
                                <i class="bi bi-award-fill text-amber-500 me-1"></i> {{ __('dashboard.badge_pro_author') }}
                            </span>
                        @elseif($isUserKycPending)
                            <span class="badge bg-warning bg-opacity-15 text-warning border border-warning border-opacity-30 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                <i class="bi bi-hourglass-split me-1"></i> {{ $userBadgeLabel }}
                            </span>
                        @elseif($isUserKycRejectedInCooldown)
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                <i class="bi bi-shield-x me-1"></i> {{ $userBadgeLabel }}
                            </span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                <i class="bi bi-person-fill me-1"></i> {{ $userBadgeLabel }}
                            </span>
                        @endif
                    </div>
                    <div class="extra-small text-muted font-mono mt-0.5">
                        {{ $user->email }}
                    </div>
                </div>
            </div>

            <!-- Buyer Mode <-> Seller Studio Pill Switcher -->
            <div class="d-flex align-items-center gap-2">
                <div class="mode-switch-pill" role="group">
                    <button type="button" class="mode-switch-btn {{ $initialMode === 'buyer' ? 'active' : '' }}" id="btnBuyerMode" onclick="setDashboardMode('buyer')">
                        <i class="bi bi-bag-check-fill"></i>
                        <span>{{ __('dashboard.buyer_mode') }}</span>
                    </button>
                    <button type="button" class="mode-switch-btn {{ $initialMode === 'seller' ? 'active' : '' }}" id="btnSellerMode" onclick="setDashboardMode('seller')">
                        <i class="bi bi-palette-fill"></i>
                        <span>{{ __('dashboard.seller_studio') }}</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 2. DISTINCT HORIZONTAL NAVIGATION TABS -->
<!-- ========================================== -->
<div class="dash-nav-tabs-wrapper px-3 px-md-4">
    <div class="container-fluid max-w-7xl mx-auto d-flex align-items-center gap-1">
        
        @php
            $isUploadTab = ($tabParam ?? request()->query('tab')) === 'upload';
        @endphp

        <!-- A. BUYER MODE TABS (Visible strictly in Buyer Mode) -->
        <div id="buyerTabsNav" class="{{ $initialMode === 'seller' ? 'd-none' : 'd-flex' }} align-items-center gap-1">
            <a class="dash-nav-tab tab-btn-buyer active" data-tab="pane-buyer-overview" onclick="switchDashTab('pane-buyer-overview', this)">
                <i class="bi bi-grid-fill"></i>
                <span>{{ __('dashboard.tab_buyer_overview') }}</span>
            </a>
            <a class="dash-nav-tab tab-btn-buyer" data-tab="pane-buyer-orders" onclick="switchDashTab('pane-buyer-orders', this)">
                <i class="bi bi-receipt"></i>
                <span>{{ __('dashboard.tab_buyer_orders') }}</span>
                @if($ordersCount > 0)
                    <span class="badge bg-light text-muted rounded-pill px-2 py-0.5 extra-small">{{ $ordersCount }}</span>
                @endif
            </a>
            <a class="dash-nav-tab tab-btn-buyer" data-tab="pane-buyer-downloads" onclick="switchDashTab('pane-buyer-downloads', this)">
                <i class="bi bi-cloud-arrow-down-fill text-success"></i>
                <span>{{ __('dashboard.tab_buyer_downloads') }}</span>
                @if($downloadsCount > 0)
                    <span class="badge bg-light text-muted rounded-pill px-2 py-0.5 extra-small">{{ $downloadsCount }}</span>
                @endif
            </a>
            <a class="dash-nav-tab tab-btn-buyer" data-tab="pane-buyer-wishlist" onclick="switchDashTab('pane-buyer-wishlist', this)">
                <i class="bi bi-heart-fill text-danger"></i>
                <span>{{ __('dashboard.tab_buyer_wishlist') }}</span>
                @if($wishlistCount > 0)
                    <span class="badge bg-light text-muted rounded-pill px-2 py-0.5 extra-small">{{ $wishlistCount }}</span>
                @endif
            </a>
        </div>

        <!-- B. SELLER STUDIO TABS (Visible strictly in Seller Studio for Contributor / Admin) -->
        <div id="sellerTabsNav" class="{{ $initialMode === 'seller' ? 'd-flex' : 'd-none' }} align-items-center gap-1">
            <a class="dash-nav-tab tab-btn-seller {{ $isUploadTab ? '' : 'active' }}" data-tab="pane-seller-overview" onclick="switchDashTab('pane-seller-overview', this)">
                <i class="bi bi-speedometer2"></i>
                <span>{{ __('dashboard.tab_seller_overview') }}</span>
            </a>
            <a class="dash-nav-tab tab-btn-seller" data-tab="pane-seller-designs" onclick="switchDashTab('pane-seller-designs', this)">
                <i class="bi bi-collection-fill"></i>
                <span>{{ __('dashboard.tab_seller_designs') }}</span>
                @if($totalResources > 0)
                    <span class="badge bg-light text-muted rounded-pill px-2 py-0.5 extra-small">{{ $totalResources }}</span>
                @endif
            </a>
            <a class="dash-nav-tab tab-btn-seller {{ $isUploadTab ? 'active' : '' }}" data-tab="pane-seller-upload" onclick="switchDashTab('pane-seller-upload', this)">
                <i class="bi bi-cloud-arrow-up-fill text-warning"></i>
                <span>{{ __('dashboard.tab_seller_upload') }}</span>
            </a>
            <a class="dash-nav-tab tab-btn-seller" data-tab="pane-seller-wallet" onclick="switchDashTab('pane-seller-wallet', this)">
                <i class="bi bi-wallet2 text-success"></i>
                <span>{{ __('dashboard.tab_seller_wallet') }}</span>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-0.5 extra-small">৳{{ number_format($totalEarnings, 0) }}</span>
            </a>
        </div>

    </div>
</div>

<!-- ========================================== -->
<!-- 3. MAIN DASHBOARD CONTENT AREA -->
<!-- ========================================== -->
<div class="py-4 py-lg-5" style="background-color: #F8F7FF; min-height: 75vh;">
    <div class="container-fluid max-w-7xl mx-auto px-3 px-md-4">


        <!-- ======================================================== -->
        <!-- GROUP A: BUYER MODE CONTENT PANES (4 Distinct Buyer Tabs) -->
        <!-- ======================================================== -->
        <div id="buyerPanesContainer" class="{{ $initialMode === 'seller' ? 'd-none' : '' }}">
            
            <!-- 1. BUYER OVERVIEW TAB -->
            <div class="dash-pane active" id="pane-buyer-overview">
                <!-- 4 Buyer Stat Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <div class="stat-card-clean">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="extra-small fw-bold text-muted text-uppercase tracking-wider d-block mb-1">{{ __('dashboard.stat_total_orders') }}</span>
                                <h3 class="fw-extrabold text-dark mb-0">{{ $ordersCount }}</h3>
                            </div>
                            <div class="stat-icon-box bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-receipt"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card-clean">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="extra-small fw-bold text-muted text-uppercase tracking-wider d-block mb-1">{{ __('dashboard.stat_purchased_downloads') }}</span>
                                <h3 class="fw-extrabold text-success mb-0">{{ $downloadsCount }}</h3>
                            </div>
                            <div class="stat-icon-box bg-success bg-opacity-10 text-success">
                                <i class="bi bi-cloud-arrow-down-fill"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card-clean">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="extra-small fw-bold text-muted text-uppercase tracking-wider d-block mb-1">{{ __('dashboard.stat_saved_wishlist') }}</span>
                                <h3 class="fw-extrabold text-danger mb-0">{{ $wishlistCount }}</h3>
                            </div>
                            <div class="stat-icon-box bg-danger bg-opacity-10 text-danger">
                                <i class="bi bi-heart-fill"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card-clean">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="extra-small fw-bold text-muted text-uppercase tracking-wider d-block mb-1">{{ __('dashboard.stat_total_spent') }}</span>
                                <h3 class="fw-extrabold text-purple mb-0" style="color: #7C3AED;">৳{{ number_format($totalSpent, 2) }}</h3>
                            </div>
                            <div class="stat-icon-box bg-purple bg-opacity-10 text-purple" style="background: rgba(124,58,237,0.1); color: #7C3AED;">
                                <i class="bi bi-credit-card-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Preview Card -->
                <div class="clean-table-card">
                    <div class="p-3.5 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h6 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history text-primary"></i>
                            <span>{{ __('dashboard.recent_orders_title') }}</span>
                        </h6>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 extra-small fw-bold" onclick="switchDashTab('pane-buyer-orders')">
                            {{ __('dashboard.tab_buyer_orders') }} &rarr;
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted extra-small text-uppercase tracking-wider">
                                <tr>
                                    <th class="ps-3.5">{{ __('dashboard.order_number') }}</th>
                                    <th>Items</th>
                                    <th>{{ __('dashboard.date') }}</th>
                                    <th>{{ __('dashboard.price') }}</th>
                                    <th class="text-end pe-3.5">{{ __('dashboard.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders->take(5) as $order)
                                    <tr>
                                        <td class="ps-3.5 font-monospace small fw-bold text-primary">
                                            #{{ $order->order_number ?? $order->id }}
                                        </td>
                                        <td>
                                            <span class="small fw-semibold text-dark">{{ $order->items->count() }} items</span>
                                        </td>
                                        <td>
                                            <span class="extra-small text-muted">{{ $order->created_at->format('M d, Y') }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark fw-bold">৳{{ number_format($order->total, 2) }}</span>
                                        </td>
                                        <td class="text-end pe-3.5">
                                            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">
                                                Completed
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-2 text-secondary opacity-50 d-block mb-1.5"></i>
                                            <span class="small">{{ __('dashboard.no_orders') }}</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 2. BUYER ORDERS / INVOICES TAB -->
            <div class="dash-pane" id="pane-buyer-orders">
                <div class="clean-table-card">
                    <div class="p-3.5 border-bottom">
                        <h6 class="fw-extrabold text-dark mb-0">{{ __('dashboard.tab_buyer_orders') }}</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted extra-small text-uppercase tracking-wider">
                                <tr>
                                    <th class="ps-3.5">{{ __('dashboard.order_number') }}</th>
                                    <th>Items</th>
                                    <th>{{ __('dashboard.date') }}</th>
                                    <th>{{ __('dashboard.price') }}</th>
                                    <th>{{ __('dashboard.status') }}</th>
                                    <th class="text-end pe-3.5">Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td class="ps-3.5 font-monospace small fw-bold text-primary">
                                            #{{ $order->order_number ?? $order->id }}
                                        </td>
                                        <td>
                                            @foreach($order->items as $item)
                                                <div class="small fw-semibold text-dark text-truncate" style="max-width: 260px;">
                                                    {{ $item->resource?->title ?? 'Design Template' }}
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            <span class="extra-small text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark fw-bold">৳{{ number_format($order->total, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">
                                                Paid
                                            </span>
                                        </td>
                                        <td class="text-end pe-3.5">
                                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 extra-small fw-bold">
                                                View Order
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <span class="small">{{ __('dashboard.no_orders') }}</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 3. BUYER DOWNLOADS TAB -->
            <div class="dash-pane" id="pane-buyer-downloads">
                <div class="clean-table-card">
                    <div class="p-3.5 border-bottom">
                        <h6 class="fw-extrabold text-dark mb-0">{{ __('dashboard.tab_buyer_downloads') }} ({{ $downloadsCount }})</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted extra-small text-uppercase tracking-wider">
                                <tr>
                                    <th class="ps-3.5">Asset</th>
                                    <th>Category</th>
                                    <th>Date</th>
                                    <th class="text-end pe-3.5">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orderDownloads as $res)
                                    <tr>
                                        <td class="ps-3.5">
                                            <div class="d-flex align-items-center gap-2.5">
                                                <img src="{{ asset('storage/' . $res->preview_image) }}" class="table-thumb border" alt="{{ $res->title }}" onerror="this.src='{{ asset('images/logo.png') }}'">
                                                <div class="text-truncate" style="max-width: 280px;">
                                                    <a href="{{ route('resource.show', $res->slug ?? $res->id) }}" class="fw-bold text-dark text-decoration-none small">
                                                        {{ $res->title }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-muted extra-small">{{ $res->category?->name ?? 'Design' }}</span>
                                        </td>
                                        <td>
                                            <span class="extra-small text-muted">{{ $res->created_at->format('M d, Y') }}</span>
                                        </td>
                                        <td class="text-end pe-3.5">
                                            <a href="{{ route('resource.download', $res->id) }}" class="btn btn-gradient-cta btn-sm px-3.5 py-1 extra-small fw-bold">
                                                <i class="bi bi-download me-1"></i> {{ __('dashboard.download_again') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <span class="small">{{ __('dashboard.no_downloads') }}</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 4. BUYER WISHLIST TAB -->
            <div class="dash-pane" id="pane-buyer-wishlist">
                <div class="clean-table-card p-3.5">
                    <h6 class="fw-extrabold text-dark mb-3">{{ __('dashboard.tab_buyer_wishlist') }} ({{ $wishlistCount }})</h6>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @forelse($wishlistItems as $wItem)
                            <div class="p-3 border rounded-3 d-flex align-items-center justify-content-between gap-2.5 bg-light">
                                <div class="d-flex align-items-center gap-2.5 text-truncate">
                                    <img src="{{ asset('storage/' . $wItem->preview_image) }}" class="table-thumb border flex-shrink-0" alt="{{ $wItem->title }}" onerror="this.src='{{ asset('images/logo.png') }}'">
                                    <div class="text-truncate">
                                        <div class="fw-bold text-dark small text-truncate">{{ $wItem->title }}</div>
                                        <span class="badge bg-primary bg-opacity-10 text-primary extra-small">৳{{ number_format($wItem->price, 2) }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('resource.show', $wItem->slug ?? $wItem->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 extra-small fw-bold flex-shrink-0">
                                    {{ __('dashboard.view_product') }}
                                </a>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-5 text-muted small">
                                {{ __('dashboard.no_wishlist') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <!-- ======================================================== -->
        <!-- GROUP B: SELLER STUDIO CONTENT (Accessible by Approved/Admin) -->
        <!-- ======================================================== -->
        <div id="sellerPanesContainer" class="{{ $initialMode === 'seller' ? '' : 'd-none' }}">

            <!-- Check if User is NOT Approved Contributor and NOT Admin -->
            @if(!$isUserAdmin && !$isUserContributor)
                
                <!-- B1. KYC 7-Day Rejection Cooldown Card (Option 2B) -->
                @if($isUserKycRejectedInCooldown)
                    <div class="clean-table-card p-4 p-md-5 text-center max-w-2xl mx-auto my-4 border-danger border-opacity-30 shadow-sm">
                        <div class="w-16 h-16 rounded-full bg-rose-500/10 text-rose-500 flex items-center justify-center mx-auto mb-3.5 fs-1">
                            <i class="bi bi-shield-x"></i>
                        </div>
                        <h4 class="fw-extrabold text-dark mb-2">
                            {{ __('dashboard.kyc_cooldown_title') }}
                        </h4>
                        <p class="text-secondary small mb-4" style="max-width: 520px; margin: 0 auto;">
                            {{ __('dashboard.kyc_cooldown_desc', ['reason' => $kycRejectionReason, 'date' => $kycCooldownDate]) }}
                        </p>
                        
                        <div class="p-3.5 bg-light rounded-3 text-start small border mb-4" style="max-width: 480px; margin: 0 auto;">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Rejection Feedback:</span>
                                <span class="fw-bold text-danger">{{ $kycRejectionReason }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Status:</span>
                                <span class="badge bg-danger bg-opacity-15 text-danger fw-bold">7-Day Resubmission Cooldown</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Reapplication Unlocks:</span>
                                <span class="fw-bold text-dark font-mono">{{ $kycCooldownDate }}</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light rounded-pill px-4 py-2 fw-bold text-dark small" onclick="setDashboardMode('buyer')">
                                &larr; Return to Buyer Mode
                            </button>
                        </div>
                    </div>

                <!-- B2. KYC Pending Review Status Card -->
                @elseif($isUserKycPending)
                    <div class="clean-table-card p-4 p-md-5 text-center max-w-2xl mx-auto my-4">
                        <div class="w-16 h-16 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto mb-3.5 fs-1">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <h4 class="fw-extrabold text-dark mb-2">
                            {{ __('dashboard.kyc_pending_title') }}
                        </h4>
                        <p class="text-secondary small mb-4" style="max-width: 520px; margin: 0 auto;">
                            {{ __('dashboard.kyc_pending_desc') }}
                        </p>
                        <div class="p-3.5 bg-light rounded-3 text-start small border mb-4" style="max-width: 480px; margin: 0 auto;">
                            <div class="d-flex justify-content-between mb-1.5">
                                <span class="text-muted">Applicant:</span>
                                <span class="fw-bold text-dark">{{ $verification?->full_name ?? $user->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1.5">
                                <span class="text-muted">Status:</span>
                                <span class="badge bg-warning bg-opacity-20 text-warning fw-bold">Pending Compliance Review</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Submitted:</span>
                                <span class="text-muted">{{ $verification?->submitted_at?->format('M d, Y h:i A') ?? 'Recently' }}</span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-light rounded-pill px-4 py-2 fw-bold text-dark small" onclick="setDashboardMode('buyer')">
                            &larr; Return to Buyer Mode
                        </button>
                    </div>

                <!-- B3. KYC Application Form (Option 1B Matching) -->
                @else
                    <div class="clean-table-card p-4 p-md-5 max-w-2xl mx-auto my-4">
                        <div class="text-center mb-4">
                            <span class="badge rounded-pill px-3 py-1 extra-small fw-bold text-white mb-2" style="background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);">
                                Creator Studio Onboarding
                            </span>
                            <h4 class="fw-extrabold text-dark mb-1">
                                {{ __('dashboard.kyc_modal_title') }}
                            </h4>
                            <p class="text-secondary extra-small mb-0">
                                {{ __('dashboard.kyc_modal_sub') }}
                            </p>
                        </div>

                        <form action="{{ route('seller.verification.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="space-y-3">
                                <!-- Full Legal Name (Required) -->
                                <div>
                                    <label class="form-label fw-bold text-dark small mb-1">{{ __('dashboard.kyc_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control rounded-2 form-control-sm" value="{{ old('full_name', $user->name) }}" required>
                                </div>

                                <!-- Phone / WhatsApp (Required) -->
                                <div>
                                    <label class="form-label fw-bold text-dark small mb-1">{{ __('dashboard.kyc_phone') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control rounded-2 form-control-sm" placeholder="01XXXXXXXXX" value="{{ old('phone', $user->phone) }}" required>
                                </div>

                                <!-- National ID / Passport Number (Required) -->
                                <div>
                                    <label class="form-label fw-bold text-dark small mb-1">{{ __('dashboard.kyc_id_number') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="id_number" class="form-control rounded-2 form-control-sm" placeholder="e.g. 199XXXXXXXXXX or Passport Number" value="{{ old('id_number', $verification?->id_number) }}" required>
                                </div>

                                <!-- File 1: Government Identity Document Scan (Required) -->
                                <div>
                                    <label class="form-label fw-bold text-dark small mb-1">{{ __('dashboard.kyc_id_document') }} <span class="text-danger">*</span></label>
                                    <input type="file" name="document_file" class="form-control rounded-2 form-control-sm" accept="image/jpeg,image/png,image/jpg,application/pdf" {{ $verification && $verification->document_file ? '' : 'required' }}>
                                    <div class="extra-small text-muted mt-0.5">Attach a high-resolution scan or photo of your National ID Card, Passport, or Driving License (JPG, PNG, or PDF max 10MB).</div>
                                </div>

                                <!-- File 2: Face Selfie holding the NID Document (Required) -->
                                <div>
                                    <label class="form-label fw-bold text-dark small mb-1">{{ __('dashboard.kyc_selfie_document') }} <span class="text-danger">*</span></label>
                                    <input type="file" name="selfie_file" class="form-control rounded-2 form-control-sm" accept="image/jpeg,image/png,image/jpg" {{ $verification && $verification->selfie_file ? '' : 'required' }}>
                                    <div class="extra-small text-muted mt-0.5 text-info">
                                        <i class="bi bi-info-circle me-1"></i> {{ __('dashboard.kyc_selfie_helper') }}
                                    </div>
                                </div>

                                <!-- Portfolio Link (Required) -->
                                <div>
                                    <label class="form-label fw-bold text-dark small mb-1">{{ __('dashboard.kyc_portfolio') }} <span class="text-danger">*</span></label>
                                    <input type="url" name="portfolio_link" class="form-control rounded-2 form-control-sm" placeholder="https://behance.net/yourprofile or https://dribbble.com/..." value="{{ old('portfolio_link', $verification?->portfolio_link) }}" required>
                                </div>

                                <!-- Terms Acceptance Checkbox (Required) -->
                                <div class="pt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="agreement" id="kycAgreement" value="1" required>
                                        <label class="form-check-label extra-small text-secondary" for="kycAgreement">
                                            {{ __('dashboard.kyc_terms') }}
                                        </label>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-3">
                                    <button type="submit" class="btn btn-gradient-cta btn-lg w-100 py-2.5 fw-bold">
                                        <i class="bi bi-shield-check me-1.5"></i> {{ __('dashboard.kyc_submit_btn') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif

            @else
                <!-- APPROVED CONTRIBUTOR OR ADMIN: FULL SELLER STUDIO PANES -->

                <!-- 1. SELLER OVERVIEW TAB -->
                <div class="dash-pane {{ ($tabParam ?? request()->query('tab')) === 'upload' ? '' : 'active' }}" id="pane-seller-overview">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <div class="stat-card-clean">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="extra-small fw-bold text-muted text-uppercase tracking-wider d-block mb-1">{{ __('dashboard.stat_total_uploads') }}</span>
                                    <h3 class="fw-extrabold text-dark mb-0">{{ $totalResources }}</h3>
                                    <span class="extra-small text-muted">{{ $approvedResources }} {{ __('dashboard.status_approved') }}</span>
                                </div>
                                <div class="stat-icon-box bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-layers-fill"></i>
                                </div>
                            </div>
                        </div>

                        <div class="stat-card-clean">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="extra-small fw-bold text-muted text-uppercase tracking-wider d-block mb-1">{{ __('dashboard.stat_total_sales') }}</span>
                                    <h3 class="fw-extrabold text-success mb-0">{{ $totalSales }}</h3>
                                    <span class="extra-small text-muted">{{ __('dashboard.gross_sales') }}: ৳{{ number_format($grossSales, 0) }}</span>
                                </div>
                                <div class="stat-icon-box bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-cart-check-fill"></i>
                                </div>
                            </div>
                        </div>

                        <div class="stat-card-clean">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="extra-small fw-bold text-muted text-uppercase tracking-wider d-block mb-1">{{ __('dashboard.stat_available_earnings') }}</span>
                                    <h3 class="fw-extrabold text-primary mb-0">৳{{ number_format($totalEarnings, 2) }}</h3>
                                    <span class="extra-small text-success fw-bold">{{ __('dashboard.royalty_cut_note') }}</span>
                                </div>
                                <div class="stat-icon-box bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-wallet2"></i>
                                </div>
                            </div>
                        </div>

                        <div class="stat-card-clean">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="extra-small fw-bold text-muted text-uppercase tracking-wider d-block mb-1">{{ __('dashboard.stat_total_downloads') }}</span>
                                    <h3 class="fw-extrabold mb-0" style="color: #7C3AED;">{{ number_format($totalDownloads) }}</h3>
                                    <span class="extra-small text-muted">{{ $pendingApproval }} {{ __('dashboard.stat_pending_approval') }}</span>
                                </div>
                                <div class="stat-icon-box bg-purple bg-opacity-10 text-purple" style="background: rgba(124,58,237,0.1); color: #7C3AED;">
                                    <i class="bi bi-cloud-arrow-down-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Sales Log Table -->
                    <div class="clean-table-card">
                        <div class="p-3.5 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h6 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-clock-history text-primary"></i>
                                <span>{{ __('dashboard.recent_sales_title') }}</span>
                            </h6>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 extra-small fw-bold" onclick="switchDashTab('pane-seller-designs')">
                                {{ __('dashboard.tab_seller_designs') }} &rarr;
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted extra-small text-uppercase tracking-wider">
                                    <tr>
                                        <th class="ps-3.5">{{ __('dashboard.order_number') }}</th>
                                        <th>{{ __('dashboard.asset_name') }}</th>
                                        <th>{{ __('dashboard.customer') }}</th>
                                        <th>{{ __('dashboard.date') }}</th>
                                        <th>{{ __('dashboard.price') }}</th>
                                        <th class="text-end pe-3.5">{{ __('dashboard.royalty') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentSales as $sale)
                                        <tr>
                                            <td class="ps-3.5 font-monospace small fw-bold text-muted">
                                                #{{ $sale->order?->order_number ?? $sale->id }}
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark text-truncate" style="max-width: 260px;">
                                                    {{ $sale->resource?->title ?? 'Digital Asset' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="small text-muted">{{ $sale->order?->user?->name ?? 'Buyer' }}</span>
                                            </td>
                                            <td>
                                                <span class="extra-small text-muted">{{ $sale->created_at->format('M d, Y') }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark fw-bold">৳{{ number_format($sale->price, 2) }}</span>
                                            </td>
                                            <td class="text-end pe-3.5">
                                                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">
                                                    + ৳{{ number_format($sale->price * 0.50, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="bi bi-inbox fs-2 text-secondary opacity-50 d-block mb-1.5"></i>
                                                <span class="small">{{ __('dashboard.no_sales') }}</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 2. SELLER DESIGNS TAB -->
                <div class="dash-pane" id="pane-seller-designs">
                    <div class="clean-table-card mb-4">
                        <div class="p-3.5 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h6 class="fw-extrabold text-dark mb-0.5">{{ __('dashboard.my_uploads_heading') }}</h6>
                                <span class="extra-small text-muted">{{ __('dashboard.my_uploads_sub') }}</span>
                            </div>
                            <button type="button" class="btn btn-gradient-cta btn-sm px-3.5 py-1.5 extra-small" onclick="switchDashTab('pane-seller-upload')">
                                <i class="bi bi-plus-lg me-1"></i> {{ __('upload.hero_title') }}
                            </button>
                        </div>

                        <!-- Search & Status Filter -->
                        <div class="p-2.5 bg-light border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div style="max-width: 320px;" class="flex-grow-1">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" id="designSearch" class="form-control border-start-0" placeholder="{{ __('dashboard.search_placeholder') }}" onkeyup="filterDesignsTable()">
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-1.5">
                                <button class="btn btn-sm btn-white border rounded-pill active fw-bold px-3 py-1 extra-small" onclick="filterByStatus('all', this)">{{ __('dashboard.all_filter') }} ({{ $totalResources }})</button>
                                <button class="btn btn-sm btn-white border rounded-pill fw-bold px-3 py-1 extra-small" onclick="filterByStatus('approved', this)">{{ __('dashboard.status_approved') }} ({{ $approvedResources }})</button>
                                <button class="btn btn-sm btn-white border rounded-pill fw-bold px-3 py-1 extra-small" onclick="filterByStatus('pending', this)">{{ __('dashboard.status_pending') }} ({{ $pendingApproval }})</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="designsTable">
                                <thead class="bg-light text-muted extra-small text-uppercase tracking-wider">
                                    <tr>
                                        <th class="ps-3.5">Asset</th>
                                        <th>Category & Type</th>
                                        <th>Price</th>
                                        <th>Downloads</th>
                                        <th>Status</th>
                                        <th class="text-end pe-3.5">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contributorResources as $res)
                                        <tr data-status="{{ $res->status }}" class="design-row">
                                            <td class="ps-3.5" style="width: 280px;">
                                                <div class="d-flex align-items-center gap-2.5">
                                                    <img src="{{ asset('storage/' . $res->preview_image) }}" class="table-thumb border" alt="{{ $res->title }}" onerror="this.src='{{ asset('images/logo.png') }}'">
                                                    <div class="text-truncate">
                                                        <a href="{{ route('resource.show', $res->slug ?? $res->id) }}" class="fw-bold text-dark text-decoration-none design-title small">
                                                            {{ $res->title }}
                                                        </a>
                                                        <div class="extra-small text-muted">{{ $res->created_at->format('M d, Y') }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-muted extra-small">{{ $res->category?->name ?? 'Uncategorized' }}</span>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary extra-small">{{ $res->file_type ?? 'ZIP' }}</span>
                                            </td>
                                            <td>
                                                @if($res->is_paid)
                                                    <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">৳{{ number_format($res->price, 2) }}</span>
                                                @else
                                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold">Free</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="small fw-semibold text-muted"><i class="bi bi-download me-1 text-primary"></i>{{ $res->downloads }}</span>
                                            </td>
                                            <td>
                                                @if($res->status === 'approved')
                                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold rounded-pill px-2.5 py-1">{{ __('dashboard.status_approved') }}</span>
                                                @elseif($res->status === 'pending')
                                                    <span class="badge bg-warning bg-opacity-10 text-warning fw-bold rounded-pill px-2.5 py-1">{{ __('dashboard.status_pending') }}</span>
                                                @elseif($res->status === 'inactive')
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold rounded-pill px-2.5 py-1">{{ __('dashboard.status_inactive') }}</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger fw-bold rounded-pill px-2.5 py-1">{{ __('dashboard.status_rejected') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-3.5">
                                                <div class="d-flex align-items-center justify-content-end gap-1">
                                                    <a href="{{ route('resource.show', $res->slug ?? $res->id) }}" class="btn btn-light btn-sm rounded-pill px-2 py-1 text-primary" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <form action="{{ route('seller.resource.destroy', $res) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this resource?');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-light btn-sm rounded-pill px-2 py-1 text-danger" title="Delete">
                                                            <i class="bi bi-trash3"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <span class="small">{{ __('dashboard.no_designs') }}</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 3. SELLER UPLOAD ASSET TAB -->
                <div class="dash-pane {{ ($tabParam ?? request()->query('tab')) === 'upload' ? 'active' : '' }}" id="pane-seller-upload">
                    <div class="clean-table-card p-3.5 p-md-4">
                        <div class="p-2.5 rounded-3 mb-3 d-flex align-items-center gap-2 bg-danger bg-opacity-10 border border-danger border-opacity-20 text-danger">
                            <i class="bi bi-shield-slash-fill fs-5 flex-shrink-0"></i>
                            <div class="small fw-semibold flex-grow-1">
                                {{ __('upload.warning_suspension') }}
                            </div>
                        </div>

                        <form action="{{ route('resource.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <!-- Left Column: Drag & Drop Cover -->
                                <div class="col-12 col-lg-5">
                                    <label class="form-label fw-bold text-dark small mb-1.5">
                                        {{ __('upload.cover_image') }} <span class="text-danger">*</span>
                                    </label>

                                    <div class="p-4 border rounded-3 text-center bg-light" style="border-style: dashed !important; border-width: 2px !important; border-color: rgba(124,58,237,0.3) !important; cursor: pointer; min-height: 280px; display: flex; flex-direction: column; align-items: center; justify-content: center;" id="tabImageDropZone" onclick="document.getElementById('tab_preview_image').click();">
                                        <i class="bi bi-cloud-arrow-up text-primary display-5 mb-2"></i>
                                        <div class="fw-bold text-dark small mb-1">{{ __('upload.dropzone_text') }}</div>
                                        <div class="extra-small text-muted mb-2">{{ __('upload.dropzone_sub') }}</div>
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 extra-small fw-bold">
                                            {{ __('upload.add_image_btn') }}
                                        </button>
                                        <input type="file" name="preview_image" id="tab_preview_image" class="d-none" accept="image/png,image/jpeg,image/webp,image/jpg" required onchange="handleTabImageSelect(this)">
                                    </div>

                                    <div id="tabImagePreviewBox" class="d-none mt-2">
                                        <img id="tabImagePreviewImg" src="" class="img-fluid rounded-3 border" style="max-height: 260px; width: 100%; object-fit: cover;">
                                        <div class="d-flex justify-content-between extra-small text-muted mt-1">
                                            <span id="tabImageFileName" class="fw-bold text-success">image.jpg</span>
                                            <button type="button" class="btn btn-link btn-sm text-danger p-0 extra-small" onclick="removeTabImage()">Remove</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column: Metadata & Pricing -->
                                <div class="col-12 col-lg-7">
                                    <div class="mb-2.5">
                                        <label class="form-label fw-bold text-dark small mb-1">{{ __('upload.title') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control form-control-sm rounded-2" placeholder="{{ __('upload.title_placeholder') }}" required>
                                    </div>

                                    <div class="row g-2 mb-2.5">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small mb-1">{{ __('upload.category') }} <span class="text-danger">*</span></label>
                                            <select name="category_id" class="form-select form-select-sm rounded-2" required>
                                                <option value="">-- {{ __('upload.select_category') }} --</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small mb-1">{{ __('upload.color') }}</label>
                                            <div class="d-flex align-items-center gap-1.5">
                                                <input type="color" name="color" value="#7C3AED" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 30px; height: 30px;">
                                                <span class="extra-small text-muted">{{ __('upload.color_sub') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Distinct Extension Buttons -->
                                    <div class="mb-2.5">
                                        <label class="form-label fw-bold text-dark small mb-1">{{ __('upload.extensions') }} <span class="text-danger">*</span></label>
                                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                            @foreach(['Figma', 'PSD', 'AI', 'XD', 'EPS', 'Sketch', 'SVG', 'PDF'] as $ext)
                                                <div>
                                                    <input type="checkbox" name="extensions[]" value="{{ $ext }}" id="tab_ext_{{ $ext }}" class="d-none ext-toggle-input" {{ $loop->first ? 'checked' : '' }}>
                                                    <label for="tab_ext_{{ $ext }}" class="ext-toggle-btn py-1 px-2.5 extra-small">
                                                        <svg class="ext-check-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        <span>{{ $ext }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="mb-2.5">
                                        <label class="form-label fw-bold text-dark small mb-1">{{ __('upload.tags') }}</label>
                                        <input type="text" name="tags" class="form-control form-control-sm rounded-2" placeholder="{{ __('upload.tags_placeholder') }}">
                                    </div>

                                    <!-- Pricing Set Box -->
                                    <div class="p-3 rounded-3 bg-light border mb-2.5">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-dark small mb-1">{{ __('upload.size_dimensions') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="size_dimensions" class="form-control form-control-sm rounded-2" value="1920x1080 px" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-dark small mb-1">{{ __('upload.status') }}</label>
                                                <select name="status_toggle" class="form-select form-select-sm rounded-2">
                                                    <option value="active">{{ __('upload.status_active') }}</option>
                                                    <option value="inactive">{{ __('upload.status_inactive') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-bold text-dark small mb-1">{{ __('upload.zip_file') }} <span class="text-danger">*</span> (Strictly .zip)</label>
                                                <input type="file" name="resource_file" class="form-control form-control-sm rounded-2" accept=".zip" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-dark small mb-1">{{ __('upload.asset_type') }}</label>
                                                <select name="is_paid" class="form-select form-select-sm rounded-2" id="tabIsPaidSelect" onchange="document.getElementById('tabPriceBox').style.display = this.value === '1' ? 'block' : 'none';">
                                                    <option value="1">{{ __('upload.type_premium') }}</option>
                                                    <option value="0">{{ __('upload.type_free') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6" id="tabPriceBox">
                                                <label class="form-label fw-bold text-dark small mb-1">{{ __('upload.price') }} <span class="text-danger">*</span></label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text bg-white fw-bold">৳</span>
                                                    <input type="number" step="1" min="10" name="price" value="299" class="form-control rounded-end-2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark small mb-1">{{ __('upload.description') }} <span class="text-danger">*</span></label>
                                        <textarea name="description" rows="3" class="form-control form-control-sm rounded-2" placeholder="{{ __('upload.description_placeholder') }}" required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-gradient-cta btn-sm w-100 py-2 fw-bold">
                                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> {{ __('upload.submit_button') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 4. SELLER WALLET & PAYOUT TAB (1,000 BDT Rule) -->
                <div class="dash-pane" id="pane-seller-wallet">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
                        <div class="lg:col-span-2">
                            <div class="clean-table-card p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <h6 class="fw-extrabold text-dark mb-0.5">{{ __('dashboard.payout_card_title') }}</h6>
                                        <span class="extra-small text-muted">{{ __('dashboard.wallet_sub') }}</span>
                                    </div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1.5 rounded-pill fw-bold fs-6">
                                        ৳{{ number_format($totalEarnings, 2) }}
                                    </span>
                                </div>

                                <!-- 1,000 BDT STRICT THRESHOLD NOTICE -->
                                @if($totalEarnings < 1000)
                                    <div class="alert alert-warning border-0 rounded-3 p-3 mb-4 d-flex align-items-center gap-2.5 bg-warning bg-opacity-10 text-dark">
                                        <i class="bi bi-info-circle-fill fs-4 text-warning flex-shrink-0"></i>
                                        <div>
                                            <div class="fw-bold small">{{ __('dashboard.min_threshold_notice') }}</div>
                                            <div class="extra-small text-muted">{{ __('dashboard.min_threshold_sub') }} ({{ __('dashboard.available_balance') }}: ৳{{ number_format($totalEarnings, 2) }})</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-success border-0 rounded-3 p-3 mb-4 d-flex align-items-center gap-2.5 bg-success bg-opacity-10 text-dark">
                                        <i class="bi bi-check-circle-fill fs-4 text-success flex-shrink-0"></i>
                                        <div class="small fw-semibold">
                                            {{ __('dashboard.available_balance') }}: <strong>৳{{ number_format($totalEarnings, 2) }}</strong>
                                        </div>
                                    </div>
                                @endif

                                <!-- Withdrawal Form -->
                                <form action="{{ route('seller.payout.request') }}" method="POST">
                                    @csrf

                                    <!-- Amount -->
                                    <div class="mb-3">
                                        <label for="payoutAmount" class="form-label fw-bold text-dark small mb-1">
                                            {{ __('dashboard.payout_amount') }} <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light fw-bold text-primary">৳</span>
                                            <input type="number" step="1" min="1000" max="{{ max(1000, $totalEarnings) }}" name="amount" id="payoutAmount" class="form-control rounded-end-2 @error('amount') is-invalid @enderror" placeholder="{{ __('dashboard.payout_amount_placeholder') }}" value="{{ old('amount', $totalEarnings >= 1000 ? floor($totalEarnings) : '') }}" {{ $totalEarnings < 1000 ? 'disabled' : '' }} required>
                                        </div>
                                        @error('amount')
                                            <div class="text-danger extra-small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- 3 Strict Gateways (bKash, Nagad, Bank) -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark small mb-2 d-block">
                                            {{ __('dashboard.payment_gateway') }} <span class="text-danger">*</span>
                                        </label>
                                        <div class="row g-2">
                                            <div class="col-4">
                                                <div class="gateway-option-card text-center active" id="cardBkash" onclick="selectPayoutGateway('bkash')">
                                                    <input type="radio" name="payment_method" value="bkash" id="gwBkash" class="d-none" checked {{ $totalEarnings < 1000 ? 'disabled' : '' }}>
                                                    <div class="fw-bold text-dark small mb-0.5"><i class="bi bi-phone-fill text-danger me-1"></i> bKash</div>
                                                    <div class="extra-small text-muted">Mobile Wallet</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="gateway-option-card text-center" id="cardNagad" onclick="selectPayoutGateway('nagad')">
                                                    <input type="radio" name="payment_method" value="nagad" id="gwNagad" class="d-none" {{ $totalEarnings < 1000 ? 'disabled' : '' }}>
                                                    <div class="fw-bold text-dark small mb-0.5"><i class="bi bi-wallet-fill text-warning me-1"></i> Nagad</div>
                                                    <div class="extra-small text-muted">Mobile Wallet</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="gateway-option-card text-center" id="cardBank" onclick="selectPayoutGateway('bank')">
                                                    <input type="radio" name="payment_method" value="bank" id="gwBank" class="d-none" {{ $totalEarnings < 1000 ? 'disabled' : '' }}>
                                                    <div class="fw-bold text-dark small mb-0.5"><i class="bi bi-bank2 text-primary me-1"></i> Bank</div>
                                                    <div class="extra-small text-muted">Direct Transfer</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mobile Banking Phone Field (bKash / Nagad) -->
                                    <div id="mobileBankingFields" class="mb-3">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label for="payoutPhone" class="form-label fw-bold text-dark small mb-1">
                                                    {{ __('dashboard.phone_number') }} <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="phone" id="payoutPhone" class="form-control rounded-2" placeholder="{{ __('dashboard.phone_placeholder') }}" value="{{ old('phone') }}" {{ $totalEarnings < 1000 ? 'disabled' : '' }}>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-dark small mb-1">{{ __('dashboard.account_type') }}</label>
                                                <select name="account_type" class="form-select rounded-2" {{ $totalEarnings < 1000 ? 'disabled' : '' }}>
                                                    <option value="personal">{{ __('dashboard.account_personal') }}</option>
                                                    <option value="merchant">{{ __('dashboard.account_merchant') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bank Banking Fields -->
                                    <div id="bankBankingFields" class="d-none mb-3">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-dark small mb-1">{{ __('dashboard.bank_name') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="bank_name" class="form-control rounded-2" placeholder="{{ __('dashboard.bank_placeholder') }}" {{ $totalEarnings < 1000 ? 'disabled' : '' }}>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-dark small mb-1">{{ __('dashboard.account_holder') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="account_name" class="form-control rounded-2" placeholder="{{ __('dashboard.account_holder_placeholder') }}" {{ $totalEarnings < 1000 ? 'disabled' : '' }}>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-dark small mb-1">{{ __('dashboard.account_number') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="account_number" class="form-control rounded-2" placeholder="{{ __('dashboard.account_number_placeholder') }}" {{ $totalEarnings < 1000 ? 'disabled' : '' }}>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-dark small mb-1">{{ __('dashboard.routing_number') }}</label>
                                                <input type="text" name="routing_number" class="form-control rounded-2" placeholder="{{ __('dashboard.routing_placeholder') }}" {{ $totalEarnings < 1000 ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button: Disabled if balance < 1000 BDT -->
                                    <div class="pt-2">
                                        <button type="submit" class="btn btn-gradient-cta btn-lg w-100 py-2.5 fw-bold" {{ $totalEarnings < 1000 ? 'disabled' : '' }}>
                                            <i class="bi bi-send-check-fill me-1.5"></i> {{ __('dashboard.submit_payout') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Right 1 Col: Policy Card -->
                        <div class="lg:col-span-1">
                            <div class="clean-table-card p-4 h-100">
                                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-shield-check text-primary fs-5"></i>
                                    {{ __('dashboard.policy_title') }}
                                </h6>
                                <ul class="list-unstyled small text-secondary d-flex flex-column gap-2.5 mb-0">
                                    <li class="d-flex align-items-start gap-2">
                                        <i class="bi bi-check-circle-fill text-success mt-0.5"></i>
                                        <span>{{ __('dashboard.policy_rule_1') }}</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2">
                                        <i class="bi bi-check-circle-fill text-success mt-0.5"></i>
                                        <span><strong>{{ __('dashboard.policy_rule_2') }}</strong></span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2">
                                        <i class="bi bi-check-circle-fill text-success mt-0.5"></i>
                                        <span>{{ __('dashboard.policy_rule_3') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            @endif

        </div>

    </div>
</div>

<!-- ========================================== -->
<!-- 4. JAVASCRIPT: STRICT MODE & TAB SWITCHING -->
<!-- ========================================== -->
<script>
// Check if user is approved or admin
const isUserAdminOrContributor = {{ ($isUserAdmin || $isUserContributor) ? 'true' : 'false' }};

// Mode Switcher (Buyer Mode vs Seller Studio)
function setDashboardMode(mode, preferredPaneId = null) {
    localStorage.setItem('noksha_dash_mode', mode);

    const btnBuyer = document.getElementById('btnBuyerMode');
    const btnSeller = document.getElementById('btnSellerMode');
    const buyerTabs = document.getElementById('buyerTabsNav');
    const sellerTabs = document.getElementById('sellerTabsNav');
    const buyerPanes = document.getElementById('buyerPanesContainer');
    const sellerPanes = document.getElementById('sellerPanesContainer');

    if (mode === 'buyer') {
        btnBuyer.classList.add('active');
        btnSeller.classList.remove('active');
        
        buyerTabs.classList.remove('d-none');
        buyerTabs.classList.add('d-flex');
        sellerTabs.classList.remove('d-flex');
        sellerTabs.classList.add('d-none');

        buyerPanes.classList.remove('d-none');
        sellerPanes.classList.add('d-none');

        // Activate buyer tab
        switchDashTab(preferredPaneId || 'pane-buyer-overview');
    } else {
        btnSeller.classList.add('active');
        btnBuyer.classList.remove('active');

        if (isUserAdminOrContributor) {
            sellerTabs.classList.remove('d-none');
            sellerTabs.classList.add('d-flex');
            buyerTabs.classList.remove('d-flex');
            buyerTabs.classList.add('d-none');

            sellerPanes.classList.remove('d-none');
            buyerPanes.classList.add('d-none');

            switchDashTab(preferredPaneId || 'pane-seller-overview');
        } else {
            // For normal buyers, show KYC application screen while hiding buyer/seller tabs
            buyerTabs.classList.remove('d-flex');
            buyerTabs.classList.add('d-none');
            sellerTabs.classList.remove('d-flex');
            sellerTabs.classList.add('d-none');

            sellerPanes.classList.remove('d-none');
            buyerPanes.classList.add('d-none');
        }
    }
}

// Horizontal Tab Switcher
function switchDashTab(paneId, triggerEl = null) {
    // Hide all panes
    document.querySelectorAll('.dash-pane').forEach(el => el.classList.remove('active'));

    // Deactivate all tab links
    document.querySelectorAll('.dash-nav-tab').forEach(el => el.classList.remove('active'));

    // Show target pane
    const target = document.getElementById(paneId);
    if (target) {
        target.classList.add('active');
    }

    // Set active tab styling
    if (triggerEl) {
        triggerEl.classList.add('active');
    } else {
        const link = document.querySelector(`.dash-nav-tab[data-tab="${paneId}"]`);
        if (link) link.classList.add('active');
    }

    // Update URL query tab parameter without scrolling
    if (history.pushState) {
        const tabKey = paneId.replace('pane-buyer-', '').replace('pane-seller-', '').replace('pane-', '');
        const newUrl = new URL(window.location);
        newUrl.searchParams.set('tab', tabKey);
        history.pushState(null, null, newUrl.toString());
    }
}

// Payout Gateway Switcher
function selectPayoutGateway(gateway) {
    document.querySelectorAll('.gateway-option-card').forEach(c => c.classList.remove('active'));

    const mobileFields = document.getElementById('mobileBankingFields');
    const bankFields = document.getElementById('bankBankingFields');

    if (gateway === 'bkash') {
        document.getElementById('cardBkash').classList.add('active');
        document.getElementById('gwBkash').checked = true;
        if (mobileFields) mobileFields.classList.remove('d-none');
        if (bankFields) bankFields.classList.add('d-none');
    } else if (gateway === 'nagad') {
        document.getElementById('cardNagad').classList.add('active');
        document.getElementById('gwNagad').checked = true;
        if (mobileFields) mobileFields.classList.remove('d-none');
        if (bankFields) bankFields.classList.add('d-none');
    } else if (gateway === 'bank') {
        document.getElementById('cardBank').classList.add('active');
        document.getElementById('gwBank').checked = true;
        if (mobileFields) mobileFields.classList.add('d-none');
        if (bankFields) bankFields.classList.remove('d-none');
    }
}

// Embedded Upload Cover Image Preview
function handleTabImageSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('tabImageFileName').textContent = file.name;
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('tabImagePreviewImg').src = e.target.result;
            document.getElementById('tabImageDropZone').classList.add('d-none');
            document.getElementById('tabImagePreviewBox').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
}

function removeTabImage() {
    document.getElementById('tab_preview_image').value = '';
    document.getElementById('tabImagePreviewBox').classList.add('d-none');
    document.getElementById('tabImageDropZone').classList.remove('d-none');
}

// Designs Search & Filter
function filterDesignsTable() {
    const query = document.getElementById('designSearch').value.toLowerCase();
    document.querySelectorAll('.design-row').forEach(row => {
        const title = row.querySelector('.design-title').textContent.toLowerCase();
        row.style.display = title.includes(query) ? '' : 'none';
    });
}

function filterByStatus(status, btn) {
    btn.parentElement.querySelectorAll('button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.design-row').forEach(row => {
        if (status === 'all' || row.getAttribute('data-status') === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const modeParam = urlParams.get('mode');
    const tabParam = urlParams.get('tab');
    const savedMode = localStorage.getItem('noksha_dash_mode');
    const hash = window.location.hash.replace('#', '');
    const activeTab = tabParam || hash;

    let targetPane = null;
    let targetMode = '{{ $initialMode }}';

    if (activeTab) {
        if (activeTab === 'upload' || activeTab === 'pane-seller-upload') {
            targetPane = 'pane-seller-upload';
            targetMode = 'seller';
        } else if (activeTab === 'designs' || activeTab === 'my-uploads' || activeTab === 'pane-seller-designs') {
            targetPane = 'pane-seller-designs';
            targetMode = 'seller';
        } else if (activeTab === 'wallet' || activeTab === 'earnings' || activeTab === 'pane-seller-wallet') {
            targetPane = 'pane-seller-wallet';
            targetMode = 'seller';
        } else if (activeTab === 'seller-overview' || activeTab === 'pane-seller-overview') {
            targetPane = 'pane-seller-overview';
            targetMode = 'seller';
        } else if (activeTab === 'orders' || activeTab === 'invoices' || activeTab === 'pane-buyer-orders') {
            targetPane = 'pane-buyer-orders';
            targetMode = 'buyer';
        } else if (activeTab === 'downloads' || activeTab === 'pane-buyer-downloads') {
            targetPane = 'pane-buyer-downloads';
            targetMode = 'buyer';
        } else if (activeTab === 'wishlist' || activeTab === 'pane-buyer-wishlist') {
            targetPane = 'pane-buyer-wishlist';
            targetMode = 'buyer';
        } else if (activeTab === 'overview' || activeTab === 'buyer-overview' || activeTab === 'pane-buyer-overview') {
            targetPane = 'pane-buyer-overview';
            targetMode = 'buyer';
        } else {
            const possiblePanes = [
                'pane-seller-' + activeTab,
                'pane-buyer-' + activeTab,
                'pane-' + activeTab
            ];
            for (const p of possiblePanes) {
                if (document.getElementById(p)) {
                    targetPane = p;
                    targetMode = p.includes('seller') ? 'seller' : 'buyer';
                    break;
                }
            }
        }
    }

    if (!targetMode) {
        if (modeParam === 'buyer' || modeParam === 'seller') {
            targetMode = modeParam;
        } else if (savedMode === 'buyer' || savedMode === 'seller') {
            targetMode = savedMode;
        } else {
            targetMode = 'buyer';
        }
    }

    setDashboardMode(targetMode, targetPane);

    if (targetPane && document.getElementById(targetPane)) {
        switchDashTab(targetPane);
    }
});
</script>

@endsection
