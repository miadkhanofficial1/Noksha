@extends('layouts.app')

@section('title', 'Central BDT Financial Hub & Wallet - Noksha')

@section('content')
<style>
    /* Modern Light Theme Wallet & Financial Hub Styling */
    .wallet-wrapper {
        background-color: #ffffff; /* pure white canvas */
        color: #121826; /* charcoal black */
        min-height: calc(100vh - 72px);
        position: relative;
    }

    .wallet-hero-glow {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 850px;
        height: 380px;
        background: radial-gradient(circle, rgba(32, 22, 92, 0.04) 0%, rgba(244, 116, 2, 0.03) 45%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }

    /* Glass Cards */
    .wallet-glass-card {
        background: #ffffff;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border-radius: 1.25rem;
        padding: 1.75rem;
        transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
    }

    .wallet-glass-card:hover {
        border-color: #cbd5e1;
    }

    /* Primary Balance Hero Card */
    .balance-hero-card {
        background: linear-gradient(135deg, #20165c 0%, #2c1e7a 100%);
        border: 1.5px solid #20165c;
        border-radius: 1.5rem;
        padding: 2.25rem 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(32, 22, 92, 0.3); color: #ffffff;
    }

    .balance-hero-glow-accent {
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .metric-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    /* Dark Form Inputs */
    .form-control-wallet {
        background-color: #ffffff;
        border: 1.5px solid #cbd5e1;
        color: #121826 !important;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control-wallet:focus {
        background-color: #f8fafc;
        border-color: #20165c;
        box-shadow: 0 0 0 3px rgba(32, 22, 92, 0.15);
        outline: none;
    }

    /* Hide native number input spinners */
    input[type="number"].form-control-wallet::-webkit-outer-spin-button,
    input[type="number"].form-control-wallet::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"].form-control-wallet {
        -moz-appearance: textfield;
        appearance: textfield;
    }

    /* Preset Quick Amount Pills */
    .amount-preset-btn {
        background: #f8fafc !important;
        border: 1.5px solid #e2e8f0 !important;
        color: #121826 !important;
        border-radius: 0.65rem;
        padding: 0.5rem 1.15rem;
        font-size: 0.95rem;
        font-weight: 700;
        font-family: inherit;
        text-decoration: none !important;
        transition: all 0.2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1.2;
    }

    .amount-preset-btn:hover, .amount-preset-btn:focus, .amount-preset-btn.active {
        background: rgba(16, 185, 129, 0.25) !important;
        border-color: #10B981 !important;
        color: #34D399 !important;
        text-decoration: none !important;
        transform: translateY(-1px);
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
        border: 1.5px solid #cbd5e1;
        border-radius: 0.85rem;
        padding: 0.85rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.25s ease;
    }

    /* bKash */
    .method-card-option.method-bkash:hover .method-card-box {
        border-color: rgba(236, 72, 153, 0.5);
        background: #0F172A;
    }
    .method-card-option.method-bkash input[type="radio"]:checked + .method-card-box {
        border-color: #EC4899 !important;
        background: rgba(236, 72, 153, 0.14) !important;
        box-shadow: 0 0 0 2px rgba(236, 72, 153, 0.3), 0 0 20px rgba(236, 72, 153, 0.25) !important;
    }

    /* Nagad */
    .method-card-option.method-nagad:hover .method-card-box {
        border-color: rgba(249, 115, 22, 0.5);
        background: #0F172A;
    }
    .method-card-option.method-nagad input[type="radio"]:checked + .method-card-box {
        border-color: #F97316 !important;
        background: rgba(249, 115, 22, 0.14) !important;
        box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.3), 0 0 20px rgba(249, 115, 22, 0.25) !important;
    }

    /* Card */
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
        color: #121826;
        margin-bottom: 0;
    }

    .table-wallet th {
        background: transparent;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.85rem 1rem;
    }

    .table-wallet td {
        background: transparent;
        color: #1e293b;
        font-size: 0.875rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        padding: 1rem;
    }

    .table-wallet tr:hover td {
        background: #f8fafc;
    }

    /* Custom Gradient Buttons */
    .btn-emerald-recharge {
        background: linear-gradient(135deg, #f47402 0%, #d96602 100%);
        color: #FFFFFF !important;
        border: none;
        border-radius: 0.75rem;
        padding: 0.75rem 1.75rem;
        font-weight: 700;
        font-size: 0.95rem;
        box-shadow: 0 4px 14px rgba(244, 116, 2, 0.3);
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .btn-emerald-recharge:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5);
    }

    .btn-withdraw-payout {
        background: linear-gradient(135deg, #20165c 0%, #181045 100%);
        color: #FFFFFF !important;
        border: none;
        border-radius: 0.75rem;
        padding: 0.75rem 1.75rem;
        font-weight: 700;
        font-size: 0.95rem;
        box-shadow: 0 4px 14px rgba(32, 22, 92, 0.3);
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .btn-withdraw-payout:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5);
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
        <div class="mb-4">
            <h1 class="display-6 fw-extrabold text-[#121826] mb-1">Central BDT Wallet</h1>
            <p class="text-slate-400 small mb-0">
                Instant deposits, creator payouts, and verified escrow transactions.
            </p>
        </div>

        <!-- Flash Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-lg py-3 px-4 mb-4 d-flex align-items-center gap-3"
                 style="background-color: rgba(16, 185, 129, 0.18); border: 1px solid rgba(16, 185, 129, 0.35) !important;" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                <div class="fw-semibold text-emerald-300 small">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-lg py-3 px-4 mb-4 d-flex align-items-center gap-3"
                 style="background-color: rgba(239, 68, 68, 0.18); border: 1px solid rgba(239, 68, 68, 0.35) !important;" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                <div class="fw-semibold text-red-300 small">{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show rounded-4 border-0 shadow-lg py-3 px-4 mb-4 d-flex align-items-center gap-3"
                 style="background-color: rgba(99, 102, 241, 0.18); border: 1px solid rgba(99, 102, 241, 0.35) !important;" role="alert">
                <i class="bi bi-info-circle-fill text-info fs-4"></i>
                <div class="fw-semibold text-indigo-300 small">{{ session('info') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- ========================================================= -->
        <!-- 1. STREAMLINED AVAILABLE WALLET BALANCE CARD             -->
        <!-- ========================================================= -->
        <div class="balance-hero-card mb-4">
            <div class="balance-hero-glow-accent"></div>

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-4 position-relative z-1">
                <!-- Balance & Status -->
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="extra-small fw-bold text-uppercase tracking-wider text-slate-400">Available Wallet Balance</span>
                        <span class="badge px-2.5 py-0.5 rounded-pill extra-small font-monospace" style="background: rgba(16, 185, 129, 0.2); color: #6EE7B7; border: 1px solid rgba(16, 185, 129, 0.3);">
                            <i class="bi bi-shield-check me-1"></i> Active Escrow Wallet
                        </span>
                        @if($canWithdraw && $earningsBalance > 0)
                            <span class="badge px-2.5 py-0.5 rounded-pill extra-small font-monospace" style="background: rgba(99, 102, 241, 0.2); color: #A5B4FC; border: 1px solid rgba(99, 102, 241, 0.3);">
                                <i class="bi bi-stars me-1"></i> Creator Royalties: ৳{{ number_format($earningsBalance, 2) }}
                            </span>
                        @endif
                    </div>

                    <!-- Prominent Balance Amount -->
                    <div class="d-flex align-items-baseline gap-2 font-monospace mb-0">
                        <span class="display-4 fw-extrabold text-emerald-400">৳</span>
                        <span class="display-4 fw-extrabold text-white">{{ number_format($wallet->balance ?? auth()->user()->balance ?? 0, 2) }}</span>
                        <span class="fs-4 fw-bold text-slate-400">BDT</span>
                    </div>
                </div>

                <!-- Primary Action Buttons -->
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <button type="button" class="btn btn-emerald-recharge d-inline-flex align-items-center justify-content-center gap-2" onclick="openDepositModal()">
                        <i class="bi bi-plus-circle-fill fs-5"></i>
                        <span>+ Add Funds (bKash / Nagad)</span>
                    </button>

                    @if($canWithdraw)
                        <button type="button" class="btn btn-withdraw-payout d-inline-flex align-items-center justify-content-center gap-2" onclick="openWithdrawalModal()">
                            <i class="bi bi-arrow-up-circle-fill fs-5"></i>
                            <span>⇪ Request Payout / Withdraw</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 2. QUICK DEPOSIT BAR                                      -->
        <!-- ========================================================= -->
        <div class="wallet-glass-card mb-4 py-3 px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <span class="fw-bold text-[#121826] small text-uppercase tracking-wider">Quick Deposit:</span>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="amount-preset-btn" onclick="startDepositWithAmount(500)">+৳500</button>
                        <button type="button" class="amount-preset-btn" onclick="startDepositWithAmount(1000)">+৳1,000</button>
                        <button type="button" class="amount-preset-btn" onclick="startDepositWithAmount(2000)">+৳2,000</button>
                    </div>
                </div>

                <div class="input-group" style="max-width: 280px;">
                    <span class="input-group-text bg-slate-50 border-slate-200 text-[#f47402] fw-bold font-monospace">৳</span>
                    <input type="number" 
                           id="quickCustomAmount" 
                           class="form-control form-control-wallet font-monospace fw-bold" 
                           placeholder="Custom amount..." 
                           min="10" 
                           step="10">
                    <button type="button" class="btn btn-emerald-recharge px-3" onclick="startDepositFromCustomInput()" title="Continue to deposit">
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 3. FINANCIAL TRANSACTION LEDGER                           -->
        <!-- ========================================================= -->
        <div class="wallet-glass-card">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-slate-800">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-receipt-cutoff text-emerald-400 fs-5"></i>
                    <h3 class="h5 fw-bold text-white mb-0">Financial Transaction Ledger</h3>
                </div>
                <span class="badge bg-slate-100 text-slate-600 border border-slate-200 rounded-pill extra-small font-monospace">
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
                                <th>Reference / Order ID</th>
                                <th>Description</th>
                                <th class="text-end">Amount</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $tx)
                                @php
                                    // Extract TrxID or Order ID from description or reference
                                    $referenceDisplay = '—';
                                    if ($tx->reference_id) {
                                        $referenceDisplay = '#' . $tx->reference_id;
                                    } elseif (preg_match('/TrxID:\s*([A-Za-z0-9\-]+)/i', $tx->description, $matches)) {
                                        $referenceDisplay = $matches[1];
                                    } elseif (preg_match('/(NOK-[A-Za-z0-9\-]+)/i', $tx->description, $matches)) {
                                        $referenceDisplay = $matches[1];
                                    } elseif (preg_match('/(WTH-[0-9]+)/i', $tx->description, $matches)) {
                                        $referenceDisplay = '#' . $matches[1];
                                    } else {
                                        $referenceDisplay = '#TX-' . $tx->id;
                                    }

                                    // Determine Credit vs Debit
                                    $isCredit = in_array($tx->type, ['deposit', 'template_sale', 'earning', 'credit']);
                                @endphp
                                <tr>
                                    <!-- Date & Time -->
                                    <td class="font-monospace text-slate-400 extra-small">
                                        <div class="text-slate-200 fw-semibold">{{ $tx->created_at->format('M d, Y') }}</div>
                                        <span class="text-slate-500" style="font-size: 0.725rem;">{{ $tx->created_at->format('h:i A') }}</span>
                                    </td>

                                    <!-- Type -->
                                    <td>
                                        @if($tx->type === 'deposit')
                                            <span class="badge rounded-pill extra-small fw-semibold" style="background: rgba(16, 185, 129, 0.18); color: #6EE7B7; border: 1px solid rgba(16, 185, 129, 0.3);">
                                                <i class="bi bi-arrow-down-circle-fill me-1 text-success"></i> Deposit
                                            </span>
                                        @elseif($tx->type === 'template_purchase')
                                            <span class="badge rounded-pill extra-small fw-semibold" style="background: rgba(59, 130, 246, 0.18); color: #93C5FD; border: 1px solid rgba(59, 130, 246, 0.3);">
                                                <i class="bi bi-bag-check-fill me-1 text-primary"></i> Template Purchase
                                            </span>
                                        @elseif(in_array($tx->type, ['template_sale', 'earning']))
                                            <span class="badge rounded-pill extra-small fw-semibold" style="background: rgba(20, 184, 166, 0.18); color: #5EEAD4; border: 1px solid rgba(20, 184, 166, 0.3);">
                                                <i class="bi bi-graph-up-arrow me-1 text-teal-400"></i> Creator Earning
                                            </span>
                                        @elseif(in_array($tx->type, ['withdrawal', 'payout']))
                                            <span class="badge rounded-pill extra-small fw-semibold" style="background: rgba(245, 158, 11, 0.18); color: #FCD34D; border: 1px solid rgba(245, 158, 11, 0.3);">
                                                <i class="bi bi-arrow-up-circle-fill me-1 text-warning"></i> Payout / Withdraw
                                            </span>
                                        @elseif(in_array($tx->type, ['service_escrow_deposit', 'escrow_funded']))
                                            <span class="badge rounded-pill extra-small fw-semibold" style="background: rgba(124, 58, 237, 0.18); color: #C4B5FD; border: 1px solid rgba(124, 58, 237, 0.3);">
                                                <i class="bi bi-shield-lock-fill me-1 text-purple-400"></i> Service Escrow
                                            </span>
                                        @elseif($tx->type === 'credit')
                                            <span class="badge rounded-pill extra-small fw-semibold" style="background: rgba(16, 185, 129, 0.18); color: #6EE7B7; border: 1px solid rgba(16, 185, 129, 0.3);">
                                                <i class="bi bi-plus-circle me-1 text-success"></i> Credit
                                            </span>
                                        @elseif($tx->type === 'debit')
                                            <span class="badge rounded-pill extra-small fw-semibold" style="background: rgba(239, 68, 68, 0.18); color: #FCA5A5; border: 1px solid rgba(239, 68, 68, 0.3);">
                                                <i class="bi bi-dash-circle me-1 text-danger"></i> Debit
                                            </span>
                                        @else
                                            <span class="badge bg-slate-100 text-slate-600 border border-slate-200 rounded-pill extra-small font-monospace">
                                                {{ ucfirst(str_replace('_', ' ', $tx->type)) }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Reference / Order ID -->
                                    <td class="font-monospace extra-small">
                                        <span class="text-slate-700 px-2 py-1 rounded bg-slate-100 border border-slate-200">
                                            {{ $referenceDisplay }}
                                        </span>
                                    </td>

                                    <!-- Description -->
                                    <td>
                                        <div class="fw-semibold text-white small">{{ $tx->description }}</div>
                                        @if($tx->balance_after !== null)
                                            <div class="extra-small text-slate-500 font-monospace">
                                                Ledger balance after: ৳{{ number_format($tx->balance_after, 2) }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Amount -->
                                    <td class="text-end font-monospace fw-bold">
                                        @if($isCredit)
                                            <span class="text-emerald-400 fs-6">+৳{{ number_format($tx->amount, 2) }}</span>
                                        @elseif($tx->amount > 0)
                                            <span class="text-rose-400 fs-6">-৳{{ number_format($tx->amount, 2) }}</span>
                                        @else
                                            <span class="text-slate-500 fs-6">৳0.00</span>
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
                    <div class="mt-4 pt-3 border-top border-slate-800 d-flex justify-content-center">
                        {{ $transactions->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div style="width: 56px; height: 56px; border-radius: 1rem; background: rgba(148, 163, 184, 0.1); color: #64748b; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                        <i class="bi bi-clock-history fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-white mb-1">No Financial Transactions Recorded Yet</h5>
                    <p class="text-slate-400 extra-small mb-3">
                        Recharge funds to start transacting on the Noksha creator marketplace.
                    </p>
                    <button type="button" class="btn btn-emerald-recharge btn-sm px-3.5 py-1.5 rounded-pill" onclick="openDepositModal()">
                        <i class="bi bi-plus-circle me-1"></i> Add Funds Now
                    </button>
                </div>
            @endif

        </div>

    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL: MANUAL DEPOSIT (bKash / Nagad / Bank)             -->
<!-- ========================================================= -->
<div class="modal fade" id="manualDepositModal" tabindex="-1" aria-labelledby="manualDepositModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 540px;">
        <div class="modal-content border-0 shadow-lg" style="background: #ffffff; border: 1px solid #e2e8f0 !important; border-radius: 1.5rem; color: #121826;">
            <div class="modal-header border-bottom border-slate-200 px-4 pt-4 pb-3 bg-slate-50">
                <div class="d-flex align-items-center gap-2">
                    <div class="metric-icon-box" style="width: 40px; height: 40px; background: rgba(16, 185, 129, 0.15); color: #34D399; font-size: 1.15rem;">
                        <i class="bi bi-phone-fill"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-extrabold text-[#121826] mb-0" id="manualDepositModalLabel">Deposit Funds via Mobile Banking</h5>
                        <span class="extra-small text-slate-400">bKash &bull; Nagad &bull; Bank Card &bull; Instant Verification</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Official Payment Numbers Box -->
                <div class="p-3.5 rounded-4 mb-4" style="background: #f8fafc; border: 1.5px dashed #f47402;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="extra-small fw-bold text-uppercase tracking-wider text-emerald-400">
                            <i class="bi bi-shield-check me-1"></i> Official Noksha Deposit Accounts
                        </span>
                        <span class="badge bg-emerald-900 text-emerald-300 extra-small">24/7 Monitored</span>
                    </div>

                    <div class="row g-2 mb-2 font-monospace extra-small">
                        <!-- bKash -->
                        <div class="col-6">
                            <div class="p-2 rounded-3 bg-white border border-slate-200">
                                <div class="text-pink-400 fw-bold d-flex align-items-center justify-content-between">
                                    <span>bKash Personal:</span>
                                </div>
                                <div class="text-[#121826] fw-bold fs-6 mt-0.5 d-flex align-items-center justify-content-between">
                                    <span>01700-123456</span>
                                    <button type="button" class="btn btn-link p-0 text-slate-400 hover-text-white" onclick="copyNumber('01700123456', this)" title="Copy">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Nagad -->
                        <div class="col-6">
                            <div class="p-2 rounded-3 bg-white border border-slate-200">
                                <div class="text-orange-400 fw-bold d-flex align-items-center justify-content-between">
                                    <span>Nagad Personal:</span>
                                </div>
                                <div class="text-[#121826] fw-bold fs-6 mt-0.5 d-flex align-items-center justify-content-between">
                                    <span>01800-123456</span>
                                    <button type="button" class="btn btn-link p-0 text-slate-400 hover-text-white" onclick="copyNumber('01800123456', this)" title="Copy">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="text-slate-400 extra-small mb-0" style="line-height: 1.4;">
                        Send Money (Personal) to either official number above, then submit your sender phone and transaction ID (TrxID) below to credit your balance.
                    </p>
                </div>

                <!-- Deposit Form -->
                <form action="{{ route('wallet.deposit') }}" method="POST" id="manualDepositForm">
                    @csrf

                    <!-- Payment Method Selector -->
                    <div class="mb-3">
                        <label class="form-label text-slate-600 extra-small fw-bold text-uppercase tracking-wider">
                            Select Payment Method <span class="text-danger">*</span>
                        </label>
                        <div class="row g-2">
                            <div class="col-4">
                                <label class="method-card-option method-bkash w-100">
                                    <input type="radio" name="payment_method" value="bkash" checked>
                                    <div class="method-card-box text-center justify-content-center flex-column py-2.5">
                                        <i class="bi bi-phone-fill fs-5" style="color: #EC4899;"></i>
                                        <span class="extra-small fw-bold text-slate-800">bKash</span>
                                    </div>
                                </label>
                            </div>

                            <div class="col-4">
                                <label class="method-card-option method-nagad w-100">
                                    <input type="radio" name="payment_method" value="nagad">
                                    <div class="method-card-box text-center justify-content-center flex-column py-2.5">
                                        <i class="bi bi-cash-stack fs-5" style="color: #F97316;"></i>
                                        <span class="extra-small fw-bold text-slate-800">Nagad</span>
                                    </div>
                                </label>
                            </div>

                            <div class="col-4">
                                <label class="method-card-option method-card w-100">
                                    <input type="radio" name="payment_method" value="card">
                                    <div class="method-card-box text-center justify-content-center flex-column py-2.5">
                                        <i class="bi bi-credit-card-2-front-fill fs-5" style="color: #38BDF8;"></i>
                                        <span class="extra-small fw-bold text-slate-800">Bank Card</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Deposit Amount -->
                    <div class="mb-3">
                        <label for="depositAmountInput" class="form-label text-slate-600 extra-small fw-bold text-uppercase tracking-wider">
                            Deposit Amount (BDT) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 border-slate-200 text-[#f47402] fw-bold font-monospace">৳</span>
                            <input type="number" 
                                   id="depositAmountInput" 
                                   name="amount" 
                                   class="form-control form-control-wallet font-monospace fw-bold" 
                                   required 
                                   min="10" 
                                   max="100000" 
                                   step="10" 
                                   value="500" 
                                   placeholder="e.g. 500">
                        </div>
                    </div>

                    <!-- Sender Phone Number -->
                    <div class="mb-3">
                        <label for="senderNumberInput" class="form-label text-slate-600 extra-small fw-bold text-uppercase tracking-wider">
                            Sender Phone Number <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 border-slate-200 text-slate-600 font-monospace">
                                <i class="bi bi-telephone"></i>
                            </span>
                            <input type="text" 
                                   id="senderNumberInput" 
                                   name="sender_number" 
                                   class="form-control form-control-wallet font-monospace" 
                                   required 
                                   placeholder="e.g. 01712345678">
                        </div>
                    </div>

                    <!-- Transaction ID (TrxID) -->
                    <div class="mb-4">
                        <label for="trxIdInput" class="form-label text-slate-600 extra-small fw-bold text-uppercase tracking-wider">
                            Transaction ID (TrxID) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 border-slate-200 text-slate-600 font-monospace">#</span>
                            <input type="text" 
                                   id="trxIdInput" 
                                   name="transaction_id" 
                                   class="form-control form-control-wallet font-monospace text-uppercase" 
                                   required 
                                   placeholder="e.g. 9J84KL2M">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-emerald-recharge w-100 d-inline-flex align-items-center justify-content-center gap-2 py-2.5">
                            <i class="bi bi-check2-circle fs-5"></i>
                            <span>Submit Deposit Confirmation</span>
                        </button>
                        <button type="button" class="btn btn-outline-slate rounded-pill py-2 text-slate-400 extra-small" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL: CREATOR WITHDRAWAL / PAYOUT                        -->
<!-- ========================================================= -->
@if($canWithdraw)
<div class="modal fade" id="creatorWithdrawalModal" tabindex="-1" aria-labelledby="creatorWithdrawalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content border-0 shadow-lg" style="background: #ffffff; border: 1px solid #e2e8f0 !important; border-radius: 1.5rem; color: #121826;">
            <div class="modal-header border-bottom border-slate-200 px-4 pt-4 pb-3 bg-slate-50">
                <div class="d-flex align-items-center gap-2">
                    <div class="metric-icon-box" style="width: 40px; height: 40px; background: rgba(99, 102, 241, 0.15); color: #A5B4FC; font-size: 1.15rem;">
                        <i class="bi bi-arrow-up-circle-fill"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-extrabold text-[#121826] mb-0" id="creatorWithdrawalModalLabel">Request Creator Payout</h5>
                        <span class="extra-small text-slate-400">Withdraw your design royalties directly to mobile banking or bank account</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Creator Balance Summary -->
                <div class="p-3.5 rounded-4 mb-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="extra-small text-slate-400 fw-bold text-uppercase tracking-wider">Available Royalty Earnings</span>
                        <span class="badge bg-indigo-900 text-indigo-300 extra-small">Minimum ৳500.00</span>
                    </div>
                    <div class="font-monospace fw-bold text-[#121826] fs-4">
                        ৳{{ number_format($earningsBalance > 0 ? $earningsBalance : ($wallet->balance ?? 0), 2) }} BDT
                    </div>
                    <div class="extra-small text-slate-400 mt-1">
                        Payouts are processed within 24–48 business hours with 0% platform withdrawal fee.
                    </div>
                </div>

                <form action="{{ route('wallet.withdraw') }}" method="POST">
                    @csrf

                    <!-- Withdrawal Amount -->
                    <div class="mb-3">
                        <label for="withdrawAmount" class="form-label text-slate-600 extra-small fw-bold text-uppercase tracking-wider">
                            Withdrawal Amount (BDT) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 border-slate-200 text-[#20165c] fw-bold font-monospace">৳</span>
                            <input type="number" 
                                   id="withdrawAmount" 
                                   name="amount" 
                                   class="form-control form-control-wallet font-monospace fw-bold" 
                                   required 
                                   min="500" 
                                   step="10" 
                                   value="{{ max(500, min(1000, $earningsBalance > 0 ? $earningsBalance : ($wallet->balance ?? 500))) }}" 
                                   placeholder="Minimum ৳500">
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <label for="withdrawMethod" class="form-label text-slate-600 extra-small fw-bold text-uppercase tracking-wider">
                            Payout Method <span class="text-danger">*</span>
                        </label>
                        <select id="withdrawMethod" name="payment_method" class="form-select form-control-wallet" required>
                            <option value="bkash">bKash Personal</option>
                            <option value="nagad">Nagad Personal</option>
                            <option value="bank">Bank Transfer (Any BD Bank)</option>
                        </select>
                    </div>

                    <!-- Account Details / Phone Number -->
                    <div class="mb-3">
                        <label for="withdrawAccountDetails" class="form-label text-slate-600 extra-small fw-bold text-uppercase tracking-wider">
                            Account Number / Bank Details <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               id="withdrawAccountDetails" 
                               name="account_details" 
                               class="form-control form-control-wallet font-monospace" 
                               required 
                               placeholder="e.g. 01712345678 or Bank Name, Branch, A/C No">
                    </div>

                    <!-- Optional Note -->
                    <div class="mb-4">
                        <label for="withdrawNote" class="form-label text-slate-600 extra-small fw-bold text-uppercase tracking-wider">
                            Special Instructions (Optional)
                        </label>
                        <textarea id="withdrawNote" 
                                  name="note" 
                                  class="form-control form-control-wallet" 
                                  rows="2" 
                                  placeholder="Routing number, preferred payout schedule, or account holder name..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-withdraw-payout w-100 d-inline-flex align-items-center justify-content-center gap-2 py-2.5">
                            <i class="bi bi-shield-check fs-5"></i>
                            <span>Confirm & Submit Payout Request</span>
                        </button>
                        <button type="button" class="btn btn-outline-slate rounded-pill py-2 text-slate-400 extra-small" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    function openDepositModal() {
        const modalEl = document.getElementById('manualDepositModal');
        if (modalEl) {
            if (window.bootstrap && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            } else {
                modalEl.classList.add('show');
                modalEl.style.display = 'block';
            }
        }
    }

    function openWithdrawalModal() {
        const modalEl = document.getElementById('creatorWithdrawalModal');
        if (modalEl) {
            if (window.bootstrap && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            } else {
                modalEl.classList.add('show');
                modalEl.style.display = 'block';
            }
        }
    }

    function startDepositWithAmount(amount) {
        const depositInput = document.getElementById('depositAmountInput');
        if (depositInput) {
            depositInput.value = amount;
        }
        openDepositModal();
    }

    function startDepositFromCustomInput() {
        const customInput = document.getElementById('quickCustomAmount');
        const customVal = customInput ? parseFloat(customInput.value) : 0;
        if (customVal && customVal >= 10) {
            startDepositWithAmount(customVal);
        } else {
            openDepositModal();
        }
    }

    function copyNumber(num, btn) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(num).then(() => {
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check text-success"></i>';
                setTimeout(() => { btn.innerHTML = original; }, 1800);
            });
        }
    }
</script>
@endsection