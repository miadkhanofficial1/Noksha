@extends('layouts.app')

@section('title', 'Design Contests & Competitions - Noksha')

@section('content')

<!-- CUSTOM CONTEST STYLES -->
<style>
    .contest-hero {
        background: linear-gradient(135deg, #131238 0%, #1E1B4B 50%, #312E81 100%);
        border-radius: 2rem;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(99, 102, 241, 0.25);
    }

    .contest-card-figma {
        background: #ffffff;
        border-radius: 1.5rem !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
    }

    .contest-card-figma:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 32px -8px rgba(32, 22, 92, 0.12) !important;
        border-color: rgba(32, 22, 92, 0.35) !important;
    }

    .btn-purple-cta {
        background: #20165c;
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 14px rgba(32, 22, 92, 0.25);
    }

    .btn-purple-cta:hover {
        background: #181045;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(32, 22, 92, 0.35);
        color: #ffffff !important;
    }

    .leaderboard-card-glass {
        background: #ffffff;
        border-radius: 1rem !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05) !important;
    }

    .filter-pill {
        border-radius: 9999px;
        padding: 0.5rem 1.25rem;
        font-size: 0.85rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .filter-pill.active {
        background: #20165c;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(32, 22, 92, 0.3);
    }

    .filter-pill:not(.active) {
        background: #f8fafc;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .filter-pill:not(.active):hover {
        background: #f1f5f9;
        color: #20165c;
        border-color: #cbd5e1;
    }

    .cat-chip {
        border-radius: 9999px;
        padding: 0.35rem 0.9rem;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #64748b;
    }

    .cat-chip.active, .cat-chip:hover {
        background: #20165c;
        color: #ffffff;
        border-color: #20165c;
    }

    .rank-badge-gold { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: white; }
    .rank-badge-silver { background: linear-gradient(135deg, #9CA3AF 0%, #4B5563 100%); color: white; }
    .rank-badge-bronze { background: linear-gradient(135deg, #B45309 0%, #78350F 100%); color: white; }
</style>

<div class="min-h-screen bg-[#ffffff] py-10 px-4 sm:px-6 lg:px-8 text-[#121826]">
    <div class="max-w-7xl mx-auto">
        
        <!-- BREADCRUMB NAVIGATION -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small fw-semibold text-slate-500 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-[#20165c] hover:text-indigo-300"><i class="bi bi-house-door me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active text-slate-700" aria-current="page">Design Contests Hub</li>
                </ol>
            </nav>
            <a href="{{ route('contests.create') }}" class="btn btn-purple-cta rounded-pill px-4 py-2 small fw-bold d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-circle-fill"></i> Launch a Contest
            </a>
        </div>

        <!-- HERO BANNER -->
        <div class="contest-hero p-4 p-md-5 mb-5 text-white shadow-lg">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-extrabold extra-small text-uppercase">
                            <i class="bi bi-trophy-fill me-1"></i> Official Creator Arena
                        </span>
                        <span class="badge bg-emerald-500/20 text-emerald-300 rounded-pill px-3 py-1.5 fw-bold extra-small border border-emerald-500/30">
                            <i class="bi bi-shield-fill-check me-1 text-warning"></i> 100% Guaranteed Escrow
                        </span>
                    </div>
                    <h1 class="display-5 fw-extrabold text-white mb-3">Noksha Design Contests</h1>
                    <p class="fs-5 text-purple-100 mb-4" style="max-width: 620px;">
                        Compete in real design challenges, submit original vectors, branding, & UI assets, win verified BDT prize pools, and rank on the national creator leaderboard.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('contests.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold shadow-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-award-fill"></i> Start Your Own Contest
                        </a>
                        <a href="#browse-contests" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-bold">
                            Explore Challenges <i class="bi bi-arrow-down-short"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="p-4 bg-white/10 rounded-2xl border border-white/20 text-center backdrop-blur">
                        <div class="extra-small text-uppercase fw-bold text-purple-200">Verified Prize Pool</div>
                        <div class="display-5 fw-extrabold text-[#f47402] my-1 font-monospace">৳{{ number_format($totalPrizePool, 0) }}</div>
                        <div class="extra-small text-purple-200">
                            <span class="fw-bold text-white">{{ $activeContestsCount }}</span> Active Challenges • <span class="fw-bold text-white">{{ $totalCompleted }}</span> Completed
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTER TABS & SEARCH BAR -->
        <div id="browse-contests" class="mb-4">
            <div class="row align-items-center g-3 justify-content-between">
                
                <!-- Status Filter Tabs -->
                <div class="col-12 col-md-auto">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('contests.index', array_merge(request()->query(), ['tab' => 'all'])) }}"
                           class="filter-pill {{ ($tab ?? 'all') === 'all' ? 'active' : '' }}">
                            <i class="bi bi-grid-fill"></i> All Contests
                        </a>
                        <a href="{{ route('contests.index', array_merge(request()->query(), ['tab' => 'active'])) }}"
                           class="filter-pill {{ ($tab ?? 'all') === 'active' ? 'active' : '' }}">
                            <span class="spinner-grow spinner-grow-sm text-success" role="status" style="width: 0.55rem; height: 0.55rem;"></span>
                            Active Challenges
                        </a>
                        <a href="{{ route('contests.index', array_merge(request()->query(), ['tab' => 'judging'])) }}"
                           class="filter-pill {{ ($tab ?? 'all') === 'judging' ? 'active' : '' }}">
                            <i class="bi bi-hourglass-split text-warning"></i> In Review / Judging
                        </a>
                        <a href="{{ route('contests.index', array_merge(request()->query(), ['tab' => 'completed'])) }}"
                           class="filter-pill {{ ($tab ?? 'all') === 'completed' ? 'active' : '' }}">
                            <i class="bi bi-check-circle-fill text-success"></i> Completed
                        </a>
                    </div>
                </div>

                <!-- Search Input Form -->
                <div class="col-12 col-md-4 col-lg-3">
                    <form action="{{ route('contests.index') }}" method="GET" class="position-relative">
                        @if(request('tab'))
                            <input type="hidden" name="tab" value="{{ request('tab') }}">
                        @endif
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search contests..."
                               class="form-control form-control-sm rounded-pill ps-3 pe-5 py-2 border border-slate-300 bg-white text-[#121826] placeholder-gray-500 shadow-sm focus:border-indigo-500"
                               style="font-size: 0.85rem;">
                        <button type="submit" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-slate-500 pe-3 text-decoration-none">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- CATEGORY CHIPS ROW -->
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-2">
                <span class="extra-small fw-bold text-slate-500 text-uppercase me-1"><i class="bi bi-funnel me-1"></i>Category:</span>
                @php
                    $categoriesList = [
                        'all' => 'All Categories',
                        'UI/UX Design' => 'UI/UX Design',
                        'Logo & Branding' => 'Logo & Branding',
                        'Vector & Illustration' => 'Vector & Illustration',
                        'Social Media Design' => 'Social Media Design',
                        'Print & Packaging' => 'Print & Packaging',
                        'Typography' => 'Typography & Lettering',
                    ];
                    $currentCat = request('category', 'all');
                @endphp

                @foreach($categoriesList as $key => $label)
                    <a href="{{ route('contests.index', array_merge(request()->query(), ['category' => $key === 'all' ? null : $key])) }}"
                       class="cat-chip {{ ($currentCat === $key || ($key === 'all' && empty(request('category')))) ? 'active' : '' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- CONTESTS LIST GRID -->
        <div class="mb-5">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="fw-extrabold text-[#121826] mb-0">
                    <i class="bi bi-fire text-[#20165c] me-2"></i>Contest Challenges
                </h4>
                <span class="small fw-bold text-slate-500 font-monospace">{{ $contests->total() }} Contests Listed</span>
            </div>

            @if($contests->count() > 0)
                <div class="row g-4">
                    @foreach($contests as $contest)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 contest-card-figma p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <!-- Badges Row -->
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                        <span class="badge bg-purple-50 text-[#20165c] border border-purple-200 rounded-pill px-3 py-1.5 fw-bold extra-small">
                                            {{ $contest->display_category }}
                                        </span>

                                        <div class="d-flex align-items-center gap-1.5">
                                            @if($contest->is_guaranteed)
                                                <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-pill px-2.5 py-1 extra-small fw-bold" title="Prize pool verified & locked in Noksha Escrow">
                                                    <i class="bi bi-shield-fill-check text-emerald-600 me-0.5"></i> Guaranteed
                                                </span>
                                            @endif

                                            @if($contest->status === 'active')
                                                <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                    <i class="bi bi-record-fill me-1"></i> Active
                                                </span>
                                            @elseif($contest->status === 'handover')
                                                <span class="badge bg-orange-50 text-[#f47402] border border-orange-200 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                    <i class="bi bi-shield-lock-fill text-[#f47402] me-1"></i> Handover
                                                </span>
                                            @elseif($contest->status === 'judging')
                                                <span class="badge bg-amber-50 text-amber-700 border border-amber-200 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                    <i class="bi bi-hourglass-split me-1"></i> Judging
                                                </span>
                                            @elseif($contest->status === 'completed')
                                                <span class="badge bg-slate-100 text-slate-700 border border-slate-200 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Completed
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Contest Title -->
                                    <h5 class="fw-extrabold text-[#121826] mb-2 text-truncate" title="{{ $contest->title }}">
                                        <a href="{{ route('contests.show', $contest->slug) }}" class="text-[#121826] text-decoration-none hover:text-[#20165c] transition-colors">
                                            {{ $contest->title }}
                                        </a>
                                    </h5>

                                    <!-- Description Snippet -->
                                    <p class="text-slate-500 small mb-3 line-clamp-2" style="min-height: 2.6rem;">
                                        {{ Str::limit($contest->description, 110) }}
                                    </p>

                                    <!-- Live Remaining Countdown Pill -->
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="badge bg-slate-100 text-slate-700 border border-slate-200 rounded-pill px-2.5 py-1 extra-small fw-bold font-monospace">
                                            <i class="bi bi-clock me-1 text-[#20165c]"></i> {{ $contest->remaining_time }}
                                        </span>
                                        @if($contest->required_dimensions)
                                            <span class="badge bg-slate-100 text-slate-600 border border-slate-200 rounded-pill px-2 py-1 extra-small font-monospace" title="Format / Dimensions">
                                                <i class="bi bi-aspect-ratio me-1"></i>{{ Str::limit($contest->required_dimensions, 16) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Bottom Card Meta & CTA -->
                                <div>
                                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl mb-3 d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="extra-small text-slate-500 text-uppercase fw-bold">Prize Bounty</div>
                                            <div class="fw-extrabold text-[#20165c] fs-5 font-monospace">৳{{ number_format($contest->prize_amount, 0) }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="extra-small text-slate-500 text-uppercase fw-bold">Entries</div>
                                            <div class="fw-bold text-slate-700 font-monospace">
                                                <i class="bi bi-file-earmark-image me-1 text-[#20165c]"></i>
                                                {{ $contest->entries->count() ?: $contest->submissions->count() }} designs
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ route('contests.show', $contest->slug) }}" class="btn btn-purple-cta rounded-pill w-100 py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2">
                                        <span>View Challenge Details</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- PAGINATION -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $contests->links() }}
                </div>
            @else
                <div class="w-full bg-[#f8fafc] border border-dashed border-slate-300 rounded-3xl p-12 text-center my-8 shadow-sm">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-purple-50 text-[#20165c] flex items-center justify-center text-2xl">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#121826] mb-2">No Contests Match Your Filter</h3>
                    <p class="text-sm text-slate-500 max-w-md mx-auto mb-6">There are currently no contests matching your search or category filter. Check back soon or launch your own contest!</p>
                    <a href="{{ route('contests.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#20165c] hover:bg-[#181045] text-white font-semibold rounded-xl shadow-md transition">
                        <span>Launch a Design Contest</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- TOP 10 CREATORS LEADERBOARD -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-2xl backdrop-blur-md">
            <div class="d-flex align-items-center justify-content-between mb-6 pb-4 border-b border-slate-200 flex-wrap gap-3">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-500/10 text-[#f47402] border border-amber-500/20 mb-2">
                        <i class="bi bi-award-fill"></i> Hall of Fame
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#121826] tracking-tight">Top Creator Leaderboard</h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 mb-0">Ranked dynamically by verified platform earnings, contest victories, and published assets.</p>
                </div>
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-50 border border-slate-200 text-xs font-mono text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Live Creator Rankings</span>
                </div>
            </div>

            @if(isset($topCreators) && $topCreators->count() > 0)
                <div class="overflow-x-auto rounded-xl border border-slate-200 bg-slate-50">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3.5 px-4 text-center" style="width: 80px;">Rank</th>
                                <th class="py-3.5 px-4">Creator</th>
                                <th class="py-3.5 px-4 text-center">Contest Wins</th>
                                <th class="py-3.5 px-4 text-center">Published Assets</th>
                                <th class="py-3.5 px-4 text-end">Earnings / Withdrawn</th>
                                <th class="py-3.5 px-4 text-end">Dynamic Tier</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($topCreators as $creator)
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition duration-150">
                                    <td class="py-4 px-4 text-center">
                                        @if($creator->rank === 1)
                                            <span class="badge rank-badge-gold rounded-circle p-2 fw-extrabold d-inline-flex align-items-center justify-content-center shadow-lg" style="width:34px; height:34px;">#1</span>
                                        @elseif($creator->rank === 2)
                                            <span class="badge rank-badge-silver rounded-circle p-2 fw-extrabold d-inline-flex align-items-center justify-content-center shadow-md" style="width:34px; height:34px;">#2</span>
                                        @elseif($creator->rank === 3)
                                            <span class="badge rank-badge-bronze rounded-circle p-2 fw-extrabold d-inline-flex align-items-center justify-content-center shadow-sm" style="width:34px; height:34px;">#3</span>
                                        @else
                                            <span class="fw-bold text-slate-500 font-mono text-sm">#{{ $creator->rank }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4">
                                        <a href="{{ route('user.profile', $creator->username ?? $creator->id) }}" class="inline-flex items-center gap-3 text-decoration-none group" title="View {{ $creator->name }}'s Profile">
                                            <div class="relative w-10 h-10 rounded-full overflow-hidden flex items-center justify-center bg-[#20165c] text-white font-bold text-xs ring-2 ring-indigo-500/30 group-hover:ring-indigo-400 transition shrink-0">
                                                @if($creator->avatar_url)
                                                    <img src="{{ $creator->avatar_url }}" alt="{{ $creator->name }}" class="w-full h-full object-cover object-center block" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <span class="w-full h-full flex items-center justify-center bg-[#20165c] text-white font-bold text-xs" style="display: none;">
                                                        {{ $creator->initials }}
                                                    </span>
                                                @else
                                                    <span class="w-full h-full flex items-center justify-center bg-[#20165c] text-white font-bold text-xs">
                                                        {{ $creator->initials }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-bold text-[#121826] group-hover:text-[#20165c] transition-colors">{{ $creator->name }}</div>
                                                <div class="text-xs text-slate-500 font-mono">&#64;{{ $creator->username ?? Str::slug($creator->name) }}</div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-700 border border-amber-500/20">
                                            <i class="bi bi-trophy-fill text-[#f47402]"></i> {{ $creator->contest_wins ?? 0 }} {{ Str::plural('Win', $creator->contest_wins ?? 0) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <span class="font-semibold text-slate-700 text-sm font-mono">
                                            <i class="bi bi-layers text-[#20165c] me-1"></i>{{ $creator->published_assets ?? 0 }} Assets
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-end">
                                        <div>
                                            <span class="font-extrabold text-emerald-600 font-mono text-sm">৳{{ number_format($creator->total_withdrawn ?? 0, 2) }}</span>
                                            <div class="text-[11px] text-slate-500 font-mono">Score: {{ number_format($creator->ranking_score ?? 0, 0) }}</div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-end">
                                        @if($creator->rank === 1)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/15 text-amber-700 border border-amber-500/30 shadow-sm">
                                                <i class="bi bi-star-fill text-[#f47402]"></i> Elite Creator
                                            </span>
                                        @elseif($creator->rank <= 3)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-[#20165c] border border-purple-200 shadow-sm">
                                                <i class="bi bi-patch-check-fill text-[#20165c]"></i> Pro Contributor
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm">
                                                <i class="bi bi-shield-check text-emerald-600"></i> Verified Seller
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Clean Dark Empty State Card -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-8 sm:p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-[#20165c] shadow-inner">
                        <i class="bi bi-trophy text-3xl"></i>
                    </div>
                    <h4 class="text-lg sm:text-xl font-bold text-[#121826] mb-2">No Ranked Creators Yet</h4>
                    <p class="text-sm text-slate-500 max-w-md mx-auto mb-6 leading-relaxed">
                        The creator leaderboard dynamically tracks and ranks verified contributors as they win design challenges, publish approved templates, and earn platform bounties.
                    </p>
                    <div class="inline-flex flex-wrap items-center justify-center gap-3">
                        <a href="{{ route('seller.verification.create') }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 text-xs font-bold inline-flex items-center gap-2">
                            <i class="bi bi-patch-check-fill"></i> Become a Verified Contributor
                        </a>
                        <a href="{{ route('contests.create') }}" class="border border-slate-300 hover:bg-slate-100 text-slate-700 rounded-pill px-4 py-2.5 text-xs font-bold inline-flex items-center gap-2 transition">
                            <i class="bi bi-plus-circle"></i> Launch a Contest
                        </a>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
