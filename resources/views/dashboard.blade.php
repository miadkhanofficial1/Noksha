@extends('layouts.app')

@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
    $currentLocale = app()->getLocale();
@endphp

@section('title', __('dashboard.page_title') . ' - Noksha')

@section('content')
<style>
    .dash-metrics-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 640px) {
        .dash-metrics-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (min-width: 1024px) {
        .dash-metrics-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }
    .dash-actions-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 768px) {
        .dash-actions-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    .dash-tabs-nav {
        display: inline-flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
    }
</style>

<div class="min-h-screen bg-white text-[#121826] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-600 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-600 text-sm space-y-1">
                <div class="font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Please correct the errors below:</span>
                </div>
                <ul class="list-disc list-inside text-xs text-rose-600 ml-6 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ========================================== -->
        <!-- 1. USER PROFILE BANNER (Top)               -->
        <!-- ========================================== -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="relative shrink-0">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-12 h-12 rounded-full object-cover border-2 border-[#20165c] shadow-sm" alt="{{ $user->name }}">
                    @else
                        <div class="w-12 h-12 rounded-full bg-[#20165c]/10 border-2 border-[#20165c]/30 text-[#20165c] font-bold flex items-center justify-center text-lg shadow-inner">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    @if($isUserAdmin)
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-[#20165c] rounded-full border-2 border-white flex items-center justify-center" title="Admin">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                        </span>
                    @elseif($user->isApprovedContributor())
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white flex items-center justify-center" title="Verified Contributor">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                        </span>
                    @endif
                </div>

                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h4 class="text-lg font-bold text-[#121826] tracking-tight mb-0">{{ $user->name }}</h4>
                        @if($isUserAdmin)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#20165c]/10 text-[#20165c] border border-[#20165c]/20">
                                {{ $userBadgeLabel }}
                            </span>
                        @elseif($user->isApprovedContributor())
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Verified Contributor</span>
                            </span>
                        @elseif($user->contributor_status === 'pending')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Pending Verification</span>
                            </span>
                        @elseif($user->contributor_status === 'rejected')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                <span>KYC Needs Update</span>
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                Buyer
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 font-mono mt-0.5 mb-0">{{ $user->email }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-xs font-medium text-slate-500 hidden sm:inline-block">Member since {{ $user->created_at->format('M Y') }}</span>

                @if($user->isApprovedContributor())
                    <form action="{{ route('user.switch-mode') }}" method="POST" class="inline-block m-0 p-0">
                        @csrf
                        <input type="hidden" name="mode" value="{{ $user->isSellerMode() ? 'buyer' : 'seller' }}">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold {{ $user->isSellerMode() ? 'bg-[#f47402]/10 text-[#f47402] border border-[#f47402]/30 hover:bg-[#f47402]/20' : 'bg-[#20165c]/10 text-[#20165c] border border-[#20165c]/30 hover:bg-[#20165c]/20' }} transition-all shadow-sm cursor-pointer" title="Toggle between Buyer and Seller modes">
                            <svg class="w-3.5 h-3.5 shrink-0" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <span>{{ $user->isSellerMode() ? 'Switch to Buyer Mode' : 'Switch to Contributor Mode' }}</span>
                        </button>
                    </form>
                @endif

                <a href="{{ route('settings.profile') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:text-[#20165c] bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-all shadow-sm">
                    <svg class="w-4 h-4 shrink-0" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Settings</span>
                </a>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 2. NAVIGATION TABS (Horizontal Pill Bar)   -->
        <!-- ========================================== -->
        <div class="inline-flex p-1.5 bg-slate-50 border border-slate-200 rounded-xl space-x-2 mb-8 overflow-x-auto max-w-full dash-tabs-nav">
            <button type="button" onclick="switchDashboardTab('pane-overview', this)" class="dash-tab-btn active bg-[#20165c] text-white font-medium shadow-sm px-4 py-2 rounded-lg text-sm flex items-center gap-2 cursor-pointer transition-all shrink-0" data-tab="pane-overview">
                <svg class="w-4 h-4 shrink-0" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Overview</span>
            </button>
            <button type="button" onclick="switchDashboardTab('pane-orders', this)" class="dash-tab-btn text-slate-600 hover:text-[#20165c] hover:bg-slate-100 px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2 cursor-pointer shrink-0" data-tab="pane-orders">
                <svg class="w-4 h-4 shrink-0 text-[#20165c]" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>My Orders / Invoices</span>
                @if(isset($ordersCount) && $ordersCount > 0)
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-semibold bg-slate-200 text-slate-700">{{ $ordersCount }}</span>
                @endif
            </button>
            <button type="button" onclick="switchDashboardTab('pane-downloads', this)" class="dash-tab-btn text-slate-600 hover:text-[#20165c] hover:bg-slate-100 px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2 cursor-pointer shrink-0" data-tab="pane-downloads">
                <svg class="w-4 h-4 shrink-0 text-[#20165c]" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>My Downloads</span>
                @if(isset($downloadsCount) && $downloadsCount > 0)
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-semibold bg-slate-200 text-slate-700">{{ $downloadsCount }}</span>
                @endif
            </button>
            <button type="button" onclick="switchDashboardTab('pane-wishlist', this)" class="dash-tab-btn text-slate-600 hover:text-[#20165c] hover:bg-slate-100 px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2 cursor-pointer shrink-0" data-tab="pane-wishlist">
                <svg class="w-4 h-4 shrink-0 text-rose-500" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span>Wishlist</span>
                @if(isset($wishlistCount) && $wishlistCount > 0)
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-semibold bg-slate-200 text-slate-700">{{ $wishlistCount }}</span>
                @endif
            </button>

            @if($user->isApprovedContributor() && $user->isSellerMode())
                <button type="button" onclick="switchDashboardTab('pane-services', this)" class="dash-tab-btn text-slate-600 hover:text-[#20165c] hover:bg-slate-100 px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2 cursor-pointer shrink-0" data-tab="pane-services">
                    <svg class="w-4 h-4 shrink-0 text-[#20165c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>My Services</span>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-[#20165c]">{{ $creatorServicesCount ?? 0 }}/5</span>
                </button>

                <button type="button" onclick="switchDashboardTab('pane-upload', this)" class="dash-tab-btn text-slate-600 hover:text-[#20165c] hover:bg-slate-100 px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2 cursor-pointer shrink-0" data-tab="pane-upload">
                    <svg class="w-4 h-4 shrink-0 text-[#f47402]" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Upload Asset</span>
                </button>
            @elseif(!$user->isApprovedContributor() && $user->contributor_status === 'pending')
                <button type="button" onclick="switchDashboardTab('pane-upload', this)" class="dash-tab-btn text-slate-600 hover:text-[#20165c] hover:bg-slate-100 px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2 cursor-pointer shrink-0" data-tab="pane-upload">
                    <svg class="w-4 h-4 shrink-0 text-[#f47402]" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>KYC Pending</span>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Review</span>
                </button>
            @elseif(!$user->isApprovedContributor() && $user->contributor_status === 'rejected')
                <button type="button" onclick="switchDashboardTab('pane-upload', this)" class="dash-tab-btn text-slate-600 hover:text-[#20165c] hover:bg-slate-100 px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2 cursor-pointer shrink-0" data-tab="pane-upload">
                    <svg class="w-4 h-4 shrink-0 text-rose-500" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Contributor KYC</span>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Action Needed</span>
                </button>
            @elseif(!$user->isApprovedContributor())
                <button type="button" onclick="switchDashboardTab('pane-upload', this)" class="dash-tab-btn text-slate-600 hover:text-[#20165c] hover:bg-slate-100 px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2 cursor-pointer shrink-0" data-tab="pane-upload">
                    <svg class="w-4 h-4 shrink-0 text-[#20165c]" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Become a Contributor</span>
                </button>
            @endif
        </div>

        <!-- ========================================== -->
        <!-- PANE 1: OVERVIEW PANE                      -->
        <!-- ========================================== -->
        <div id="pane-overview" class="dash-content-pane space-y-8">

            <!-- BENTO-GRID MODULAR DASHBOARD SUITE -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- CARD 1 (Span 7 on lg): Available Wallet & Escrow Balance -->
                <div class="lg:col-span-7 bg-gradient-to-br from-[#20165c] via-[#281c74] to-[#181045] text-white rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-48 h-48 bg-[#f47402]/15 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -left-8 -top-8 w-40 h-40 bg-purple-400/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="relative z-10">
                        <div class="flex items-center justify-between gap-3 flex-wrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-white border border-white/15 backdrop-blur-md">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Secured Escrow Wallet</span>
                            </span>
                            <span class="text-xs font-mono text-white/70">Central BDT Account</span>
                        </div>

                        <div class="my-5">
                            <div class="text-xs font-medium uppercase tracking-wider text-white/70 mb-1">Available Escrow Balance</div>
                            <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight flex items-baseline gap-2">
                                <span>৳{{ number_format(auth()->user()->wallet?->balance ?? 0, 2) }}</span>
                                <span class="text-sm font-semibold text-white/70 font-sans">BDT</span>
                            </div>
                            @if($user->isApprovedContributor() && $user->isSellerMode())
                                <div class="mt-2 text-xs text-white/80 flex items-center gap-2">
                                    <span>Royalty Earnings: <strong class="text-[#f47402]">৳{{ number_format($user->earnings_balance ?? 0, 2) }}</strong></span>
                                    <span>•</span>
                                    <span class="text-emerald-400">50% Platform Share</span>
                                </div>
                            @else
                                <div class="mt-2 text-xs text-white/70">
                                    Protected by Noksha buyer escrow guarantee for all digital asset purchases.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="relative z-10 pt-4 border-t border-white/10 flex items-center gap-3 flex-wrap">
                        <a href="{{ route('wallet.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#f47402] hover:bg-[#d96602] text-white font-bold text-xs shadow-md transition-all">
                            <i class="bi bi-plus-circle-fill"></i>
                            <span>Add Funds / Deposit</span>
                        </a>

                        @if($user->isApprovedContributor() && $user->isSellerMode())
                            <a href="{{ route('seller.payouts') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/20 transition-all">
                                <i class="bi bi-cash-stack"></i>
                                <span>Request Payout</span>
                            </a>
                        @endif

                        <a href="{{ route('wallet.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/20 transition-all">
                            <i class="bi bi-clock-history"></i>
                            <span>Transaction History</span>
                        </a>
                    </div>
                </div>

                <!-- CARD 2 (Span 5 on lg): Active Project & Service Orders / Milestones -->
                <div class="lg:col-span-5 bg-white border border-slate-200 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-xl bg-purple-50 border border-purple-200 text-[#20165c] flex items-center justify-center">
                                    <i class="bi bi-kanban small"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-[#121826] mb-0">Project Orders & Milestones</h4>
                                    <span class="text-[11px] text-slate-500">Live escrow order tracking</span>
                                </div>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 font-mono">
                                {{ number_format($ordersCount ?? $orders?->count() ?? 0) }} Orders
                            </span>
                        </div>

                        <!-- Orders List or Empty State -->
                        <div class="my-3 space-y-2.5">
                            @if(isset($orders) && $orders->count() > 0)
                                @foreach($orders->take(2) as $ord)
                                    @php
                                        $firstItem = $ord->items->first();
                                        $resource = $firstItem?->resource;
                                    @endphp
                                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3 hover:bg-slate-100/70 transition">
                                        <div class="min-w-0">
                                            <a href="{{ route('orders.show', $ord) }}" class="font-semibold text-xs text-[#121826] hover:text-[#20165c] truncate block">
                                                {{ $resource?->title ?? 'Digital Asset Order' }}
                                            </a>
                                            <div class="text-[11px] text-slate-500 font-mono mt-0.5">
                                                #{{ $ord->order_number ?? $ord->id }} • {{ $ord->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="font-bold text-[#f47402] text-xs font-mono block">৳{{ number_format($ord->total, 2) }}</span>
                                            <span class="inline-flex items-center gap-1 text-[10px] text-emerald-600 font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Delivered
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="py-6 text-center text-slate-400">
                                    <i class="bi bi-inbox fs-3 d-block mb-1 opacity-50"></i>
                                    <p class="text-xs mb-0">No active custom orders currently pending.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <button type="button" onclick="switchDashboardTab('pane-orders')" class="w-full mt-2 py-2.5 rounded-xl text-xs font-semibold text-[#20165c] bg-purple-50 hover:bg-purple-100 border border-purple-200 transition text-center cursor-pointer">
                        View All Orders & Invoices →
                    </button>
                </div>

                <!-- CARD 3 (Span 5 on lg): Download Library / Creator Performance & KYC -->
                <div class="lg:col-span-5 bg-white border border-slate-200 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center">
                                    <i class="bi bi-cloud-arrow-down-fill small"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-[#121826] mb-0">
                                        @if($user->isApprovedContributor() && $user->isSellerMode())
                                            Creator Studio & KYC
                                        @else
                                            Asset Downloads Library
                                        @endif
                                    </h4>
                                    <span class="text-[11px] text-slate-500">
                                        @if($user->isApprovedContributor() && $user->isSellerMode())
                                            Approved portfolio metrics
                                        @else
                                            Instant cloud file access
                                        @endif
                                    </span>
                                </div>
                            </div>
                            @if($user->isApprovedContributor())
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    KYC Verified
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-[#20165c] border border-purple-200">
                                    Instant License
                                </span>
                            @endif
                        </div>

                        <!-- Mini metrics -->
                        <div class="grid grid-cols-2 gap-3 my-4">
                            @if($user->isApprovedContributor() && $user->isSellerMode())
                                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                                    <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider block">Live Assets</span>
                                    <span class="text-2xl font-black text-[#121826] mt-1 block">
                                        {{ number_format($totalResources ?? $contributorResources?->count() ?? 0) }}
                                    </span>
                                </div>
                                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                                    <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider block">Downloads</span>
                                    <span class="text-2xl font-black text-[#20165c] mt-1 block">
                                        {{ number_format($totalDownloads ?? 0) }}
                                    </span>
                                </div>
                            @else
                                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                                    <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider block">Licensed Files</span>
                                    <span class="text-2xl font-black text-[#121826] mt-1 block">
                                        {{ number_format($downloadsCount ?? 0) }}
                                    </span>
                                </div>
                                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                                    <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider block">Saved Wishlist</span>
                                    <span class="text-2xl font-black text-rose-600 mt-1 block">
                                        {{ number_format($wishlistCount ?? 0) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($user->isApprovedContributor() && $user->isSellerMode())
                        <button type="button" onclick="switchDashboardTab('pane-upload')" class="w-full py-2.5 rounded-xl text-xs font-bold text-white bg-[#20165c] hover:bg-[#181045] transition shadow-sm text-center cursor-pointer">
                            + Upload New Creative Asset
                        </button>
                    @else
                        <button type="button" onclick="switchDashboardTab('pane-downloads')" class="w-full py-2.5 rounded-xl text-xs font-bold text-white bg-[#20165c] hover:bg-[#181045] transition shadow-sm text-center cursor-pointer">
                            Open Downloads Vault
                        </button>
                    @endif
                </div>

                <!-- CARD 4 (Span 7 on lg): Recent Transaction Ledger Table -->
                <div class="lg:col-span-7 bg-white border border-slate-200 rounded-3xl p-6 shadow-sm overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h4 class="text-sm font-bold text-[#121826] mb-0 flex items-center gap-1.5">
                                    <i class="bi bi-receipt text-[#20165c]"></i>
                                    <span>Recent Transaction Ledger</span>
                                </h4>
                                <span class="text-[11px] text-slate-500">Audit records of escrow transactions & licenses</span>
                            </div>
                            <button type="button" onclick="switchDashboardTab('pane-orders')" class="text-xs font-semibold text-[#20165c] hover:underline cursor-pointer">
                                All History
                            </button>
                        </div>

                        @if(isset($orders) && $orders->count() > 0)
                            <div class="overflow-x-auto mt-3">
                                <table class="w-full text-left text-xs text-slate-700">
                                    <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                                        <tr>
                                            <th class="py-2.5 px-3">Asset</th>
                                            <th class="py-2.5 px-3">Date</th>
                                            <th class="py-2.5 px-3">Amount</th>
                                            <th class="py-2.5 px-3 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($orders->take(4) as $order)
                                            @php
                                                $firstItem = $order->items->first();
                                                $resource = $firstItem?->resource;
                                            @endphp
                                            <tr class="hover:bg-slate-50/70 transition-colors">
                                                <td class="py-2.5 px-3">
                                                    <div class="flex items-center gap-2 max-w-[200px]">
                                                        @if($resource && $resource->preview_image)
                                                            <img src="{{ asset('storage/' . $resource->preview_image) }}" class="w-7 h-7 rounded-lg object-cover border border-slate-200 shrink-0" alt="">
                                                        @else
                                                            <div class="w-7 h-7 rounded-lg bg-purple-50 text-[#20165c] flex items-center justify-center shrink-0">
                                                                <i class="bi bi-file-earmark text-[11px]"></i>
                                                            </div>
                                                        @endif
                                                        <span class="font-semibold text-slate-900 truncate">{{ $resource?->title ?? 'Design Resource' }}</span>
                                                    </div>
                                                </td>
                                                <td class="py-2.5 px-3 font-mono text-slate-500 text-[11px]">
                                                    {{ $order->created_at->format('M d, Y') }}
                                                </td>
                                                <td class="py-2.5 px-3 font-bold font-mono text-[#f47402]">
                                                    ৳{{ number_format($order->total, 2) }}
                                                </td>
                                                <td class="py-2.5 px-3 text-right">
                                                    <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center px-2 py-1 rounded-md text-[11px] font-semibold text-slate-600 hover:text-[#20165c] hover:bg-slate-100 border border-slate-200 transition">
                                                        Receipt
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="py-8 text-center text-slate-400">
                                <i class="bi bi-clock-history fs-3 d-block mb-1 opacity-50"></i>
                                <p class="text-xs mb-0">No past transactions recorded yet.</p>
                            </div>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 mt-2">
                        <span>Showing {{ min(4, isset($orders) ? $orders->count() : 0) }} most recent ledger entries</span>
                        <a href="{{ route('wallet.index') }}" class="text-[#20165c] font-semibold hover:underline">Wallet Statement →</a>
                    </div>
                </div>

            </div>
        </div>

        <!-- ========================================== -->
        <!-- PANE 2: MY ORDERS / INVOICES PANE          -->
        <!-- ========================================== -->
        <div id="pane-orders" class="dash-content-pane d-none space-y-8">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4">
                    <h3 class="text-base font-bold text-[#121826] flex items-center gap-2 mb-0">
                        <svg class="w-5 h-5 shrink-0 text-[#20165c]" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>My Orders & Invoices ({{ isset($orders) ? $orders->count() : 0 }})</span>
                    </h3>
                </div>

                @if(isset($orders) && $orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-700">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-600 border-b border-slate-200">
                                <tr>
                                    <th scope="col" class="py-3 px-4">Order #</th>
                                    <th scope="col" class="py-3 px-4">Items</th>
                                    <th scope="col" class="py-3 px-4">Date</th>
                                    <th scope="col" class="py-3 px-4">Amount</th>
                                    <th scope="col" class="py-3 px-4">Status</th>
                                    <th scope="col" class="py-3 px-4 text-right">Invoice</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($orders as $ord)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="py-3.5 px-4 font-mono font-bold text-[#20165c] text-xs">
                                            #{{ $ord->order_number ?? $ord->id }}
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @foreach($ord->items as $it)
                                                <div class="text-[#121826] text-xs truncate max-w-xs font-medium">
                                                    {{ $it->resource?->title ?? 'Design Template' }}
                                                </div>
                                            @endforeach
                                        </td>
                                        <td class="py-3.5 px-4 text-xs text-slate-500 font-mono">
                                            {{ $ord->created_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="py-3.5 px-4 font-mono font-bold text-[#f47402] text-sm">
                                            ৳{{ number_format($ord->total, 2) }}
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span> Paid
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-right">
                                            <a href="{{ route('orders.show', $ord) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold text-slate-700 hover:text-[#20165c] bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-all shrink-0">
                                                <svg class="w-3.5 h-3.5 shrink-0 text-[#20165c]" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <span>View Order</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-10 h-10 text-slate-400 mx-auto mb-2 shrink-0" width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm font-semibold text-slate-600">No orders recorded yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- PANE 3: MY DOWNLOADS PANE                  -->
        <!-- ========================================== -->
        <div id="pane-downloads" class="dash-content-pane d-none space-y-8">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4">
                    <h3 class="text-base font-bold text-[#121826] flex items-center gap-2 mb-0">
                        <svg class="w-5 h-5 shrink-0 text-[#20165c]" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Purchased Digital Downloads</span>
                    </h3>
                </div>

                @if(isset($orderDownloads) && $orderDownloads->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($orderDownloads as $dl)
                            <div class="bg-white border border-slate-200 rounded-xl p-4 flex flex-col justify-between hover:border-slate-300 shadow-sm transition-all">
                                <div>
                                    <div class="w-full h-36 rounded-lg overflow-hidden bg-slate-50 mb-3 border border-slate-200">
                                        @if($dl->preview_image)
                                            <img src="{{ asset('storage/' . $dl->preview_image) }}" class="w-full h-full object-cover" alt="{{ $dl->title }}" onerror="this.src='{{ asset('images/logo.png') }}'">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                <svg class="w-8 h-8 shrink-0" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <h5 class="text-sm font-bold text-[#121826] truncate mb-1">{{ $dl->title }}</h5>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ $dl->category?->name ?? 'Digital Template' }}
                                    </span>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-xs text-slate-500 font-mono">{{ strtoupper($dl->file_type ?? 'ZIP') }}</span>
                                    <a href="{{ route('resource.show', $dl->slug ?? $dl->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#20165c] hover:bg-[#181045] shadow-sm transition-all shrink-0">
                                        <svg class="w-3.5 h-3.5 shrink-0" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        <span>Download</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-10 h-10 text-slate-400 mx-auto mb-2 shrink-0" width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <p class="text-sm font-semibold text-slate-600">No downloaded items available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- PANE 4: WISHLIST PANE                      -->
        <!-- ========================================== -->
        <div id="pane-wishlist" class="dash-content-pane d-none space-y-8">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4">
                    <h3 class="text-base font-bold text-[#121826] flex items-center gap-2 mb-0">
                        <svg class="w-5 h-5 shrink-0 text-rose-500" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span>Saved Wishlist Templates</span>
                    </h3>
                </div>

                @if(isset($wishlistItems) && $wishlistItems->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($wishlistItems as $wl)
                            <div class="bg-white border border-slate-200 rounded-xl p-4 flex flex-col justify-between hover:border-slate-300 shadow-sm transition-all">
                                <div>
                                    <div class="w-full h-36 rounded-lg overflow-hidden bg-slate-50 mb-3 border border-slate-200">
                                        @if($wl->preview_image)
                                            <img src="{{ asset('storage/' . $wl->preview_image) }}" class="w-full h-full object-cover" alt="{{ $wl->title }}" onerror="this.src='{{ asset('images/logo.png') }}'">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                <svg class="w-8 h-8 shrink-0" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <h5 class="text-sm font-bold text-[#121826] truncate mb-1">{{ $wl->title }}</h5>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">
                                            {{ $wl->category?->name ?? 'Template' }}
                                        </span>
                                        <span class="font-bold text-[#f47402] text-sm font-mono">
                                            @if($wl->is_paid) ৳{{ number_format($wl->price, 2) }} @else Free @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-end">
                                    <a href="{{ route('resource.show', $wl->slug ?? $wl->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#20165c] hover:bg-[#181045] shadow-sm transition-all shrink-0">
                                        <svg class="w-3.5 h-3.5 shrink-0" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>View Details</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-10 h-10 text-slate-400 mx-auto mb-2 shrink-0" width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <p class="text-sm font-semibold text-slate-600">Your wishlist is currently empty</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- PANE: MY SERVICES (CREATOR STUDIO)         -->
        <!-- ========================================== -->
        <div id="pane-services" class="dash-content-pane d-none space-y-8">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <!-- Header -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-6 flex-wrap gap-4">
                    <div>
                        <h3 class="text-base font-bold text-[#121826] flex items-center gap-2 mb-0">
                            <svg class="w-5 h-5 shrink-0 text-[#20165c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>Creator Service Studio — My Offerings</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-1 mb-0">Offer bespoke design packages, take custom client orders, and earn protected escrow milestones</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold {{ ($creatorServicesCount ?? 0) >= 5 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-purple-50 text-[#20165c] border border-purple-200' }}">
                            Services Used: {{ $creatorServicesCount ?? 0 }} / 5
                        </span>

                        @if(($creatorServicesCount ?? 0) < 5)
                            <button type="button" onclick="openCreateServiceModal()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#20165c] hover:bg-[#181045] shadow-xs transition cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>+ Add New Service</span>
                            </button>
                        @else
                            <button type="button" disabled class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed" title="Limit reached (5/5). Edit or delete an existing service to add a new one.">
                                <span>Max Limit Reached (5/5)</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Services Grid -->
                @if(isset($creatorServices) && $creatorServices->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($creatorServices as $service)
                            <x-service-card :service="$service" />
                        @endforeach
                    </div>
                @else
                    <div class="border border-dashed border-slate-300 rounded-2xl p-10 text-center bg-slate-50">
                        <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-purple-50 text-[#20165c] flex items-center justify-center text-xl shadow-xs">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-[#121826] mb-1">No Services Created Yet</h4>
                        <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                            You can publish up to 5 custom design services (e.g. Logo Design, UI Kit, Mockups) and receive inquiries directly in your inbox.
                        </p>
                        <button type="button" onclick="openCreateServiceModal()" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-[#20165c] hover:bg-[#181045] shadow-sm transition cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Create Your First Service</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- PANE 5: UPLOAD ASSET / CONTRIBUTOR KYC PANE -->
        <!-- ========================================== -->
        <div id="pane-upload" class="dash-content-pane d-none space-y-8">

            @if($user->isApprovedContributor())
                {{-- STATE 1: APPROVED CONTRIBUTOR (CREATOR STUDIO) --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-6 flex-wrap gap-4">
                        <div>
                            <h3 class="text-base font-bold text-[#121826] flex items-center gap-2 mb-0">
                                <svg class="w-5 h-5 shrink-0 text-[#f47402]" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span>Creator Studio - Upload Creative Asset</span>
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 mb-0">Publish vectors, mockups, Figma kits, or PSD graphics to start earning creator royalties</p>
                        </div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <button type="button" onclick="openUploadGuidelinesModal()" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-[#20165c]/10 text-[#20165c] border border-[#20165c]/30 hover:bg-[#20165c]/20 hover:border-[#20165c]/50 transition-all cursor-pointer shadow-sm">
                                <svg class="w-4 h-4 shrink-0 text-[#20165c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <span>Upload Guidelines</span>
                            </button>
                            @if(!$user->isSellerMode())
                                <form action="{{ route('user.switch-mode') }}" method="POST" class="m-0 p-0">
                                    @csrf
                                    <input type="hidden" name="mode" value="seller">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#f47402]/10 text-[#f47402] border border-[#f47402]/30 hover:bg-[#f47402]/20 transition-all cursor-pointer">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        <span>Switch to Contributor Mode</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    @include('dashboard.upload')
                </div>

            @elseif($user->contributor_status === 'pending')
                {{-- STATE 2: KYC APPLICATION UNDER REVIEW --}}
                <div class="bg-white border border-amber-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <div class="max-w-2xl mx-auto text-center space-y-4">
                        <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-[#f47402] mx-auto flex items-center justify-center shadow-sm">
                            <svg class="w-8 h-8 shrink-0" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>

                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-50 text-[#f47402] border border-amber-200 mb-2">
                                <span class="w-2 h-2 rounded-full bg-[#f47402] animate-pulse"></span>
                                Verification In Review
                            </span>
                            <h3 class="text-xl sm:text-2xl font-extrabold text-[#121826] tracking-tight">Your Contributor KYC Application is Under Review</h3>
                            <p class="text-sm text-slate-500 mt-2">
                                Thank you for applying to become a Noksha Contributor! Our verification team is currently reviewing your identity document and portfolio. You will be able to upload creative assets and monetize your work once approved.
                            </p>
                        </div>

                        <!-- Submitted Details Box -->
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-left space-y-3 mt-6">
                            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 pb-2">
                                Submitted Application Details
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                <div>
                                    <span class="text-slate-500 block">Applicant Name:</span>
                                    <span class="text-slate-900 font-medium">{{ $user->name }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">National ID / Passport:</span>
                                    <span class="text-slate-900 font-mono font-medium">{{ $user->nid_or_passport_number ?? 'Submitted' }}</span>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="text-slate-500 block">Portfolio Link:</span>
                                    @if($user->portfolio_link)
                                        <a href="{{ $user->portfolio_link }}" target="_blank" rel="noopener noreferrer" class="text-[#20165c] hover:underline break-all font-mono font-medium">
                                            {{ $user->portfolio_link }}
                                        </a>
                                    @else
                                        <span class="text-slate-500">Under review</span>
                                    @endif
                                </div>
                                @if($user->kyc_document_path)
                                    <div class="sm:col-span-2">
                                        <span class="text-slate-500 block">ID Document:</span>
                                        <a href="{{ asset('storage/' . $user->kyc_document_path) }}" target="_blank" download class="inline-flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 font-semibold mt-0.5">
                                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span>Document Attached</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="pt-2 flex items-center justify-center gap-2 text-xs text-slate-500">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Typical review timeframe is within 24 to 48 business hours.</span>
                        </div>
                    </div>
                </div>

            @else
                {{-- STATE 3 & 4: REJECTED OR NONE (KYC ONBOARDING & APPLICATION FORM) --}}
                <div class="space-y-6">

                    @if($user->contributor_status === 'rejected')
                        <!-- REJECTION ALERT -->
                        <div class="p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-sm font-bold text-rose-900 mb-0">Contributor KYC Application Needs Update</h4>
                                    <p class="text-xs text-rose-700 leading-relaxed mb-0">
                                        <span class="font-semibold text-rose-800">Admin Feedback:</span>
                                        {{ $user->kyc_rejection_reason ?? 'Your previous application could not be verified with the provided documents or portfolio.' }}
                                    </p>
                                    <p class="text-xs text-slate-500 pt-1 mb-0">Please review the reason above and resubmit your updated verification application below.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- CONTRIBUTOR ONBOARDING HERO BANNER -->
                    <div class="relative overflow-hidden bg-gradient-to-r from-[#20165c] to-[#2c1e7a] border border-[#20165c] rounded-2xl p-6 sm:p-8 shadow-md text-white">
                        <div class="max-w-2xl space-y-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-white border border-white/20">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span>Monetize Your Creative Work</span>
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Become a Verified Noksha Contributor</h2>
                            <p class="text-sm text-slate-200 leading-relaxed">
                                Join our creator community to sell vectors, Figma templates, mockups, and UI assets to thousands of designers. Enjoy competitive 50% royalties and instant creator payouts.
                            </p>
                        </div>

                        <!-- 3 Benefits Highlights -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6 pt-6 border-t border-white/15">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-white/10 border border-white/20 text-[#f47402] flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-white block">50% Creator Royalties</span>
                                    <span class="text-[11px] text-slate-700 block">Earn on every sale</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-white/10 border border-white/20 text-emerald-400 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-white block">Fast Direct Payouts</span>
                                    <span class="text-[11px] text-slate-700 block">bKash, Nagad & Bank</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-white/10 border border-white/20 text-indigo-300 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-white block">Verified Author Badge</span>
                                    <span class="text-[11px] text-slate-700 block">Enhanced buyer trust</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KYC APPLICATION FORM CARD -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                        <div class="pb-5 border-b border-slate-200 mb-6">
                            <h3 class="text-lg font-bold text-[#121826] flex items-center gap-2 mb-0">
                                <svg class="w-5 h-5 shrink-0 text-[#20165c]" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Contributor Identity & Portfolio Verification</span>
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 mb-0">Please fill out your legal details and provide your public design portfolio for review.</p>
                        </div>

                        <form action="{{ route('seller.verification.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                                        Full Legal Name <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="full_name" value="{{ old('full_name', $user->name) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] focus:outline-none" placeholder="Your full name as on ID" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                                        Phone / WhatsApp <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] focus:outline-none" placeholder="+880 1700-000000" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                                        National ID / Passport Number <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="nid_or_passport_number" value="{{ old('nid_or_passport_number', $user->nid_or_passport_number) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] focus:outline-none font-mono" placeholder="e.g. 19941234567890 or A12345678" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                                        Portfolio Link (Behance / Dribbble / Web) <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="url" name="portfolio_link" value="{{ old('portfolio_link', $user->portfolio_link) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] focus:outline-none" placeholder="https://behance.net/yourprofile" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                                    Contributor Bio & Experience
                                </label>
                                <textarea name="contributor_bio" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] focus:outline-none" placeholder="Briefly describe your design background, tools used (Figma, Illustrator, Blender), and the types of creative assets you create...">{{ old('contributor_bio', $user->contributor_bio) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                                    Government ID / Passport Document <span class="text-rose-500">*</span>
                                </label>
                                <input type="file" name="document_file" accept="image/png,image/jpeg,image/webp,image/jpg,application/pdf" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-slate-700 text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#20165c] file:text-white hover:file:bg-[#181045] cursor-pointer" required>
                                <p class="text-xs text-slate-500 mt-1 mb-0">Upload a clear photo or scanned PDF of your National ID, Passport, or Driving License (Max 10MB)</p>
                            </div>

                            <div class="pt-2">
                                <label class="inline-flex items-start gap-2.5 cursor-pointer">
                                    <input type="checkbox" name="agreement" value="1" class="mt-1 rounded bg-white border-slate-300 text-[#20165c] focus:ring-[#20165c]" required>
                                    <span class="text-xs text-slate-600 leading-relaxed">
                                        I certify that the information and documents provided are authentic, and I agree to only upload original designs or properly licensed creative assets in compliance with Noksha Contributor Terms.
                                    </span>
                                </label>
                            </div>

                            <div class="pt-3">
                                <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white font-bold text-sm shadow-md transition-all cursor-pointer">
                                    <svg class="w-4 h-4 shrink-0" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    <span>Submit Contributor KYC Application</span>
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            @endif

        </div>

    </div>
</div>

<script>
function switchDashboardTab(paneId, btnEl) {
    // Hide all panes
    document.querySelectorAll('.dash-content-pane').forEach(function(pane) {
        pane.classList.add('d-none');
    });

    // Remove active styles from tabs
    document.querySelectorAll('.dash-tab-btn').forEach(function(btn) {
        btn.classList.remove('active', 'bg-[#20165c]', 'bg-indigo-600', 'text-white', 'shadow-md', 'shadow-sm');
        btn.classList.add('text-slate-600');
    });

    // Show selected pane
    var target = document.getElementById(paneId);
    if (target) {
        target.classList.remove('d-none');
    }

    // Set active tab styling
    var activeBtn = btnEl || document.querySelector('.dash-tab-btn[data-tab="' + paneId + '"]');
    if (activeBtn) {
        activeBtn.classList.add('active', 'bg-[#20165c]', 'text-white', 'shadow-sm');
        activeBtn.classList.remove('text-slate-600', 'text-slate-400');
    }

    if (history.pushState) {
        history.pushState(null, null, '#' + paneId.replace('pane-', ''));
    }
}

function handleDashImageSelect(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        document.getElementById('dashImageFileName').textContent = file.name;
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('dashImagePreviewImg').src = e.target.result;
            document.getElementById('dashImageDropZone').classList.add('hidden');
            document.getElementById('dashImagePreviewBox').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function removeDashImage() {
    var input = document.getElementById('cover_image_input') || document.getElementById('dash_preview_image');
    if (input) input.value = '';
    var previewBox = document.getElementById('dashImagePreviewBox');
    if (previewBox) previewBox.classList.add('hidden');
    var dropZone = document.getElementById('dashImageDropZone');
    if (dropZone) dropZone.classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    var urlParams = new URLSearchParams(window.location.search);
    var tabParam = urlParams.get('tab') || "{{ $tabParam ?? '' }}";
    var hash = window.location.hash.replace('#', '');
    var activeTab = tabParam || hash;
    if (activeTab) {
        var paneId = 'pane-' + activeTab;
        if (document.getElementById(paneId)) {
            switchDashboardTab(paneId);
        }
    }
});

function getGuidelinesModal() {
    return document.getElementById('nokshaGuidelinesModal') || document.getElementById('guidelinesModal');
}

function openUploadGuidelinesModal() {
    var modal = getGuidelinesModal();
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    } else {
        window.open('{{ route("guidelines.download-pdf") }}', '_blank');
    }
}

function closeUploadGuidelinesModal(e) {
    if (e && e.target && e.currentTarget && e.target !== e.currentTarget) {
        return;
    }
    var modal = getGuidelinesModal();
    if (modal) {
        modal.remove();
    }
    document.body.style.overflow = '';
    try {
        localStorage.setItem('noksha_guidelines_seen', 'true');
    } catch (err) {}
}

// Keyboard 'Escape' & Backdrop Click Dismissal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' || e.keyCode === 27) {
        closeUploadGuidelinesModal();
    }
});

// Ensure modal is removed if seen, or kept hidden initially
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('noksha_guidelines_seen') === 'true') {
        var modal = getGuidelinesModal();
        if (modal) modal.remove();
    } else {
        var modal = getGuidelinesModal();
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }
});
</script>

<!-- ======================================================== -->
<!-- UPLOAD GUIDELINES MODAL POPUP (DARK THEMED)              -->
<!-- ======================================================== -->
<div id="nokshaGuidelinesModal" data-modal="uploadGuidelinesModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden transition-opacity duration-300" style="display: none;" onclick="if(event.target === this) { document.getElementById('nokshaGuidelinesModal').remove(); localStorage.setItem('noksha_guidelines_seen', 'true'); }">
    <div class="relative w-full max-w-2xl bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden my-8 max-h-[90vh] flex flex-col" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#20165c]/10 border border-[#20165c]/20 text-[#20165c] flex items-center justify-center">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-[#121826] mb-0">Noksha Creator Upload Guidelines</h3>
                    <p class="text-xs text-slate-500 mb-0">Follow our quality and licensing rules to ensure rapid asset approval</p>
                </div>
            </div>
            <button type="button" id="closeGuidelinesXBtn" onclick="document.getElementById('nokshaGuidelinesModal').remove(); localStorage.setItem('noksha_guidelines_seen', 'true');" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:text-slate-800 hover:bg-slate-200 flex items-center justify-center transition-colors cursor-pointer" aria-label="Close modal">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="p-6 space-y-4 overflow-y-auto text-sm text-slate-700">
            <!-- Rule 1: Cover Preview -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-[#20165c]/10 text-[#20165c] border border-[#20165c]/20 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white mb-1">1. High-Resolution Cover Preview</h4>
                    <p class="text-xs text-slate-400 leading-relaxed mb-0">
                        Upload a clean, high-resolution preview image in <strong>16:9</strong> or <strong>4:3</strong> aspect ratio. Supported formats are <strong>JPG, PNG, or WEBP</strong> (Max file size: <strong>5MB</strong>). Avoid pixelated imagery, external watermarks, or intrusive text overlays.
                    </p>
                </div>
            </div>

            <!-- Rule 2: Source File Packing -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-[#20165c]/10 text-[#20165c] border border-[#20165c]/20 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white mb-1">2. Clean Source Package (.ZIP)</h4>
                    <p class="text-xs text-slate-400 leading-relaxed mb-0">
                        All downloadable asset packages must strictly be packed into a clean <strong>.ZIP archive</strong> (Max 100MB). Include well-structured raw vector and design files: <strong>Figma (.fig), Adobe Photoshop (.psd), Illustrator (.ai), EPS, SVG</strong>, alongside font license documentation and readme notes.
                    </p>
                </div>
            </div>

            <!-- Rule 3: Category & Discoverability Tags -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white mb-1">3. Relevant Categories & Keyword Tags</h4>
                    <p class="text-xs text-slate-400 leading-relaxed mb-0">
                        Select the most precise marketplace category (UI Kit, Mockups, Templates, Graphics, etc.). Provide at least <strong>3 to 5 comma-separated tags</strong> (e.g., <em>dashboard, dark mode, fintech, responsive</em>) to optimize discoverability on marketplace search engines.
                    </p>
                </div>
            </div>

            <!-- Rule 4: Intellectual Property & Copyright Protection -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-rose-200 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-rose-700 mb-1">4. 100% Original Intellectual Property</h4>
                    <p class="text-xs text-slate-400 leading-relaxed mb-0">
                        You must hold full ownership and intellectual property rights for every file uploaded. The upload of ripped designs, freeware without commercial re-distribution rights, or copyright-infringing content is strictly prohibited and results in <strong>immediate and permanent account suspension</strong>.
                    </p>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between flex-wrap gap-3">
            <span class="text-xs text-slate-400">Noksha Creator Submission Standards</span>
            <div class="flex items-center gap-3">
                <a href="{{ route('guidelines.download-pdf') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white font-semibold text-xs shadow-sm transition-all">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Download PDF Guidelines</span>
                </a>
                <button type="button" id="closeGuidelinesGotItBtn" onclick="document.getElementById('nokshaGuidelinesModal').remove(); localStorage.setItem('noksha_guidelines_seen', 'true');" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-700 hover:text-white font-medium text-xs transition-colors cursor-pointer">
                    Got it, Close
                </button>
            </div>
        </div>
    </div>
</div>

@include('services.partials.inquiry-modal')
@include('services.partials.service-form-modal', ['userTemplates' => $contributorResources ?? collect()])
@include('services.partials.image-preview-modal')

<script>
if (localStorage.getItem('noksha_guidelines_seen') === 'true') {
  const modal = document.getElementById('nokshaGuidelinesModal');
  if (modal) modal.remove();
}
</script>
@endsection
