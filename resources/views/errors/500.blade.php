<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Canvas Engine Interrupted - Noksha</title>
    
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
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-rose-500/15 rounded-full blur-[128px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-purple-600/15 rounded-full blur-[128px] pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[32rem] h-[32rem] bg-rose-600/10 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="relative z-10 max-w-xl w-full mx-4 text-center space-y-8 p-8 sm:p-10 rounded-3xl bg-slate-900/60 border border-slate-800/90 shadow-2xl backdrop-blur-xl">
        
        <!-- VISUAL MOTIF: DISCONNECTED VECTOR NODES & BROKEN ANCHOR CURVES WITH GLOWING RED/VIOLET PULSING GLITCH -->
        <div class="relative w-48 h-48 mx-auto flex items-center justify-center">
            
            <!-- Glitch Aura Ring -->
            <div class="absolute inset-0 rounded-full border border-rose-500/30 animate-ping opacity-25"></div>
            <div class="absolute inset-4 rounded-full border border-purple-500/40 animate-pulse"></div>

            <!-- Broken Vector Nodes & Interrupted Curves SVG -->
            <svg class="absolute inset-0 w-full h-full" viewBox="0 0 100 100" fill="none">
                <!-- Glitch dashed vectors -->
                <path d="M 18 35 L 42 45" stroke="#f43f5e" stroke-width="2" stroke-dasharray="2 3" class="animate-pulse"/>
                <path d="M 45 48 L 55 42" stroke="#a855f7" stroke-width="1.5" stroke-dasharray="1 2"/>
                <path d="M 58 40 L 82 62" stroke="#f43f5e" stroke-width="2" stroke-dasharray="4 2"/>
                <path d="M 28 75 Q 48 55 52 70" stroke="#ec4899" stroke-width="1.5" stroke-dasharray="2 4"/>

                <!-- Disconnected Node Points -->
                <circle cx="18" cy="35" r="3.5" class="fill-rose-500 stroke-white stroke-1 animate-pulse"/>
                <circle cx="42" cy="45" r="4" class="fill-slate-950 stroke-rose-400 stroke-2"/>
                <circle cx="58" cy="40" r="4" class="fill-slate-950 stroke-purple-400 stroke-2"/>
                <circle cx="82" cy="62" r="3.5" class="fill-purple-500 stroke-white stroke-1 animate-ping"/>

                <!-- Anchor Handle Tangent Lines Disconnected -->
                <line x1="38" y1="38" x2="46" y2="52" stroke="#94a3b8" stroke-width="1" stroke-dasharray="2 2"/>
                <rect x="36" y="36" width="3.5" height="3.5" class="fill-rose-400 stroke-slate-950 stroke-1"/>
                
                <line x1="54" y1="34" x2="62" y2="46" stroke="#94a3b8" stroke-width="1" stroke-dasharray="2 2"/>
                <rect x="60" y="44" width="3.5" height="3.5" class="fill-purple-400 stroke-slate-950 stroke-1"/>
            </svg>

            <!-- Center Glitch Warning Emblem -->
            <div class="relative w-20 h-20 rounded-2xl bg-gradient-to-br from-rose-500 via-pink-600 to-purple-700 p-0.5 shadow-xl shadow-rose-500/30 flex items-center justify-center animate-pulse">
                <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center text-rose-400">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>

        </div>

        <!-- Typography -->
        <div class="space-y-3">
            <span class="px-3.5 py-1 rounded-full text-xs font-mono font-bold uppercase tracking-wider bg-rose-500/10 text-rose-300 border border-rose-500/20 inline-block">
                Rendering Engine Exception
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                500 — Canvas Engine Interrupted
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-md mx-auto">
                Our asset synthesis engine encountered an unexpected error while processing this canvas. Our engineering team has logged the diagnostic telemetry.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            <button type="button" 
                    onclick="window.location.reload()" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-rose-600 to-purple-600 hover:from-rose-500 hover:to-purple-500 text-white text-xs font-black shadow-lg shadow-rose-600/25 transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reload Workspace</span>
            </button>

            <a href="{{ route('contact') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-bold transition border border-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>Contact Support</span>
            </a>
        </div>

        <!-- Footer watermark -->
        <p class="text-[11px] font-mono text-slate-600">
            Noksha Core Engine Telemetry • 500 Internal Error
        </p>

    </div>
</body>
</html>
