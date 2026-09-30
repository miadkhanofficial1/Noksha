@extends('layouts.app')

@section('title', 'Top Verified Creators & Visual Designers - Noksha')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-100 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-10">

        <!-- HERO HEADER BANNER -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-950/70 via-slate-900/90 to-purple-950/60 border border-slate-800 p-8 sm:p-12 shadow-2xl backdrop-blur-md text-center space-y-4">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-mono font-bold uppercase tracking-wider">
                <svg class="w-4 h-4 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>Curated Talent Network</span>
            </div>

            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
                Discover Verified Bangladeshi Creators & Visual Designers
            </h1>

            <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto leading-relaxed">
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
                           class="w-full pl-12 pr-28 py-3.5 bg-slate-950/90 border border-slate-700/80 rounded-2xl text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-inner">
                    
                    <div class="absolute left-4 text-slate-500 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <button type="submit" class="absolute right-2 px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-md shadow-indigo-600/30">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- SPECIALTY FILTER PILLS -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
            <span class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 shrink-0 mr-2">Specialty:</span>
            @foreach($specialties as $key => $label)
                <a href="{{ route('creators.index', array_merge(request()->except('specialty', 'page'), ['specialty' => $key])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition border {{ $activeSpecialty === $key ? 'bg-indigo-600 text-white border-indigo-500 shadow-md shadow-indigo-600/20' : 'bg-slate-900/80 text-slate-300 border-slate-800 hover:border-slate-700 hover:text-white' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- CREATOR CARDS GRID -->
        @if($creators->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($creators as $creator)
                    <div class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-6 shadow-lg backdrop-blur-sm flex flex-col justify-between hover:border-indigo-500/40 hover:-translate-y-1 transition duration-200 group">
                        
                        <div class="space-y-4">
                            <!-- Avatar & Verified Badge -->
                            <div class="relative w-20 h-20 mx-auto">
                                <img src="{{ $creator->avatar_url }}" 
                                     alt="{{ $creator->name }}" 
                                     class="w-20 h-20 rounded-2xl object-cover border-2 border-slate-700 group-hover:border-indigo-500/60 transition shadow-md">
                                <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-emerald-500 text-slate-950 flex items-center justify-center border-2 border-slate-900 shadow" title="Verified Noksha Contributor">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </div>
                            </div>

                            <!-- Name, Handle & Headline -->
                            <div class="text-center space-y-1">
                                <h3 class="font-bold text-white text-base group-hover:text-indigo-400 transition truncate">
                                    {{ $creator->name }}
                                </h3>
                                
                                <p class="text-xs font-mono text-slate-400 truncate">
                                    {{ $creator->username ? '@' . $creator->username : 'Verified Creator' }}
                                </p>

                                @if($creator->headline)
                                    <p class="text-xs text-indigo-300/90 font-medium truncate pt-0.5">
                                        {{ $creator->headline }}
                                    </p>
                                @endif
                            </div>

                            <!-- Bio -->
                            <p class="text-xs text-slate-400 leading-relaxed text-center line-clamp-2 min-h-[32px]">
                                {{ $creator->bio ?: ($creator->contributor_bio ?: 'Visual creator publishing authentic design templates and branding assets.') }}
                            </p>

                            <!-- Specialty Skills Badges -->
                            <div class="flex flex-wrap items-center justify-center gap-1.5 pt-1">
                                @if(is_array($creator->skills) && count($creator->skills) > 0)
                                    @foreach(array_slice($creator->skills, 0, 3) as $skill)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-mono bg-slate-800 text-slate-300 border border-slate-700/80">
                                            {{ $skill }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-mono bg-indigo-950/60 text-indigo-300 border border-indigo-500/20">
                                        Visual Design
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-mono bg-purple-950/60 text-purple-300 border border-purple-500/20">
                                        Templates
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="pt-5 mt-5 border-t border-slate-800/80 space-y-3">
                            <div class="flex items-center justify-between text-xs text-slate-400">
                                <span class="font-mono">Approved Assets</span>
                                <span class="font-mono font-bold text-white">{{ number_format($creator->templates_count ?? 0) }} resources</span>
                            </div>

                            <a href="{{ route('user.profile', $creator->username ?: $creator->id) }}" 
                               class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-xl bg-slate-800 hover:bg-indigo-600 text-slate-200 hover:text-white text-xs font-bold transition shadow-sm">
                                <span>View Portfolio</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
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
            <div class="p-12 rounded-3xl bg-slate-900/60 border border-slate-800/80 text-center max-w-lg mx-auto space-y-4">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-indigo-600/10 text-indigo-400 border border-indigo-500/20 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-white">No Creators Match Your Filter</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    We couldn't find any verified contributors matching your current query or specialty filter. Try clearing the search query or browse all creators.
                </p>
                <div class="pt-2">
                    <a href="{{ route('creators.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-lg shadow-indigo-600/30">
                        <span>Reset All Filters</span>
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
