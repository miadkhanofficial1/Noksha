<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
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

    <!-- Dark / Light Theme Anti-Flicker Script (Executes before CSS render) -->
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('theme');
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>

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

        html.dark .lang-switcher-pill {
            background: rgba(99, 102, 241, 0.15);
            border-color: rgba(99, 102, 241, 0.25);
        }

        html.dark .lang-switcher-btn {
            color: #9CA3AF;
        }

        html.dark .lang-switcher-btn:hover {
            color: #A5B4FC;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-[#0B0F19] dark:text-gray-100 min-h-screen d-flex flex-column transition-colors duration-300">

    <!-- HEADER NAVIGATION -->
    @include('layouts.navigation')

    <!-- MAIN CONTENT AREA -->
    <main class="flex-grow-1">
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

    <!-- GLOBAL UX & THEME SCRIPTS -->
    <script>
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            try {
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            } catch (e) {}
            updateThemeIcons();
        }

        function updateThemeIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('.theme-icon-sun').forEach(el => {
                el.style.display = isDark ? 'block' : 'none';
            });
            document.querySelectorAll('.theme-icon-moon').forEach(el => {
                el.style.display = isDark ? 'none' : 'block';
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Theme Icons State
            updateThemeIcons();

            // Bind click to all theme toggle buttons
            document.querySelectorAll('.theme-toggle-btn').forEach(btn => {
                btn.addEventListener('click', toggleTheme);
            });

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