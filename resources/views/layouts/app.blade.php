<!DOCTYPE html>
<html lang="en">
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
</head>
<body>

    <!-- HEADER NAVIGATION -->
    <header class="sticky-top bg-white border-bottom shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light py-3">
            <div class="container">
                <!-- Brand Logo -->
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                    <span class="noksha-logo-badge">ন</span>
                    <span class="fw-bold fs-4 tracking-tight text-dark">Noksha <span class="text-primary fs-5">(নকশা)</span></span>
                </a>

                <!-- Mobile Toggle Button -->
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#nokshaNavbar" aria-controls="nokshaNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation Links & Actions -->
                <div class="collapse navbar-collapse" id="nokshaNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-lg-1">
                        <li class="nav-item">
                            <a class="nav-link fw-semibold {{ request()->routeIs('home') ? 'active text-primary' : 'text-secondary' }}" href="{{ route('home') }}">
                                <i class="bi bi-house-door me-1"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold text-secondary" href="{{ route('home') }}#templates">
                                <i class="bi bi-grid me-1"></i> Templates
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold text-secondary" href="{{ route('home') }}#ai-features">
                                <i class="bi bi-magic me-1"></i> AI Tools
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold {{ request()->routeIs('seller.dashboard') ? 'active text-primary' : 'text-secondary' }}" href="{{ route('seller.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1 text-primary"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold {{ request()->routeIs('seller.demo') ? 'active text-primary' : 'text-secondary' }}" href="{{ route('seller.demo') }}">
                                <i class="bi bi-person-badge me-1"></i> Seller Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold {{ request()->routeIs('seller.verification.create') ? 'active text-primary' : 'text-secondary' }}" href="{{ route('seller.verification.create') }}">
                                <i class="bi bi-shield-check me-1 text-success"></i> Verify Identity
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold {{ request()->routeIs('admin.resources.index') ? 'active text-primary' : 'text-secondary' }}" href="{{ route('admin.resources.index') }}">
                                <i class="bi bi-shield-lock-fill me-1 text-warning"></i> Admin Panel
                            </a>
                        </li>
                    </ul>

                    <!-- Search Bar Placeholder -->
                    <form class="d-flex me-lg-3 mb-2 mb-lg-0 role-search" style="max-width: 280px;">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input class="form-group form-control bg-light border-start-0 ps-0" type="search" placeholder="Search templates, AI tags..." aria-label="Search">
                        </div>
                    </form>

                    <!-- Auth Actions / Active User Profile Dropdown -->
                    <div class="d-flex align-items-center gap-2 ms-lg-2">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-3">
                                Sign In / লগইন
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-noksha btn-sm px-3 rounded-3">
                                Register / নিবন্ধন
                            </a>
                        @else
                            @if(! auth()->user()->hasVerifiedEmail())
                                <a href="{{ route('verification.notice') }}" class="badge bg-warning text-dark text-decoration-none px-2.5 py-2 rounded-3 me-1" title="Email verification pending">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i> Verify Email
                                </a>
                            @endif

                            <div class="dropdown">
                                <button class="btn btn-light border btn-sm dropdown-toggle d-flex align-items-center gap-2 rounded-3 px-3 py-1.5" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="noksha-logo-badge" style="width:26px; height:26px; font-size:0.8rem;">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                    <span class="fw-semibold small text-dark">{{ auth()->user()->name }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2">
                                    <li class="px-3 py-2 border-bottom">
                                        <div class="fw-bold small text-dark">{{ auth()->user()->name }}</div>
                                        <div class="text-muted small">@ {{ auth()->user()->username }}</div>
                                    <li>
                                        <a class="dropdown-item small py-2 fw-semibold" href="{{ route('seller.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2 text-primary"></i> Seller Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item small py-2 fw-semibold" href="{{ route('resource.create') }}">
                                            <i class="bi bi-cloud-arrow-up-fill me-2 text-primary"></i> Upload Resource
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item small py-2 fw-semibold" href="{{ route('seller.demo') }}">
                                            <i class="bi bi-person-badge me-2 text-primary"></i> My Profile
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger small py-2">
                                                <i class="bi bi-box-arrow-right me-2"></i> Log Out / লগআউট
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

</body>
</html>
