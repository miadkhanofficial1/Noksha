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
        background: #0d1527;
        border-radius: 1.5rem !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
        transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
    }

    .contest-card-figma:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 45px -10px rgba(99, 102, 241, 0.25) !important;
        border-color: rgba(99, 102, 241, 0.45) !important;
    }

    .btn-purple-cta {
        background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 6px 16px -4px rgba(99, 102, 241, 0.35);
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #4F46E5 0%, #4338CA 100%);
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 12px 25px -4px rgba(99, 102, 241, 0.45);
        color: #ffffff !important;
    }

    .leaderboard-card-glass {
        background: #0d1527;
        border-radius: 1.5rem !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.5) !important;
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
        background: #6366F1;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
    }

    .filter-pill:not(.active) {
        background: #0d1527;
        color: #9CA3AF;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .filter-pill:not(.active):hover {
        background: #131d36;
        color: #ffffff;
        border-color: rgba(99, 102, 241, 0.3);
    }

    .cat-chip {
        border-radius: 9999px;
        padding: 0.35rem 0.9rem;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid rgba(99, 102, 241, 0.2);
        background: rgba(99, 102, 241, 0.08);
        color: #A5B4FC;
    }

    .cat-chip.active, .cat-chip:hover {
        background: #6366F1;
        color: #ffffff;
        border-color: #6366F1;
    }

    .rank-badge-gold { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: white; }
    .rank-badge-silver { background: linear-gradient(135deg, #9CA3AF 0%, #4B5563 100%); color: white; }
    .rank-badge-bronze { background: linear-gradient(135deg, #B45309 0%, #78350F 100%); color: white; }
</style>

<div class="min-h-screen bg-[#070b14] py-10 px-4 sm:px-6 lg:px-8 text-white">
    <div class="max-w-7xl mx-auto">
        
        <!-- BREADCRUMB NAVIGATION -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small fw-semibold text-gray-400 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-indigo-400 hover:text-indigo-300"><i class="bi bi-house-door me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active text-gray-300" aria-current="page">Design Contests Hub</li>
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
                    <p class="fs-5 text-gray-300 mb-4" style="max-width: 620px;">
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
                    <div class="p-4 bg-white/5 rounded-2xl border border-white/10 text-center backdrop-blur">
                        <div class="extra-small text-uppercase fw-bold text-gray-400">Verified Prize Pool</div>
                        <div class="display-5 fw-extrabold text-amber-400 my-1 font-monospace">৳{{ number_format($totalPrizePool, 0) }}</div>
                        <div class="extra-small text-gray-300">
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
                               class="form-control form-control-sm rounded-pill ps-3 pe-5 py-2 border border-gray-700 bg-[#0b1329] text-white placeholder-gray-500 shadow-sm focus:border-indigo-500"
                               style="font-size: 0.85rem;">
                        <button type="submit" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-gray-400 pe-3 text-decoration-none">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- CATEGORY CHIPS ROW -->
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-2">
                <span class="extra-small fw-bold text-gray-400 text-uppercase me-1"><i class="bi bi-funnel me-1"></i>Category:</span>
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
                <h4 class="fw-extrabold text-white mb-0">
                    <i class="bi bi-fire text-indigo-400 me-2"></i>Contest Challenges
                </h4>
                <span class="small fw-bold text-gray-400 font-monospace">{{ $contests->total() }} Contests Listed</span>
            </div>

            @if($contests->count() > 0)
                <div class="row g-4">
                    @foreach($contests as $contest)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 contest-card-figma p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <!-- Badges Row -->
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                        <span class="badge bg-indigo-500/15 text-indigo-300 border border-indigo-500/30 rounded-pill px-3 py-1.5 fw-bold extra-small">
                                            {{ $contest->display_category }}
                                        </span>

                                        <div class="d-flex align-items-center gap-1.5">
                                            @if($contest->is_guaranteed)
                                                <span class="badge bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 rounded-pill px-2.5 py-1 extra-small fw-bold" title="Prize pool verified & locked in Noksha Escrow">
                                                    <i class="bi bi-shield-fill-check text-emerald-400 me-0.5"></i> Guaranteed
                                                </span>
                                            @endif

                                            @if($contest->status === 'active')
                                                <span class="badge bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                    <i class="bi bi-record-fill me-1"></i> Active
                                                </span>
                                            @elseif($contest->status === 'handover')
                                                <span class="badge bg-amber-500/20 text-amber-300 border border-amber-500/30 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                    <i class="bi bi-shield-lock-fill text-amber-400 me-1"></i> Handover
                                                </span>
                                            @elseif($contest->status === 'judging')
                                                <span class="badge bg-amber-500/20 text-amber-300 border border-amber-500/30 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                    <i class="bi bi-hourglass-split me-1"></i> Judging
                                                </span>
                                            @elseif($contest->status === 'completed')
                                                <span class="badge bg-gray-700/50 text-gray-300 border border-gray-600/50 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Completed
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Contest Title -->
                                    <h5 class="fw-extrabold text-white mb-2 text-truncate" title="{{ $contest->title }}">
                                        <a href="{{ route('contests.show', $contest->slug) }}" class="text-white text-decoration-none hover:text-indigo-400 transition-colors">
                                            {{ $contest->title }}
                                        </a>
                                    </h5>

                                    <!-- Description Snippet -->
                                    <p class="text-gray-400 small mb-3 line-clamp-2" style="min-height: 2.6rem;">
                                        {{ Str::limit($contest->description, 110) }}
                                    </p>

                                    <!-- Live Remaining Countdown Pill -->
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="badge bg-[#111c35] text-gray-200 border border-gray-700/60 rounded-pill px-2.5 py-1 extra-small fw-bold font-monospace">
                                            <i class="bi bi-clock me-1 text-indigo-400"></i> {{ $contest->remaining_time }}
                                        </span>
                                        @if($contest->required_dimensions)
                                            <span class="badge bg-[#111c35] text-gray-400 border border-gray-700/60 rounded-pill px-2 py-1 extra-small font-monospace" title="Format / Dimensions">
                                                <i class="bi bi-aspect-ratio me-1"></i>{{ Str::limit($contest->required_dimensions, 16) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Bottom Card Meta & CTA -->
                                <div>
                                    <div class="p-3 bg-[#080d1a] border border-gray-800/80 rounded-xl mb-3 d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="extra-small text-gray-400 text-uppercase fw-bold">Prize Bounty</div>
                                            <div class="fw-extrabold text-emerald-400 fs-5 font-monospace">৳{{ number_format($contest->prize_amount, 0) }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="extra-small text-gray-400 text-uppercase fw-bold">Entries</div>
                                            <div class="fw-bold text-gray-200 font-monospace">
                                                <i class="bi bi-file-earmark-image me-1 text-indigo-400"></i>
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
                <div class="card bg-[#0d1527] border border-gray-800/80 shadow-2xl rounded-2xl p-5 text-center my-4 text-white">
                    <div class="p-4 bg-indigo-500/10 text-indigo-400 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-trophy fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-2">No Contests Match Your Filter</h4>
                    <p class="text-gray-400 small mb-4" style="max-width: 440px; margin: 0 auto;">
                        There are currently no contests matching your search or category filter. Check back soon or launch your own contest!
                    </p>
                    <div>
                        <a href="{{ route('contests.create') }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold">
                            <i class="bi bi-plus-circle me-1"></i> Launch a Design Contest
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- TOP 10 CREATORS LEADERBOARD -->
        <div class="card leaderboard-card-glass p-4 p-md-5">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-b border-gray-800 flex-wrap gap-2">
                <div>
                    <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(245, 158, 11, 0.15); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.25);">
                        <i class="bi bi-award-fill me-1"></i> Hall of Fame
                    </span>
                    <h3 class="fw-extrabold text-white mt-1 mb-0">Top Creator Leaderboard</h3>
                </div>
                <span class="small fw-bold text-gray-400 font-monospace"><i class="bi bi-shield-check text-emerald-400 me-1"></i>Verified Noksha Contributor Rankings</span>
            </div>

            <div class="table-responsive rounded-xl overflow-hidden border border-gray-800">
                <table class="table table-hover align-middle mb-0 text-white" style="background-color: transparent;">
                    <thead class="bg-[#0b1120] text-uppercase extra-small text-gray-400 fw-bold border-b border-gray-800">
                        <tr>
                            <th class="py-3 px-3">Rank</th>
                            <th class="py-3 px-3">Creator</th>
                            <th class="py-3 px-3">Contest Wins</th>
                            <th class="py-3 px-3">Published Assets</th>
                            <th class="py-3 px-3">Trust Score</th>
                            <th class="py-3 px-3 text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/60">
                        @if(isset($topCreators) && $topCreators->count() > 0)
                            @foreach($topCreators as $index => $creator)
                                <tr class="bg-[#0b1120] hover:bg-[#111c35] transition-colors border-b border-gray-800/60">
                                    <td class="py-3 px-3" style="width: 70px;">
                                        @if($index === 0)
                                            <span class="badge rank-badge-gold rounded-circle p-2 fw-extrabold d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">#1</span>
                                        @elseif($index === 1)
                                            <span class="badge rank-badge-silver rounded-circle p-2 fw-extrabold d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">#2</span>
                                        @elseif($index === 2)
                                            <span class="badge rank-badge-bronze rounded-circle p-2 fw-extrabold d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">#3</span>
                                        @else
                                            <span class="fw-bold text-gray-400 font-monospace ms-2">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3">
                                        <a href="{{ route('user.profile', $creator->username ?? $creator->id) }}" class="d-flex align-items-center gap-3 text-decoration-none text-white" title="View {{ $creator->name }}'s Profile">
                                            @if($creator->avatar)
                                                <img src="{{ asset('storage/' . $creator->avatar) }}" alt="{{ $creator->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="avatar-placeholder rounded-circle bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 fw-bold extra-small d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    {{ strtoupper(substr($creator->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-white mb-0 hover:text-indigo-400 transition-colors">{{ $creator->name }}</div>
                                                <div class="extra-small text-gray-400 font-monospace">&#64;{{ $creator->username ?? Str::slug($creator->name) }}</div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="badge bg-amber-500/15 text-amber-300 border border-amber-500/30 fw-bold rounded-pill px-3 py-1 extra-small">
                                            <i class="bi bi-trophy-fill text-amber-400 me-1"></i> {{ $creator->wins_count ?? 0 }} Wins
                                        </span>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="fw-bold text-gray-300 small font-monospace">
                                            {{ $creator->resources_count ?? 0 }} Assets
                                        </span>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="fw-extrabold text-indigo-400 small">
                                            <i class="bi bi-shield-check text-emerald-400 me-1"></i>{{ number_format($creator->trust_score ?? 99.4, 1) }}%
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-end">
                                        @if(($creator->wins_count ?? 0) > 0)
                                            <span class="badge bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                <i class="bi bi-patch-check-fill me-1"></i> Contest Champion
                                            </span>
                                        @elseif($creator->isContributor())
                                            <span class="badge bg-indigo-500/15 text-indigo-300 border border-indigo-500/30 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                Verified Contributor
                                            </span>
                                        @else
                                            <span class="badge bg-gray-800 text-gray-400 border border-gray-700/60 rounded-pill px-2.5 py-1 extra-small">
                                                Member
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="bg-[#0b1120]">
                                <td colspan="6" class="text-center text-gray-400 py-4 small">Leaderboard entries will populate as creators win official design challenges.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
