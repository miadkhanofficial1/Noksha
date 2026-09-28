@extends('layouts.app')

@section('title', $user->name . ' (@' . $user->username . ') - Noksha Portfolio')

@section('content')
<style>
    /* Profile Cover Banner */
    .profile-cover-banner {
        height: 280px;
        background: linear-gradient(135deg, #6C4CF1 0%, #8B5CF6 50%, #9F7AEA 100%);
        position: relative;
        overflow: hidden;
    }

    .profile-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-cover-banner::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: 0.5;
        pointer-events: none;
    }

    /* Avatar & Online Availability */
    .profile-avatar-wrapper {
        position: relative;
        margin-top: -85px;
        display: inline-block;
    }

    .profile-avatar-img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 4px solid #ffffff;
        box-shadow: 0 15px 35px -10px rgba(108, 76, 241, 0.35);
        object-fit: cover;
        background: #F8F5FF;
        display: block;
    }

    .profile-avatar-initial {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 4px solid #ffffff;
        box-shadow: 0 15px 35px -10px rgba(108, 76, 241, 0.35);
        background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);
        color: #ffffff;
        font-size: 3.5rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-status-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 24px;
        height: 24px;
        border: 3px solid #ffffff;
        border-radius: 50%;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .status-available {
        background-color: #10B981;
    }

    .status-busy {
        background-color: #94A3B8;
    }

    /* Metric Badges Strip */
    .metric-chip {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #334155;
    }

    /* Showcase Tabs Nav */
    .showcase-tabs-nav {
        border-bottom: 2px solid #F1F5F9;
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 2px;
    }

    .showcase-tab-btn {
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 0.85rem 1.25rem;
        font-weight: 700;
        font-size: 0.925rem;
        color: #64748B;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
        text-decoration: none;
    }

    .showcase-tab-btn:hover {
        color: #7C3AED;
    }

    .showcase-tab-btn.active {
        color: #7C3AED;
        border-bottom-color: #7C3AED;
    }

    .showcase-tab-counter {
        background: #F1F5F9;
        color: #475569;
        border-radius: 999px;
        padding: 0.15rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 800;
    }

    .showcase-tab-btn.active .showcase-tab-counter {
        background: rgba(124, 58, 237, 0.12);
        color: #7C3AED;
    }

    /* Asset Grid Card */
    .asset-card {
        border-radius: 1.25rem;
        border: 1px solid rgba(226, 232, 240, 0.8);
        background: #ffffff;
        box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .asset-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 30px -8px rgba(124, 58, 237, 0.15);
        border-color: rgba(124, 58, 237, 0.3);
    }

    .asset-thumb-box {
        height: 190px;
        position: relative;
        background: #F8FAFC;
        overflow: hidden;
    }

    .asset-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
    }

    .asset-card:hover .asset-thumb-img {
        transform: scale(1.04);
    }

    /* Winning Entry Ribbon */
    .winner-gold-ribbon {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        color: #ffffff;
        font-weight: 800;
        font-size: 0.725rem;
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
        box-shadow: 0 4px 10px rgba(217, 119, 6, 0.35);
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* Skill Chip */
    .skill-badge-item {
        background: rgba(124, 58, 237, 0.07);
        color: #7C3AED;
        border: 1px solid rgba(124, 58, 237, 0.2);
        padding: 0.45rem 1.1rem;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s ease;
    }

    .skill-badge-item:hover {
        background: #7C3AED;
        color: #ffffff;
        transform: translateY(-2px);
    }

    /* Social Link Cards */
    .social-link-card {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 0.85rem;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #1E293B;
        text-decoration: none;
        transition: all 0.2s ease;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .social-link-card:hover {
        background: #FFFFFF;
        border-color: #7C3AED;
        color: #7C3AED;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px -3px rgba(124, 58, 237, 0.12);
    }

    /* Follow Button */
    .btn-follow-toggle {
        min-width: 120px;
        border-radius: 50rem;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .btn-purple-cta {
        background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);
        color: #FFFFFF !important;
        border: none;
        box-shadow: 0 4px 14px rgba(124, 58, 237, 0.3);
    }

    .btn-purple-cta:hover {
        box-shadow: 0 6px 18px rgba(124, 58, 237, 0.45);
        transform: translateY(-1px);
    }
</style>

<!-- HERO IDENTITY BANNER -->
<section class="profile-cover-banner">
    @if($user->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->cover_image))
        <img src="{{ asset('storage/' . $user->cover_image) }}" class="profile-cover-img" alt="{{ $user->name }} cover">
    @endif
    <div class="container h-100 position-relative d-flex align-items-end justify-content-end pb-3">
        @if($user->is_available)
            <span class="badge bg-white bg-opacity-25 text-white backdrop-blur border border-white border-opacity-30 rounded-pill px-3 py-1.5 extra-small fw-bold">
                <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Open for Custom Work
            </span>
        @endif
    </div>
</section>

<!-- HERO IDENTITY HEADER BAR -->
<section class="bg-white border-bottom pb-4 mb-4">
    <div class="container">
        <div class="row align-items-end g-4">
            
            <!-- Left: Avatar & Identity -->
            <div class="col-12 col-lg-7">
                <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-end gap-4 text-center text-sm-start">
                    
                    <!-- Avatar with Availability Indicator -->
                    <div class="profile-avatar-wrapper">
                        @if($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar))
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="profile-avatar-img">
                        @else
                            <div class="profile-avatar-initial">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="profile-status-badge {{ $user->is_available ? 'status-available' : 'status-busy' }}" title="{{ $user->is_available ? 'Available for Hire' : 'Busy / Not Taking Work' }}"></div>
                    </div>

                    <!-- Name, Handle & Headline -->
                    <div class="pb-1">
                        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-2 mb-1">
                            <h2 class="fw-extrabold text-dark mb-0">{{ $user->name }}</h2>
                            
                            @if($user->isContributor())
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                    <i class="bi bi-patch-check-fill me-1"></i> Verified Creator
                                </span>
                            @elseif($user->isAdmin())
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                    <i class="bi bi-shield-lock-fill me-1"></i> Admin
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                    Member
                                </span>
                            @endif

                            @if($totalWinsCount > 0)
                                <span class="winner-gold-ribbon">
                                    <i class="bi bi-trophy-fill"></i> {{ $totalWinsCount }} Contest {{ Str::plural('Win', $totalWinsCount) }}
                                </span>
                            @endif
                        </div>

                        <!-- Username Handle & Headline -->
                        <div class="text-muted small mb-2">
                            <span class="font-monospace text-primary fw-bold">&#64;{{ $user->username }}</span>
                            @if($user->headline)
                                <span class="mx-1.5">•</span>
                                <span class="fw-semibold text-dark">{{ $user->headline }}</span>
                            @endif
                        </div>

                        <!-- Trust Metrics Strip -->
                        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-2">
                            <div class="metric-chip">
                                <i class="bi bi-trophy-fill text-warning"></i>
                                <span class="fw-bold text-dark">{{ $totalWinsCount }}</span>
                                <span class="text-muted extra-small">Wins</span>
                            </div>

                            <div class="metric-chip">
                                <i class="bi bi-star-fill text-warning"></i>
                                <span class="fw-bold text-dark">{{ number_format($averageRating, 1) }}</span>
                                <span class="text-muted extra-small">({{ $totalReviewsCount }} reviews)</span>
                            </div>

                            <div class="metric-chip">
                                <i class="bi bi-collection-fill text-primary"></i>
                                <span class="fw-bold text-dark">{{ $totalAssetsCount }}</span>
                                <span class="text-muted extra-small">Assets</span>
                            </div>

                            <div class="metric-chip">
                                <i class="bi bi-people-fill text-info"></i>
                                <span class="fw-bold text-dark" id="followersCountVal">{{ $followersCount }}</span>
                                <span class="text-muted extra-small">Followers</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right: Action Buttons -->
            <div class="col-12 col-lg-5 text-center text-lg-end">
                <div class="d-inline-flex flex-wrap align-items-center justify-content-center justify-content-lg-end gap-2 pb-1">
                    @if($isOwnProfile)
                        <a href="{{ route('settings.profile') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2">
                            <i class="bi bi-gear-fill"></i>
                            <span>Edit Profile</span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2">
                            <i class="bi bi-speedometer2"></i>
                            <span>My Dashboard</span>
                        </a>
                    @else
                        <!-- Follow Toggle Button -->
                        @auth
                            <button type="button" class="btn btn-follow-toggle {{ $isFollowing ? 'btn-outline-primary active' : 'btn-purple-cta' }} px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2" id="followBtn" onclick="toggleFollow('{{ $user->username }}')">
                                <i class="bi {{ $isFollowing ? 'bi-check2' : 'bi-plus-lg' }}" id="followBtnIcon"></i>
                                <span id="followBtnText">{{ $isFollowing ? 'Following' : 'Follow' }}</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-purple-cta btn-follow-toggle px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2">
                                <i class="bi bi-plus-lg"></i>
                                <span>Follow</span>
                            </a>
                        @endauth

                        <!-- Contact / Hire Button -->
                        <button type="button" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#contactModal">
                            <i class="bi bi-chat-dots-fill text-primary"></i>
                            <span>Contact & Hire</span>
                        </button>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>

<!-- MAIN SHOWCASE BODY -->
<div class="container pb-5">
    
    <!-- TABS NAVIGATION -->
    <div class="showcase-tabs-nav mb-4" id="profileTabsNav" role="tablist">
        <!-- Tab 1: Store Assets -->
        <button class="showcase-tab-btn active" id="tab-assets-btn" data-bs-toggle="tab" data-bs-target="#tab-assets" type="button" role="tab" aria-controls="tab-assets" aria-selected="true">
            <i class="bi bi-grid-3x3-gap-fill"></i>
            <span>Assets & Templates</span>
            <span class="showcase-tab-counter">{{ $totalAssetsCount }}</span>
        </button>

        <!-- Tab 2: Contest Wins & Submissions -->
        <button class="showcase-tab-btn" id="tab-contests-btn" data-bs-toggle="tab" data-bs-target="#tab-contests" type="button" role="tab" aria-controls="tab-contests" aria-selected="false">
            <i class="bi bi-trophy-fill text-warning"></i>
            <span>Contest Showcase</span>
            <span class="showcase-tab-counter">{{ $totalWinsCount + $contestEntries->total() }}</span>
        </button>

        <!-- Tab 3: Client Reviews -->
        <button class="showcase-tab-btn" id="tab-reviews-btn" data-bs-toggle="tab" data-bs-target="#tab-reviews" type="button" role="tab" aria-controls="tab-reviews" aria-selected="false">
            <i class="bi bi-star-fill text-warning"></i>
            <span>Client Reviews</span>
            <span class="showcase-tab-counter">{{ $totalReviewsCount }}</span>
        </button>

        <!-- Tab 4: About & Skills -->
        <button class="showcase-tab-btn" id="tab-about-btn" data-bs-toggle="tab" data-bs-target="#tab-about" type="button" role="tab" aria-controls="tab-about" aria-selected="false">
            <i class="bi bi-person-lines-fill"></i>
            <span>About & Skills</span>
        </button>

        <!-- Tab 5: Launched Contests (If Buyer/Organized) -->
        @if($organizedContests->count() > 0)
            <button class="showcase-tab-btn" id="tab-launched-btn" data-bs-toggle="tab" data-bs-target="#tab-launched" type="button" role="tab" aria-controls="tab-launched" aria-selected="false">
                <i class="bi bi-award"></i>
                <span>Launched Challenges</span>
                <span class="showcase-tab-counter">{{ $organizedContests->count() }}</span>
            </button>
        @endif
    </div>

    <!-- TABS CONTENT -->
    <div class="tab-content" id="profileTabsContent">

        <!-- ============================================== -->
        <!-- TAB 1: ASSETS / STORE TEMPLATES -->
        <!-- ============================================== -->
        <div class="tab-pane fade show active" id="tab-assets" role="tabpanel" aria-labelledby="tab-assets-btn">
            @if($assets->count() > 0)
                <div class="row g-4">
                    @foreach($assets as $asset)
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                            <div class="asset-card">
                                <a href="{{ route('resource.show', $asset->slug) }}" class="asset-thumb-box d-block text-decoration-none">
                                    @if($asset->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($asset->thumbnail))
                                        <img src="{{ asset('storage/' . $asset->thumbnail) }}" alt="{{ $asset->title }}" class="asset-thumb-img">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary">
                                            <i class="bi bi-file-earmark-image fs-1"></i>
                                        </div>
                                    @endif

                                    <!-- Category Pill -->
                                    <div class="position-absolute top-0 start-0 m-2.5">
                                        <span class="badge bg-white bg-opacity-90 text-dark rounded-pill px-2.5 py-1 extra-small fw-bold shadow-sm backdrop-blur">
                                            {{ $asset->category->name ?? 'Design' }}
                                        </span>
                                    </div>
                                </a>

                                <div class="p-3 d-flex flex-column flex-grow-1 justify-content-between">
                                    <div>
                                        <h6 class="fw-bold text-dark text-truncate mb-1" title="{{ $asset->title }}">
                                            <a href="{{ route('resource.show', $asset->slug) }}" class="text-dark text-decoration-none hover-primary">
                                                {{ $asset->title }}
                                            </a>
                                        </h6>
                                        <div class="extra-small text-muted mb-2">
                                            <i class="bi bi-star-fill text-warning me-1"></i>
                                            <span class="fw-bold text-dark">{{ number_format($asset->reviews_avg_rating ?? 5.0, 1) }}</span>
                                            <span>({{ $asset->reviews_count }} ratings)</span>
                                        </div>
                                    </div>

                                    <div class="pt-2 border-top d-flex align-items-center justify-content-between">
                                        <div>
                                            @if($asset->is_free || $asset->price <= 0)
                                                <span class="badge bg-success bg-opacity-15 text-success fw-bold rounded-pill px-2.5 py-1 extra-small">Free</span>
                                            @else
                                                <span class="fw-extrabold text-dark fs-6 font-monospace">৳{{ number_format($asset->price, 0) }}</span>
                                            @endif
                                        </div>

                                        <a href="{{ route('resource.show', $asset->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold extra-small">
                                            View Asset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $assets->links() }}
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-3 bg-white">
                    <div class="p-4 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px; margin: 0 auto;">
                        <i class="bi bi-collection fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">No Published Assets Yet</h5>
                    <p class="text-secondary small mb-3" style="max-width: 420px; margin: 0 auto;">
                        This creator hasn't published downloadable assets to the marketplace yet. Check back soon or explore their contest entries!
                    </p>
                </div>
            @endif
        </div>

        <!-- ============================================== -->
        <!-- TAB 2: CONTEST WINS & ENTRIES -->
        <!-- ============================================== -->
        <div class="tab-pane fade" id="tab-contests" role="tabpanel" aria-labelledby="tab-contests-btn">
            
            <!-- SECTION 2A: WINNING ENTRIES -->
            @if($contestWins->count() > 0)
                <div class="mb-5">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-trophy-fill text-warning fs-4"></i>
                        <h5 class="fw-extrabold text-dark mb-0">Winning Challenge Submissions ({{ $totalWinsCount }})</h5>
                    </div>

                    <div class="row g-4">
                        @foreach($contestWins as $win)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white" style="border: 2px solid #F59E0B !important;">
                                    <div class="position-relative" style="height: 220px; background: #0F172A;">
                                        @if($win->watermarked_preview_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($win->watermarked_preview_image))
                                            <img src="{{ asset('storage/' . $win->watermarked_preview_image) }}" alt="Winning Entry" class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white-50">
                                                <i class="bi bi-image fs-1"></i>
                                            </div>
                                        @endif

                                        <!-- Winner Badge Overlay -->
                                        <div class="position-absolute top-0 start-0 m-3">
                                            <span class="winner-gold-ribbon">
                                                <i class="bi bi-trophy-fill"></i> Contest Winner
                                            </span>
                                        </div>

                                        @if($win->contest)
                                            <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(to top, rgba(15,23,42,0.9), transparent);">
                                                <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                    Prize ৳{{ number_format($win->contest->prize_amount, 0) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-3">
                                        <h6 class="fw-bold text-dark text-truncate mb-1">
                                            @if($win->contest)
                                                <a href="{{ route('contests.show', $win->contest->slug) }}" class="text-dark text-decoration-none hover-primary">
                                                    {{ $win->contest->title }}
                                                </a>
                                            @else
                                                <span>{{ $win->title ?? 'Contest Winning Design' }}</span>
                                            @endif
                                        </h6>

                                        @if($win->client_rating)
                                            <div class="d-flex align-items-center gap-1 extra-small mb-2">
                                                <span class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi {{ $i <= $win->client_rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                    @endfor
                                                </span>
                                                <span class="fw-bold text-dark font-monospace">{{ $win->client_rating }}.0</span>
                                                <span class="text-muted">• Buyer Rating</span>
                                            </div>
                                        @endif

                                        @if($win->client_feedback)
                                            <p class="text-secondary extra-small fst-italic mb-2 p-2 bg-light rounded-3">
                                                "{{ Str::limit($win->client_feedback, 120) }}"
                                            </p>
                                        @endif

                                        @if($win->contest)
                                            <div class="text-end pt-2 border-top">
                                                <a href="{{ route('contests.show', $win->contest->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 extra-small fw-bold">
                                                    View Challenge
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- SECTION 2B: ALL CONTEST SUBMISSIONS -->
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold text-dark mb-0">Contest Entries Gallery</h5>
                    <span class="extra-small text-muted font-monospace">{{ $contestEntries->total() }} Designs Submitted</span>
                </div>

                @if($contestEntries->count() > 0)
                    <div class="row g-3">
                        @foreach($contestEntries as $entry)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white">
                                    <div class="position-relative" style="height: 180px; background: #F8FAFC;">
                                        @if($entry->watermarked_preview_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($entry->watermarked_preview_image))
                                            <img src="{{ asset('storage/' . $entry->watermarked_preview_image) }}" alt="Entry" class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                <i class="bi bi-image fs-2"></i>
                                            </div>
                                        @endif

                                        @if($entry->is_winner)
                                            <div class="position-absolute top-0 start-0 m-2">
                                                <span class="badge bg-warning text-dark fw-bold rounded-pill px-2 py-1 extra-small">
                                                    🏆 Winner
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-2.5">
                                        @if($entry->contest)
                                            <div class="extra-small fw-bold text-truncate text-dark mb-1">
                                                <a href="{{ route('contests.show', $entry->contest->slug) }}" class="text-dark text-decoration-none hover-primary">
                                                    {{ $entry->contest->title }}
                                                </a>
                                            </div>
                                            <div class="extra-small text-muted font-monospace">
                                                Prize: ৳{{ number_format($entry->contest->prize_amount, 0) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 d-flex justify-content-center">
                        {{ $contestEntries->links() }}
                    </div>
                @else
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                        <p class="text-secondary small mb-0">No public design entries submitted yet.</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- ============================================== -->
        <!-- TAB 3: CLIENT REVIEWS -->
        <!-- ============================================== -->
        <div class="tab-pane fade" id="tab-reviews" role="tabpanel" aria-labelledby="tab-reviews-btn">
            
            <!-- Summary Rating Box -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="row align-items-center g-3">
                    <div class="col-auto text-center border-end pe-4">
                        <div class="display-5 fw-extrabold text-dark font-monospace mb-0">{{ number_format($averageRating, 1) }}</div>
                        <div class="text-warning small mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= round($averageRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>
                        <div class="extra-small text-muted fw-bold">Overall Rating</div>
                    </div>

                    <div class="col">
                        <h6 class="fw-bold text-dark mb-1">Client & Buyer Satisfaction</h6>
                        <p class="text-secondary small mb-0">
                            Based on {{ $totalReviewsCount }} verified customer reviews across Noksha digital assets and contest handovers.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Reviews Listing -->
            @if($reviews->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($reviews as $rev)
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-2">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="avatar-placeholder rounded-circle bg-primary bg-opacity-10 text-primary fw-bold extra-small d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($rev->user->name ?? 'Buyer', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small mb-0">{{ $rev->user->name ?? 'Verified Buyer' }}</div>
                                        <div class="extra-small text-muted">{{ $rev->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>

                                <div class="text-warning small">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= ($rev->rating ?? 5) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                </div>
                            </div>

                            <p class="text-secondary small mb-2">
                                "{{ $rev->review }}"
                            </p>

                            @if($rev->resource)
                                <div class="extra-small text-muted font-monospace border-top pt-2">
                                    <i class="bi bi-box-seam me-1 text-primary"></i>
                                    <a href="{{ route('resource.show', $rev->resource->slug) }}" class="text-decoration-none text-muted">
                                        {{ $rev->resource->title }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                    <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; margin: 0 auto;">
                        <i class="bi bi-chat-quote fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">No Customer Reviews Yet</h5>
                    <p class="text-secondary small mb-0">Client feedback and verified reviews will populate as orders and contest handovers are finalized.</p>
                </div>
            @endif

        </div>

        <!-- ============================================== -->
        <!-- TAB 4: ABOUT & SKILLS -->
        <!-- ============================================== -->
        <div class="tab-pane fade" id="tab-about" role="tabpanel" aria-labelledby="tab-about-btn">
            <div class="row g-4">
                
                <!-- Left: Bio & Skills -->
                <div class="col-12 col-lg-8">
                    <!-- Bio Card -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
                        <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-person-badge text-primary"></i>
                            <span>About the Designer</span>
                        </h5>

                        <div class="text-secondary lh-lg mb-0" style="white-space: pre-line;">
                            {{ $user->bio ?? 'This creator has not added a detailed biography yet. Reach out directly for project inquiries and design collaborations!' }}
                        </div>
                    </div>

                    <!-- Skills & Creative Tools -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                        <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-tools text-primary"></i>
                            <span>Core Competencies & Tools</span>
                        </h5>

                        @php
                            $skillsList = $user->skills ?? [];
                            if (is_string($skillsList)) {
                                $skillsList = array_filter(array_map('trim', explode(',', $skillsList)));
                            }
                        @endphp

                        @if(!empty($skillsList))
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($skillsList as $sk)
                                    <span class="skill-badge-item">
                                        <i class="bi bi-check-circle-fill fs-6"></i>
                                        <span>{{ $sk }}</span>
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted small mb-0">No specific skills listed yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Right: External Presence & Trust Card -->
                <div class="col-12 col-lg-4">
                    
                    <!-- Social & Portfolio Presence -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-globe2 text-primary"></i>
                            <span>Online Presence</span>
                        </h6>

                        @php
                            $links = $user->social_links ?? [];
                        @endphp

                        <div class="d-flex flex-column gap-2">
                            @if(!empty($links['behance']))
                                <a href="{{ $links['behance'] }}" target="_blank" rel="noopener noreferrer" class="social-link-card">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-behance text-primary fs-5"></i>
                                        <span>Behance</span>
                                    </div>
                                    <i class="bi bi-box-arrow-up-right extra-small text-muted"></i>
                                </a>
                            @endif

                            @if(!empty($links['dribbble']))
                                <a href="{{ $links['dribbble'] }}" target="_blank" rel="noopener noreferrer" class="social-link-card">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-dribbble text-danger fs-5"></i>
                                        <span>Dribbble</span>
                                    </div>
                                    <i class="bi bi-box-arrow-up-right extra-small text-muted"></i>
                                </a>
                            @endif

                            @if(!empty($links['website']))
                                <a href="{{ $links['website'] }}" target="_blank" rel="noopener noreferrer" class="social-link-card">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-globe fs-5 text-secondary"></i>
                                        <span>Studio Website</span>
                                    </div>
                                    <i class="bi bi-box-arrow-up-right extra-small text-muted"></i>
                                </a>
                            @endif

                            @if(!empty($links['linkedin']))
                                <a href="{{ $links['linkedin'] }}" target="_blank" rel="noopener noreferrer" class="social-link-card">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-linkedin text-info fs-5"></i>
                                        <span>LinkedIn</span>
                                    </div>
                                    <i class="bi bi-box-arrow-up-right extra-small text-muted"></i>
                                </a>
                            @endif

                            @if(!empty($links['twitter']))
                                <a href="{{ $links['twitter'] }}" target="_blank" rel="noopener noreferrer" class="social-link-card">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-twitter-x text-dark fs-5"></i>
                                        <span>Twitter / X</span>
                                    </div>
                                    <i class="bi bi-box-arrow-up-right extra-small text-muted"></i>
                                </a>
                            @endif

                            @if(!empty($links['github']))
                                <a href="{{ $links['github'] }}" target="_blank" rel="noopener noreferrer" class="social-link-card">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-github text-dark fs-5"></i>
                                        <span>GitHub</span>
                                    </div>
                                    <i class="bi bi-box-arrow-up-right extra-small text-muted"></i>
                                </a>
                            @endif

                            @if(empty(array_filter($links)))
                                <p class="text-muted extra-small mb-0">No external portfolio links provided.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Creator Trust & Verification Summary -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-success"></i>
                            <span>Verified Badges & Status</span>
                        </h6>

                        <div class="d-flex flex-column gap-2.5 small">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted">Account Status:</span>
                                <span class="fw-bold text-dark">{{ $user->getRoleBadgeLabel() }}</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted">Trust Score:</span>
                                <span class="fw-extrabold text-success font-monospace">{{ number_format($user->trust_score ?? 100, 1) }}%</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted">Freelance Availability:</span>
                                @if($user->is_available)
                                    <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-2.5 py-1 extra-small fw-bold">Available</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-15 text-secondary rounded-pill px-2.5 py-1 extra-small fw-bold">Busy</span>
                                @endif
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted">Member Since:</span>
                                <span class="fw-semibold text-dark font-monospace">{{ $user->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 5: LAUNCHED CONTESTS (FOR BUYERS) -->
        <!-- ============================================== -->
        @if($organizedContests->count() > 0)
            <div class="tab-pane fade" id="tab-launched" role="tabpanel" aria-labelledby="tab-launched-btn">
                <div class="row g-4">
                    @foreach($organizedContests as $c)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1 extra-small fw-bold">
                                            {{ $c->display_category }}
                                        </span>
                                        <span class="badge bg-light text-secondary rounded-pill px-2.5 py-1 extra-small font-monospace text-uppercase">
                                            {{ $c->status }}
                                        </span>
                                    </div>

                                    <h6 class="fw-bold text-dark mb-2 text-truncate" title="{{ $c->title }}">
                                        <a href="{{ route('contests.show', $c->slug) }}" class="text-dark text-decoration-none hover-primary">
                                            {{ $c->title }}
                                        </a>
                                    </h6>
                                    <p class="text-secondary extra-small mb-3 line-clamp-2">
                                        {{ Str::limit($c->description, 100) }}
                                    </p>
                                </div>

                                <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="extra-small text-muted text-uppercase fw-bold">Prize Bounty</div>
                                        <div class="fw-extrabold text-success font-monospace">৳{{ number_format($c->prize_amount, 0) }}</div>
                                    </div>
                                    <a href="{{ route('contests.show', $c->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 extra-small fw-bold">
                                        View Challenge
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

<!-- CONTACT / HIRE MODAL -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="contactModalLabel">
                    <i class="bi bi-chat-quote-fill text-primary me-2"></i>Contact {{ $user->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body px-4 pt-3 pb-4">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 mb-3">
                    <div class="profile-avatar-wrapper m-0">
                        @if($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar))
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold fs-5 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="fw-bold text-dark">{{ $user->name }}</div>
                        <div class="extra-small text-muted font-monospace">&#64;{{ $user->username }}</div>
                        <div class="extra-small {{ $user->is_available ? 'text-success' : 'text-secondary' }} fw-bold">
                            <i class="bi bi-record-fill me-1"></i>{{ $user->is_available ? 'Available for Custom Projects' : 'Currently Busy' }}
                        </div>
                    </div>
                </div>

                <p class="text-secondary small mb-4">
                    Send an email inquiry directly to {{ $user->name }} regarding freelance design services, custom template requests, or design collaboration.
                </p>

                <div class="d-grid gap-2">
                    <a href="mailto:{{ $user->email }}?subject=Project%20Inquiry%20from%20Noksha%20Community" class="btn btn-purple-cta rounded-pill py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-envelope-fill"></i>
                        <span>Send Email to {{ $user->email }}</span>
                    </a>

                    @if($user->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-success rounded-pill py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-whatsapp"></i>
                            <span>Chat on WhatsApp</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AJAX Follow Script -->
<script>
    function toggleFollow(username) {
        const btn = document.getElementById('followBtn');
        const icon = document.getElementById('followBtnIcon');
        const text = document.getElementById('followBtnText');
        const countDisplay = document.getElementById('followersCountVal');

        if (!btn) return;
        btn.disabled = true;

        fetch(`/u/${username}/follow`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.status === 401) {
                window.location.href = "{{ route('login') }}";
                return;
            }
            return response.json();
        })
        .then(data => {
            btn.disabled = false;
            if (data && data.success) {
                if (data.is_following) {
                    btn.className = 'btn btn-follow-toggle btn-outline-primary active px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2';
                    icon.className = 'bi bi-check2';
                    text.textContent = 'Following';
                } else {
                    btn.className = 'btn btn-follow-toggle btn-purple-cta px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2';
                    icon.className = 'bi bi-plus-lg';
                    text.textContent = 'Follow';
                }
                if (countDisplay && typeof data.followers_count !== 'undefined') {
                    countDisplay.textContent = data.followers_count;
                }
            }
        })
        .catch(err => {
            btn.disabled = false;
            console.error('Follow request error:', err);
        });
    }
</script>
@endsection
