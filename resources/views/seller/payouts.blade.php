@extends('layouts.app')

@section('title', 'Seller Earnings & Payouts - Noksha')

@section('content')
<style>
    .payouts-page-wrapper {
        background-color: #020617; /* slate-950 */
        color: #F8FAFC; /* slate-50 */
        min-height: calc(100vh - 72px);
        position: relative;
    }

    .payouts-glow {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 1000px;
        height: 420px;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, rgba(124, 58, 237, 0.08) 45%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }

    .payout-card {
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(148, 163, 184, 0.14);
        border-radius: 1.25rem;
        padding: 1.5rem;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .payout-card:hover {
        border-color: rgba(148, 163, 184, 0.25);
    }

    .stat-metric-card {
        background: rgba(15, 23, 42, 0.65);
        border: 1px solid rgba(148, 163, 184, 0.12);
        border-radius: 1.25rem;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .stat-metric-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: currentColor;
        opacity: 0.35;
    }

    .method-radio-box {
        position: relative;
        cursor: pointer;
        display: block;
        margin-bottom: 0;
    }

    .method-radio-box input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .method-card-inner {
        border: 1.5px solid #1E293B;
        background: #0B1120;
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        transition: all 0.2s ease;
    }

    .method-radio-box:hover .method-card-inner {
        border-color: rgba(16, 185, 129, 0.45);
        background: #0F172A;
    }

    .method-radio-box input[type="radio"]:checked + .method-card-inner {
        border-color: #10B981;
        background: rgba(16, 185, 129, 0.12);
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25);
    }

    .form-control-payout {
        background-color: #0B1120;
        border: 1.5px solid #1E293B;
        color: #F8FAFC !important;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control-payout:focus {
        background-color: #0F172A;
        border-color: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        outline: none;
    }

    .form-control-payout::placeholder {
        color: #475569;
    }

    .btn-withdraw-emerald {
        background: linear-gradient(135deg, #059669 0%, #10B981 50%, #14B8A6 100%);
        color: #FFFFFF !important;
        border: none;
        border-radius: 0.75rem;
        padding: 0.85rem 1.75rem;
        font-weight: 700;
        font-size: 1rem;
        box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);
        transition: all 0.25s ease;
    }

    .btn-withdraw-emerald:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.6);
    }

    .btn-outline-slate {
        background: rgba(30, 41, 59, 0.6);
        color: #94A3B8 !important;
        border: 1px solid #334155;
        border-radius: 0.75rem;
        padding: 0.6rem 1rem;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .btn-outline-slate:hover {
        background: #1E293B;
        color: #F8FAFC !important;
        border-color: #64748B;
    }

    .amount-preset-chip {
        cursor: pointer;
        padding: 0.4rem 0.85rem;
        border-radius: 50rem;
        font-size: 0.8rem;
        font-weight: 700;
        background: #0B1120;
        border: 1px solid #1E293B;
        color: #CBD5E1;
        transition: all 0.2s ease;
    }

    .amount-preset-chip:hover {
        border-color: #10B981;
        color: #34D399;
        background: rgba(16, 185, 129, 0.1);
    }

    .table-ledger {
        --bs-table-bg: transparent;
        --bs-table-color: #CBD5E1;
        --bs-table-border-color: rgba(148, 163, 184, 0.12);
        margin-bottom: 0;
    }

    .table-ledger th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94A3B8;
        font-weight: 700;
        padding: 1rem 1.25rem;
        border-bottom: 1.5px solid rgba(148, 163, 184, 0.18);
    }

    .table-ledger td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        font-size: 0.875rem;
    }

    .table-ledger tr:hover td {
        background-color: rgba(30, 41, 59, 0.35);
    }
</style>

<div class="payouts-page-wrapper py-5 position-relative">
    <div class="payouts-glow"></div>

    <div class="container position-relative z-1">

        <!-- ========================================================= -->
        <!-- TOP BREADCRUMB & HEADER                                   -->
        <!-- ========================================================= -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 pb-2 border-bottom border-slate-800">
            <div>
                <nav aria-label="breadcrumb" class="mb-1">
                    <ol class="breadcrumb extra-small font-monospace mb-0 text-slate-400">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-slate-400 text-decoration-none hover-underline">Noksha</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard', ['mode' => 'seller']) }}" class="text-slate-400 text-decoration-none hover-underline">Creator Hub</a></li>
                        <li class="breadcrumb-item active text-emerald-400" aria-current="page">Earnings & Payouts</li>
                    </ol>
                </nav>
                <div class="d-flex align-items-center gap-2.5">
                    <div style="width: 40px; height: 40px; border-radius: 0.75rem; background: rgba(16, 185, 129, 0.18); color: #34D399; display: inline-flex; align-items: center; justify-content: center; border: 1px solid rgba(16, 185, 129, 0.35);">
                        <i class="bi bi-cash-stack fs-5"></i>
                    </div>
                    <div>
                        <h1 class="h4 fw-extrabold text-white mb-0">Seller Earnings & Payouts</h1>
                        <p class="text-slate-400 extra-small mb-0">Manage your template royalty revenues, cash balances, and disburse instant withdrawals.</p>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('wallet.index') }}" class="btn btn-outline-slate d-inline-flex align-items-center gap-1.5" title="View central wallet ledger">
                    <i class="bi bi-wallet2 text-purple-400"></i>
                    <span>Central Wallet</span>
                </a>
                <a href="{{ route('dashboard', ['mode' => 'seller', 'tab' => 'designs']) }}" class="btn btn-outline-slate d-inline-flex align-items-center gap-1.5" title="Manage your uploaded templates">
                    <i class="bi bi-grid-fill text-emerald-400"></i>
                    <span>My Designs</span>
                </a>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- TOP 4 FINANCIAL METRIC CARDS                              -->
        <!-- ========================================================= -->
        <div class="row g-3 mb-4">
            
            <!-- Card 1: Available Balance -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-metric-card text-emerald-400 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="extra-small font-monospace text-slate-400 text-uppercase fw-bold">Available to Cashout</span>
                        <div class="p-1.5 rounded-3 bg-emerald-500 bg-opacity-15 text-emerald-400 border border-emerald-500 border-opacity-30">
                            <i class="bi bi-wallet-fill"></i>
                        </div>
                    </div>
                    <div class="h3 fw-extrabold text-white mb-1">
                        ৳ {{ number_format($availableBalance, 2) }}
                    </div>
                    <div class="extra-small text-slate-400 d-flex align-items-center gap-1">
                        <i class="bi bi-shield-check text-emerald-400"></i>
                        <span>Immediate disbursement ready</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Gross Sales -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-metric-card text-purple-400 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="extra-small font-monospace text-slate-400 text-uppercase fw-bold">Lifetime Template Sales</span>
                        <div class="p-1.5 rounded-3 bg-purple-500 bg-opacity-15 text-purple-400 border border-purple-500 border-opacity-30">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                    <div class="h3 fw-extrabold text-white mb-1">
                        ৳ {{ number_format($totalGrossSales, 2) }}
                    </div>
                    <div class="extra-small text-slate-400 d-flex align-items-center gap-1">
                        <span class="badge bg-purple-500 bg-opacity-20 text-purple-300 extra-small py-0.5">85% Net Royalty</span>
                        <span>15% Platform fee</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Pending Payouts -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-metric-card text-warning h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="extra-small font-monospace text-slate-400 text-uppercase fw-bold">Pending Approval</span>
                        <div class="p-1.5 rounded-3 bg-warning bg-opacity-15 text-warning border border-warning border-opacity-30">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <div class="h3 fw-extrabold text-white mb-1">
                        ৳ {{ number_format($pendingWithdrawals, 2) }}
                    </div>
                    <div class="extra-small text-slate-400 d-flex align-items-center gap-1">
                        <i class="bi bi-clock-history text-warning"></i>
                        <span>Held in queue for processing</span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Lifetime Completed Cashouts -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-metric-card text-cyan-400 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="extra-small font-monospace text-slate-400 text-uppercase fw-bold">Completed Cashouts</span>
                        <div class="p-1.5 rounded-3 bg-cyan-500 bg-opacity-15 text-cyan-400 border border-cyan-500 border-opacity-30">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                    </div>
                    <div class="h3 fw-extrabold text-white mb-1">
                        ৳ {{ number_format($completedPayouts, 2) }}
                    </div>
                    <div class="extra-small text-slate-400 d-flex align-items-center gap-1">
                        <i class="bi bi-bank text-cyan-400"></i>
                        <span>Disbursed to seller accounts</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="row g-4">

            <!-- ========================================================= -->
            <!-- LEFT COLUMN: WITHDRAWAL REQUEST FORM (5 COLUMNS)          -->
            <!-- ========================================================= -->
            <div class="col-12 col-lg-5">
                <div class="payout-card" id="withdrawalFormCard">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-slate-800">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-send-check text-emerald-400 fs-5"></i>
                            <h2 class="h6 fw-bold text-white mb-0">Request Withdrawal</h2>
                        </div>
                        <span class="badge rounded-pill extra-small font-monospace" style="background: rgba(16, 185, 129, 0.15); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.3);">
                            Min ৳{{ number_format($minThreshold, 0) }}
                        </span>
                    </div>

                    <form action="{{ route('seller.payouts.withdraw') }}" method="POST" id="payoutWithdrawForm" onsubmit="return confirmWithdrawal(event);">
                        @csrf

                        <!-- Field 1: Amount with Presets -->
                        <div class="mb-3.5">
                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                <label for="withdrawAmountInput" class="extra-small font-monospace text-uppercase text-slate-400 fw-bold">
                                    Withdrawal Amount (BDT) <span class="text-danger">*</span>
                                </label>
                                <span class="extra-small text-slate-400">
                                    Avail: <strong class="text-emerald-400 font-monospace">৳{{ number_format($availableBalance, 2) }}</strong>
                                </span>
                            </div>

                            <div class="input-group mb-2">
                                <span class="input-group-text bg-slate-900 border-slate-800 text-slate-400 fw-bold px-3">৳</span>
                                <input type="number" 
                                       step="1" 
                                       min="{{ $minThreshold }}" 
                                       max="{{ $availableBalance }}" 
                                       id="withdrawAmountInput" 
                                       name="amount" 
                                       class="form-control form-control-payout" 
                                       placeholder="e.g. 1000" 
                                       required
                                       value="{{ old('amount', $availableBalance >= 500 ? 500 : '') }}">
                            </div>

                            <!-- Quick Preset Buttons -->
                            <div class="d-flex align-items-center flex-wrap gap-1.5">
                                <span class="extra-small text-slate-500 me-1">Quick:</span>
                                <button type="button" class="amount-preset-chip" onclick="setPresetAmount(500)">৳500</button>
                                <button type="button" class="amount-preset-chip" onclick="setPresetAmount(1000)">৳1,000</button>
                                <button type="button" class="amount-preset-chip" onclick="setPresetAmount(2500)">৳2,500</button>
                                <button type="button" class="amount-preset-chip text-emerald-400" onclick="setPresetAmount({{ floor($availableBalance) }})">All Available</button>
                            </div>
                        </div>

                        <!-- Field 2: Payout Method Radios -->
                        <div class="mb-3.5">
                            <label class="extra-small font-monospace text-uppercase text-slate-400 fw-bold mb-2 d-block">
                                Disbursal Method <span class="text-danger">*</span>
                            </label>

                            <div class="row g-2">
                                <!-- Option 1: bKash -->
                                <div class="col-12">
                                    <label class="method-radio-box">
                                        <input type="radio" name="payment_method" value="bkash" checked onchange="updateAccountHelp('bkash')">
                                        <div class="method-card-inner">
                                            <div style="width: 36px; height: 36px; border-radius: 0.5rem; background: rgba(225, 29, 72, 0.15); color: #FB7185; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;" class="flex-shrink-0">
                                                bK
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="fw-bold text-white small">bKash Personal</div>
                                                <div class="extra-small text-slate-400">Direct mobile wallet transfer • 0% fee</div>
                                            </div>
                                            <span class="badge bg-slate-800 text-slate-300 extra-small font-monospace">Instant</span>
                                        </div>
                                    </label>
                                </div>

                                <!-- Option 2: Nagad -->
                                <div class="col-12">
                                    <label class="method-radio-box">
                                        <input type="radio" name="payment_method" value="nagad" onchange="updateAccountHelp('nagad')">
                                        <div class="method-card-inner">
                                            <div style="width: 36px; height: 36px; border-radius: 0.5rem; background: rgba(249, 115, 22, 0.15); color: #FB923C; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;" class="flex-shrink-0">
                                                NG
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="fw-bold text-white small">Nagad Direct</div>
                                                <div class="extra-small text-slate-400">Postal financial mobile wallet • 0% fee</div>
                                            </div>
                                            <span class="badge bg-slate-800 text-slate-300 extra-small font-monospace">Instant</span>
                                        </div>
                                    </label>
                                </div>

                                <!-- Option 3: Bank -->
                                <div class="col-12">
                                    <label class="method-radio-box">
                                        <input type="radio" name="payment_method" value="bank" onchange="updateAccountHelp('bank')">
                                        <div class="method-card-inner">
                                            <div style="width: 36px; height: 36px; border-radius: 0.5rem; background: rgba(59, 130, 246, 0.15); color: #60A5FA; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;" class="flex-shrink-0">
                                                <i class="bi bi-bank2"></i>
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="fw-bold text-white small">Bank Transfer</div>
                                                <div class="extra-small text-slate-400">BEFTN / NPSB clearing to any BD Bank</div>
                                            </div>
                                            <span class="badge bg-slate-800 text-slate-300 extra-small font-monospace">1-2 Days</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Field 3: Account Number / Details -->
                        <div class="mb-3.5">
                            <label for="accountDetailsInput" class="extra-small font-monospace text-uppercase text-slate-400 fw-bold mb-1 d-block" id="accountDetailsLabel">
                                bKash Mobile Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="accountDetailsInput" 
                                   name="account_details" 
                                   class="form-control form-control-payout" 
                                   placeholder="e.g. 01712345678" 
                                   required 
                                   value="{{ old('account_details') }}">
                            <div class="extra-small text-slate-500 mt-1" id="accountHelpText">
                                Enter your 11-digit personal bKash account phone number.
                            </div>
                        </div>

                        <!-- Field 4: Optional Memo / Note -->
                        <div class="mb-4">
                            <label for="withdrawNoteInput" class="extra-small font-monospace text-uppercase text-slate-400 fw-bold mb-1 d-block">
                                Memo / Reference Note (Optional)
                            </label>
                            <input type="text" 
                                   id="withdrawNoteInput" 
                                   name="note" 
                                   class="form-control form-control-payout" 
                                   placeholder="e.g. September earnings payout" 
                                   maxlength="150"
                                   value="{{ old('note') }}">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                id="btnSubmitWithdrawal" 
                                class="btn btn-withdraw-emerald w-100 d-inline-flex align-items-center justify-content-center gap-2"
                                {{ $availableBalance < $minThreshold ? 'disabled' : '' }}>
                            <i class="bi bi-arrow-up-right-circle-fill"></i>
                            <span>Submit Withdrawal Request</span>
                        </button>

                        @if($availableBalance < $minThreshold)
                            <div class="text-warning extra-small text-center mt-2.5">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> You need at least ৳{{ number_format($minThreshold, 2) }} available balance to request a withdrawal.
                            </div>
                        @else
                            <div class="text-slate-500 extra-small text-center mt-2.5">
                                <i class="bi bi-shield-check text-emerald-400 me-1"></i> Funds are secured in escrow until disbursement is complete.
                            </div>
                        @endif

                    </form>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- RIGHT COLUMN: PAYOUT LEDGER TABLE (7 COLUMNS)             -->
            <!-- ========================================================= -->
            <div class="col-12 col-lg-7">
                <div class="payout-card">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-slate-800 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-receipt-cutoff text-purple-400 fs-5"></i>
                            <h2 class="h6 fw-bold text-white mb-0">Withdrawal History & Ledger</h2>
                        </div>
                        <span class="badge bg-slate-900 border border-slate-800 text-slate-300 rounded-pill extra-small font-monospace">
                            {{ $withdrawals->total() }} Record(s)
                        </span>
                    </div>

                    @if($withdrawals->isEmpty())
                        <div class="text-center py-5">
                            <div style="width: 60px; height: 60px; border-radius: 1.25rem; background: rgba(30, 41, 59, 0.5); color: #64748B; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem; border: 1px solid rgba(148, 163, 184, 0.1);">
                                <i class="bi bi-inbox fs-3"></i>
                            </div>
                            <h3 class="h6 fw-bold text-white mb-1">No Withdrawal Requests Yet</h3>
                            <p class="text-slate-400 extra-small mx-auto mb-0" style="max-width: 320px;">
                                When you submit payout requests from your template earnings, each transaction status will be tracked here.
                            </p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-ledger">
                                <thead>
                                    <tr>
                                        <th>Date & ID</th>
                                        <th>Method</th>
                                        <th>Account</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($withdrawals as $item)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-white font-monospace extra-small">#WTH-{{ $item->id }}</div>
                                                <div class="extra-small text-slate-400">{{ $item->created_at->format('M d, Y • h:i A') }}</div>
                                            </td>
                                            <td>
                                                @php
                                                    $mIcon = match($item->payment_method) {
                                                        'bkash' => 'bK',
                                                        'nagad' => 'NG',
                                                        default => 'Bank',
                                                    };
                                                    $mColor = match($item->payment_method) {
                                                        'bkash' => 'text-rose-400 bg-rose-500 bg-opacity-15 border-rose-500 border-opacity-30',
                                                        'nagad' => 'text-orange-400 bg-orange-500 bg-opacity-15 border-orange-500 border-opacity-30',
                                                        default => 'text-blue-400 bg-blue-500 bg-opacity-15 border-blue-500 border-opacity-30',
                                                    };
                                                @endphp
                                                <span class="badge rounded-pill extra-small px-2.5 py-1 border {{ $mColor }}">
                                                    {{ ucfirst($item->payment_method) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="extra-small text-slate-300 font-monospace text-truncate" style="max-width: 160px;" title="{{ $item->account_details }}">
                                                    {{ $item->account_details }}
                                                </div>
                                                @if($item->note)
                                                    <div class="extra-small text-slate-500 text-truncate" style="max-width: 160px;" title="{{ $item->note }}">
                                                        Note: {{ $item->note }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong class="text-white font-monospace">৳{{ number_format($item->amount, 2) }}</strong>
                                            </td>
                                            <td>
                                                @if($item->status === 'approved')
                                                    <span class="badge rounded-pill px-2.5 py-1 extra-small" style="background: rgba(16, 185, 129, 0.2); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.35);">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Disbursed
                                                    </span>
                                                @elseif($item->status === 'rejected')
                                                    <span class="badge rounded-pill px-2.5 py-1 extra-small" style="background: rgba(239, 68, 68, 0.2); color: #FCA5A5; border: 1px solid rgba(239, 68, 68, 0.35);">
                                                        <i class="bi bi-x-circle-fill me-1"></i> Rejected
                                                    </span>
                                                @else
                                                    <span class="badge rounded-pill px-2.5 py-1 extra-small" style="background: rgba(245, 158, 11, 0.2); color: #FCD34D; border: 1px solid rgba(245, 158, 11, 0.35);">
                                                        <i class="bi bi-clock-history me-1"></i> Pending
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($withdrawals->hasPages())
                            <div class="pt-3 border-top border-slate-800 d-flex justify-content-center">
                                {{ $withdrawals->links() }}
                            </div>
                        @endif
                    @endif

                </div>
            </div>

        </div>

    </div>
</div>

<!-- Modal: Confirmation Prompt before Withdrawal Submit -->
<div class="modal fade" id="withdrawConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content" style="background: #0B1120; border: 1.5px solid rgba(16, 185, 129, 0.35); border-radius: 1.5rem; color: #F8FAFC;">
            <div class="modal-body p-4 text-center">
                <div style="width: 60px; height: 60px; border-radius: 1.25rem; background: rgba(16, 185, 129, 0.15); color: #34D399; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; border: 1px solid rgba(16, 185, 129, 0.3);">
                    <i class="bi bi-question-diamond-fill fs-2"></i>
                </div>
                <h3 class="h5 fw-extrabold text-white mb-2">Confirm Withdrawal Request</h3>
                <p class="text-slate-400 extra-small mb-3">
                    You are requesting a payout of <strong class="text-white" id="confirmAmountDisplay">৳0.00</strong> to your <span id="confirmMethodDisplay" class="text-emerald-400 fw-bold">bKash</span> account.
                </p>
                <div class="p-3 rounded-3 bg-slate-900 border border-slate-800 text-start mb-4">
                    <div class="d-flex justify-content-between extra-small text-slate-400 mb-1">
                        <span>Recipient Account:</span>
                        <strong class="text-white font-monospace" id="confirmAccountDisplay">-</strong>
                    </div>
                    <div class="d-flex justify-content-between extra-small text-slate-400">
                        <span>Processing Fee:</span>
                        <strong class="text-emerald-400 font-monospace">৳0.00 (Free)</strong>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-withdraw-emerald flex-grow-1" id="btnFinalConfirmWithdraw" onclick="finalizeWithdrawalSubmit()">
                        Confirm & Submit
                    </button>
                    <button type="button" class="btn btn-outline-slate px-4" data-bs-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setPresetAmount(amount) {
        const input = document.getElementById('withdrawAmountInput');
        if (input) {
            input.value = amount;
            input.dispatchEvent(new Event('input'));
        }
    }

    function updateAccountHelp(method) {
        const label = document.getElementById('accountDetailsLabel');
        const input = document.getElementById('accountDetailsInput');
        const help = document.getElementById('accountHelpText');

        if (method === 'bkash') {
            label.innerHTML = 'bKash Mobile Number <span class="text-danger">*</span>';
            input.placeholder = 'e.g. 01712345678';
            help.textContent = 'Enter your 11-digit personal bKash account phone number.';
        } else if (method === 'nagad') {
            label.innerHTML = 'Nagad Mobile Number <span class="text-danger">*</span>';
            input.placeholder = 'e.g. 01812345678';
            help.textContent = 'Enter your 11-digit personal Nagad account phone number.';
        } else {
            label.innerHTML = 'Bank Details (Bank, Branch, A/C # & Title) <span class="text-danger">*</span>';
            input.placeholder = 'e.g. City Bank, Gulshan Br, A/C 1122334455, Miad Khan';
            help.textContent = 'Specify Bank name, branch, account number, and exact account holder title.';
        }
    }

    let isWithdrawConfirmed = false;

    function confirmWithdrawal(e) {
        if (isWithdrawConfirmed) return true;
        e.preventDefault();

        const amountInput = document.getElementById('withdrawAmountInput');
        const accountInput = document.getElementById('accountDetailsInput');
        const method = document.querySelector('input[name="payment_method"]:checked')?.value || 'bkash';

        const amount = parseFloat(amountInput.value);
        const min = {{ $minThreshold }};
        const max = {{ $availableBalance }};

        if (isNaN(amount) || amount < min) {
            alert(`Minimum withdrawal amount is ৳${min.toFixed(2)}.`);
            amountInput.focus();
            return false;
        }

        if (amount > max) {
            alert(`Insufficient funds. Your available balance is ৳${max.toFixed(2)}.`);
            amountInput.focus();
            return false;
        }

        if (!accountInput.value.trim()) {
            alert('Please enter your account details.');
            accountInput.focus();
            return false;
        }

        // Populate modal data
        document.getElementById('confirmAmountDisplay').textContent = `৳${amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        document.getElementById('confirmMethodDisplay').textContent = method.toUpperCase();
        document.getElementById('confirmAccountDisplay').textContent = accountInput.value.trim();

        const modalEl = document.getElementById('withdrawConfirmModal');
        if (window.bootstrap && bootstrap.Modal) {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        } else {
            if (confirm(`Confirm withdrawal request of ৳${amount.toFixed(2)} via ${method.toUpperCase()}?`)) {
                isWithdrawConfirmed = true;
                document.getElementById('payoutWithdrawForm').submit();
            }
        }

        return false;
    }

    function finalizeWithdrawalSubmit() {
        isWithdrawConfirmed = true;
        const modalEl = document.getElementById('withdrawConfirmModal');
        if (window.bootstrap && bootstrap.Modal) {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }
        document.getElementById('payoutWithdrawForm').submit();
    }
</script>
@endsection
