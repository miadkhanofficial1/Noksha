<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Design Workspace Locked - Noksha</title>
    
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center relative overflow-hidden font-sans antialiased">

    <!-- Blueprint Drafting Grid Background -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b15_1px,transparent_1px),linear-gradient(to_bottom,#1e293b15_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none"></div>

    <!-- Ambient Radial Glow -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-amber-500/10 rounded-full blur-[128px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-[128px] pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[32rem] h-[32rem] bg-purple-500/10 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="relative z-10 max-w-xl w-full mx-4 text-center space-y-8 p-8 sm:p-10 rounded-3xl bg-slate-900/60 border border-slate-800/90 shadow-2xl backdrop-blur-xl">
        
        <!-- VISUAL MOTIF: INTERLOCKING GLOWING PADLOCK & BÉZIER VECTOR ANCHORS -->
        <div class="relative w-44 h-44 mx-auto flex items-center justify-center">
            
            <!-- Rotating/pulsing Bézier Guide Ring -->
            <svg class="absolute inset-0 w-full h-full text-indigo-500/30 animate-[spin_20s_linear_infinite]" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="44" fill="none" stroke="currentColor" stroke-width="1" stroke-dasharray="4 4"/>
            </svg>

            <!-- Vector Bézier Handles & Floating Control Nodes -->
            <svg class="absolute inset-0 w-full h-full text-amber-400" viewBox="0 0 100 100" fill="none">
                <!-- Bézier curve lines -->
                <path d="M 20 50 C 20 20, 80 20, 80 50" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3" class="opacity-60"/>
                
                <!-- Floating Anchor Control Nodes with Pulse -->
                <circle cx="20" cy="50" r="3.5" class="fill-indigo-500 stroke-white stroke-1 animate-ping"/>
                <circle cx="20" cy="50" r="3" class="fill-indigo-400 stroke-slate-950 stroke-1"/>
                
                <circle cx="50" cy="25" r="3.5" class="fill-amber-400 stroke-white stroke-1 animate-pulse"/>
                <circle cx="50" cy="25" r="3" class="fill-amber-300 stroke-slate-950 stroke-1"/>

                <circle cx="80" cy="50" r="3.5" class="fill-indigo-500 stroke-white stroke-1 animate-ping"/>
                <circle cx="80" cy="50" r="3" class="fill-indigo-400 stroke-slate-950 stroke-1"/>

                <!-- Tangent handle bars -->
                <line x1="38" y1="25" x2="62" y2="25" stroke="currentColor" stroke-width="1" class="opacity-70"/>
                <rect x="36" y="23" width="4" height="4" class="fill-amber-400 stroke-slate-950 stroke-1"/>
                <rect x="60" y="23" width="4" height="4" class="fill-amber-400 stroke-slate-950 stroke-1"/>
            </svg>

            <!-- Interlocking Glowing Golden Padlock -->
            <div class="relative w-20 h-20 rounded-2xl bg-gradient-to-br from-amber-400 via-amber-500 to-yellow-600 p-0.5 shadow-lg shadow-amber-500/30 flex items-center justify-center animate-pulse">
                <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center text-amber-400">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Typography -->
        <div class="space-y-3">
            <span class="px-3.5 py-1 rounded-full text-xs font-mono font-bold uppercase tracking-wider bg-amber-500/10 text-amber-300 border border-amber-500/20 inline-block">
                Access Restricted Canvas
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                403 — Design Workspace Locked
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-md mx-auto">
                This canvas requires Verified Contributor credentials. Submit your KYC portfolio to unlock creator studio tools and asset publishing.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            <a href="{{ route('contributor.apply') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 text-xs font-black shadow-lg shadow-amber-500/25 transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Apply for Contributor</span>
            </a>

            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-bold transition border border-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Return to Dashboard</span>
            </a>
        </div>

        <!-- Footer watermark -->
        <p class="text-[11px] font-mono text-slate-600">
            Noksha Creative Security Protocol • 403 Forbidden
        </p>

    </div>
</body>
</html>
