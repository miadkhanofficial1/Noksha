@php
    $currentLocale = app()->getLocale();
    $user = auth()->user();
    $wishCount = $user ? \App\Models\Wishlist::where('user_id', $user->id)->count() : 0;
    $cartCount = $user ? \App\Models\Cart::where('user_id', $user->id)->count() : 0;
    $notifCount = $notifCount ?? ($user ? \App\Models\Notification::where('user_id', $user->id)->where('is_seen', false)->count() : 0);
    $unreadCount = $unreadCount ?? (auth()->check() ? auth()->user()->unreadNotifications->count() : 0);
    $recentNotifs = $recentNotifs ?? (auth()->check() ? auth()->user()->notifications()->latest()->take(10)->get() : collect());
    $inboxUnreadCount = $user ? $user->unreadMessagesCount() : 0;
@endphp

<style>
    .wallet-credit-badge {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        transition: all 0.2s ease;
    }
    .wallet-credit-badge:hover {
        background: #FFFFFF;
        border-color: #20165c;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(32, 22, 92, 0.12) !important;
    }
</style>

<header class="noksha-header sticky-top py-2 py-lg-2.5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-100">
        <nav class="d-flex align-items-center justify-content-between flex-nowrap w-100" aria-label="Main Navigation">

            <!-- ========================================================= -->
            <!-- 1. LEFT SECTION: BRAND LOGO                               -->
            <!-- ========================================================= -->
            <div class="d-flex align-items-center flex-shrink-0">
                <!-- Brand Logo -->
                <a class="d-flex align-items-center gap-2 text-decoration-none flex-shrink-0" href="{{ route('home') }}" aria-label="Noksha" style="height: 40px; max-height: 40px;">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Noksha" 
                         class="noksha-brand-logo h-10 w-auto object-contain flex-shrink-0"
                         height="40"
                         style="height: 40px; width: auto; max-height: 40px; object-fit: contain; display: block;">
                    <span class="fw-black fs-4 tracking-tight text-[#20165c] text-nowrap">
                        Noksha
                    </span>
                </a>
            </div>

            <!-- ========================================================= -->
            <!-- 2. CENTER SECTION: CENTRAL DISCOVERY SEARCH & NAV LINKS   -->
            <!-- ========================================================= -->
            <div class="d-none d-lg-flex align-items-center gap-3 flex-grow-1 mx-3 mx-xl-4 justify-content-center">
                <!-- Navigation Links -->
                <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                    <a class="nav-link-custom {{ request()->routeIs('resources.*') || request()->is('templates*') || request()->is('resource*') ? 'active' : '' }}" href="{{ route('resources.index') }}">
                        <i class="bi bi-grid me-1 text-slate-500"></i> Templates
                    </a>

                    <a class="nav-link-custom {{ request()->routeIs('creators.*') ? 'active' : '' }}" href="{{ route('creators.index') }}">
                        <i class="bi bi-people-fill me-1 text-[#20165c]"></i> Creators
                    </a>

                    <a class="nav-link-custom {{ request()->routeIs('contests.*') ? 'active' : '' }}" href="{{ route('contests.index') }}">
                        <i class="bi bi-trophy-fill me-1 text-[#f47402]"></i> Contests
                    </a>
                </div>

                <!-- Central Discovery Search Bar -->
                <div class="w-100 max-w-sm" style="max-width: 320px;">
                    <form action="{{ route('resources.index') }}" method="GET" class="m-0 position-relative">
                        <div class="d-flex align-items-center bg-slate-50 hover:bg-white focus-within:bg-white border border-slate-200 focus-within:border-[#20165c] rounded-full px-3 py-1.5 transition-all shadow-sm">
                            <i class="bi bi-search text-slate-400 me-2 small"></i>
                            <input type="text" 
                                   name="q" 
                                   id="globalNavSearchInput" 
                                   value="{{ request('q') }}" 
                                   placeholder="Search templates, mockups..." 
                                   class="form-control form-control-sm border-0 bg-transparent p-0 text-slate-800 shadow-none" 
                                   style="font-size: 0.8125rem;"
                                   aria-label="Search Marketplace">
                            <kbd class="d-none d-xl-inline-block px-1.5 py-0.5 text-[10px] font-mono bg-slate-100 border border-slate-200 rounded text-slate-500 flex-shrink-0 ms-1.5">Ctrl K</kbd>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- 3. RIGHT SECTION: LANGUAGE, ACTIONS & USER PROFILE        -->
            <!-- ========================================================= -->
            <div class="d-flex align-items-center gap-1.5 gap-sm-2 flex-shrink-0 flex-nowrap">

                <!-- Compact Navigation Dropdown (Visible on viewports below 1200px / standard or smaller screens) -->
                <div class="dropdown d-xl-none" id="compactNavDropdown">
                    <button class="nav-action-btn position-relative" 
                            type="button" 
                            id="compactNavBtn" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false" 
                            title="Explore Noksha">
                        <svg class="text-secondary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end sleek-dropdown-menu mt-2 p-2 shadow-xl border" style="width: 260px;">
                        <div class="px-2.5 py-1.5 mb-1 border-bottom d-flex align-items-center justify-content-between">
                            <span class="extra-small fw-bold text-uppercase tracking-wider text-muted">Explore Noksha</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary extra-small px-2 py-0.5">Quick Menu</span>
                        </div>
                        <a class="dropdown-item py-2 px-2.5 rounded-3 d-flex align-items-center gap-2.5 {{ request()->routeIs('resources.*') || request()->is('templates*') || request()->is('resource*') ? 'active bg-primary bg-opacity-10 text-primary fw-bold' : '' }}" href="{{ route('resources.index') }}">
                            <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: rgba(100, 116, 139, 0.12); color: #64748B;">
                                <i class="bi bi-grid-fill small"></i>
                            </div>
                            <div>
                                <div class="small fw-semibold">Templates</div>
                                <div class="extra-small text-muted" style="font-size: 0.68rem;">UI Kits, Vectors, Graphics</div>
                            </div>
                        </a>
                        <a class="dropdown-item py-2 px-2.5 rounded-3 d-flex align-items-center gap-2.5 {{ request()->routeIs('creators.*') ? 'active bg-primary bg-opacity-10 text-primary fw-bold' : '' }}" href="{{ route('creators.index') }}">
                            <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: rgba(99, 102, 241, 0.12); color: #6366F1;">
                                <i class="bi bi-people-fill small"></i>
                            </div>
                            <div>
                                <div class="small fw-semibold">Creators</div>
                                <div class="extra-small text-muted" style="font-size: 0.68rem;">Hire Verified Designers</div>
                            </div>
                        </a>
                        <a class="dropdown-item py-2 px-2.5 rounded-3 d-flex align-items-center gap-2.5 {{ request()->routeIs('contests.*') ? 'active bg-primary bg-opacity-10 text-primary fw-bold' : '' }}" href="{{ route('contests.index') }}">
                            <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: rgba(245, 158, 11, 0.12); color: #F59E0B;">
                                <i class="bi bi-trophy-fill small"></i>
                            </div>
                            <div>
                                <div class="small fw-semibold">Contests</div>
                                <div class="extra-small text-muted" style="font-size: 0.68rem;">Design Challenges & Prizes</div>
                            </div>
                        </a>
                        <a class="dropdown-item py-2 px-2.5 rounded-3 d-flex align-items-center gap-2.5 {{ request()->routeIs('contact.*') ? 'active bg-primary bg-opacity-10 text-primary fw-bold' : '' }}" href="{{ route('contact.index') }}">
                            <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: rgba(59, 130, 246, 0.12); color: #3B82F6;">
                                <i class="bi bi-headset small"></i>
                            </div>
                            <div>
                                <div class="small fw-semibold">Contact Us</div>
                                <div class="extra-small text-muted" style="font-size: 0.68rem;">Get in touch with Noksha</div>
                            </div>
                        </a>
                        <div class="dropdown-divider my-1"></div>
                        <a class="dropdown-item py-2 px-2.5 rounded-3 d-flex align-items-center gap-2.5 {{ request()->is('support*') ? 'active bg-primary bg-opacity-10 text-primary fw-bold' : '' }}" href="{{ route('contact.index') }}">
                            <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: rgba(16, 185, 129, 0.12); color: #10B981;">
                                <i class="bi bi-life-preserver small"></i>
                            </div>
                            <div>
                                <div class="small fw-semibold">Support Desk</div>
                                <div class="extra-small text-muted" style="font-size: 0.68rem;">Help Center & Resolution</div>
                            </div>
                        </a>
                    </div>
                </div>

                


                @guest
                    <!-- Guest Authentication Actions -->
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm px-3.5 py-1.5 rounded-pill fw-semibold">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-noksha btn-sm px-3.5 py-1.5 rounded-pill fw-bold shadow-sm">
                        Register
                    </a>
                @else
                    @php
                        $unreadCount = $unreadCount ?? (auth()->check() ? auth()->user()->unreadNotifications()->count() : 0);
                        $recentNotifs = $recentNotifs ?? (auth()->check() ? auth()->user()->notifications()->latest()->take(10)->get() : collect());
    $inboxUnreadCount = $user ? $user->unreadMessagesCount() : 0;
                    @endphp

                    <!-- Clickable Central BDT Wallet Pill Badge -->
                    <a href="{{ route('wallet.index') }}" class="d-none d-sm-inline-flex align-items-center gap-1.5 text-decoration-none px-3 py-1.5 rounded-full border border-purple-200 bg-purple-50 hover:bg-purple-100/80 transition-all shadow-sm" title="Central BDT Escrow Wallet">
                        <i class="bi bi-wallet2 text-[#20165c] small"></i>
                        <span class="font-monospace fw-semibold small text-[#20165c]">৳{{ number_format(auth()->user()->wallet?->balance ?? 0, 2) }}</span>
                    </a>

                    <!-- Notification Bell Dropdown -->
                    <div class="dropdown" id="notifDropdown">
                        <button class="nav-action-btn position-relative" type="button"
                                id="notifBellBtn"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                title="{{ __('app.notifications') }}">
                            <i class="bi bi-bell-fill text-warning fs-6"></i>
                            <span class="badge-counter bg-danger text-white {{ ($unreadCount ?? 0) > 0 ? '' : 'd-none' }}" id="notifBadgeCount">{{ ($unreadCount ?? 0) > 0 ? $unreadCount : '' }}</span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end sleek-dropdown-menu mt-2 p-0 overflow-hidden" style="width: 350px;">
                            {{-- Header --}}
                            <div class="p-3 bg-light border-bottom d-flex align-items-center justify-content-between">
                                <span class="fw-bold small text-dark"><i class="bi bi-bell-fill me-1.5 text-warning"></i> {{ __('app.notifications') }}</span>
                                <span class="badge bg-primary rounded-pill extra-small {{ ($unreadCount ?? 0) > 0 ? '' : 'd-none' }}" id="notifHeaderBadge">{{ ($unreadCount ?? 0) }} unread</span>
                            </div>

                            {{-- Notification Items List --}}
                            <div class="list-group list-group-flush" style="max-height: 340px; overflow-y: auto;">
                                @forelse(($recentNotifs ?? collect()) as $rn)
                                    @php
                                        $rawType = strtolower($rn->type ?? 'system');
                                        $rawTitle = strtolower($rn->title ?? '');

                                        $isBroadcast = in_array($rawType, ['broadcast', 'admin', 'announcement']) ||
                                                       str_contains($rawTitle, 'broadcast') ||
                                                       str_contains($rawTitle, 'announcement');

                                        $isSale = in_array($rawType, ['sale', 'template_sale', 'seller', 'order', 'buyer', 'purchase']) ||
                                                  str_contains($rawTitle, 'sold') ||
                                                  str_contains($rawTitle, 'sale') ||
                                                  str_contains($rawTitle, 'order') ||
                                                  str_contains($rawTitle, 'purchased');

                                        $isUpload = in_array($rawType, ['upload', 'resource', 'asset']) ||
                                                    str_contains($rawTitle, 'asset') ||
                                                    str_contains($rawTitle, 'submission') ||
                                                    str_contains($rawTitle, 'approved') ||
                                                    str_contains($rawTitle, 'declined') ||
                                                    str_contains($rawTitle, 'template upload');

                                        $isWallet = in_array($rawType, ['wallet', 'finance', 'payout', 'withdrawal']) ||
                                                    str_contains($rawTitle, 'payout') ||
                                                    str_contains($rawTitle, 'withdrawal') ||
                                                    str_contains($rawTitle, 'balance') ||
                                                    str_contains($rawTitle, 'earnings');

                                        $isKyc = in_array($rawType, ['kyc', 'verification', 'contributor']) ||
                                                 str_contains($rawTitle, 'verification') ||
                                                 str_contains($rawTitle, 'identity') ||
                                                 str_contains($rawTitle, 'contributor');

                                        $isContest = in_array($rawType, ['contest', 'success']) ||
                                                     str_contains($rawTitle, 'contest');

                                        if ($isBroadcast) {
                                            $itemClass = 'notif-item-broadcast';
                                            $iconContainerClass = 'bg-indigo-100 text-indigo-600';
                                            $typeBadge = '<span class="badge rounded-pill px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-200" style="font-size: 0.62rem;"><i class="bi bi-megaphone-fill me-1"></i>Broadcast</span>';
                                            $iconSvg = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>';
                                        } elseif ($isSale) {
                                            $itemClass = 'notif-item-standard ' . (!$rn->is_read ? 'bg-light' : '');
                                            $iconContainerClass = 'bg-emerald-100 text-emerald-600';
                                            $typeBadge = '<span class="badge rounded-pill px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200" style="font-size: 0.62rem;"><i class="bi bi-bag-check-fill me-1"></i>Sale / Order</span>';
                                            $iconSvg = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>';
                                        } elseif ($isUpload) {
                                            $itemClass = 'notif-item-standard ' . (!$rn->is_read ? 'bg-light' : '');
                                            $iconContainerClass = 'bg-purple-100 text-[#20165c]';
                                            $typeBadge = '<span class="badge rounded-pill px-2 py-0.5 bg-purple-50 text-[#20165c] border border-purple-200" style="font-size: 0.62rem;"><i class="bi bi-cloud-arrow-up-fill me-1"></i>Asset</span>';
                                            $iconSvg = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>';
                                        } elseif ($isWallet) {
                                            $itemClass = 'notif-item-standard ' . (!$rn->is_read ? 'bg-light' : '');
                                            $iconContainerClass = 'bg-orange-100 text-[#f47402]';
                                            $typeBadge = '<span class="badge rounded-pill px-2 py-0.5 bg-orange-50 text-[#f47402] border border-orange-200" style="font-size: 0.62rem;"><i class="bi bi-wallet2 me-1"></i>Finance</span>';
                                            $iconSvg = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>';
                                        } elseif ($isKyc) {
                                            $itemClass = 'notif-item-standard ' . (!$rn->is_read ? 'bg-light' : '');
                                            $iconContainerClass = 'bg-sky-100 text-sky-600';
                                            $typeBadge = '<span class="badge rounded-pill px-2 py-0.5 bg-sky-50 text-sky-700 border border-sky-200" style="font-size: 0.62rem;"><i class="bi bi-shield-check me-1"></i>KYC</span>';
                                            $iconSvg = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>';
                                        } elseif ($isContest) {
                                            $itemClass = 'notif-item-contest';
                                            $iconContainerClass = 'bg-amber-100 text-amber-600';
                                            $typeBadge = '<span class="badge rounded-pill px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200" style="font-size: 0.62rem;"><i class="bi bi-trophy-fill me-1"></i>Contest</span>';
                                            $iconSvg = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2 0h4m5-18v4m-2-2h4m3 6v1a6 6 0 01-6 6H9a6 6 0 01-6-6V7h14zm0 0a3 3 0 003-3V3.5a.5.5 0 00-.5-.5h-2.5M7 7H4.5a.5.5 0 00-.5.5V5a3 3 0 003 3z"/></svg>';
                                        } else {
                                            $itemClass = 'notif-item-standard ' . (!$rn->is_read ? 'bg-light' : '');
                                            $iconContainerClass = 'bg-slate-100 text-slate-600';
                                            $typeBadge = '';
                                            $iconSvg = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>';
                                        }
                                    @endphp

                                    <div class="notif-dropdown-item border-bottom {{ $itemClass }} p-3"
                                         data-notif-id="{{ $rn->id }}"
                                         data-notif-type="{{ $rn->type }}"
                                         data-notif-title="{{ e($rn->title) }}"
                                         data-notif-message="{{ e($rn->message) }}"
                                         data-notif-date="{{ $rn->created_at->format('d M Y, g:i A') }}"
                                         data-is-broadcast="{{ $isBroadcast ? 'true' : 'false' }}"
                                         data-is-unread="{{ !$rn->is_read ? 'true' : 'false' }}"
                                         data-ajax-read-url="{{ route('notifications.markAsRead', $rn->id) }}"
                                         data-redirect-url="{{ $rn->action_url ? url(parse_url($rn->action_url, PHP_URL_PATH) ?: '/') : '' }}"
                                         style="cursor: pointer; transition: all 0.2s ease;">
                                        <div class="d-flex align-items-start gap-2.5">
                                            {{-- Type Icon (SVG guaranteed to render) --}}
                                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 {{ $iconContainerClass }}"
                                                 style="width: 36px; height: 36px;">
                                                {!! $iconSvg !!}
                                            </div>

                                            <div class="flex-grow-1 min-w-0">
                                                <div class="d-flex align-items-center justify-content-between gap-1 mb-0.5">
                                                    <span class="fw-bold extra-small text-dark text-truncate">{{ $rn->title }}</span>
                                                    {{-- Glowing dot for unread --}}
                                                    @if(!$rn->is_read)
                                                        <span class="notif-unread-dot flex-shrink-0" title="Unread"></span>
                                                    @endif
                                                </div>
                                                <div class="text-muted extra-small line-clamp-2" style="line-height: 1.4;">{{ $rn->message }}</div>
                                                <div class="d-flex align-items-center gap-2 mt-1.5 flex-wrap">
                                                    <span class="extra-small text-secondary font-monospace" style="font-size: 0.68rem;">{{ $rn->created_at->diffForHumans() }}</span>
                                                    {!! $typeBadge !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-muted extra-small">
                                        <i class="bi bi-bell-slash fs-4 d-block mb-2 opacity-50"></i>
                                        {{ __('app.no_data') }}
                                    </div>
                                @endforelse
                            </div>

                            {{-- Footer --}}
                            <div class="p-2 bg-light text-center border-top">
                                <a href="{{ route('notifications.index') }}" class="extra-small fw-bold text-primary text-decoration-none">
                                    {{ __('app.view_all') }} {{ __('app.notifications') }} <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Inbox / Messages Icon Button -->
                    <a href="{{ route('inbox.index') }}" class="nav-action-btn position-relative {{ request()->routeIs('inbox.*') ? 'active' : '' }}" title="Messages & Inquiries">
                        <i class="bi bi-chat-dots-fill text-primary fs-6"></i>
                        @if($inboxUnreadCount > 0)
                            <span class="badge-counter bg-primary text-white">{{ $inboxUnreadCount > 9 ? '9+' : $inboxUnreadCount }}</span>
                        @endif
                    </a>

                    <!-- Wishlist Icon Button -->
                    <a href="{{ route('wishlist.index') }}" class="nav-action-btn" title="{{ __('marketplace.wishlist') }}">
                        <i class="bi bi-heart-fill text-danger fs-6"></i>
                        @if($wishCount > 0)
                            <span class="badge-counter bg-danger text-white">{{ $wishCount > 9 ? '9+' : $wishCount }}</span>
                        @endif
                    </a>

                    <!-- Cart Icon Button -->
                    <a href="{{ route('cart.index') }}" class="nav-action-btn" title="{{ __('marketplace.cart') }}">
                        <i class="bi bi-cart-fill text-primary fs-6"></i>
                        @if($cartCount > 0)
                            <span class="badge-counter bg-primary text-white">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
                        @endif
                    </a>

                    <!-- User Profile Sleek Dropdown (Consolidates Dashboard, Verify Email, Settings, Logout) -->
                    <div class="dropdown">
                        <button class="user-avatar-btn dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ $user->name }}">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
                            @else
                                <span class="user-avatar-initial">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            @endif
                            <span class="fw-semibold small text-dark d-none d-md-inline" style="max-width: 110px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $user->name }}
                            </span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end sleek-dropdown-menu mt-2">
                            <!-- User Header Card -->
                            <li class="px-3 py-2 border-bottom mb-1 border-slate-200 dark:border-slate-700">
                                <div class="fw-bold small text-slate-900 dark:text-slate-100 text-truncate">{{ $user->name }}</div>
                                <div class="text-muted extra-small d-flex align-items-center gap-1.5 mt-0.5 flex-wrap">
                                    <span>&#64;{{ $user->username }}</span>
                                    <span>•</span>
                                    @if(in_array($user->role, ['admin', 'super_admin']) || (method_exists($user, 'isAdmin') && $user->isAdmin()))
                                        <span class="badge bg-danger bg-opacity-10 text-danger extra-small d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-shield-lock-fill text-danger"></i> System Administrator
                                        </span>
                                    @elseif($user->contributor_status === 'approved')
                                        <span class="badge bg-success bg-opacity-10 text-success extra-small d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-shield-fill-check text-success"></i> Verified Contributor
                                        </span>
                                    @elseif($user->contributor_status === 'pending')
                                        <span class="badge bg-warning bg-opacity-15 text-warning-emphasis extra-small d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-clock-history text-warning"></i> Verification Pending
                                        </span>
                                    @elseif($user->contributor_status === 'rejected')
                                        <span class="badge bg-danger bg-opacity-10 text-danger extra-small d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-x-circle-fill"></i> KYC Declined
                                        </span>
                                    @else
                                        <span class="badge bg-primary bg-opacity-10 text-primary extra-small">Buyer</span>
                                    @endif
                                </div>
                            </li>

                            @if(in_array($user->role, ['admin', 'super_admin']) || (method_exists($user, 'isAdmin') && $user->isAdmin()))
                                <!-- ========================================================= -->
                                <!-- ADMIN ROLE EXCLUSIVE NAVIGATION ITEMS                     -->
                                <!-- ========================================================= -->
                                
                                <!-- 1. Admin HQ Command Center -->
                                <li>
                                    <a class="dropdown-item small py-2 fw-bold text-primary d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-shield-lock-fill text-primary fs-6"></i>
                                        <span>Admin HQ Command Center</span>
                                    </a>
                                </li>

                                <!-- 2. Resource Moderation Queue -->
                                @php
                                    $pendingResourceCount = \App\Models\Resource::where('status', 'pending')->count();
                                @endphp
                                <li>
                                    <a class="dropdown-item small py-2 fw-semibold d-flex align-items-center justify-content-between" href="{{ route('admin.resources.index') }}">
                                        <span class="d-inline-flex align-items-center gap-2">
                                            <i class="bi bi-collection-fill text-amber-500 fs-6"></i>
                                            <span>Resource Moderation Queue</span>
                                        </span>
                                        @if($pendingResourceCount > 0)
                                            <span class="badge rounded-pill bg-amber-500 text-slate-950 fw-bold extra-small px-1.5 py-0.5">{{ $pendingResourceCount }}</span>
                                        @endif
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider my-1 border-slate-200 dark:border-slate-700"></li>

                                <!-- 3. Profile Settings -->
                                <li>
                                    <a class="dropdown-item small py-2 fw-semibold d-flex align-items-center gap-2" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-gear-fill text-secondary fs-6"></i>
                                        <span>Profile Settings</span>
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider my-1 border-slate-200 dark:border-slate-700"></li>

                                <!-- 4. Log Out -->
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger small py-2 fw-semibold d-flex align-items-center gap-2">
                                            <i class="bi bi-box-arrow-right fs-6"></i>
                                            <span>{{ __('auth.logout') }}</span>
                                        </button>
                                    </form>
                                </li>
                            @else
                                <!-- ========================================================= -->
                                <!-- REGULAR ROLES (BUYER / CONTRIBUTOR) NAVIGATION ITEMS      -->
                                <!-- ========================================================= -->

                                <!-- Contributor Mode Switcher / KYC Status Actions -->
                                @if($user->contributor_status === 'approved')
                                    <li class="px-2 py-1">
                                        <form action="{{ route('user.switch-mode') }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="mode" value="{{ $user->active_mode === 'seller' ? 'buyer' : 'seller' }}">
                                            <button type="submit" class="dropdown-item py-2 rounded-3 d-flex align-items-center justify-content-between text-decoration-none border-0 {{ $user->active_mode === 'seller' ? 'bg-success bg-opacity-10 text-success fw-bold' : 'bg-primary bg-opacity-10 text-primary fw-bold' }}" title="Click to switch dashboard mode">
                                                <span class="d-inline-flex align-items-center gap-2 small">
                                                    <i class="bi bi-arrow-left-right"></i>
                                                    @if($user->active_mode === 'seller')
                                                        <span>Switch to Buyer Mode</span>
                                                    @else
                                                        <span>Switch to Contributor Mode</span>
                                                    @endif
                                                </span>
                                                <span class="badge {{ $user->active_mode === 'seller' ? 'bg-success text-white' : 'bg-primary text-white' }} extra-small px-2 py-0.5">
                                                    {{ $user->active_mode === 'seller' ? 'Seller Active' : 'Buyer Active' }}
                                                </span>
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider my-1 border-slate-200 dark:border-slate-700"></li>
                                @elseif($user->contributor_status === 'pending')
                                    <li class="px-2 py-1">
                                        <div class="p-2 bg-warning bg-opacity-10 rounded-3 border border-warning border-opacity-25 d-flex align-items-center gap-2">
                                            <i class="bi bi-hourglass-split text-warning fs-6 shrink-0"></i>
                                            <div class="lh-1">
                                                <div class="extra-small fw-bold text-dark dark:text-slate-100">Contributor Application Pending Verification</div>
                                                <span class="text-muted" style="font-size: 0.68rem;">Admin review in progress</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider my-1 border-slate-200 dark:border-slate-700"></li>
                                @else
                                    <li class="px-2 py-1">
                                        <a class="dropdown-item py-2 bg-primary bg-opacity-10 text-primary fw-semibold rounded-3 d-flex align-items-center gap-2" href="{{ route('contributor.apply') }}">
                                            <i class="bi bi-award-fill text-primary fs-6 shrink-0"></i>
                                            <div class="lh-1">
                                                <div class="extra-small fw-bold">Become a Contributor</div>
                                                <span class="text-muted" style="font-size: 0.68rem;">Apply with KYC to sell designs</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1 border-slate-200 dark:border-slate-700"></li>
                                @endif

                                <!-- Unverified Email Alert Banner (Collapsed inside dropdown) -->
                                @if(! $user->hasVerifiedEmail())
                                    <li class="px-2 py-1">
                                        <a class="dropdown-item py-2 bg-warning bg-opacity-10 text-dark fw-semibold rounded-3 d-flex align-items-center gap-2" href="{{ route('verification.notice') }}" title="Click to verify your email address">
                                            <i class="bi bi-exclamation-circle-fill text-warning fs-6"></i>
                                            <div class="lh-1">
                                                <div class="extra-small fw-bold text-warning-emphasis">{{ __('auth.verify_email') }}</div>
                                                <span class="text-muted" style="font-size: 0.7rem;">Action required</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1 border-slate-200 dark:border-slate-700"></li>
                                @endif

                                <!-- Dashboard Link -->
                                <li>
                                    <a class="dropdown-item small py-2 fw-semibold" href="{{ route('dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2 text-primary"></i> {{ $user->isApprovedContributor() ? __('app.dashboard') : 'Dashboard (Buyer View)' }}
                                    </a>
                                </li>

                                <!-- Messages & Inquiries Link -->
                                <li>
                                    <a class="dropdown-item small py-2 fw-semibold d-flex align-items-center justify-content-between {{ request()->routeIs('inbox.*') ? 'active bg-primary bg-opacity-10 text-primary' : '' }}" href="{{ route('inbox.index') }}">
                                        <span class="d-inline-flex align-items-center gap-2">
                                            <svg class="w-4 h-4 text-primary shrink-0" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                            <span>Messages & Inquiries</span>
                                        </span>
                                        @if($inboxUnreadCount > 0)
                                            <span class="badge rounded-pill bg-primary text-white extra-small px-1.5 py-0.5">{{ $inboxUnreadCount > 9 ? '9+' : $inboxUnreadCount }}</span>
                                        @endif
                                    </a>
                                </li>

                                @if($user->isApprovedContributor() && $user->isSellerMode())
                                    <!-- Creator Studio / Upload Asset (Contributor Mode Only) -->
                                    <li>
                                        <a class="dropdown-item small py-2 fw-semibold text-amber-500" href="{{ route('dashboard', ['tab' => 'upload']) }}">
                                            <i class="bi bi-cloud-arrow-up-fill me-2 text-amber-500"></i> Creator Studio (Upload Asset)
                                        </a>
                                    </li>

                                    <!-- Seller Earnings & Payouts (Contributor Mode Only) -->
                                    <li>
                                        <a class="dropdown-item small py-2 fw-semibold" href="{{ route('seller.payouts') }}">
                                            <i class="bi bi-cash-stack me-2 text-emerald-500"></i> Seller Earnings & Payouts
                                        </a>
                                    </li>
                                @elseif(!$user->isApprovedContributor() && $user->contributor_status === 'pending')
                                    <li>
                                        <a class="dropdown-item small py-2 text-warning-emphasis fw-semibold" href="{{ route('dashboard', ['tab' => 'upload']) }}">
                                            <i class="bi bi-hourglass-split me-2 text-warning"></i> Contributor KYC (Pending)
                                        </a>
                                    </li>
                                @elseif(!$user->isApprovedContributor())
                                    <li>
                                        <a class="dropdown-item small py-2 fw-semibold text-primary" href="{{ route('contributor.apply') }}">
                                            <i class="bi bi-award-fill me-2"></i> Become a Contributor
                                        </a>
                                    </li>
                                @endif

                                <!-- Order Records / Invoices -->
                                <li>
                                    <a class="dropdown-item small py-2 fw-semibold" href="{{ route('orders.index') }}">
                                        <i class="bi bi-receipt me-2 text-success"></i> Order Records & Downloads
                                    </a>
                                </li>

                                <!-- Central Wallet & Balance -->
                                <li>
                                    <a class="dropdown-item small py-2 fw-semibold" href="{{ route('wallet.index') }}">
                                        <i class="bi bi-wallet2 me-2 text-purple-500"></i> Central Wallet & Balance
                                    </a>
                                </li>

                                <!-- Saved Wishlist -->
                                <li>
                                    <a class="dropdown-item small py-2 fw-semibold" href="{{ route('wishlist.index') }}">
                                        <i class="bi bi-heart me-2 text-danger"></i> Saved Wishlist
                                    </a>
                                </li>

                                <!-- Profile Settings -->
                                <li>
                                    <a class="dropdown-item small py-2 fw-semibold" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-gear-fill me-2 text-secondary"></i> Profile Settings
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider my-1 border-slate-200 dark:border-slate-700"></li>

                                <!-- Logout -->
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger small py-2 fw-semibold">
                                            <i class="bi bi-box-arrow-right me-2"></i> {{ __('auth.logout') }}
                                        </button>
                                    </form>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endguest

                <!-- Mobile Hamburger Navigation Toggler -->
                <button class="navbar-toggler d-lg-none border-0 p-1 ms-1" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#nokshaNavbarCollapse" 
                        aria-controls="nokshaNavbarCollapse" 
                        aria-expanded="false" 
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon" style="width: 1.35em; height: 1.35em;"></span>
                </button>

            </div>
        </nav>

        <!-- ========================================================= -->
        <!-- 4. MOBILE / TABLET COLLAPSIBLE MENU DRAWER                -->
        <!-- ========================================================= -->
        <div class="collapse d-lg-none border-top mt-2 pt-3 pb-2" id="nokshaNavbarCollapse">
            <!-- Mobile Search Bar -->
            <form action="{{ route('search.index') }}" method="GET" class="d-md-none mb-3" role="search">
                <div class="input-group bg-light rounded-pill px-3 py-1 border">
                    <span class="input-group-text bg-transparent border-0 text-muted ps-0 pe-2">
                        <i class="bi bi-search"></i>
                    </span>
                    <input name="q" 
                           value="{{ request('q') }}" 
                           class="form-control bg-transparent border-0 ps-0 small" 
                           type="search" 
                           placeholder="{{ __('app.search') }}..." 
                           aria-label="Search">
                </div>
            </form>

            @auth
                <!-- Mobile Wallet Card -->
                <a href="{{ route('wallet.index') }}" class="wallet-credit-badge d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-3 border shadow-sm" title="View Wallet Balance">
                    <div class="d-flex align-items-center gap-2 font-monospace fw-bold text-success small">
                        <i class="bi bi-wallet2 fs-6"></i>
                        <span>৳ {{ number_format(auth()->user()->wallet?->balance ?? 0, 2) }}</span>
                    </div>
                    <span class="extra-small text-muted font-monospace">Central Wallet</span>
                </a>
            @endauth

            <!-- Mobile Core Navigation Links -->
            <div class="d-flex flex-column gap-1 mb-3">
                <a class="nav-link-custom {{ request()->routeIs('resources.*') ? 'active' : '' }}" href="{{ route('resources.index') }}">
                    <i class="bi bi-grid me-2 text-secondary"></i> Templates
                </a>
                <a class="nav-link-custom {{ request()->routeIs('creators.*') ? 'active' : '' }}" href="{{ route('creators.index') }}">
                    <i class="bi bi-people-fill me-2 text-indigo-400"></i> Creators
                </a>
                <a class="nav-link-custom {{ request()->routeIs('contests.*') ? 'active' : '' }}" href="{{ route('contests.index') }}">
                    <i class="bi bi-trophy-fill me-2 text-warning"></i> Contests
                </a>
                <a class="nav-link-custom {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.index') }}">
                    <i class="bi bi-envelope-fill me-2 text-info"></i> Contact Us
                </a>
                @auth
                    <a class="nav-link-custom {{ request()->routeIs('dashboard') || request()->routeIs('buyer.dashboard') || request()->routeIs('seller.dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-2 text-primary"></i> Dashboard
                    </a>
                    <a class="nav-link-custom d-flex align-items-center justify-content-between {{ request()->routeIs('inbox.*') ? 'active' : '' }}" href="{{ route('inbox.index') }}">
                        <span>
                            <i class="bi bi-chat-dots-fill me-2 text-primary"></i> Messages & Inquiries
                        </span>
                        @if($inboxUnreadCount > 0)
                            <span class="badge rounded-pill bg-primary text-white extra-small px-2 py-0.5">{{ $inboxUnreadCount }}</span>
                        @endif
                    </a>
                @endauth
            </div>

            


            @auth
                <!-- Mobile Unverified Email Notice -->
                @if(! $user->hasVerifiedEmail())
                    <a href="{{ route('verification.notice') }}" class="alert alert-warning d-flex align-items-center gap-2 py-2 px-3 mb-3 text-decoration-none rounded-3 small">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <div>
                            <span class="fw-bold">{{ __('auth.verify_email') }}</span>
                            <span class="d-block extra-small text-muted">Tap to send verification email</span>
                        </div>
                    </a>
                @endif

                <!-- Mobile User Quick Actions -->
                <div class="border-top pt-2 d-flex flex-column gap-1">
                    @if(in_array($user->role, ['admin', 'super_admin']) || (method_exists($user, 'isAdmin') && $user->isAdmin()))
                        <a class="nav-link-custom text-primary fw-bold" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-shield-lock-fill me-2"></i> Admin HQ Command Center
                        </a>
                        @php
                            $pendingResourceCountMobile = \App\Models\Resource::where('status', 'pending')->count();
                        @endphp
                        <a class="nav-link-custom text-primary d-flex align-items-center justify-content-between" href="{{ route('admin.resources.index') }}">
                            <span><i class="bi bi-collection-fill me-2 text-amber-500"></i> Resource Moderation Queue</span>
                            @if($pendingResourceCountMobile > 0)
                                <span class="badge rounded-pill bg-amber-500 text-slate-950 fw-bold px-2 py-0.5">{{ $pendingResourceCountMobile }}</span>
                            @endif
                        </a>
                        <a class="nav-link-custom text-secondary" href="{{ route('profile.edit') }}">
                            <i class="bi bi-gear-fill me-2 text-secondary"></i> Profile Settings
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-3 py-2 fw-semibold">
                                <i class="bi bi-box-arrow-right me-1.5"></i> {{ __('auth.logout') }}
                            </button>
                        </form>
                    @else
                        @if($user->isApprovedContributor())
                            <form action="{{ route('user.switch-mode') }}" method="POST" class="mb-2">
                                @csrf
                                <input type="hidden" name="mode" value="{{ $user->active_mode === 'seller' ? 'buyer' : 'seller' }}">
                                <button type="submit" class="btn btn-sm w-100 rounded-3 py-2 fw-bold d-flex align-items-center justify-content-between {{ $user->active_mode === 'seller' ? 'btn-outline-success' : 'btn-outline-primary' }}">
                                    <span><i class="bi bi-arrow-left-right me-1.5"></i> {{ $user->active_mode === 'seller' ? 'Switch to Buyer Mode' : 'Switch to Contributor Mode' }}</span>
                                    <span class="badge {{ $user->active_mode === 'seller' ? 'bg-success text-white' : 'bg-primary text-white' }} extra-small">
                                        {{ $user->active_mode === 'seller' ? 'Seller' : 'Buyer' }}
                                    </span>
                                </button>
                            </form>
                            @if($user->isSellerMode())
                                <a class="nav-link-custom text-primary" href="{{ route('dashboard', ['tab' => 'upload']) }}">
                                    <i class="bi bi-cloud-arrow-up-fill me-2 text-amber-500"></i> Creator Studio (Upload)
                                </a>
                                <a class="nav-link-custom text-success" href="{{ route('seller.payouts') }}">
                                    <i class="bi bi-cash-stack me-2 text-emerald-500"></i> Seller Earnings & Payouts
                                </a>
                            @endif
                        @elseif($user->contributor_status === 'pending')
                            <div class="p-2.5 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-3 mb-2 d-flex align-items-center gap-2">
                                <i class="bi bi-hourglass-split text-warning"></i>
                                <span class="extra-small fw-bold text-dark dark:text-slate-100">Contributor Application Pending Verification</span>
                            </div>
                        @else
                            <a class="nav-link-custom text-primary fw-bold" href="{{ route('contributor.apply') }}">
                                <i class="bi bi-award-fill me-2 text-warning"></i> Become a Contributor
                            </a>
                        @endif

                        <a class="nav-link-custom text-secondary" href="{{ route('orders.index') }}">
                            <i class="bi bi-receipt me-2 text-success"></i> {{ __('marketplace.orders') }}
                        </a>

                        <a class="nav-link-custom text-primary" href="{{ route('user.profile', $user->username ?? $user->id) }}">
                            <i class="bi bi-person-badge-fill me-2"></i> My Public Profile
                        </a>

                        <a class="nav-link-custom text-secondary" href="{{ route('profile.edit') }}">
                            <i class="bi bi-gear-fill me-2 text-secondary"></i> Profile Settings
                        </a>

                        <form action="{{ route('logout') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-3 py-2 fw-semibold">
                                <i class="bi bi-box-arrow-right me-1.5"></i> {{ __('auth.logout') }}
                            </button>
                        </form>
                    @endif
                </div>
            @else
                <!-- Mobile Guest Auth Buttons -->
                <div class="d-grid gap-2 border-top pt-3">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary rounded-pill py-2 fw-semibold">
                        {{ __('auth.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-noksha rounded-pill py-2 fw-bold">
                        {{ __('auth.register') }}
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>

<script>
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            const searchInput = document.getElementById('globalNavSearchInput');
            if (searchInput) {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            }
        }
    });
</script>
