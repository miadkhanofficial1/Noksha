@extends('layouts.app')

@section('title', $contest->title . ' - Contest Details - Noksha')

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
        position: relative;
        overflow: hidden;
    }

    .entry-card-figma {
        background: #ffffff;
        border-radius: 1.25rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.08) !important;
        overflow: hidden;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
    }

    .entry-card-figma:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 35px -8px rgba(108, 76, 241, 0.18) !important;
    }

    .winner-glow-card {
        border: 2px solid #F59E0B !important;
        box-shadow: 0 0 30px rgba(245, 158, 11, 0.25) !important;
    }

    .glass-sidebar-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(108, 76, 241, 0.15) !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 15px 35px -10px rgba(108, 76, 241, 0.12) !important;
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
        transform: translateY(-2px);
        box-shadow: 0 12px 25px -4px rgba(108, 76, 241, 0.45);
        color: #ffffff !important;
    }

    .entry-preview-box {
        position: relative;
        height: 240px;
        background: #0F172A;
        overflow: hidden;
        cursor: pointer;
    }

    .entry-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.4s ease;
    }

    .entry-preview-box:hover img {
        transform: scale(1.03);
    }

    .zoom-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .entry-preview-box:hover .zoom-overlay {
        opacity: 1;
    }

    .btn-like {
        transition: all 0.2s ease;
        border-radius: 9999px;
    }

    .btn-like.liked {
        background: #FEE2E2 !important;
        color: #EF4444 !important;
        border-color: #FECACA !important;
    }

    .btn-like.liked i {
        color: #EF4444 !important;
    }

    .rating-star-interactive {
        cursor: pointer;
        font-size: 1.5rem;
        color: #CBD5E1;
        transition: color 0.15s ease;
    }

    .rating-star-interactive:hover,
    .rating-star-interactive.active {
        color: #F59E0B;
    }
</style>

<div class="contest-detail-bg py-4 py-lg-5">
    <div class="container py-2">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-muted mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contests.index') }}" class="text-decoration-none text-primary">Contests</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">{{ Str::limit($contest->title, 35) }}</li>
            </ol>
        </nav>


        <!-- HERO HEADER CARD -->
        <div class="card contest-hero-card p-4 p-md-5 mb-5 shadow-lg">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold extra-small">
                            {{ $contest->display_category }}
                        </span>

                        @if($contest->is_guaranteed)
                            <span class="badge bg-success text-white rounded-pill px-3 py-1.5 extra-small fw-bold border border-white border-opacity-25 shadow-sm">
                                <i class="bi bi-shield-fill-check me-1 text-warning"></i> 100% Guaranteed Escrow
                            </span>
                        @endif

                        @if($contest->status === 'active')
                            <span class="badge bg-success bg-opacity-25 text-white rounded-pill px-3 py-1.5 extra-small fw-bold border border-white border-opacity-25">
                                <i class="bi bi-record-fill me-1 text-success"></i> Accepting Submissions
                            </span>
                        @elseif($contest->status === 'judging')
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 extra-small fw-bold">
                                <i class="bi bi-hourglass-split me-1"></i> In Review / Judging
                            </span>
                        @elseif($contest->status === 'completed')
                            <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 extra-small fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i> Winner Announced
                            </span>
                        @endif
                    </div>

                    <h1 class="display-6 fw-extrabold text-white mb-3">{{ $contest->title }}</h1>
                    <p class="text-white text-opacity-90 fs-6 mb-4 lh-lg" style="max-width: 720px;">
                        {{ $contest->description }}
                    </p>

                    <!-- Meta Details -->
                    <div class="d-flex flex-wrap align-items-center gap-4 text-white text-opacity-80 small font-monospace">
                        <div class="d-flex align-items-center gap-1.5">
                            <i class="bi bi-person-circle text-warning fs-6"></i>
                            <span>Organized by <strong>{{ $contest->organizer ? $contest->organizer->name : 'Noksha Official' }}</strong></span>
                        </div>
                        <div>
                            <i class="bi bi-images text-warning me-1"></i>
                            <strong>{{ $contest->entries->count() ?: $contest->submissions->count() }}</strong> Submissions
                        </div>
                        <div>
                            <i class="bi bi-clock-history text-warning me-1"></i>
                            <strong>{{ $contest->remaining_time }}</strong>
                            <span class="text-white text-opacity-60">({{ $contest->effective_deadline ? $contest->effective_deadline->format('M d, Y') : 'Ongoing' }})</span>
                        </div>
                    </div>

                    <!-- Attachment / Dimensions bar -->
                    @if($contest->required_dimensions || $contest->attachment_file)
                        <div class="d-flex flex-wrap align-items-center gap-3 mt-4 pt-3 border-top border-white border-opacity-15">
                            @if($contest->required_dimensions)
                                <div class="badge bg-white bg-opacity-10 text-white rounded-pill px-3 py-2 extra-small fw-normal">
                                    <i class="bi bi-aspect-ratio text-warning me-1"></i> Required Format: <strong>{{ $contest->required_dimensions }}</strong>
                                </div>
                            @endif

                            @if($contest->attachment_file)
                                <a href="{{ asset('storage/' . $contest->attachment_file) }}" target="_blank" download class="btn btn-outline-light btn-sm rounded-pill px-3 py-1.5 extra-small fw-bold">
                                    <i class="bi bi-paperclip me-1 text-warning"></i> Download Brief Attachment
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="col-lg-4 text-lg-end">
                    <div class="p-4 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-25 text-center">
                        <div class="extra-small text-uppercase fw-bold text-white text-opacity-75">1st Place Prize Bounty</div>
                        <div class="display-5 fw-extrabold text-warning my-1 font-monospace">৳{{ number_format($contest->prize_amount, 0) }}</div>
                        <div class="extra-small text-white text-opacity-90">
                            @if($contest->is_guaranteed)
                                <i class="bi bi-shield-check text-success me-1"></i> 100% Escrow Funded
                            @else
                                <i class="bi bi-clock me-1"></i> Verified Tournament
                            @endif
                        </div>

                        @if($isOrganizer && $contest->status === 'active')
                            <div class="mt-3 pt-3 border-top border-white border-opacity-20">
                                <span class="badge bg-white text-primary rounded-pill px-3 py-1 extra-small fw-bold">
                                    <i class="bi bi-star-fill text-warning me-1"></i> You are the Organizer
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- WINNER BANNER IF CONTEST IS COMPLETED -->
        @php
            $winnerEntry = $contest->winningEntry ?? $contest->entries->firstWhere('is_winner', true);
            $winnerUser = $contest->winner ?? ($winnerEntry ? $winnerEntry->user : null);
        @endphp

        @if($contest->status === 'completed' && ($winnerUser || $winnerEntry))
            <div class="card p-4 p-md-4 rounded-4 bg-warning bg-opacity-10 border border-warning mb-5 shadow-sm">
                <div class="row align-items-center g-3">
                    <div class="col-auto">
                        <div class="p-3 bg-warning text-dark rounded-circle fs-1 shadow-sm d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            🏆
                        </div>
                    </div>
                    <div class="col">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-extrabold extra-small text-uppercase mb-1">
                            Official Contest Champion Announced
                        </span>
                        <h3 class="fw-extrabold text-dark mb-1">
                            Congratulations to {{ $winnerUser ? $winnerUser->name : 'Winning Designer' }}!
                        </h3>
                        <p class="text-secondary small mb-0">
                            Awarded <strong>৳{{ number_format($contest->prize_amount, 0) }}</strong> cash prize for winning design concept: 
                            <span class="fw-bold text-dark font-monospace">"{{ $winnerEntry ? $winnerEntry->title : 'Winning Entry' }}"</span>
                        </p>
                    </div>
                    @if($winnerEntry && $winnerEntry->watermarked_preview_image)
                        <div class="col-auto text-end d-none d-md-block">
                            <img src="{{ asset('storage/' . $winnerEntry->watermarked_preview_image) }}" alt="Winner" class="rounded-3 shadow-sm border border-warning" style="width: 100px; height: 70px; object-fit: cover;">
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="row g-4 g-lg-5">
            <!-- LEFT COLUMN: PUBLIC SUBMISSIONS GALLERY (8 COLUMNS) -->
            <div class="col-12 col-lg-8">
                
                <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                    <div>
                        <h4 class="fw-extrabold text-dark mb-0">
                            <i class="bi bi-grid-fill text-primary me-2"></i>Contest Submissions
                        </h4>
                        <span class="text-muted extra-small">100% Public Gallery with Protected Watermarks</span>
                    </div>
                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1.5 font-monospace fw-bold">
                        {{ $contest->entries->count() }} Designs Submitted
                    </span>
                </div>

                @if($contest->entries->count() > 0)
                    <div class="row g-4 mb-5">
                        @foreach($contest->entries as $entry)
                            <div class="col-12 col-sm-6" id="entry-{{ $entry->id }}">
                                <div class="card entry-card-figma h-100 {{ $entry->is_winner ? 'winner-glow-card' : '' }}">
                                    
                                    <!-- Watermarked Image Box -->
                                    <div class="entry-preview-box" onclick="openLightbox('{{ asset('storage/' . $entry->watermarked_preview_image) }}', '{{ addslashes($entry->title) }}', '{{ addslashes($entry->user->name ?? 'Designer') }}')">
                                        <img src="{{ asset('storage/' . $entry->watermarked_preview_image) }}" alt="{{ $entry->title }}" loading="lazy">
                                        
                                        <!-- Overlay -->
                                        <div class="zoom-overlay">
                                            <span class="btn btn-light btn-sm rounded-pill px-3 py-1.5 fw-bold shadow-sm extra-small">
                                                <i class="bi bi-zoom-in me-1"></i> Preview Concept
                                            </span>
                                        </div>

                                        <!-- Badges on Image -->
                                        <div class="position-absolute top-0 start-0 m-2.5">
                                            <span class="badge bg-dark bg-opacity-75 text-white rounded-pill px-2.5 py-1 extra-small font-monospace">
                                                #{{ $entry->id }}
                                            </span>
                                        </div>

                                        @if($entry->is_winner)
                                            <div class="position-absolute top-0 end-0 m-2.5">
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-extrabold shadow-sm">
                                                    🏆 WINNER
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Entry Details -->
                                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $entry->title }}">
                                                {{ $entry->title }}
                                            </h6>
                                            
                                            <!-- Creator & Time -->
                                            <div class="d-flex align-items-center gap-2 extra-small text-muted mb-2">
                                                <span>by <strong>{{ $entry->user ? $entry->user->name : 'Contributor' }}</strong></span>
                                                <span>•</span>
                                                <span>{{ $entry->created_at->diffForHumans() }}</span>
                                            </div>

                                            @if($entry->description)
                                                <p class="text-secondary extra-small mb-3 line-clamp-2">
                                                    "{{ Str::limit($entry->description, 110) }}"
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Client Star Rating & Feedback Quote -->
                                        @if($entry->client_rating > 0)
                                            <div class="p-2.5 bg-warning bg-opacity-10 rounded-3 mb-3 border border-warning border-opacity-25">
                                                <div class="d-flex align-items-center gap-1 mb-1">
                                                    <div class="text-warning extra-small">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $entry->client_rating)
                                                                <i class="bi bi-star-fill"></i>
                                                            @else
                                                                <i class="bi bi-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span class="extra-small fw-bold text-dark ms-1">Client Rating</span>
                                                </div>
                                                @if($entry->client_feedback)
                                                    <div class="extra-small text-secondary fst-italic">
                                                        "{{ $entry->client_feedback }}"
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Footer Actions: Like Button & Client Controls -->
                                        <div class="pt-2 border-top d-flex align-items-center justify-content-between gap-2">
                                            <!-- Like / Upvote Button (AJAX) -->
                                            @php
                                                $isLiked = $entry->isLikedBy(auth()->id(), request()->ip());
                                            @endphp
                                            <button type="button"
                                                    onclick="toggleEntryLike({{ $entry->id }}, this)"
                                                    class="btn btn-sm btn-outline-secondary btn-like d-inline-flex align-items-center gap-1.5 extra-small px-3 py-1 fw-bold {{ $isLiked ? 'liked' : '' }}"
                                                    data-liked="{{ $isLiked ? '1' : '0' }}">
                                                <i class="bi {{ $isLiked ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                                <span class="like-counter">{{ $entry->likes_count }}</span>
                                            </button>

                                            <!-- Organizer Specific Controls -->
                                            @if($isOrganizer)
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <!-- Rate Button -->
                                                    <button type="button"
                                                            onclick="openRateModal({{ $entry->id }}, '{{ addslashes($entry->title) }}', {{ $entry->client_rating ?? 0 }}, '{{ addslashes($entry->client_feedback ?? '') }}')"
                                                            class="btn btn-sm btn-outline-warning rounded-pill px-2.5 py-1 extra-small fw-bold"
                                                            title="Rate & leave feedback">
                                                        <i class="bi bi-star me-1"></i> Rate
                                                    </button>

                                                    <!-- Award as Winner Button -->
                                                    @if($contest->status !== 'completed' && !$entry->is_winner)
                                                        <button type="button"
                                                                onclick="confirmAwardWinner({{ $entry->id }}, '{{ addslashes($entry->title) }}', '{{ addslashes($entry->user->name ?? 'Designer') }}')"
                                                                class="btn btn-sm btn-success rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                            <i class="bi bi-trophy-fill me-1"></i> Award
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-images fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">No Entries Submitted Yet</h5>
                        <p class="text-secondary small mb-0">Be the first verified contributor to submit an entry for this challenge and win ৳{{ number_format($contest->prize_amount, 0) }}.</p>
                    </div>
                @endif

            </div>

            <!-- RIGHT COLUMN: CONTRIBUTOR SUBMISSION & RULES (4 COLUMNS) -->
            <div class="col-12 col-lg-4">
                
                <!-- SUBMISSION FORM CARD -->
                <div class="card glass-sidebar-card p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <h5 class="fw-extrabold text-dark mb-0">
                            <i class="bi bi-cloud-arrow-up-fill text-primary me-2"></i>Submit Design
                        </h5>
                        <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-2.5 py-1 extra-small fw-bold">
                            Zero Entry Fee
                        </span>
                    </div>

                    @auth
                        @if($contest->status === 'active')
                            @if(!$isContributor)
                                <!-- User is NOT an approved contributor -->
                                <div class="text-center py-4">
                                    <div class="p-3 bg-warning bg-opacity-15 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="bi bi-person-badge fs-2"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Contributor KYC Required</h6>
                                    <p class="text-secondary extra-small mb-3">
                                        To ensure high quality original submissions and protect organizers, only verified Noksha Contributors can submit entries.
                                    </p>
                                    <a href="{{ route('contributor.apply') }}" class="btn btn-purple-cta rounded-pill w-100 py-2.5 fw-bold extra-small">
                                        <i class="bi bi-shield-check me-1"></i> Apply for Contributor KYC
                                    </a>
                                </div>
                            @elseif($userHasSubmitted)
                                <!-- User has ALREADY submitted (Single Entry Rule) -->
                                <div class="p-3 bg-light rounded-4 text-center border">
                                    <div class="p-2.5 bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                                        <i class="bi bi-check-lg fs-3"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Your Entry Is Active!</h6>
                                    <p class="text-secondary extra-small mb-3">
                                        Under Noksha's <strong>Single Entry Rule</strong>, you have submitted your designated concept for this tournament:
                                        @if($userEntry)
                                            <div class="p-2 bg-white rounded-3 border extra-small text-dark font-monospace mb-2 text-truncate">
                                                #{{ $userEntry->id }} - {{ $userEntry->title }}
                                            </div>
                                        @endif
                                    </p>
                                    @if($userEntry)
                                        <a href="#entry-{{ $userEntry->id }}" class="btn btn-outline-primary rounded-pill w-100 py-2 extra-small fw-bold">
                                            <i class="bi bi-eye me-1"></i> View My Submission
                                        </a>
                                    @endif
                                </div>
                            @else
                                <!-- Eligible Contributor Submission Form -->
                                <form action="{{ route('contests.submit', $contest->slug) }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <!-- Concept Title -->
                                    <div class="mb-3">
                                        <label class="form-label extra-small fw-bold text-uppercase text-muted">Concept Title *</label>
                                        <input type="text" name="title" class="form-control form-control-sm rounded-3 @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g. Modern Minimalist Vector Identity" required>
                                        @error('title')
                                            <div class="invalid-feedback extra-small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Clean Preview Image Upload -->
                                    <div class="mb-3">
                                        <label class="form-label extra-small fw-bold text-uppercase text-muted">Clean Artwork Preview *</label>
                                        <input type="file" name="clean_preview_image" class="form-control form-control-sm rounded-3 @error('clean_preview_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp" required>
                                        <div class="form-text extra-small text-muted">
                                            <i class="bi bi-shield-shaded text-primary me-1"></i> Upload your clean JPG/PNG (max 10MB). Noksha will automatically apply protective diagonal watermarks for public display.
                                        </div>
                                        @error('clean_preview_image')
                                            <div class="invalid-feedback extra-small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Source File Cloud Link -->
                                    <div class="mb-3">
                                        <label class="form-label extra-small fw-bold text-uppercase text-muted">Source Asset Link (Optional)</label>
                                        <input type="url" name="source_file_link" class="form-control form-control-sm rounded-3 @error('source_file_link') is-invalid @enderror" value="{{ old('source_file_link') }}" placeholder="https://figma.com/... or Google Drive link">
                                        <div class="form-text extra-small text-muted">
                                            Link to Figma, Adobe XD, AI, or drive folder for the client review.
                                        </div>
                                        @error('source_file_link')
                                            <div class="invalid-feedback extra-small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Concept Notes -->
                                    <div class="mb-3">
                                        <label class="form-label extra-small fw-bold text-uppercase text-muted">Concept Brief / Notes</label>
                                        <textarea name="description" rows="3" class="form-control form-control-sm rounded-3 @error('description') is-invalid @enderror" placeholder="Describe the design inspiration, fonts, or tools used...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback extra-small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Originality Confirmation Checkbox -->
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="agreement" id="contestAgreement" value="1" required>
                                        <label class="form-check-label extra-small text-muted" for="contestAgreement">
                                            I confirm this is <strong>100% my original artwork</strong> and adheres to Noksha's anti-plagiarism terms.
                                        </label>
                                    </div>

                                    <button type="submit" class="btn btn-purple-cta rounded-pill w-100 py-2.5 fw-bold">
                                        <i class="bi bi-send-fill me-1"></i> Submit Design Entry
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="alert alert-secondary rounded-4 small mb-0 text-center py-4">
                                <i class="bi bi-lock-fill text-muted fs-3 d-block mb-2"></i>
                                Submissions are closed. This contest is currently in the <strong>{{ ucfirst($contest->status) }}</strong> stage.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-light border rounded-4 small text-muted mb-0 text-center py-4">
                            <i class="bi bi-lock-fill text-primary fs-3 d-block mb-2"></i>
                            <div class="fw-bold text-dark mb-1">Sign in to participate</div>
                            <p class="extra-small text-muted mb-3">Verified contributors can enter contests with zero submission fees.</p>
                            <a href="{{ route('login') }}" class="btn btn-purple-cta rounded-pill px-4 py-2 extra-small fw-bold">
                                Sign In / Register
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- CONTEST RULES CARD -->
                <div class="card p-4 rounded-4 border-0 shadow-sm bg-white">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check text-primary me-1.5"></i>Contest Integrity Rules</h6>
                    <ul class="extra-small text-secondary ps-3 mb-0 space-y-2">
                        <li class="mb-2"><strong>Single Entry Rule:</strong> Each verified contributor may only submit one distinct concept per tournament.</li>
                        <li class="mb-2"><strong>Auto-Watermark Protection:</strong> All previews are stamped with diagonal safety seals to prevent asset theft.</li>
                        <li class="mb-2"><strong>Zero Plagiarism:</strong> Copied templates or stock art usage will result in immediate disqualification and KYC revocation.</li>
                        <li><strong>100% Escrow Guarantee:</strong> The organizer has pre-funded the ৳{{ number_format($contest->prize_amount, 0) }} bounty.</li>
                    </ul>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- LIGHTBOX MODAL -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden bg-dark">
            <div class="modal-header border-0 pb-0 text-white">
                <div>
                    <h5 class="modal-title fw-bold text-white mb-0" id="lightboxTitle">Design Preview</h5>
                    <span class="extra-small text-white text-opacity-75" id="lightboxAuthor">by Designer</span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <img id="lightboxImage" src="" alt="Enlarged Preview" class="img-fluid rounded-3" style="max-height: 80vh; object-fit: contain;">
                <div class="mt-2 text-white text-opacity-50 extra-small font-monospace">
                    Protected by Noksha Diagonal Preview Seal
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CLIENT RATING MODAL (ORGANIZER ACTION) -->
@if($isOrganizer)
<div class="modal fade" id="rateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-0">Client Rating & Feedback</h5>
                    <span class="extra-small text-muted" id="rateModalEntryTitle">Rate design submission</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rateModalForm" method="POST">
                @csrf
                <div class="modal-body py-3">
                    <input type="hidden" name="rating" id="ratingInput" value="5">

                    <!-- Interactive Star Rating -->
                    <label class="form-label extra-small fw-bold text-uppercase text-muted mb-2">Quality Rating (1 to 5 Stars)</label>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star-fill rating-star-interactive {{ $i <= 5 ? 'active' : '' }}" data-value="{{ $i }}" onclick="setStarRating({{ $i }})"></i>
                        @endfor
                        <span id="starRatingLabel" class="fw-bold extra-small text-warning ms-2 font-monospace">5.0 / 5</span>
                    </div>

                    <!-- Client Feedback Text -->
                    <label class="form-label extra-small fw-bold text-uppercase text-muted">Constructive Feedback to Contributor</label>
                    <textarea name="feedback" id="feedbackInput" rows="3" class="form-control rounded-3" placeholder="Provide feedback on color, typography, or alignment..."></textarea>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3 py-2 extra-small fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 py-2 extra-small fw-bold">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Rating
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- AWARD WINNER CONFIRMATION MODAL (ORGANIZER ACTION) -->
<div class="modal fade" id="awardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-3">🏆</span>
                    <h5 class="modal-title fw-bold text-dark mb-0">Award Contest Winner</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="awardModalForm" method="POST">
                @csrf
                <div class="modal-body py-3">
                    <p class="small text-secondary mb-3">
                        Are you sure you want to award the 1st Place bounty of 
                        <strong class="text-success fs-6 font-monospace">৳{{ number_format($contest->prize_amount, 0) }}</strong> to:
                    </p>
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="fw-bold text-dark fs-6" id="awardWinnerName">Contributor Name</div>
                        <div class="extra-small text-muted font-monospace" id="awardEntryTitle">Entry Title</div>
                    </div>
                    <div class="alert alert-warning border-0 rounded-3 extra-small mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> This decision will conclude the contest, lock further submissions, and transfer the escrow prize pool.
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3 py-2 extra-small fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 py-2 extra-small fw-bold">
                        <i class="bi bi-trophy-fill me-1"></i> Confirm & Award Prize
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- SCRIPTS FOR LIGHTBOX, LIKE, & MODALS -->
<script>
    function openLightbox(imageUrl, title, author) {
        document.getElementById('lightboxImage').src = imageUrl;
        document.getElementById('lightboxTitle').textContent = title;
        document.getElementById('lightboxAuthor').textContent = 'by ' + author;
        var modal = new bootstrap.Modal(document.getElementById('lightboxModal'));
        modal.show();
    }

    // AJAX Like Toggle
    function toggleEntryLike(entryId, btnElement) {
        fetch('/contests/entries/' + entryId + '/like', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                var counterSpan = btnElement.querySelector('.like-counter');
                var icon = btnElement.querySelector('i');
                counterSpan.textContent = data.likes_count;

                if (data.liked) {
                    btnElement.classList.add('liked');
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill');
                } else {
                    btnElement.classList.remove('liked');
                    icon.classList.remove('bi-heart-fill');
                    icon.classList.add('bi-heart');
                }
            }
        })
        .catch(err => console.error('Like toggle error:', err));
    }

    @if($isOrganizer)
    function openRateModal(entryId, title, currentRating, currentFeedback) {
        document.getElementById('rateModalEntryTitle').textContent = title;
        document.getElementById('feedbackInput').value = currentFeedback || '';
        setStarRating(currentRating > 0 ? currentRating : 5);
        document.getElementById('rateModalForm').action = '/contests/{{ $contest->slug }}/entries/' + entryId + '/rate';
        var modal = new bootstrap.Modal(document.getElementById('rateModal'));
        modal.show();
    }

    function setStarRating(rating) {
        document.getElementById('ratingInput').value = rating;
        document.getElementById('starRatingLabel').textContent = rating + '.0 / 5';
        var stars = document.querySelectorAll('.rating-star-interactive');
        stars.forEach(function(star) {
            var val = parseInt(star.getAttribute('data-value'));
            if (val <= rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    function confirmAwardWinner(entryId, title, creatorName) {
        document.getElementById('awardWinnerName').textContent = creatorName;
        document.getElementById('awardEntryTitle').textContent = title;
        document.getElementById('awardModalForm').action = '/contests/{{ $contest->slug }}/entries/' + entryId + '/award';
        var modal = new bootstrap.Modal(document.getElementById('awardModal'));
        modal.show();
    }
    @endif
</script>

@endsection
