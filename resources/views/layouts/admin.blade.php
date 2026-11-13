<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
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

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <!-- Tailwind CSS CDN (Guarantees all admin styling renders reliably) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#F5F3FF',
                            100: '#EDE9FE',
                            500: '#20165c',
                            600: '#181045',
                            700: '#120c38',
                            purple: '#20165c',
                            accent: '#f47402',
                            tangerine: '#f47402',
                        },
                        noksha: {
                            purple: '#20165c',
                            accent: '#f47402',
                            charcoal: '#121826',
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

    <!-- Ensure Light Mode Standard -->
    <script>
        try { localStorage.removeItem('admin_theme'); localStorage.removeItem('theme'); } catch(e) {}
        document.documentElement.classList.remove('dark');
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
            background: rgba(148, 163, 184, 0.4);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.7);
        }
        .sidebar-link.active {
            background: #20165c !important;
            color: #ffffff !important;
            font-weight: 600;
        }
        .sidebar-link.active i {
            color: #ffffff !important;
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-[#121826] min-h-screen font-sans antialiased flex flex-col">

    @php
        $pendingResourcesCount = \App\Models\Resource::where('status', 'pending')->count();
        $pendingKycCount = \App\Models\SellerVerification::where('status', 'pending')->count();
        $pendingContestsCount = \App\Models\Contest::where('status', 'pending_approval')->count();
        $unreadMessagesCount = \App\Models\ContactMessage::where('status', 'unread')->count();
        $pendingPayoutsCount = \App\Models\Withdrawal::where('status', 'pending')->count();
        $disputedOrdersCount = \App\Models\ServiceOrder::where('status', 'disputed')->count();
        $currentRoute = request()->route() ? request()->route()->getName() : '';
    @endphp

    <div class="flex h-screen overflow-hidden">
        <!-- ============================================================ -->
        <!-- EXECUTIVE SIDEBAR (Clean White Surface & Royal Purple Accent)-->
        <!-- ============================================================ -->
        <aside id="admin-sidebar" class="w-64 bg-white text-slate-700 flex flex-col flex-shrink-0 border-r border-slate-200 transition-all duration-300 z-30 fixed inset-y-0 left-0 lg:static lg:translate-x-0 -translate-x-full shadow-sm">
            
            <!-- Brand / Logo Header -->
            <div class="h-16 flex items-center justify-between px-5 border-b border-slate-200 bg-white">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="Noksha" class="h-9 w-auto object-contain">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-[#20165c] text-base tracking-tight">NOKSHA</span>
                            <span class="text-[10px] uppercase font-bold tracking-widest bg-purple-50 text-[#20165c] border border-purple-200 rounded px-1.5 py-0.5">HQ</span>
                        </div>
                        <span class="text-[10px] text-slate-500 font-semibold block -mt-0.5 tracking-wider">COMMAND CENTER</span>
                    </div>
                </a>
                <button id="close-sidebar-btn" class="lg:hidden text-slate-400 hover:text-slate-700 p-1">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto px-3 py-4 space-y-1.5">
                <div class="px-3 pt-1 pb-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Executive Control
                </div>

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all duration-200 text-slate-700 hover:text-[#20165c] hover:bg-purple-50/70 {{ str_starts_with($currentRoute, 'admin.dashboard') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-grid-1x2-fill text-[#20165c] text-base"></i>
                        <span>Dashboard & Analytics</span>
                    </div>
                </a>

                <!-- Resource Moderation -->
                <a href="{{ route('admin.resources.index') }}"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all duration-200 text-slate-700 hover:text-[#20165c] hover:bg-purple-50/70 {{ str_starts_with($currentRoute, 'admin.resources') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-collection-fill text-emerald-600 text-base"></i>
                        <span>Resource Moderation</span>
                    </div>
                    @if($pendingResourcesCount > 0)
                        <span class="bg-orange-50 text-[#f47402] border border-orange-200 text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">
                            {{ $pendingResourcesCount }}
                        </span>
                    @endif
                </a>

                <!-- User & KYC Management -->
                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all duration-200 text-slate-700 hover:text-[#20165c] hover:bg-purple-50/70 {{ str_starts_with($currentRoute, 'admin.users') || str_starts_with($currentRoute, 'admin.verifications') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-people-fill text-sky-600 text-base"></i>
                        <span>User & KYC Hub</span>
                    </div>
                    @if($pendingKycCount > 0)
                        <span class="bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $pendingKycCount }}
                        </span>
                    @endif
                </a>

                <!-- Contest Management -->
                <a href="{{ route('admin.contests.index') }}"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all duration-200 text-slate-700 hover:text-[#20165c] hover:bg-purple-50/70 {{ str_starts_with($currentRoute, 'admin.contests') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-trophy-fill text-[#f47402] text-base"></i>
                        <span>Contest Hub & Escrow</span>
                    </div>
                    @if($pendingContestsCount > 0)
                        <span class="bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">
                            {{ $pendingContestsCount }}
                        </span>
                    @endif
                </a>

                <!-- Broadcast Alerts & History -->
                <a href="{{ route('admin.broadcastHistory') }}"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all duration-200 text-slate-700 hover:text-[#20165c] hover:bg-purple-50/70 {{ str_starts_with($currentRoute, 'admin.broadcastHistory') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-megaphone-fill text-indigo-600 text-base"></i>
                        <span>Broadcast Alerts</span>
                    </div>
                </a>

                <!-- Customer Support Messages Inbox -->
                <a href="{{ route('admin.messages.index') }}"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all duration-200 text-slate-700 hover:text-[#20165c] hover:bg-purple-50/70 {{ str_starts_with($currentRoute, 'admin.messages') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-chat-left-dots-fill text-purple-600 text-base"></i>
                        <span>Support Messages</span>
                    </div>
                    @if($unreadMessagesCount > 0)
                        <span class="bg-purple-50 text-[#20165c] border border-purple-200 text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">
                            {{ $unreadMessagesCount }}
                        </span>
                    @endif
                </a>

                <div class="px-3 pt-4 pb-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Finance & Operations
                </div>

                <!-- Financials & Payouts -->
                <a href="{{ route('admin.finance.index') }}"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all duration-200 text-slate-700 hover:text-[#20165c] hover:bg-purple-50/70 {{ (str_starts_with($currentRoute, 'admin.finance') || str_starts_with($currentRoute, 'admin.payouts')) ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-cash-stack text-teal-600 text-base"></i>
                        <span>Financials & Payouts</span>
                    </div>
                    @if($pendingPayoutsCount > 0)
                        <span class="bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">
                            {{ $pendingPayoutsCount }}
                        </span>
                    @endif
                </a>

                <!-- Services & Escrow Hub -->
                <a href="{{ route('admin.services-hub.index') }}"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all duration-200 text-slate-700 hover:text-[#20165c] hover:bg-purple-50/70 {{ str_starts_with($currentRoute, 'admin.services-hub') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-briefcase-fill text-[#f47402] text-base"></i>
                        <span>Services & Escrow</span>
                    </div>
                    @if($disputedOrdersCount > 0)
                        <span class="bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">
                            {{ $disputedOrdersCount }}
                        </span>
                    @endif
                </a>

                <!-- Live System Logs -->
                <a href="{{ route('admin.logs.index') }}"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all duration-200 text-slate-700 hover:text-[#20165c] hover:bg-purple-50/70 {{ str_starts_with($currentRoute, 'admin.logs') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-terminal-fill text-slate-600 text-base"></i>
                        <span>Live System Logs</span>
                    </div>
                </a>

                <!-- Factory Reset / Danger Zone Shortcut -->
                <a href="{{ route('admin.dashboard') }}#danger-zone"
                   class="sidebar-link flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all duration-200 text-rose-600 hover:text-rose-700 hover:bg-rose-50">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-radioactive text-rose-500 text-base"></i>
                        <span>Factory Reset</span>
                    </div>
                    <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-rose-50 text-rose-600 border border-rose-200">Danger</span>
                </a>
            </div>

            <!-- Sidebar Footer: Quick Site Link & Profile -->
            <div class="p-3 border-t border-slate-200 bg-slate-50">
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center justify-between px-3 py-2 rounded-xl text-xs text-slate-600 hover:text-[#20165c] hover:bg-purple-50 transition-colors mb-2">
                    <span class="flex items-center gap-2">
                        <i class="bi bi-box-arrow-up-right text-[#20165c]"></i>
                        <span class="font-medium">View Public Marketplace</span>
                    </span>
                    <i class="bi bi-chevron-right text-[10px]"></i>
                </a>

                <div class="flex items-center justify-between pt-2 border-t border-slate-200 px-1">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        <div class="w-8 h-8 rounded-full bg-[#20165c] flex items-center justify-center text-white font-bold text-xs flex-shrink-0 shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <div class="text-xs font-semibold text-[#121826] truncate">{{ auth()->user()->name ?? 'Super Admin' }}</div>
                            <div class="text-[10px] text-[#f47402] font-semibold">Super Admin</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Direct Logout" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                            <i class="bi bi-box-arrow-right text-base"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Sidebar Mobile Overlay Backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-black/40 z-20 hidden lg:hidden"></div>

        <!-- ============================================================ -->
        <!-- MAIN CONTENT CONTAINER                                       -->
        <!-- ============================================================ -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#f8fafc]">

            <!-- TOP EXECUTIVE HEADER BAR -->
            <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between z-10 transition-colors duration-200">
                <!-- Left: Mobile Toggle & Breadcrumbs -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <button id="mobile-toggle-btn" class="lg:hidden text-slate-600 hover:text-slate-900 p-2 rounded-lg hover:bg-slate-100">
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    <div>
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <span>Admin HQ</span>
                            <i class="bi bi-chevron-right text-[9px]"></i>
                            <span class="text-[#121826] font-semibold">@yield('page_title', 'Command Center')</span>
                        </div>
                        <h1 class="text-base sm:text-lg font-bold text-[#121826] tracking-tight">
                            @yield('page_heading', 'Executive Dashboard')
                        </h1>
                    </div>
                </div>

                <!-- Right: System Status & Controls -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Live System Indicator -->
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 -ml-4"></span>
                        <span>System Operational</span>
                        <span class="text-slate-300">|</span>
                        <span class="font-mono text-[11px] text-slate-500">PHP {{ PHP_VERSION }}</span>
                    </div>

                    <!-- Admin Profile Quick Dropdown -->
                    <div class="relative" id="profile-dropdown-container">
                        <button id="profile-menu-btn" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-[#20165c] flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="hidden md:block text-xs font-medium text-slate-800">
                                {{ auth()->user()->name }}
                            </span>
                            <i class="bi bi-chevron-down text-[10px] text-slate-400"></i>
                        </button>

                        <div id="profile-dropdown-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-200 py-1 z-50 text-sm">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-500">Signed in as</p>
                                <p class="font-semibold text-[#121826] truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-slate-50">
                                <i class="bi bi-shop me-1.5 text-[#20165c]"></i> Marketplace Store
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-slate-50">
                                <i class="bi bi-speedometer2 me-1.5 text-emerald-600"></i> Command Center
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 flex items-center gap-1.5">
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
                    <div class="p-3.5 mb-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <i class="bi bi-check-circle-fill text-emerald-600 text-lg"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 cursor-pointer">
                            <i class="bi bi-x-lg text-xs"></i>
                        </button>
                    </div>
                @elseif(session('status'))
                    <div class="p-3.5 mb-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <i class="bi bi-check-circle-fill text-emerald-600 text-lg"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 cursor-pointer">
                            <i class="bi bi-x-lg text-xs"></i>
                        </button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="p-3.5 mb-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <i class="bi bi-exclamation-triangle-fill text-amber-600 text-lg"></i>
                            <span>{{ session('warning') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-amber-600 hover:text-amber-800 cursor-pointer">
                            <i class="bi bi-x-lg text-xs"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-3.5 mb-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <i class="bi bi-x-circle-fill text-rose-600 text-lg"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 cursor-pointer">
                            <i class="bi bi-x-lg text-xs"></i>
                        </button>
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="p-3.5 mb-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm">
                        <div class="font-bold mb-1 flex items-center gap-1.5">
                            <i class="bi bi-exclamation-circle-fill text-rose-600"></i>
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
