@extends('layouts.app')

@section('title', $contest->title . ' - Contest - Noksha (নকশা)')

@section('content')

<!-- CUSTOM SINGLE CONTEST STYLES -->
<style>
    .contest-detail-bg {
        background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%);
        min-height: 100vh;
    }

    .contest-hero-card {
        background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #4338CA 100%);
        border-radius: 1.75rem;
        color: #ffffff;
    }

    .submission-card-figma {
        background: #ffffff;
        border-radius: 1.25rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.08) !important;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .submission-card-figma:hover {
        transform: translateY(-4px);
    }

    .glass-upload-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(108, 76, 241, 0.2) !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 20px 45px -10px rgba(108, 76, 241, 0.15) !important;
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

    .winner-glow-card {
        border: 2px solid #F59E0B !important;
        box-shadow: 0 0 25px rgba(245, 158, 11, 0.3) !important;
    }
</style>

<div class="contest-detail-bg py-4 py-lg-5">
    <div class="container py-2">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-muted mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contests.index') }}" class="text-decoration-none text-primary">Contests</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">{{ Str::limit($contest->title, 25) }}</li>
            </ol>
        </nav>

        <!-- HERO HEADER CARD -->
        <div class="card contest-hero-card p-4 p-md-5 mb-5 shadow-lg">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold extra-small">
                            {{ $contest->category ? $contest->category->name : 'Design Challenge' }}
                        </span>
                        @if($contest->status === 'active')
                            <span class="badge bg-success text-white rounded-pill px-3 py-1.5 extra-small fw-bold">
                                <i class="bi bi-record-fill me-1"></i> Accepting Submissions
                            </span>
                        @else
                            <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 extra-small fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i> Winner Announced
                            </span>
                        @endif
                    </div>

                    <h2 class="display-6 fw-extrabold text-white mb-3">{{ $contest->title }}</h2>
                    <p class="text-white text-opacity-90 fs-6 mb-4 lh-lg">{{ $contest->description }}</p>

                    <div class="d-flex flex-wrap gap-4 text-white text-opacity-80 small font-monospace">
                        <div><i class="bi bi-people-fill text-warning me-1"></i> <strong>{{ $contest->submissions->count() }}</strong> Participants</div>
                        <div><i class="bi bi-calendar-event me-1"></i> Ends: <strong>{{ $contest->end_date ? $contest->end_date->format('M d, Y') : 'Active' }}</strong></div>
                    </div>
                </div>

                <div class="col-lg-4 text-lg-end">
                    <div class="p-4 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-25 text-center">
                        <div class="extra-small text-uppercase fw-bold text-white text-opacity-75">Cash Prize</div>
                        <div class="display-5 fw-extrabold text-warning my-1">৳{{ number_format($contest->prize_amount, 2) }}</div>
                        <div class="extra-small text-white text-opacity-90">1st Place Creator Reward</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 g-lg-5">
            <!-- LEFT COLUMN: SUBMISSION GALLERY (8 COLUMNS) -->
            <div class="col-12 col-lg-8">
                
                <!-- WINNER BANNER IF COMPLETED -->
                @if($contest->status === 'completed' && $contest->winner)
                    <div class="card p-4 rounded-4 bg-warning bg-opacity-10 border border-warning mb-4 shadow-sm">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-warning text-dark rounded-circle fs-2">
                                🏆
                            </div>
                            <div>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold extra-small text-uppercase mb-1">
                                    Contest Winner Announced
                                </span>
                                <h4 class="fw-extrabold text-dark mb-0">Congratulations to {{ $contest->winner->name }}!</h4>
                                <p class="text-secondary small mb-0">Winner of ৳{{ number_format($contest->prize_amount, 2) }} prize money.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="fw-extrabold text-dark mb-0"><i class="bi bi-grid-fill text-primary me-2"></i>Submitted Entries ({{ $contest->submissions->count() }})</h4>
                </div>

                @if($contest->submissions->count() > 0)
                    <div class="row g-4 mb-5">
                        @foreach($contest->submissions as $sub)
                            <div class="col-12 col-sm-6">
                                <div class="card submission-card-figma h-100 {{ $sub->is_winner ? 'winner-glow-card' : '' }}">
                                    <div class="position-relative" style="height: 180px; background: #1E1B4B;">
                                        @if($sub->preview_image)
                                            <img src="{{ asset('storage/' . $sub->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $sub->title }}">
                                        @else
                                            <div class="w-100 h-100 p-4 text-white d-flex align-items-center justify-content-center fw-bold">Design Preview</div>
                                        @endif

                                        @if($sub->is_winner)
                                            <span class="position-absolute top-0 end-0 m-2.5 badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-extrabold shadow-sm">
                                                🏆 WINNER
                                            </span>
                                        @endif
                                    </div>
                                    <div class="card-body p-3.5">
                                        <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $sub->title }}</h6>
                                        <div class="d-flex align-items-center gap-2 extra-small text-muted mb-2">
                                            <span>by {{ $sub->user ? $sub->user->name : 'Creator' }}</span>
                                            <span>•</span>
                                            <span>{{ $sub->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($sub->note)
                                            <p class="text-secondary extra-small mb-0 line-clamp-2">"{{ $sub->note }}"</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-box-seam fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">No Entries Submitted Yet</h5>
                        <p class="text-secondary small mb-0">Be the first designer to submit an entry for this challenge.</p>
                    </div>
                @endif

            </div>

            <!-- RIGHT COLUMN: SELLER SUBMISSION FORM (4 COLUMNS) -->
            <div class="col-12 col-lg-4">
                <div class="card glass-upload-card p-4">
                    <h5 class="fw-extrabold text-dark mb-3 pb-2 border-bottom">
                        <i class="bi bi-cloud-arrow-up-fill text-primary me-2"></i>Submit Your Entry
                    </h5>

                    @auth
                        @if($contest->status === 'active')
                            <form action="{{ route('contests.submit', $contest->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Entry Title -->
                                <div class="mb-3">
                                    <label class="form-label extra-small fw-bold text-uppercase text-muted">Entry Title</label>
                                    <input type="text" name="title" class="form-control form-control-sm rounded-3" placeholder="e.g. Banking App UI Concept" required>
                                </div>

                                <!-- Preview Image -->
                                <div class="mb-3">
                                    <label class="form-label extra-small fw-bold text-uppercase text-muted">Preview Image (JPG/PNG/WEBP)</label>
                                    <input type="file" name="preview_image" class="form-control form-control-sm rounded-3" accept="image/*" required>
                                </div>

                                <!-- Source File -->
                                <div class="mb-3">
                                    <label class="form-label extra-small fw-bold text-uppercase text-muted">Source Design File (ZIP/PSD/AI/SVG)</label>
                                    <input type="file" name="design_file" class="form-control form-control-sm rounded-3" required>
                                </div>

                                <!-- Note -->
                                <div class="mb-3">
                                    <label class="form-label extra-small fw-bold text-uppercase text-muted">Design Note / Process</label>
                                    <textarea name="note" rows="3" class="form-control form-control-sm rounded-3" placeholder="Briefly describe your design approach..."></textarea>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-purple-cta rounded-pill py-3 fw-bold">
                                        <i class="bi bi-send-fill me-1"></i> Submit Design Entry
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-secondary rounded-4 small mb-0">
                                <i class="bi bi-lock-fill me-1"></i> This contest is closed for new entries.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-light border rounded-4 small text-muted mb-0 text-center py-4">
                            <i class="bi bi-lock-fill text-primary fs-3 d-block mb-2"></i>
                            Please <a href="{{ route('login') }}" class="fw-bold text-primary">Sign In</a> to submit your design entry.
                        </div>
                    @endauth
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
