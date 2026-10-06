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

<div class="min-h-screen bg-slate-950 text-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm space-y-1">
                <div class="font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Please correct the errors below:</span>
                </div>
                <ul class="list-disc list-inside text-xs text-rose-300 ml-6 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ========================================== -->
        <!-- 1. USER PROFILE BANNER (Top)               -->
        <!-- ========================================== -->
        <div class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-5 shadow-lg backdrop-blur-sm flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="relative shrink-0">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-12 h-12 rounded-full object-cover border-2 border-indigo-500 shadow-md" alt="{{ $user->name }}">
                    @else
                        <div class="w-12 h-12 rounded-full bg-indigo-500/10 border-2 border-indigo-500/30 text-indigo-400 font-bold flex items-center justify-center text-lg shadow-inner">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    @if($isUserAdmin)
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-purple-600 rounded-full border-2 border-slate-900 flex items-center justify-center" title="Admin">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                        </span>
                    @elseif($user->isApprovedContributor())
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-slate-900 flex items-center justify-center" title="Verified Contributor">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                        </span>
                    @endif
                </div>

                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h4 class="text-lg font-bold text-white tracking-tight mb-0">{{ $user->name }}</h4>
                        @if($isUserAdmin)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                {{ $userBadgeLabel }}
                            </span>
                        @elseif($user->isApprovedContributor())
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Verified Contributor</span>
                            </span>
                        @elseif($user->contributor_status === 'pending')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Pending Verification</span>
                            </span>
                        @elseif($user->contributor_status === 'rejected')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                <span>KYC Needs Update</span>
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-300 border border-slate-700/50">
                                Buyer
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 font-mono mt-0.5 mb-0">{{ $user->email }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-xs font-medium text-slate-400 hidden sm:inline-block">Member since {{ $user->created_at->format('M Y') }}</span>

                @if($user->isApprovedContributor())
                    <form action="{{ route('user.switch-mode') }}" method="POST" class="inline-block m-0 p-0">
                        @csrf
                        <input type="hidden" name="mode" value="{{ $user->isSellerMode() ? 'buyer' : 'seller' }}">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold {{ $user->isSellerMode() ? 'bg-amber-500/15 text-amber-300 border border-amber-500/30 hover:bg-amber-500/25' : 'bg-indigo-500/15 text-indigo-300 border border-indigo-500/30 hover:bg-indigo-500/25' }} transition-all shadow-sm cursor-pointer" title="Toggle between Buyer and Seller modes">
                            <svg class="w-3.5 h-3.5 shrink-0" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <span>{{ $user->isSellerMode() ? 'Switch to Buyer Mode' : 'Switch to Contributor Mode' }}</span>
                        </button>
                    </form>
                @endif

                <a href="{{ route('settings.profile') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-300 hover:text-white bg-slate-800/80 hover:bg-slate-700 border border-slate-700/60 transition-all shadow-sm">
                    <svg class="w-4 h-4 shrink-0" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Settings</span>
                </a>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 2. NAVIGATION TABS (Horizontal Pill Bar)   -->
        <!-- ========================================== -->
        <div class="inline-flex p-1.5 bg-slate-900/90 border border-slate-800 rounded-xl space-x-2 mb-8 overflow-x-auto max-w-full dash-tabs-nav">
            <button type="button" onclick="switchDashboardTab('pane-overview', this)" class="dash-tab-btn active bg-indigo-600 text-white font-medium shadow-md px-4 py-2 rounded-lg text-sm flex items-center gap-2 cursor-pointer transition-all shrink-0" data-tab="pane-overview">
                <svg class="w-4 h-4 shrink-0" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Overview</span>
            </button>
            <button type="button" onclick="switchDashboardTab('pane-orders', this)" class="dash-tab-btn text-slate-400 hover:text-white hover:bg-slate-800/60 px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2 cursor-pointer shrink-0" data-tab="pane-orders">
                <svg class="w-4 h-4 shrink-0 text-indigo-400" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>My Orders / Invoices</span>
                @if(isset($ordersCount) && $ordersCount > 0)
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-300">{{ $ordersCount }}</span>
                @endif
            </button>
            <button type="button" onclick="switchDashboardTab('pane-downloads', this)" class="dash-tab-btn text-slate-400 hover:text-white hover:bg-slate-800/60 px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2 cursor-pointer shrink-0" data-tab="pane-downloads">
                <svg class="w-4 h-4 shrink-0 text-purple-400" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>My Downloads</span>
                @if(isset($downloadsCount) && $downloadsCount > 0)
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-300">{{ $downloadsCount }}</span>
                @endif
            </button>
            <button type="button" onclick="switchDashboardTab('pane-wishlist', this)" class="dash-tab-btn text-slate-400 hover:text-white hover:bg-slate-800/60 px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2 cursor-pointer shrink-0" data-tab="pane-wishlist">
                <svg class="w-4 h-4 shrink-0 text-rose-400" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span>Wishlist</span>
                @if(isset($wishlistCount) && $wishlistCount > 0)
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-300">{{ $wishlistCount }}</span>
                @endif
            </button>

            @if($user->isApprovedContributor())
                <button type="button" onclick="switchDashboardTab('pane-upload', this)" class="dash-tab-btn text-slate-400 hover:text-white hover:bg-slate-800/60 px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2 cursor-pointer shrink-0" data-tab="pane-upload">
                    <svg class="w-4 h-4 shrink-0 text-amber-400" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Upload Asset</span>
                </button>
            @elseif($user->contributor_status === 'pending')
                <button type="button" onclick="switchDashboardTab('pane-upload', this)" class="dash-tab-btn text-slate-400 hover:text-white hover:bg-slate-800/60 px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2 cursor-pointer shrink-0" data-tab="pane-upload">
                    <svg class="w-4 h-4 shrink-0 text-amber-400" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>KYC Pending</span>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">Review</span>
                </button>
            @elseif($user->contributor_status === 'rejected')
                <button type="button" onclick="switchDashboardTab('pane-upload', this)" class="dash-tab-btn text-slate-400 hover:text-white hover:bg-slate-800/60 px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2 cursor-pointer shrink-0" data-tab="pane-upload">
                    <svg class="w-4 h-4 shrink-0 text-rose-400" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Contributor KYC</span>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">Action Needed</span>
                </button>
            @else
                <button type="button" onclick="switchDashboardTab('pane-upload', this)" class="dash-tab-btn text-slate-400 hover:text-white hover:bg-slate-800/60 px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2 cursor-pointer shrink-0" data-tab="pane-upload">
                    <svg class="w-4 h-4 shrink-0 text-indigo-400" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Become a Contributor</span>
                </button>
            @endif
        </div>

        <!-- ========================================== -->
        <!-- PANE 1: OVERVIEW PANE                      -->
        <!-- ========================================== -->
        <div id="pane-overview" class="dash-content-pane space-y-8">

            <!-- 3. TOP STAT CARDS (Strict Buyer vs Seller Separation) -->
            @if($user->isApprovedContributor() && $user->isSellerMode())
                {{-- SELLER / CONTRIBUTOR STUDIO METRICS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 dash-metrics-grid">
                    <!-- Seller Card 1: Total Uploads -->
                    <div class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-5 shadow-lg backdrop-blur-sm flex flex-col justify-between hover:border-slate-700 transition duration-200">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Uploads</span>
                                <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-extrabold text-white tracking-tight my-2">
                                {{ number_format($totalResources ?? $contributorResources?->count() ?? 0) }}
                            </div>
                        </div>
                        <div class="pt-3 border-t border-slate-800/80 mt-auto flex items-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-800/80 text-slate-300 border border-slate-700/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>
                                Live & Approved
                            </span>
                        </div>
                    </div>

                    <!-- Seller Card 2: Gross Sales Volume -->
                    <div class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-5 shadow-lg backdrop-blur-sm flex flex-col justify-between hover:border-slate-700 transition duration-200">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Sales</span>
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-extrabold text-white tracking-tight my-2">
                                {{ number_format($totalSales ?? 0) }}
                            </div>
                        </div>
                        <div class="pt-3 border-t border-slate-800/80 mt-auto flex items-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-800/80 text-slate-300 border border-slate-700/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                                Gross: ৳{{ number_format($grossSales ?? 0, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Seller Card 3: Royalties Available for Payout -->
                    <div class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-5 shadow-lg backdrop-blur-sm flex flex-col justify-between hover:border-slate-700 transition duration-200">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Seller Earnings</span>
                                <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-extrabold text-cyan-400 tracking-tight my-2">
                                ৳{{ number_format($user->earnings_balance, 2) }}
                            </div>
                        </div>
                        <div class="pt-3 border-t border-slate-800/80 mt-auto flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 shrink-0"></span>
                                Available to Cashout
                            </span>
                            <a href="{{ route('seller.payouts.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold underline">Payouts</a>
                        </div>
                    </div>

                    <!-- Seller Card 4: Total Deliveries -->
                    <div class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-5 shadow-lg backdrop-blur-sm flex flex-col justify-between hover:border-slate-700 transition duration-200">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Downloads</span>
                                <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-extrabold text-white tracking-tight my-2">
                                {{ number_format($totalDownloads ?? 0) }}
                            </div>
                        </div>
                        <div class="pt-3 border-t border-slate-800/80 mt-auto flex items-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-800/80 text-slate-300 border border-slate-700/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400 shrink-0"></span>
                                Digital Deliveries
                            </span>
                        </div>
                    </div>
                </div>

                <!-- SELLER QUICK ACTIONS -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 dash-actions-grid">
                    <button type="button" onclick="switchDashboardTab('pane-upload')" class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-4 flex items-center gap-3.5 hover:border-slate-700 hover:bg-slate-800/50 transition duration-200 text-left w-full group shadow-md cursor-pointer">
                        <div class="w-11 h-11 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        </div>
                        <div>
                            <span class="text-sm font-bold text-white block group-hover:text-amber-400 transition-colors">Upload New Asset</span>
                            <span class="text-xs text-slate-400 block mt-0.5">Publish vector, template or UI kit</span>
                        </div>
                    </button>

                    <a href="{{ route('seller.payouts.index') }}" class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-4 flex items-center gap-3.5 hover:border-slate-700 hover:bg-slate-800/50 transition duration-200 text-left group shadow-md">
                        <div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div>
                            <span class="text-sm font-bold text-white block group-hover:text-emerald-400 transition-colors">Seller Earnings & Payouts</span>
                            <span class="text-xs text-slate-400 block mt-0.5">Request withdrawal to bKash / Bank</span>
                        </div>
                    </a>

                    <a href="{{ route('resources.index') }}" class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-4 flex items-center gap-3.5 hover:border-slate-700 hover:bg-slate-800/50 transition duration-200 text-left group shadow-md">
                        <div class="w-11 h-11 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div>
                            <span class="text-sm font-bold text-white block group-hover:text-indigo-400 transition-colors">Explore Marketplace</span>
                            <span class="text-xs text-slate-400 block mt-0.5">Browse verified creative designs</span>
                        </div>
                    </a>
                </div>
            @else
                {{-- BUYER-CENTRIC STAT CARDS (Strict Buyer View, Zero Seller Metrics) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 dash-metrics-grid">
                    <!-- Buyer Card 1: Available AI Credits -->
                    <div class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-5 shadow-lg backdrop-blur-sm flex flex-col justify-between hover:border-slate-700 transition duration-200">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Available AI Credits</span>
                                <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-extrabold text-white tracking-tight my-2">
                                {{ number_format(auth()->user()->ai_credits ?? (auth()->user()->aiCredit?->credits ?? 0)) }}
                            </div>
                        </div>
                        <div class="pt-3 border-t border-slate-800/80 mt-auto flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400 shrink-0"></span>
                                AI Generation Tokens
                            </span>
                            <a href="{{ route('wallet.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold underline">Recharge</a>
                        </div>
                    </div>

                    <!-- Buyer Card 2: Total Spent / Purchases -->
                    <div class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-5 shadow-lg backdrop-blur-sm flex flex-col justify-between hover:border-slate-700 transition duration-200">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Spent / Purchases</span>
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-extrabold text-emerald-400 tracking-tight my-2">
                                ৳{{ number_format($totalSpent ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="pt-3 border-t border-slate-800/80 mt-auto flex items-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-800/80 text-slate-300 border border-slate-700/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                                {{ number_format($ordersCount ?? 0) }} Completed Orders
                            </span>
                        </div>
                    </div>

                    <!-- Buyer Card 3: Total Downloads -->
                    <div class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-5 shadow-lg backdrop-blur-sm flex flex-col justify-between hover:border-slate-700 transition duration-200">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Downloads</span>
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-extrabold text-white tracking-tight my-2">
                                {{ number_format($downloadsCount ?? 0) }}
                            </div>
                        </div>
                        <div class="pt-3 border-t border-slate-800/80 mt-auto flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-800/80 text-slate-300 border border-slate-700/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                                Purchased Asset Files
                            </span>
                            <button type="button" onclick="switchDashboardTab('pane-downloads')" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold underline cursor-pointer">View Library</button>
                        </div>
                    </div>

                    <!-- Buyer Card 4: Saved Wishlist -->
                    <div class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-5 shadow-lg backdrop-blur-sm flex flex-col justify-between hover:border-slate-700 transition duration-200">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Saved Wishlist</span>
                                <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-extrabold text-white tracking-tight my-2">
                                {{ number_format($wishlistCount ?? 0) }}
                            </div>
                        </div>
                        <div class="pt-3 border-t border-slate-800/80 mt-auto flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-800/80 text-slate-300 border border-slate-700/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400 shrink-0"></span>
                                Saved For Later
                            </span>
                            <button type="button" onclick="switchDashboardTab('pane-wishlist')" class="text-xs text-rose-400 hover:text-rose-300 font-semibold underline cursor-pointer">Wishlist</button>
                        </div>
                    </div>
                </div>

                <!-- BUYER QUICK ACTIONS -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 dash-actions-grid">
                    @if($user->isApprovedContributor())
                        <form action="{{ route('user.switch-mode') }}" method="POST" class="m-0 p-0">
                            @csrf
                            <input type="hidden" name="mode" value="seller">
                            <button type="submit" class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-4 flex items-center gap-3.5 hover:border-slate-700 hover:bg-slate-800/50 transition duration-200 text-left w-full group shadow-md cursor-pointer">
                                <div class="w-11 h-11 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-white block group-hover:text-amber-400 transition-colors">Switch to Contributor Studio</span>
                                    <span class="text-xs text-slate-400 block mt-0.5">Publish assets & view royalties</span>
                                </div>
                            </button>
                        </form>
                    @elseif($user->contributor_status === 'pending')
                        <button type="button" onclick="switchDashboardTab('pane-upload')" class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-4 flex items-center gap-3.5 hover:border-slate-700 hover:bg-slate-800/50 transition duration-200 text-left w-full group shadow-md cursor-pointer">
                            <div class="w-11 h-11 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <span class="text-sm font-bold text-white block group-hover:text-amber-400 transition-colors">KYC Verification Status</span>
                                <span class="text-xs text-slate-400 block mt-0.5">Application in admin review</span>
                            </div>
                        </button>
                    @else
                        <button type="button" onclick="switchDashboardTab('pane-upload')" class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-4 flex items-center gap-3.5 hover:border-slate-700 hover:bg-slate-800/50 transition duration-200 text-left w-full group shadow-md cursor-pointer">
                            <div class="w-11 h-11 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <span class="text-sm font-bold text-white block group-hover:text-indigo-400 transition-colors">Become a Contributor</span>
                                <span class="text-xs text-slate-400 block mt-0.5">Apply for KYC to earn 50% royalties</span>
                            </div>
                        </button>
                    @endif

                    <!-- Action 2: Explore Marketplace -->
                    <a href="{{ route('resources.index') }}" class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-4 flex items-center gap-3.5 hover:border-slate-700 hover:bg-slate-800/50 transition duration-200 text-left group shadow-md">
                        <div class="w-11 h-11 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div>
                            <span class="text-sm font-bold text-white block group-hover:text-indigo-400 transition-colors">Explore Marketplace</span>
                            <span class="text-xs text-slate-400 block mt-0.5">Browse vectors, Figma & UI assets</span>
                        </div>
                    </a>

                    <!-- Action 3: Download Active License Keys -->
                    <button type="button" onclick="switchDashboardTab('pane-downloads')" class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-4 flex items-center gap-3.5 hover:border-slate-700 hover:bg-slate-800/50 transition duration-200 text-left w-full group shadow-md cursor-pointer">
                        <div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        </div>
                        <div>
                            <span class="text-sm font-bold text-white block group-hover:text-emerald-400 transition-colors">My Downloaded Assets</span>
                            <span class="text-xs text-slate-400 block mt-0.5">Instant access to purchased files</span>
                        </div>
                    </button>
                </div>
            @endif

            <!-- 5. RECENT ACTIVITY SECTION -->
            <div class="bg-slate-900/70 border border-slate-800/90 rounded-2xl overflow-hidden p-6 shadow-lg backdrop-blur-sm">
                <div class="flex items-center justify-between pb-5 border-b border-slate-800/80 flex-wrap gap-3">
                    <div>
                        <h3 class="text-base font-bold text-white flex items-center gap-2 mb-0">
                            <svg class="w-5 h-5 shrink-0 text-indigo-400" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Recent Purchases & Activity
                        </h3>
                        <p class="text-xs text-slate-400 mt-1 mb-0">Real-time records of digital acquisitions, licensing orders, and asset performance</p>
                    </div>
                    <button type="button" onclick="switchDashboardTab('pane-orders')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-300 hover:text-white bg-slate-800/80 hover:bg-slate-800 border border-slate-700/50 transition-all cursor-pointer">
                        <span>View Full History</span>
                        <svg class="w-3.5 h-3.5 shrink-0" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                @if(isset($orders) && $orders->count() > 0)
                    <div class="overflow-x-auto mt-4">
                        <table class="w-full text-left text-sm text-slate-300">
                            <thead class="bg-slate-950/60 text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                                <tr>
                                    <th scope="col" class="py-3.5 px-4">Asset Name</th>
                                    <th scope="col" class="py-3.5 px-4">Category</th>
                                    <th scope="col" class="py-3.5 px-4">Date</th>
                                    <th scope="col" class="py-3.5 px-4">Amount / Status</th>
                                    <th scope="col" class="py-3.5 px-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @foreach($orders->take(5) as $order)
                                    @php
                                        $firstItem = $order->items->first();
                                        $resource = $firstItem?->resource;
                                    @endphp
                                    <tr class="hover:bg-slate-800/30 transition-colors">
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-3">
                                                @if($resource && $resource->preview_image)
                                                    <img src="{{ asset('storage/' . $resource->preview_image) }}" class="w-11 h-11 rounded-xl object-cover border border-slate-700/60 bg-slate-800 shrink-0" alt="{{ $resource->title }}" onerror="this.src='{{ asset('images/logo.png') }}'">
                                                @else
                                                    <div class="w-11 h-11 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0">
                                                        <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    </div>
                                                @endif
                                                <div class="truncate max-w-xs">
                                                    <a href="{{ $resource ? route('resource.show', $resource->slug ?? $resource->id) : '#' }}" class="font-semibold text-white hover:text-indigo-400 text-sm truncate block transition-colors">
                                                        {{ $resource?->title ?? 'Digital Design Asset' }}
                                                    </a>
                                                    <span class="text-xs text-slate-400 font-mono block mt-0.5">
                                                        #{{ $order->order_number ?? $order->id }} @if($order->items->count() > 1) <span class="text-indigo-400">(+{{ $order->items->count() - 1 }} more)</span> @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-800 text-slate-400">
                                                {{ $resource?->category?->name ?? 'Design Resource' }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <span class="text-xs text-slate-400 font-mono">
                                                {{ $order->created_at->format('M d, Y') }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-cyan-400 font-mono text-sm">৳{{ number_format($order->total, 2) }}</span>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shrink-0">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span> Paid
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4 text-right">
                                            <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-300 hover:text-white bg-slate-800/80 hover:bg-slate-700 border border-slate-700/60 transition-all shrink-0">
                                                <svg class="w-3.5 h-3.5 shrink-0 text-indigo-400" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <span>Invoice</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Graceful Empty State -->
                    <div class="text-center py-12">
                        <svg class="w-10 h-10 text-slate-500 mx-auto mb-2 shrink-0" width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <h4 class="text-sm font-semibold text-slate-300">No recent activity found</h4>
                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Explore thousands of creative templates, vectors, and UI kits ready for immediate use.</p>
                        <div class="mt-4">
                            <a href="{{ route('resources.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md transition-all">
                                <svg class="w-3.5 h-3.5 shrink-0" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span>Explore Assets</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>

        <!-- ========================================== -->
        <!-- PANE 2: MY ORDERS / INVOICES PANE          -->
        <!-- ========================================== -->
        <div id="pane-orders" class="dash-content-pane d-none space-y-8">
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 shadow-lg backdrop-blur-md">
                <div class="flex items-center justify-between pb-4 border-b border-slate-800/80 mb-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2 mb-0">
                        <svg class="w-5 h-5 shrink-0 text-indigo-400" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>My Orders & Invoices ({{ isset($orders) ? $orders->count() : 0 }})</span>
                    </h3>
                </div>

                @if(isset($orders) && $orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-300">
                            <thead class="bg-slate-950/60 text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                                <tr>
                                    <th scope="col" class="py-3 px-4">Order #</th>
                                    <th scope="col" class="py-3 px-4">Items</th>
                                    <th scope="col" class="py-3 px-4">Date</th>
                                    <th scope="col" class="py-3 px-4">Amount</th>
                                    <th scope="col" class="py-3 px-4">Status</th>
                                    <th scope="col" class="py-3 px-4 text-right">Invoice</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @foreach($orders as $ord)
                                    <tr class="hover:bg-slate-800/30 transition-colors">
                                        <td class="py-3.5 px-4 font-mono font-bold text-indigo-400 text-xs">
                                            #{{ $ord->order_number ?? $ord->id }}
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @foreach($ord->items as $it)
                                                <div class="text-white text-xs truncate max-w-xs font-medium">
                                                    {{ $it->resource?->title ?? 'Design Template' }}
                                                </div>
                                            @endforeach
                                        </td>
                                        <td class="py-3.5 px-4 text-xs text-slate-400 font-mono">
                                            {{ $ord->created_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="py-3.5 px-4 font-mono font-bold text-cyan-400 text-sm">
                                            ৳{{ number_format($ord->total, 2) }}
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shrink-0">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span> Paid
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-right">
                                            <a href="{{ route('orders.show', $ord) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold text-slate-300 hover:text-white bg-slate-800/80 hover:bg-slate-700 border border-slate-700/60 transition-all shrink-0">
                                                <svg class="w-3.5 h-3.5 shrink-0 text-indigo-400" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
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
                        <svg class="w-10 h-10 text-slate-500 mx-auto mb-2 shrink-0" width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm font-semibold text-slate-300">No orders recorded yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- PANE 3: MY DOWNLOADS PANE                  -->
        <!-- ========================================== -->
        <div id="pane-downloads" class="dash-content-pane d-none space-y-8">
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 shadow-lg backdrop-blur-md">
                <div class="flex items-center justify-between pb-4 border-b border-slate-800/80 mb-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2 mb-0">
                        <svg class="w-5 h-5 shrink-0 text-purple-400" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Purchased Digital Downloads</span>
                    </h3>
                </div>

                @if(isset($orderDownloads) && $orderDownloads->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($orderDownloads as $dl)
                            <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-4 flex flex-col justify-between hover:border-slate-700 transition-all">
                                <div>
                                    <div class="w-full h-36 rounded-lg overflow-hidden bg-slate-950 mb-3 border border-slate-800">
                                        @if($dl->preview_image)
                                            <img src="{{ asset('storage/' . $dl->preview_image) }}" class="w-full h-full object-cover" alt="{{ $dl->title }}" onerror="this.src='{{ asset('images/logo.png') }}'">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-600">
                                                <svg class="w-8 h-8 shrink-0" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <h5 class="text-sm font-bold text-white truncate mb-1">{{ $dl->title }}</h5>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-800 text-slate-400">
                                        {{ $dl->category?->name ?? 'Digital Template' }}
                                    </span>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between">
                                    <span class="text-xs text-slate-400 font-mono">{{ strtoupper($dl->file_type ?? 'ZIP') }}</span>
                                    <a href="{{ route('resource.show', $dl->slug ?? $dl->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md transition-all shrink-0">
                                        <svg class="w-3.5 h-3.5 shrink-0" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        <span>Download</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-10 h-10 text-slate-500 mx-auto mb-2 shrink-0" width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <p class="text-sm font-semibold text-slate-300">No downloaded items available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- PANE 4: WISHLIST PANE                      -->
        <!-- ========================================== -->
        <div id="pane-wishlist" class="dash-content-pane d-none space-y-8">
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 shadow-lg backdrop-blur-md">
                <div class="flex items-center justify-between pb-4 border-b border-slate-800/80 mb-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2 mb-0">
                        <svg class="w-5 h-5 shrink-0 text-rose-400" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span>Saved Wishlist Templates</span>
                    </h3>
                </div>

                @if(isset($wishlistItems) && $wishlistItems->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($wishlistItems as $wl)
                            <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-4 flex flex-col justify-between hover:border-slate-700 transition-all">
                                <div>
                                    <div class="w-full h-36 rounded-lg overflow-hidden bg-slate-950 mb-3 border border-slate-800">
                                        @if($wl->preview_image)
                                            <img src="{{ asset('storage/' . $wl->preview_image) }}" class="w-full h-full object-cover" alt="{{ $wl->title }}" onerror="this.src='{{ asset('images/logo.png') }}'">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-600">
                                                <svg class="w-8 h-8 shrink-0" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <h5 class="text-sm font-bold text-white truncate mb-1">{{ $wl->title }}</h5>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-800 text-slate-400">
                                            {{ $wl->category?->name ?? 'Template' }}
                                        </span>
                                        <span class="font-bold text-cyan-400 text-sm font-mono">
                                            @if($wl->is_paid) ৳{{ number_format($wl->price, 2) }} @else Free @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-end">
                                    <a href="{{ route('resource.show', $wl->slug ?? $wl->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md transition-all shrink-0">
                                        <svg class="w-3.5 h-3.5 shrink-0" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>View Details</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-10 h-10 text-slate-500 mx-auto mb-2 shrink-0" width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <p class="text-sm font-semibold text-slate-300">Your wishlist is currently empty</p>
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
                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 shadow-lg backdrop-blur-md">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-800/80 mb-6 flex-wrap gap-4">
                        <div>
                            <h3 class="text-base font-bold text-white flex items-center gap-2 mb-0">
                                <svg class="w-5 h-5 shrink-0 text-amber-400" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span>Creator Studio - Upload Creative Asset</span>
                            </h3>
                            <p class="text-xs text-slate-400 mt-1 mb-0">Publish vectors, mockups, Figma kits, or PSD graphics to start earning creator royalties</p>
                        </div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <button type="button" onclick="openUploadGuidelinesModal()" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/30 hover:bg-indigo-500/20 hover:border-indigo-500/50 transition-all cursor-pointer shadow-sm shadow-indigo-500/10">
                                <svg class="w-4 h-4 shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <span>Upload Guidelines</span>
                            </button>
                            @if(!$user->isSellerMode())
                                <form action="{{ route('user.switch-mode') }}" method="POST" class="m-0 p-0">
                                    @csrf
                                    <input type="hidden" name="mode" value="seller">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30 hover:bg-amber-500/20 transition-all cursor-pointer">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        <span>Switch to Contributor Mode</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('resource.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                            <!-- Cover Image Dropzone -->
                            <div class="lg:col-span-5">
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                                    Cover Preview Image <span class="text-rose-400">*</span>
                                </label>

                                <div class="p-6 border-2 border-dashed border-slate-700 hover:border-indigo-500 rounded-2xl text-center bg-slate-950/60 cursor-pointer min-h-[260px] flex flex-col items-center justify-center transition-colors" id="dashImageDropZone" onclick="document.getElementById('dash_preview_image').click();">
                                    <svg class="w-10 h-10 text-indigo-400 mb-2 shrink-0" width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <div class="font-semibold text-white text-sm mb-1">Click to upload cover image</div>
                                    <div class="text-xs text-slate-500 mb-3">PNG, JPG, or WEBP (Max 5MB)</div>
                                    <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-400 bg-indigo-500/10 border border-indigo-500/20">
                                        Select Image
                                    </span>
                                    <input type="file" name="preview_image" id="dash_preview_image" class="hidden" accept="image/png,image/jpeg,image/webp,image/jpg" required onchange="handleDashImageSelect(this)">
                                </div>

                                <div id="dashImagePreviewBox" class="hidden mt-3">
                                    <img id="dashImagePreviewImg" src="" class="rounded-xl border border-slate-700 w-full max-h-56 object-cover" alt="Preview">
                                    <div class="flex justify-between text-xs text-slate-400 mt-2">
                                        <span id="dashImageFileName" class="font-medium text-emerald-400 truncate">image.jpg</span>
                                        <button type="button" class="text-rose-400 hover:text-rose-300 font-semibold cursor-pointer" onclick="removeDashImage()">Remove</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Asset Metadata -->
                            <div class="lg:col-span-7 space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Asset Title <span class="text-rose-400">*</span></label>
                                    <input type="text" name="title" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none" placeholder="e.g. Modern FinTech Mobile App UI Kit" required>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Category <span class="text-rose-400">*</span></label>
                                        <select name="category_id" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white text-sm focus:border-indigo-500 focus:outline-none" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Price (BDT) <span class="text-rose-400">*</span></label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 font-bold text-sm">৳</span>
                                            <input type="number" step="0.01" min="0" name="price" class="w-full pl-8 pr-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none font-mono" placeholder="0.00 for Free" value="0.00" required>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Search Tags (comma separated)</label>
                                    <input type="text" name="tags" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none" placeholder="mobile, ui kit, figma, dark mode">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Source File (ZIP / RAR) <span class="text-rose-400">*</span></label>
                                    <input type="file" name="file" class="w-full px-3 py-2 rounded-xl bg-slate-950/80 border border-slate-800 text-slate-300 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer" required>
                                </div>

                                <div class="pt-2">
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm shadow-lg shadow-indigo-600/25 transition-all cursor-pointer">
                                        <svg class="w-4 h-4 shrink-0" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        <span>Publish Asset to Noksha</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            @elseif($user->contributor_status === 'pending')
                {{-- STATE 2: KYC APPLICATION UNDER REVIEW --}}
                <div class="bg-slate-900/60 border border-amber-500/30 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-md">
                    <div class="max-w-2xl mx-auto text-center space-y-4">
                        <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-400 mx-auto flex items-center justify-center shadow-lg shadow-amber-500/10">
                            <svg class="w-8 h-8 shrink-0" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>

                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-500/10 text-amber-400 border border-amber-500/20 mb-2">
                                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                                Verification In Review
                            </span>
                            <h3 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight">Your Contributor KYC Application is Under Review</h3>
                            <p class="text-sm text-slate-400 mt-2">
                                Thank you for applying to become a Noksha Contributor! Our verification team is currently reviewing your identity document and portfolio. You will be able to upload creative assets and monetize your work once approved.
                            </p>
                        </div>

                        <!-- Submitted Details Box -->
                        <div class="bg-slate-950/70 border border-slate-800 rounded-xl p-5 text-left space-y-3 mt-6">
                            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-800/80 pb-2">
                                Submitted Application Details
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                <div>
                                    <span class="text-slate-500 block">Applicant Name:</span>
                                    <span class="text-white font-medium">{{ $user->name }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">National ID / Passport:</span>
                                    <span class="text-white font-mono font-medium">{{ $user->nid_or_passport_number ?? 'Submitted' }}</span>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="text-slate-500 block">Portfolio Link:</span>
                                    @if($user->portfolio_link)
                                        <a href="{{ $user->portfolio_link }}" target="_blank" rel="noopener noreferrer" class="text-indigo-400 hover:text-indigo-300 underline break-all font-mono">
                                            {{ $user->portfolio_link }}
                                        </a>
                                    @else
                                        <span class="text-slate-400">Under review</span>
                                    @endif
                                </div>
                                @if($user->kyc_document_path)
                                    <div class="sm:col-span-2">
                                        <span class="text-slate-500 block">ID Document:</span>
                                        <a href="{{ asset('storage/' . $user->kyc_document_path) }}" target="_blank" download class="inline-flex items-center gap-1.5 text-emerald-400 hover:text-emerald-300 font-semibold mt-0.5">
                                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span>Document Attached</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="pt-2 flex items-center justify-center gap-2 text-xs text-slate-400">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Typical review timeframe is within 24 to 48 business hours.</span>
                        </div>
                    </div>
                </div>

            @else
                {{-- STATE 3 & 4: REJECTED OR NONE (KYC ONBOARDING & APPLICATION FORM) --}}
                <div class="space-y-6">

                    @if($user->contributor_status === 'rejected')
                        <!-- REJECTION ALERT -->
                        <div class="p-5 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-sm font-bold text-white mb-0">Contributor KYC Application Needs Update</h4>
                                    <p class="text-xs text-rose-300 leading-relaxed mb-0">
                                        <span class="font-semibold text-rose-200">Admin Feedback:</span>
                                        {{ $user->kyc_rejection_reason ?? 'Your previous application could not be verified with the provided documents or portfolio.' }}
                                    </p>
                                    <p class="text-xs text-slate-400 pt-1 mb-0">Please review the reason above and resubmit your updated verification application below.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- CONTRIBUTOR ONBOARDING HERO BANNER -->
                    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-950/80 via-slate-900 to-purple-950/80 border border-indigo-500/20 rounded-2xl p-6 sm:p-8 shadow-xl">
                        <div class="max-w-2xl space-y-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span>Monetize Your Creative Work</span>
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Become a Verified Noksha Contributor</h2>
                            <p class="text-sm text-slate-300 leading-relaxed">
                                Join our creator community to sell vectors, Figma templates, mockups, and UI assets to thousands of designers. Enjoy competitive 50% royalties and instant creator payouts.
                            </p>
                        </div>

                        <!-- 3 Benefits Highlights -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6 pt-6 border-t border-slate-800/80">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-white block">50% Creator Royalties</span>
                                    <span class="text-[11px] text-slate-400 block">Earn on every sale</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-white block">Fast Direct Payouts</span>
                                    <span class="text-[11px] text-slate-400 block">bKash, Nagad & Bank</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-white block">Verified Author Badge</span>
                                    <span class="text-[11px] text-slate-400 block">Enhanced buyer trust</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KYC APPLICATION FORM CARD -->
                    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-md">
                        <div class="pb-5 border-b border-slate-800/80 mb-6">
                            <h3 class="text-lg font-bold text-white flex items-center gap-2 mb-0">
                                <svg class="w-5 h-5 shrink-0 text-indigo-400" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Contributor Identity & Portfolio Verification</span>
                            </h3>
                            <p class="text-xs text-slate-400 mt-1 mb-0">Please fill out your legal details and provide your public design portfolio for review.</p>
                        </div>

                        <form action="{{ route('seller.verification.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">
                                        Full Legal Name <span class="text-rose-400">*</span>
                                    </label>
                                    <input type="text" name="full_name" value="{{ old('full_name', $user->name) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none" placeholder="Your full name as on ID" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">
                                        Phone / WhatsApp <span class="text-rose-400">*</span>
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none" placeholder="+880 1700-000000" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">
                                        National ID / Passport Number <span class="text-rose-400">*</span>
                                    </label>
                                    <input type="text" name="nid_or_passport_number" value="{{ old('nid_or_passport_number', $user->nid_or_passport_number) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none font-mono" placeholder="e.g. 19941234567890 or A12345678" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">
                                        Portfolio Link (Behance / Dribbble / Web) <span class="text-rose-400">*</span>
                                    </label>
                                    <input type="url" name="portfolio_link" value="{{ old('portfolio_link', $user->portfolio_link) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none" placeholder="https://behance.net/yourprofile" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">
                                    Contributor Bio & Experience
                                </label>
                                <textarea name="contributor_bio" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none" placeholder="Briefly describe your design background, tools used (Figma, Illustrator, Blender), and the types of creative assets you create...">{{ old('contributor_bio', $user->contributor_bio) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">
                                    Government ID / Passport Document <span class="text-rose-400">*</span>
                                </label>
                                <input type="file" name="document_file" accept="image/png,image/jpeg,image/webp,image/jpg,application/pdf" class="w-full px-3 py-2 rounded-xl bg-slate-950/80 border border-slate-800 text-slate-300 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer" required>
                                <p class="text-xs text-slate-500 mt-1 mb-0">Upload a clear photo or scanned PDF of your National ID, Passport, or Driving License (Max 10MB)</p>
                            </div>

                            <div class="pt-2">
                                <label class="inline-flex items-start gap-2.5 cursor-pointer">
                                    <input type="checkbox" name="agreement" value="1" class="mt-1 rounded bg-slate-950 border-slate-700 text-indigo-600 focus:ring-0" required>
                                    <span class="text-xs text-slate-400 leading-relaxed">
                                        I certify that the information and documents provided are authentic, and I agree to only upload original designs or properly licensed creative assets in compliance with Noksha Contributor Terms.
                                    </span>
                                </label>
                            </div>

                            <div class="pt-3">
                                <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm shadow-lg shadow-indigo-600/25 transition-all cursor-pointer">
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
        btn.classList.remove('active', 'bg-indigo-600', 'text-white', 'shadow-md');
        btn.classList.add('text-slate-400');
    });

    // Show selected pane
    var target = document.getElementById(paneId);
    if (target) {
        target.classList.remove('d-none');
    }

    // Set active tab styling
    var activeBtn = btnEl || document.querySelector('.dash-tab-btn[data-tab="' + paneId + '"]');
    if (activeBtn) {
        activeBtn.classList.add('active', 'bg-indigo-600', 'text-white', 'shadow-md');
        activeBtn.classList.remove('text-slate-400');
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
    document.getElementById('dash_preview_image').value = '';
    document.getElementById('dashImagePreviewBox').classList.add('hidden');
    document.getElementById('dashImageDropZone').classList.remove('hidden');
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

function openUploadGuidelinesModal() {
    var modal = document.getElementById('uploadGuidelinesModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeUploadGuidelinesModal() {
    var modal = document.getElementById('uploadGuidelinesModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}
</script>

<!-- ======================================================== -->
<!-- UPLOAD GUIDELINES MODAL POPUP (DARK THEMED)              -->
<!-- ======================================================== -->
<div id="uploadGuidelinesModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md hidden transition-opacity duration-300">
    <div class="relative w-full max-w-2xl bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl overflow-hidden my-8 max-h-[90vh] flex flex-col" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between bg-slate-950/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white mb-0">Noksha Creator Upload Guidelines</h3>
                    <p class="text-xs text-slate-400 mb-0">Follow our quality and licensing rules to ensure rapid asset approval</p>
                </div>
            </div>
            <button type="button" onclick="closeUploadGuidelinesModal()" class="w-8 h-8 rounded-lg bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 flex items-center justify-center transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="p-6 space-y-4 overflow-y-auto text-sm text-slate-300">
            <!-- Rule 1: Cover Preview -->
            <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20 flex items-center justify-center shrink-0 mt-0.5">
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
            <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 flex items-center justify-center shrink-0 mt-0.5">
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
            <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center shrink-0 mt-0.5">
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
            <div class="p-4 rounded-2xl bg-slate-950/60 border border-rose-500/30 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-rose-300 mb-1">4. 100% Original Intellectual Property</h4>
                    <p class="text-xs text-slate-400 leading-relaxed mb-0">
                        You must hold full ownership and intellectual property rights for every file uploaded. The upload of ripped designs, freeware without commercial re-distribution rights, or copyright-infringing content is strictly prohibited and results in <strong>immediate and permanent account suspension</strong>.
                    </p>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/70 flex items-center justify-between flex-wrap gap-3">
            <span class="text-xs text-slate-400">Noksha Creator Standards v2026.1</span>
            <div class="flex items-center gap-3">
                <a href="{{ route('guidelines.download-pdf') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-lg shadow-indigo-600/25 transition-all">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Download PDF Guidelines</span>
                </a>
                <button type="button" onclick="closeUploadGuidelinesModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-medium text-xs transition-colors cursor-pointer">
                    Got it, Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

