<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Noksha (নকশা) - AI-Powered Graphics Template Marketplace')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Vite Assets -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    <style>
        .lang-switcher-pill {
            background: rgba(108, 76, 241, 0.08);
            border: 1px solid rgba(108, 76, 241, 0.18);
            border-radius: 50rem;
            padding: 0.2rem;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .lang-switcher-btn {
            border-radius: 50rem;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            color: #4B5563;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .lang-switcher-btn:hover {
            color: #6C4CF1;
        }

        .lang-switcher-btn.active {
            background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(108, 76, 241, 0.35);
        }
    </style>
</head>
<body>

    <!-- HEADER NAVIGATION -->
    <header class="sticky-top bg-white border-bottom shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light py-3">
            <div class="container">
                <!-- Brand Logo & Mobile Action Bar -->
                <div class="d-flex align-items-center gap-2">
                    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                        <span class="noksha-logo-badge">ন</span>
                        <span class="fw-bold fs-4 tracking-tight text-dark">Noksha <span class="text-primary fs-5">(নকশা)</span></span>
                    </a>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <!-- Segmented Pill Language Switcher (Mobile & Tablet visible) -->
                    @php $currentLocale = app()->getLocale(); @endphp
                    <div class="lang-switcher-pill d-lg-none me-1">
                        <a href="{{ route('lang.switch', 'bn') }}" class="lang-switcher-btn {{ $currentLocale === 'bn' ? 'active' : '' }}">
                            🇧🇩 বাংলা
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" class="lang-switcher-btn {{ $currentLocale === 'en' ? 'active' : '' }}">
                            🇬🇧 EN
                        </a>
                    </div>

                    <!-- Mobile Toggle Button -->
                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#nokshaNavbar" aria-controls="nokshaNavbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <!-- Navigation Links & Actions -->
                <div class="collapse navbar-collapse" id="nokshaNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-lg-2">
                        <li class="nav-item">
                            <a class="nav-link fw-semibold {{ request()->routeIs('home') ? 'active text-primary' : 'text-secondary' }}" href="{{ route('home') }}">
                                <i class="bi bi-house-door me-1"></i> {{ __('app.home') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold {{ request()->routeIs('search.index') ? 'active text-primary' : 'text-secondary' }}" href="{{ route('search.index') }}">
                                <i class="bi bi-search me-1 text-primary"></i> {{ __('app.search') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold text-secondary" href="{{ route('home') }}#templates">
                                <i class="bi bi-grid me-1"></i> {{ __('app.templates') }}
                            </a>
                        </li>

                        @auth
                            <!-- SINGLE UNIFIED USER DASHBOARD LINK -->
                            <li class="nav-item">
                                <a class="nav-link fw-semibold {{ request()->routeIs('dashboard') || request()->routeIs('buyer.dashboard') || request()->routeIs('seller.dashboard') ? 'active text-primary' : 'text-secondary' }}" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1 text-primary"></i> {{ __('app.dashboard') }}
                                </a>
                            </li>
                        @endauth

                        <li class="nav-item">
                            <a class="nav-link fw-semibold {{ request()->routeIs('contests.*') ? 'active text-primary' : 'text-secondary' }}" href="{{ route('contests.index') }}">
                                <i class="bi bi-trophy-fill me-1 text-warning"></i> {{ __('app.contests') }}
                            </a>
                        </li>
                    </ul>

                    <!-- Search Bar Input Form -->
                    <form action="{{ route('search.index') }}" method="GET" class="d-flex me-lg-2 mb-2 mb-lg-0 role-search" style="max-width: 220px;">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input name="q" class="form-control bg-light border-start-0 ps-0 text-dark small" type="search" placeholder="{{ __('app.search') }}..." aria-label="Search">
                        </div>
                    </form>

                    <!-- Desktop Segmented Pill Language Switcher -->
                    <div class="lang-switcher-pill d-none d-lg-inline-flex me-2">
                        <a href="{{ route('lang.switch', 'bn') }}" class="lang-switcher-btn {{ $currentLocale === 'bn' ? 'active' : '' }}" title="বাংলা ভাষায় পরিবর্তন করুন">
                            🇧🇩 বাংলা
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" class="lang-switcher-btn {{ $currentLocale === 'en' ? 'active' : '' }}" title="Switch to English">
                            🇬🇧 English
                        </a>
                    </div>

                    <!-- Auth Actions / Active User Profile Dropdown -->
                    <div class="d-flex align-items-center gap-2">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-3 fw-bold">
                                {{ __('auth.login') }}
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-noksha btn-sm px-3 rounded-3 fw-bold">
                                {{ __('auth.register') }}
                            </a>
                        @else
                            @if(! auth()->user()->hasVerifiedEmail())
                                <a href="{{ route('verification.notice') }}" class="badge bg-warning text-dark text-decoration-none px-2.5 py-2 rounded-3 me-1" title="Email verification pending">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i> {{ __('auth.verify_email') }}
                                </a>
                            @endif

                            @php
                                $wishCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
                                $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                                $notifCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                                $recentNotifs = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(5)->get();
                                $user = auth()->user();
                            @endphp

                            <!-- Notification Bell Icon Dropdown -->
                            <div class="dropdown me-1">
                                <button class="btn btn-light border btn-sm position-relative rounded-3 px-2.5 py-1.5" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                                    <i class="bi bi-bell-fill text-warning fs-6"></i>
                                    @if($notifCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger extra-small">{{ $notifCount }}</span>
                                    @endif
                                </button>
                                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-0 overflow-hidden" style="width: 320px;">
                                    <div class="p-3 bg-light border-bottom d-flex align-items-center justify-content-between">
                                        <span class="fw-bold small text-dark"><i class="bi bi-bell me-1"></i> {{ __('app.notifications') }}</span>
                                        <span class="badge bg-primary rounded-pill extra-small">{{ $notifCount }} New</span>
                                    </div>
                                    <div class="list-group list-group-flush" style="max-height: 280px; overflow-y: auto;">
                                        @if($recentNotifs->count() > 0)
                                            @foreach($recentNotifs as $rn)
                                                <form action="{{ route('notifications.read', $rn->id) }}" method="POST" class="m-0">
                                                    @csrf
                                                    <button type="submit" class="list-group-item list-group-item-action p-3 text-start border-bottom small {{ !$rn->is_read ? 'bg-light font-weight-bold' : '' }}">
                                                        <div class="fw-bold text-dark extra-small">{{ $rn->title }}</div>
                                                        <div class="text-muted extra-small line-clamp-1">{{ $rn->message }}</div>
                                                        <div class="extra-small text-primary font-monospace mt-1">{{ $rn->created_at->diffForHumans() }}</div>
                                                    </button>
                                                </form>
                                            @endforeach
                                        @else
                                            <div class="p-3 text-center text-muted extra-small">{{ __('app.no_data') }}</div>
                                        @endif
                                    </div>
                                    <div class="p-2 bg-light text-center border-top">
                                        <a href="{{ route('notifications.index') }}" class="extra-small fw-bold text-primary text-decoration-none">
                                            {{ __('app.view_all') }} {{ __('app.notifications') }} <i class="bi bi-arrow-right me-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Wishlist Icon Badge -->
                            <a href="{{ route('wishlist.index') }}" class="btn btn-light border btn-sm position-relative rounded-3 px-2.5 py-1.5 me-1" title="Saved Wishlist">
                                <i class="bi bi-heart-fill text-danger fs-6 me-1"></i>
                                @if($wishCount > 0)
                                    <span class="badge bg-danger rounded-pill extra-small me-1">{{ $wishCount }}</span>
                                @endif
                                <span class="d-none d-xl-inline small fw-semibold">{{ __('marketplace.wishlist') }}</span>
                            </a>

                            <!-- Cart Icon Badge -->
                            <a href="{{ route('cart.index') }}" class="btn btn-light border btn-sm position-relative rounded-3 px-2.5 py-1.5 me-2" title="Shopping Cart">
                                <i class="bi bi-cart-fill text-primary fs-6 me-1"></i>
                                @if($cartCount > 0)
                                    <span class="badge bg-primary rounded-pill extra-small me-1">{{ $cartCount }}</span>
                                @endif
                                <span class="d-none d-xl-inline small fw-semibold">{{ __('marketplace.cart') }}</span>
                            </a>

                            <!-- User Profile Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-light border btn-sm dropdown-toggle d-flex align-items-center gap-2 rounded-3 px-3 py-1.5" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="noksha-logo-badge" style="width:26px; height:26px; font-size:0.8rem;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                    <span class="fw-semibold small text-dark">{{ $user->name }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2">
                                    <li class="px-3 py-2 border-bottom">
                                        <div class="fw-bold small text-dark">{{ $user->name }}</div>
                                        <div class="text-muted extra-small">
                                            @ {{ $user->username }} • 
                                            @if($user->isContributor())
                                                <span class="badge bg-success bg-opacity-10 text-success extra-small">Contributor ✓</span>
                                            @else
                                                <span class="badge bg-primary bg-opacity-10 text-primary extra-small">User / বায়ার</span>
                                            @endif
                                        </div>
                                    </li>

                                    @if(in_array($user->role, ['admin', 'super_admin']))
                                        <li>
                                            <a class="dropdown-item small py-2 fw-bold text-primary" href="{{ route('admin.dashboard') }}">
                                                <i class="bi bi-shield-lock-fill me-2 text-primary"></i> {{ __('dashboard.admin_dashboard') }}
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                    @endif

                                    <li>
                                        <a class="dropdown-item small py-2 fw-semibold" href="{{ route('dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2 text-primary"></i> {{ __('app.dashboard') }}
                                        </a>
                                    </li>

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

                                    <li>
                                        <a class="dropdown-item small py-2 fw-semibold" href="{{ route('orders.index') }}">
                                            <i class="bi bi-receipt me-2 text-success"></i> {{ __('marketplace.orders') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item small py-2 fw-semibold" href="{{ route('wishlist.index') }}">
                                            <i class="bi bi-heart me-2 text-danger"></i> {{ __('marketplace.wishlist') }}
                                        </a>
                                    </li>

                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger small py-2">
                                                <i class="bi bi-box-arrow-right me-2"></i> {{ __('auth.logout') }}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- MAIN CONTENT AREA -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-dark text-white pt-5 pb-4 mt-auto">
        <div class="container">
            <div class="row g-4 mb-4">
                <!-- Column 1: Brand Info -->
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="noksha-logo-badge">ন</span>
                        <span class="fw-bold fs-4 text-white">Noksha <span class="text-noksha-gradient fs-5">(নকশা)</span></span>
                    </div>
                    <p class="text-secondary small mb-3">
                        Noksha is an AI-Powered Graphics Template Marketplace connecting designers, creators, and buyers with automated tagging, intelligent search, and high-quality design assets.
                    </p>
                    <div class="d-flex gap-3 text-secondary fs-5">
                        <a href="#" class="text-secondary hover-text-white"><i class="bi bi-github"></i></a>
                        <a href="#" class="text-secondary hover-text-white"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-secondary hover-text-white"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-secondary hover-text-white"><i class="bi bi-discord"></i></a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-uppercase fw-bold text-white mb-3 tracking-wider">Marketplace</h6>
                    <ul class="list-unstyled text-secondary small d-grid gap-2">
                        <li><a href="#" class="text-secondary text-decoration-none">PSD Templates</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Vector Graphics</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">UI Wireframes</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Figma Assets</a></li>
                    </ul>
                </div>

                <!-- Column 3: AI Capabilities -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-uppercase fw-bold text-white mb-3 tracking-wider">AI Innovation</h6>
                    <ul class="list-unstyled text-secondary small d-grid gap-2">
                        <li><a href="#" class="text-secondary text-decoration-none">Auto-Tagging Engine</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Prompt-to-Asset Search</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">AI Metadata Assistance</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Smart Recommendations</a></li>
                    </ul>
                </div>

                <!-- Column 4: Project Info -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-uppercase fw-bold text-white mb-3 tracking-wider">Project Details</h6>
                    <p class="text-secondary small mb-2">
                        <i class="bi bi-mortarboard-fill me-1 text-primary"></i> University Final Year Project
                    </p>
                    <p class="text-secondary small mb-2">
                        <i class="bi bi-code-slash me-1 text-info"></i> Built with Laravel 12 & Bootstrap 5
                    </p>
                    <p class="text-secondary small">
                        <i class="bi bi-shield-check me-1 text-success"></i> Auth Engine & Verification Ready
                    </p>
                </div>
            </div>

            <hr class="border-secondary opacity-25 my-4">

            <!-- Bottom Copyright & Status -->
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between text-secondary small gap-2">
                <div>
                    &copy; 2026 <strong>Noksha (নকশা)</strong>. All rights reserved.
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ url('/docs/README.md') }}" class="text-secondary text-decoration-none">Documentation</a>
                    <span>•</span>
                    <a href="{{ url('/docs/SRS.md') }}" class="text-secondary text-decoration-none">SRS</a>
                    <span>•</span>
                    <a href="{{ url('/docs/CHANGELOG.md') }}" class="text-secondary text-decoration-none">Changelog</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- GLOBAL TOAST NOTIFICATION CONTAINER -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
        @if(session('success'))
            <div class="toast show align-items-center text-white bg-success border-0 shadow-lg rounded-4 p-1" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body small fw-bold">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if(session('warning'))
            <div class="toast show align-items-center text-dark bg-warning border-0 shadow-lg rounded-4 p-1" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body small fw-bold">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast show align-items-center text-white bg-danger border-0 shadow-lg rounded-4 p-1" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body small fw-bold">
                        <i class="bi bi-exclamation-diamond-fill me-2"></i>{{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <!-- GLOBAL LOADING INDICATOR & UX SCRIPTS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-hide toasts after 6 seconds
            document.querySelectorAll('.toast').forEach(t => {
                setTimeout(() => {
                    const bsToast = bootstrap.Toast.getOrCreateInstance(t);
                    bsToast.hide();
                }, 6000);
            });

            // Form Submit Loading Indicator
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function () {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled && !form.hasAttribute('data-no-loader')) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span> Processing...';
                    }
                });
            });
        });
    </script>

</body>
</html>
