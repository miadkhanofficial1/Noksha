@extends('layouts.app')

@section('title', 'Design Contests & Leaderboard - Noksha (নকশা)')

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
        transition: transform 0.35s ease, box-shadow 0.35s ease;
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

    .rank-badge-gold { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: white; }
    .rank-badge-silver { background: linear-gradient(135deg, #9CA3AF 0%, #4B5563 100%); color: white; }
    .rank-badge-bronze { background: linear-gradient(135deg, #B45309 0%, #78350F 100%); color: white; }
</style>

<div class="py-4 py-lg-5" style="background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%); min-height: 100vh;">
    <div class="container py-2">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-muted mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">Design Contests</li>
            </ol>
        </nav>

        <!-- HERO BANNER -->
        <div class="contest-hero p-4 p-md-5 mb-5 text-white">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-extrabold extra-small text-uppercase mb-3">
                        <i class="bi bi-trophy-fill me-1"></i> Official Creator Arena
                    </span>
                    <h1 class="display-5 fw-extrabold text-white mb-3">Noksha Design Contests</h1>
                    <p class="fs-5 text-white text-opacity-90 mb-0" style="max-width: 620px;">
                        Compete in official UI/UX, vector, and branding challenges. Submit your original design assets, win cash prizes, and rank on the creator leaderboard.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="p-4 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-25 text-center">
                        <div class="extra-small text-uppercase fw-bold text-white text-opacity-75">Total Prize Pool</div>
                        <div class="display-5 fw-extrabold text-warning my-1">৳150,000+</div>
                        <div class="extra-small text-white text-opacity-90">Distributed to Top Designers</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTESTS LIST GRID -->
        <div class="mb-5">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3 class="fw-extrabold text-dark mb-0"><i class="bi bi-fire text-primary me-2"></i>Active & Past Challenges</h3>
                </div>
                <span class="small fw-bold text-muted font-monospace">{{ $contests->count() }} Contests Total</span>
            </div>

            @if($contests->count() > 0)
                <div class="row g-4">
                    @foreach($contests as $contest)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 contest-card-figma p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1.5 fw-bold extra-small">
                                            {{ $contest->category ? $contest->category->name : 'UI/UX Design' }}
                                        </span>
                                        @if($contest->status === 'active')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                <i class="bi bi-record-fill me-1"></i> Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                <i class="bi bi-check-circle-fill me-1"></i> Completed
                                            </span>
                                        @endif
                                    </div>

                                    <h5 class="fw-extrabold text-dark mb-2 text-truncate" title="{{ $contest->title }}">
                                        {{ $contest->title }}
                                    </h5>
                                    <p class="text-secondary small mb-4 line-clamp-2">
                                        {{ Str::limit($contest->description, 110) }}
                                    </p>
                                </div>

                                <div>
                                    <div class="p-3 bg-light rounded-3 mb-4 d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="extra-small text-muted text-uppercase fw-bold">Prize Amount</div>
                                            <div class="fw-extrabold text-success fs-5">৳{{ number_format($contest->prize_amount, 2) }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="extra-small text-muted text-uppercase fw-bold">Submissions</div>
                                            <div class="fw-bold text-dark font-monospace"><i class="bi bi-file-earmark-text me-1 text-primary"></i>{{ $contest->submissions->count() }}</div>
                                        </div>
                                    </div>

                                    <a href="{{ route('contests.show', $contest->slug) }}" class="btn btn-purple-cta rounded-pill w-100 py-2.5 fw-bold">
                                        View Challenge Details <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4">
                    <div class="p-4 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-trophy fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">No Active Contests Published Yet</h4>
                    <p class="text-secondary small mb-0" style="max-width: 440px; margin: 0 auto;">
                        Check back soon for new official Noksha design competitions and creator reward programs.
                    </p>
                </div>
            @endif
        </div>

        <!-- TOP 10 CREATORS LEADERBOARD -->
        <div class="card leaderboard-card-glass p-4 p-md-5">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(245, 158, 11, 0.1); color: #D97706;">
                        <i class="bi bi-award-fill me-1"></i> Hall of Fame
                    </span>
                    <h3 class="fw-extrabold text-dark mt-1 mb-0">Top Creator Leaderboard</h3>
                </div>
                <span class="small fw-bold text-muted font-monospace">Updated Daily</span>
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
                                            <span class="badge rank-badge-gold rounded-circle p-2 fw-extrabold" style="width:34px; height:34px;">#1</span>
                                        @elseif($index === 1)
                                            <span class="badge rank-badge-silver rounded-circle p-2 fw-extrabold" style="width:34px; height:34px;">#2</span>
                                        @elseif($index === 2)
                                            <span class="badge rank-badge-bronze rounded-circle p-2 fw-extrabold" style="width:34px; height:34px;">#3</span>
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
                                                <div class="extra-small text-muted">@ {{ $creator->username }}</div>
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
                                                <i class="bi bi-patch-check-fill me-1"></i> Contest Winner
                                            </span>
                                        @else
                                            <span class="badge bg-light text-secondary rounded-pill px-2.5 py-1 extra-small">
                                                Contributor
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
