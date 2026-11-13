@php
    $activeConversation = $activeConversation ?? ($conversation ?? null);
    $conversation = $activeConversation;
    $conversations = $conversations ?? collect();
    $counterpart = $counterpart ?? ($conversation ? $conversation->getOtherParticipant(auth()->user()) : null);
    $services = $services ?? collect();
    $sellerServices = $sellerServices ?? $services;
    $workHistoryOrders = $workHistoryOrders ?? collect();
    $workHistoryCount = $workHistoryCount ?? $workHistoryOrders->count();
    $messages = $messages ?? ($conversation ? $conversation->messages : collect());
@endphp

@extends('layouts.app')

@section('title', ($activeConversation && $counterpart ? 'Chat with ' . $counterpart->name : 'Inbox') . ' - Noksha')

@section('content')
<style>
    /* Strict Viewport Clamp for Inbox */
    footer, .site-footer {
        display: none !important;
    }
    html, body {
        overflow: hidden !important;
        height: 100vh !important;
    }

    /* Anti-theft Diagonal Watermark Overlay */
    .noksha-watermark-overlay {
        background-image: repeating-linear-gradient(-45deg, rgba(32, 22, 92, 0.08), rgba(32, 22, 92, 0.08) 25px, transparent 25px, transparent 50px);
    }
</style>

<div class="bg-[#ffffff] text-[#121826] flex flex-col font-sans antialiased h-[calc(100vh-80px)] max-h-[calc(100vh-80px)] overflow-hidden">
    
    {{-- ALERT TOAST CONTAINER (Client-side & Session) --}}
    <div id="toastContainer" class="fixed top-20 right-6 z-50 flex flex-col gap-3 pointer-events-none max-w-md w-full">
        @if(session('success'))
            <div class="pointer-events-auto flex items-center justify-between gap-3 p-4 rounded-2xl bg-emerald-950/90 border border-emerald-500/40 text-emerald-200 shadow-2xl backdrop-blur-xl animate-fade-in">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200 text-sm">&times;</button>
            </div>
        @endif
        @if(session('error') || (isset($errors) && $errors->any()))
            <div class="pointer-events-auto flex items-center justify-between gap-3 p-4 rounded-2xl bg-rose-950/90 border border-rose-500/40 text-rose-200 shadow-2xl backdrop-blur-xl animate-fade-in">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="text-xs font-semibold">{{ session('error') ?? ($errors->first() ?? '') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-200 text-sm">&times;</button>
            </div>
        @endif
    </div>

    {{-- MAIN INBOX CONTAINER --}}
    <div class="container-fluid max-w-7xl mx-auto px-2 sm:px-4 lg:px-6 py-2 flex-1 flex flex-col h-[calc(100vh-80px)] max-h-[calc(100vh-80px)] overflow-hidden">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-md overflow-hidden flex-1 flex flex-col lg:flex-row h-full max-h-full min-h-0">

            {{-- ======================================================== --}}
            {{-- 1. LEFT COLUMN: CONVERSATIONS SIDEBAR                    --}}
            {{-- ======================================================== --}}
            <aside id="chatSidebar" class="w-full lg:w-80 xl:w-96 border-b lg:border-b-0 lg:border-r border-slate-200 flex flex-col bg-slate-50 shrink-0 h-full max-h-full overflow-hidden min-h-0">
                
                {{-- Sidebar Header --}}
                <div class="p-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <div>
                        <h1 class="text-base font-bold text-[#121826] tracking-tight flex items-center gap-2 mb-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#20165c] animate-pulse"></span>
                            <span>Direct Escrow Inbox</span>
                        </h1>
                        <p class="text-[11px] text-slate-400 mb-0">Noksha Secured Messaging</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#20165c]/10 text-[#20165c] border border-[#20165c]/20">
                        {{ $conversations->count() }} {{ Str::plural('Chat', $conversations->count()) }}
                    </span>
                </div>

                {{-- Search Filter Input --}}
                <div class="p-3 border-b border-slate-200 bg-slate-50">
                    <div class="relative">
                        <svg class="w-4 h-4 text-slate-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="conversationSearchInput" placeholder="Search chats by name or keyword..." value="{{ $search ?? '' }}" class="w-full pl-9 pr-4 py-2 rounded-xl text-xs bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] transition-all">
                    </div>
                </div>

                {{-- Thread List (Scrollable) --}}
                <div id="conversationThreadList" class="flex-1 overflow-y-auto divide-y divide-slate-200 scrollbar-thin scrollbar-thumb-slate-300 min-h-0">
                    @forelse($conversations as $conv)
                        @php
                            $convOther = $conv->getOtherParticipant(auth()->user());
                            $isActive = $activeConversation && $activeConversation->id === $conv->id;
                            $latestMsg = $conv->latestMessage;
                            $isSupport = $conv->conversation_type === 'system_support';
                            $unread = $conv->unread_count ?? 0;
                        @endphp
                        <div class="conversation-thread-item group relative p-3 hover:bg-slate-100 transition-colors {{ $isActive ? 'bg-purple-50/80 border-l-4 border-[#20165c]' : '' }}" data-search-name="{{ strtolower($convOther->name ?? 'support') }}" data-search-text="{{ strtolower($latestMsg->body ?? '') }}">
                            <div class="flex items-center gap-3">
                                
                                {{-- Avatar & Online dot --}}
                                <a href="{{ route('inbox.show', $conv->id) }}" class="relative shrink-0 text-decoration-none">
                                    @if($isSupport)
                                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-md shadow-indigo-600/20">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                    @elseif($convOther && $convOther->avatar)
                                        <img src="{{ asset('storage/' . $convOther->avatar) }}" alt="{{ $convOther->name }}" class="w-11 h-11 rounded-2xl object-cover border border-slate-200 shadow-sm">
                                    @else
                                        <div class="w-11 h-11 rounded-2xl bg-[#20165c]/10 border border-[#20165c]/20 flex items-center justify-center text-[#20165c] font-bold text-sm shadow-sm">
                                            {{ strtoupper(substr($convOther->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                    
                                    @if(!$isSupport)
                                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full {{ ($convOther->is_available ?? true) ? 'bg-emerald-500' : 'bg-slate-500' }} border-2 border-white" title="{{ ($convOther->is_available ?? true) ? 'Available' : 'Offline' }}"></span>
                                    @endif
                                </a>

                                {{-- Details --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <a href="{{ route('inbox.show', $conv->id) }}" class="text-xs font-bold text-[#121826] hover:text-[#20165c] truncate mb-0 text-decoration-none">
                                            {{ $isSupport ? 'Official System Notice' : ($convOther->name ?? 'User') }}
                                        </a>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span class="text-[10px] text-slate-500">
                                                {{ $conv->last_message_at ? $conv->last_message_at->diffForHumans(null, true) : '' }}
                                            </span>

                                            {{-- Delete Chat Option Button --}}
                                            <form action="{{ route('inbox.destroy', $conv->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this chat?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-red-500 p-0.5 rounded transition opacity-0 group-hover:opacity-100 focus:opacity-100 hover:bg-red-50 cursor-pointer" title="Delete this chat">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1.5 mb-1">
                                        @if($isSupport)
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20">System Support</span>
                                        @elseif($convOther && $convOther->isSellerOrCreator())
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Creator / Seller</span>
                                        @else
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">Buyer</span>
                                        @endif

                                        @if($conv->activeOrder && $conv->activeOrder->isFunded())
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 font-mono">
                                                {{ $conv->activeOrder->order_code ?? $conv->activeOrder->order_number }}
                                            </span>
                                        @endif
                                    </div>

                                    <a href="{{ route('inbox.show', $conv->id) }}" class="flex items-center justify-between gap-2 text-decoration-none">
                                        <p class="text-[11px] text-slate-500 truncate mb-0 max-w-[190px]">
                                            @if($latestMsg)
                                                {{ $latestMsg->sender_id === auth()->id() ? 'You: ' : '' }}{{ $latestMsg->body }}
                                            @else
                                                <span class="italic text-slate-500">No messages yet</span>
                                            @endif
                                        </p>
                                        @if($unread > 0)
                                            <span class="shrink-0 w-4 h-4 rounded-full bg-[#f47402] text-white font-bold text-[9px] flex items-center justify-center shadow-sm">
                                                {{ $unread }}
                                            </span>
                                        @endif
                                    </a>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500">
                            <svg class="w-10 h-10 mx-auto mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <p class="text-xs mb-0">No conversations yet.</p>
                        </div>
                    @endforelse
                </div>
            </aside>

            {{-- ======================================================== --}}
            {{-- 2. RIGHT COLUMN: ACTIVE CHAT VIEWPORT                    --}}
            {{-- ======================================================== --}}
            <section id="activeChatSection" class="flex-1 flex flex-col h-full max-h-full overflow-hidden bg-white relative min-h-0">
                
                @if($activeConversation)
                    @php
                        $isSupportThread = $activeConversation->conversation_type === 'system_support';
                        $activeOrder = $activeConversation->activeOrder;
                        $isFunded = $activeOrder && $activeOrder->isFunded();
                        $isFundedInProgress = $activeOrder && $activeOrder->isInProgress();
                        $isSeller = (int) auth()->id() === (int) $activeConversation->seller_id;
                        $isBuyer = (int) auth()->id() === (int) $activeConversation->buyer_id;
                    @endphp

                    {{-- 2.1 CHAT HEADER --}}
                    <header class="p-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between flex-wrap gap-3 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                @if($isSupportThread)
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                @elseif($counterpart && $counterpart->avatar)
                                    <img src="{{ asset('storage/' . $counterpart->avatar) }}" alt="{{ $counterpart->name }}" class="w-10 h-10 rounded-2xl object-cover border border-slate-200">
                                @else
                                    <div class="w-10 h-10 rounded-2xl bg-[#20165c]/10 border border-[#20165c]/20 flex items-center justify-center text-[#20165c] font-bold text-sm">
                                        {{ strtoupper(substr($counterpart->name ?? 'C', 0, 1)) }}
                                    </div>
                                @endif
                                @if(!$isSupportThread)
                                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full {{ ($counterpart->is_available ?? true) ? 'bg-emerald-500' : 'bg-slate-500' }} border-2 border-white"></span>
                                @endif
                            </div>

                            <div>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-sm font-bold text-[#121826] mb-0">
                                        {{ $isSupportThread ? 'Official Platform Notice' : ($counterpart->name ?? 'Counterpart') }}
                                    </h2>
                                    @if($counterpart && $counterpart->username)
                                        @php
                                            $creatorUrl = route('creator.profile', $counterpart->username);
                                        @endphp
                                        <a href="{{ $creatorUrl }}" target="_blank" class="text-[11px] text-slate-500 hover:text-[#20165c]">
                                            &#64;{{ $counterpart->username }}
                                        </a>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 text-[10px] text-slate-400">
                                    <span>{{ ($counterpart->is_available ?? true) ? '🟢 Available for Hire' : '⚪ Currently Away' }}</span>
                                    <span>&bull;</span>
                                    <span>Escrow Protected</span>
                                    @if($counterpart && $counterpart->hasReliabilityWarning())
                                        <span>&bull;</span>
                                        <span class="text-amber-600 font-bold">⚠️ Reliability Warning</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Header Actions: Work History, Proposal Trigger & Delete Chat --}}
                        <div class="flex items-center gap-2">
                            {{-- Work History Drawer Trigger Button --}}
                            @if(!$isSupportThread)
                                <button type="button" onclick="openWorkHistoryDrawer()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-medium bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-sm transition-all duration-200 cursor-pointer" title="View order history with this creator">
                                    <span>📋 Work History</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-[#20165c] text-white">{{ $workHistoryCount ?? 0 }}</span>
                                </button>
                            @endif

                            {{-- Service Proposal / Offer Button --}}
                            @if(!$isSupportThread)
                                <button type="button" onclick="openProposalModal()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium bg-[#20165c] hover:bg-[#170f45] text-white shadow-sm transition-all duration-200 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    <span>{{ $isSeller ? 'Offer Service' : 'Pick Seller Service' }}</span>
                                </button>
                            @endif

                            {{-- Delete Conversation Action in Header --}}
                            <form action="{{ route('inbox.destroy', $activeConversation->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this chat?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-xl text-slate-400 hover:text-red-500 hover:bg-red-50 border border-slate-200 transition-all cursor-pointer" title="Delete conversation">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </header>

                    {{-- 2.15 TOP SERVICE SHOWCASE PILLS (COMPACT HORIZONTAL STRIP) --}}
                    @if(!$isSupportThread && isset($services) && $services->isNotEmpty())
                        <div class="px-4 py-2 border-b border-slate-200 bg-white shrink-0 overflow-x-auto scrollbar-none">
                            <div class="flex items-center gap-2.5 min-w-max">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider shrink-0 flex items-center gap-1">
                                    <span>⚡ Services:</span>
                                </span>
                                @foreach($services as $srvItem)
                                    @php
                                        $sSlots = max(0, ($srvItem->max_slots ?? 3) - ($srvItem->active_orders_count ?? 0));
                                        $sThumb = !empty($srvItem->preview_images) && is_array($srvItem->preview_images) ? $srvItem->preview_images[0] : null;
                                        $sThumbUrl = $sThumb ? (str_starts_with($sThumb, 'http') ? $sThumb : asset('storage/' . ltrim($sThumb, '/'))) : null;
                                    @endphp
                                    <button type="button" 
                                            onclick="prefillCustomProposal('{{ addslashes($srvItem->title) }}', {{ (float)($srvItem->starting_price ?? $srvItem->price ?? 600) }}, {{ (int)($srvItem->delivery_days ?? $srvItem->duration_days ?? 3) }}, '{{ $srvItem->id }}', '{{ addslashes($srvItem->description ?? '') }}')"
                                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-50 hover:bg-purple-50 hover:border-purple-200 border border-slate-200 text-slate-800 transition-all cursor-pointer shrink-0 group">
                                        @if($sThumbUrl)
                                            <img src="{{ $sThumbUrl }}" alt="thumb" class="w-4 h-4 rounded object-cover shrink-0">
                                        @else
                                            <span class="text-[#20165c] text-xs">💼</span>
                                        @endif
                                        <span class="group-hover:text-[#20165c] max-w-[140px] truncate font-medium">{{ $srvItem->title }}</span>
                                        <span class="font-bold text-[#20165c] font-mono text-[11px]">From ৳{{ number_format($srvItem->starting_price ?? $srvItem->price ?? 600, 0) }}</span>
                                        @if($sSlots > 0)
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500" title="{{ $sSlots }} slots left"></span>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500" title="Booked out"></span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 2.2 TOP ESCROW STATUS BANNER (CONDITIONAL) --}}
                    @if($activeOrder)
                        @php
                            $orderCodeDisplay = $activeOrder->order_code ?? $activeOrder->order_number;
                            $orderAmountDisplay = (float) ($activeOrder->amount ?? $activeOrder->total_amount);
                            $minorFixAvailable = $activeOrder->status === 'completed' && !$activeOrder->minor_fix_used && $activeOrder->completed_at && $activeOrder->completed_at->addHours(48)->isFuture();
                        @endphp
                        <div class="px-4 py-3 border-b border-slate-200 bg-purple-50/40 shrink-0">
                            <div class="flex items-center justify-between flex-wrap gap-3">
                                
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-[#20165c]/10 text-[#20165c] border border-[#20165c]/20 flex items-center justify-center font-bold text-xs shrink-0">
                                        ৳
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-[#121826]">{{ $activeOrder->custom_title ?? $activeOrder->service?->title ?? 'Custom Service' }}</span>
                                            <span class="text-[10px] text-slate-500 font-mono font-bold">#{{ $orderCodeDisplay }}</span>
                                        </div>
                                        <div class="text-[11px] text-slate-500">
                                            Total: <strong class="text-[#121826]">৳{{ number_format($orderAmountDisplay, 2) }}</strong>
                                            &bull; Platform Fee: ৳{{ number_format($activeOrder->platform_fee, 2) }}
                                            &bull; Payout: <strong class="text-emerald-600">৳{{ number_format($activeOrder->seller_payout ?? $activeOrder->seller_payout_amount, 2) }}</strong>
                                        </div>
                                    </div>
                                </div>

                                {{-- Order Status Badge & Escrow Actions --}}
                                <div class="flex items-center gap-2.5 flex-wrap justify-end">
                                    @if($activeOrder->status === 'in_progress' || $activeOrder->status === 'funded_in_progress')
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-700 border border-emerald-500/30 flex items-center gap-1.5">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                                <span>Funded & In Progress</span>
                                            </span>
                                            @if($activeOrder->deadline_at)
                                                <div id="countdownTimer" data-deadline="{{ $activeOrder->deadline_at->toIso8601String() }}" class="px-2.5 py-1 rounded-lg text-xs font-mono bg-white border border-slate-200 text-[#20165c] font-semibold">
                                                    Calculating countdown...
                                                </div>
                                            @endif
                                            @if($isSeller)
                                                <button type="button" onclick="openDeliveryModal()" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#f47402] hover:bg-[#d96602] text-white shadow-sm transition-all cursor-pointer">
                                                    📦 Deliver Work
                                                </button>
                                                @if(!$activeOrder->extension_used)
                                                    <button type="button" onclick="openExtensionModal()" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-purple-50 hover:bg-purple-100 text-[#20165c] border border-purple-200 transition-all cursor-pointer">
                                                        ⏳ Extend Deadline
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    @elseif($activeOrder->status === 'delivered_preview' || $activeOrder->status === 'delivered')
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-500/10 text-blue-700 border border-blue-500/30">
                                                📦 Work Delivered (Preview)
                                            </span>
                                            @if($isBuyer)
                                                <form action="{{ route('inbox.orders.release', $activeOrder->id) }}" method="POST" class="inline" onsubmit="return confirm('Accept delivery and release escrow funds to creator? (15% platform commission will be deducted).');">
                                                    @csrf
                                                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-medium bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition-all duration-200 cursor-pointer">
                                                        ✅ Approve & Complete
                                                    </button>
                                                </form>
                                                <button type="button" onclick="openRevisionModal()" class="px-3.5 py-2 rounded-xl text-xs font-medium bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-sm transition-all duration-200 cursor-pointer">
                                                    🔄 Request Revision
                                                </button>
                                                <button type="button" onclick="openFakeReportModal()" class="px-3.5 py-2 rounded-xl text-xs font-medium bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-300 transition-all duration-200 cursor-pointer">
                                                    🚨 Report Fake File
                                                </button>
                                            @else
                                                <span class="text-[11px] text-slate-500 italic">
                                                    Client reviewing previews (Auto-completes after 3 days)
                                                </span>
                                            @endif
                                        </div>
                                    @elseif($activeOrder->status === 'awaiting_payment' || $activeOrder->status === 'pending_acceptance')
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-500/10 text-amber-700 border border-amber-500/30">
                                            Awaiting Escrow Payment
                                        </span>
                                        @if($isBuyer)
                                            <form action="{{ route('inbox.orders.fund', $activeOrder->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-medium bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition-all duration-200 cursor-pointer">
                                                    💳 Accept & Pay (৳{{ number_format($orderAmountDisplay, 2) }})
                                                </button>
                                            </form>
                                        @endif
                                    @elseif($activeOrder->status === 'completed')
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                ✅ Completed & Escrow Released
                                            </span>
                                            <a href="{{ route('inbox.orders.download-delivery', $activeOrder->id) }}" class="px-3 py-1 rounded-xl text-xs font-bold bg-[#20165c] hover:bg-[#181045] text-white transition-colors">
                                                📥 Download Full ZIP
                                            </a>
                                            @if($isBuyer && $minorFixAvailable)
                                                <button type="button" onclick="openMinorFixModal()" class="px-3 py-1 rounded-xl text-xs font-bold bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-300 transition-all cursor-pointer">
                                                    🔧 Request Minor Fix (1 Left)
                                                </button>
                                            @endif
                                            @if($isBuyer && $activeOrder->reviews->isEmpty())
                                                <button type="button" onclick="openReviewModal({{ $activeOrder->id }})" class="px-3 py-1 rounded-xl text-xs font-bold bg-purple-50 hover:bg-purple-100 text-[#20165c] border border-purple-200 transition-all cursor-pointer">
                                                    ⭐ Leave Review
                                                </button>
                                            @endif
                                        </div>
                                    @elseif($activeOrder->status === 'disputed')
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-300">
                                            🚨 Arbitration Dispute Active
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 capitalize">
                                            {{ str_replace('_', ' ', $activeOrder->status) }}
                                        </span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endif

                    {{-- 2.3 MESSAGE HISTORY STREAM (SCROLLABLE) --}}
                    <div id="messageHistoryContainer" data-poll-url="{{ route('inbox.poll', $activeConversation->id) }}" data-latest-id="{{ $messages->last()?->id ?? 0 }}" data-order-status="{{ $activeOrder->status ?? '' }}" class="flex-1 overflow-y-auto max-h-[calc(100vh-280px)] p-4 space-y-3 scrollbar-thin scrollbar-thumb-slate-300 min-h-0 bg-white">
                        @forelse($messages as $msg)
                            @php
                                $isMe = (int) $msg->sender_id === (int) auth()->id();
                                $isSystemNotice = $msg->message_type === 'system_notice';
                                $isOrderStatus = $msg->message_type === 'order_status';
                                $isProposal = $msg->message_type === 'service_proposal';
                                $meta = $msg->metadata ?? [];

                                $bodyLower = strtolower($msg->body ?? '');
                                $isClutterNotice = $isSystemNotice ||
                                                   str_contains($bodyLower, 'left the conversation') ||
                                                   str_contains($bodyLower, 're-open') ||
                                                   str_contains($bodyLower, 'frozen') ||
                                                   str_contains($bodyLower, 'declined the re-open') ||
                                                   str_contains($bodyLower, 'approved the re-open') ||
                                                   str_contains($bodyLower, 'welcome to noksha');
                            @endphp

                            {{-- Strip system clutter bubbles --}}
                            @if($isClutterNotice)
                                @continue
                            @endif

                            {{-- ORDER COMPLETED BANNER --}}
                            @if(isset($meta['type']) && $meta['type'] === 'order_completed_banner')
                                <div class="my-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-sm text-center max-w-xl mx-auto" data-msg-id="{{ $msg->id }}">
                                    <div class="flex items-center justify-center gap-2 mb-1.5">
                                        <span class="text-xl">🎉</span>
                                        <span class="text-xs font-black text-emerald-800 uppercase tracking-wider">Project Successfully Completed</span>
                                        <span class="text-xl">✅</span>
                                    </div>
                                    <h4 class="text-sm font-bold text-emerald-900 mb-1">
                                        ✅ {{ $meta['service_title'] ?? 'Creative Service' }} — Completed
                                    </h4>
                                    <p class="text-xs text-emerald-700/80 mb-3 font-mono">
                                        #{{ $meta['order_code'] ?? 'COMPLETED' }}
                                    </p>
                                    <div class="flex items-center justify-center gap-2.5 flex-wrap">
                                        <button type="button" onclick="openProposalModal()" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#20165c] hover:bg-[#181045] text-white shadow-sm transition-all cursor-pointer">
                                            🔄 Order Again
                                        </button>
                                        @if(isset($meta['order_id']))
                                            <a href="{{ route('inbox.orders.download-delivery', $meta['order_id']) }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition-all">
                                                📥 Download Full ZIP
                                            </a>
                                            <button type="button" onclick="openReviewModal({{ $meta['order_id'] }})" class="px-4 py-2 rounded-xl text-xs font-bold bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-300 transition-all cursor-pointer">
                                                ⭐ Leave Review
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                @continue
                            @endif

                            {{-- EXTENSION REQUEST CARD --}}
                            @if(isset($meta['type']) && $meta['type'] === 'extension_request')
                                <div class="my-3 p-4 rounded-2xl bg-amber-50/70 border border-amber-200 max-w-md mx-auto shadow-sm" data-msg-id="{{ $msg->id }}">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-base">⏳</span>
                                        <h5 class="text-xs font-bold text-amber-900 mb-0">Deadline Extension Requested (+{{ $meta['extension_days'] ?? 1 }} Days)</h5>
                                    </div>
                                    <p class="text-xs text-slate-700 leading-relaxed mb-3">
                                        <strong>Reason:</strong> {{ $meta['extension_reason'] ?? 'Additional creative refinement needed.' }}
                                    </p>
                                    @if($isBuyer && isset($meta['order_id']))
                                        <div class="flex items-center gap-2">
                                            <form action="{{ route('inbox.orders.respond-extension', $meta['order_id']) }}" method="POST" class="flex-1">
                                                @csrf
                                                <input type="hidden" name="action" value="accept">
                                                <input type="hidden" name="days" value="{{ $meta['extension_days'] ?? 1 }}">
                                                <button type="submit" class="w-full py-1.5 rounded-lg text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow transition cursor-pointer">
                                                    ✅ Approve (+{{ $meta['extension_days'] ?? 1 }} Days)
                                                </button>
                                            </form>
                                            <form action="{{ route('inbox.orders.respond-extension', $meta['order_id']) }}" method="POST" class="flex-1">
                                                @csrf
                                                <input type="hidden" name="action" value="decline">
                                                <button type="submit" class="w-full py-1.5 rounded-lg text-xs font-bold bg-slate-200 hover:bg-slate-300 text-slate-700 transition cursor-pointer">
                                                    ❌ Decline
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                @continue
                            @endif

                            @if($isOrderStatus)
                                {{-- Centered Clean Order Status Notice --}}
                                <div class="flex justify-center my-1.5" data-msg-id="{{ $msg->id }}">
                                    <div class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-purple-50 border border-[#20165c]/20 text-[#20165c] flex items-center gap-2 shadow-xs max-w-lg text-center">
                                        <svg class="w-3.5 h-3.5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>{{ $msg->body }}</span>
                                        <span class="text-[9px] text-slate-400 font-mono shrink-0">{{ $msg->created_at ? $msg->created_at->format('H:i') : '' }}</span>
                                    </div>
                                </div>
                            @elseif($isProposal)
                                {{-- IN-CHAT CUSTOM OFFER / PROPOSAL CARD --}}
                                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} my-2" data-msg-id="{{ $msg->id }}">
                                    <div class="max-w-md w-full bg-white border border-slate-200 rounded-3xl p-4 shadow-md relative overflow-hidden">
                                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-purple-500/10 rounded-full blur-xl pointer-events-none"></div>

                                        <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-slate-100 mb-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#20165c] border border-purple-200 flex items-center justify-center font-bold text-sm">
                                                    💼
                                                </div>
                                                <div>
                                                    <h4 class="text-xs font-bold text-[#121826] mb-0">Custom Service Offer</h4>
                                                    <span class="text-[10px] text-slate-400 font-mono font-bold">#{{ $meta['order_code'] ?? ($meta['order_number'] ?? 'NOK-SRV') }}</span>
                                                </div>
                                            </div>
                                            <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-[#20165c]/10 text-[#20165c] border border-[#20165c]/20">
                                                ৳{{ number_format($meta['price'] ?? ($meta['amount'] ?? 600), 2) }}
                                            </span>
                                        </div>

                                        <h5 class="text-xs font-bold text-[#121826] mb-1.5">{{ $meta['title'] ?? 'Creative Service Package' }}</h5>
                                        @if(!empty($meta['description']))
                                            <p class="text-xs text-slate-600 leading-relaxed mb-3">
                                                {{ $meta['description'] }}
                                            </p>
                                        @endif

                                        <div class="grid grid-cols-3 gap-2 p-2.5 bg-slate-50 rounded-xl border border-slate-200 text-center mb-3">
                                            <div>
                                                <span class="block text-[10px] text-slate-400 uppercase font-semibold">Delivery</span>
                                                <span class="text-xs font-bold text-[#121826]">{{ $meta['delivery_days'] ?? ($meta['duration_days'] ?? 3) }} Days</span>
                                            </div>
                                            <div>
                                                <span class="block text-[10px] text-slate-400 uppercase font-semibold">Revisions</span>
                                                <span class="text-xs font-bold text-[#121826]">{{ $meta['revisions_allowed'] ?? 2 }} Allowed</span>
                                            </div>
                                            <div>
                                                <span class="block text-[10px] text-slate-400 uppercase font-semibold">Protection</span>
                                                <span class="text-xs font-bold text-emerald-600">100% Escrow</span>
                                            </div>
                                        </div>

                                        {{-- Proposal Action Button --}}
                                        @if(isset($meta['order_id']))
                                            @php
                                                $propOrder = \App\Models\ServiceOrder::find($meta['order_id']);
                                            @endphp
                                            @if($propOrder && in_array($propOrder->status, ['awaiting_payment', 'pending_acceptance']))
                                                @if($isBuyer)
                                                    <form action="{{ route('inbox.orders.fund', $propOrder->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-medium bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition-all duration-200 cursor-pointer flex items-center justify-center gap-1.5">
                                                            <span>💳 Accept & Pay (৳{{ number_format($propOrder->amount ?? $propOrder->total_amount, 2) }})</span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <div class="p-2.5 rounded-xl bg-purple-50 text-[#20165c] text-xs text-center font-semibold">
                                                        ⏳ Waiting for client to accept & pay
                                                    </div>
                                                @endif
                                            @elseif($propOrder && $propOrder->isInProgress())
                                                <div class="p-2 rounded-xl bg-emerald-50 text-emerald-700 text-xs text-center font-bold flex items-center justify-center gap-1.5">
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                                    <span>Escrow Funded & In Progress</span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @else
                                {{-- Regular Chat Message Bubble --}}
                                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} items-end gap-2.5">
                                    @if(!$isMe)
                                        <div class="w-7 h-7 rounded-xl bg-slate-200 border border-slate-300 flex items-center justify-center text-[10px] text-slate-700 font-bold shrink-0">
                                            {{ strtoupper(substr($msg->sender->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif

                                    <div class="max-w-[75%] sm:max-w-md {{ $isMe ? 'bg-[#20165c] text-white rounded-2xl rounded-tr-sm shadow-sm' : 'bg-slate-100 text-slate-900 border border-slate-200 rounded-2xl rounded-tl-sm' }} p-3.5 shadow-sm">
                                        @if(!$isMe)
                                            <div class="text-[10px] font-bold text-slate-500 mb-1">
                                                {{ $msg->sender->name ?? 'User' }}
                                            </div>
                                        @endif

                                        <p class="text-xs leading-relaxed whitespace-pre-wrap break-words mb-1">{{ $msg->body }}</p>

                                        {{-- File Attachments --}}
                                        @if($msg->attachments->isNotEmpty())
                                            <div class="mt-2 pt-2 border-t {{ $isMe ? 'border-indigo-500/40' : 'border-slate-300' }} space-y-1.5">
                                                @foreach($msg->attachments as $att)
                                                    <a href="{{ $att->url }}" target="_blank" class="flex items-center gap-2 p-2 rounded-xl {{ $isMe ? 'bg-white/15 hover:bg-white/25 text-white' : 'bg-white hover:bg-slate-50 text-slate-800 border border-slate-200' }} transition-colors text-xs">
                                                        <svg class="w-4 h-4 text-indigo-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                                        <span class="truncate flex-1 font-mono text-[11px]">{{ $att->file_name }}</span>
                                                        <span class="text-[10px] opacity-75 shrink-0">{{ $att->formatted_size }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="flex justify-end mt-1 text-[9px] {{ $isMe ? 'text-indigo-200' : 'text-slate-400' }}">
                                            {{ $msg->created_at ? $msg->created_at->format('H:i') : '' }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="h-full flex flex-col items-center justify-center text-center p-8 text-slate-500">
                                <div class="w-16 h-16 rounded-3xl bg-slate-100 border border-slate-200 flex items-center justify-center mb-3 text-slate-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-700 mb-1">Secure Escrow Conversation Started</h3>
                                <p class="text-xs text-slate-500 max-w-sm mb-0">
                                    Discuss requirements, agree on deliverables, or select a custom offer above.
                                </p>
                            </div>
                        @endforelse

                        {{-- IN-CHAT DELIVERED PREVIEWS & LOCKED MANIFEST CONTAINER --}}
                        @if($activeOrder && in_array($activeOrder->status, ['delivered_preview', 'delivered']))
                            <div class="my-4 p-4 rounded-3xl bg-white border-2 border-purple-200 shadow-md max-w-2xl mx-auto">
                                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-sm">
                                            📦
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-[#121826] mb-0">Work Delivered for Client Review</h4>
                                            <span class="text-[10px] text-slate-400 font-mono">#{{ $activeOrder->order_code ?? $activeOrder->order_number }}</span>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-300">
                                        🔒 Source Files Locked
                                    </span>
                                </div>

                                {{-- WATERMARKED PREVIEWS GALLERY --}}
                                @if(!empty($activeOrder->delivery_preview_images) && is_array($activeOrder->delivery_preview_images))
                                    <div class="mb-3">
                                        <label class="block text-[11px] font-bold text-slate-600 mb-2">Watermarked Preview Images:</label>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            @foreach($activeOrder->delivery_preview_images as $prevImg)
                                                @php
                                                    $pUrl = str_starts_with($prevImg, 'http') ? $prevImg : asset('storage/' . ltrim($prevImg, '/'));
                                                @endphp
                                                <div class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 group select-none">
                                                    {{-- Anti-theft diagonal watermark overlay --}}
                                                    <div class="absolute inset-0 z-10 noksha-watermark-overlay flex items-center justify-center pointer-events-none p-4">
                                                        <div class="transform -rotate-12 select-none text-center">
                                                            <span class="text-xs font-black text-[#20165c]/40 tracking-widest uppercase block">
                                                                NOKSHA PREVIEW
                                                            </span>
                                                            <span class="text-[9px] font-bold text-[#20165c]/30 uppercase block">
                                                                ESCROW PROTECTED • DO NOT COPY
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <img src="{{ $pUrl }}" alt="Delivery Preview" class="w-full h-44 object-cover select-none pointer-events-none" oncontextmenu="return false;">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- LOCKED FILE MANIFEST --}}
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[11px] font-bold text-slate-700 flex items-center gap-1.5">
                                            <span>🗂️ Deliverable Package Manifest</span>
                                        </span>
                                        <span class="text-[10px] font-bold text-slate-500">Unlocks upon approval</span>
                                    </div>
                                    <div class="space-y-1.5">
                                        @if(!empty($activeOrder->delivery_manifest) && is_array($activeOrder->delivery_manifest))
                                            @foreach($activeOrder->delivery_manifest as $mItem)
                                                <div class="flex items-center justify-between p-2 rounded-lg bg-white border border-slate-200 text-xs">
                                                    <span class="font-mono text-[11px] text-slate-700 truncate max-w-[240px]">{{ $mItem['name'] ?? 'File' }}</span>
                                                    <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                                        🔒 Locked
                                                    </span>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="flex items-center justify-between p-2 rounded-lg bg-white border border-slate-200 text-xs">
                                                <span class="font-mono text-[11px] text-slate-700 truncate">Final Source Bundle (.ZIP)</span>
                                                <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                                    🔒 Locked
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- ACTION BUTTONS FOR BUYER --}}
                                @if($isBuyer)
                                    <div class="flex items-center gap-2.5 flex-wrap">
                                        <form action="{{ route('inbox.orders.release', $activeOrder->id) }}" method="POST" class="flex-1 min-w-[140px]" onsubmit="return confirm('Approve delivery and release escrow funds to creator? (15% platform commission deducted). Full source files will be unlocked.');">
                                            @csrf
                                            <button type="submit" class="w-full py-2.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition-all cursor-pointer">
                                                ✅ Approve & Complete
                                            </button>
                                        </form>
                                        <button type="button" onclick="openRevisionModal()" class="flex-1 min-w-[140px] py-2.5 rounded-xl text-xs font-bold bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-300 transition-all cursor-pointer">
                                            🔄 Request Revision ({{ $activeOrder->revisions_used }}/{{ $activeOrder->revisions_allowed }})
                                        </button>
                                        <button type="button" onclick="openFakeReportModal()" class="py-2.5 px-3 rounded-xl text-xs font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-300 transition-all cursor-pointer">
                                            🚨 Report Fake File
                                        </button>
                                    </div>
                                @else
                                    <p class="text-xs text-slate-500 italic text-center mb-0">
                                        Client is currently reviewing previews. Funds auto-release after 3 days if no disputes are opened.
                                    </p>
                                @endif
                            </div>
                        @endif

                    </div>

                    {{-- 2.4 CHAT INPUT AREA --}}
                    @if($isSupportThread)
                        <div class="p-3 bg-slate-50 border-t border-slate-200 text-slate-500 text-center text-xs text-slate-400 font-medium shrink-0">
                            🔒 This is an official system notice. Replies are disabled.
                        </div>
                    @else
                        <!-- Chat Input Area (Always Active & Frictionless) -->
                        <div class="p-4 bg-white border-t border-slate-200 sticky bottom-0 z-20 shrink-0">
                            <form id="chatMessageForm" action="{{ route('chat.send', $activeConversation->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2.5">
                                @csrf
                                <input type="hidden" name="is_funded" id="isFundedFlag" value="{{ $isFunded ? '1' : '0' }}">
                                <input type="hidden" name="is_funded_in_progress" id="isFundedInProgressFlag" value="{{ $isFundedInProgress ? '1' : '0' }}">

                                <!-- Offer Service Button -->
                                @if($isSeller || (!$isSupportThread && isset($services) && $services->isNotEmpty()))
                                    <button type="button" onclick="openProposalModal()" class="p-2.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-[#20165c] cursor-pointer border border-purple-200 transition shrink-0 flex items-center gap-1.5 text-xs font-bold" title="Recommend or offer a custom service package">
                                        <span>💼</span>
                                        <span class="hidden sm:inline">{{ $isSeller ? 'Offer Service' : 'Pick Service' }}</span>
                                    </button>
                                @endif

                                <!-- Attachment Button -->
                                <label for="chat_file" class="p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 cursor-pointer border border-slate-200 transition shrink-0" title="Attach file">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    <input type="file" id="chat_file" name="attachment" class="hidden">
                                </label>

                                <!-- Text Input Field -->
                                <input type="text" name="message" id="messageInput" placeholder="Type your message to {{ $counterpart->name ?? 'recipient' }}..." required
                                       class="flex-1 bg-slate-50 border border-slate-300 focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] rounded-xl px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none transition">

                                <!-- Send Button -->
                                <button type="submit" class="px-5 py-2.5 bg-[#20165c] hover:bg-[#181045] text-white text-sm font-semibold rounded-xl flex items-center gap-2 transition shadow-sm shrink-0">
                                    <span>Send</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </form>
                        </div>
                    @endif

                @else
                    {{-- Blank / No Conversation Selected State --}}
                    <div class="flex-1 flex flex-col items-center justify-center p-8 text-center text-slate-500">
                        <div class="w-20 h-20 rounded-3xl bg-slate-100 border border-slate-200 flex items-center justify-center mb-4 text-slate-400 shadow-sm">
                            <svg class="w-10 h-10 text-indigo-400/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <h2 class="text-base font-bold text-[#121826] mb-1">Your Noksha Escrow Inbox</h2>
                        <p class="text-xs text-slate-400 max-w-sm mb-4 leading-relaxed">
                            Select a thread from the left sidebar to resume messaging, or visit a creator's public profile to send a direct project inquiry.
                        </p>
                        <a href="{{ route('creators.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-[#20165c] hover:bg-[#181045] text-white transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span>Browse Verified Creators</span>
                        </a>
                    </div>
                @endif

            </section>

        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- 3. PROPOSE / CUSTOM OFFER BUILDER MODAL                  --}}
{{-- ======================================================== --}}
@if($activeConversation)
<div id="serviceProposalModal" data-modal="service-proposal" data-service-modal style="display: none;" class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden transition-opacity duration-200" onclick="if(event.target === this) handleModalClose()">
    <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#20165c] border border-purple-200 flex items-center justify-center font-bold text-xs">
                    💼
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#121826] mb-0">{{ $isSeller ? 'Offer / Recommend Service' : 'Request Creative Service' }}</h3>
                    <p class="text-[11px] text-slate-400 mb-0">Minimum order threshold ৳600.00 BDT &bull; 100% Escrow Protected</p>
                </div>
            </div>
            <button type="button" onclick="handleModalClose()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:text-slate-900 flex items-center justify-center text-sm cursor-pointer" title="Close">&times;</button>
        </div>

        <form id="serviceProposalForm" action="{{ route('inbox.service-proposal.store', $activeConversation->id) }}" method="POST" class="p-6 space-y-4">
            @csrf

            {{-- Optional: Pick an existing seller service --}}
            @if(isset($services) && $services->isNotEmpty())
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pick Service Template (Optional)</label>
                    <select name="service_id" id="serviceSelect" onchange="handleServiceSelect(this)" class="w-full px-3 py-2 rounded-xl text-xs bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-[#20165c]">
                        <option value="">-- Custom Custom Offer / Scope Below --</option>
                        @foreach($services as $srv)
                            <option value="{{ $srv->id }}" data-title="{{ $srv->title }}" data-price="{{ $srv->starting_price ?? $srv->price }}" data-days="{{ $srv->delivery_days ?? $srv->duration_days }}" data-desc="{{ $srv->description }}" data-revisions="{{ $srv->revisions_count ?? 2 }}">
                                {{ $srv->title }} (৳{{ number_format($srv->starting_price ?? $srv->price, 0) }} - {{ $srv->delivery_days ?? $srv->duration_days }} Days)
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Title --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Service Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" id="proposalTitleInput" required placeholder="e.g. Modern Brand Identity & Vector Source Files" class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#20165c]">
            </div>

            {{-- Price, Delivery Days & Revisions Grid --}}
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Price (৳) <span class="text-rose-500">*</span></label>
                    <input type="number" name="price" id="proposalPriceInput" min="600" step="50" value="600" required class="w-full px-3 py-2 rounded-xl text-xs bg-white border border-slate-300 text-slate-900 font-mono focus:outline-none focus:border-[#20165c]">
                    <span class="text-[10px] text-slate-400">Min ৳600</span>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Days <span class="text-rose-500">*</span></label>
                    <input type="number" name="delivery_days" id="proposalDaysInput" min="1" max="90" value="3" required class="w-full px-3 py-2 rounded-xl text-xs bg-white border border-slate-300 text-slate-900 font-mono focus:outline-none focus:border-[#20165c]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Revisions <span class="text-rose-500">*</span></label>
                    <input type="number" name="revisions_allowed" id="proposalRevisionsInput" min="0" max="10" value="2" required class="w-full px-3 py-2 rounded-xl text-xs bg-white border border-slate-300 text-slate-900 font-mono focus:outline-none focus:border-[#20165c]">
                </div>
            </div>

            {{-- Scope / Requirements --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Scope of Work & Deliverables <span class="text-rose-500">*</span></label>
                <textarea name="description" id="proposalDescInput" rows="3" required placeholder="Outline project milestones, formats (.AI, .FIG, .PSD), and deliverables..." class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#20165c] resize-none"></textarea>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-200">
                <button type="button" onclick="handleModalClose()" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors cursor-pointer">
                    Cancel
                </button>
                <button type="submit" id="submitProposalBtn" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#20165c] hover:bg-[#181045] text-white shadow-sm transition-all cursor-pointer">
                    🚀 Dispatch Custom Offer
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ======================================================== --}}
{{-- 3B. DELIVER WORK MULTI-FILE MODAL (SELLER ONLY)          --}}
{{-- ======================================================== --}}
@if(isset($activeOrder) && $activeOrder && !empty($isSeller) && $activeOrder->isInProgress())
<div id="deliveryModal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden transition-opacity duration-200" onclick="if(event.target === this) closeDeliveryModal()">
    <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#20165c] border border-purple-200 flex items-center justify-center font-bold text-xs">
                    📦
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#121826] mb-0">Deliver Project Work</h3>
                    <p class="text-[11px] text-slate-400 mb-0">Upload previews (watermarked) and source file</p>
                </div>
            </div>
            <button type="button" onclick="closeDeliveryModal()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:text-slate-900 flex items-center justify-center text-sm cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('inbox.orders.deliver', $activeOrder->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            
            {{-- Preview Images --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Preview Images (1 to 5 images: JPG, PNG, WebP) <span class="text-rose-500">*</span></label>
                <input type="file" name="preview_images[]" multiple accept="image/jpeg,image/png,image/webp" required class="w-full text-xs text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#20165c] file:text-white hover:file:bg-[#181045] bg-white border border-slate-300 rounded-xl p-2 cursor-pointer">
                <p class="text-[10px] text-slate-400 mt-1">These will be displayed to client with non-removable Noksha Preview watermark.</p>
            </div>

            {{-- Source File --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Final Source Deliverable (ZIP, PSD, AI, FIG, etc., Max 200MB) <span class="text-rose-500">*</span></label>
                <input type="file" name="source_file" required class="w-full text-xs text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#20165c] file:text-white hover:file:bg-[#181045] bg-white border border-slate-300 rounded-xl p-2 cursor-pointer">
                <p class="text-[10px] text-slate-400 mt-1">Remains locked until client approves delivery and releases escrow.</p>
            </div>

            {{-- Optional Instruction PDF --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Instruction / Handover PDF (Optional)</label>
                <input type="file" name="instruction_pdf" accept="application/pdf" class="w-full text-xs text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 bg-white border border-slate-300 rounded-xl p-2 cursor-pointer">
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Delivery Notes / Handover Instructions</label>
                <textarea name="delivery_notes" rows="3" placeholder="Provide instructions on fonts, layers, or software versions..." class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#20165c] resize-none"></textarea>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-200">
                <button type="button" onclick="closeDeliveryModal()" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#f47402] hover:bg-[#d96602] text-white shadow-sm transition-all cursor-pointer">
                    🚀 Submit Delivery for Review
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ======================================================== --}}
{{-- 3C. REQUEST REVISION MODAL (BUYER ONLY)                 --}}
{{-- ======================================================== --}}
@if(isset($activeOrder) && $activeOrder && !empty($isBuyer))
<div id="revisionModal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden transition-opacity duration-200" onclick="if(event.target === this) closeRevisionModal()">
    <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center font-bold text-xs">
                    🔄
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#121826] mb-0">Request Project Revision</h3>
                    <p class="text-[11px] text-slate-400 mb-0">Revisions Used: {{ $activeOrder->revisions_used }} of {{ $activeOrder->revisions_allowed }}</p>
                </div>
            </div>
            <button type="button" onclick="closeRevisionModal()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:text-slate-900 flex items-center justify-center text-sm cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('inbox.orders.revision', $activeOrder->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Detailed Revision Instructions <span class="text-rose-500">*</span></label>
                <textarea name="revision_notes" rows="4" required placeholder="Clearly explain what changes or corrections are needed..." class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#20165c] resize-none"></textarea>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-200">
                <button type="button" onclick="closeRevisionModal()" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#f47402] hover:bg-[#d96602] text-white shadow-sm transition-all cursor-pointer">
                    🔄 Dispatch Revision Request
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ======================================================== --}}
{{-- 3D. 48-HOUR MINOR FIX MODAL (BUYER ONLY)                --}}
{{-- ======================================================== --}}
@if(isset($activeOrder) && $activeOrder && !empty($isBuyer))
<div id="minorFixModal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden transition-opacity duration-200" onclick="if(event.target === this) closeMinorFixModal()">
    <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#20165c] border border-purple-200 flex items-center justify-center font-bold text-xs">
                    🔧
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#121826] mb-0">Request 48-Hour Minor Fix</h3>
                    <p class="text-[11px] text-slate-400 mb-0">1 complimentary minor adjustment included within 48h of completion</p>
                </div>
            </div>
            <button type="button" onclick="closeMinorFixModal()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:text-slate-900 flex items-center justify-center text-sm cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('inbox.orders.minor-fix', $activeOrder->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Minor Adjustment Details <span class="text-rose-500">*</span></label>
                <textarea name="minor_fix_notes" rows="3" required placeholder="Specify slight color adjustments, typos, or file format exports..." class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#20165c] resize-none"></textarea>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-200">
                <button type="button" onclick="closeMinorFixModal()" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#20165c] hover:bg-[#181045] text-white shadow-sm transition-all cursor-pointer">
                    🔧 Submit Minor Fix (1 Use)
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ======================================================== --}}
{{-- 3E. EXTEND DEADLINE MODAL (CREATOR ONLY)                 --}}
{{-- ======================================================== --}}
@if(isset($activeOrder) && $activeOrder && !empty($isSeller))
<div id="extensionModal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden transition-opacity duration-200" onclick="if(event.target === this) closeExtensionModal()">
    <div class="relative w-full max-w-md bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#20165c] border border-purple-200 flex items-center justify-center font-bold text-xs">
                    ⏳
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#121826] mb-0">Request Deadline Extension</h3>
                    <p class="text-[11px] text-slate-400 mb-0">Maximum 1 extension allowed per order</p>
                </div>
            </div>
            <button type="button" onclick="closeExtensionModal()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:text-slate-900 flex items-center justify-center text-sm cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('inbox.orders.request-extension', $activeOrder->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Extension Duration <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="flex flex-col items-center justify-center p-3 rounded-xl border border-slate-300 hover:border-[#20165c] cursor-pointer text-center has-[:checked]:border-[#20165c] has-[:checked]:bg-purple-50">
                        <input type="radio" name="extension_days" value="1" checked class="hidden">
                        <span class="text-sm font-bold text-[#121826]">+1 Day</span>
                        <span class="text-[10px] text-slate-500">24 Hours</span>
                    </label>
                    <label class="flex flex-col items-center justify-center p-3 rounded-xl border border-slate-300 hover:border-[#20165c] cursor-pointer text-center has-[:checked]:border-[#20165c] has-[:checked]:bg-purple-50">
                        <input type="radio" name="extension_days" value="2" class="hidden">
                        <span class="text-sm font-bold text-[#121826]">+2 Days</span>
                        <span class="text-[10px] text-slate-500">48 Hours</span>
                    </label>
                    <label class="flex flex-col items-center justify-center p-3 rounded-xl border border-slate-300 hover:border-[#20165c] cursor-pointer text-center has-[:checked]:border-[#20165c] has-[:checked]:bg-purple-50">
                        <input type="radio" name="extension_days" value="3" class="hidden">
                        <span class="text-sm font-bold text-[#121826]">+3 Days</span>
                        <span class="text-[10px] text-slate-500">72 Hours</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Reason for Extension <span class="text-rose-500">*</span></label>
                <textarea name="extension_reason" rows="3" required placeholder="Explain why extra time is required for this delivery..." class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#20165c] resize-none"></textarea>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-200">
                <button type="button" onclick="closeExtensionModal()" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#20165c] hover:bg-[#181045] text-white shadow-sm transition-all cursor-pointer">
                    ⏳ Send Extension Request
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ======================================================== --}}
{{-- 3F. REPORT EMPTY / FAKE FILE DISPUTE MODAL (BUYER ONLY)   --}}
{{-- ======================================================== --}}
@if(isset($activeOrder) && $activeOrder && !empty($isBuyer))
<div id="fakeReportModal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden transition-opacity duration-200" onclick="if(event.target === this) closeFakeReportModal()">
    <div class="relative w-full max-w-md bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-rose-50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xs">
                    🚨
                </div>
                <div>
                    <h3 class="text-sm font-bold text-rose-950 mb-0">Report Empty / Fake Delivery</h3>
                    <p class="text-[11px] text-rose-700 mb-0">Dispute halts auto-release and alerts Noksha support</p>
                </div>
            </div>
            <button type="button" onclick="closeFakeReportModal()" class="w-8 h-8 rounded-lg bg-rose-100 text-rose-700 hover:text-rose-950 flex items-center justify-center text-sm cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('inbox.orders.report-fake', $activeOrder->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Explain the Delivery Issue <span class="text-rose-500">*</span></label>
                <textarea name="report_reason" rows="4" required placeholder="Describe why this delivery is empty, corrupt, or fake..." class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-rose-500 resize-none"></textarea>
            </div>

            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-[11px] text-amber-900 leading-relaxed">
                ⚠️ Opening a dispute will instantly stop escrow release, penalize the creator with 25 demerits, and assign an administrator to review the delivered files.
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-200">
                <button type="button" onclick="closeFakeReportModal()" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-500 text-white shadow-sm transition-all cursor-pointer">
                    🚨 Submit Formal Dispute
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ======================================================== --}}
{{-- 3G. ORDER REVIEW MODAL (5-STAR RATING & FEEDBACK)        --}}
{{-- ======================================================== --}}
<div id="orderReviewModal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden transition-opacity duration-200" onclick="if(event.target === this) closeReviewModal()">
    <div class="relative w-full max-w-md bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center font-bold text-xs">
                    ⭐
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#121826] mb-0">Leave Verified Creator Review</h3>
                    <p class="text-[11px] text-slate-400 mb-0">Your review will be publicly visible on creator's profile</p>
                </div>
            </div>
            <button type="button" onclick="closeReviewModal()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:text-slate-900 flex items-center justify-center text-sm cursor-pointer">&times;</button>
        </div>

        <form id="orderReviewForm" action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            {{-- Star Rating --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">Overall Rating <span class="text-rose-500">*</span></label>
                <div class="flex items-center gap-2 text-2xl" id="starRatingContainer">
                    <input type="hidden" name="rating" id="selectedRatingInput" value="5">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="setStarRating({{ $i }})" class="star-rating-btn text-amber-400 hover:scale-110 transition cursor-pointer" data-star="{{ $i }}">
                            ★
                        </button>
                    @endfor
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Feedback & Experience <span class="text-rose-500">*</span></label>
                <textarea name="comment" rows="3" required placeholder="Share details about communication, quality, and delivery speed..." class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#20165c] resize-none"></textarea>
            </div>

            {{-- Evidence Images (Up to 3) --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Evidence / Artifact Screenshots <span class="text-slate-400 font-normal">(Optional, max 3 images)</span>
                </label>
                <input type="file" name="evidence_images[]" multiple accept="image/png,image/jpeg,image/webp" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-[#20165c] hover:file:bg-purple-100 border border-slate-300 rounded-xl p-1 bg-white cursor-pointer">
                <p class="text-[10px] text-slate-400 mt-1 mb-0">Upload deliverable previews or proof screenshots (PNG, JPG, WEBP, max 5MB each)</p>
            </div>

            {{-- Optional Complaint / Issue Flag --}}
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl">
                <label class="flex items-start gap-2.5 cursor-pointer mb-0">
                    <input type="checkbox" name="is_complaint" value="1" class="mt-0.5 rounded border-slate-300 text-rose-600 focus:ring-rose-500 cursor-pointer">
                    <div>
                        <span class="text-xs font-bold text-slate-800">Report an Issue / Complaint</span>
                        <p class="text-[11px] text-slate-500 mb-0">Flag this review for inspection in the Noksha Admin Dispute & Arbitration Hub.</p>
                    </div>
                </label>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-200">
                <button type="button" onclick="closeReviewModal()" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#20165c] hover:bg-[#181045] text-white shadow-sm transition-all cursor-pointer">
                    ⭐ Publish Verified Review
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======================================================== --}}
{{-- 3H. INSUFFICIENT WALLET BALANCE SHORTFALL MODAL          --}}
{{-- ======================================================== --}}
<div id="insufficientFundsModal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden transition-opacity duration-200" onclick="if(event.target === this) closeInsufficientFundsModal()">
    <div class="relative w-full max-w-md bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-amber-50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xs">
                    💳
                </div>
                <div>
                    <h3 class="text-sm font-bold text-amber-950 mb-0">Insufficient Wallet Balance</h3>
                    <p class="text-[11px] text-amber-700 mb-0">Deposit funds to proceed with escrow protection</p>
                </div>
            </div>
            <button type="button" onclick="closeInsufficientFundsModal()" class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 hover:text-amber-950 flex items-center justify-center text-sm cursor-pointer">&times;</button>
        </div>

        <div class="p-6 space-y-4">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-center">
                <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Shortfall Needed</span>
                <span class="text-2xl font-black text-[#f47402] font-mono" id="shortfallAmountDisplay">৳0.00</span>
                <span class="block text-[11px] text-slate-400 mt-1">Total required exceeds your current wallet balance</span>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed mb-0">
                You need to deposit funds into your Noksha Wallet to safely fund this custom creative order. Once deposited, you can return to accept & pay immediately.
            </p>

            <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-200">
                <button type="button" onclick="closeInsufficientFundsModal()" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors cursor-pointer">
                    Dismiss
                </button>
                <a href="{{ route('wallet.index', ['tab' => 'deposit']) }}" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#20165c] hover:bg-[#181045] text-white shadow-sm transition-all cursor-pointer">
                    💳 Deposit Funds Now
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- 3I. WORK HISTORY SLIDE-OVER DRAWER                       --}}
{{-- ======================================================== --}}
<div id="workHistoryDrawer" style="display: none;" class="fixed inset-0 z-50 overflow-hidden hidden" onclick="if(event.target === this) closeWorkHistoryDrawer()">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity duration-300"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div class="w-screen max-w-md bg-white border-l border-slate-200 shadow-2xl flex flex-col" onclick="event.stopPropagation()">
            
            {{-- Drawer Header --}}
            <div class="p-5 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-2xl bg-[#20165c]/10 text-[#20165c] border border-[#20165c]/20 flex items-center justify-center font-bold text-sm">
                        📋
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[#121826] mb-0">Work History</h3>
                        <p class="text-[11px] text-slate-400 mb-0">Orders with {{ $counterpart->name ?? 'Creator' }} ({{ $workHistoryCount ?? 0 }})</p>
                    </div>
                </div>
                <button type="button" onclick="closeWorkHistoryDrawer()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:text-slate-900 flex items-center justify-center text-sm cursor-pointer">&times;</button>
            </div>

            {{-- Orders List --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-3 scrollbar-thin">
                @forelse($workHistoryOrders as $hOrd)
                    @php
                        $hReview = $hOrd->reviews->first();
                    @endphp
                    <div class="p-3.5 rounded-2xl border border-slate-200 bg-slate-50/60 hover:bg-slate-50 transition-all space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs font-bold text-[#20165c]">#{{ $hOrd->order_code ?? $hOrd->order_number }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $hOrd->created_at->format('M d, Y') }}</span>
                        </div>
                        <h5 class="text-xs font-bold text-[#121826] mb-0">{{ $hOrd->custom_title ?? $hOrd->service?->title ?? 'Custom Creative Project' }}</h5>
                        <div class="flex items-center justify-between text-xs pt-1 border-t border-slate-200/60">
                            <span class="font-mono font-bold text-[#121826]">৳{{ number_format($hOrd->amount ?? $hOrd->total_amount, 2) }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $hOrd->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-700' }} capitalize">
                                {{ str_replace('_', ' ', $hOrd->status) }}
                            </span>
                        </div>
                        @if($hReview)
                            <div class="p-2 rounded-xl bg-amber-50/80 border border-amber-200 text-xs">
                                <div class="flex items-center gap-1 text-amber-500 font-bold mb-0.5">
                                    @for($s = 1; $s <= ($hReview->rating ?? 5); $s++)
                                        ★
                                    @endfor
                                </div>
                                <p class="text-[11px] text-slate-700 italic mb-0">"{{ $hReview->comment }}"</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400">
                        <span class="text-2xl block mb-2">📋</span>
                        <p class="text-xs mb-0">No past service orders between you yet.</p>
                    </div>
                @endforelse
            </div>

            <div class="p-4 border-t border-slate-200 bg-slate-50">
                <button type="button" onclick="closeWorkHistoryDrawer()" class="w-full py-2.5 rounded-xl text-xs font-bold bg-slate-200 hover:bg-slate-300 text-slate-800 transition cursor-pointer">
                    Close History
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- 4. INLINE JAVASCRIPT LOGIC & ANTI-BYPASS GUARD           --}}
{{-- ======================================================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Auto-scroll message stream to bottom
    const historyContainer = document.getElementById('messageHistoryContainer');
    if (historyContainer) {
        historyContainer.scrollTop = historyContainer.scrollHeight;
    }

    // 2. Real-time conversation thread search
    const searchInput = document.getElementById('conversationSearchInput');
    const threads = document.querySelectorAll('.conversation-thread-item');
    if (searchInput && threads.length > 0) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            threads.forEach(thread => {
                const name = thread.dataset.searchName || '';
                const text = thread.dataset.searchText || '';
                if (name.includes(query) || text.includes(query)) {
                    thread.style.display = 'block';
                } else {
                    thread.style.display = 'none';
                }
            });
        });
    }

    // 3. Live Escrow Delivery Countdown Timer
    const countdownEl = document.getElementById('countdownTimer');
    if (countdownEl && countdownEl.dataset.deadline) {
        const deadline = new Date(countdownEl.dataset.deadline).getTime();
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = deadline - now;
            if (distance < 0) {
                countdownEl.innerHTML = '<span class="text-rose-600 font-bold">⚠️ Overdue</span>';
                return;
            }
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const mins = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const secs = Math.floor((distance % (1000 * 60)) / 1000);
            countdownEl.innerHTML = `⏱️ ${days}d ${hours}h ${mins}m ${secs}s left`;
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    // 4. Form Submit Anti-Bypass Guard
    const messageForm = document.getElementById('chatMessageForm');
    if (messageForm) {
        messageForm.addEventListener('submit', function (e) {
            const bodyInput = document.getElementById('messageInput');
            const text = (bodyInput ? bodyInput.value : '').trim();
            const isFunded = document.getElementById('isFundedFlag')?.value === '1';

            // If order is not funded, check for sensitive contact info
            if (!isFunded && text !== '') {
                const phoneRegex = /(?:\+?880?|01)\d{8,9}|\b(?:\+?88|01)?\d{9,11}\b|\b\d{10,14}\b/;
                const emailRegex = /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/;
                const linkRegex = /(https?:\/\/|www\.|wa\.me|wa\.link|facebook\.com|fb\.com|t\.me|telegram|whatsapp|instagram\.com|discord\.gg|linkedin\.com)/i;

                const stripped = text.replace(/[\s\-\.\(\)]/g, '');
                const hasPhone = phoneRegex.test(text) || /(?:\+?88|01)\d{8,11}/.test(stripped);
                const hasEmail = emailRegex.test(text);
                const hasLink = linkRegex.test(text);

                if (hasPhone || hasEmail || hasLink) {
                    e.preventDefault();
                    if (typeof showNokshaToast === 'function') {
                        showNokshaToast('⚠️ Security Policy Alert: Sharing personal phone numbers, email addresses, or external links outside Noksha is strictly prohibited.', 'error');
                    } else {
                        alert('⚠️ Security Policy Alert: Sharing personal phone numbers, email addresses, or external links outside Noksha is strictly prohibited.');
                    }
                    return false;
                }
            }
        });
    }

    // 5. Lightweight AJAX Polling for Live Inbox Updates (every 4 seconds)
    if (historyContainer && historyContainer.dataset.pollUrl) {
        const pollUrl = historyContainer.dataset.pollUrl;
        let latestMsgId = parseInt(historyContainer.dataset.latestId || '0', 10);
        const currentOrderStatus = historyContainer.dataset.orderStatus || '';

        function pollNewMessages() {
            fetch(`${pollUrl}?after_id=${latestMsgId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Poll failed');
                return res.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    // Auto-sync if order status transitioned
                    if (data.active_order && data.active_order.status !== currentOrderStatus) {
                        window.location.reload();
                        return;
                    }

                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(msg => {
                            if (!document.querySelector(`[data-msg-id="${msg.id}"]`)) {
                                appendPolledMessage(msg, historyContainer);
                            }
                            if (msg.id > latestMsgId) {
                                latestMsgId = msg.id;
                            }
                        });
                        historyContainer.scrollTop = historyContainer.scrollHeight;
                    }
                }
            })
            .catch(() => {});
        }

        setInterval(pollNewMessages, 4000);
    }

    // 6. Global Escape key listener to dismiss open modals
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' || e.key === 'Esc') {
            handleModalClose();
            closeDeliveryModal();
            closeRevisionModal();
            closeMinorFixModal();
            closeExtensionModal();
            closeFakeReportModal();
            closeReviewModal();
            closeInsufficientFundsModal();
            closeWorkHistoryDrawer();
        }
    });

    // 7. Check session prompts
    @if(session('insufficient_funds'))
        openInsufficientFundsModal({{ session('shortfall') ?? 0 }});
    @endif
    @if(session('prompt_review_order_id'))
        openReviewModal({{ session('prompt_review_order_id') }});
    @endif
});

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
}

function appendPolledMessage(msg, container) {
    const div = document.createElement('div');
    div.dataset.msgId = msg.id;

    if (msg.message_type === 'system_notice') {
        return;
    }
    const bodyLower = (msg.body || '').toLowerCase();
    if (bodyLower.includes('left the conversation') || bodyLower.includes('re-open') || bodyLower.includes('frozen') || bodyLower.includes('declined') || bodyLower.includes('welcome to noksha')) {
        return;
    }

    if (msg.message_type === 'order_status') {
        div.className = 'flex justify-center my-2';
        div.innerHTML = `
            <div class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-purple-50 border border-[#20165c]/20 text-[#20165c] flex items-center gap-2 shadow-xs max-w-lg text-center">
                <span>${escapeHtml(msg.body)}</span>
                <span class="text-[9px] text-slate-400 font-mono shrink-0">${msg.created_at_human || ''}</span>
            </div>
        `;
    } else {
        div.className = `flex ${msg.is_me ? 'justify-end' : 'justify-start'} items-end gap-2.5`;
        let attachmentsHtml = '';
        if (msg.attachments && msg.attachments.length > 0) {
            attachmentsHtml = `<div class="mt-2 pt-2 border-t ${msg.is_me ? 'border-indigo-500/40' : 'border-slate-300'} space-y-1.5">` +
                msg.attachments.map(att => `
                    <a href="${att.url}" target="_blank" class="flex items-center gap-2 p-2 rounded-xl ${msg.is_me ? 'bg-white/15 hover:bg-white/25 text-white' : 'bg-white hover:bg-slate-50 text-slate-800 border border-slate-200'} transition-colors text-xs">
                        <svg class="w-4 h-4 text-indigo-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        <span class="truncate flex-1 font-mono text-[11px]">${escapeHtml(att.file_name)}</span>
                        <span class="text-[10px] opacity-75 shrink-0">${escapeHtml(att.size || '')}</span>
                    </a>
                `).join('') + `</div>`;
        }

        const avatarHtml = msg.is_me ? '' : `
            <div class="w-7 h-7 rounded-xl bg-slate-200 border border-slate-300 flex items-center justify-center text-[10px] text-slate-700 font-bold shrink-0">
                ${(msg.sender_name || 'U').substring(0, 1).toUpperCase()}
            </div>
        `;

        div.innerHTML = `
            ${avatarHtml}
            <div class="max-w-[75%] sm:max-w-md ${msg.is_me ? 'bg-[#20165c] text-white rounded-2xl rounded-tr-sm shadow-sm' : 'bg-slate-100 text-slate-900 border border-slate-200 rounded-2xl rounded-tl-sm'} p-3.5 shadow-sm">
                ${!msg.is_me ? `<div class="text-[10px] font-bold text-slate-500 mb-1">${escapeHtml(msg.sender_name || 'User')}</div>` : ''}
                <p class="text-xs leading-relaxed whitespace-pre-wrap break-words mb-1">${escapeHtml(msg.body)}</p>
                ${attachmentsHtml}
                <div class="flex justify-end mt-1 text-[9px] ${msg.is_me ? 'text-indigo-200' : 'text-slate-400'}">
                    ${msg.created_at_human || ''}
                </div>
            </div>
        `;
    }

    container.appendChild(div);
}

// Modal open/close helpers
function openProposalModal() {
    const modal = document.getElementById('serviceProposalModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
    }
}

function handleModalClose() {
    const modal = document.getElementById('serviceProposalModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
    }
}

function prefillCustomProposal(title, price, days, serviceId = '', desc = '', revisions = 2) {
    openProposalModal();
    const titleInput = document.getElementById('proposalTitleInput');
    const priceInput = document.getElementById('proposalPriceInput');
    const daysInput = document.getElementById('proposalDaysInput');
    const descInput = document.getElementById('proposalDescInput');
    const serviceSelect = document.getElementById('serviceSelect');
    const revisionsInput = document.getElementById('proposalRevisionsInput');

    if (titleInput && title) titleInput.value = title;
    if (priceInput && price) priceInput.value = price;
    if (daysInput && days) daysInput.value = days;
    if (descInput && desc) descInput.value = desc;
    if (revisionsInput && revisions) revisionsInput.value = revisions;
    if (serviceSelect && serviceId) serviceSelect.value = serviceId;
}

function handleServiceSelect(select) {
    const opt = select.options[select.selectedIndex];
    if (opt && opt.value) {
        document.getElementById('proposalTitleInput').value = opt.dataset.title || '';
        document.getElementById('proposalPriceInput').value = opt.dataset.price || '600';
        document.getElementById('proposalDaysInput').value = opt.dataset.days || '3';
        document.getElementById('proposalDescInput').value = opt.dataset.desc || '';
        if (document.getElementById('proposalRevisionsInput')) {
            document.getElementById('proposalRevisionsInput').value = opt.dataset.revisions || '2';
        }
    }
}

function openDeliveryModal() {
    const modal = document.getElementById('deliveryModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
    }
}

function closeDeliveryModal() {
    const modal = document.getElementById('deliveryModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
    }
}

function openRevisionModal() {
    const modal = document.getElementById('revisionModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
    }
}

function closeRevisionModal() {
    const modal = document.getElementById('revisionModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
    }
}

function openMinorFixModal() {
    const modal = document.getElementById('minorFixModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
    }
}

function closeMinorFixModal() {
    const modal = document.getElementById('minorFixModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
    }
}

function openExtensionModal() {
    const modal = document.getElementById('extensionModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
    }
}

function closeExtensionModal() {
    const modal = document.getElementById('extensionModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
    }
}

function openFakeReportModal() {
    const modal = document.getElementById('fakeReportModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
    }
}

function closeFakeReportModal() {
    const modal = document.getElementById('fakeReportModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
    }
}

function openReviewModal(orderId) {
    const modal = document.getElementById('orderReviewModal');
    const form = document.getElementById('orderReviewForm');
    if (modal && form && orderId) {
        form.action = `/inbox/orders/${orderId}/review`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
    }
}

function closeReviewModal() {
    const modal = document.getElementById('orderReviewModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
    }
}

function setStarRating(rating) {
    document.getElementById('selectedRatingInput').value = rating;
    const buttons = document.querySelectorAll('.star-rating-btn');
    buttons.forEach((btn, index) => {
        if (index < rating) {
            btn.classList.add('text-amber-400');
            btn.classList.remove('text-slate-300');
        } else {
            btn.classList.remove('text-amber-400');
            btn.classList.add('text-slate-300');
        }
    });
}

function openInsufficientFundsModal(shortfall) {
    const modal = document.getElementById('insufficientFundsModal');
    const display = document.getElementById('shortfallAmountDisplay');
    if (display) {
        display.textContent = '৳' + Number(shortfall).toFixed(2);
    }
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
    }
}

function closeInsufficientFundsModal() {
    const modal = document.getElementById('insufficientFundsModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
    }
}

function openWorkHistoryDrawer() {
    const drawer = document.getElementById('workHistoryDrawer');
    if (drawer) {
        drawer.classList.remove('hidden');
        drawer.style.display = 'block';
    }
}

function closeWorkHistoryDrawer() {
    const drawer = document.getElementById('workHistoryDrawer');
    if (drawer) {
        drawer.classList.add('hidden');
        drawer.style.display = 'none';
    }
}

function showNokshaToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    const isError = type === 'error';
    toast.className = `pointer-events-auto flex items-center justify-between gap-3 p-4 rounded-2xl ${
        isError ? 'bg-rose-950/95 border-rose-500/50 text-rose-200' : 'bg-emerald-950/95 border-emerald-500/50 text-emerald-200'
    } border shadow-2xl backdrop-blur-xl transition-all duration-300 transform translate-y-2 opacity-0`;
    
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 ${isError ? 'text-rose-400' : 'text-emerald-400'} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${
                    isError ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                }"/>
            </svg>
            <span class="text-xs font-semibold leading-relaxed">${message}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-white text-base leading-none">&times;</button>
    `;

    container.appendChild(toast);
    setTimeout(() => toast.classList.remove('translate-y-2', 'opacity-0'), 10);
    setTimeout(() => {
        toast.classList.add('opacity-0', '-translate-y-2');
        setTimeout(() => toast.remove(), 300);
    }, 6000);
}
</script>
@endsection
