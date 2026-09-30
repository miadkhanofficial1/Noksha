<div class="relative overflow-hidden border-b border-slate-800/80 bg-slate-950 py-12 px-4 sm:px-6 lg:px-8">
    <!-- Ambient Background Glow -->
    <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.16) 0%, rgba(168, 85, 247, 0.08) 40%, transparent 70%);"></div>

    <div class="relative max-w-4xl mx-auto text-center space-y-4">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 shadow-sm">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span>Legal, Trust & Platform Compliance</span>
        </div>

        <!-- Heading -->
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight">
            {{ $title ?? 'Legal Terms & Policies' }}
        </h1>

        <!-- Subtitle -->
        <p class="text-sm sm:text-base text-slate-400 max-w-2xl mx-auto leading-relaxed">
            {{ $subtitle ?? 'Clear, transparent rules governing asset ownership, commercial rights, dispute handling, and privacy on Noksha.' }}
        </p>

        <div class="flex items-center justify-center gap-3 text-xs font-mono text-slate-500 pt-1">
            <span>Last Updated: October 2026</span>
            <span>•</span>
            <span>Applicable to all Buyers, Creators & Organizers</span>
        </div>

        <!-- Policy Navigation Tabs -->
        <div class="pt-6">
            <nav class="inline-flex p-1.5 bg-slate-900/90 border border-slate-800 rounded-2xl space-x-1.5 overflow-x-auto max-w-full shadow-inner" aria-label="Legal Tabs">
                <!-- Tab 1: Licenses -->
                <a href="{{ route('legal.licenses') }}" class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-medium transition-all flex items-center gap-2 whitespace-nowrap {{ ($activePolicy ?? '') === 'licenses' ? 'bg-indigo-600 text-white shadow-md font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <svg class="w-4 h-4 shrink-0 {{ ($activePolicy ?? '') === 'licenses' ? 'text-white' : 'text-indigo-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Licensing Terms</span>
                </a>

                <!-- Tab 2: Terms of Service -->
                <a href="{{ route('legal.terms') }}" class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-medium transition-all flex items-center gap-2 whitespace-nowrap {{ ($activePolicy ?? '') === 'terms' ? 'bg-indigo-600 text-white shadow-md font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <svg class="w-4 h-4 shrink-0 {{ ($activePolicy ?? '') === 'terms' ? 'text-white' : 'text-purple-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    <span>Terms of Service</span>
                </a>

                <!-- Tab 3: Privacy Policy -->
                <a href="{{ route('legal.privacy') }}" class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-medium transition-all flex items-center gap-2 whitespace-nowrap {{ ($activePolicy ?? '') === 'privacy' ? 'bg-indigo-600 text-white shadow-md font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <svg class="w-4 h-4 shrink-0 {{ ($activePolicy ?? '') === 'privacy' ? 'text-white' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Privacy Policy</span>
                </a>

                <!-- Tab 4: Refund & Escrow -->
                <a href="{{ route('legal.refunds') }}" class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-medium transition-all flex items-center gap-2 whitespace-nowrap {{ ($activePolicy ?? '') === 'refunds' ? 'bg-indigo-600 text-white shadow-md font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <svg class="w-4 h-4 shrink-0 {{ ($activePolicy ?? '') === 'refunds' ? 'text-white' : 'text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/></svg>
                    <span>Refund & Payouts</span>
                </a>
            </nav>
        </div>
    </div>
</div>
