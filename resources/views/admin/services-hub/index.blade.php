@extends('layouts.admin')

@section('title', 'Services, Escrow & Disputes Hub - Noksha HQ')

@section('content')
<div class="space-y-6">

    <!-- ======================================================== -->
    <!-- 1. HEADER & KPI METRICS SUMMARY                          -->
    <!-- ======================================================== -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-50 text-[#f47402] border border-orange-200">
                    🛠️ Creator Services & Escrow Engine
                </span>
                <span class="text-xs text-slate-400 font-mono">Live Arbitration</span>
            </div>
            <h1 class="text-2xl font-black text-[#121826] tracking-tight">Services & Escrow Command Center</h1>
            <p class="text-sm text-slate-500 mb-0">Arbitration console, live escrow tracking, creator catalogue & demerit control</p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('admin.finance.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition shadow-xs">
                <i class="bi bi-cash-stack text-teal-600"></i>
                <span>Platform Financials</span>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-[#20165c] hover:bg-[#181045] transition shadow-xs">
                <i class="bi bi-speedometer2"></i>
                <span>Executive HQ</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Active Escrow Held --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Active Escrow Held</span>
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-[#20165c] flex items-center justify-center font-bold text-sm">
                    🔒
                </div>
            </div>
            <div class="text-2xl font-black text-[#121826]">
                ৳{{ number_format($escrowHeld, 2) }}
            </div>
            <p class="text-xs text-slate-400 mt-1 mb-0 flex items-center gap-1.5 font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ $activeOrdersCount }} funded active orders in pipeline</span>
            </p>
        </div>

        {{-- Card 2: Completed Service Volume --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Completed Volume</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    ৳
                </div>
            </div>
            <div class="text-2xl font-black text-[#121826]">
                ৳{{ number_format($completedVolume, 2) }}
            </div>
            <p class="text-xs text-emerald-600 font-semibold mt-1 mb-0">
                100% verified delivered & released
            </p>
        </div>

        {{-- Card 3: 15% Platform Commission --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Platform Commission (15%)</span>
                <div class="w-9 h-9 rounded-xl bg-orange-50 text-[#f47402] flex items-center justify-center font-bold text-sm">
                    💎
                </div>
            </div>
            <div class="text-2xl font-black text-[#f47402]">
                ৳{{ number_format($totalCommission, 2) }}
            </div>
            <p class="text-xs text-slate-400 mt-1 mb-0">
                Net retained revenue on services
            </p>
        </div>

        {{-- Card 4: Active Disputes & Complaints --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Arbitration Cases</span>
                <div class="w-9 h-9 rounded-xl {{ $totalDisputesCount > 0 ? 'bg-rose-50 text-rose-600 animate-pulse' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center font-bold text-sm">
                    🚨
                </div>
            </div>
            <div class="text-2xl font-black {{ $totalDisputesCount > 0 ? 'text-rose-600' : 'text-[#121826]' }}">
                {{ $totalDisputesCount }}
            </div>
            <p class="text-xs text-slate-400 mt-1 mb-0">
                {{ $disputedOrdersCount }} disputed orders · {{ $unresolvedComplaintsCount }} complaint reviews
            </p>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 2. TAB NAVIGATION BAR                                    -->
    <!-- ======================================================== -->
    <div class="bg-white border border-slate-200 rounded-2xl p-2 shadow-xs">
        <div class="flex items-center gap-2 overflow-x-auto">
            <button type="button" onclick="switchHubTab('creators', this)" class="hub-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer shrink-0 {{ $activeTab === 'creators' ? 'bg-[#20165c] text-white shadow-sm' : 'text-slate-600 hover:text-[#20165c] hover:bg-slate-50' }}" data-tab="creators">
                <i class="bi bi-people-fill text-sm"></i>
                <span>1-Click Creator Sales & Service Catalogue</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $activeTab === 'creators' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">
                    {{ $creators->count() }}
                </span>
            </button>

            <button type="button" onclick="switchHubTab('ledger', this)" class="hub-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer shrink-0 {{ $activeTab === 'ledger' ? 'bg-[#20165c] text-white shadow-sm' : 'text-slate-600 hover:text-[#20165c] hover:bg-slate-50' }}" data-tab="ledger">
                <i class="bi bi-receipt-cutoff text-sm"></i>
                <span>Live Escrow, Orders & Commission Ledger</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $activeTab === 'ledger' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">
                    {{ $orders->total() }}
                </span>
            </button>

            <button type="button" onclick="switchHubTab('disputes', this)" class="hub-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer shrink-0 {{ $activeTab === 'disputes' ? 'bg-[#20165c] text-white shadow-sm' : 'text-slate-600 hover:text-[#20165c] hover:bg-slate-50' }}" data-tab="disputes">
                <i class="bi bi-shield-exclamation text-sm text-rose-500"></i>
                <span>Disputes & Evidence Inspector</span>
                @if($totalDisputesCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white animate-pulse">
                        {{ $totalDisputesCount }}
                    </span>
                @else
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                        0
                    </span>
                @endif
            </button>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 3. TAB PANE 1: CREATOR SALES & SERVICE CATALOGUE         -->
    <!-- ======================================================== -->
    <div id="pane-creators" class="hub-pane {{ $activeTab === 'creators' ? '' : 'hidden' }} space-y-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-200">
                <div>
                    <h3 class="text-base font-bold text-[#121826] mb-0.5">Creator Catalogue & Reliability Matrix</h3>
                    <p class="text-xs text-slate-500 mb-0">Search creators, audit active services, review on-time performance & adjust demerits</p>
                </div>

                {{-- Search bar --}}
                <form method="GET" action="{{ route('admin.services-hub.index') }}" class="flex items-center gap-2">
                    <input type="hidden" name="tab" value="creators">
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="creator_q" value="{{ $creatorSearch }}" placeholder="Search by name, email..." class="pl-8 pr-3 py-1.5 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#20165c] w-60">
                    </div>
                    <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#20165c] text-white hover:bg-[#181045] transition">
                        Filter
                    </button>
                    @if($creatorSearch)
                        <a href="{{ route('admin.services-hub.index', ['tab' => 'creators']) }}" class="px-2.5 py-1.5 rounded-xl text-xs text-slate-500 bg-slate-100 hover:bg-slate-200 transition">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto mt-4">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px] bg-slate-50/70">
                            <th class="py-3 px-4">Creator</th>
                            <th class="py-3 px-4 text-center">Services (Active / Total)</th>
                            <th class="py-3 px-4 text-center">Completed Orders</th>
                            <th class="py-3 px-4 text-center">Service Volume</th>
                            <th class="py-3 px-4 text-center">15% Comm. Retained</th>
                            <th class="py-3 px-4 text-center">Rating</th>
                            <th class="py-3 px-4 text-center">Demerit Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($creators as $cData)
                            @php
                                $crUser = $cData['user'];
                                $crAvatar = $crUser->avatar ? (str_starts_with($crUser->avatar, 'http') ? $crUser->avatar : asset('storage/' . ltrim(str_replace('storage/', '', $crUser->avatar), '/'))) : null;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        @if($crAvatar)
                                            <img src="{{ $crAvatar }}" alt="{{ $crUser->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-200" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($crUser->name) }}&background=20165c&color=fff';">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-[#20165c]/10 text-[#20165c] font-black flex items-center justify-center text-sm">
                                                {{ strtoupper(substr($crUser->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-bold text-[#121826] text-xs flex items-center gap-1.5">
                                                <span>{{ $crUser->name }}</span>
                                                @if($cData['has_warning'])
                                                    <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                        ⚠️ Warning
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        ✓ Reliable
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-[11px] text-slate-400 font-mono">{{ $crUser->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3.5 px-4 text-center">
                                    <span class="font-semibold text-slate-800">
                                        {{ $cData['active_services_count'] }}
                                    </span>
                                    <span class="text-slate-400">/ {{ $cData['services_count'] }} (Max 5)</span>
                                </td>

                                <td class="py-3.5 px-4 text-center font-semibold text-slate-800">
                                    {{ $cData['completed_orders_count'] }}
                                </td>

                                <td class="py-3.5 px-4 text-center font-bold text-[#121826]">
                                    ৳{{ number_format($cData['service_volume'], 2) }}
                                </td>

                                <td class="py-3.5 px-4 text-center font-bold text-[#f47402]">
                                    ৳{{ number_format($cData['total_commission'], 2) }}
                                </td>

                                <td class="py-3.5 px-4 text-center">
                                    <div class="inline-flex items-center gap-1 text-amber-500 font-bold">
                                        <span>★</span>
                                        <span>{{ number_format($cData['avg_rating'], 1) }}</span>
                                        <span class="text-slate-400 text-[10px] font-normal">({{ $cData['reviews_count'] }})</span>
                                    </div>
                                </td>

                                <td class="py-3.5 px-4 text-center">
                                    @if($cData['demerit_points'] > 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold {{ $cData['demerit_points'] >= 20 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                            <span>{{ $cData['demerit_points'] }} pts</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600">
                                            0 pts (Clean)
                                        </span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4 text-right">
                                    <button type="button" onclick="openCreatorInspector('{{ $crUser->id }}', '{{ addslashes($crUser->name) }}', '{{ $crUser->email }}', {{ $cData['demerit_points'] }}, {{ $cData['has_warning'] ? 'true' : 'false' }}, '{{ route('creator.profile', $crUser->username) }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-[#20165c] hover:bg-[#181045] text-white shadow-xs transition cursor-pointer">
                                        <i class="bi bi-search"></i>
                                        <span>1-Click Inspect</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    <i class="bi bi-people text-3xl d-block mb-2 text-slate-300"></i>
                                    <p class="text-sm font-semibold text-slate-600 mb-1">No creators matched your search</p>
                                    <p class="text-xs text-slate-400">Try searching with a different keyword</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 4. TAB PANE 2: LIVE ESCROW, ORDERS & COMMISSION LEDGER   -->
    <!-- ======================================================== -->
    <div id="pane-ledger" class="hub-pane {{ $activeTab === 'ledger' ? '' : 'hidden' }} space-y-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 pb-4 border-b border-slate-200">
                <div>
                    <h3 class="text-base font-bold text-[#121826] mb-0.5">Live Escrow & Service Orders Ledger</h3>
                    <p class="text-xs text-slate-500 mb-0">Track real-time escrow holds, delivery countdowns, 15% platform commission and 85% payouts</p>
                </div>

                {{-- Status Filter Pills & Search --}}
                <div class="flex items-center gap-2 flex-wrap">
                    <form method="GET" action="{{ route('admin.services-hub.index') }}" class="flex items-center gap-2 flex-wrap">
                        <input type="hidden" name="tab" value="ledger">
                        <select name="order_status" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 font-semibold focus:outline-none focus:border-[#20165c]">
                            <option value="all" {{ $orderFilter === 'all' ? 'selected' : '' }}>All Orders</option>
                            <option value="in_progress" {{ $orderFilter === 'in_progress' ? 'selected' : '' }}>🔒 Escrow Held (In Progress)</option>
                            <option value="delivered" {{ $orderFilter === 'delivered' ? 'selected' : '' }}>📦 Delivered (Preview)</option>
                            <option value="completed" {{ $orderFilter === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                            <option value="disputed" {{ $orderFilter === 'disputed' ? 'selected' : '' }}>🚨 In Dispute</option>
                            <option value="refunded" {{ $orderFilter === 'refunded' ? 'selected' : '' }}>↩️ Refunded</option>
                        </select>

                        <div class="relative">
                            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="order_q" value="{{ $orderSearch }}" placeholder="Order #, client, creator..." class="pl-8 pr-3 py-1.5 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#20165c] w-56">
                        </div>

                        <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#20165c] text-white hover:bg-[#181045] transition">
                            Search
                        </button>
                        @if($orderSearch || $orderFilter !== 'all')
                            <a href="{{ route('admin.services-hub.index', ['tab' => 'ledger']) }}" class="px-2.5 py-1.5 rounded-xl text-xs text-slate-500 bg-slate-100 hover:bg-slate-200 transition">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Orders Table --}}
            <div class="overflow-x-auto mt-4">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px] bg-slate-50/70">
                            <th class="py-3 px-4">Order Code / Date</th>
                            <th class="py-3 px-4">Service Package</th>
                            <th class="py-3 px-4">Client (Buyer)</th>
                            <th class="py-3 px-4">Creator (Seller)</th>
                            <th class="py-3 px-4 text-center">Gross (100%)</th>
                            <th class="py-3 px-4 text-center">Commission (15%)</th>
                            <th class="py-3 px-4 text-center">Payout (85%)</th>
                            <th class="py-3 px-4 text-center">Escrow Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($orders as $ord)
                            @php
                                $grossAmt = (float) ($ord->amount ?? $ord->total_amount);
                                $commAmt = (float) ($ord->platform_fee ?? round($grossAmt * 0.15, 2));
                                $payoutAmt = (float) ($ord->seller_payout ?? round($grossAmt - $commAmt, 2));
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 font-mono">
                                    <div class="font-bold text-[#20165c] text-xs">#{{ $ord->order_code ?? $ord->order_number }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $ord->created_at->format('M d, Y H:i') }}</div>
                                </td>

                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-slate-900 max-w-xs truncate">
                                        {{ $ord->custom_title ?? $ord->service?->title ?? 'Custom Creative Package' }}
                                    </div>
                                    <div class="text-[11px] text-slate-400">
                                        {{ $ord->delivery_days }} days delivery · {{ $ord->revisions_allowed }} revisions
                                    </div>
                                </td>

                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-slate-800">{{ $ord->buyer->name ?? 'Buyer #' . $ord->buyer_id }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono">{{ $ord->buyer->email ?? '' }}</div>
                                </td>

                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-slate-800">{{ $ord->seller->name ?? 'Creator #' . $ord->seller_id }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono">{{ $ord->seller->email ?? '' }}</div>
                                </td>

                                <td class="py-3.5 px-4 text-center font-bold text-[#121826]">
                                    ৳{{ number_format($grossAmt, 2) }}
                                </td>

                                <td class="py-3.5 px-4 text-center font-bold text-[#f47402]">
                                    ৳{{ number_format($commAmt, 2) }}
                                </td>

                                <td class="py-3.5 px-4 text-center font-bold text-emerald-600">
                                    ৳{{ number_format($payoutAmt, 2) }}
                                </td>

                                <td class="py-3.5 px-4 text-center">
                                    @if($ord->status === 'in_progress' || $ord->status === 'funded_in_progress')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-[#20165c] border border-purple-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#20165c] animate-pulse"></span> Escrow Held
                                        </span>
                                    @elseif($ord->status === 'delivered_preview' || $ord->status === 'delivered')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                            📦 Delivered
                                        </span>
                                    @elseif($ord->status === 'completed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            ✅ Released
                                        </span>
                                    @elseif($ord->status === 'disputed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-300 animate-pulse">
                                            🚨 Disputed
                                        </span>
                                    @elseif($ord->status === 'refunded' || $ord->status === 'cancelled')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                            ↩️ Refunded
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 capitalize">
                                            {{ str_replace('_', ' ', $ord->status) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('inbox.show', $ord->conversation_id) }}" target="_blank" class="px-2.5 py-1.5 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                                            💬 Thread
                                        </a>
                                        @if($ord->status === 'disputed')
                                            <button type="button" onclick="openDisputeModal({{ $ord->id }}, '{{ $ord->order_code }}', {{ $grossAmt }}, {{ $payoutAmt }}, '{{ addslashes($ord->buyer->name ?? 'Buyer') }}', '{{ addslashes($ord->seller->name ?? 'Creator') }}')" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-500 text-white shadow-xs transition cursor-pointer">
                                                ⚖️ Arbitrate
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-slate-400">
                                    <i class="bi bi-receipt text-3xl d-block mb-2 text-slate-300"></i>
                                    <p class="text-sm font-semibold text-slate-600 mb-1">No orders found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-center">
                {{ $orders->appends(['tab' => 'ledger', 'order_status' => $orderFilter, 'order_q' => $orderSearch])->links() }}
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 5. TAB PANE 3: DISPUTES & EVIDENCE INSPECTOR             -->
    <!-- ======================================================== -->
    <div id="pane-disputes" class="hub-pane {{ $activeTab === 'disputes' ? '' : 'hidden' }} space-y-6">

        {{-- Section 3A: Disputed Orders --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4">
                <div>
                    <h3 class="text-base font-bold text-[#121826] flex items-center gap-2 mb-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                        <span>Active Arbitration Escrow Disputes ({{ $disputedOrders->count() }})</span>
                    </h3>
                    <p class="text-xs text-slate-500 mb-0">Disputed deliveries reported by buyers. Review brief, evidence & issue binding arbitration.</p>
                </div>
            </div>

            @if($disputedOrders->count() > 0)
                <div class="grid grid-cols-1 gap-4">
                    @foreach($disputedOrders as $dispOrd)
                        @php
                            $dispGross = (float) ($dispOrd->amount ?? $dispOrd->total_amount);
                            $dispPayout = (float) ($dispOrd->seller_payout ?? round($dispGross * 0.85, 2));
                        @endphp
                        <div class="p-5 rounded-2xl border border-rose-200 bg-rose-50/30 space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-rose-100">
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap mb-1">
                                        <span class="font-mono text-sm font-black text-rose-700">#{{ $dispOrd->order_code }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            🚨 Empty/Fake File Reported
                                        </span>
                                        <span class="text-xs text-slate-500 font-mono">Disputed on {{ $dispOrd->updated_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    <h4 class="text-sm font-bold text-[#121826] mb-0">
                                        {{ $dispOrd->custom_title ?? $dispOrd->service?->title ?? 'Custom Service Order' }}
                                    </h4>
                                </div>

                                <div class="text-right">
                                    <div class="text-xl font-black text-[#121826]">৳{{ number_format($dispGross, 2) }}</div>
                                    <div class="text-xs text-slate-500">Creator Net: ৳{{ number_format($dispPayout, 2) }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                                <div class="p-3.5 rounded-xl bg-white border border-slate-200 space-y-2">
                                    <div class="font-bold text-[#121826] flex items-center gap-1.5">
                                        <i class="bi bi-person-fill text-indigo-600"></i> Client (Buyer): {{ $dispOrd->buyer->name ?? 'Client' }}
                                    </div>
                                    <div class="text-slate-500 font-mono">{{ $dispOrd->buyer->email ?? '' }}</div>
                                    <div class="text-slate-600 pt-1 border-t border-slate-100">
                                        <strong>Requirements Brief:</strong> {{ Str::limit($dispOrd->requirements_brief ?? 'Standard service brief submitted.', 200) }}
                                    </div>
                                </div>

                                <div class="p-3.5 rounded-xl bg-white border border-slate-200 space-y-2">
                                    <div class="font-bold text-[#121826] flex items-center gap-1.5">
                                        <i class="bi bi-brush-fill text-[#f47402]"></i> Creator: {{ $dispOrd->seller->name ?? 'Creator' }}
                                    </div>
                                    <div class="text-slate-500 font-mono">{{ $dispOrd->seller->email ?? '' }} · Demerits: {{ $dispOrd->seller->demerit_points ?? 0 }} pts</div>
                                    <div class="text-slate-600 pt-1 border-t border-slate-100">
                                        <strong>Delivery Files:</strong>
                                        @if($dispOrd->delivery_manifest && is_array($dispOrd->delivery_manifest))
                                            {{ count($dispOrd->delivery_manifest) }} files recorded in manifest
                                        @else
                                            Standard delivery package recorded
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- 1-Click Resolution Toolbar --}}
                            <div class="pt-2 flex items-center justify-end gap-3 flex-wrap border-t border-rose-200/60">
                                <a href="{{ route('inbox.show', $dispOrd->conversation_id) }}" target="_blank" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition">
                                    💬 Open Full Chat Conversation
                                </a>

                                <button type="button" onclick="openDisputeModal({{ $dispOrd->id }}, '{{ $dispOrd->order_code }}', {{ $dispGross }}, {{ $dispPayout }}, '{{ addslashes($dispOrd->buyer->name ?? 'Buyer') }}', '{{ addslashes($dispOrd->seller->name ?? 'Creator') }}')" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#20165c] hover:bg-[#181045] text-white shadow-xs transition cursor-pointer">
                                    ⚖️ Issue Binding Decision
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-200 text-slate-500">
                    <i class="bi bi-shield-check text-3xl text-emerald-500 d-block mb-2"></i>
                    <h5 class="font-bold text-[#121826] mb-1">No Active Escrow Disputes</h5>
                    <p class="text-xs text-slate-400 mb-0">All ongoing deliveries are progressing smoothly without reported empty/fake files.</p>
                </div>
            @endif
        </div>

        {{-- Section 3B: Verified Complaint Reviews & Evidence Inspector --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4">
                <div>
                    <h3 class="text-base font-bold text-[#121826] flex items-center gap-2 mb-0.5">
                        <i class="bi bi-images text-[#20165c]"></i>
                        <span>Verified Complaint Reviews & Artifact Evidence ({{ $complaintReviews->count() }})</span>
                    </h3>
                    <p class="text-xs text-slate-500 mb-0">Customer reviews flagged with complaints. Inspect attached evidence images and resolve.</p>
                </div>
            </div>

            @if($complaintReviews->count() > 0)
                <div class="grid grid-cols-1 gap-4">
                    @foreach($complaintReviews as $cRev)
                        @php
                            $evidenceImgs = $cRev->evidence_image_urls ?? [];
                        @endphp
                        <div class="p-4 rounded-2xl border border-slate-200 bg-white shadow-xs space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-900 text-xs">{{ $cRev->reviewer->name ?? 'Buyer' }}</span>
                                    <span class="text-slate-400 text-xs">reviewed</span>
                                    <span class="font-bold text-[#20165c] text-xs">{{ $cRev->creator->name ?? 'Creator' }}</span>
                                    @if($cRev->admin_resolved)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            ✓ Resolved
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 animate-pulse">
                                            ⚠️ Pending Moderation
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="text-amber-500 text-xs font-bold">
                                        ★ {{ $cRev->rating }}/5
                                    </div>
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $cRev->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <p class="text-xs text-slate-700 mb-0 p-3 bg-slate-50 rounded-xl border border-slate-200 font-normal">
                                "{{ $cRev->comment }}"
                            </p>

                            {{-- Evidence Gallery --}}
                            @if(!empty($evidenceImgs) && count($evidenceImgs) > 0)
                                <div>
                                    <div class="text-[11px] font-bold text-slate-600 mb-1.5 flex items-center gap-1">
                                        <i class="bi bi-camera-fill text-[#20165c]"></i> Attached Evidence Proof ({{ count($evidenceImgs) }}):
                                    </div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @foreach($evidenceImgs as $idx => $eUrl)
                                            <button type="button" onclick="previewHubEvidence('{{ $eUrl }}', 'Evidence Image {{ $idx + 1 }}')" class="p-0 border border-slate-200 rounded-xl overflow-hidden hover:scale-105 transition shadow-xs cursor-pointer" style="width: 72px; height: 72px;">
                                                <img src="{{ $eUrl }}" alt="Evidence {{ $idx + 1 }}" class="w-full h-full object-cover">
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Resolution control --}}
                            @if(!$cRev->admin_resolved)
                                <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                                    <form action="{{ route('admin.services-hub.resolve-review', $cRev->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="dismiss_complaint" value="1">
                                        <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 transition">
                                            Dismiss Complaint Flag
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.services-hub.resolve-review', $cRev->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 shadow-xs transition">
                                            ✓ Mark Resolved
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-200 text-slate-500">
                    <i class="bi bi-chat-heart text-3xl text-emerald-500 d-block mb-2"></i>
                    <h5 class="font-bold text-[#121826] mb-1">No Complaint Reviews Logged</h5>
                    <p class="text-xs text-slate-400 mb-0">Customer satisfaction across services and marketplace items is in good standing.</p>
                </div>
            @endif
        </div>

    </div>

</div>

<!-- ======================================================== -->
<!-- MODAL: BINDING ARBITRATION DISPUTE RESOLVER              -->
<!-- ======================================================== -->
<div id="arbitrationModal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm hidden" onclick="if(event.target === this) closeDisputeModal()">
    <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 flex items-center justify-center font-bold text-xs">
                    ⚖️
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#121826] mb-0">Binding Arbitration Decision</h3>
                    <p class="text-[11px] text-slate-400 mb-0 font-mono" id="arbModalOrderCode">#NOK-SRV-XXXXXX</p>
                </div>
            </div>
            <button type="button" onclick="closeDisputeModal()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:text-slate-900 flex items-center justify-center text-sm cursor-pointer">&times;</button>
        </div>

        <form id="arbitrationForm" action="" method="POST" class="p-6 space-y-4">
            @csrf

            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-1.5">
                <div class="flex justify-between">
                    <span class="text-slate-500">Client (Buyer):</span>
                    <strong class="text-slate-800" id="arbModalBuyerName">-</strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Creator (Seller):</span>
                    <strong class="text-slate-800" id="arbModalSellerName">-</strong>
                </div>
                <div class="flex justify-between pt-1 border-t border-slate-200">
                    <span class="text-slate-500">Escrow Held Amount:</span>
                    <strong class="text-[#121826] font-mono text-sm" id="arbModalGrossAmount">৳0.00</strong>
                </div>
            </div>

            {{-- Decision Selection --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Select Resolution Decision <span class="text-rose-500">*</span></label>
                <div class="space-y-2">
                    <label class="p-3 rounded-2xl border border-rose-200 bg-rose-50/40 flex items-start gap-3 cursor-pointer hover:bg-rose-50 transition">
                        <input type="radio" name="resolution" value="refund_buyer" checked class="mt-1 text-rose-600 focus:ring-rose-500">
                        <div>
                            <span class="text-xs font-bold text-rose-900 d-block">🔴 Refund Buyer (100% Wallet Credit)</span>
                            <span class="text-[11px] text-slate-600">Cancel order and credit the full gross escrow amount back to buyer wallet.</span>
                        </div>
                    </label>

                    <label class="p-3 rounded-2xl border border-emerald-200 bg-emerald-50/40 flex items-start gap-3 cursor-pointer hover:bg-emerald-50 transition">
                        <input type="radio" name="resolution" value="release_to_creator" class="mt-1 text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <span class="text-xs font-bold text-emerald-900 d-block">💚 Release to Creator (85% Net / 15% Platform)</span>
                            <span class="text-[11px] text-slate-600">Uphold delivery and credit creator earnings balance, retaining platform fee.</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Demerit Action --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Creator Demerit Handling</label>
                <select name="demerit_action" class="w-full px-3.5 py-2 rounded-xl text-xs bg-white border border-slate-300 text-slate-800 focus:outline-none focus:border-[#20165c]">
                    <option value="no_change">Leave demerits as is (keep existing)</option>
                    <option value="add_25">Penalize Creator (+25 Demerit Points)</option>
                    <option value="clear_demerits">Clear Disputed Demerits (-25 Points, if delivery upheld)</option>
                </select>
            </div>

            {{-- Admin Notes --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Arbitration Notes (Dispatched to both parties)</label>
                <textarea name="admin_notes" rows="2" placeholder="Explain findings (e.g., source files verified intact / incomplete deliverable confirmed)..." class="w-full px-3.5 py-2 rounded-xl text-xs bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-[#20165c] resize-none"></textarea>
            </div>

            <div class="pt-3 flex items-center justify-end gap-2.5 border-t border-slate-200">
                <button type="button" onclick="closeDisputeModal()" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#20165c] hover:bg-[#181045] text-white shadow-xs transition cursor-pointer">
                    Execute Binding Decision
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: 1-CLICK CREATOR INSPECTOR DRAWER                  -->
<!-- ======================================================== -->
<div id="creatorInspectorModal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm hidden" onclick="if(event.target === this) closeCreatorInspector()">
    <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]" onclick="event.stopPropagation()">
        
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#20165c] border border-purple-200 flex items-center justify-center font-bold text-xs">
                    🔍
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#121826] mb-0" id="inspCreatorName">Creator Name</h3>
                    <p class="text-[11px] text-slate-400 mb-0 font-mono" id="inspCreatorEmail">email@example.com</p>
                </div>
            </div>
            <button type="button" onclick="closeCreatorInspector()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:text-slate-900 flex items-center justify-center text-sm cursor-pointer">&times;</button>
        </div>

        <div class="p-6 space-y-5 overflow-y-auto">
            {{-- Demerit & Standing Adjustment Form --}}
            <form id="inspDemeritsForm" action="" method="POST" class="p-4 rounded-2xl bg-amber-50/50 border border-amber-200 space-y-3">
                @csrf
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                        <i class="bi bi-shield-slash-fill text-amber-600"></i> Demerits & Reliability Standing
                    </span>
                    <a id="inspProfileLink" href="#" target="_blank" class="text-xs font-semibold text-[#20165c] hover:underline">
                        View Public Profile →
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Demerit Points</label>
                        <input type="number" name="demerit_points" id="inspDemeritsInput" min="0" max="500" value="0" class="w-full px-3 py-1.5 rounded-xl text-xs bg-white border border-slate-300 text-slate-800 font-bold">
                    </div>
                    <div class="flex items-end pb-1.5">
                        <label class="flex items-center gap-2 cursor-pointer mb-0">
                            <input type="checkbox" name="reliability_warning" value="1" id="inspWarningInput" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                            <span class="text-xs font-semibold text-slate-700">Flag Warning Badge</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full py-2 rounded-xl text-xs font-bold bg-[#20165c] hover:bg-[#181045] text-white transition cursor-pointer">
                    Save Demerit Adjustments
                </button>
            </form>
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end">
            <button type="button" onclick="closeCreatorInspector()" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-100 transition cursor-pointer">
                Close
            </button>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: IMAGE PREVIEW LIGHTBOX                            -->
<!-- ======================================================== -->
<div id="hubImagePreviewModal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md hidden" onclick="if(event.target === this) closeHubPreviewModal()">
    <div class="relative max-w-4xl w-full bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-200">
        <div class="p-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h4 id="hubPreviewTitle" class="text-xs font-bold text-slate-800 truncate px-2 mb-0">Evidence Preview</h4>
            <button type="button" onclick="closeHubPreviewModal()" class="w-8 h-8 rounded-full bg-slate-200 hover:bg-slate-300 text-slate-700 flex items-center justify-center cursor-pointer">
                ✕
            </button>
        </div>
        <div class="p-2 bg-slate-950 flex items-center justify-center max-h-[80vh] overflow-hidden">
            <img id="hubPreviewImg" src="" class="max-w-full max-h-[75vh] object-contain rounded-xl" alt="Evidence Enlarged">
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- INTERACTIVE SCRIPTS                                      -->
<!-- ======================================================== -->
<script>
function switchHubTab(tabName, btnEl) {
    document.querySelectorAll('.hub-pane').forEach(el => el.classList.add('hidden'));
    const targetPane = document.getElementById('pane-' + tabName);
    if (targetPane) targetPane.classList.remove('hidden');

    document.querySelectorAll('.hub-tab-btn').forEach(btn => {
        btn.classList.remove('bg-[#20165c]', 'text-white', 'shadow-sm');
        btn.classList.add('text-slate-600', 'hover:text-[#20165c]', 'hover:bg-slate-50');
        const badge = btn.querySelector('span:last-child');
        if (badge) {
            badge.classList.remove('bg-white/20', 'text-white');
            badge.classList.add('bg-slate-100', 'text-slate-600');
        }
    });

    if (btnEl) {
        btnEl.classList.remove('text-slate-600', 'hover:text-[#20165c]', 'hover:bg-slate-50');
        btnEl.classList.add('bg-[#20165c]', 'text-white', 'shadow-sm');
        const badge = btnEl.querySelector('span:last-child');
        if (badge) {
            badge.classList.remove('bg-slate-100', 'text-slate-600');
            badge.classList.add('bg-white/20', 'text-white');
        }
    }
}

function openDisputeModal(orderId, orderCode, grossAmt, payoutAmt, buyerName, sellerName) {
    const modal = document.getElementById('arbitrationModal');
    const form = document.getElementById('arbitrationForm');
    if (!modal || !form) return;

    const baseAction = "{{ route('admin.services-hub.resolve-dispute', ['order' => 'ORDER_ID_PLACEHOLDER']) }}";
    form.action = baseAction.replace('ORDER_ID_PLACEHOLDER', orderId);
    document.getElementById('arbModalOrderCode').textContent = '#' + orderCode;
    document.getElementById('arbModalBuyerName').textContent = buyerName;
    document.getElementById('arbModalSellerName').textContent = sellerName;
    document.getElementById('arbModalGrossAmount').textContent = '৳' + Number(grossAmt).toFixed(2);

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modal.style.display = 'flex';
}

function closeDisputeModal() {
    const modal = document.getElementById('arbitrationModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
    }
}

function openCreatorInspector(creatorId, name, email, demerits, hasWarning, profileUrl) {
    const modal = document.getElementById('creatorInspectorModal');
    const form = document.getElementById('inspDemeritsForm');
    if (!modal || !form) return;

    const baseAction = "{{ route('admin.services-hub.adjust-demerits', ['creator' => 'CREATOR_ID_PLACEHOLDER']) }}";
    form.action = baseAction.replace('CREATOR_ID_PLACEHOLDER', creatorId);
    document.getElementById('inspCreatorName').textContent = name;
    document.getElementById('inspCreatorEmail').textContent = email;
    document.getElementById('inspDemeritsInput').value = demerits;
    document.getElementById('inspWarningInput').checked = !!hasWarning;
    document.getElementById('inspProfileLink').href = profileUrl;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modal.style.display = 'flex';
}

function closeCreatorInspector() {
    const modal = document.getElementById('creatorInspectorModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
    }
}

function previewHubEvidence(url, title) {
    const modal = document.getElementById('hubImagePreviewModal');
    const img = document.getElementById('hubPreviewImg');
    const titleEl = document.getElementById('hubPreviewTitle');
    if (!modal || !img) return;

    img.src = url;
    if (titleEl) titleEl.textContent = title || 'Evidence Preview';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modal.style.display = 'flex';
}

function closeHubPreviewModal() {
    const modal = document.getElementById('hubImagePreviewModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
    }
}
</script>
@endsection
