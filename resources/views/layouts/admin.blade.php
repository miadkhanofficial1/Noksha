<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin HQ - Noksha')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tailwind CSS CDN (Guarantees all admin styling renders reliably) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#F5F3FF',
                            100: '#EDE9FE',
                            500: '#8B5CF6',
                            600: '#7C3AED',
                            700: '#6D28D9',
                            800: '#5B21B6',
                            900: '#4C1D95',
                        },
                        executive: {
                            dark: '#080B11',
                            card: '#0F1623',
                            sidebar: '#0B0F19',
                            border: '#1E293B',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>

    <!-- Anti-flicker Dark/Light Theme Script -->
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('admin_theme') || localStorage.getItem('theme');
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>

    <style>
        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.4);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.7);
        }
        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.25) 0%, rgba(139, 92, 246, 0.15) 100%);
            border-left: 3px solid #8B5CF6;
            color: #ffffff !important;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-[#080B11] text-gray-900 dark:text-gray-100 min-h-screen font-sans antialiased flex flex-col transition-colors duration-200">

    @php
        $pendingResourcesCount = \App\Models\Resource::where('status', 'pending')->count();
        $pendingKycCount = \App\Models\SellerVerification::where('status', 'pending')->count();
        $currentRoute = request()->route() ? request()->route()->getName() : '';
    @endphp

    <div class="flex h-screen overflow-hidden">
        <!-- ============================================================ -->
        <!-- EXECUTIVE SIDEBAR (Fixed, Dark High-Contrast Admin Panel)    -->
        <!-- ============================================================ -->
        <aside id="admin-sidebar" class="w-64 bg-[#0B0F19] text-gray-300 flex flex-col flex-shrink-0 border-r border-gray-800 transition-all duration-300 z-30 fixed inset-y-0 left-0 lg:static lg:translate-x-0 -translate-x-full">
            
            <!-- Brand / Logo Header -->
            <div class="h-16 flex items-center justify-between px-5 border-b border-gray-800/80 bg-[#080B11]/50">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-700 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-brand-500/20 group-hover:scale-105 transition-transform">
                        <i class="bi bi-shield-lock-fill text-lg"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-white text-base tracking-tight">NOKSHA</span>
                            <span class="text-[10px] uppercase font-bold tracking-widest bg-brand-900/80 text-brand-300 border border-brand-700/50 rounded px-1.5 py-0.5">HQ</span>
                        </div>
                        <span class="text-[10px] text-gray-400 font-medium block -mt-0.5 tracking-wider">COMMAND CENTER</span>
                    </div>
                </a>
                <button id="close-sidebar-btn" class="lg:hidden text-gray-400 hover:text-white p-1">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto px-3 py-4 space-y-1.5">
                <div class="px-3 pt-1 pb-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                    Executive Control
                </div>

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-lg text-sm transition-all duration-200 text-gray-300 hover:text-white hover:bg-gray-800/60 {{ str_starts_with($currentRoute, 'admin.dashboard') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-grid-1x2-fill text-brand-400 text-base"></i>
                        <span>Dashboard & Analytics</span>
                    </div>
                </a>

                <!-- Resource Moderation -->
                <a href="{{ route('admin.resources.index') }}"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-lg text-sm transition-all duration-200 text-gray-300 hover:text-white hover:bg-gray-800/60 {{ str_starts_with($currentRoute, 'admin.resources') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-collection-fill text-emerald-400 text-base"></i>
                        <span>Resource Moderation</span>
                    </div>
                    @if($pendingResourcesCount > 0)
                        <span class="bg-amber-500/20 text-amber-300 border border-amber-500/40 text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">
                            {{ $pendingResourcesCount }}
                        </span>
                    @endif
                </a>

                <!-- User & KYC Management -->
                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-lg text-sm transition-all duration-200 text-gray-300 hover:text-white hover:bg-gray-800/60 {{ str_starts_with($currentRoute, 'admin.users') || str_starts_with($currentRoute, 'admin.verifications') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-people-fill text-sky-400 text-base"></i>
                        <span>User & KYC Hub</span>
                    </div>
                    @if($pendingKycCount > 0)
                        <span class="bg-rose-500/20 text-rose-300 border border-rose-500/40 text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $pendingKycCount }}
                        </span>
                    @endif
                </a>

                <!-- Contest Management -->
                <a href="{{ route('admin.contests.index') }}"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-lg text-sm transition-all duration-200 text-gray-300 hover:text-white hover:bg-gray-800/60 {{ str_starts_with($currentRoute, 'admin.contests') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-trophy-fill text-amber-400 text-base"></i>
                        <span>Contest Management</span>
                    </div>
                </a>

                <div class="px-3 pt-4 pb-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                    Finance & Operations
                </div>

                <!-- Financials & Payouts -->
                <a href="{{ route('admin.payouts.index') }}"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-lg text-sm transition-all duration-200 text-gray-300 hover:text-white hover:bg-gray-800/60 {{ str_starts_with($currentRoute, 'admin.payouts') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-cash-stack text-teal-400 text-base"></i>
                        <span>Financials & Payouts</span>
                    </div>
                </a>

                <!-- Live System Logs -->
                <a href="{{ route('admin.logs.index') }}"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-lg text-sm transition-all duration-200 text-gray-300 hover:text-white hover:bg-gray-800/60 {{ str_starts_with($currentRoute, 'admin.logs') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-terminal-fill text-purple-400 text-base"></i>
                        <span>Live System Logs</span>
                    </div>
                </a>
            </div>

            <!-- Sidebar Footer: Quick Site Link & Profile -->
            <div class="p-3 border-t border-gray-800 bg-[#080B11]/60">
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center justify-between px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-gray-800 transition-colors mb-2">
                    <span class="flex items-center gap-2">
                        <i class="bi bi-box-arrow-up-right text-brand-400"></i>
                        <span>View Public Marketplace</span>
                    </span>
                    <i class="bi bi-chevron-right text-[10px]"></i>
                </a>

                <div class="flex items-center justify-between pt-2 border-t border-gray-800/60 px-1">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        <div class="w-8 h-8 rounded-full bg-brand-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <div class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Super Admin' }}</div>
                            <div class="text-[10px] text-brand-400 capitalize">Super Admin</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Direct Logout" class="p-1.5 text-gray-400 hover:text-rose-400 hover:bg-rose-500/10 rounded transition-colors">
                            <i class="bi bi-box-arrow-right text-base"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Sidebar Mobile Overlay Backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-black/60 z-20 hidden lg:hidden"></div>

        <!-- ============================================================ -->
        <!-- MAIN CONTENT CONTAINER                                       -->
        <!-- ============================================================ -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <!-- TOP EXECUTIVE HEADER BAR -->
            <header class="h-16 bg-white dark:bg-[#0F1623] border-b border-gray-200 dark:border-gray-800/80 px-4 sm:px-6 flex items-center justify-between z-10 transition-colors duration-200">
                <!-- Left: Mobile Toggle & Breadcrumbs -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <button id="mobile-toggle-btn" class="lg:hidden text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    <div>
                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <span>Admin HQ</span>
                            <i class="bi bi-chevron-right text-[9px]"></i>
                            <span class="text-gray-800 dark:text-gray-200 font-semibold">@yield('page_title', 'Command Center')</span>
                        </div>
                        <h1 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white tracking-tight">
                            @yield('page_heading', 'Executive Dashboard')
                        </h1>
                    </div>
                </div>

                <!-- Right: System Status & Controls -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Live System Indicator -->
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 -ml-4"></span>
                        <span>System Operational</span>
                        <span class="text-gray-400 dark:text-gray-600">|</span>
                        <span class="font-mono text-[11px] text-gray-500 dark:text-gray-400">PHP {{ PHP_VERSION }}</span>
                    </div>

                    <!-- Theme Switcher Toggle -->
                    <button id="admin-theme-toggle"
                            type="button"
                            title="Toggle Light/Dark Theme"
                            class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <i id="theme-icon-sun" class="bi bi-sun-fill text-amber-500 hidden text-base"></i>
                        <i id="theme-icon-moon" class="bi bi-moon-stars-fill text-indigo-400 hidden text-base"></i>
                    </button>

                    <!-- Admin Profile Quick Dropdown -->
                    <div class="relative" id="profile-dropdown-container">
                        <button id="profile-menu-btn" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-brand-600 to-indigo-500 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="hidden md:block text-xs font-medium text-gray-800 dark:text-gray-200">
                                {{ auth()->user()->name }}
                            </span>
                            <i class="bi bi-chevron-down text-[10px] text-gray-400"></i>
                        </button>

                        <div id="profile-dropdown-menu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-[#0F1623] rounded-xl shadow-xl border border-gray-200 dark:border-gray-800 py-1 z-50 text-sm">
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Signed in as</p>
                                <p class="font-semibold text-gray-900 dark:text-white truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800/80">
                                <i class="bi bi-shop me-1.5 text-brand-500"></i> Marketplace Store
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800/80">
                                <i class="bi bi-speedometer2 me-1.5 text-emerald-500"></i> Command Center
                            </a>
                            <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 flex items-center gap-1.5">
                                    <i class="bi bi-box-arrow-right"></i> Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- NOTIFICATIONS / ALERTS -->
            <div class="px-4 sm:px-6 pt-4">
                @if(session('success'))
                    <div class="p-3.5 mb-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <i class="bi bi-check-circle-fill text-emerald-600 dark:text-emerald-400 text-lg"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400">
                            <i class="bi bi-x-lg text-xs"></i>
                        </button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="p-3.5 mb-3 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-sm flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <i class="bi bi-exclamation-triangle-fill text-amber-600 dark:text-amber-400 text-lg"></i>
                            <span>{{ session('warning') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-amber-600 hover:text-amber-800 dark:text-amber-400">
                            <i class="bi bi-x-lg text-xs"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-3.5 mb-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <i class="bi bi-x-circle-fill text-rose-600 dark:text-rose-400 text-lg"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 dark:text-rose-400">
                            <i class="bi bi-x-lg text-xs"></i>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-3.5 mb-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm shadow-sm">
                        <div class="font-bold mb-1 flex items-center gap-1.5">
                            <i class="bi bi-exclamation-circle-fill text-rose-600 dark:text-rose-400"></i>
                            <span>Please resolve the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-xs">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- SCROLLABLE PAGE BODY -->
            <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- UI Interaction Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Theme toggle
            const html = document.documentElement;
            const themeToggleBtn = document.getElementById('admin-theme-toggle');
            const sunIcon = document.getElementById('theme-icon-sun');
            const moonIcon = document.getElementById('theme-icon-moon');

            function updateThemeIcons() {
                if (html.classList.contains('dark')) {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                } else {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                }
            }
            updateThemeIcons();

            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function() {
                    if (html.classList.contains('dark')) {
                        html.classList.remove('dark');
                        localStorage.setItem('admin_theme', 'light');
                        localStorage.setItem('theme', 'light');
                    } else {
                        html.classList.add('dark');
                        localStorage.setItem('admin_theme', 'dark');
                        localStorage.setItem('theme', 'dark');
                    }
                    updateThemeIcons();
                });
            }

            // Mobile sidebar toggle
            const mobileBtn = document.getElementById('mobile-toggle-btn');
            const closeBtn = document.getElementById('close-sidebar-btn');
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            }
            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }

            if (mobileBtn) mobileBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);

            // Profile dropdown toggle
            const profileBtn = document.getElementById('profile-menu-btn');
            const profileMenu = document.getElementById('profile-dropdown-menu');
            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileMenu.classList.toggle('hidden');
                });
                document.addEventListener('click', function() {
                    profileMenu.classList.add('hidden');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
