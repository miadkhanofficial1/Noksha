@extends('layouts.app')

@section('title', 'Licensing Terms & Usage Rights - Noksha')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-200">
    <!-- Header Hero Banner -->
    @include('legal.header', [
        'activePolicy' => 'licenses',
        'title' => 'Licensing Terms & Commercial Rights',
        'subtitle' => 'Understand how you can use graphic assets, templates, and designs purchased on Noksha for personal, client, and commercial projects.'
    ])

    <!-- Main Container -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

        <!-- Quick Summary Callout -->
        <div class="p-6 rounded-2xl bg-indigo-950/40 border border-indigo-500/30 backdrop-blur-sm shadow-xl flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="space-y-1 text-sm leading-relaxed">
                <h3 class="font-bold text-white text-base">The Core Principle</h3>
                <p class="text-slate-300">
                    When you purchase a design template or asset on Noksha, you are granted a non-exclusive, worldwide, ongoing license to use the item in your end creative deliverables. You do <strong>not</strong> purchase the original underlying copyright; ownership remains with the verified creator. You may never resell, re-license, or redistribute raw source files.
                </p>
            </div>
        </div>

        <!-- Section 1: The Three License Tiers -->
        <div class="space-y-6">
            <div class="border-b border-slate-800 pb-3">
                <h2 class="text-2xl font-black text-white tracking-tight flex items-center gap-2">
                    <span class="text-indigo-400">01.</span> License Tiers Breakdown
                </h2>
                <p class="text-xs font-mono text-slate-400 mt-1 uppercase tracking-wider">Choose the license level appropriate for your use case</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Tier 1: Personal -->
                <div class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-6 shadow-lg backdrop-blur-sm flex flex-col justify-between hover:border-slate-700 transition">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-800 text-slate-300 border border-slate-700">Non-Commercial</span>
                            <span class="text-xs font-mono text-slate-500">Tier 1</span>
                        </div>
                        <h3 class="text-lg font-bold text-white">Personal License</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Suitable for students, non-profit experiments, personal artistic portfolios, and individual projects with zero commercial monetization.
                        </p>
                        <ul class="text-xs text-slate-300 space-y-2 pt-2 border-t border-slate-800/80">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>1 Single personal project</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Personal social media posts</span>
                            </li>
                            <li class="flex items-center gap-2 text-rose-400">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>No client work or billing</span>
                            </li>
                            <li class="flex items-center gap-2 text-rose-400">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>Zero sales or impressions cap</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tier 2: Commercial (Recommended) -->
                <div class="bg-indigo-950/30 border-2 border-indigo-500/60 rounded-2xl p-6 shadow-xl backdrop-blur-sm flex flex-col justify-between relative">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-indigo-600 text-white shadow-md">
                        Standard Default
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/40">Standard Commercial</span>
                            <span class="text-xs font-mono text-indigo-400">Tier 2</span>
                        </div>
                        <h3 class="text-lg font-bold text-white">Commercial License</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Included with all standard template sales on Noksha. Intended for professional agencies, freelance client orders, and corporate branding.
                        </p>
                        <ul class="text-xs text-slate-300 space-y-2 pt-2 border-t border-indigo-500/20">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>1 Single commercial client deliverable</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Up to 5,000 digital sales / print copies</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Monetized YouTube / social ads</span>
                            </li>
                            <li class="flex items-center gap-2 text-rose-400">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>No print-on-demand resale</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tier 3: Extended Commercial -->
                <div class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-6 shadow-lg backdrop-blur-sm flex flex-col justify-between hover:border-slate-700 transition">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-purple-500/20 text-purple-300 border border-purple-500/40">Enterprise</span>
                            <span class="text-xs font-mono text-purple-400">Tier 3</span>
                        </div>
                        <h3 class="text-lg font-bold text-white">Extended Commercial</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            For mass production, SaaS software themes, broadcast television, print-on-demand merchandising, and unlimited reproduction runs.
                        </p>
                        <ul class="text-xs text-slate-300 space-y-2 pt-2 border-t border-slate-800/80">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Unlimited end client deliverables</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Unlimited physical copies & merch</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>App UI & software embedded assets</span>
                            </li>
                            <li class="flex items-center gap-2 text-emerald-400">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Worldwide broadcast rights</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Allowed vs Strictly Prohibited Matrix -->
        <div class="space-y-6">
            <div class="border-b border-slate-800 pb-3">
                <h2 class="text-2xl font-black text-white tracking-tight flex items-center gap-2">
                    <span class="text-indigo-400">02.</span> Permitted Uses vs. Prohibited Actions
                </h2>
                <p class="text-xs font-mono text-slate-400 mt-1 uppercase tracking-wider">A transparent guide to keeping your creative workflow compliant</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Allowed -->
                <div class="p-6 rounded-2xl bg-emerald-950/20 border border-emerald-500/30 backdrop-blur-sm space-y-4">
                    <div class="flex items-center gap-2.5 text-emerald-400 font-bold text-base border-b border-emerald-500/20 pb-3">
                        <div class="w-7 h-7 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span>What You ARE Allowed To Do</span>
                    </div>

                    <ul class="text-xs sm:text-sm text-slate-300 space-y-3">
                        <li class="flex items-start gap-2.5">
                            <span class="text-emerald-400 font-bold mt-0.5">✓</span>
                            <span>Incorporate designs into completed client projects (e.g. delivering an exported PDF or final branding guidelines to a business client).</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="text-emerald-400 font-bold mt-0.5">✓</span>
                            <span>Modify, crop, tint, rearrange, or synthesize elements within our in-browser AI Canvas Editor or desktop design software (Photoshop, Illustrator, Figma).</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="text-emerald-400 font-bold mt-0.5">✓</span>
                            <span>Produce marketing advertisements, billboards, flyers, and social media promotions up to the copies limit specified in your license tier.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="text-emerald-400 font-bold mt-0.5">✓</span>
                            <span>Display rendered screenshots or mockups in your professional design portfolio and agency showcases.</span>
                        </li>
                    </ul>
                </div>

                <!-- Prohibited -->
                <div class="p-6 rounded-2xl bg-rose-950/20 border border-rose-500/30 backdrop-blur-sm space-y-4">
                    <div class="flex items-center gap-2.5 text-rose-400 font-bold text-base border-b border-rose-500/20 pb-3">
                        <div class="w-7 h-7 rounded-lg bg-rose-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <span>What You Are NEVER Allowed To Do</span>
                    </div>

                    <ul class="text-xs sm:text-sm text-slate-300 space-y-3">
                        <li class="flex items-start gap-2.5">
                            <span class="text-rose-400 font-bold mt-0.5">✗</span>
                            <span>Re-sell, sub-license, donate, or distribute the raw source files (`.psd`, `.ai`, `.eps`, `.fig`) on any marketplace, cloud folder, or website.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="text-rose-400 font-bold mt-0.5">✗</span>
                            <span>Extract graphic elements, icons, or illustrations to package into competing design asset packs, UI kits, or website builder templates.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="text-rose-400 font-bold mt-0.5">✗</span>
                            <span>Claim original trademark, copyright ownership, or register the raw downloaded design as a proprietary trademark.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="text-rose-400 font-bold mt-0.5">✗</span>
                            <span>Upload raw assets to unauthorized automated third-party AI image generators, scraping scrapers, or warez portals.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Section 3: Client Transfer & Handover Rules -->
        <div class="space-y-4 bg-slate-900/70 border border-slate-800/90 rounded-2xl p-6 sm:p-8 backdrop-blur-sm shadow-xl">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <span class="text-indigo-400">03.</span> Client Handover Protocol
            </h2>
            <p class="text-sm text-slate-300 leading-relaxed">
                If you are a freelance designer or agency using a Noksha template for a paying client:
            </p>
            <div class="space-y-3 text-xs sm:text-sm text-slate-400">
                <p>
                    • You may transfer the <em>customized end deliverable</em> (flattened images, vector silhouettes, printed merchandise, or rendered web assets) to your client.
                </p>
                <p>
                    • Your client may only use the deliverable in accordance with the original license scope. They do <strong>not</strong> gain the right to extract and reuse the raw components for other unrelated projects.
                </p>
                <p>
                    • If your client requires the raw editable source files to modify themselves in the future, the client must purchase their own Commercial License on Noksha.
                </p>
            </div>
        </div>

        <!-- Section 4: Font & Stock Asset Notice -->
        <div class="space-y-4 bg-slate-900/70 border border-slate-800/90 rounded-2xl p-6 sm:p-8 backdrop-blur-sm shadow-xl">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <span class="text-indigo-400">04.</span> Fonts, Photos & Embedded Assets
            </h2>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                Some templates contain mock images or typography for demonstration purposes. Contributors are required to use open-source Google Fonts or licensed stock photos. Noksha licenses cover the <em>design structure and arrangement</em>. Users are responsible for confirming third-party font license conditions before commercial publishing.
            </p>
        </div>

        <!-- Help CTA -->
        <div class="text-center p-8 rounded-2xl border border-slate-800 bg-gradient-to-b from-slate-900/80 to-slate-950 space-y-3">
            <h3 class="text-lg font-bold text-white">Have questions about your specific commercial project?</h3>
            <p class="text-xs text-slate-400 max-w-md mx-auto">
                Our compliance team is ready to evaluate your enterprise use case or discuss bespoke extended licensing agreements.
            </p>
            <div class="pt-2">
                <a href="{{ route('contact.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-lg shadow-indigo-600/30">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span>Contact Legal Support</span>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
