@extends('layouts.admin')

@section('title', 'Financial Analytics & Platform Commission - Noksha Admin HQ')
@section('page_title', 'Finance & Commissions')
@section('page_heading', 'Financial Intelligence & Payout Operations')

@section('content')
<div x-data="{
    rejectModal: false,
    selectedWithdrawal: null,
    rejectReason: '',
    openReject(withdrawal) {
        this.selectedWithdrawal = withdrawal;
        this.rejectReason = '';
        this.rejectModal = true;
    }
}" class="space-y-8">

    <!-- 1. TOP METRICS GRID (4 PRIMARY STAT CARDS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <!-- Total Marketplace GMV -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-[#20165c]/40 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Platform Gross GMV</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-[#20165c] flex items-center justify-center">
                    <i class="bi bi-graph-up-arrow text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl sm:text-3xl font-black text-[#121826] tracking-tight flex items-baseline gap-1">
                    <span class="text-[#20165c] font-bold text-xl">৳</span>
                    <span>{{ number_format($totalGmv, 2) }}</span>
                </div>
                <div class="text-[11px] text-slate-500 mt-1.5 flex items-center gap-2">
                    <span>Templates: ৳{{ number_format($templateGmv, 0) }}</span>
                    <span>•</span>
                    <span>Contests: ৳{{ number_format($contestPrizeGmv, 0) }}</span>
                </div>
            </div>
        </div>

        <!-- Net Platform Commission Revenue -->
        <div class="bg-white rounded-2xl p-5 border border-emerald-200 bg-emerald-50/20 shadow-sm relative overflow-hidden group hover:border-emerald-500/60 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Net Commission Revenue</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="bi bi-wallet2 text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl sm:text-3xl font-black text-emerald-700 tracking-tight flex items-baseline gap-1">
                    <span class="font-bold text-xl">৳</span>
                    <span>{{ number_format($totalCommissionRevenue, 2) }}</span>
                </div>
                <div class="text-[11px] text-emerald-700/80 mt-1.5 flex items-center gap-1.5">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">15% Cut</span>
                    <span>+ ৳{{ number_format($contestFees, 0) }} contest fees</span>
                </div>
            </div>
        </div>

        <!-- Total AI Credit Sales -->
        <div class="bg-white rounded-2xl p-5 border border-orange-200 bg-orange-50/20 shadow-sm relative overflow-hidden group hover:border-[#f47402]/60 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#f47402] uppercase tracking-wider">Credit Bundle Sales</span>
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#f47402] flex items-center justify-center">
                    <i class="bi bi-lightning-charge-fill text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl sm:text-3xl font-black text-[#f47402] tracking-tight flex items-baseline gap-1">
                    <span class="font-bold text-xl">৳</span>
                    <span>{{ number_format($totalAiCreditSales, 2) }}</span>
                </div>
                <div class="text-[11px] text-orange-800/80 mt-1.5 flex items-center gap-1">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-[#f47402] border border-orange-200">Token Store</span>
                    <span>Starter, Creator & Pro packages</span>
                </div>
            </div>
        </div>

        <!-- Pending Creator Withdrawals Pipeline -->
        <div class="bg-white rounded-2xl p-5 border {{ $pendingPayoutsCount > 0 ? 'border-rose-300 bg-rose-50/20' : 'border-slate-200' }} shadow-sm relative overflow-hidden group hover:border-rose-400 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold {{ $pendingPayoutsCount > 0 ? 'text-rose-700' : 'text-slate-500' }} uppercase tracking-wider">
                    Pending Withdrawals Queue
                </span>
                <div class="w-10 h-10 rounded-xl {{ $pendingPayoutsCount > 0 ? 'bg-rose-100 text-rose-600 animate-pulse' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center">
                    <i class="bi bi-clock-history text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl sm:text-3xl font-black {{ $pendingPayoutsCount > 0 ? 'text-rose-600' : 'text-[#121826]' }} tracking-tight flex items-baseline gap-1">
                    <span class="font-bold text-xl">৳</span>
                    <span>{{ number_format($pendingPayoutsSum, 2) }}</span>
                </div>
                <div class="text-[11px] mt-1.5 flex items-center gap-2">
                    @if($pendingPayoutsCount > 0)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-100 text-rose-700 border border-rose-200 animate-pulse">
                            {{ $pendingPayoutsCount }} {{ Str::plural('Request', $pendingPayoutsCount) }} Awaiting
                        </span>
                    @else
                        <span class="text-emerald-600 font-semibold flex items-center gap-1">
                            <i class="bi bi-check-circle-fill text-[11px]"></i> All requests settled
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- SECONDARY KPI / ESCROW STRIP -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Active Locked Escrow in Contests -->
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-purple-50 text-[#20165c] flex items-center justify-center">
                    <i class="bi bi-shield-lock-fill text-base"></i>
                </div>
                <div>
                    <div class="text-[11px] text-slate-500 font-medium uppercase tracking-wider">Locked Escrow (Active Contests)</div>
                    <div class="text-lg font-extrabold text-[#121826] mt-0.5">৳{{ number_format($activeEscrowFunds, 2) }}</div>
                </div>
            </div>
            <span class="text-xs px-2 py-1 rounded bg-purple-50 text-[#20165c] border border-purple-200 font-mono font-bold">Protected</span>
        </div>

        <!-- Total Disbursed Creator Payouts -->
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="bi bi-check2-all text-base"></i>
                </div>
                <div>
                    <div class="text-[11px] text-slate-500 font-medium uppercase tracking-wider">Total Disbursed Payouts</div>
                    <div class="text-lg font-extrabold text-[#121826] mt-0.5">৳{{ number_format($approvedPayoutsSum, 2) }}</div>
                </div>
            </div>
            <span class="text-xs px-2 py-1 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono font-bold">Settled</span>
        </div>

        <!-- Platform Commission Split Rule -->
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-orange-50 text-[#f47402] flex items-center justify-center">
                    <i class="bi bi-pie-chart-fill text-base"></i>
                </div>
                <div>
                    <div class="text-[11px] text-slate-500 font-medium uppercase tracking-wider">Creator Revenue Split</div>
                    <div class="text-lg font-extrabold text-[#121826] mt-0.5">85% Creator / 15% Noksha</div>
                </div>
            </div>
            <span class="text-xs px-2 py-1 rounded bg-orange-50 text-[#f47402] border border-orange-200 font-mono font-bold">Standard</span>
        </div>
    </div>

    <!-- 2. PENDING CREATOR WITHDRAWALS TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-2.5 h-2.5 rounded-full {{ $pendingWithdrawals->count() > 0 ? 'bg-rose-500 animate-ping' : 'bg-emerald-500' }}"></div>
                    <h2 class="text-base font-bold text-[#121826]">Pending Creator Withdrawals</h2>
                    <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $pendingWithdrawals->count() > 0 ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                        {{ $pendingWithdrawals->count() }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Review requested disbursements and approve or decline with automated instant refunds.</p>
            </div>

            @if($pendingWithdrawals->count() > 0)
                <div class="text-xs text-rose-700 font-medium flex items-center gap-1.5 bg-rose-50 border border-rose-200 px-3 py-1.5 rounded-lg">
                    <i class="bi bi-shield-exclamation"></i>
                    <span>Action required: {{ $pendingWithdrawals->count() }} creator {{ Str::plural('payout', $pendingWithdrawals->count()) }} waiting</span>
                </div>
            @endif
        </div>

        @if($pendingWithdrawals->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5 font-semibold">Creator / Seller</th>
                            <th class="px-5 py-3.5 font-semibold">Payment Method</th>
                            <th class="px-5 py-3.5 font-semibold">Account / Phone Details</th>
                            <th class="px-5 py-3.5 font-semibold">Requested Amount</th>
                            <th class="px-5 py-3.5 font-semibold">Submission Date</th>
                            <th class="px-5 py-3.5 font-semibold text-right">Quick Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($pendingWithdrawals as $payout)
                            @php
                                $methodBg = match(strtolower($payout->payment_method)) {
                                    'bkash' => 'bg-pink-50 text-pink-700 border-pink-200',
                                    'nagad' => 'bg-orange-50 text-orange-700 border-orange-200',
                                    'bank' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200',
                                };
                                $methodIcon = match(strtolower($payout->payment_method)) {
                                    'bkash' => 'bi-phone',
                                    'nagad' => 'bi-phone-flip',
                                    'bank' => 'bi-bank',
                                    default => 'bi-credit-card',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- Creator / Seller -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-purple-100 text-[#20165c] font-bold text-xs flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($payout->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-[#121826] flex items-center gap-1.5">
                                                <span>{{ $payout->user->name ?? 'Unknown User' }}</span>
                                                <span class="text-[10px] text-slate-400">#{{ $payout->user_id }}</span>
                                            </div>
                                            <div class="text-xs text-slate-500 font-mono">{{ $payout->user->email ?? 'No Email' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Payment Method -->
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold uppercase border {{ $methodBg }}">
                                        <i class="bi {{ $methodIcon }}"></i>
                                        <span>{{ $payout->payment_method }}</span>
                                    </span>
                                </td>

                                <!-- Account / Phone Details -->
                                <td class="px-5 py-4">
                                    <div class="font-mono text-xs font-semibold text-[#121826] bg-slate-50 px-2.5 py-1.5 rounded border border-slate-200 inline-block select-all">
                                        {{ $payout->account_details }}
                                    </div>
                                    @if($payout->note)
                                        <div class="text-[11px] text-slate-500 italic mt-1 max-w-xs truncate" title="{{ $payout->note }}">
                                            "{{ $payout->note }}"
                                        </div>
                                    @endif
                                </td>

                                <!-- Requested Amount -->
                                <td class="px-5 py-4">
                                    <div class="text-base font-black text-[#20165c] font-mono">
                                        ৳{{ number_format($payout->amount, 2) }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-mono">Payout ID: #WTH-{{ $payout->id }}</div>
                                </td>

                                <!-- Submission Date -->
                                <td class="px-5 py-4">
                                    <div class="text-xs text-slate-700">{{ $payout->created_at->format('M d, Y') }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $payout->created_at->diffForHumans() }}</div>
                                </td>

                                <!-- Quick Actions -->
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Approve Form -->
                                        <form method="POST" action="{{ route('admin.finance.approveWithdrawal', $payout->id) }}" onsubmit="return confirm('Are you sure you want to approve and mark payout #WTH-{{ $payout->id }} for ৳{{ number_format($payout->amount, 2) }} as disbursed?');">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition-all cursor-pointer">
                                                <i class="bi bi-check-lg"></i>
                                                <span>Approve</span>
                                            </button>
                                        </form>

                                        <!-- Reject & Refund Modal Trigger -->
                                        <button type="button" @click="openReject({
                                            id: {{ $payout->id }},
                                            amount: '{{ number_format($payout->amount, 2) }}',
                                            user_name: '{{ addslashes($payout->user->name ?? 'User') }}',
                                            method: '{{ strtoupper($payout->payment_method) }}',
                                            account: '{{ addslashes($payout->account_details) }}',
                                            url: '{{ route('admin.finance.rejectWithdrawal', $payout->id) }}'
                                        })" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 transition-all cursor-pointer">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                            <span>Reject & Refund</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-3 text-2xl">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <h3 class="text-base font-bold text-[#121826]">No Pending Withdrawals</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                    All creator withdrawal payout requests have been reviewed, approved, or refunded. The queue is fully clear.
                </p>
            </div>
        @endif
    </div>

    <!-- 3. PLATFORM-WIDE AUDIT LEDGER -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Header & Search/Filter Controls -->
        <div class="p-5 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
            <div>
                <div class="flex items-center gap-2">
                    <i class="bi bi-journal-text text-[#20165c] text-base"></i>
                    <h2 class="text-base font-bold text-[#121826]">Platform-Wide Audit Ledger</h2>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Real-time immutable ledger stream of deposits, sales commissions, credit purchases, and deductions.</p>
            </div>

            <!-- Filter / Search Form -->
            <form method="GET" action="{{ route('admin.finance.index') }}" class="flex flex-wrap items-center gap-2.5">
                <!-- Search Box -->
                <div class="relative min-w-[220px]">
                    <i class="bi bi-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search email, user, memo..."
                           class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-8 pr-3 py-1.5 text-xs text-[#121826] placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#20165c]">
                </div>

                <!-- Transaction Type Filter -->
                <select name="type" class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-[#121826] focus:bg-white focus:outline-none focus:border-[#20165c]">
                    <option value="all" {{ $typeFilter === 'all' ? 'selected' : '' }}>All Types</option>
                    @foreach($transactionTypes as $type)
                        <option value="{{ $type }}" {{ $typeFilter === $type ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $type)) }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-[#20165c] hover:bg-[#181045] text-white text-xs font-bold transition-colors shadow-sm">
                    Filter
                </button>

                @if($search || ($typeFilter && $typeFilter !== 'all'))
                    <a href="{{ route('admin.finance.index') }}" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs transition-colors border border-slate-200" title="Clear Filters">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </form>
        </div>

        <!-- Ledger Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">TX ID / Time</th>
                        <th class="px-5 py-3.5">User Account</th>
                        <th class="px-5 py-3.5">Event Type</th>
                        <th class="px-5 py-3.5">Description / Memo</th>
                        <th class="px-5 py-3.5 text-right">Amount (৳)</th>
                        <th class="px-5 py-3.5 text-right">Tokens</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ledgerTransactions as $tx)
                        @php
                            $isCredit = in_array($tx->type, ['deposit', 'template_sale', 'refund', 'credit_purchase']);
                            $typeBadge = match($tx->type) {
                                'deposit' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'template_purchase' => 'bg-purple-50 text-[#20165c] border-purple-200',
                                'template_sale' => 'bg-teal-50 text-teal-700 border-teal-200',
                                'credit_purchase' => 'bg-orange-50 text-[#f47402] border-orange-200',
                                'withdrawal' => 'bg-rose-50 text-rose-700 border-rose-200',
                                'refund' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'ai_generation_fee' => 'bg-sky-50 text-sky-700 border-sky-200',
                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                            $statusBadge = match($tx->status) {
                                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'rejected', 'failed' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- TX ID & Timestamp -->
                            <td class="px-5 py-3.5">
                                <div class="font-mono text-xs font-bold text-[#121826]">#TX-{{ $tx->id }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $tx->created_at->format('M d, H:i') }}</div>
                            </td>

                            <!-- User -->
                            <td class="px-5 py-3.5">
                                @if($tx->user)
                                    <div class="font-medium text-[#121826] text-xs">{{ $tx->user->name }}</div>
                                    <div class="text-[10px] text-slate-500 font-mono">{{ $tx->user->email }}</div>
                                @else
                                    <span class="text-xs text-slate-400 italic">System Auto</span>
                                @endif
                            </td>

                            <!-- Type -->
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold uppercase border {{ $typeBadge }}">
                                    {{ ucwords(str_replace('_', ' ', $tx->type)) }}
                                </span>
                            </td>

                            <!-- Description -->
                            <td class="px-5 py-3.5">
                                <div class="text-xs text-slate-700 max-w-sm truncate" title="{{ $tx->description }}">
                                    {{ $tx->description }}
                                </div>
                                @if($tx->balance_after !== null)
                                    <div class="text-[10px] text-slate-400 font-mono">
                                        Post-bal: ৳{{ number_format($tx->balance_after, 2) }}
                                    </div>
                                @endif
                            </td>

                            <!-- Amount -->
                            <td class="px-5 py-3.5 text-right font-mono">
                                @if($tx->amount > 0)
                                    <span class="font-bold text-xs {{ $isCredit ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $isCredit ? '+' : '-' }}৳{{ number_format($tx->amount, 2) }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>

                            <!-- AI Tokens transacted -->
                            <td class="px-5 py-3.5 text-right font-mono">
                                @if($tx->credits_transacted > 0)
                                    <span class="text-[#f47402] font-bold text-xs">+{{ $tx->credits_transacted }}</span>
                                @elseif($tx->credits_transacted < 0)
                                    <span class="text-rose-600 font-bold text-xs">{{ $tx->credits_transacted }}</span>
                                @else
                                    <span class="text-slate-400 text-xs">0</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase border {{ $statusBadge }}">
                                    {{ $tx->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-500">
                                <i class="bi bi-search text-2xl block mb-2 text-slate-400"></i>
                                <span>No ledger transactions matched your query.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($ledgerTransactions->hasPages())
            <div class="p-4 border-t border-slate-200 flex justify-center">
                {{ $ledgerTransactions->links() }}
            </div>
        @endif
    </div>

    <!-- 4. REJECT & REFUND MODAL (ALPINE.JS) -->
    <div x-show="rejectModal"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">

        <div @click.away="rejectModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white border border-slate-200 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden text-slate-800">

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-rose-100 flex items-center justify-between bg-rose-50">
                <div class="flex items-center gap-2.5 text-rose-700">
                    <i class="bi bi-arrow-counterclockwise text-lg"></i>
                    <h3 class="font-bold text-base text-rose-950">Reject Payout & Auto-Refund</h3>
                </div>
                <button type="button" @click="rejectModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <template x-if="selectedWithdrawal">
                <form :action="selectedWithdrawal.url" method="POST" class="p-6 space-y-4">
                    @csrf

                    <!-- Payout Details Summary Card -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-1.5">
                        <div class="flex justify-between text-slate-600">
                            <span>Recipient:</span>
                            <span class="font-bold text-[#121826]" x-text="selectedWithdrawal.user_name"></span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Amount to Refund:</span>
                            <span class="font-bold text-emerald-600 font-mono" x-text="'৳' + selectedWithdrawal.amount"></span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Target Method:</span>
                            <span class="font-mono text-slate-800" x-text="selectedWithdrawal.method + ' (' + selectedWithdrawal.account + ')'"></span>
                        </div>
                    </div>

                    <!-- Safety Explanation Alert -->
                    <div class="p-3 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-start gap-2">
                        <i class="bi bi-info-circle-fill text-amber-600 mt-0.5"></i>
                        <span>The requested ৳<span x-text="selectedWithdrawal.amount"></span> will be immediately returned into the creator's wallet balance with an audit log.</span>
                    </div>

                    <!-- Rejection Reason Input -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Rejection Reason (Sent to Creator)
                        </label>
                        <textarea name="reason" rows="3" x-model="rejectReason" required
                                  placeholder="e.g. Invalid bKash account number, name on bank account mismatch, or verification required..."
                                  class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-[#121826] placeholder-slate-400 focus:bg-white focus:outline-none focus:border-rose-500"></textarea>
                    </div>

                    <!-- Quick Reason Templates -->
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" @click="rejectReason = 'bKash/Nagad phone number entered is invalid or unreachable.'"
                                class="px-2 py-1 rounded bg-slate-100 text-slate-700 hover:bg-slate-200 text-[10px] border border-slate-200">
                            Invalid Phone Number
                        </button>
                        <button type="button" @click="rejectReason = 'Bank account details (routing number or account name) did not match.'"
                                class="px-2 py-1 rounded bg-slate-100 text-slate-700 hover:bg-slate-200 text-[10px] border border-slate-200">
                            Bank Info Mismatch
                        </button>
                        <button type="button" @click="rejectReason = 'Identity verification (KYC) required before large payouts can be disbursed.'"
                                class="px-2 py-1 rounded bg-slate-100 text-slate-700 hover:bg-slate-200 text-[10px] border border-slate-200">
                            KYC Needed
                        </button>
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-3 border-t border-slate-200 flex justify-end gap-3">
                        <button type="button" @click="rejectModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all flex items-center gap-1.5">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            <span>Confirm Rejection & Refund</span>
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>
@endsection