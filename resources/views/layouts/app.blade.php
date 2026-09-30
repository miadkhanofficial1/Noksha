<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Noksha')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Permanent Dark Mode -->
    <script>
        try { localStorage.removeItem('theme'); } catch(e) {}
        document.documentElement.classList.add('dark');
    </script>

    <!-- Tailwind CSS CDN (Guarantees modern utility classes render reliably) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        noksha: {
                            primary: '#4F46E5',
                            secondary: '#7C3AED',
                            accent: '#06B6D4',
                            dark: '#0F172A',
                            surface: '#1E293B',
                            light: '#F8FAFC',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])

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
<body class="bg-slate-950 text-slate-100 min-h-screen d-flex flex-column">

    <!-- INITIAL PRELOADER / SPLASH SCREEN -->
    @include('partials.preloader')

    <!-- HEADER NAVIGATION -->
    @include('layouts.navigation')

    <!-- MAIN CONTENT AREA -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- FOOTER -->
    @include('layouts.footer')

    <!-- GLOBAL TOAST NOTIFICATION CONTAINER -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
        @if(session('success') || session('status'))
            <div class="toast show align-items-center text-white bg-success border-0 shadow-lg rounded-4 p-1" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body small fw-bold">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') ?? session('status') }}
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

    <!-- ========================================================= -->
    <!-- ADMIN ANNOUNCEMENT BROADCAST MODAL (Centered Fullscreen)  -->
    <!-- ========================================================= -->
    <div id="broadcastModal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm d-none"
         style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; display: flex !important; align-items: center; justify-content: center; z-index: 99999; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); padding: 1rem; opacity: 0; transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1); pointer-events: none;"
         aria-modal="true" role="dialog" aria-labelledby="broadcastModalTitle">
        
        <!-- Modal Card -->
        <div id="broadcastModalCard"
             class="relative w-full max-w-lg rounded-2xl border border-purple-500/30 bg-[#0f172a] p-6 shadow-2xl text-center"
             style="position: relative; width: 100%; max-width: 32rem; border-radius: 1rem; border: 1px solid rgba(168, 85, 247, 0.3); background-color: #0f172a; padding: 1.75rem 1.5rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6); text-align: center; color: #ffffff; transform: scale(0.9) translateY(20px); transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1); overflow: hidden;">
            
            <!-- Top Gradient Accent Strip -->
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #8b5cf6, #d946ef, #f59e0b);"></div>

            <!-- Crisp Close Button ('X') in Top-Right Corner -->
            <button id="broadcastModalClose" type="button" aria-label="Close Announcement"
                    class="absolute top-4 right-4 text-gray-400 hover:text-white cursor-pointer"
                    style="position: absolute; top: 1rem; right: 1rem; color: #9ca3af; background: transparent; border: none; font-size: 1.25rem; line-height: 1; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0.25rem; transition: color 0.2s; z-index: 10;"
                    onmouseover="this.style.color='#ffffff';"
                    onmouseout="this.style.color='#9ca3af';">
                <i class="bi bi-x-lg"></i>
            </button>

            <!-- Megaphone Announcement Badge & Icon -->
            <div class="mb-3">
                <div style="width: 56px; height: 56px; border-radius: 1rem; background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4); border: 2px solid rgba(255, 255, 255, 0.15);">
                    <i class="bi bi-megaphone-fill text-white fs-4"></i>
                </div>
                <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill mb-1"
                     style="background: rgba(168, 85, 247, 0.15); border: 1px solid rgba(168, 85, 247, 0.3);">
                    <i class="bi bi-patch-check-fill text-purple-400" style="font-size: 0.8rem; color: #c084fc;"></i>
                    <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #c084fc;">
                        Platform Announcement
                    </span>
                </div>
                <h3 id="broadcastModalTitle" class="font-extrabold text-white mt-2 mb-0 px-3"
                    style="font-size: 1.25rem; line-height: 1.35; color: #ffffff;"></h3>
            </div>

            <!-- Subtle Divider -->
            <div style="height: 1px; background: linear-gradient(90deg, transparent, rgba(168, 85, 247, 0.35), transparent); margin: 1rem 0;"></div>

            <!-- Message Body -->
            <div class="px-2">
                <p id="broadcastModalMessage" class="text-gray-300 text-center mb-0"
                   style="font-size: 0.925rem; line-height: 1.65; color: #cbd5e1; white-space: pre-line; word-break: break-word;"></p>
            </div>

            <!-- Footer with Branding & Date -->
            <div class="d-flex align-items-center justify-content-between pt-3 mt-4 border-top"
                 style="border-color: rgba(255, 255, 255, 0.08) !important;">
                <div class="d-flex align-items-center gap-1.5 text-gray-400" style="font-size: 0.75rem; color: #94a3b8;">
                    <i class="bi bi-clock-history"></i>
                    <span id="broadcastModalDate" class="font-monospace"></span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Noksha" style="height: 22px; width: auto; object-fit: contain;">
                    <span class="font-bold text-white" style="font-size: 0.85rem; color: #ffffff;">Noksha</span>
                </div>
            </div>

            <!-- Dismiss Button -->
            <div class="mt-3">
                <button id="broadcastModalDismiss" type="button"
                        class="w-full py-2.5 rounded-xl font-bold text-white text-xs shadow-lg transition-all"
                        style="width: 100%; padding: 0.65rem 1rem; border-radius: 0.75rem; font-weight: 700; font-size: 0.825rem; background: linear-gradient(135deg, #7c3aed, #9333ea); border: 1px solid rgba(168, 85, 247, 0.4); color: #ffffff; cursor: pointer; box-shadow: 0 4px 15px rgba(124, 58, 237, 0.35);"
                        onmouseover="this.style.background='linear-gradient(135deg, #6d28d9, #7e22ce)';"
                        onmouseout="this.style.background='linear-gradient(135deg, #7c3aed, #9333ea)';">
                    <i class="bi bi-check2-circle me-1"></i> Understood & Dismiss
                </button>
            </div>

            <!-- Canvas for Festive Confetti -->
            <canvas id="broadcastConfettiCanvas"
                    style="position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; z-index: 1;"></canvas>
        </div>
    </div>

    <!-- GLOBAL UX SCRIPTS -->
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

        <!-- ======================================================= -->
    <!-- NOTIFICATION SYSTEM: Badge-clear, AJAX read, Broadcast Modal + Confetti -->
    <!-- ======================================================= -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
    <script>
    (function () {
        'use strict';

        function getCsrf() {
            var m = document.querySelector('meta[name="csrf-token"]');
            return m ? m.getAttribute('content') : '';
        }

        // ── Celebration Confetti: Falling festive streamers & fireworks ──
        function fireCelebrationConfetti() {
            if (typeof confetti === 'undefined') return;

            try {
                // Cannon burst from left & right corners
                confetti({
                    particleCount: 80,
                    angle: 60,
                    spread: 65,
                    origin: { x: 0.1, y: 0.65 },
                    colors: ['#6366F1', '#EC4899', '#F59E0B', '#10B981', '#8B5CF6', '#3B82F6'],
                    zIndex: 100005
                });
                confetti({
                    particleCount: 80,
                    angle: 120,
                    spread: 65,
                    origin: { x: 0.9, y: 0.65 },
                    colors: ['#6366F1', '#EC4899', '#F59E0B', '#10B981', '#8B5CF6', '#3B82F6'],
                    zIndex: 100005
                });

                // Center fireworks star shower after 250ms
                setTimeout(function () {
                    confetti({
                        particleCount: 100,
                        spread: 100,
                        origin: { x: 0.5, y: 0.35 },
                        colors: ['#F59E0B', '#EC4899', '#6366F1', '#10B981', '#FCD34D'],
                        gravity: 0.8,
                        scalar: 1.15,
                        zIndex: 100005
                    });
                }, 250);

                // Celebratory cascade for 2 seconds
                var end = Date.now() + 2000;
                var interval = setInterval(function () {
                    if (Date.now() > end) {
                        return clearInterval(interval);
                    }
                    confetti({
                        particleCount: 25,
                        startVelocity: 30,
                        spread: 360,
                        ticks: 60,
                        origin: { x: Math.random(), y: Math.random() * 0.4 },
                        colors: ['#6366F1', '#EC4899', '#F59E0B', '#34D399', '#A78BFA'],
                        zIndex: 100005
                    });
                }, 250);
            } catch (err) {
                console.error('Confetti error:', err);
            }
        }

        // ── Admin Broadcast Modal Controls ──
        var broadcastModal        = document.getElementById('broadcastModal');
        var broadcastModalCard    = document.getElementById('broadcastModalCard');
        var modalClose            = document.getElementById('broadcastModalClose');
        var modalDismiss          = document.getElementById('broadcastModalDismiss');

        function openBroadcastModal(title, message, date) {
            if (!broadcastModal) return;
            var titleEl = document.getElementById('broadcastModalTitle');
            var msgEl   = document.getElementById('broadcastModalMessage');
            var dateEl  = document.getElementById('broadcastModalDate');

            if (titleEl) titleEl.textContent = title;
            if (msgEl)   msgEl.textContent   = message;
            if (dateEl)  dateEl.textContent  = date;

            broadcastModal.classList.remove('d-none');
            broadcastModal.style.pointerEvents = 'all';

            requestAnimationFrame(function () {
                broadcastModal.style.opacity = '1';
                if (broadcastModalCard) {
                    broadcastModalCard.style.transform = 'scale(1) translateY(0)';
                }
            });

            setTimeout(fireCelebrationConfetti, 150);
        }

        function closeBroadcastModal() {
            if (!broadcastModal) return;
            broadcastModal.style.opacity = '0';
            if (broadcastModalCard) {
                broadcastModalCard.style.transform = 'scale(0.85) translateY(20px)';
            }
            broadcastModal.style.pointerEvents = 'none';
            setTimeout(function () {
                broadcastModal.classList.add('d-none');
            }, 320);
        }

        if (modalClose)   modalClose.addEventListener('click', closeBroadcastModal);
        if (modalDismiss) modalDismiss.addEventListener('click', closeBroadcastModal);
        if (broadcastModal) {
            broadcastModal.addEventListener('click', function (e) {
                if (e.target === broadcastModal) closeBroadcastModal();
            });
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeBroadcastModal();
        });

        // ── Individual Notification Click & Read Handler + Live Bell Badge Sync ──
        function handleNotificationItemClick(item, e) {
            var isBroadcast = item.dataset.isBroadcast === 'true';
            var ajaxUrl     = item.dataset.ajaxReadUrl || ('/notifications/' + item.dataset.notifId + '/mark-as-read');
            var redirectUrl = (item.dataset.redirectUrl || '').trim();
            var isUnread    = item.dataset.isUnread === 'true' || item.classList.contains('notif-unread') || item.querySelector('.notif-unread-dot') !== null;

            // 1. Live state update if notification is unread
            if (isUnread) {
                item.dataset.isUnread = 'false';
                item.classList.remove('bg-light', 'notif-unread', 'border-primary');

                // Remove glowing green dot with animation
                var dot = item.querySelector('.notif-unread-dot');
                if (dot) {
                    dot.style.transition = 'all 0.25s ease';
                    dot.style.transform  = 'scale(0)';
                    dot.style.opacity    = '0';
                    setTimeout(function () { dot.remove(); }, 250);
                }

                // Decrement Bell Icon Badge Counter (#notifBadgeCount)
                var bellBadge = document.getElementById('notifBadgeCount');
                if (bellBadge) {
                    var currentBell = parseInt(bellBadge.textContent.trim(), 10) || 0;
                    var newBellCount = Math.max(0, currentBell - 1);
                    if (newBellCount > 0) {
                        bellBadge.textContent = newBellCount;
                        bellBadge.classList.remove('d-none');
                    } else {
                        bellBadge.textContent = '';
                        bellBadge.classList.add('d-none');
                    }
                }

                // Decrement Header Badge inside Dropdown (#notifHeaderBadge)
                var headerBadge = document.getElementById('notifHeaderBadge');
                if (headerBadge) {
                    var currentHeader = parseInt(headerBadge.textContent.trim(), 10) || 0;
                    var newHeaderCount = Math.max(0, currentHeader - 1);
                    if (newHeaderCount > 0) {
                        headerBadge.textContent = newHeaderCount + ' unread';
                        headerBadge.classList.remove('d-none');
                    } else {
                        headerBadge.classList.add('d-none');
                    }
                }

                // Send dedicated lightweight AJAX fetch request to mark as read
                if (ajaxUrl) {
                    fetch(ajaxUrl, {
                        method:  'POST',
                        headers: {
                            'X-CSRF-TOKEN': getCsrf(),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (data && typeof data.unread_count !== 'undefined') {
                            var serverCount = parseInt(data.unread_count, 10) || 0;
                            if (bellBadge) {
                                if (serverCount > 0) {
                                    bellBadge.textContent = serverCount;
                                    bellBadge.classList.remove('d-none');
                                } else {
                                    bellBadge.textContent = '';
                                    bellBadge.classList.add('d-none');
                                }
                            }
                            if (headerBadge) {
                                if (serverCount > 0) {
                                    headerBadge.textContent = serverCount + ' unread';
                                    headerBadge.classList.remove('d-none');
                                } else {
                                    headerBadge.classList.add('d-none');
                                }
                            }
                        }
                    })
                    .catch(function (err) {
                        console.warn('Mark as read sync error:', err);
                    });
                }
            }

            // 2. Broadcast / Platform Announcement Modal
            if (isBroadcast) {
                if (e) e.preventDefault();
                var bellBtn = document.getElementById('notifBellBtn');
                if (bellBtn && typeof bootstrap !== 'undefined') {
                    var bsDropdown = bootstrap.Dropdown.getInstance(bellBtn);
                    if (bsDropdown) bsDropdown.hide();
                }

                var title   = item.dataset.notifTitle   || 'Platform Announcement';
                var message = item.dataset.notifMessage || '';
                var date    = item.dataset.notifDate    || '';
                openBroadcastModal(title, message, date);
                return;
            }

            // 3. Redirection handling: only redirect if a valid destination URL exists
            var hasValidUrl = redirectUrl && redirectUrl !== '' && redirectUrl !== '#' && redirectUrl !== 'javascript:void(0)' && !redirectUrl.startsWith('javascript:');
            if (hasValidUrl) {
                if (e) e.preventDefault();
                setTimeout(function () {
                    window.location.href = redirectUrl;
                }, 100);
            } else {
                if (e) e.preventDefault();
            }
        }

        // Attach click listeners to all dropdown and page notification items
        document.querySelectorAll('.notif-dropdown-item, .notif-item-wrapper').forEach(function (item) {
            item.addEventListener('click', function (e) {
                handleNotificationItemClick(item, e);
            });
        });
    }());
    </script>
</body>
</html>