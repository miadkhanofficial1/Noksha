@php
    $currentLocale = app()->getLocale();
    $user = auth()->user();
    $wishCount = $user ? \App\Models\Wishlist::where('user_id', $user->id)->count() : 0;
    $cartCount = $user ? \App\Models\Cart::where('user_id', $user->id)->count() : 0;
    $notifCount = $user ? \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count() : 0;
    $recentNotifs = $user ? \App\Models\Notification::where('user_id', $user->id)->latest()->take(5)->get() : collect();
@endphp

<header class="noksha-header sticky-top py-2 py-lg-2.5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-100">
        <nav class="d-flex align-items-center justify-content-between flex-nowrap w-100" aria-label="Main Navigation">

            <!-- ========================================================= -->
            <!-- 1. LEFT SECTION: BRAND LOGO + CORE DESKTOP NAV LINKS      -->
            <!-- ========================================================= -->
            <div class="d-flex align-items-center gap-3 gap-xl-4 flex-shrink-0">
                <!-- Brand Logo -->
                <a class="d-flex align-items-center gap-2 text-decoration-none" href="{{ route('home') }}">
                    <span class="noksha-logo-badge">ন</span>
                    <span class="fw-bold fs-4 tracking-tight text-dark d-flex align-items-baseline gap-1">
                        Noksha <span class="text-primary fs-6 fw-bold">(নকশা)</span>
                    </span>
                </a>

                <!-- Core Desktop Navigation Links -->
                <div class="d-none d-lg-flex align-items-center gap-1 ms-2">
                    <a class="nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door me-1.5 text-secondary"></i> {{ __('app.home') }}
                    </a>

                    <a class="nav-link-custom text-secondary" href="{{ route('home') }}#templates">
                        <i class="bi bi-grid me-1.5 text-secondary"></i> {{ __('app.templates') }}
                    </a>

                    <a class="nav-link-custom {{ request()->routeIs('contests.*') ? 'active' : '' }}" href="{{ route('contests.index') }}">
                        <i class="bi bi-trophy-fill me-1.5 text-warning"></i> {{ __('app.contests') }}
                    </a>

                    @auth
                        <a class="nav-link-custom {{ request()->routeIs('dashboard') || request()->routeIs('buyer.dashboard') || request()->routeIs('seller.dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-1.5 text-primary"></i> {{ __('app.dashboard') ?? 'Dashboard' }}
                        </a>
                    @endauth
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- 2. CENTER SECTION: COMPACT SEARCH BAR                     -->
            <!-- ========================================================= -->
            <div class="nav-search-container d-none d-md-flex align-items-center mx-2 mx-xl-3 flex-grow-1 max-w-xs">
                <form action="{{ route('search.index') }}" method="GET" class="w-100 m-0" role="search">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-0 text-muted ps-0 pe-2">
                            <i class="bi bi-search"></i>
                        </span>
                        <input name="q" 
                               value="{{ request('q') }}" 
                               class="form-control ps-0" 
                               type="search" 
                               placeholder="{{ __('app.search') }}..." 
                               aria-label="Search templates">
                    </div>
                </form>
            </div>

            <!-- ========================================================= -->
            <!-- 3. RIGHT SECTION: LANGUAGE, ACTIONS & USER PROFILE        -->
            <!-- ========================================================= -->
            <div class="d-flex align-items-center gap-2 gap-sm-2.5 flex-shrink-0 flex-nowrap">

                <!-- Segmented Language Switcher (Desktop) -->
                <div class="lang-switcher-pill d-none d-sm-inline-flex">
                    <a href="{{ route('lang.switch', 'bn') }}" 
                       class="lang-switcher-btn {{ $currentLocale === 'bn' ? 'active' : '' }}" 
                       title="বাংলা ভাষা নির্বাচন করুন">
                        🇧🇩 বাংলা
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}" 
                       class="lang-switcher-btn {{ $currentLocale === 'en' ? 'active' : '' }}" 
                       title="Switch to English">
                        🇬🇧 EN
                    </a>
                </div>

                <!-- Dark / Light Theme Toggle Button -->
                <button type="button" 
                        class="nav-action-btn theme-toggle-btn" 
                        aria-label="Toggle dark/light mode" 
                        title="Toggle dark/light theme">
                    <!-- Sun SVG Icon (shown in dark mode) -->
                    <svg class="theme-icon-sun text-warning" style="display: none; width: 1.15rem; height: 1.15rem;" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                    </svg>
                    <!-- Moon SVG Icon (shown in light mode) -->
                    <svg class="theme-icon-moon text-secondary" style="width: 1.15rem; height: 1.15rem;" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                </button>

                @guest
                    <!-- Guest Authentication Actions -->
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm px-3 py-1.5 rounded-pill fw-semibold">
                        {{ __('auth.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-noksha btn-sm px-3.5 py-1.5 rounded-pill fw-bold shadow-sm">
                        {{ __('auth.register') }}
                    </a>
                @else
                    <!-- Notification Bell Dropdown -->
                    <div class="dropdown">
                        <button class="nav-action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('app.notifications') }}">
                            <i class="bi bi-bell-fill text-warning fs-6"></i>
                            @if($notifCount > 0)
                                <span class="badge-counter bg-danger text-white">{{ $notifCount > 9 ? '9+' : $notifCount }}</span>
                            @endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-end sleek-dropdown-menu mt-2 p-0 overflow-hidden" style="width: 320px;">
                            <div class="p-3 bg-light border-bottom d-flex align-items-center justify-content-between">
                                <span class="fw-bold small text-dark"><i class="bi bi-bell me-1.5"></i> {{ __('app.notifications') }}</span>
                                @if($notifCount > 0)
                                    <span class="badge bg-primary rounded-pill extra-small">{{ $notifCount }} {{ __('app.notifications') }}</span>
                                @endif
                            </div>
                            <div class="list-group list-group-flush" style="max-height: 280px; overflow-y: auto;">
                                @forelse($recentNotifs as $rn)
                                    <form action="{{ route('notifications.read', $rn->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="list-group-item list-group-item-action p-3 text-start border-bottom small {{ !$rn->is_read ? 'bg-light font-weight-bold' : '' }}">
                                            <div class="fw-bold text-dark extra-small">{{ $rn->title }}</div>
                                            <div class="text-muted extra-small line-clamp-1">{{ $rn->message }}</div>
                                            <div class="extra-small text-primary font-monospace mt-1">{{ $rn->created_at->diffForHumans() }}</div>
                                        </button>
                                    </form>
                                @empty
                                    <div class="p-3 text-center text-muted extra-small">{{ __('app.no_data') }}</div>
                                @endforelse
                            </div>
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
                            <span class="user-avatar-initial">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                            <span class="fw-semibold small text-dark d-none d-md-inline" style="max-width: 110px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $user->name }}
                            </span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end sleek-dropdown-menu mt-2">
                            <!-- User Header Card -->
                            <li class="px-3 py-2 border-bottom mb-1">
                                <div class="fw-bold small text-dark text-truncate">{{ $user->name }}</div>
                                <div class="text-muted extra-small d-flex align-items-center gap-1.5 mt-0.5">
                                    <span>&#64;{{ $user->username }}</span>
                                    <span>•</span>
                                    @if($user->isContributor())
                                        <span class="badge bg-success bg-opacity-10 text-success extra-small">Contributor ✓</span>
                                    @else
                                        <span class="badge bg-primary bg-opacity-10 text-primary extra-small">User / বায়ার</span>
                                    @endif
                                </div>
                            </li>

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
                                <li><hr class="dropdown-divider my-1"></li>
                            @endif

                            <!-- Dashboard Link -->
                            <li>
                                <a class="dropdown-item small py-2 fw-semibold" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2 text-primary"></i> {{ __('app.dashboard') ?? 'Dashboard' }}
                                </a>
                            </li>

                            <!-- Contributor / Creator Action -->
                            @if($user->isContributor())
                                <li>
                                    <a class="dropdown-item small py-2 fw-semibold" href="{{ route('resource.create') }}">
                                        <i class="bi bi-cloud-arrow-up-fill me-2 text-primary"></i> {{ __('dashboard.upload') }}
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item small py-2 fw-semibold text-primary" href="{{ route('contributor.apply') }}">
                                        <i class="bi bi-award-fill me-2"></i> Become a Contributor
                                    </a>
                                </li>
                            @endif

                            <!-- Orders / Purchased Downloads -->
                            <li>
                                <a class="dropdown-item small py-2 fw-semibold" href="{{ route('orders.index') }}">
                                    <i class="bi bi-receipt me-2 text-success"></i> {{ __('marketplace.orders') }}
                                </a>
                            </li>

                            <!-- Wishlist -->
                            <li>
                                <a class="dropdown-item small py-2 fw-semibold" href="{{ route('wishlist.index') }}">
                                    <i class="bi bi-heart me-2 text-danger"></i> {{ __('marketplace.wishlist') }}
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

            <!-- Mobile Core Navigation Links -->
            <div class="d-flex flex-column gap-1 mb-3">
                <a class="nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <i class="bi bi-house-door me-2 text-secondary"></i> {{ __('app.home') }}
                </a>
                <a class="nav-link-custom text-secondary" href="{{ route('home') }}#templates">
                    <i class="bi bi-grid me-2 text-secondary"></i> {{ __('app.templates') }}
                </a>
                <a class="nav-link-custom {{ request()->routeIs('contests.*') ? 'active' : '' }}" href="{{ route('contests.index') }}">
                    <i class="bi bi-trophy-fill me-2 text-warning"></i> {{ __('app.contests') }}
                </a>
                @auth
                    <a class="nav-link-custom {{ request()->routeIs('dashboard') || request()->routeIs('buyer.dashboard') || request()->routeIs('seller.dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-2 text-primary"></i> {{ __('app.dashboard') ?? 'Dashboard' }}
                    </a>
                @endauth
            </div>

            <!-- Mobile Language Switcher (Visible on small phones) -->
            <div class="d-sm-none d-flex align-items-center justify-content-between p-2.5 bg-light rounded-3 mb-3">
                <span class="small fw-semibold text-muted">Language / ভাষা</span>
                <div class="lang-switcher-pill m-0">
                    <a href="{{ route('lang.switch', 'bn') }}" class="lang-switcher-btn {{ $currentLocale === 'bn' ? 'active' : '' }}">
                        🇧🇩 বাংলা
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}" class="lang-switcher-btn {{ $currentLocale === 'en' ? 'active' : '' }}">
                        🇬🇧 EN
                    </a>
                </div>
            </div>

            <!-- Mobile Dark / Light Theme Toggle -->
            <div class="d-flex align-items-center justify-content-between p-2.5 bg-light rounded-3 mb-3">
                <span class="small fw-semibold text-muted">Theme / থিম</span>
                <button type="button" class="nav-action-btn theme-toggle-btn" aria-label="Toggle dark mode">
                    <svg class="theme-icon-sun text-warning" style="display: none; width: 1.15rem; height: 1.15rem;" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                    </svg>
                    <svg class="theme-icon-moon text-secondary" style="width: 1.15rem; height: 1.15rem;" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                </button>
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
                    @endif

                    @if($user->isContributor())
                        <a class="nav-link-custom text-primary" href="{{ route('resource.create') }}">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i> {{ __('dashboard.upload') }}
                        </a>
                    @else
                        <a class="nav-link-custom text-primary" href="{{ route('contributor.apply') }}">
                            <i class="bi bi-award-fill me-2"></i> Become a Contributor
                        </a>
                    @endif

                    <a class="nav-link-custom text-secondary" href="{{ route('orders.index') }}">
                        <i class="bi bi-receipt me-2 text-success"></i> {{ __('marketplace.orders') }}
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
