@extends('layouts.app')

@section('title', 'Central Wallet & AI Credit Store - Noksha')

@section('content')
<style>
    /* Dark Theme Wallet & Billing Styling */
    .wallet-wrapper {
        background-color: #020617; /* slate-950 */
        color: #F8FAFC; /* slate-50 */
        min-height: calc(100vh - 72px);
        position: relative;
    }

    .wallet-hero-glow {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 750px;
        height: 350px;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, rgba(124, 58, 237, 0.1) 40%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }

    /* Glass Cards */
    .wallet-glass-card {
        background: rgba(15, 23, 42, 0.75); /* slate-900 */
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(148, 163, 184, 0.14);
        border-radius: 1.25rem;
        padding: 1.75rem;
        transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
    }

    .wallet-glass-card:hover {
        border-color: rgba(148, 163, 184, 0.25);
    }

    /* Top Metric Stat Cards */
    .metric-stat-card {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(148, 163, 184, 0.16);
        border-radius: 1.25rem;
        padding: 1.75rem;
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .metric-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px -10px rgba(0, 0, 0, 0.5);
    }

    .metric-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }

    /* Package Pricing Tier Cards */
    .package-tier-card {
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1.5px solid rgba(148, 163, 184, 0.15);
        border-radius: 1.5rem;
        padding: 2.25rem 1.75rem;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .package-tier-card:hover {
        transform: translateY(-6px);
        border-color: rgba(124, 58, 237, 0.5);
        box-shadow: 0 20px 40px -15px rgba(124, 58, 237, 0.25);
    }

    .package-tier-popular {
        border-color: rgba(139, 92, 246, 0.6) !important;
        background: linear-gradient(180deg, rgba(30, 27, 75, 0.6) 0%, rgba(15, 23, 42, 0.85) 100%) !important;
        box-shadow: 0 15px 35px -10px rgba(139, 92, 246, 0.3);
    }

    .package-ribbon {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 0.35rem 0.85rem;
        border-radius: 50rem;
        background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);
        color: #FFFFFF;
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.4);
    }

    /* Dark Form Inputs */
    .form-control-wallet {
        background-color: #0B1120; /* deep slate */
        border: 1.5px solid #1E293B;
        color: #F8FAFC !important;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control-wallet:focus {
        background-color: #0F172A;
        border-color: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        outline: none;
    }

    /* Hide native number input spinners (up/down arrows) */
    #depositAmountInput::-webkit-outer-spin-button,
    #depositAmountInput::-webkit-inner-spin-button,
    input[type="number"].form-control-wallet::-webkit-outer-spin-button,
    input[type="number"].form-control-wallet::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    #depositAmountInput,
    input[type="number"].form-control-wallet {
        -moz-appearance: textfield;
        appearance: textfield;
    }

    /* Preset Quick Amount Pills */
    .amount-preset-btn {
        background: #0B1120;
        border: 1px solid #1E293B;
        color: #94A3B8;
        border-radius: 50rem;
        padding: 0.4rem 1rem;
        font-size: 0.825rem;
        font-weight: 700;
        font-family: monospace;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .amount-preset-btn:hover, .amount-preset-btn.active {
        background: rgba(16, 185, 129, 0.15) !important;
        border-color: #10B981 !important;
        color: #34D399 !important;
    }

    /* Payment Method Radio Cards */
    .method-card-option {
        cursor: pointer;
        display: block;
        margin-bottom: 0;
    }

    .method-card-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .method-card-box {
        background: #0B1120;
        border: 1.5px solid #1E293B;
        border-radius: 0.85rem;
        padding: 0.85rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.25s ease;
    }

    /* bKash (Brand Pink/Rose) */
    .method-card-option.method-bkash:hover .method-card-box {
        border-color: rgba(236, 72, 153, 0.5);
        background: #0F172A;
    }
    .method-card-option.method-bkash input[type="radio"]:checked + .method-card-box {
        border-color: #EC4899 !important;
        background: rgba(236, 72, 153, 0.14) !important;
        box-shadow: 0 0 0 2px rgba(236, 72, 153, 0.3), 0 0 20px rgba(236, 72, 153, 0.25) !important;
    }

    /* Nagad (Brand Orange) */
    .method-card-option.method-nagad:hover .method-card-box {
        border-color: rgba(249, 115, 22, 0.5);
        background: #0F172A;
    }
    .method-card-option.method-nagad input[type="radio"]:checked + .method-card-box {
        border-color: #F97316 !important;
        background: rgba(249, 115, 22, 0.14) !important;
        box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.3), 0 0 20px rgba(249, 115, 22, 0.25) !important;
    }

    /* Bank Card (Brand Blue) */
    .method-card-option.method-card:hover .method-card-box {
        border-color: rgba(59, 130, 246, 0.5);
        background: #0F172A;
    }
    .method-card-option.method-card input[type="radio"]:checked + .method-card-box {
        border-color: #3B82F6 !important;
        background: rgba(59, 130, 246, 0.14) !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3), 0 0 20px rgba(59, 130, 246, 0.25) !important;
    }

    /* Transaction Table */
    .table-wallet {
        color: #F8FAFC;
        margin-bottom: 0;
    }

    .table-wallet th {
        background: transparent;
        color: #94A3B8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: 1px solid rgba(148, 163, 184, 0.15);
        padding: 0.85rem 1rem;
    }

    .table-wallet td {
        background: transparent;
        color: #E2E8F0;
        font-size: 0.875rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(148, 163, 184, 0.08);
        padding: 1rem;
    }

    .table-wallet tr:hover td {
        background: rgba(255, 255, 255, 0.02);
    }

    /* Custom Gradient Buttons */
    .btn-emerald-recharge {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: #FFFFFF !important;
        border: none;
        border-radius: 0.75rem;
        padding: 0.75rem 1.75rem;
        font-weight: 700;
        font-size: 0.95rem;
        box-shadow: 0 4px 18px rgba(16, 185, 129, 0.35);
        transition: all 0.25s ease;
    }

    .btn-emerald-recharge:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5);
    }

    .btn-buy-pack {
        background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);
        color: #FFFFFF !important;
        border: none;
        border-radius: 50rem;
        padding: 0.75rem 1.5rem;
        font-weight: 700;
        font-size: 0.9rem;
        width: 100%;
        box-shadow: 0 4px 16px rgba(124, 58, 237, 0.35);
        transition: all 0.25s ease;
    }

    .btn-buy-pack:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(124, 58, 237, 0.55);
    }

    /* Scoped Pagination Clean-up */
    .wallet-wrapper nav[role="navigation"] p {
        display: none !important;
    }
    .wallet-wrapper nav[role="navigation"] > div.sm\:hidden {
        display: none !important;
    }
</style>

<div class="wallet-wrapper position-relative py-5">
    <div class="wallet-hero-glow"></div>

    <div class="container position-relative z-1" style="max-width: 1140px;">

        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-5">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small"
                          style="background: rgba(16, 185, 129, 0.15); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.3);">
                        <i class="bi bi-wallet2 me-1"></i> Central Finance Hub
                    </span>
                    <span class="extra-small text-slate-400 font-monospace">Realtime Balance Engine</span>
                </div>
                <h1 class="display-6 fw-extrabold text-white mb-1">Billing, Wallet & AI Store</h1>
                <p class="text-slate-400 small mb-0" style="max-width: 620px;">
                    Manage your central BDT wallet funds, purchase instant AI customizer token bundles, and audit your complete ledger.
                </p>
            </div>

            <!-- Quick Action Shortcuts -->
            <div class="d-flex align-items-center gap-2">
                <a href="#depositSection" class="btn btn-emerald-recharge d-inline-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Add Funds</span>
                </a>
                @if(auth()->user()->isAdmin())
                    <span class="badge rounded-3 px-3 py-2.5 fw-bold d-inline-flex align-items-center gap-2"
                          style="background: rgba(139, 92, 246, 0.18); border: 1px solid rgba(139, 92, 246, 0.4); color: #C4B5FD;">
                        <i class="bi bi-shield-check text-purple-400"></i>
                        <span>Admin Unlimited</span>
                    </span>
                @else
                    <a href="#packagesSection" class="btn btn-outline-secondary rounded-3 text-slate-300 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="border-color: rgba(148, 163, 184, 0.3);">
                        <i class="bi bi-lightning-charge-fill text-warning"></i>
                        <span>Buy Credits</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Flash Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-lg py-3 px-4 mb-4 d-flex align-items-center gap-3"
                 style="background-color: rgba(16, 185, 129, 0.18); border: 1px solid rgba(16, 185, 129, 0.35) !important;" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                <div class="fw-semibold text-emerald-300 small">{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-lg py-3 px-4 mb-4 d-flex align-items-center gap-3"
                 style="background-color: rgba(239, 68, 68, 0.18); border: 1px solid rgba(239, 68, 68, 0.35) !important;" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                <div class="fw-semibold text-red-300 small">{{ session('error') }}</div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show rounded-4 border-0 shadow-lg py-3 px-4 mb-4 d-flex align-items-center gap-3"
                 style="background-color: rgba(99, 102, 241, 0.18); border: 1px solid rgba(99, 102, 241, 0.35) !important;" role="alert">
                <i class="bi bi-info-circle-fill text-info fs-4"></i>
                <div class="fw-semibold text-indigo-300 small">{{ session('info') }}</div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-lg py-3 px-4 mb-4"
                 style="background-color: rgba(239, 68, 68, 0.18); border: 1px solid rgba(239, 68, 68, 0.35) !important;" role="alert">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-exclamation-octagon-fill text-danger fs-5"></i>
                    <strong class="small text-red-300">Action could not be completed:</strong>
                </div>
                <ul class="mb-0 small text-red-300 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- ========================================================= -->
        <!-- 1. TOP METRIC CARDS                                       -->
        <!-- ========================================================= -->
        <div class="row g-4 mb-5">
            
            <!-- Card 1: Available Wallet Balance -->
            <div class="col-12 col-md-4">
                <div class="metric-stat-card">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="extra-small fw-bold text-uppercase tracking-wider text-slate-400">Available Balance</span>
                            <div class="metric-icon-box" style="background: rgba(16, 185, 129, 0.15); color: #34D399;">
                                <i class="bi bi-wallet2"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline gap-1 mb-1 font-monospace">
                            <span class="fs-4 text-emerald-400 fw-bold">৳</span>
                            <span class="display-6 fw-extrabold text-white">{{ number_format($wallet->balance, 2) }}</span>
                        </div>
                        <div class="extra-small text-slate-400">Active Bangladeshi Taka funds</div>
                    </div>
                    <div class="pt-3 mt-3 border-top border-slate-800 d-flex align-items-center justify-content-between">
                        <span class="extra-small text-slate-500 font-monospace">Wallet ID #{{ $wallet->id }}</span>
                        <a href="#depositSection" class="text-decoration-none extra-small fw-bold text-emerald-400 hover-underline d-inline-flex align-items-center gap-1">
                            <span>Top Up Now</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Available AI Credits -->
            <div class="col-12 col-md-4">
                <div class="metric-stat-card">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="extra-small fw-bold text-uppercase tracking-wider text-slate-400">Available AI Tokens</span>
                            <div class="metric-icon-box" style="background: rgba(245, 158, 11, 0.15); color: #FBBF24;">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </div>
                        </div>
                        @if(auth()->user()->isAdmin())
                            <div class="d-flex align-items-baseline gap-2 mb-1 font-monospace">
                                <span class="display-6 fw-extrabold text-warning">Unlimited</span>
                                <span class="fs-4 text-purple-400 fw-bold">∞</span>
                            </div>
                            <div class="extra-small text-slate-400">Admin Unlimited Access</div>
                        @else
                            <div class="d-flex align-items-baseline gap-1 mb-1 font-monospace">
                                <span class="display-6 fw-extrabold text-warning">{{ number_format($aiCredit->credits) }}</span>
                                <span class="fs-6 text-slate-400 fw-semibold">Tokens</span>
                            </div>
                            <div class="extra-small text-slate-400">1 Token = 1 High-Res AI Graphic Customization</div>
                        @endif
                    </div>
                    <div class="pt-3 mt-3 border-top border-slate-800 d-flex align-items-center justify-content-between">
                        <span class="extra-small text-slate-500 font-monospace">
                            @if(auth()->user()->isAdmin())
                                Role: Super Admin
                            @else
                                Account #{{ $aiCredit->id }}
                            @endif
                        </span>
                        @if(auth()->user()->isAdmin())
                            <span class="badge extra-small fw-bold px-2.5 py-1 rounded-pill" style="background: rgba(139, 92, 246, 0.18); border: 1px solid rgba(139, 92, 246, 0.35); color: #C4B5FD;">
                                <i class="bi bi-shield-check me-1 text-purple-400"></i> Admin Unlimited
                            </span>
                        @else
                            <a href="#packagesSection" class="text-decoration-none extra-small fw-bold text-warning hover-underline d-inline-flex align-items-center gap-1">
                                <span>Buy Bundle</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card 3: Total AI Generations / Edits Performed -->
            <div class="col-12 col-md-4">
                <div class="metric-stat-card">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="extra-small fw-bold text-uppercase tracking-wider text-slate-400">AI Graphics Synthesized</span>
                            <div class="metric-icon-box" style="background: rgba(124, 58, 237, 0.15); color: #A78BFA;">
                                <i class="bi bi-magic"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline gap-1 mb-1 font-monospace">
                            <span class="display-6 fw-extrabold text-purple-400">{{ number_format($totalGenerations) }}</span>
                            <span class="fs-6 text-slate-400 fw-semibold">Generations</span>
                        </div>
                        <div class="extra-small text-slate-400">Completed template customizations</div>
                    </div>
                    <div class="pt-3 mt-3 border-top border-slate-800 d-flex align-items-center justify-content-between">
                        <span class="extra-small text-slate-500 font-monospace">Unlimited Canvas Engine</span>
                        <a href="{{ route('resources.index') }}" class="text-decoration-none extra-small fw-bold text-purple-400 hover-underline d-inline-flex align-items-center gap-1">
                            <span>Explore Templates</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================= -->
        <!-- 2. CREDIT PRICING BUNDLES (STOREFRONT)                    -->
        <!-- ========================================================= -->
        <div id="packagesSection" class="mb-5">
            <div class="text-center mb-4">
                <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small mb-2"
                      style="background: rgba(124, 58, 237, 0.15); color: #C4B5FD; border: 1px solid rgba(124, 58, 237, 0.3);">
                    <i class="bi bi-stars me-1 text-warning"></i> AI Credit Bundles
                </span>
                <h2 class="h3 fw-extrabold text-white mb-1">Select an AI Generation Package</h2>
                <p class="text-slate-400 extra-small mx-auto mb-0" style="max-width: 520px;">
                    Tokens are deducted instantly from your central wallet balance upon purchase and never expire.
                </p>
            </div>

            <div class="row g-4 align-items-stretch">
                @foreach($packages as $key => $pack)
                    @php
                        $isPopular = ($pack['badge'] === 'Most Popular');
                    @endphp
                    <div class="col-12 col-md-4">
                        <div class="package-tier-card {{ $isPopular ? 'package-tier-popular' : '' }}">
                            
                            @if($pack['badge'])
                                <div class="package-ribbon">
                                    {{ $pack['badge'] }}
                                </div>
                            @endif

                            <div>
                                <!-- Package Title & Icon -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="metric-icon-box" style="width: 38px; height: 38px; background: rgba(124, 58, 237, 0.15); color: #C4B5FD; font-size: 1.15rem;">
                                        @if($key === 'starter')
                                            <i class="bi bi-lightning"></i>
                                        @elseif($key === 'creator')
                                            <i class="bi bi-lightning-charge-fill text-warning"></i>
                                        @else
                                            <i class="bi bi-gem text-info"></i>
                                        @endif
                                    </div>
                                    <h3 class="h5 fw-bold text-white mb-0">{{ $pack['name'] }}</h3>
                                </div>

                                <p class="text-slate-400 extra-small mb-4" style="min-height: 38px; line-height: 1.5;">
                                    {{ $pack['description'] }}
                                </p>

                                <!-- Pricing & Token Specs -->
                                <div class="p-3 rounded-4 mb-4" style="background: rgba(11, 17, 32, 0.6); border: 1px solid rgba(148, 163, 184, 0.12);">
                                    <div class="d-flex align-items-baseline gap-1 font-monospace mb-1">
                                        <span class="fs-4 text-emerald-400 fw-bold">৳</span>
                                        <span class="display-6 fw-extrabold text-white">{{ number_format($pack['price'], 0) }}</span>
                                        <span class="text-slate-400 extra-small">BDT</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between text-slate-300 small">
                                        <span class="fw-bold text-warning font-monospace">
                                            <i class="bi bi-lightning-charge-fill me-0.5"></i> {{ $pack['credits'] }} AI Credits
                                        </span>
                                        <span class="extra-small text-slate-400 font-monospace">
                                            (৳{{ $pack['rate'] }} / edit)
                                        </span>
                                    </div>
                                </div>

                                <!-- Features list -->
                                <ul class="list-unstyled d-flex flex-column gap-2.5 text-slate-300 extra-small mb-4">
                                    <li class="d-flex align-items-center gap-2">
                                        <i class="bi bi-check-circle-fill text-emerald-400"></i>
                                        <span>{{ $pack['credits'] }} High-Res 1920×1080 JPEG outputs</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-2">
                                        <i class="bi bi-check-circle-fill text-emerald-400"></i>
                                        <span>Multi-vibe ambient color grading engine</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-2">
                                        <i class="bi bi-check-circle-fill text-emerald-400"></i>
                                        <span>Full typography & geometry synthesis</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-2">
                                        <i class="bi bi-check-circle-fill text-emerald-400"></i>
                                        <span>Credits never expire & apply sitewide</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Purchase Action Form -->
                            @if(auth()->user()->isAdmin())
                                <button type="button" class="btn d-inline-flex align-items-center justify-content-center gap-2 w-100 rounded-pill py-2.5 fw-bold text-purple-300"
                                        style="background: rgba(139, 92, 246, 0.15); border: 1.5px solid rgba(139, 92, 246, 0.4); cursor: default;"
                                        disabled>
                                    <i class="bi bi-shield-check text-purple-400"></i>
                                    <span>Admin Unlimited</span>
                                </button>
                            @else
                                <form action="{{ route('wallet.buy-credits') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="package" value="{{ $key }}">
                                    <button type="submit" class="btn btn-buy-pack d-inline-flex align-items-center justify-content-center gap-2">
                                        <span>Purchase {{ $pack['credits'] }} Credits</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 3. ADD FUNDS / INSTANT RECHARGE SECTION                   -->
        <!-- ========================================================= -->
        <div id="depositSection" class="wallet-glass-card mb-5">
            <div class="row g-4 align-items-center">
                
                <div class="col-12 col-lg-5">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge px-3 py-1 rounded-pill extra-small fw-bold" style="background: rgba(16, 185, 129, 0.15); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.3);">
                            <i class="bi bi-shield-lock-fill me-1"></i> Instant Top-Up
                        </span>
                    </div>
                    <h3 class="h4 fw-extrabold text-white mb-2">Recharge Your Wallet</h3>
                    <p class="text-slate-400 small mb-4">
                        Add funds instantly using Bangladesh mobile financial services (bKash, Nagad) or bank cards. Balances update immediately with 0% gateway commission.
                    </p>

                    <div class="d-flex flex-column gap-2 text-slate-300 extra-small">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-patch-check-fill text-emerald-400"></i>
                            <span>Simulated secure sandbox processing</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-patch-check-fill text-emerald-400"></i>
                            <span>Instant balance update with automated receipt ledger</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-7">
                    <div class="p-4 rounded-4" style="background: rgba(11, 17, 32, 0.7); border: 1px solid rgba(148, 163, 184, 0.12);">
                        <form action="{{ route('wallet.deposit') }}" method="POST">
                            @csrf

                            <!-- Amount Input -->
                            <div class="mb-3">
                                <label for="depositAmountInput" class="form-label text-slate-300 small fw-bold text-uppercase tracking-wider">
                                    Recharge Amount (BDT) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-slate-900 border-slate-800 text-emerald-400 fw-bold font-monospace">৳</span>
                                    <input type="number" 
                                           id="depositAmountInput" 
                                           name="amount" 
                                           class="form-control form-control-wallet font-monospace fw-bold" 
                                           required 
                                           min="10" 
                                           max="50000" 
                                           step="10"
                                           value="250" 
                                           placeholder="e.g. 500">
                                </div>
                            </div>

                            <!-- Quick Preset Pills -->
                            <div class="mb-4">
                                <span class="extra-small text-slate-400 d-block mb-1.5 fw-semibold">Quick Amounts:</span>
                                <div class="d-flex flex-wrap gap-2" id="quickAmountsContainer">
                                    <button type="button" class="amount-preset-btn" data-amount="100" onclick="setDepositAmount(100)">+ ৳100</button>
                                    <button type="button" class="amount-preset-btn active" data-amount="250" onclick="setDepositAmount(250)">+ ৳250</button>
                                    <button type="button" class="amount-preset-btn" data-amount="500" onclick="setDepositAmount(500)">+ ৳500</button>
                                    <button type="button" class="amount-preset-btn" data-amount="1000" onclick="setDepositAmount(1000)">+ ৳1,000</button>
                                    <button type="button" class="amount-preset-btn" data-amount="2500" onclick="setDepositAmount(2500)">+ ৳2,500</button>
                                </div>
                            </div>

                            <!-- Payment Method Selector -->
                            <div class="mb-4">
                                <span class="extra-small text-slate-300 d-block mb-2 fw-bold text-uppercase tracking-wider">Choose Payment Method</span>
                                <div class="row g-2">
                                    <!-- bKash -->
                                    <div class="col-4">
                                        <label class="method-card-option method-bkash">
                                            <input type="radio" name="payment_method" value="bkash" checked>
                                            <div class="method-card-box text-center justify-content-center flex-column py-2.5">
                                                <i class="bi bi-phone-fill fs-5" style="color: #EC4899;"></i>
                                                <span class="extra-small fw-bold text-white">bKash</span>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Nagad -->
                                    <div class="col-4">
                                        <label class="method-card-option method-nagad">
                                            <input type="radio" name="payment_method" value="nagad">
                                            <div class="method-card-box text-center justify-content-center flex-column py-2.5">
                                                <i class="bi bi-cash-stack fs-5" style="color: #F97316;"></i>
                                                <span class="extra-small fw-bold text-white">Nagad</span>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Card -->
                                    <div class="col-4">
                                        <label class="method-card-option method-card">
                                            <input type="radio" name="payment_method" value="card">
                                            <div class="method-card-box text-center justify-content-center flex-column py-2.5">
                                                <i class="bi bi-credit-card-2-front-fill fs-5" style="color: #38BDF8;"></i>
                                                <span class="extra-small fw-bold text-white">Bank Card</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-emerald-recharge w-100 d-inline-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-check2-circle fs-5"></i>
                                    <span>Top Up Wallet Now</span>
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 4. TRANSACTION HISTORY LEDGER                             -->
        <!-- ========================================================= -->
        <div class="wallet-glass-card">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-slate-800">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-receipt-cutoff text-purple-400 fs-5"></i>
                    <h3 class="h5 fw-bold text-white mb-0">Transaction Ledger & History</h3>
                </div>
                <span class="badge bg-slate-800 text-slate-400 rounded-pill extra-small font-monospace">
                    {{ $transactions->total() }} Total Records
                </span>
            </div>

            @if($transactions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-wallet">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th class="text-end">Amount (৳)</th>
                                <th class="text-end">Credits</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $tx)
                                <tr>
                                    <!-- Date -->
                                    <td class="font-monospace text-slate-400 extra-small">
                                        {{ $tx->created_at->format('M d, Y') }}
                                        <span class="d-block text-slate-500" style="font-size: 0.725rem;">{{ $tx->created_at->format('h:i A') }}</span>
                                    </td>

                                    <!-- Type -->
                                    <td>
                                        @if($tx->type === 'credit_purchase')
                                            <span class="badge rounded-pill extra-small fw-semibold" style="background: rgba(124, 58, 237, 0.2); color: #C4B5FD; border: 1px solid rgba(124, 58, 237, 0.3);">
                                                <i class="bi bi-lightning-charge-fill me-1 text-warning"></i> Credit Purchase
                                            </span>
                                        @elseif($tx->type === 'deposit')
                                            <span class="badge rounded-pill extra-small fw-semibold" style="background: rgba(16, 185, 129, 0.2); color: #6EE7B7; border: 1px solid rgba(16, 185, 129, 0.3);">
                                                <i class="bi bi-arrow-down-circle-fill me-1 text-success"></i> Wallet Deposit
                                            </span>
                                        @elseif($tx->type === 'ai_generation_fee')
                                            <span class="badge rounded-pill extra-small fw-semibold" style="background: rgba(245, 158, 11, 0.2); color: #FCD34D; border: 1px solid rgba(245, 158, 11, 0.3);">
                                                <i class="bi bi-magic me-1"></i> AI Customizer Fee
                                            </span>
                                        @elseif($tx->type === 'template_purchase')
                                            <span class="badge rounded-pill extra-small fw-semibold" style="background: rgba(59, 130, 246, 0.2); color: #93C5FD; border: 1px solid rgba(59, 130, 246, 0.3);">
                                                <i class="bi bi-bag-check-fill me-1"></i> Template Purchase
                                            </span>
                                        @elseif($tx->type === 'credit')
                                            <span class="badge rounded-pill extra-small fw-semibold" style="background: rgba(16, 185, 129, 0.2); color: #6EE7B7; border: 1px solid rgba(16, 185, 129, 0.3);">
                                                <i class="bi bi-plus-circle me-1"></i> Credit
                                            </span>
                                        @elseif($tx->type === 'debit')
                                            <span class="badge rounded-pill extra-small fw-semibold" style="background: rgba(239, 68, 68, 0.2); color: #FCA5A5; border: 1px solid rgba(239, 68, 68, 0.3);">
                                                <i class="bi bi-dash-circle me-1"></i> Debit
                                            </span>
                                        @else
                                            <span class="badge bg-slate-800 text-slate-300 rounded-pill extra-small font-monospace">
                                                {{ ucfirst(str_replace('_', ' ', $tx->type)) }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Description -->
                                    <td>
                                        <div class="fw-semibold text-white small">{{ $tx->description }}</div>
                                        @if($tx->balance_after !== null)
                                            <div class="extra-small text-slate-500 font-monospace">
                                                Balance after: ৳{{ number_format($tx->balance_after, 2) }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Amount -->
                                    <td class="text-end font-monospace fw-bold">
                                        @if(in_array($tx->type, ['deposit', 'credit']))
                                            <span class="text-emerald-400">+৳{{ number_format($tx->amount, 2) }}</span>
                                        @elseif($tx->amount > 0)
                                            <span class="text-slate-300">-৳{{ number_format($tx->amount, 2) }}</span>
                                        @else
                                            <span class="text-slate-500">৳0.00</span>
                                        @endif
                                    </td>

                                    <!-- Credits -->
                                    <td class="text-end font-monospace fw-bold">
                                        @if(($tx->credits_transacted ?? 0) > 0)
                                            <span class="text-warning">+{{ $tx->credits_transacted }} ⚡</span>
                                        @elseif($tx->type === 'ai_generation_fee')
                                            <span class="text-red-400">-1 ⚡</span>
                                        @else
                                            <span class="text-slate-600">—</span>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td class="text-center">
                                        @if($tx->status === 'completed')
                                            <span class="badge bg-success bg-opacity-20 text-success rounded-pill extra-small font-monospace border border-success border-opacity-25 px-2.5 py-1">
                                                Completed
                                            </span>
                                        @elseif($tx->status === 'pending')
                                            <span class="badge bg-warning bg-opacity-20 text-warning rounded-pill extra-small font-monospace border border-warning border-opacity-25 px-2.5 py-1">
                                                Pending
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-20 text-danger rounded-pill extra-small font-monospace border border-danger border-opacity-25 px-2.5 py-1">
                                                {{ ucfirst($tx->status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($transactions->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $transactions->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div style="width: 56px; height: 56px; border-radius: 1rem; background: rgba(148, 163, 184, 0.1); color: #94A3B8; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                        <i class="bi bi-clock-history fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-white mb-1">No Transactions Recorded Yet</h5>
                    <p class="text-slate-400 extra-small mb-3">
                        Recharge funds or purchase an AI credit bundle to start building your ledger history.
                    </p>
                    <a href="#depositSection" class="btn btn-emerald-recharge btn-sm px-3.5 py-1.5 rounded-pill">
                        <i class="bi bi-plus-circle me-1"></i> Add Funds
                    </a>
                </div>
            @endif

        </div>

    </div>
</div>

<script>
    function updateQuickAmountActiveState(currentVal) {
        const numericVal = parseInt(currentVal, 10);
        document.querySelectorAll('.amount-preset-btn').forEach(btn => {
            const btnAmount = parseInt(btn.getAttribute('data-amount'), 10);
            btn.classList.toggle('active', !isNaN(numericVal) && btnAmount === numericVal);
        });
    }

    function setDepositAmount(amount) {
        const input = document.getElementById('depositAmountInput');
        if (input) {
            input.value = amount;
            input.focus();
        }
        updateQuickAmountActiveState(amount);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('depositAmountInput');
        if (input) {
            updateQuickAmountActiveState(input.value);
            input.addEventListener('input', function () {
                updateQuickAmountActiveState(this.value);
            });
        }
    });
</script>
@endsection
