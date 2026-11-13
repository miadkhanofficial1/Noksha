@extends('layouts.app')

@section('title', $user->name . ' (@' . $user->username . ') - Noksha Portfolio')

@section('content')
<style>
    /* Profile Cover Banner */
    .profile-cover-banner {
        height: 280px;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
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
        opacity: 0.35;
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
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.6);
        object-fit: cover;
        background: #ffffff;
        display: block;
    }

    .profile-avatar-initial {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 4px solid #ffffff;
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.6);
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #ffffff;
        font-size: 3rem;
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
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
    }

    .status-available {
        background-color: #10B981;
    }

    .status-busy {
        background-color: #94A3B8;
    }

    /* Metric Badges Strip */
    .metric-chip {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #1e293b;
    }

    /* Showcase Tabs Nav */
    .showcase-tabs-nav {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
        color: #64748b;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
        text-decoration: none;
    }

    .showcase-tab-btn:hover {
        color: #20165c;
    }

    .showcase-tab-btn.active {
        color: #20165c;
        border-bottom-color: #20165c;
    }

    .showcase-tab-counter {
        background: rgba(255, 255, 255, 0.08);
        color: #cbd5e1;
        border-radius: 999px;
        padding: 0.15rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 800;
    }

    .showcase-tab-btn.active .showcase-tab-counter {
        background: rgba(99, 102, 241, 0.2);
        color: #20165c;
    }

    /* Asset Grid Card */
    .asset-card {
        border-radius: 1.25rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.4);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .asset-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 30px -8px rgba(99, 102, 241, 0.25);
        border-color: rgba(99, 102, 241, 0.4);
    }

    .asset-thumb-box {
        height: 190px;
        position: relative;
        background: #f8fafc;
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
        background: rgba(99, 102, 241, 0.15);
        color: #20165c;
        border: 1px solid rgba(99, 102, 241, 0.3);
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
        background: #6366f1;
        color: #ffffff;
        transform: translateY(-2px);
    }

    /* Social Link Cards */
    .social-link-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.85rem;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #1e293b;
        text-decoration: none;
        transition: all 0.2s ease;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .social-link-card:hover {
        background: #131d36;
        border-color: #6366f1;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px -3px rgba(99, 102, 241, 0.2);
    }

    /* Follow Button */
    .btn-follow-toggle {
        min-width: 120px;
        border-radius: 50rem;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .btn-purple-cta {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #FFFFFF !important;
        border: none;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
    }

    .btn-purple-cta:hover {
        box-shadow: 0 6px 18px rgba(79, 70, 229, 0.45);
        transform: translateY(-1px);
    }
</style>

<div class="min-h-screen bg-[#ffffff] text-slate-800">

    <!-- HERO IDENTITY BANNER -->
    @php
        $coverUrl = $user && $user->cover_image ? (str_starts_with($user->cover_image, 'http') ? $user->cover_image : asset('storage/' . ltrim(str_replace('storage/', '', $user->cover_image), '/'))) : null;
        $avatarUrl = $user && $user->avatar ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . ltrim(str_replace('storage/', '', $user->avatar), '/'))) : null;
    @endphp
    <section class="profile-cover-banner" style="background-image: url('{{ $coverUrl }}'); background-size: cover; background-position: center; background-color: #111a33;">
        @if($coverUrl)
            <img src="{{ $coverUrl }}" alt="{{ $user->name }} cover" class="w-100 h-100 object-fit-cover">
        @endif
        <div class="container h-100 position-relative d-flex align-items-end justify-content-end pb-3">
            @if($user->is_available)
                <span class="badge bg-black/40 text-amber-300 backdrop-blur border border-amber-500/30 rounded-pill px-3 py-1.5 extra-small fw-bold">
                    <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Open for Custom Work
                </span>
            @endif
        </div>
    </section>

    <!-- HERO IDENTITY HEADER BAR -->
    <section class="bg-white border-b border-slate-200/80 pb-6 mb-8 text-slate-800">
        <div class="container">
            <div class="row align-items-end g-4">
                
                <!-- Left: Avatar & Identity -->
                <div class="col-12 col-lg-7">
                    <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-end gap-4 text-center text-sm-start">
                        
                        <!-- Avatar with Availability Indicator -->
                        <div class="profile-avatar-wrapper">
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" 
                                     alt="{{ $user->name }}" 
                                     class="profile-avatar-img ring-4 ring-[#0b1329] rounded-full object-cover"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'profile-avatar-initial ring-4 ring-[#0b1329]\'>{{ strtoupper(substr($user->name, 0, 2)) }}</div>';">
                            @else
                                <div class="profile-avatar-initial ring-4 ring-[#0b1329]">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif

                            <div class="profile-status-badge {{ $user->is_available ? 'status-available' : 'status-busy' }}" title="{{ $user->is_available ? 'Available for Hire' : 'Busy / Not Taking Work' }}"></div>
                        </div>

                        <!-- Name, Handle & Headline -->
                        <div class="pb-1">
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-2 mb-1">
                                <h2 class="fw-extrabold text-[#121826] mb-0">{{ $user->name }}</h2>
                                
                                @if($user->isContributor())
                                    <span class="badge bg-emerald-950/40 text-[#f47402] border border-emerald-500/30 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                        <i class="bi bi-patch-check-fill me-1 text-[#f47402]"></i> Verified Creator
                                    </span>
                                @elseif($user->isAdmin())
                                    <span class="badge bg-purple-950/40 text-purple-300 border border-purple-500/30 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                        <i class="bi bi-shield-lock-fill me-1 text-purple-400"></i> Admin
                                    </span>
                                @else
                                    <span class="badge bg-gray-800 text-slate-700 border border-gray-700 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                        Member
                                    </span>
                                @endif

                                @if($user->hasReliabilityWarning())
                                    <span class="badge bg-amber-500/15 text-amber-700 border border-amber-300 rounded-pill px-2.5 py-1 extra-small fw-bold" title="Reliability Warning: Accrued demerits or late deliveries">
                                        <i class="bi bi-exclamation-triangle-fill me-1 text-amber-600"></i> Reliability Warning
                                    </span>
                                @endif

                                @if($totalWinsCount > 0)
                                    <span class="winner-gold-ribbon">
                                        <i class="bi bi-trophy-fill"></i> {{ $totalWinsCount }} Contest {{ Str::plural('Win', $totalWinsCount) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Username Handle & Headline -->
                            <div class="text-slate-500 small mb-2">
                                <span class="font-monospace text-indigo-400 fw-bold">&#64;{{ $user->username }}</span>
                                @if($user->headline)
                                    <span class="mx-1.5">•</span>
                                    <span class="fw-semibold text-slate-800">{{ $user->headline }}</span>
                                @endif
                            </div>

                            <!-- Trust Metrics Strip -->
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-2">
                                <div class="metric-chip">
                                    <i class="bi bi-trophy-fill text-warning"></i>
                                    <span class="fw-bold text-[#121826]">{{ $totalWinsCount }}</span>
                                    <span class="text-slate-500 extra-small">Wins</span>
                                </div>

                                <div class="metric-chip">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <span class="fw-bold text-[#121826]">{{ number_format($averageRating, 1) }}</span>
                                    <span class="text-slate-500 extra-small">({{ $totalReviewsCount }} reviews)</span>
                                </div>

                                <div class="metric-chip">
                                    <i class="bi bi-collection-fill text-indigo-400"></i>
                                    <span class="fw-bold text-[#121826]">{{ $totalAssetsCount }}</span>
                                    <span class="text-slate-500 extra-small">Assets</span>
                                </div>

                                <div class="metric-chip">
                                    <i class="bi bi-people-fill text-sky-400"></i>
                                    <span class="fw-bold text-[#121826]" id="followersCountVal">{{ $followersCount }}</span>
                                    <span class="text-slate-500 extra-small">Followers</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Right: Action Buttons -->
                <div class="col-12 col-lg-5 text-center text-lg-end">
                    <div class="d-inline-flex flex-wrap align-items-center justify-content-center justify-content-lg-end gap-2 pb-1">
                        @if($isOwnProfile)
                            <a href="{{ route('settings.profile') }}" class="btn btn-outline-dark text-slate-700 border-gray-700 hover:bg-gray-800 hover:text-[#121826] rounded-pill px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2">
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
                                <button type="button" class="btn btn-follow-toggle {{ $isFollowing ? 'btn-success text-[#121826]' : 'btn-purple-cta' }} px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2" id="followBtn" onclick="toggleFollow('{{ $user->username }}')">
                                    <i class="bi {{ $isFollowing ? 'bi-check2' : 'bi-plus-lg' }}" id="followBtnIcon"></i>
                                    <span id="followBtnText">{{ $isFollowing ? 'Following' : 'Follow' }}</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-purple-cta btn-follow-toggle px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2">
                                    <i class="bi bi-plus-lg"></i>
                                    <span>Follow</span>
                                </a>
                            @endauth

                            <!-- Send Message Button -->
                            <a href="{{ route('inbox.show', $user->id) }}" class="btn btn-outline-dark text-slate-700 border-gray-700 hover:bg-gray-800 hover:text-[#121826] rounded-pill px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2">
                                <i class="bi bi-chat-dots-fill text-indigo-400"></i>
                                <span>Send Message</span>
                            </a>
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

            <!-- Tab: Creator Services -->
            <button class="showcase-tab-btn" id="tab-services-btn" data-bs-toggle="tab" data-bs-target="#tab-services" type="button" role="tab" aria-controls="tab-services" aria-selected="false">
                <i class="bi bi-briefcase-fill text-[#20165c]"></i>
                <span>Services</span>
                <span class="showcase-tab-counter">{{ $servicesCount ?? 0 }}</span>
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
                                        @if($asset->thumbnail_url)
                                            <img src="{{ $asset->thumbnail_url }}" alt="{{ $asset->title }}" class="asset-thumb-img" onerror="this.src='{{ asset('images/logo.png') }}'">
                                        @elseif($asset->thumbnail)
                                            <img src="{{ asset('storage/' . ltrim(str_replace('storage/', '', $asset->thumbnail), '/')) }}" alt="{{ $asset->title }}" class="asset-thumb-img" onerror="this.src='{{ asset('images/logo.png') }}'">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-indigo-950/30 text-indigo-400">
                                                <i class="bi bi-file-earmark-image fs-1"></i>
                                            </div>
                                        @endif

                                        <!-- Category Pill -->
                                        <div class="position-absolute top-0 start-0 m-2.5">
                                            <span class="badge bg-white/80 text-slate-800 border border-gray-700/60 rounded-pill px-2.5 py-1 extra-small fw-bold shadow-sm backdrop-blur">
                                                {{ $asset->category->name ?? 'Design' }}
                                            </span>
                                        </div>
                                    </a>

                                    <div class="p-3.5 d-flex flex-column flex-grow-1 justify-content-between">
                                        <div>
                                            <h6 class="fw-bold text-[#121826] text-truncate mb-1" title="{{ $asset->title }}">
                                                <a href="{{ route('resource.show', $asset->slug) }}" class="text-[#121826] text-decoration-none hover-primary">
                                                    {{ $asset->title }}
                                                </a>
                                            </h6>
                                            <div class="extra-small text-slate-500 mb-2">
                                                <i class="bi bi-star-fill text-warning me-1"></i>
                                                <span class="fw-bold text-[#121826]">{{ number_format($asset->reviews_avg_rating ?? 5.0, 1) }}</span>
                                                <span>({{ $asset->reviews_count }} ratings)</span>
                                            </div>
                                        </div>

                                        <div class="pt-3 border-t border-slate-200 d-flex align-items-center justify-content-between">
                                            <div>
                                                @if($asset->is_free || $asset->price <= 0)
                                                    <span class="badge bg-emerald-500/10 text-[#f47402] border border-emerald-500/20 fw-bold rounded-pill px-2.5 py-1 extra-small">Free</span>
                                                @else
                                                    <span class="fw-extrabold text-[#121826] fs-6 font-monospace">৳{{ number_format($asset->price, 0) }}</span>
                                                @endif
                                            </div>

                                            <a href="{{ route('resource.show', $asset->slug) }}" class="btn btn-sm btn-purple-cta rounded-pill px-3 py-1 fw-bold extra-small">
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
                    <div class="card border border-slate-200 rounded-4 p-5 text-center my-3 bg-white">
                        <div class="p-4 bg-indigo-600/10 text-indigo-400 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px; margin: 0 auto;">
                            <i class="bi bi-collection fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-[#121826] mb-1">No Published Assets Yet</h5>
                        <p class="text-slate-500 small mb-3" style="max-width: 420px; margin: 0 auto;">
                            This creator hasn't published downloadable assets to the marketplace yet. Check back soon or explore their contest entries!
                        </p>
                    </div>
                @endif
            </div>

            <!-- ============================================== -->
            <!-- TAB: CREATOR SERVICES                          -->
            <!-- ============================================== -->
            <div class="tab-pane fade" id="tab-services" role="tabpanel" aria-labelledby="tab-services-btn">
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                    <div>
                        <h4 class="fw-bold text-[#121826] mb-1">Custom Creative Services</h4>
                        <p class="text-slate-500 small mb-0">Hire {{ $user->name }} for bespoke, milestone-protected projects</p>
                    </div>

                    @if($isOwnProfile)
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-purple-50 text-[#20165c] border border-purple-200 px-3 py-2 rounded-pill font-mono fw-bold">
                                Services Used: {{ $servicesCount ?? 0 }} / 5
                            </span>
                            @if(($servicesCount ?? 0) < 5)
                                <button type="button" onclick="openCreateServiceModal()" class="btn btn-sm btn-purple-cta rounded-pill px-3.5 py-2 fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm">
                                    <i class="bi bi-plus-lg"></i>
                                    <span>Add New Service</span>
                                </button>
                            @else
                                <button type="button" disabled class="btn btn-sm btn-light border text-muted rounded-pill px-3.5 py-2 fw-bold" title="Maximum limit of 5 services reached">
                                    Max Limit (5/5)
                                </button>
                            @endif
                        </div>
                    @endif
                </div>

                @if(isset($services) && $services->count() > 0)
                    <div class="row g-4">
                        @foreach($services as $srv)
                            <div class="col-12 col-md-6 col-lg-4">
                                <x-service-card :service="$srv" />
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white border border-dashed border-slate-300 rounded-4 p-5 text-center my-3">
                        <div class="w-14 h-14 mx-auto mb-3 rounded-circle bg-purple-50 text-[#20165c] d-flex align-items-center justify-content-center fs-3">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <h5 class="fw-bold text-[#121826] mb-1">No Services Listed Yet</h5>
                        <p class="text-slate-500 small mb-3">
                            @if($isOwnProfile)
                                You haven't added any custom services to your public profile yet. Offer custom design packages to receive client inquiries directly.
                            @else
                                {{ $user->name }} hasn't published any custom services yet. You can still message them directly for inquiries!
                            @endif
                        </p>
                        @if($isOwnProfile)
                            <button type="button" onclick="openCreateServiceModal()" class="btn btn-sm btn-purple-cta rounded-pill px-4 py-2 fw-bold">
                                + Add Your First Service
                            </button>
                        @endif
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
                            <h5 class="fw-extrabold text-[#121826] mb-0">Winning Challenge Submissions ({{ $totalWinsCount }})</h5>
                        </div>

                        <div class="row g-4">
                            @foreach($contestWins as $win)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card border border-amber-500/40 rounded-4 overflow-hidden h-100 bg-white">
                                        <div class="position-relative" style="height: 220px; background: #f8fafc;">
                                            @if($win->watermarked_preview_image)
                                                <img src="{{ asset('storage/' . ltrim(str_replace('storage/', '', $win->watermarked_preview_image), '/')) }}" alt="Winning Entry" class="w-100 h-100 object-fit-cover" onerror="this.src='{{ asset('images/logo.png') }}'">
                                            @else
                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-gray-500">
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
                                                <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(to top, rgba(7,11,20,0.9), transparent);">
                                                    <span class="badge bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                        Prize ৳{{ number_format($win->contest->prize_amount, 0) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="p-3.5">
                                            <h6 class="fw-bold text-[#121826] text-truncate mb-1">
                                                @if($win->contest)
                                                    <a href="{{ route('contests.show', $win->contest->slug) }}" class="text-[#121826] text-decoration-none hover-primary">
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
                                                    <span class="fw-bold text-[#121826] font-monospace">{{ $win->client_rating }}.0</span>
                                                    <span class="text-slate-500">• Buyer Rating</span>
                                                </div>
                                            @endif

                                            @if($win->client_feedback)
                                                <p class="text-slate-700 extra-small fst-italic mb-2 p-2.5 bg-slate-50 border border-slate-200 rounded-3">
                                                    "{{ Str::limit($win->client_feedback, 120) }}"
                                                </p>
                                            @endif

                                            @if($win->contest)
                                                <div class="text-end pt-2 border-t border-slate-200">
                                                    <a href="{{ route('contests.show', $win->contest->slug) }}" class="btn btn-sm btn-outline-dark text-slate-700 border-gray-700 hover:bg-gray-800 hover:text-[#121826] rounded-pill px-3 py-1 extra-small fw-bold">
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
                        <h5 class="fw-bold text-[#121826] mb-0">Contest Entries Gallery</h5>
                        <span class="extra-small text-slate-500 font-monospace">{{ $contestEntries->total() }} Designs Submitted</span>
                    </div>

                    @if($contestEntries->count() > 0)
                        <div class="row g-3">
                            @foreach($contestEntries as $entry)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="card border border-slate-200 rounded-4 overflow-hidden h-100 bg-white">
                                        <div class="position-relative" style="height: 180px; background: #f8fafc;">
                                            @if($entry->watermarked_preview_image)
                                                <img src="{{ asset('storage/' . ltrim(str_replace('storage/', '', $entry->watermarked_preview_image), '/')) }}" alt="Entry" class="w-100 h-100 object-fit-cover" onerror="this.src='{{ asset('images/logo.png') }}'">
                                            @else
                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-gray-500">
                                                    <i class="bi bi-image fs-2"></i>
                                                </div>
                                            @endif

                                            @if($entry->is_winner)
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-amber-500 text-black fw-bold rounded-pill px-2 py-1 extra-small">
                                                        🏆 Winner
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="p-2.5">
                                            @if($entry->contest)
                                                <div class="extra-small fw-bold text-truncate text-[#121826] mb-1">
                                                    <a href="{{ route('contests.show', $entry->contest->slug) }}" class="text-[#121826] text-decoration-none hover-primary">
                                                        {{ $entry->contest->title }}
                                                    </a>
                                                </div>
                                                <div class="extra-small text-slate-500 font-monospace">
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
                        <div class="card border border-slate-200 rounded-4 p-5 text-center bg-white">
                            <p class="text-slate-500 small mb-0">No public design entries submitted yet.</p>
                        </div>
                    @endif
                </div>

            </div>

            <!-- ============================================== -->
            <!-- TAB 3: CLIENT REVIEWS -->
            <!-- ============================================== -->
            <div class="tab-pane fade" id="tab-reviews" role="tabpanel" aria-labelledby="tab-reviews-btn">
                
                <!-- Summary Rating Box -->
                <div class="card border border-slate-200 rounded-4 p-4 mb-4 bg-white">
                    <div class="row align-items-center g-3">
                        <div class="col-auto text-center border-end border-slate-200 pe-4">
                            <div class="display-5 fw-extrabold text-[#121826] font-monospace mb-0">{{ number_format($averageRating, 1) }}</div>
                            <div class="text-warning small mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= round($averageRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                            <div class="extra-small text-slate-500 fw-bold">Overall Rating</div>
                        </div>

                        <div class="col">
                            <h6 class="fw-bold text-[#121826] mb-1">Client & Buyer Satisfaction</h6>
                            <p class="text-slate-700 small mb-0">
                                Based on {{ $totalReviewsCount }} verified customer reviews across Noksha digital assets and contest handovers.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Reviews Listing -->
                @if($reviews->count() > 0)
                    <div class="d-flex flex-column gap-3">
                        @foreach($reviews as $rev)
                            <div class="card border border-slate-200 rounded-4 p-4 bg-white">
                                <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-2">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar-placeholder rounded-circle bg-indigo-600/20 text-indigo-400 fw-bold extra-small d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($rev->user->name ?? 'Buyer', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-[#121826] small mb-0">{{ $rev->user->name ?? 'Verified Buyer' }}</div>
                                            <div class="extra-small text-slate-500">{{ $rev->created_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>

                                    <div class="text-warning small">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= ($rev->rating ?? 5) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                </div>

                                <p class="text-slate-700 small mb-2">
                                    "{{ $rev->review }}"
                                </p>

                                @if($rev->resource)
                                    <div class="extra-small text-slate-500 font-monospace border-t border-slate-200 pt-2">
                                        <i class="bi bi-box-seam me-1 text-indigo-400"></i>
                                        <a href="{{ route('resource.show', $rev->resource->slug) }}" class="text-decoration-none text-slate-500 hover-primary">
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
                    <div class="card border border-slate-200 rounded-4 p-5 text-center bg-white">
                        <div class="p-3 bg-amber-500/10 text-amber-400 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; margin: 0 auto;">
                            <i class="bi bi-chat-quote fs-3"></i>
                        </div>
                        <h5 class="fw-bold text-[#121826] mb-1">No Customer Reviews Yet</h5>
                        <p class="text-slate-500 small mb-0">Client feedback and verified reviews will populate as orders and contest handovers are finalized.</p>
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
                        <div class="card border border-slate-200 rounded-4 p-4 p-md-5 bg-white mb-4">
                            <h5 class="fw-bold text-[#121826] mb-3 d-flex align-items-center gap-2">
                                <i class="bi bi-person-badge text-indigo-400"></i>
                                <span>About the Designer</span>
                            </h5>

                            <div class="text-slate-700 lh-lg mb-0" style="white-space: pre-line;">
                                {{ $user->bio ?? 'This creator has not added a detailed biography yet. Reach out directly for project inquiries and design collaborations!' }}
                            </div>
                        </div>

                        <!-- Skills & Creative Tools -->
                        <div class="card border border-slate-200 rounded-4 p-4 p-md-5 bg-white">
                            <h5 class="fw-bold text-[#121826] mb-3 d-flex align-items-center gap-2">
                                <i class="bi bi-tools text-indigo-400"></i>
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
                                            <i class="bi bi-check-circle-fill fs-6 text-indigo-400"></i>
                                            <span>{{ $sk }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-slate-500 small mb-0">No specific skills listed yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Right: External Presence & Trust Card -->
                    <div class="col-12 col-lg-4">
                        
                        <!-- Social & Portfolio Presence -->
                        <div class="card border border-slate-200 rounded-4 p-4 bg-white mb-4">
                            <h6 class="fw-bold text-[#121826] mb-3 d-flex align-items-center gap-2">
                                <i class="bi bi-globe2 text-indigo-400"></i>
                                <span>Online Presence</span>
                            </h6>

                            @php
                                $links = $user->social_links ?? [];
                            @endphp

                            <div class="d-flex flex-column gap-2">
                                @if(!empty($links['behance']))
                                    <a href="{{ $links['behance'] }}" target="_blank" rel="noopener noreferrer" class="social-link-card">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-behance text-indigo-400 fs-5"></i>
                                            <span>Behance</span>
                                        </div>
                                        <i class="bi bi-box-arrow-up-right extra-small text-slate-500"></i>
                                    </a>
                                @endif

                                @if(!empty($links['dribbble']))
                                    <a href="{{ $links['dribbble'] }}" target="_blank" rel="noopener noreferrer" class="social-link-card">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-dribbble text-pink-400 fs-5"></i>
                                            <span>Dribbble</span>
                                        </div>
                                        <i class="bi bi-box-arrow-up-right extra-small text-slate-500"></i>
                                    </a>
                                @endif

                                @if(!empty($links['website']))
                                    <a href="{{ $links['website'] }}" target="_blank" rel="noopener noreferrer" class="social-link-card">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-globe fs-5 text-slate-700"></i>
                                            <span>Studio Website</span>
                                        </div>
                                        <i class="bi bi-box-arrow-up-right extra-small text-slate-500"></i>
                                    </a>
                                @endif

                                @if(!empty($links['linkedin']))
                                    <a href="{{ $links['linkedin'] }}" target="_blank" rel="noopener noreferrer" class="social-link-card">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-linkedin text-sky-400 fs-5"></i>
                                            <span>LinkedIn</span>
                                        </div>
                                        <i class="bi bi-box-arrow-up-right extra-small text-slate-500"></i>
                                    </a>
                                @endif

                                @if(!empty($links['twitter']))
                                    <a href="{{ $links['twitter'] }}" target="_blank" rel="noopener noreferrer" class="social-link-card">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-twitter-x text-[#121826] fs-5"></i>
                                            <span>Twitter / X</span>
                                        </div>
                                        <i class="bi bi-box-arrow-up-right extra-small text-slate-500"></i>
                                    </a>
                                @endif

                                @if(!empty($links['github']))
                                    <a href="{{ $links['github'] }}" target="_blank" rel="noopener noreferrer" class="social-link-card">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-github text-[#121826] fs-5"></i>
                                            <span>GitHub</span>
                                        </div>
                                        <i class="bi bi-box-arrow-up-right extra-small text-slate-500"></i>
                                    </a>
                                @endif

                                @if(empty(array_filter($links)))
                                    <p class="text-slate-500 extra-small mb-0">No external portfolio links provided.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Creator Trust & Verification Summary -->
                        <div class="card border border-slate-200 rounded-4 p-4 bg-white">
                            <h6 class="fw-bold text-[#121826] mb-3 d-flex align-items-center gap-2">
                                <i class="bi bi-shield-check text-[#f47402]"></i>
                                <span>Verified Badges & Status</span>
                            </h6>

                            <div class="d-flex flex-column gap-2.5 small">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-slate-500">Account Status:</span>
                                    <span class="fw-bold text-[#121826]">{{ $user->getRoleBadgeLabel() }}</span>
                                </div>

                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-slate-500">Trust Score:</span>
                                    <span class="fw-extrabold text-[#f47402] font-monospace">{{ number_format($user->trust_score ?? 100, 1) }}%</span>
                                </div>

                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-slate-500">Freelance Availability:</span>
                                    @if($user->is_available)
                                        <span class="badge bg-emerald-500/10 text-[#f47402] border border-emerald-500/20 rounded-pill px-2.5 py-1 extra-small fw-bold">Available</span>
                                    @else
                                        <span class="badge bg-gray-800 text-slate-500 border border-gray-700 rounded-pill px-2.5 py-1 extra-small fw-bold">Busy</span>
                                    @endif
                                </div>

                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-slate-500">Member Since:</span>
                                    <span class="fw-semibold text-[#121826] font-monospace">{{ $user->created_at->format('M Y') }}</span>
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
                                <div class="card border border-slate-200 rounded-4 p-4 h-100 bg-white d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                {{ $c->display_category }}
                                            </span>
                                            <span class="badge bg-slate-50 text-slate-700 border border-slate-200 rounded-pill px-2.5 py-1 extra-small font-monospace text-uppercase">
                                                {{ $c->status }}
                                            </span>
                                        </div>

                                        <h6 class="fw-bold text-[#121826] mb-2 text-truncate" title="{{ $c->title }}">
                                            <a href="{{ route('contests.show', $c->slug) }}" class="text-[#121826] text-decoration-none hover-primary">
                                                {{ $c->title }}
                                            </a>
                                        </h6>
                                        <p class="text-slate-700 extra-small mb-3 line-clamp-2">
                                            {{ Str::limit($c->description, 100) }}
                                        </p>
                                    </div>

                                    <div class="pt-3 border-t border-slate-200 d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="extra-small text-slate-500 text-uppercase fw-bold">Prize Bounty</div>
                                            <div class="fw-extrabold text-[#f47402] font-monospace">৳{{ number_format($c->prize_amount, 0) }}</div>
                                        </div>
                                        <a href="{{ route('contests.show', $c->slug) }}" class="btn btn-sm btn-purple-cta rounded-pill px-3 py-1 extra-small fw-bold">
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

</div>

<!-- CONTACT / HIRE MODAL -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content bg-white border border-slate-200 text-slate-800 rounded-4 overflow-hidden shadow-2xl">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-[#121826]" id="contactModalLabel">
                    <i class="bi bi-chat-quote-fill text-indigo-400 me-2"></i>Contact {{ $user->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body px-4 pt-3 pb-4">
                <div class="d-flex align-items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-3 mb-3">
                    <div class="profile-avatar-wrapper m-0">
                        @php
                            $modalAvatarSrc = $user->avatar ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . ltrim(str_replace('storage/', '', $user->avatar), '/'))) : $user->avatar_url;
                        @endphp
                        <img src="{{ $modalAvatarSrc }}" alt="{{ $user->name }}" class="rounded-circle ring-2 ring-indigo-500/50" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff';">
                    </div>
                    <div>
                        <div class="fw-bold text-[#121826]">{{ $user->name }}</div>
                        <div class="extra-small text-slate-500 font-monospace">&#64;{{ $user->username }}</div>
                        <div class="extra-small {{ $user->is_available ? 'text-[#f47402]' : 'text-slate-500' }} fw-bold">
                            <i class="bi bi-record-fill me-1"></i>{{ $user->is_available ? 'Available for Custom Projects' : 'Currently Busy' }}
                        </div>
                    </div>
                </div>

                <p class="text-slate-700 small mb-4">
                    Send an email inquiry directly to {{ $user->name }} regarding freelance design services, custom template requests, or design collaboration.
                </p>

                <div class="d-grid gap-2">
                    <a href="{{ route('inbox.show', $user->id) }}" class="btn btn-purple-cta rounded-pill py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-chat-dots-fill"></i>
                        <span>Start Chat on Noksha</span>
                    </a>
                    <a href="mailto:{{ $user->email }}?subject=Project%20Inquiry%20from%20Noksha%20Community" class="btn btn-outline-dark text-slate-800 border-gray-700 hover:bg-gray-800 hover:text-[#121826] rounded-pill py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2">
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
                    btn.className = 'btn btn-follow-toggle btn-success text-[#121826] px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2';
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

@include('services.partials.inquiry-modal')
@include('services.partials.service-form-modal', ['userTemplates' => $userTemplates ?? collect()])
@include('services.partials.image-preview-modal')
@endsection
