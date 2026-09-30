<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Vector Asset Missing from Canvas - Noksha</title>
    
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
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-500/15 rounded-full blur-[128px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-purple-500/15 rounded-full blur-[128px] pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[32rem] h-[32rem] bg-indigo-600/10 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="relative z-10 max-w-xl w-full mx-4 text-center space-y-8 p-8 sm:p-10 rounded-3xl bg-slate-900/60 border border-slate-800/90 shadow-2xl backdrop-blur-xl">
        
        <!-- VISUAL MOTIF: FLOATING 3D CANVAS ARTBOARD WITH DASHED BOUNDING BOX & PEN-TOOL CURSOR HOVERING OVER BROKEN PATH -->
        <div class="relative w-48 h-48 mx-auto flex items-center justify-center">
            
            <!-- Isometric / Floating Artboard Frame with Dashed Bounding Box -->
            <div class="absolute inset-4 rounded-2xl border-2 border-dashed border-indigo-500/50 bg-slate-950/70 shadow-2xl shadow-indigo-500/20 transform rotate-[-4deg] flex items-center justify-center overflow-hidden">
                <!-- Drafting crosshairs -->
                <div class="absolute inset-0 flex items-center justify-center opacity-20 pointer-events-none">
                    <div class="w-full h-px bg-indigo-400"></div>
                    <div class="h-full w-px bg-indigo-400 absolute"></div>
                </div>

                <!-- Transform Handles on Corners of the Bounding Box -->
                <div class="absolute top-1 left-1 w-2.5 h-2.5 bg-indigo-400 border border-slate-950 rounded-sm"></div>
                <div class="absolute top-1 right-1 w-2.5 h-2.5 bg-indigo-400 border border-slate-950 rounded-sm"></div>
                <div class="absolute bottom-1 left-1 w-2.5 h-2.5 bg-indigo-400 border border-slate-950 rounded-sm"></div>
                <div class="absolute bottom-1 right-1 w-2.5 h-2.5 bg-indigo-400 border border-slate-950 rounded-sm"></div>

                <!-- Big 404 watermark inside artboard -->
                <span class="text-4xl font-black font-mono tracking-tighter text-indigo-500/25 select-none">404</span>
            </div>

            <!-- Broken Vector Curve & Anchor Points SVG -->
            <svg class="absolute inset-0 w-full h-full text-indigo-400" viewBox="0 0 100 100" fill="none">
                <!-- Left path segment -->
                <path d="M 22 65 Q 35 30 45 48" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                
                <!-- Broken gap gap indicator -->
                <circle cx="45" cy="48" r="3" class="fill-rose-500 stroke-white stroke-1 animate-ping"/>
                <circle cx="45" cy="48" r="2.5" class="fill-rose-500 stroke-slate-950 stroke-1"/>

                <!-- Right disjointed path segment -->
                <path d="M 58 55 Q 68 75 80 40" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-dasharray="4 4" class="opacity-50"/>
                <circle cx="58" cy="55" r="2.5" class="fill-amber-400 stroke-slate-950 stroke-1"/>
                <circle cx="80" cy="40" r="2.5" class="fill-indigo-400 stroke-slate-950 stroke-1"/>
            </svg>

            <!-- Floating Vector Pen Tool Cursor hovering over broken path -->
            <div class="absolute top-10 right-8 animate-bounce z-20">
                <svg class="w-10 h-10 text-white drop-shadow-[0_4px_12px_rgba(79,70,229,0.6)]" viewBox="0 0 24 24" fill="currentColor">
                    <!-- Vector Pen Nib -->
                    <path d="M7.707 3.293a1 1 0 00-1.414 0l-3 3a1 1 0 000 1.414l6.586 6.586a1 1 0 00.707.293l4.586 1.146 1.146 4.586a1 1 0 001.213.738 1 1 0 00.738-1.213l-1.146-4.586 4.586-1.146a1 1 0 00.707-.293l6.586-6.586a1 1 0 000-1.414l-3-3a1 1 0 00-1.414 0L12 11.586 7.707 3.293z"/>
                </svg>
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-400 absolute -top-1 -right-1 animate-ping"></span>
            </div>

        </div>

        <!-- Typography -->
        <div class="space-y-3">
            <span class="px-3.5 py-1 rounded-full text-xs font-mono font-bold uppercase tracking-wider bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 inline-block">
                Missing Artboard Element
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                404 — Vector Asset Missing from Canvas
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-md mx-auto">
                The graphic resource, template, or page you are looking for has been moved, renamed, or does not exist on the current canvas.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            <a href="{{ route('templates.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-xs font-black shadow-lg shadow-indigo-600/30 transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Explore Marketplace</span>
            </a>

            <a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-bold transition border border-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Return Home</span>
            </a>
        </div>

        <!-- Footer watermark -->
        <p class="text-[11px] font-mono text-slate-600">
            Noksha Digital Creative Canvas • 404 Not Found
        </p>

    </div>
</body>
</html>
