@extends('layouts.app')

@section('title', 'Help Center & Frequently Asked Questions - Noksha')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-100 py-10 px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'all', searchQuery: '{{ addslashes($search ?? '') }}' }">
    <div class="max-w-5xl mx-auto space-y-10">

        <!-- HERO HEADER BANNER -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-950/70 via-slate-900/90 to-purple-950/60 border border-slate-800 p-8 sm:p-12 shadow-2xl backdrop-blur-md text-center space-y-4">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-mono font-bold uppercase tracking-wider">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Knowledge Base & Support Hub</span>
            </div>

            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
                How Can We Help You Today?
            </h1>

            <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Find clear answers regarding template licensing, Contributor KYC validation, manual bKash/Nagad wallet deposits, and contest escrow workflows.
            </p>

            <!-- Search Input -->
            <div class="max-w-2xl mx-auto pt-4">
                <div class="relative flex items-center">
                    <input type="text" 
                           x-model="searchQuery" 
                           placeholder="Type keywords to filter questions (e.g. bKash, license, escrow, withdrawal)..." 
                           class="w-full pl-12 pr-4 py-3.5 bg-slate-950/90 border border-slate-700/80 rounded-2xl text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-inner">
                    
                    <div class="absolute left-4 text-slate-500 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <button type="button" 
                            x-show="searchQuery" 
                            @click="searchQuery = ''" 
                            class="absolute right-4 text-xs font-mono text-slate-400 hover:text-white transition">
                        Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- CATEGORY NAVIGATION TABS -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none justify-start sm:justify-center">
            <button type="button" 
                    @click="activeTab = 'all'" 
                    :class="activeTab === 'all' ? 'bg-indigo-600 text-white border-indigo-500 shadow-md shadow-indigo-600/20' : 'bg-slate-900/80 text-slate-300 border-slate-800 hover:border-slate-700 hover:text-white'" 
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition border whitespace-nowrap">
                All Topics
            </button>

            @foreach($faqCategories as $slug => $category)
                <button type="button" 
                        @click="activeTab = '{{ $slug }}'" 
                        :class="activeTab === '{{ $slug }}' ? 'bg-indigo-600 text-white border-indigo-500 shadow-md shadow-indigo-600/20' : 'bg-slate-900/80 text-slate-300 border-slate-800 hover:border-slate-700 hover:text-white'" 
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition border whitespace-nowrap flex items-center gap-1.5">
                    <span>{{ $category['name'] }}</span>
                </button>
            @endforeach
        </div>

        <!-- FAQ CATEGORY SECTIONS -->
        <div class="space-y-8">
            @foreach($faqCategories as $slug => $category)
                <div x-show="activeTab === 'all' || activeTab === '{{ $slug }}'" 
                     class="bg-slate-900/70 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl backdrop-blur-sm space-y-6">
                    
                    <!-- Category Header -->
                    <div class="flex items-start gap-4 pb-4 border-b border-slate-800/80">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 flex items-center justify-center shrink-0">
                            @if($slug === 'buyer')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            @elseif($slug === 'contributor')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            @elseif($slug === 'deposits')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            @endif
                        </div>
                        <div class="space-y-1">
                            <h2 class="text-xl font-bold text-white tracking-tight">{{ $category['name'] }}</h2>
                            <p class="text-xs text-slate-400 leading-relaxed">{{ $category['description'] }}</p>
                        </div>
                    </div>

                    <!-- Accordion List -->
                    <div class="space-y-3">
                        @foreach($category['faqs'] as $faq)
                            <div x-data="{ open: false }" 
                                 x-show="!searchQuery || '{{ strtolower(addslashes($faq['q'] . ' ' . $faq['a'])) }}'.includes(searchQuery.toLowerCase())"
                                 class="rounded-2xl bg-slate-950/80 border border-slate-800/90 overflow-hidden transition">
                                
                                <button type="button" 
                                        @click="open = !open" 
                                        class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 group focus:outline-none">
                                    <span class="text-sm font-bold text-slate-200 group-hover:text-indigo-400 transition">
                                        {{ $faq['q'] }}
                                    </span>
                                    
                                    <span class="w-7 h-7 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center shrink-0 text-slate-400 group-hover:text-white transition"
                                          :class="{ 'rotate-180 text-indigo-400 border-indigo-500/40': open }">
                                        <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </span>
                                </button>

                                <div x-show="open" 
                                     x-collapse
                                     class="px-4 pb-5 sm:px-5 sm:pb-6 text-xs sm:text-sm text-slate-300 leading-relaxed border-t border-slate-800/80 pt-4">
                                    {{ $faq['a'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach
        </div>

        <!-- STILL HAVE QUESTIONS? CALLOUT CARD -->
        <div class="p-8 sm:p-10 rounded-3xl bg-gradient-to-br from-indigo-950/50 via-slate-900 to-purple-950/40 border border-indigo-500/30 backdrop-blur-md shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="space-y-2 text-center sm:text-left">
                <span class="px-3 py-1 rounded-full text-[11px] font-mono font-bold uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 inline-block">
                    Dedicated Support Desk
                </span>
                <h3 class="text-xl sm:text-2xl font-black text-white">Still have questions or need assistance?</h3>
                <p class="text-xs sm:text-sm text-slate-300 max-w-lg leading-relaxed">
                    Our verified trust & safety team is available to assist with bKash TrxID verification, contested deliverables, or custom enterprise licensing inquiries.
                </p>
            </div>

            <a href="{{ route('contact') }}" class="shrink-0 inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-xl shadow-indigo-600/30 transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Contact Support Desk</span>
            </a>
        </div>

    </div>
</div>
@endsection
