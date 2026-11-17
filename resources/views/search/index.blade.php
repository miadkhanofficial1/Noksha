@extends('layouts.app')

@section('title', !empty($q) ? 'Search Results for "' . $q . '" - Noksha' : 'Smart Discovery Search - Noksha')

@section('content')
<div class="min-h-screen bg-[#f8fafc] text-slate-800 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-10">

        <!-- ========================================================= -->
        <!-- 1. TOP HEADER DISCOVERY CARD (MATCHES NOKSHA LIGHT THEME) -->
        <!-- ========================================================= -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-sm">
            <!-- Eyebrow Pill -->
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider text-[#20165c] bg-[#20165c]/10 border border-[#20165c]/20 mb-3 font-mono">
                🔍 UNIFIED DISCOVERY
            </span>

            <!-- Dynamic Heading -->
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#121826] tracking-tight">
                @if(!empty($q))
                    Search Results for <span class="text-[#20165c]">"{{ $q }}"</span>
                @else
                    Search Marketplace
                @endif
            </h1>

            <!-- Subtitle -->
            <p class="text-sm sm:text-base text-slate-600 mt-2 max-w-2xl leading-relaxed">
                Found {{ number_format($totalCount) }} matching results across templates, services, and verified creators.
            </p>

            <!-- Search Form Bar -->
            <form action="{{ route('search') }}" method="GET" class="mt-6 max-w-2xl">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="relative flex items-center">
                    <input type="text" 
                           name="q" 
                           value="{{ $q }}" 
                           placeholder="Search templates, services, creators..." 
                           class="w-full pl-12 pr-28 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-[#121826] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#20165c] focus:border-transparent transition shadow-xs">
                    <div class="absolute left-4 text-slate-400 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <button type="submit" class="absolute right-2 px-5 py-2 rounded-xl text-xs font-bold text-white bg-[#20165c] hover:bg-[#181045] transition-all shadow-sm">
                        Search
                    </button>
                </div>
            </form>

            <!-- Filter Pills Row -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 mt-6 border-t border-slate-100 pt-5">
                <a href="{{ route('search', ['q' => $q, 'tab' => 'all']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition border {{ $tab === 'all' ? 'bg-[#20165c] text-white border-[#20165c] shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-[#20165c] hover:text-[#20165c]' }}">
                    All Results ({{ number_format($totalCount) }})
                </a>
                <a href="{{ route('search', ['q' => $q, 'tab' => 'templates']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition border {{ $tab === 'templates' ? 'bg-[#20165c] text-white border-[#20165c] shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-[#20165c] hover:text-[#20165c]' }}">
                    Templates ({{ number_format($templates->count()) }})
                </a>
                <a href="{{ route('search', ['q' => $q, 'tab' => 'services']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition border {{ $tab === 'services' ? 'bg-[#20165c] text-white border-[#20165c] shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-[#20165c] hover:text-[#20165c]' }}">
                    Services ({{ number_format($services->count()) }})
                </a>
                <a href="{{ route('search', ['q' => $q, 'tab' => 'creators']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition border {{ $tab === 'creators' ? 'bg-[#20165c] text-white border-[#20165c] shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-[#20165c] hover:text-[#20165c]' }}">
                    Creators ({{ number_format($creators->count()) }})
                </a>
            </div>

            <!-- Showing Results & Clear Search -->
            @if(!empty($q))
                <div class="mt-3 flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-50">
                    <span>Showing results for <strong class="text-[#121826]">"{{ $q }}"</strong></span>
                    <a href="{{ route('search') }}" class="text-rose-600 hover:text-rose-700 font-semibold inline-flex items-center gap-1 transition no-underline">
                        <span>✕ Clear search</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- ========================================================= -->
        <!-- 2. SMART SECTIONED RESULTS BODY                           -->
        <!-- ========================================================= -->
        @if($totalCount > 0)

            <!-- 2.1 MATCHING CREATORS SECTION -->
            @if(($tab === 'all' || $tab === 'creators') && $creators->count() > 0)
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-[#20165c]/10 text-[#20165c] flex items-center justify-center font-bold">
                                <i class="bi bi-people-fill text-sm"></i>
                            </div>
                            <h2 class="text-lg font-bold text-[#121826] tracking-tight">Matching Creators</h2>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-200/70 text-slate-700 font-mono">
                                {{ $creators->count() }}
                            </span>
                        </div>
                        @if($tab === 'all' && $creators->count() > 4)
                            <a href="{{ route('search', ['q' => $q, 'tab' => 'creators']) }}" class="text-xs font-semibold text-[#20165c] hover:text-[#181045] inline-flex items-center gap-1 no-underline">
                                <span>View all {{ $creators->count() }} creators</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach(($tab === 'all' ? $creators->take(4) : $creators) as $creator)
                            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                                <div>
                                    <!-- Card Header: Avatar, Name, Verified Badge, Headline -->
                                    <div class="flex items-start gap-3.5">
                                        <div class="relative shrink-0">
                                            <a href="{{ route('user.profile', $creator->username ?: $creator->id) }}" class="block text-decoration-none">
                                                <div class="relative w-14 h-14 rounded-full overflow-hidden flex items-center justify-center bg-[#20165c] text-white font-bold text-base ring-2 ring-white shadow-sm">
                                                    @if($creator->avatar_url)
                                                        <img src="{{ $creator->avatar_url }}" 
                                                             alt="{{ $creator->name }}" 
                                                             class="w-full h-full object-cover object-center block"
                                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        <span class="w-full h-full flex items-center justify-center bg-[#20165c] text-white font-bold text-base" style="display: none;">
                                                            {{ $creator->initials }}
                                                        </span>
                                                    @else
                                                        <span class="w-full h-full flex items-center justify-center bg-[#20165c] text-white font-bold text-base">
                                                            {{ $creator->initials }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </a>
                                            <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 rounded-full border-2 border-white" title="Active on Noksha"></span>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-1.5">
                                                <a href="{{ route('user.profile', $creator->username ?: $creator->id) }}" class="font-bold text-[#121826] text-base group-hover:text-[#20165c] transition truncate block no-underline">
                                                    {{ $creator->name }}
                                                </a>
                                                <i class="bi bi-patch-check-fill text-emerald-500 text-sm shrink-0" title="Verified Noksha Contributor"></i>
                                            </div>
                                            
                                            <p class="text-xs text-slate-500 font-medium truncate mb-1">
                                                {{ $creator->headline ?: ($creator->skills ? (is_array($creator->skills) ? implode(', ', array_slice($creator->skills, 0, 2)) : $creator->skills) : 'Visual & Brand Designer') }}
                                            </p>

                                            <!-- Rating / Assets Count -->
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
                                </div>

                                <!-- Card Footer: Username & View Profile -->
                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between mt-4">
                                    <span class="text-slate-400 font-mono text-[11px]">
                                        {{ $creator->username ? '@' . $creator->username : 'Creator' }}
                                    </span>
                                    <a href="{{ route('user.profile', $creator->username ?: $creator->id) }}" 
                                       class="px-3.5 py-1.5 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white text-xs font-semibold transition-all duration-200 shadow-sm inline-flex items-center gap-1.5 no-underline">
                                        <span>View Profile</span>
                                        <i class="bi bi-arrow-right text-[11px]"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 2.2 MATCHING SERVICES SECTION -->
            @if(($tab === 'all' || $tab === 'services') && $services->count() > 0)
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-[#f47402]/10 text-[#f47402] flex items-center justify-center font-bold">
                                <i class="bi bi-briefcase-fill text-sm"></i>
                            </div>
                            <h2 class="text-lg font-bold text-[#121826] tracking-tight">Matching Creative Services</h2>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-200/70 text-slate-700 font-mono">
                                {{ $services->count() }}
                            </span>
                        </div>
                        @if($tab === 'all' && $services->count() > 3)
                            <a href="{{ route('search', ['q' => $q, 'tab' => 'services']) }}" class="text-xs font-semibold text-[#20165c] hover:text-[#181045] inline-flex items-center gap-1 no-underline">
                                <span>View all {{ $services->count() }} services</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach(($tab === 'all' ? $services->take(6) : $services) as $service)
                            <x-service-card :service="$service" />
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 2.3 MATCHING TEMPLATES SECTION -->
            @if(($tab === 'all' || $tab === 'templates') && $templates->count() > 0)
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                                <i class="bi bi-grid-fill text-sm"></i>
                            </div>
                            <h2 class="text-lg font-bold text-[#121826] tracking-tight">Matching Templates &amp; Assets</h2>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-200/70 text-slate-700 font-mono">
                                {{ $templates->count() }}
                            </span>
                        </div>
                        @if($tab === 'all' && $templates->count() > 8)
                            <a href="{{ route('search', ['q' => $q, 'tab' => 'templates']) }}" class="text-xs font-semibold text-[#20165c] hover:text-[#181045] inline-flex items-center gap-1 no-underline">
                                <span>View all {{ $templates->count() }} templates</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach(($tab === 'all' ? $templates->take(12) : $templates) as $template)
                            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md hover:border-[#20165c]/30 transition duration-200 flex flex-col justify-between group">
                                <div>
                                    <!-- Thumbnail Box -->
                                    <div class="relative aspect-video overflow-hidden bg-slate-100">
                                        <img src="{{ $template->thumbnail_url }}" alt="{{ $template->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.src='{{ asset('images/logo.png') }}'">
                                        
                                        <!-- Category Badge -->
                                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-white/95 text-slate-700 border border-slate-200 shadow-xs backdrop-blur-md">
                                            {{ $template->category?->name ?? 'Design' }}
                                        </span>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="p-4">
                                        <h3 class="text-sm font-bold text-[#121826] truncate block group-hover:text-[#20165c] transition-colors" title="{{ $template->title }}">
                                            {{ $template->title }}
                                        </h3>

                                        <div class="flex items-center gap-2 mt-2 text-xs text-slate-500">
                                            @if($template->owner)
                                                <span>by <strong class="text-slate-700">{{ $template->owner->name }}</strong></span>
                                            @else
                                                <span>by <strong class="text-slate-700">Noksha Creator</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Footer -->
                                <div class="p-4 pt-3 border-t border-slate-200 flex items-center justify-between mt-auto">
                                    <span class="text-base font-extrabold text-[#f47402] font-mono">
                                        {{ $template->price == 0 ? 'Free' : '৳' . number_format($template->price, 2) }}
                                    </span>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('templates.show', $template->id) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#20165c] hover:bg-[#181045] shadow-sm transition-all no-underline">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Tab has 0 results but other tabs have results -->
            @if($tab === 'creators' && $creators->count() === 0)
                <div class="bg-white border border-slate-200 rounded-3xl p-12 text-center shadow-xs">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-[#20165c] mx-auto flex items-center justify-center mb-3">
                        <i class="bi bi-people text-xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-[#121826]">No creators matched "{{ $q }}"</h3>
                    <p class="text-xs text-slate-500 mt-1">Try switching to <a href="{{ route('search', ['q' => $q, 'tab' => 'all']) }}" class="text-[#20165c] font-semibold underline">All Results</a> to check templates or services.</p>
                </div>
            @elseif($tab === 'services' && $services->count() === 0)
                <div class="bg-white border border-slate-200 rounded-3xl p-12 text-center shadow-xs">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-[#f47402] mx-auto flex items-center justify-center mb-3">
                        <i class="bi bi-briefcase text-xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-[#121826]">No services matched "{{ $q }}"</h3>
                    <p class="text-xs text-slate-500 mt-1">Try switching to <a href="{{ route('search', ['q' => $q, 'tab' => 'all']) }}" class="text-[#20165c] font-semibold underline">All Results</a> to check templates or creators.</p>
                </div>
            @elseif($tab === 'templates' && $templates->count() === 0)
                <div class="bg-white border border-slate-200 rounded-3xl p-12 text-center shadow-xs">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-3">
                        <i class="bi bi-grid text-xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-[#121826]">No templates matched "{{ $q }}"</h3>
                    <p class="text-xs text-slate-500 mt-1">Try switching to <a href="{{ route('search', ['q' => $q, 'tab' => 'all']) }}" class="text-[#20165c] font-semibold underline">All Results</a> to check services or creators.</p>
                </div>
            @endif

        @else
            <!-- ========================================================= -->
            <!-- 3. CLEAN EMPTY STATE (WHEN TOTAL RESULTS === 0)            -->
            <!-- ========================================================= -->
            <div class="bg-white border border-slate-200/90 rounded-3xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-[#20165c]/10 text-[#20165c] mx-auto flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#121826]">
                    @if(!empty($q))
                        No matching templates, services, or creators found for "{{ $q }}"
                    @else
                        Discover templates, services, and top creators
                    @endif
                </h3>
                <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto">
                    Try searching with different keywords, check for typos, or browse through our categories.
                </p>
                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('templates.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-[#20165c] hover:bg-[#181045] shadow-sm transition-all inline-flex items-center gap-2 no-underline">
                        <i class="bi bi-grid"></i>
                        <span>Browse All Templates</span>
                    </a>
                    <a href="{{ route('services.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-[#f47402] hover:bg-[#d86300] shadow-sm transition-all inline-flex items-center gap-2 no-underline">
                        <i class="bi bi-briefcase"></i>
                        <span>Explore Services</span>
                    </a>
                    <a href="{{ route('creators.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-700 hover:text-[#20165c] bg-white border border-slate-200 hover:bg-slate-50 shadow-xs transition-all inline-flex items-center gap-2 no-underline">
                        <i class="bi bi-people"></i>
                        <span>View Top Creators</span>
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>

<!-- INQUIRY & SERVICE MODALS -->
@include('services.partials.inquiry-modal')
@include('services.partials.image-preview-modal')
@endsection
