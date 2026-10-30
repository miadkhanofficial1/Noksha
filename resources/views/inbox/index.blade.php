@extends('layouts.app')

@section('title', ($activeConversation && $counterpart ? 'Chat with ' . $counterpart->name : 'Inbox') . ' - Noksha')

@section('content')
<div class="bg-slate-950 min-h-[calc(100vh-4.5rem)] text-slate-200 flex flex-col font-sans antialiased">
    
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
    <div class="container-fluid max-w-7xl mx-auto px-2 sm:px-4 lg:px-6 py-4 flex-1 flex flex-col">
        <div class="bg-slate-900/90 border border-slate-800/80 rounded-3xl shadow-2xl overflow-hidden flex-1 flex flex-col lg:flex-row h-[calc(100vh-7rem)] min-h-[620px] backdrop-blur-xl">

            {{-- ======================================================== --}}
            {{-- 1. LEFT COLUMN: CONVERSATIONS SIDEBAR                    --}}
            {{-- ======================================================== --}}
            <aside id="chatSidebar" class="w-full lg:w-80 xl:w-96 border-b lg:border-b-0 lg:border-r border-slate-800/80 flex flex-col bg-slate-950/40 shrink-0">
                
                {{-- Sidebar Header --}}
                <div class="p-4 border-b border-slate-800/80 flex items-center justify-between">
                    <div>
                        <h1 class="text-base font-bold text-white tracking-tight flex items-center gap-2 mb-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 animate-pulse"></span>
                            <span>Direct Escrow Inbox</span>
                        </h1>
                        <p class="text-[11px] text-slate-400 mb-0">Noksha Secured Messaging</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                        {{ $conversations->count() }} {{ Str::plural('Chat', $conversations->count()) }}
                    </span>
                </div>

                {{-- Search Filter Input --}}
                <div class="p-3 border-b border-slate-800/60 bg-slate-900/40">
                    <div class="relative">
                        <svg class="w-4 h-4 text-slate-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="conversationSearchInput" placeholder="Search chats by name or keyword..." value="{{ $search ?? '' }}" class="w-full pl-9 pr-4 py-2 rounded-xl text-xs bg-slate-950 border border-slate-800 text-slate-200 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    </div>
                </div>

                {{-- Thread List (Scrollable) --}}
                <div id="conversationThreadList" class="flex-1 overflow-y-auto divide-y divide-slate-800/40 scrollbar-thin scrollbar-thumb-slate-800">
                    @forelse($conversations as $conv)
                        @php
                            $convOther = $conv->getOtherParticipant(auth()->user());
                            $isActive = $activeConversation && $activeConversation->id === $conv->id;
                            $latestMsg = $conv->latestMessage;
                            $isSupport = $conv->conversation_type === 'system_support';
                            $unread = $conv->unread_count ?? 0;
                        @endphp
                        <a href="{{ route('inbox.show', $conv->id) }}" class="conversation-thread-item block p-3.5 hover:bg-slate-800/40 transition-colors {{ $isActive ? 'bg-slate-800/70 border-l-4 border-indigo-500' : '' }}" data-search-name="{{ strtolower($convOther->name ?? 'support') }}" data-search-text="{{ strtolower($latestMsg->body ?? '') }}">
                            <div class="flex items-center gap-3">
                                
                                {{-- Avatar & Online dot --}}
                                <div class="relative shrink-0">
                                    @if($isSupport)
                                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-md shadow-indigo-600/20">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                    @elseif($convOther && $convOther->avatar)
                                        <img src="{{ asset('storage/' . $convOther->avatar) }}" alt="{{ $convOther->name }}" class="w-11 h-11 rounded-2xl object-cover border border-slate-700/60 shadow-sm">
                                    @else
                                        <div class="w-11 h-11 rounded-2xl bg-slate-800 border border-slate-700/60 flex items-center justify-center text-indigo-400 font-bold text-sm shadow-sm">
                                            {{ strtoupper(substr($convOther->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                    
                                    @if(!$isSupport)
                                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full {{ ($convOther->is_available ?? true) ? 'bg-emerald-500' : 'bg-slate-500' }} border-2 border-slate-900" title="{{ ($convOther->is_available ?? true) ? 'Available' : 'Offline' }}"></span>
                                    @endif
                                </div>

                                {{-- Details --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <h4 class="text-xs font-bold text-white truncate mb-0">
                                            {{ $isSupport ? 'Official System Notice' : ($convOther->name ?? 'User') }}
                                        </h4>
                                        <span class="text-[10px] text-slate-500 shrink-0">
                                            {{ $conv->last_message_at ? $conv->last_message_at->diffForHumans(null, true) : '' }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-1.5 mb-1">
                                        @if($isSupport)
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20">System Support</span>
                                        @elseif($convOther && $convOther->isSellerOrCreator())
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Creator / Seller</span>
                                        @else
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">Buyer</span>
                                        @endif

                                        @if($conv->is_frozen)
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">Frozen</span>
                                        @elseif($conv->activeOrder && $conv->activeOrder->isFunded())
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Escrow Funded</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-[11px] text-slate-400 truncate mb-0 max-w-[190px]">
                                            @if($latestMsg)
                                                {{ $latestMsg->sender_id === auth()->id() ? 'You: ' : '' }}{{ $latestMsg->body }}
                                            @else
                                                <span class="italic text-slate-500">No messages yet</span>
                                            @endif
                                        </p>
                                        @if($unread > 0)
                                            <span class="shrink-0 w-4 h-4 rounded-full bg-rose-500 text-white font-bold text-[9px] flex items-center justify-center shadow-sm shadow-rose-500/30">
                                                {{ $unread }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </a>
                    @empty
                        <div class="p-8 text-center text-slate-500">
                            <svg class="w-10 h-10 mx-auto mb-2 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <p class="text-xs mb-0">No conversations yet.</p>
                        </div>
                    @endforelse
                </div>
            </aside>

            {{-- ======================================================== --}}
            {{-- 2. RIGHT COLUMN: ACTIVE CHAT VIEWPORT                    --}}
            {{-- ======================================================== --}}
            <main class="flex-1 flex flex-col bg-slate-900/60 overflow-hidden relative">
                
                @if($activeConversation)
                    @php
                        $isSupportThread = $activeConversation->conversation_type === 'system_support';
                        $isFrozen = (bool) $activeConversation->is_frozen;
                        $activeOrder = $activeConversation->activeOrder;
                        $isFunded = $activeOrder && $activeOrder->isFunded();
                        $isFundedInProgress = $activeOrder && $activeOrder->status === 'funded_in_progress';
                        $isSeller = auth()->id() === $activeConversation->seller_id;
                        $isBuyer = auth()->id() === $activeConversation->buyer_id;
                    @endphp

                    {{-- 2.1 CHAT HEADER --}}
                    <header class="p-4 border-b border-slate-800/80 bg-slate-950/60 flex items-center justify-between flex-wrap gap-3 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                @if($isSupportThread)
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                @elseif($counterpart && $counterpart->avatar)
                                    <img src="{{ asset('storage/' . $counterpart->avatar) }}" alt="{{ $counterpart->name }}" class="w-10 h-10 rounded-2xl object-cover border border-slate-700">
                                @else
                                    <div class="w-10 h-10 rounded-2xl bg-slate-800 flex items-center justify-center text-indigo-400 font-bold text-sm">
                                        {{ strtoupper(substr($counterpart->name ?? 'C', 0, 1)) }}
                                    </div>
                                @endif
                                @if(!$isSupportThread)
                                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full {{ ($counterpart->is_available ?? true) ? 'bg-emerald-500' : 'bg-slate-500' }} border-2 border-slate-950"></span>
                                @endif
                            </div>

                            <div>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-sm font-bold text-white mb-0">
                                        {{ $isSupportThread ? 'Official Platform Notice' : ($counterpart->name ?? 'Counterpart') }}
                                    </h2>
                                    @if($counterpart && $counterpart->username)
                                        @php
                                            $creatorUrl = '#';
                                            if (\Illuminate\Support\Facades\Route::has('creator.show')) {
                                                $creatorUrl = route('creator.show', $counterpart->username);
                                            } elseif (\Illuminate\Support\Facades\Route::has('creators.show')) {
                                                $creatorUrl = route('creators.show', $counterpart->username);
                                            } else {
                                                $creatorUrl = url('/creator/' . $counterpart->username);
                                            }
                                        @endphp
                                        <a href="{{ $creatorUrl }}" target="_blank" class="text-[11px] text-gray-400 hover:text-white">
                                            &#64;{{ $counterpart->username }}
                                        </a>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 text-[10px] text-slate-400">
                                    <span>{{ ($counterpart->is_available ?? true) ? '🟢 Available for Hire' : '⚪ Currently Away' }}</span>
                                    <span>&bull;</span>
                                    <span>Escrow Protected</span>
                                </div>
                            </div>
                        </div>

                        {{-- Header Actions: Proposal Trigger & Freeze / Leave Chat --}}
                        <div class="flex items-center gap-2">
                            {{-- Service Proposal Button --}}
                            @if(!$isSupportThread && !$isFrozen)
                                <button type="button" onclick="openProposalModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white shadow-sm shadow-indigo-600/30 transition-all cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    <span>{{ $isSeller ? 'Propose Custom Service' : 'Pick Seller Service' }}</span>
                                </button>
                            @endif

                            {{-- Leave Chat / Reopen Action --}}
                            @if($isFrozen)
                                @if($activeConversation->reopen_status === 'requested')
                                    @if((int) $activeConversation->reopen_requested_by !== (int) auth()->id())
                                        <form action="{{ route('inbox.reopen-respond', $activeConversation->id) }}" method="POST" class="inline flex items-center gap-1">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="px-2.5 py-1.5 rounded-xl text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/20 transition-all">
                                                ✅ Approve Re-open
                                            </button>
                                        </form>
                                        <form action="{{ route('inbox.reopen-respond', $activeConversation->id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="action" value="decline">
                                            <button type="submit" class="px-2.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-800 text-slate-400 hover:text-white transition-all">
                                                Decline
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                            ⏳ Re-open Requested
                                        </span>
                                    @endif
                                @else
                                    <form action="{{ route('inbox.reopen-request', $activeConversation->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/30 hover:bg-indigo-500/20 transition-all cursor-pointer">
                                            🔓 Request to Re-open
                                        </button>
                                    </form>
                                @endif
                            @else
                                @if(!$isSupportThread)
                                    <form action="{{ route('inbox.freeze', $activeConversation->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to leave and freeze this conversation?');">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-medium text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 border border-slate-800 hover:border-rose-500/30 transition-all cursor-pointer" title="Leave Chat">
                                            Leave Chat
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </header>

                    {{-- 2.2 TOP ESCROW STATUS BANNER (CONDITIONAL) --}}
                    @if($activeOrder)
                        <div class="px-4 py-3 border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-md shrink-0">
                            <div class="flex items-center justify-between flex-wrap gap-3">
                                
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 flex items-center justify-center font-bold text-xs shrink-0">
                                        ৳
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-white">{{ $activeOrder->service->title ?? 'Custom Service' }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono">({{ $activeOrder->order_number }})</span>
                                        </div>
                                        <div class="text-[11px] text-slate-400">
                                            Total: <strong class="text-white">৳{{ number_format($activeOrder->total_amount, 2) }}</strong>
                                            &bull; Platform Fee: ৳{{ number_format($activeOrder->platform_fee, 2) }}
                                            &bull; Payout: <strong class="text-emerald-400">৳{{ number_format($activeOrder->seller_payout_amount, 2) }}</strong>
                                        </div>
                                    </div>
                                </div>

                                {{-- Order Status Badge & Escrow Actions --}}
                                <div class="flex items-center gap-2.5 flex-wrap justify-end">
                                    @if($activeOrder->status === 'funded_in_progress')
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center gap-1.5">
                                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                                <span>Funded & In Progress</span>
                                            </span>
                                            @if($activeOrder->deadline_at)
                                                <div id="countdownTimer" data-deadline="{{ $activeOrder->deadline_at->toIso8601String() }}" class="px-2.5 py-1 rounded-lg text-xs font-mono bg-slate-900 border border-slate-800 text-indigo-300">
                                                    Calculating countdown...
                                                </div>
                                            @endif
                                            @if($isSeller)
                                                <button type="button" onclick="openDeliveryModal()" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition-all cursor-pointer">
                                                    📦 Deliver Final Work
                                                </button>
                                            @endif
                                            @if($isBuyer)
                                                <form action="{{ route('inbox.orders.cancel', $activeOrder->id) }}" method="POST" id="overdueCancelAction" class="hidden inline" onsubmit="return confirm('Cancel this overdue order and process full refund to your balance?');">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-500 text-white transition-all cursor-pointer shadow-md shadow-rose-600/20">
                                                        ⚠️ Request Cancellation & Refund
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @elseif($activeOrder->status === 'delivered')
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/30">
                                                📦 Work Delivered
                                            </span>
                                            <a href="{{ route('inbox.orders.download-delivery', $activeOrder->id) }}" class="px-2.5 py-1 rounded-xl text-xs font-medium bg-slate-800 hover:bg-slate-700 text-indigo-300 border border-slate-700 transition-colors">
                                                📥 Download Delivery (.ZIP)
                                            </a>
                                            @if($isBuyer)
                                                <form action="{{ route('inbox.orders.release', $activeOrder->id) }}" method="POST" class="inline" onsubmit="return confirm('Release escrow funds to creator? 15% platform commission will be deducted.');">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/25 transition-all cursor-pointer">
                                                        ✅ Accept & Release Funds
                                                    </button>
                                                </form>
                                                <button type="button" onclick="openRevisionModal()" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/30 transition-all cursor-pointer">
                                                    🔄 Request Revision
                                                </button>
                                            @else
                                                <span class="text-[11px] text-slate-400 italic">
                                                    Client reviewing (Auto-releases after 3 days)
                                                </span>
                                            @endif
                                        </div>
                                    @elseif($activeOrder->status === 'awaiting_payment')
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                            Awaiting Escrow Payment
                                        </span>
                                        @if($isBuyer)
                                            <form action="{{ route('inbox.orders.fund', $activeOrder->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition-all cursor-pointer">
                                                    💳 Proceed to Secure Escrow Payment (৳{{ number_format($activeOrder->total_amount, 2) }})
                                                </button>
                                            </form>
                                        @endif
                                    @elseif($activeOrder->status === 'completed')
                                        <div class="flex items-center gap-2">
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                                ✅ Completed & Escrow Released
                                            </span>
                                            @if($activeOrder->delivery_archive_path)
                                                <a href="{{ route('inbox.orders.download-delivery', $activeOrder->id) }}" class="px-2.5 py-1 rounded-xl text-xs font-medium bg-slate-800 hover:bg-slate-700 text-indigo-300 border border-slate-700 transition-colors">
                                                    📥 Download Final Files
                                                </a>
                                            @endif
                                        </div>
                                    @elseif($activeOrder->status === 'pending_approval')
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/30">
                                            Pending Creator Approval
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-800 text-slate-300 capitalize">
                                            {{ str_replace('_', ' ', $activeOrder->status) }}
                                        </span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endif

                    {{-- 2.3 MESSAGE HISTORY STREAM (SCROLLABLE) --}}
                    <div id="messageHistoryContainer" data-poll-url="{{ route('inbox.poll', $activeConversation->id) }}" data-latest-id="{{ $messages->last()?->id ?? 0 }}" data-order-status="{{ $activeOrder->status ?? '' }}" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 scrollbar-thin scrollbar-thumb-slate-800">
                        @forelse($messages as $msg)
                            @php
                                $isMe = (int) $msg->sender_id === (int) auth()->id();
                                $isSystem = in_array($msg->message_type, ['system_notice', 'order_status']);
                                $isProposal = $msg->message_type === 'service_proposal';
                            @endphp

                            @if($isSystem)
                                {{-- Centered System / Order Status Notice --}}
                                <div class="flex justify-center my-3" data-msg-id="{{ $msg->id }}">
                                    <div class="px-4 py-2 rounded-2xl text-xs font-semibold {{ $msg->message_type === 'order_status' ? 'bg-indigo-950/60 border border-indigo-500/30 text-indigo-200' : 'bg-slate-950/80 border border-slate-800 text-slate-400' }} flex items-center gap-2 shadow-sm max-w-lg text-center">
                                        <svg class="w-4 h-4 {{ $msg->message_type === 'order_status' ? 'text-indigo-400' : 'text-slate-500' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>{{ $msg->body }}</span>
                                        <span class="text-[9px] text-slate-500 font-mono shrink-0">{{ $msg->created_at ? $msg->created_at->format('H:i') : '' }}</span>
                                    </div>
                                </div>
                            @elseif($isProposal)
                                {{-- In-Chat Service Widget Card --}}
                                @php $meta = $msg->metadata ?? []; @endphp
                                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} my-3">
                                    <div class="max-w-md w-full bg-slate-950 border border-indigo-500/30 rounded-3xl p-5 shadow-2xl relative overflow-hidden">
                                        <div class="absolute -top-12 -right-12 w-24 h-24 bg-indigo-500/10 rounded-full blur-xl pointer-events-none"></div>
                                        
                                        <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-800/80 mb-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 flex items-center justify-center font-bold text-xs">
                                                    ৳
                                                </div>
                                                <div>
                                                    <h4 class="text-xs font-bold text-white mb-0">Custom Service Proposal</h4>
                                                    <span class="text-[10px] text-slate-400">{{ $meta['order_number'] ?? 'Custom Order' }}</span>
                                                </div>
                                            </div>
                                            <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-indigo-500/10 text-indigo-400 border border-indigo-500/30">
                                                ৳{{ number_format($meta['price'] ?? 600, 2) }}
                                            </span>
                                        </div>

                                        <h5 class="text-sm font-bold text-white mb-1.5">{{ $meta['title'] ?? 'Creative Service Package' }}</h5>
                                        <p class="text-xs text-slate-400 leading-relaxed mb-3">
                                            {{ $meta['description'] ?? 'Custom design project negotiated via Noksha escrow.' }}
                                        </p>

                                        <div class="flex items-center gap-3 text-[11px] text-slate-400 mb-4 bg-slate-900/60 p-2.5 rounded-xl border border-slate-800/60">
                                            <span>⏱️ <strong>{{ $meta['duration_days'] ?? 3 }} Days</strong> Delivery</span>
                                            <span>&bull;</span>
                                            <span>🛡️ 100% Escrow Protected</span>
                                        </div>

                                        {{-- Proposal Action Buttons --}}
                                        @if(isset($meta['order_id']))
                                            @php
                                                $propOrder = \App\Models\ServiceOrder::find($meta['order_id']);
                                            @endphp
                                            @if($propOrder && $propOrder->status === 'pending_approval')
                                                @if($isSeller)
                                                    <div class="flex items-center gap-2">
                                                        <form action="{{ route('inbox.orders.respond', $propOrder->id) }}" method="POST" class="flex-1">
                                                            @csrf
                                                            <input type="hidden" name="action" value="approve">
                                                            <button type="submit" class="w-full py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white transition-all shadow-md shadow-emerald-600/20 cursor-pointer">
                                                                ✅ Approve Request
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('inbox.orders.respond', $propOrder->id) }}" method="POST" class="flex-1">
                                                            @csrf
                                                            <input type="hidden" name="action" value="decline">
                                                            <button type="submit" class="w-full py-2 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-700 text-slate-300 transition-all cursor-pointer">
                                                                ❌ Decline
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="p-2.5 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-300 text-xs text-center font-medium">
                                                        ⏳ Waiting for creator approval...
                                                    </div>
                                                @endif
                                            @elseif($propOrder && $propOrder->status === 'awaiting_payment')
                                                @if($isBuyer)
                                                    <form action="{{ route('inbox.orders.fund', $propOrder->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="w-full py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white text-center shadow-lg shadow-indigo-600/30 transition-all cursor-pointer">
                                                            💳 Proceed to Secure Escrow Payment (৳{{ number_format($propOrder->total_amount, 2) }})
                                                        </button>
                                                    </form>
                                                @else
                                                    <div class="p-2.5 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs text-center font-medium">
                                                        Approved &bull; Waiting for client payment
                                                    </div>
                                                @endif
                                            @elseif($propOrder && $propOrder->status === 'funded_in_progress')
                                                <div class="p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs text-center font-bold flex items-center justify-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                                    <span>Escrow Secured & Project Underway</span>
                                                </div>
                                            @elseif($propOrder && $propOrder->status === 'delivered')
                                                <div class="space-y-2.5">
                                                    <div class="p-2.5 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-300 text-xs">
                                                        <div class="font-bold flex items-center gap-1.5 mb-1">
                                                            <span>📦 Work Delivered by Creator</span>
                                                        </div>
                                                        @if($propOrder->delivery_notes)
                                                            <p class="text-[11px] text-slate-300 italic mb-2">"{{ $propOrder->delivery_notes }}"</p>
                                                        @endif
                                                        <a href="{{ route('inbox.orders.download-delivery', $propOrder->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 hover:bg-slate-800 text-indigo-300 border border-slate-700 transition-colors">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                            <span>Download Source Delivery (.ZIP)</span>
                                                        </a>
                                                    </div>
                                                    @if($isBuyer)
                                                        <div class="flex items-center gap-2">
                                                            <form action="{{ route('inbox.orders.release', $propOrder->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Accept delivery and release escrow funds to creator?');">
                                                                @csrf
                                                                <button type="submit" class="w-full py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition-all cursor-pointer">
                                                                    ✅ Accept & Release Funds
                                                                </button>
                                                            </form>
                                                            <button type="button" onclick="openRevisionModal()" class="flex-1 py-2 rounded-xl text-xs font-semibold bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/30 transition-all cursor-pointer">
                                                                🔄 Request Revision
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            @elseif($propOrder && $propOrder->status === 'completed')
                                                <div class="p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs text-center font-bold">
                                                    🎉 Order Completed & Escrow Released
                                                    @if($propOrder->delivery_archive_path)
                                                        <div class="mt-2">
                                                            <a href="{{ route('inbox.orders.download-delivery', $propOrder->id) }}" class="inline-flex items-center gap-1 text-xs text-indigo-400 underline hover:text-indigo-300">
                                                                📥 Download Delivery Archive
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            @elseif($propOrder && $propOrder->status === 'cancelled')
                                                <div class="p-2.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs text-center font-bold">
                                                    ❌ Order Cancelled & Escrow Refunded
                                                </div>
                                            @elseif($propOrder && $propOrder->isFunded())
                                                <div class="p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs text-center font-bold">
                                                    ✅ Escrow Secured & Project Underway
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @else
                                {{-- Regular Message Bubble --}}
                                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} items-end gap-2.5">
                                    @if(!$isMe)
                                        <div class="w-7 h-7 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-[10px] text-slate-300 font-bold shrink-0">
                                            {{ strtoupper(substr($msg->sender->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif

                                    <div class="max-w-[75%] sm:max-w-md {{ $isMe ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-2xl rounded-tr-sm' : 'bg-slate-800/90 text-slate-200 border border-slate-700/60 rounded-2xl rounded-tl-sm' }} p-3.5 shadow-lg">
                                        @if(!$isMe)
                                            <div class="text-[10px] font-bold text-slate-400 mb-1">
                                                {{ $msg->sender->name ?? 'User' }}
                                            </div>
                                        @endif

                                        <p class="text-xs leading-relaxed whitespace-pre-wrap break-words mb-1">{{ $msg->body }}</p>

                                        {{-- File Attachments --}}
                                        @if($msg->attachments->isNotEmpty())
                                            <div class="mt-2 pt-2 border-t {{ $isMe ? 'border-indigo-500/40' : 'border-slate-700/60' }} space-y-1.5">
                                                @foreach($msg->attachments as $att)
                                                    <a href="{{ $att->url }}" target="_blank" class="flex items-center gap-2 p-2 rounded-xl {{ $isMe ? 'bg-indigo-800/50 hover:bg-indigo-800/70' : 'bg-slate-900/60 hover:bg-slate-900' }} transition-colors text-xs text-white">
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
                                <div class="w-16 h-16 rounded-3xl bg-slate-800/40 border border-slate-800 flex items-center justify-center mb-3 text-slate-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-300 mb-1">Secure Escrow Conversation Started</h3>
                                <p class="text-xs text-slate-500 max-w-sm mb-0">
                                    Discuss project requirements, clarify scope, or pick a custom service package below.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    {{-- 2.4 CHAT INPUT AREA --}}
                    <footer class="p-3 sm:p-4 border-t border-slate-800/80 bg-slate-950/70 shrink-0">
                        @if($isSupportThread)
                            <div class="p-3 rounded-2xl bg-slate-900 border border-slate-800 text-center text-xs text-slate-400 font-medium">
                                🔒 This is an official system notice. Replies are disabled.
                            </div>
                        @elseif($isFrozen)
                            <div class="p-3 rounded-2xl bg-slate-900 border border-amber-500/30 text-center text-xs text-amber-300 font-medium flex items-center justify-center gap-3">
                                <span>❄️ Conversation is frozen.</span>
                                @if($activeConversation->reopen_status !== 'requested')
                                    <form action="{{ route('inbox.reopen-request', $activeConversation->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="underline font-bold text-amber-400 hover:text-amber-200 cursor-pointer">
                                            Request to Re-open
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400">(Re-open request pending approval)</span>
                                @endif
                            </div>
                        @else
                            {{-- Active Message Form --}}
                            <form id="chatMessageForm" action="{{ route('inbox.messages.store', $activeConversation->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-2">
                                @csrf
                                <input type="hidden" name="is_funded" id="isFundedFlag" value="{{ $isFunded ? '1' : '0' }}">
                                <input type="hidden" name="is_funded_in_progress" id="isFundedInProgressFlag" value="{{ $isFundedInProgress ? '1' : '0' }}">

                                {{-- Attachment Preview Pill (if selected) --}}
                                <div id="attachmentPill" class="hidden items-center justify-between px-3 py-1.5 rounded-xl bg-slate-900 border border-indigo-500/30 text-xs text-indigo-300">
                                    <div class="flex items-center gap-2 truncate">
                                        <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        <span id="attachmentFileName" class="truncate font-mono">file.zip</span>
                                    </div>
                                    <button type="button" onclick="clearAttachment()" class="text-slate-400 hover:text-white">&times;</button>
                                </div>

                                <div class="flex items-end gap-2 bg-slate-900/80 border border-slate-800 rounded-2xl p-2 focus-within:border-indigo-500 transition-colors">
                                    
                                    {{-- Paperclip Attachment Button --}}
                                    <div class="relative shrink-0">
                                        @if($isFundedInProgress)
                                            <input type="file" name="attachment" id="attachmentInput" class="hidden" onchange="handleAttachmentSelected(this)">
                                            <button type="button" onclick="document.getElementById('attachmentInput').click()" class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-800 transition-colors cursor-pointer" title="Attach file">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                            </button>
                                        @else
                                            <button type="button" onclick="showLockedAttachmentNotice()" class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-600 hover:text-slate-400 hover:bg-slate-800/50 transition-colors cursor-not-allowed relative" title="File attachments locked until order is funded in progress">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                                <span class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-slate-900 border border-slate-700 rounded-full flex items-center justify-center text-[9px] text-amber-400 font-bold">🔒</span>
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Textarea with Auto-Expand --}}
                                    <textarea id="chatInputTextarea" name="body" rows="1" placeholder="Type a message (Enter to send, Shift+Enter for new line)..." class="flex-1 bg-transparent border-0 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-0 resize-none py-2 px-1 max-h-36 overflow-y-auto" oninput="autoResizeTextarea(this)" onkeydown="handleChatKeydown(event)"></textarea>

                                    {{-- Send Button --}}
                                    <button type="submit" id="chatSendButton" class="w-9 h-9 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white flex items-center justify-center shadow-md shadow-indigo-600/30 transition-all shrink-0 cursor-pointer">
                                        <svg class="w-4 h-4 -rotate-45 relative left-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    </button>
                                </div>
                            </form>
                        @endif
                    </footer>

                @else
                    {{-- Blank / No Conversation Selected State --}}
                    <div class="flex-1 flex flex-col items-center justify-center p-8 text-center text-slate-500">
                        <div class="w-20 h-20 rounded-3xl bg-slate-950 border border-slate-800 flex items-center justify-center mb-4 text-slate-600 shadow-xl">
                            <svg class="w-10 h-10 text-indigo-400/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <h2 class="text-base font-bold text-white mb-1">Your Noksha Escrow Inbox</h2>
                        <p class="text-xs text-slate-400 max-w-sm mb-4 leading-relaxed">
                            Select a thread from the left sidebar to resume messaging, or visit a creator's public profile to send a direct project inquiry.
                        </p>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('creators.index') ? route('creators.index') : url('/creators') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white transition-all shadow-lg shadow-indigo-600/25">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span>Browse Verified Creators</span>
                        </a>
                    </div>
                @endif

            </main>

        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- 3. PROPOSE / PICK SERVICE MODAL (DARK THEME)             --}}
{{-- ======================================================== --}}
@if($activeConversation && !$activeConversation->is_frozen)
<div id="proposalModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md hidden transition-opacity duration-200" onclick="if(event.target === this) closeProposalModal()">
    <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between bg-slate-950/50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold text-xs">
                    💼
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white mb-0">{{ $isSeller ? 'Propose Custom Service' : 'Request Creative Service' }}</h3>
                    <p class="text-[11px] text-slate-400 mb-0">Minimum service threshold ৳600.00 BDT</p>
                </div>
            </div>
            <button type="button" onclick="closeProposalModal()" class="w-8 h-8 rounded-lg bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center text-sm">&times;</button>
        </div>

        <form action="{{ route('inbox.service-proposal.store', $activeConversation->id) }}" method="POST" class="p-6 space-y-4">
            @csrf

            {{-- Optional: Pick an existing seller service --}}
            @if($services->isNotEmpty())
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Pick Existing Service (Optional)</label>
                    <select name="service_id" id="serviceSelect" onchange="handleServiceSelect(this)" class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 border border-slate-800 text-slate-200 focus:outline-none focus:border-indigo-500">
                        <option value="">-- Propose Custom / Enter Details Below --</option>
                        @foreach($services as $srv)
                            <option value="{{ $srv->id }}" data-title="{{ $srv->title }}" data-price="{{ $srv->price }}" data-days="{{ $srv->duration_days }}" data-desc="{{ $srv->description }}">
                                {{ $srv->title }} (৳{{ number_format($srv->price, 2) }} - {{ $srv->duration_days }} Days)
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Title --}}
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Service Title <span class="text-rose-400">*</span></label>
                <input type="text" name="title" id="proposalTitleInput" required placeholder="e.g. Mobile App UI Kit & Figma Design System" class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
            </div>

            {{-- Price & Delivery Days Grid --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Price (BDT ৳) <span class="text-rose-400">*</span></label>
                    <input type="number" name="price" id="proposalPriceInput" min="600" step="10" value="600" required class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white font-mono focus:outline-none focus:border-indigo-500">
                    <span class="text-[10px] text-slate-500">Min: ৳600.00 BDT</span>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Delivery Time (Days) <span class="text-rose-400">*</span></label>
                    <input type="number" name="duration_days" id="proposalDaysInput" min="1" max="90" value="3" required class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white font-mono focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            {{-- Description / Scope --}}
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Scope of Work & Deliverables <span class="text-rose-400">*</span></label>
                <textarea name="description" id="proposalDescInput" rows="3" required placeholder="Outline deliverables, file formats (.FIG, .PSD, .AI), and revision milestones..." class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 resize-none"></textarea>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-800">
                <button type="button" onclick="closeProposalModal()" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white bg-slate-800 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition-all cursor-pointer">
                    🚀 Dispatch Proposal
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ======================================================== --}}
{{-- 3B. DELIVER WORK MODAL (SELLER ONLY)                    --}}
{{-- ======================================================== --}}
@if(isset($activeOrder) && $activeOrder && !empty($isSeller) && $activeOrder->status === 'funded_in_progress')
<div id="deliveryModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md hidden transition-opacity duration-200" onclick="if(event.target === this) closeDeliveryModal()">
    <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between bg-slate-950/50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xs">
                    📦
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white mb-0">Deliver Final Work</h3>
                    <p class="text-[11px] text-slate-400 mb-0">Upload source ZIP package (AI, PSD, Figma, EPS, etc.)</p>
                </div>
            </div>
            <button type="button" onclick="closeDeliveryModal()" class="w-8 h-8 rounded-lg bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center text-sm">&times;</button>
        </div>

        <form action="{{ route('inbox.orders.deliver', $activeOrder->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Deliverable Package (.ZIP only, Max 200MB) <span class="text-rose-400">*</span></label>
                <input type="file" name="delivery_file" accept=".zip,application/zip,application/x-zip-compressed" required class="w-full text-xs text-slate-300 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 bg-slate-950 border border-slate-800 rounded-xl p-2 cursor-pointer">
                <p class="text-[10px] text-slate-500 mt-1">Package all project source files, fonts, and documentation into a single .ZIP archive.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Delivery Notes / Handover Instructions</label>
                <textarea name="delivery_notes" rows="4" placeholder="Add instructions on how to use the files, software versions, or handover notes..." class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 resize-none"></textarea>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-800">
                <button type="button" onclick="closeDeliveryModal()" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white bg-slate-800 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-600/30 transition-all cursor-pointer">
                    🚀 Submit Delivery to Client
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ======================================================== --}}
{{-- 3C. REQUEST REVISION MODAL (BUYER ONLY)                 --}}
{{-- ======================================================== --}}
@if(isset($activeOrder) && $activeOrder && !empty($isBuyer) && $activeOrder->status === 'delivered')
<div id="revisionModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md hidden transition-opacity duration-200" onclick="if(event.target === this) closeRevisionModal()">
    <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between bg-slate-950/50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-xs">
                    🔄
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white mb-0">Request Revision</h3>
                    <p class="text-[11px] text-slate-400 mb-0">Specify changes or adjustments required for this project</p>
                </div>
            </div>
            <button type="button" onclick="closeRevisionModal()" class="w-8 h-8 rounded-lg bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center text-sm">&times;</button>
        </div>

        <form action="{{ route('inbox.orders.revision', $activeOrder->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Revision Instructions <span class="text-rose-400">*</span></label>
                <textarea name="revision_notes" rows="4" required placeholder="Clearly describe what changes are needed, referencing specific sections or assets..." class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 resize-none"></textarea>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-800">
                <button type="button" onclick="closeRevisionModal()" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white bg-slate-800 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-amber-600 hover:bg-amber-500 text-white shadow-lg shadow-amber-600/30 transition-all cursor-pointer">
                    🔄 Submit Revision Request
                </button>
            </div>
        </form>
    </div>
</div>
@endif

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
                countdownEl.innerHTML = '<span class="text-rose-400 font-bold">⚠️ Delivery Overdue - Late Penalty Protection Active</span>';
                const overdueBtn = document.getElementById('overdueCancelAction');
                if (overdueBtn) {
                    overdueBtn.classList.remove('hidden');
                }
                return;
            }
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const mins = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const secs = Math.floor((distance % (1000 * 60)) / 1000);
            countdownEl.innerHTML = `⏱️ ${days}d ${hours}h ${mins}m ${secs}s`;
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    // 4. Form Submit Anti-Bypass Guard
    const messageForm = document.getElementById('chatMessageForm');
    if (messageForm) {
        messageForm.addEventListener('submit', function (e) {
            const bodyInput = document.getElementById('chatInputTextarea');
            const text = (bodyInput ? bodyInput.value : '').trim();
            const isFunded = document.getElementById('isFundedFlag')?.value === '1';

            // If order is not funded, check for sensitive contact info
            if (!isFunded && text !== '') {
                const phoneRegex = /(?:\+?880?|01)\d{8,9}|\b(?:\+?88|01)?\d{9,11}\b|\b\d{10,14}\b/;
                const emailRegex = /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/;
                const linkRegex = /(https?:\/\/|www\.|wa\.me|wa\.link|facebook\.com|fb\.com|t\.me|telegram|whatsapp|instagram\.com|discord\.gg|linkedin\.com)/i;

                // Strip punctuation and spacing to detect spaced phone numbers (e.g. 0 1 7 ...)
                const stripped = text.replace(/[\s\-\.\(\)]/g, '');
                const hasPhone = phoneRegex.test(text) || /(?:\+?88|01)\d{8,11}/.test(stripped);
                const hasEmail = emailRegex.test(text);
                const hasLink = linkRegex.test(text);

                if (hasPhone || hasEmail || hasLink) {
                    e.preventDefault();
                    showNokshaToast('⚠️ Security Policy Alert: Sharing personal phone numbers, email addresses, or external links outside Noksha is strictly prohibited.', 'error');
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
});

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
}

function appendPolledMessage(msg, container) {
    const div = document.createElement('div');
    div.dataset.msgId = msg.id;

    if (msg.message_type === 'system_notice' || msg.message_type === 'order_status') {
        const isOrder = msg.message_type === 'order_status';
        div.className = 'flex justify-center my-3';
        div.innerHTML = `
            <div class="px-4 py-2 rounded-2xl text-xs font-semibold ${isOrder ? 'bg-indigo-950/60 border border-indigo-500/30 text-indigo-200' : 'bg-slate-950/80 border border-slate-800 text-slate-400'} flex items-center gap-2 shadow-sm max-w-lg text-center">
                <span>${escapeHtml(msg.body)}</span>
                <span class="text-[9px] text-slate-500 font-mono shrink-0">${msg.created_at_human || ''}</span>
            </div>
        `;
    } else {
        div.className = `flex ${msg.is_me ? 'justify-end' : 'justify-start'} items-end gap-2.5`;
        let attachmentsHtml = '';
        if (msg.attachments && msg.attachments.length > 0) {
            attachmentsHtml = `<div class="mt-2 pt-2 border-t ${msg.is_me ? 'border-indigo-500/40' : 'border-slate-700/60'} space-y-1.5">` +
                msg.attachments.map(att => `
                    <a href="${att.url}" target="_blank" class="flex items-center gap-2 p-2 rounded-xl ${msg.is_me ? 'bg-indigo-800/50 hover:bg-indigo-800/70' : 'bg-slate-900/60 hover:bg-slate-900'} transition-colors text-xs text-white">
                        <svg class="w-4 h-4 text-indigo-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        <span class="truncate flex-1 font-mono text-[11px]">${escapeHtml(att.file_name)}</span>
                        <span class="text-[10px] opacity-75 shrink-0">${escapeHtml(att.size || '')}</span>
                    </a>
                `).join('') + `</div>`;
        }

        const avatarHtml = msg.is_me ? '' : `
            <div class="w-7 h-7 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-[10px] text-slate-300 font-bold shrink-0">
                ${(msg.sender_name || 'U').substring(0, 1).toUpperCase()}
            </div>
        `;

        div.innerHTML = `
            ${avatarHtml}
            <div class="max-w-[75%] sm:max-w-md ${msg.is_me ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-2xl rounded-tr-sm' : 'bg-slate-800/90 text-slate-200 border border-slate-700/60 rounded-2xl rounded-tl-sm'} p-3.5 shadow-lg">
                ${!msg.is_me ? `<div class="text-[10px] font-bold text-slate-400 mb-1">${escapeHtml(msg.sender_name || 'User')}</div>` : ''}
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

// Auto-expand textarea as user types
function autoResizeTextarea(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = (textarea.scrollHeight) + 'px';
}

// Enter to submit, Shift+Enter for newline
function handleChatKeydown(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        const form = document.getElementById('chatMessageForm');
        if (form) form.requestSubmit();
    }
}

// Notice for locked attachments
function showLockedAttachmentNotice() {
    showNokshaToast('🔒 Attachment Lock: File sharing is locked until a service order has been funded in progress.', 'error');
}

// File attachment selection preview
function handleAttachmentSelected(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('attachmentFileName').textContent = file.name;
        document.getElementById('attachmentPill').classList.remove('hidden');
        document.getElementById('attachmentPill').classList.add('flex');
    }
}

function clearAttachment() {
    const input = document.getElementById('attachmentInput');
    if (input) input.value = '';
    const pill = document.getElementById('attachmentPill');
    if (pill) {
        pill.classList.add('hidden');
        pill.classList.remove('flex');
    }
}

// Modal open/close
function openProposalModal() {
    const modal = document.getElementById('proposalModal');
    if (modal) modal.classList.remove('hidden');
}

function closeProposalModal() {
    const modal = document.getElementById('proposalModal');
    if (modal) modal.classList.add('hidden');
}

function openDeliveryModal() {
    const modal = document.getElementById('deliveryModal');
    if (modal) modal.classList.remove('hidden');
}

function closeDeliveryModal() {
    const modal = document.getElementById('deliveryModal');
    if (modal) modal.classList.add('hidden');
}

function openRevisionModal() {
    const modal = document.getElementById('revisionModal');
    if (modal) modal.classList.remove('hidden');
}

function closeRevisionModal() {
    const modal = document.getElementById('revisionModal');
    if (modal) modal.classList.add('hidden');
}

// Pre-fill modal fields when an existing service is picked
function handleServiceSelect(select) {
    const opt = select.options[select.selectedIndex];
    if (opt && opt.value) {
        document.getElementById('proposalTitleInput').value = opt.dataset.title || '';
        document.getElementById('proposalPriceInput').value = opt.dataset.price || '600';
        document.getElementById('proposalDaysInput').value = opt.dataset.days || '3';
        document.getElementById('proposalDescInput').value = opt.dataset.desc || '';
    }
}

// Dynamic client-side toast renderer
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
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-y-2', 'opacity-0');
    }, 10);

    // Auto dismiss after 6 seconds
    setTimeout(() => {
        toast.classList.add('opacity-0', '-translate-y-2');
        setTimeout(() => toast.remove(), 300);
    }, 6000);
}
</script>
@endsection
