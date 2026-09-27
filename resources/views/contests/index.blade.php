@extends('layouts.app')

@section('title', 'Design Contests & Competitions - Noksha')

@section('content')

<!-- CUSTOM CONTEST STYLES -->
<style>
    .contest-hero {
        background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #4338CA 100%);
        border-radius: 2rem;
        position: relative;
        overflow: hidden;
    }

    .contest-card-figma {
        background: #ffffff;
        border-radius: 1.5rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.08) !important;
        transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
    }

    .contest-card-figma:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 45px -10px rgba(108, 76, 241, 0.22) !important;
        border-color: rgba(108, 76, 241, 0.35) !important;
    }

    .btn-purple-cta {
        background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 6px 16px -4px rgba(108, 76, 241, 0.35);
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #5A3DE0 0%, #4327C6 100%);
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 12px 25px -4px rgba(108, 76, 241, 0.45);
        color: #ffffff !important;
    }

    .leaderboard-card-glass {
        background: #ffffff;
        border-radius: 1.5rem !important;
        border: 1px solid rgba(108, 76, 241, 0.15) !important;
        box-shadow: 0 15px 35px -10px rgba(108, 76, 241, 0.12) !important;
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
        background: #6C4CF1;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(108, 76, 241, 0.3);
    }

    .filter-pill:not(.active) {
        background: #ffffff;
        color: #4B5563;
        border: 1px solid #E5E7EB;
    }

    .filter-pill:not(.active):hover {
        background: #F3F4F6;
        color: #111827;
    }

    .cat-chip {
        border-radius: 9999px;
        padding: 0.35rem 0.9rem;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid rgba(108, 76, 241, 0.15);
        background: rgba(108, 76, 241, 0.04);
        color: #5A3DE0;
    }

    .cat-chip.active, .cat-chip:hover {
        background: #6C4CF1;
        color: #ffffff;
        border-color: #6C4CF1;
    }

    .rank-badge-gold { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: white; }
    .rank-badge-silver { background: linear-gradient(135deg, #9CA3AF 0%, #4B5563 100%); color: white; }
    .rank-badge-bronze { background: linear-gradient(135deg, #B45309 0%, #78350F 100%); color: white; }
</style>

<div class="py-4 py-lg-5" style="background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%); min-height: 100vh;">
    <div class="container py-2">
        
        <!-- BREADCRUMB NAVIGATION -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small fw-semibold text-muted mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active text-dark" aria-current="page">Design Contests Hub</li>
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
                        <span class="badge bg-success bg-opacity-25 text-white rounded-pill px-3 py-1.5 fw-bold extra-small border border-white border-opacity-25">
                            <i class="bi bi-shield-fill-check me-1 text-warning"></i> 100% Guaranteed Escrow
                        </span>
                    </div>
                    <h1 class="display-5 fw-extrabold text-white mb-3">Noksha Design Contests</h1>
                    <p class="fs-5 text-white text-opacity-90 mb-4" style="max-width: 620px;">
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
                    <div class="p-4 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-25 text-center backdrop-blur">
                        <div class="extra-small text-uppercase fw-bold text-white text-opacity-75">Verified Prize Pool</div>
                        <div class="display-5 fw-extrabold text-warning my-1 font-monospace">৳{{ number_format($totalPrizePool, 0) }}</div>
                        <div class="extra-small text-white text-opacity-90">
                            <span class="fw-bold">{{ $activeContestsCount }}</span> Active Challenges • <span class="fw-bold">{{ $totalCompleted }}</span> Completed
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
                               class="form-control form-control-sm rounded-pill ps-3 pe-5 py-2 border shadow-sm"
                               style="font-size: 0.85rem;">
                        <button type="submit" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted pe-3 text-decoration-none">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- CATEGORY CHIPS ROW -->
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-2">
                <span class="extra-small fw-bold text-muted text-uppercase me-1"><i class="bi bi-funnel me-1"></i>Category:</span>
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
                <h4 class="fw-extrabold text-dark mb-0">
                    <i class="bi bi-fire text-primary me-2"></i>Contest Challenges
                </h4>
                <span class="small fw-bold text-muted font-monospace">{{ $contests->total() }} Contests Listed</span>
            </div>

            @if($contests->count() > 0)
                <div class="row g-4">
                    @foreach($contests as $contest)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 contest-card-figma p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <!-- Badges Row -->
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1.5 fw-bold extra-small">
                                            {{ $contest->display_category }}
                                        </span>

                                        <div class="d-flex align-items-center gap-1.5">
                                            @if($contest->is_guaranteed)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold" title="Prize pool verified & locked in Noksha Escrow">
                                                    <i class="bi bi-shield-fill-check text-success me-0.5"></i> Guaranteed
                                                </span>
                                            @endif

                                            @if($contest->status === 'active')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                    <i class="bi bi-record-fill me-1"></i> Active
                                                </span>
                                            @elseif($contest->status === 'handover')
                                                <span class="badge bg-warning bg-opacity-15 text-dark border border-warning border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                    <i class="bi bi-shield-lock-fill text-warning me-1"></i> Handover
                                                </span>
                                            @elseif($contest->status === 'judging')
                                                <span class="badge bg-warning bg-opacity-15 text-dark border border-warning border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                    <i class="bi bi-hourglass-split me-1"></i> Judging
                                                </span>
                                            @elseif($contest->status === 'completed')
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Completed
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Contest Title -->
                                    <h5 class="fw-extrabold text-dark mb-2 text-truncate" title="{{ $contest->title }}">
                                        <a href="{{ route('contests.show', $contest->slug) }}" class="text-dark text-decoration-none hover-primary">
                                            {{ $contest->title }}
                                        </a>
                                    </h5>

                                    <!-- Description Snippet -->
                                    <p class="text-secondary small mb-3 line-clamp-2" style="min-height: 2.6rem;">
                                        {{ Str::limit($contest->description, 110) }}
                                    </p>

                                    <!-- Live Remaining Countdown Pill -->
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="badge bg-light text-dark rounded-pill px-2.5 py-1 extra-small fw-bold font-monospace border">
                                            <i class="bi bi-clock me-1 text-primary"></i> {{ $contest->remaining_time }}
                                        </span>
                                        @if($contest->required_dimensions)
                                            <span class="badge bg-light text-muted rounded-pill px-2 py-1 extra-small font-monospace" title="Format / Dimensions">
                                                <i class="bi bi-aspect-ratio me-1"></i>{{ Str::limit($contest->required_dimensions, 16) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Bottom Card Meta & CTA -->
                                <div>
                                    <div class="p-3 bg-light rounded-3 mb-3 d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="extra-small text-muted text-uppercase fw-bold">Prize Bounty</div>
                                            <div class="fw-extrabold text-success fs-5 font-monospace">৳{{ number_format($contest->prize_amount, 0) }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="extra-small text-muted text-uppercase fw-bold">Entries</div>
                                            <div class="fw-bold text-dark font-monospace">
                                                <i class="bi bi-file-earmark-image me-1 text-primary"></i>
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
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4">
                    <div class="p-4 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-trophy fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">No Contests Match Your Filter</h4>
                    <p class="text-secondary small mb-4" style="max-width: 440px; margin: 0 auto;">
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
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom flex-wrap gap-2">
                <div>
                    <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(245, 158, 11, 0.1); color: #D97706;">
                        <i class="bi bi-award-fill me-1"></i> Hall of Fame
                    </span>
                    <h3 class="fw-extrabold text-dark mt-1 mb-0">Top Creator Leaderboard</h3>
                </div>
                <span class="small fw-bold text-muted font-monospace"><i class="bi bi-shield-check text-success me-1"></i>Verified Noksha Contributor Rankings</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase extra-small text-muted fw-bold">
                        <tr>
                            <th>Rank</th>
                            <th>Creator</th>
                            <th>Contest Wins</th>
                            <th>Published Assets</th>
                            <th>Trust Score</th>
                            <th class="text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($topCreators) && $topCreators->count() > 0)
                            @foreach($topCreators as $index => $creator)
                                <tr>
                                    <td style="width: 70px;">
                                        @if($index === 0)
                                            <span class="badge rank-badge-gold rounded-circle p-2 fw-extrabold d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">#1</span>
                                        @elseif($index === 1)
                                            <span class="badge rank-badge-silver rounded-circle p-2 fw-extrabold d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">#2</span>
                                        @elseif($index === 2)
                                            <span class="badge rank-badge-bronze rounded-circle p-2 fw-extrabold d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">#3</span>
                                        @else
                                            <span class="fw-bold text-muted font-monospace ms-2">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-placeholder rounded-circle bg-primary bg-opacity-10 text-primary fw-bold extra-small d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($creator->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-0">{{ $creator->name }}</div>
                                                <div class="extra-small text-muted">@ {{ $creator->username ?? Str::slug($creator->name) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning bg-opacity-15 text-dark fw-bold rounded-pill px-3 py-1 extra-small">
                                            <i class="bi bi-trophy-fill text-warning me-1"></i> {{ $creator->wins_count ?? 0 }} Wins
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark small font-monospace">
                                            {{ $creator->resources_count ?? 0 }} Assets
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-extrabold text-primary small">
                                            <i class="bi bi-shield-check text-success me-1"></i>{{ number_format($creator->trust_score ?? 99.4, 1) }}%
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        @if(($creator->wins_count ?? 0) > 0)
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                <i class="bi bi-patch-check-fill me-1"></i> Contest Champion
                                            </span>
                                        @elseif($creator->isContributor())
                                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                Verified Contributor
                                            </span>
                                        @else
                                            <span class="badge bg-light text-secondary rounded-pill px-2.5 py-1 extra-small">
                                                Member
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4 small">Leaderboard entries will populate as creators win official design challenges.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
