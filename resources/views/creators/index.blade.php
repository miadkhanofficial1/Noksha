@extends('layouts.app')

@section('title', 'Top Verified Creators & Visual Designers - Noksha')

@section('content')
<div class="min-h-screen bg-[#ffffff] text-slate-800 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-10">

        <!-- HERO HEADER BANNER -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-purple-50/60 via-white to-slate-50 border border-slate-200 p-8 sm:p-12 shadow-sm text-center space-y-4">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-purple-50 border border-purple-200 text-[#20165c] text-xs font-mono font-bold uppercase tracking-wider">
                <svg class="w-4 h-4 text-[#20165c]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>Curated Talent Network</span>
            </div>

            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-[#121826] tracking-tight leading-tight">
                Discover Verified Bangladeshi Creators & Visual Designers
            </h1>

            <p class="text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Connect with identity designers, 3D artists, UI/UX craftsmen, and vector specialists creating authentic high-caliber digital assets on Noksha.
            </p>

            <!-- Search Input Box -->
            <form action="{{ route('creators.index') }}" method="GET" class="max-w-2xl mx-auto pt-4">
                @if(request('specialty') && request('specialty') !== 'all')
                    <input type="hidden" name="specialty" value="{{ request('specialty') }}">
                @endif
                <div class="relative flex items-center">
                    <input type="text" 
                           name="search" 
                           value="{{ $search ?? '' }}" 
                           placeholder="Search creators by name, username, or specialty..." 
                           class="w-full pl-12 pr-28 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm text-[#121826] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#20165c] focus:border-transparent transition shadow-sm">
                    
                    <div class="absolute left-4 text-slate-400 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <button type="submit" class="absolute right-2 px-5 py-2 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white text-xs font-bold transition shadow-sm">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- SPECIALTY FILTER PILLS -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
            <span class="text-xs font-mono font-bold uppercase tracking-wider text-slate-500 shrink-0 mr-2">Specialty:</span>
            @foreach($specialties as $key => $label)
                <a href="{{ route('creators.index', array_merge(request()->except('specialty', 'page'), ['specialty' => $key])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition border {{ $activeSpecialty === $key ? 'bg-[#20165c] text-white border-[#20165c] shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:border-[#20165c] hover:text-[#20165c]' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- BEHANCE-INSPIRED "FIND CREATIVES" CARDS GRID -->
        @if($creators->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($creators as $creator)
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                        
                        <div>
                            <!-- Card Header: Avatar, Name, Verified Badge, Headline -->
                            <div class="flex items-start gap-3.5">
                                <div class="relative shrink-0">
                                    <a href="{{ route('user.profile', $creator->username ?: $creator->id) }}">
                                        <img src="{{ $creator->avatar_url }}" 
                                             alt="{{ $creator->name }}" 
                                             class="w-14 h-14 rounded-full object-cover border-2 border-white shadow-sm transition-opacity">
                                    </a>
                                    <!-- Online Green Indicator Dot -->
                                    <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 rounded-full border-2 border-white" title="Active on Noksha"></span>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('user.profile', $creator->username ?: $creator->id) }}" class="font-bold text-[#121826] text-base group-hover:text-[#20165c] transition truncate block">
                                            {{ $creator->name }}
                                        </a>
                                        <i class="bi bi-patch-check-fill text-emerald-500 text-sm shrink-0" title="Verified Noksha Contributor"></i>
                                    </div>
                                    
                                    <p class="text-xs text-slate-500 font-medium truncate mb-1">
                                        {{ $creator->headline ?: ($creator->skills ? (is_array($creator->skills) ? implode(', ', array_slice($creator->skills, 0, 2)) : $creator->skills) : 'Visual & Brand Designer') }}
                                    </p>

                                    <!-- Rating / Appreciations -->
                                    <div class="flex items-center gap-1 text-xs text-amber-500 font-semibold">
                                        <i class="bi bi-star-fill text-[11px]"></i>
                                        <span>5.0</span>
                                        <span class="text-slate-400 font-normal">({{ number_format($creator->templates_count ?? 0) }} assets)</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bio Snippet -->
                            <p class="text-xs text-slate-600 leading-relaxed mt-3 line-clamp-2 min-h-[32px]">
                                {{ $creator->bio ?: ($creator->contributor_bio ?: 'Visual creator publishing authentic design templates and branding assets.') }}
                            </p>

                            <!-- 3-Column Mini-Preview Gallery Strip (Recent Designs) -->
                            <div class="grid grid-cols-3 gap-1.5 h-20 rounded-xl overflow-hidden bg-slate-100 my-4 border border-slate-100">
                                @php
                                    $sampleTemplates = $creator->templates ? $creator->templates->take(3) : collect();
                                @endphp

                                @if($sampleTemplates->count() > 0)
                                    @foreach($sampleTemplates as $tmpl)
                                        <div class="relative w-full h-full bg-slate-200 overflow-hidden">
                                            @if($tmpl->preview_image)
                                                <a href="{{ route('resource.show', $tmpl->slug ?? $tmpl->id) }}" class="block w-full h-full" title="{{ $tmpl->title }}">
                                                    <img src="{{ $tmpl->thumbnail_url }}" class="w-full h-full object-cover transition-opacity duration-200 hover:opacity-90" alt="{{ $tmpl->title }}">
                                                </a>
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-[#20165c]/10 to-purple-200 flex items-center justify-center text-[#20165c]">
                                                    <i class="bi bi-image text-sm opacity-60"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    {{-- Fill remaining slots up to 3 if creator has fewer than 3 --}}
                                    @for($i = $sampleTemplates->count(); $i < 3; $i++)
                                        <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-300">
                                            <i class="bi bi-grid-3x3 text-xs opacity-50"></i>
                                        </div>
                                    @endfor
                                @else
                                    {{-- Fallback default showcase previews --}}
                                    <div class="w-full h-full bg-gradient-to-br from-[#20165c]/15 to-[#20165c]/5 flex items-center justify-center text-[#20165c]">
                                        <i class="bi bi-palette text-sm opacity-60"></i>
                                    </div>
                                    <div class="w-full h-full bg-gradient-to-br from-[#f47402]/15 to-[#f47402]/5 flex items-center justify-center text-[#f47402]">
                                        <i class="bi bi-vector-pen text-sm opacity-60"></i>
                                    </div>
                                    <div class="w-full h-full bg-gradient-to-br from-indigo-100 to-purple-50 flex items-center justify-center text-indigo-700">
                                        <i class="bi bi-layers text-sm opacity-60"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer: Starting Price & Action Buttons -->
                        <div class="pt-3 border-t border-slate-100 space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-purple-50 text-[#20165c] border border-purple-200">
                                    Custom gigs from ৳600
                                </span>
                                <span class="text-slate-400 font-mono text-[11px]">
                                    {{ $creator->username ? '@' . $creator->username : 'Creator' }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('inbox.index', ['recipient' => $creator->id]) }}" 
                                   class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl bg-[#20165c] hover:bg-[#170f45] text-white text-xs font-medium transition-all duration-200 shadow-sm">
                                    <i class="bi bi-chat-dots-fill small"></i>
                                    <span>Hire / Message</span>
                                </a>

                                <a href="{{ route('user.profile', $creator->username ?: $creator->id) }}" 
                                   class="p-2.5 rounded-xl border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 transition-all duration-200 shadow-sm"
                                   title="View Full Portfolio">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- PAGINATION -->
            <div class="pt-6">
                {{ $creators->links() }}
            </div>
        @else
            <!-- ZERO STATE -->
            <div class="p-12 rounded-3xl bg-white border border-slate-200 text-center max-w-lg mx-auto space-y-4 shadow-sm">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-purple-50 text-[#20165c] border border-purple-200 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#121826]">No Creators Match Your Filter</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    We couldn't find any verified contributors matching your current query or specialty filter. Try clearing the search query or browse all creators.
                </p>
                <div class="pt-2">
                    <a href="{{ route('creators.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white text-xs font-bold transition shadow-sm">
                        <span>Reset All Filters</span>
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
