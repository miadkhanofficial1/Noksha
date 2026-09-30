@php
    $currentLocale = app()->getLocale();
    $user = auth()->user();
    $wishCount = $user ? \App\Models\Wishlist::where('user_id', $user->id)->count() : 0;
    $cartCount = $user ? \App\Models\Cart::where('user_id', $user->id)->count() : 0;
    $notifCount = $notifCount ?? ($user ? \App\Models\Notification::where('user_id', $user->id)->where('is_seen', false)->count() : 0);
    $unreadCount = $unreadCount ?? (auth()->check() ? auth()->user()->unreadNotifications->count() : 0);
    $recentNotifs = $recentNotifs ?? (auth()->check() ? auth()->user()->notifications()->latest()->take(10)->get() : collect());
@endphp

<style>
    .wallet-credit-badge {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        transition: all 0.2s ease;
    }
    .wallet-credit-badge:hover {
        background: #FFFFFF;
        border-color: #8B5CF6;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(124, 58, 237, 0.15) !important;
    }
    .dark .wallet-credit-badge,
    [data-bs-theme="dark"] .wallet-credit-badge {
        background: #0F172A;
        border-color: #334155;
    }
    .dark .wallet-credit-badge:hover,
    [data-bs-theme="dark"] .wallet-credit-badge:hover {
        background: #1E293B;
        border-color: #A78BFA;
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
                    <span class="fw-bold fs-4 tracking-tight text-dark dark:text-white text-nowrap">
                        Noksha
                    </span>
                </a>
            </div>

            <!-- ========================================================= -->
            <!-- 2. CENTER SECTION: TEMPLATES, CONTESTS, CONTACT US        -->
            <!-- ========================================================= -->
            <div class="d-none d-lg-flex align-items-center gap-2 mx-auto">
                <a class="nav-link-custom {{ request()->routeIs('resources.*') || request()->is('templates*') || request()->is('resource*') ? 'active' : '' }}" href="{{ route('resources.index') }}">
                    <i class="bi bi-grid me-1.5 text-secondary"></i> Templates
                </a>

                <a class="nav-link-custom {{ request()->routeIs('contests.*') ? 'active' : '' }}" href="{{ route('contests.index') }}">
                    <i class="bi bi-trophy-fill me-1.5 text-warning"></i> Contests
                </a>

                <a class="nav-link-custom {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.index') }}">
                    <i class="bi bi-headset me-1.5 text-primary"></i> Contact Us
                </a>
            </div>

            <!-- ========================================================= -->
            <!-- 3. RIGHT SECTION: LANGUAGE, ACTIONS & USER PROFILE        -->
            <!-- ========================================================= -->
            <div class="d-flex align-items-center gap-2 gap-sm-2.5 flex-shrink-0 flex-nowrap">

                


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
                    @endphp

                    <!-- Clickable Wallet & AI Credit Pill Badge -->
                    <a href="{{ route('wallet.index') }}" class="wallet-credit-badge d-none d-sm-inline-flex align-items-center gap-2 text-decoration-none px-3 py-1.5 rounded-pill shadow-sm" title="View Wallet & Credit Balance">
                        <div class="d-flex align-items-center gap-1 font-monospace fw-extrabold text-success small">
                            <i class="bi bi-wallet2 text-success"></i>
                            <span>৳ {{ number_format(auth()->user()->wallet?->balance ?? 0, 2) }}</span>
                        </div>
                        <span class="text-muted opacity-50" style="font-size: 0.75rem;">|</span>
                        <div class="d-flex align-items-center gap-1 font-monospace fw-bold extra-small" style="color: #F59E0B;">
                            <i class="bi bi-lightning-charge-fill text-warning"></i>
                            <span id="navbar-credit-count" class="navbar-credit-count">
                                @if(auth()->user()->isAdmin())
                                    ⚡ Unlimited Credits
                                @else
                                    ⚡ {{ auth()->user()->ai_credits ?? 0 }} Credits
                                @endif
                            </span>
                        </div>
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
                                        $type = $rn->type ?? 'system';
                                        $isBroadcast = in_array($type, ['broadcast', 'admin']) ||
                                                       str_contains(strtolower($rn->title), 'broadcast') ||
                                                       str_contains(strtolower($rn->title), 'announcement');
                                        $isContest = in_array($type, ['contest', 'success']) ||
                                                       str_contains(strtolower($rn->title), 'contest');

                                        if ($isBroadcast) {
                                            $itemClass = 'notif-item-broadcast';
                                            $iconClass = 'bi-megaphone-fill text-indigo-500';
                                            $iconBg = 'background: rgba(99, 102, 241, 0.12); color: #6366f1;';
                                            $typeBadge = '<span class="badge rounded-pill px-2 py-0.5" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; font-size: 0.62rem;"><i class="bi bi-megaphone-fill me-1"></i>Broadcast</span>';
                                        } elseif ($isContest) {
                                            $itemClass = 'notif-item-contest';
                                            $iconClass = 'bi-trophy-fill text-amber-500';
                                            $iconBg = 'background: rgba(245, 158, 11, 0.12); color: #f59e0b;';
                                            $typeBadge = '<span class="badge rounded-pill px-2 py-0.5" style="background: linear-gradient(135deg, #f59e0b, #ea580c); color: #fff; font-size: 0.62rem;"><i class="bi bi-trophy-fill me-1"></i>Contest Alert</span>';
                                        } else {
                                            $itemClass = 'notif-item-standard ' . (!$rn->is_read ? 'bg-light' : '');
                                            $iconClass = match($type) {
                                                'seller' => 'bi-shop-fill text-primary',
                                                'buyer'  => 'bi-bag-check-fill text-success',
                                                'warning'=> 'bi-exclamation-triangle-fill text-warning',
                                                default  => 'bi-bell-fill text-info',
                                            };
                                            $iconBg = 'background: rgba(108, 76, 241, 0.08);';
                                            $typeBadge = '';
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
                                            {{-- Type Icon --}}
                                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                 style="width: 36px; height: 36px; {{ $iconBg }}">
                                                <i class="bi {{ $iconClass }} fs-6"></i>
                                            </div>

                                            <div class="flex-grow-1 min-w-0">
                                                <div class="d-flex align-items-center justify-content-between gap-1 mb-0.5">
                                                    <span class="fw-bold extra-small text-dark text-truncate">{{ $rn->title }}</span>
                                                    {{-- Glowing green dot for unread --}}
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
                            <li class="px-3 py-2 border-bottom mb-1">
                                <div class="fw-bold small text-dark text-truncate">{{ $user->name }}</div>
                                <div class="text-muted extra-small d-flex align-items-center gap-1.5 mt-0.5 flex-wrap">
                                    <span>&#64;{{ $user->username }}</span>
                                    <span>•</span>
                                    @if($user->contributor_status === 'approved')
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
                                <li><hr class="dropdown-divider my-1"></li>
                            @elseif($user->contributor_status === 'pending')
                                <li class="px-2 py-1">
                                    <div class="p-2 bg-warning bg-opacity-10 rounded-3 border border-warning border-opacity-25 d-flex align-items-center gap-2">
                                        <i class="bi bi-hourglass-split text-warning fs-6 shrink-0"></i>
                                        <div class="lh-1">
                                            <div class="extra-small fw-bold text-dark">Contributor Application Pending Verification</div>
                                            <span class="text-muted" style="font-size: 0.68rem;">Admin review in progress</span>
                                        </div>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
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
                                <li><hr class="dropdown-divider my-1"></li>
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
                                <li><hr class="dropdown-divider my-1"></li>
                            @endif

                            <!-- Super Admin Console Link -->
                            @if(in_array($user->role, ['admin', 'super_admin']))
                                <li>
                                    <a class="dropdown-item small py-2 fw-bold text-primary" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-shield-lock-fill me-2 text-primary"></i> {{ __('dashboard.admin_dashboard') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item small py-2 fw-semibold d-flex align-items-center justify-content-between" href="{{ route('admin.finance.index') }}">
                                        <span><i class="bi bi-cash-stack me-2 text-primary"></i> Finance & Payouts</span>
                                        @php
                                            $pendingPayoutCount = \App\Models\Withdrawal::where('status', 'pending')->count();
                                        @endphp
                                        @if($pendingPayoutCount > 0)
                                            <span class="badge rounded-pill bg-danger text-white extra-small px-1.5 py-0.5">{{ $pendingPayoutCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item small py-2 fw-semibold d-flex align-items-center justify-content-between" href="{{ route('admin.messages.index') }}">
                                        <span><i class="bi bi-envelope-paper-fill me-2 text-primary"></i> Support Messages</span>
                                        @php
                                            $unreadMessagesCount = \App\Models\ContactMessage::where('status', 'unread')->count();
                                        @endphp
                                        @if($unreadMessagesCount > 0)
                                            <span class="badge rounded-pill bg-danger text-white extra-small px-1.5 py-0.5">{{ $unreadMessagesCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                            @endif

                            <!-- Dashboard Link -->
                            <li>
                                <a class="dropdown-item small py-2 fw-semibold" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2 text-primary"></i> {{ $user->isApprovedContributor() ? __('app.dashboard') : 'Dashboard (Buyer View)' }}
                                </a>
                            </li>

                            @if($user->isApprovedContributor())
                                <!-- Creator Studio / Upload Asset (Approved Contributors Only) -->
                                <li>
                                    <a class="dropdown-item small py-2 fw-semibold text-amber-500" href="{{ route('dashboard', ['tab' => 'upload']) }}">
                                        <i class="bi bi-cloud-arrow-up-fill me-2 text-amber-500"></i> Creator Studio (Upload Asset)
                                    </a>
                                </li>

                                <!-- Seller Earnings & Payouts (Approved Contributors Only) -->
                                <li>
                                    <a class="dropdown-item small py-2 fw-semibold" href="{{ route('seller.payouts.index') }}">
                                        <i class="bi bi-cash-stack me-2 text-emerald-500"></i> Seller Earnings & Payouts
                                    </a>
                                </li>
                            @elseif($user->contributor_status === 'pending')
                                <li>
                                    <a class="dropdown-item small py-2 text-warning-emphasis fw-semibold" href="{{ route('dashboard', ['tab' => 'upload']) }}">
                                        <i class="bi bi-hourglass-split me-2 text-warning"></i> Contributor KYC (Pending)
                                    </a>
                                </li>
                            @else
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

                            <!-- Central Wallet & AI Credits -->
                            <li>
                                <a class="dropdown-item small py-2 fw-semibold" href="{{ route('wallet.index') }}">
                                    <i class="bi bi-wallet2 me-2 text-purple-500"></i> Central Wallet & Credits
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
                                <a class="dropdown-item small py-2 fw-semibold" href="{{ route('settings.profile') }}">
                                    <i class="bi bi-gear-fill me-2 text-secondary"></i> Profile Settings
                                </a>
                            </li>

                            <li><hr class="dropdown-divider my-1"></li>

                            <!-- Logout -->
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger small py-2 fw-semibold">
                                        <i class="bi bi-box-arrow-right me-2"></i> {{ __('auth.logout') }}
                                    </button>
                                </form>
                            </li>
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
                <!-- Mobile Wallet & Credit Pill Card -->
                <a href="{{ route('wallet.index') }}" class="wallet-credit-badge d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-3 border shadow-sm" title="View Wallet & Credit Balance">
                    <div class="d-flex align-items-center gap-2 font-monospace fw-bold text-success small">
                        <i class="bi bi-wallet2 fs-6"></i>
                        <span>৳ {{ number_format(auth()->user()->wallet?->balance ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-1.5 font-monospace fw-bold small" style="color: #F59E0B;">
                        <i class="bi bi-lightning-charge-fill text-warning"></i>
                        <span class="navbar-credit-count">
                            @if(auth()->user()->isAdmin())
                                ⚡ Unlimited Credits
                            @else
                                ⚡ {{ auth()->user()->ai_credits ?? 0 }} Credits
                            @endif
                        </span>
                    </div>
                </a>
            @endauth

            <!-- Mobile Core Navigation Links -->
            <div class="d-flex flex-column gap-1 mb-3">
                <a class="nav-link-custom {{ request()->routeIs('resources.*') ? 'active' : '' }}" href="{{ route('resources.index') }}">
                    <i class="bi bi-grid me-2 text-secondary"></i> Templates
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
                    @if(in_array($user->role, ['admin', 'super_admin']))
                        <a class="nav-link-custom text-primary fw-bold" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-shield-lock-fill me-2"></i> {{ __('dashboard.admin_dashboard') }}
                        </a>
                        <a class="nav-link-custom text-primary d-flex align-items-center justify-content-between" href="{{ route('admin.finance.index') }}">
                            <span><i class="bi bi-cash-stack me-2"></i> Finance & Payouts</span>
                            @php
                                $pendingPayoutCount = \App\Models\Withdrawal::where('status', 'pending')->count();
                            @endphp
                            @if($pendingPayoutCount > 0)
                                <span class="badge rounded-pill bg-danger text-white px-2 py-0.5">{{ $pendingPayoutCount }}</span>
                            @endif
                        </a>
                        <a class="nav-link-custom text-primary d-flex align-items-center justify-content-between" href="{{ route('admin.messages.index') }}">
                            <span><i class="bi bi-envelope-paper-fill me-2"></i> Support Messages</span>
                            @php
                                $unreadMessagesCount = \App\Models\ContactMessage::where('status', 'unread')->count();
                            @endphp
                            @if($unreadMessagesCount > 0)
                                <span class="badge rounded-pill bg-danger text-white px-2 py-0.5">{{ $unreadMessagesCount }}</span>
                            @endif
                        </a>
                    @endif

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
                        <a class="nav-link-custom text-primary" href="{{ route('dashboard', ['tab' => 'upload']) }}">
                            <i class="bi bi-cloud-arrow-up-fill me-2 text-amber-500"></i> Creator Studio (Upload)
                        </a>
                        <a class="nav-link-custom text-success" href="{{ route('seller.payouts.index') }}">
                            <i class="bi bi-cash-stack me-2 text-emerald-500"></i> Seller Earnings & Payouts
                        </a>
                    @elseif($user->contributor_status === 'pending')
                        <div class="p-2.5 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-3 mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-hourglass-split text-warning"></i>
                            <span class="extra-small fw-bold text-dark">Contributor Application Pending Verification</span>
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

                    <a class="nav-link-custom text-secondary" href="{{ route('settings.profile') }}">
                        <i class="bi bi-gear-fill me-2 text-secondary"></i> Profile Settings
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-3 py-2 fw-semibold">
                            <i class="bi bi-box-arrow-right me-1.5"></i> {{ __('auth.logout') }}
                        </button>
                    </form>
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
