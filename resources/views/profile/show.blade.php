@extends('layouts.app')

@section('title', $user->name . ' (@' . $user->username . ') - Noksha Portfolio')

@section('content')
<style>
    /* Profile Cover Banner */
    .profile-cover-banner {
        height: 300px;
        position: relative;
        overflow: hidden;
    }

    .profile-cover-mesh {
        background: linear-gradient(135deg, #20165c 0%, #2d1f7a 50%, #150d40 100%);
    }

    .profile-cover-mesh::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.2) 1.5px, transparent 1.5px);
        background-size: 24px 24px;
        opacity: 0.35;
        pointer-events: none;
    }

    /* Ambient Glowing Orbs */
    .ambient-orb-emerald {
        position: absolute;
        top: -60px;
        left: -60px;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.25) 0%, rgba(16, 185, 129, 0) 70%);
        filter: blur(40px);
        pointer-events: none;
    }

    .ambient-orb-tangerine {
        position: absolute;
        bottom: -60px;
        right: -60px;
        width: 340px;
        height: 340px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(244, 116, 2, 0.22) 0%, rgba(244, 116, 2, 0) 70%);
        filter: blur(40px);
        pointer-events: none;
    }

    .ambient-orb-indigo {
        position: absolute;
        top: 20%;
        left: 30%;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.22) 0%, rgba(99, 102, 241, 0) 70%);
        filter: blur(35px);
        pointer-events: none;
    }

    /* Stat micro-cards */
    .stat-chip-card {
        background: rgba(248, 250, 252, 0.95);
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 0.75rem;
        padding: 0.45rem 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
    }
    .stat-chip-card:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px -2px rgba(18, 24, 38, 0.08);
        transform: translateY(-1px);
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
        background: linear-gradient(135deg, #20165c 0%, #312285 100%);
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
        background: #f1f5f9;
        color: #64748b;
        border-radius: 999px;
        padding: 0.15rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .showcase-tab-btn:hover .showcase-tab-counter {
        background: #e2e8f0;
        color: #334155;
    }

    .showcase-tab-btn.active .showcase-tab-counter {
        background: rgba(32, 22, 92, 0.1);
        color: #20165c;
    }

    /* Asset Grid Card */
    .asset-card {
        border-radius: 1rem;
        border: 1px solid rgba(226, 232, 240, 0.9);
        background: #ffffff;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .asset-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e1;
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
        transition: opacity 0.2s ease;
    }

    .asset-card:hover .asset-thumb-img {
        opacity: 0.92;
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
        background: rgba(32, 22, 92, 0.06);
        color: #20165c;
        border: 1px solid rgba(32, 22, 92, 0.15);
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
        background: #20165c;
        color: #ffffff;
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
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    /* Follow Button */
    .btn-follow-toggle {
        min-width: 120px;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-purple-cta {
        background: #20165c !important;
        color: #FFFFFF !important;
        border: none;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-purple-cta:hover {
        background: #170f45 !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        color: #ffffff !important;
    }
</style>

<div class="min-h-screen bg-[#ffffff] text-slate-800">

    <!-- HERO IDENTITY BANNER -->
    @php
        $coverUrl = $user ? $user->cover_url : null;
        $avatarUrl = $user ? $user->avatar_url : null;
        $userInitials = $user ? $user->initials : 'NO';
    @endphp
    <section class="profile-cover-banner relative w-full h-[260px] md:h-[320px] overflow-hidden {{ $coverUrl ? '' : 'profile-cover-mesh' }}" style="{{ $coverUrl ? "background-image: url('{$coverUrl}'); background-size: cover; background-position: center;" : "" }}">
        @if($coverUrl)
            <img src="{{ $coverUrl }}" alt="{{ $user->name }} cover" class="absolute inset-0 w-full h-full object-cover" onerror="this.remove(); this.parentElement.classList.add('profile-cover-mesh');">
            <!-- Subtle bottom vignette overlay for high contrast readability -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent pointer-events-none"></div>
        @else
            <!-- Ambient Glowing Orbs on top of mesh -->
            <div class="ambient-orb-emerald"></div>
            <div class="ambient-orb-indigo"></div>
            <div class="ambient-orb-tangerine"></div>
        @endif

        <!-- Banner Top-Right Overlays (Availability & Quick Actions) -->
        <div class="absolute top-4 right-4 md:top-6 md:right-6 z-10 flex items-center gap-2.5">
            @if($user->is_available)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-black/40 backdrop-blur-md text-emerald-400 border border-emerald-500/30 shadow-lg">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Open for Custom Work</span>
                </span>
            @endif

            @if($isOwnProfile)
                <a href="{{ route('settings.profile') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-medium bg-black/40 hover:bg-black/60 text-white backdrop-blur-md border border-white/20 shadow-lg transition-all duration-200 text-decoration-none">
                    <i class="bi bi-camera-fill"></i>
                    <span>Change Cover</span>
                </a>
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
                        <div class="relative -mt-16 md:-mt-20 inline-block shrink-0">
                            <div class="relative w-28 h-28 md:w-32 md:h-32 rounded-full overflow-hidden flex items-center justify-center bg-[#20165c] text-white font-bold ring-4 ring-white shadow-xl">
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-full h-full object-cover object-center block"
                                         onerror="this.style.display='none'; document.getElementById('avatar-fallback-initial')?.classList.remove('hidden');">
                                    <div id="avatar-fallback-initial" class="w-full h-full flex items-center justify-center bg-[#20165c] text-white font-extrabold text-3xl hidden">
                                        {{ $userInitials }}
                                    </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-[#20165c] text-white font-extrabold text-3xl">
                                        {{ $userInitials }}
                                    </div>
                                @endif
                            </div>

                            <!-- Availability Dot Indicator -->
                            <span class="absolute bottom-1 right-1 w-5 h-5 rounded-full ring-4 ring-white {{ $user->is_available ? 'bg-emerald-500' : 'bg-slate-400' }}" title="{{ $user->is_available ? 'Available for Hire' : 'Busy / Not Taking Work' }}"></span>
                        </div>

                        <!-- Name, Handle & Headline -->
                        <div class="pb-1 flex-1">
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-2 mb-1.5">
                                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight mb-0">{{ $user->name }}</h1>
                                
                                @if($user->isContributor())
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full px-2.5 py-0.5 text-xs font-semibold shadow-xs">
                                        <i class="bi bi-patch-check-fill text-emerald-600"></i>
                                        <span>Verified Creator</span>
                                    </span>
                                @elseif($user->isAdmin())
                                    <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 border border-purple-200 rounded-full px-2.5 py-0.5 text-xs font-semibold shadow-xs">
                                        <i class="bi bi-shield-lock-fill text-purple-600"></i>
                                        <span>Admin</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center bg-slate-100 text-slate-600 border border-slate-200 rounded-full px-2.5 py-0.5 text-xs font-semibold">
                                        Member
                                    </span>
                                @endif

                                @if($user->hasReliabilityWarning())
                                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 border border-amber-300 rounded-full px-2.5 py-0.5 text-xs font-semibold" title="Reliability Warning: Accrued demerits or late deliveries">
                                        <i class="bi bi-exclamation-triangle-fill text-amber-600"></i>
                                        <span>Reliability Warning</span>
                                    </span>
                                @endif

                                @if($totalWinsCount > 0)
                                    <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 border border-amber-300 rounded-full px-2.5 py-0.5 text-xs font-bold shadow-xs">
                                        <i class="bi bi-trophy-fill text-amber-600"></i>
                                        <span>{{ $totalWinsCount }} {{ Str::plural('Win', $totalWinsCount) }}</span>
                                    </span>
                                @endif
                            </div>

                            <!-- Username Handle, Headline & Website -->
                            <div class="flex flex-wrap items-center justify-center justify-sm-start gap-x-2 gap-y-1 text-sm text-slate-500 mb-3">
                                <span class="font-mono text-indigo-600 font-semibold">&#64;{{ $user->username }}</span>
                                @if($user->headline)
                                    <span class="text-slate-300">•</span>
                                    <span class="text-slate-700 font-medium">{{ $user->headline }}</span>
                                @endif
                                @if($user->website)
                                    <span class="text-slate-300">•</span>
                                    <a href="{{ $user->website }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 text-decoration-none">
                                        <i class="bi bi-link-45deg"></i>
                                        <span>Portfolio</span>
                                    </a>
                                @endif
                            </div>

                            <!-- Trust Metrics Chips (Micro-Cards) -->
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-2">
                                <div class="stat-chip-card">
                                    <span class="w-7 h-7 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                                        <i class="bi bi-trophy-fill text-xs"></i>
                                    </span>
                                    <div class="flex flex-col text-left leading-tight">
                                        <span class="text-xs font-semibold text-slate-900">{{ $totalWinsCount }}</span>
                                        <span class="text-[10px] text-slate-500 uppercase tracking-wider font-medium">Wins</span>
                                    </div>
                                </div>

                                <div class="stat-chip-card">
                                    <span class="w-7 h-7 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                                        <i class="bi bi-star-fill text-xs"></i>
                                    </span>
                                    <div class="flex flex-col text-left leading-tight">
                                        <span class="text-xs font-semibold text-slate-900">{{ number_format($averageRating, 1) }}</span>
                                        <span class="text-[10px] text-slate-500 uppercase tracking-wider font-medium">({{ $totalReviewsCount }})</span>
                                    </div>
                                </div>

                                <div class="stat-chip-card">
                                    <span class="w-7 h-7 rounded-lg bg-purple-500/10 text-[#20165c] flex items-center justify-center shrink-0">
                                        <i class="bi bi-collection-fill text-xs"></i>
                                    </span>
                                    <div class="flex flex-col text-left leading-tight">
                                        <span class="text-xs font-semibold text-slate-900">{{ $totalAssetsCount }}</span>
                                        <span class="text-[10px] text-slate-500 uppercase tracking-wider font-medium">Assets</span>
                                    </div>
                                </div>

                                <div class="stat-chip-card">
                                    <span class="w-7 h-7 rounded-lg bg-sky-500/10 text-sky-600 flex items-center justify-center shrink-0">
                                        <i class="bi bi-people-fill text-xs"></i>
                                    </span>
                                    <div class="flex flex-col text-left leading-tight">
                                        <span class="text-xs font-semibold text-slate-900" id="followersCountVal">{{ $followersCount }}</span>
                                        <span class="text-[10px] text-slate-500 uppercase tracking-wider font-medium">Followers</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Right: Action Buttons -->
                <div class="col-12 col-lg-5 text-center text-lg-end">
                    <div class="d-inline-flex flex-wrap align-items-center justify-content-center justify-content-lg-end gap-2.5 pb-1">
                        @if($isOwnProfile)
                            <a href="{{ route('settings.profile') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-medium text-sm transition-all duration-200 text-decoration-none shadow-sm">
                                <i class="bi bi-gear-fill"></i>
                                <span>Edit Profile</span>
                            </a>
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#20165c] hover:bg-[#170f45] text-white font-medium text-sm transition-all duration-200 text-decoration-none shadow-sm">
                                <i class="bi bi-speedometer2"></i>
                                <span>My Dashboard</span>
                            </a>
                        @else
                            <!-- Hire / Inquire CTA -->
                            <a href="{{ route('inbox.show', $user->id) }}?inquiry=1" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#20165c] hover:bg-[#170f45] text-white font-medium text-sm transition-all duration-200 text-decoration-none shadow-sm">
                                <i class="bi bi-briefcase-fill text-amber-300"></i>
                                <span>Hire / Inquire</span>
                            </a>

                            <!-- Follow Toggle Button -->
                            @auth
                                <button type="button" class="btn-follow-toggle inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 shadow-sm border cursor-pointer {{ $isFollowing ? 'bg-emerald-600 hover:bg-emerald-700 text-white border-emerald-600' : 'bg-white hover:bg-slate-50 text-slate-700 border-slate-200' }}" id="followBtn" onclick="toggleFollow('{{ $user->username }}')">
                                    <i class="bi {{ $isFollowing ? 'bi-check2' : 'bi-plus-lg' }}" id="followBtnIcon"></i>
                                    <span id="followBtnText">{{ $isFollowing ? 'Following' : 'Follow' }}</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn-follow-toggle inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-medium text-sm transition-all duration-200 text-decoration-none shadow-sm">
                                    <i class="bi bi-plus-lg"></i>
                                    <span>Follow</span>
                                </a>
                            @endauth

                            <!-- Send Message Button -->
                            <a href="{{ route('inbox.show', $user->id) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 transition-all duration-200 text-decoration-none shadow-sm" title="Send Message">
                                <i class="bi bi-chat-dots-fill text-[#20165c]"></i>
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

                                            <a href="{{ route('resource.show', $asset->slug) }}" class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl bg-[#20165c] hover:bg-[#170f45] text-white font-medium text-xs shadow-sm transition-all duration-200 text-decoration-none">
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
                                <button type="button" onclick="openCreateServiceModal()" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#20165c] hover:bg-[#170f45] text-white font-medium text-xs shadow-sm transition-all duration-200 border-0 cursor-pointer">
                                    <i class="bi bi-plus-lg"></i>
                                    <span>Add New Service</span>
                                </button>
                            @else
                                <button type="button" disabled class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-100 text-slate-400 font-medium text-xs border border-slate-200 cursor-not-allowed" title="Maximum limit of 5 services reached">
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
                            <button type="button" onclick="openCreateServiceModal()" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#20165c] hover:bg-[#170f45] text-white font-medium text-xs shadow-sm transition-all duration-200 border-0 cursor-pointer">
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
                            @php
                                $reviewerUser = $rev->reviewer ?? $rev->user;
                                $reviewerName = $reviewerUser->name ?? 'Verified Client';
                                $reviewerInitial = $reviewerUser ? $reviewerUser->initials : strtoupper(substr($reviewerName, 0, 2));
                                $reviewerAvatar = $reviewerUser ? $reviewerUser->avatar_url : null;
                                $reviewText = $rev->comment ?? $rev->review ?? '';
                                $evidenceUrls = method_exists($rev, 'getEvidenceImageUrlsAttribute') ? $rev->evidence_image_urls : [];
                            @endphp
                            <div class="card border border-slate-200 rounded-4 p-4 bg-white shadow-xs">
                                <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-2">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="relative w-10 h-10 rounded-full overflow-hidden flex items-center justify-center bg-[#20165c] text-white font-bold text-xs ring-1 ring-slate-200 shrink-0">
                                            @if($reviewerAvatar)
                                                <img src="{{ $reviewerAvatar }}" alt="{{ $reviewerName }}" class="w-full h-full object-cover object-center block" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="w-full h-full flex items-center justify-center bg-[#20165c] text-white font-bold text-xs" style="display: none;">
                                                    {{ $reviewerInitial }}
                                                </div>
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-[#20165c] text-white font-bold text-xs">
                                                    {{ $reviewerInitial }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <span class="fw-bold text-[#121826] small mb-0">{{ $reviewerName }}</span>
                                                <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-pill px-2 py-0.5 extra-small fw-semibold">
                                                    <i class="bi bi-patch-check-fill me-1 text-emerald-600"></i> Verified Buyer
                                                </span>
                                                @if(isset($rev->is_complaint) && $rev->is_complaint)
                                                    <span class="badge bg-rose-50 text-rose-700 border border-rose-200 rounded-pill px-2 py-0.5 extra-small fw-semibold">
                                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Issue Reported
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="extra-small text-slate-500 font-mono mt-0.5">{{ $rev->created_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>

                                    <div class="text-amber-500 small">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= ($rev->rating ?? 5) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                        <span class="fw-bold ms-1 text-slate-700 extra-small">{{ number_format($rev->rating ?? 5, 1) }}</span>
                                    </div>
                                </div>

                                <p class="text-slate-700 small mb-2 lh-relaxed">
                                    "{{ $reviewText }}"
                                </p>

                                {{-- Evidence Images Thumbnail Strip --}}
                                @if(!empty($evidenceUrls) && count($evidenceUrls) > 0)
                                    <div class="mt-2.5 mb-2">
                                        <div class="extra-small font-semibold text-slate-500 mb-1.5 flex items-center gap-1">
                                            <i class="bi bi-images text-[#20165c]"></i> Evidence / Work Artifacts ({{ count($evidenceUrls) }}):
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($evidenceUrls as $imgIdx => $imgUrl)
                                                <button type="button" onclick="openServicePreviewModal('{{ $imgUrl }}', 'Evidence Artifact {{ $imgIdx + 1 }}')" class="p-0 border border-slate-200 rounded-xl overflow-hidden shadow-xs hover:scale-105 transition-transform cursor-pointer" style="width: 72px; height: 72px;">
                                                    <img src="{{ $imgUrl }}" alt="Evidence {{ $imgIdx + 1 }}" class="w-100 h-100 object-cover">
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($rev->service)
                                    <div class="extra-small text-slate-500 font-monospace border-t border-slate-100 pt-2 mt-1 d-flex align-items-center gap-1.5">
                                        <i class="bi bi-briefcase text-[#f47402]"></i>
                                        <span>Service:</span>
                                        <strong class="text-slate-800">{{ $rev->service->title }}</strong>
                                    </div>
                                @elseif($rev->asset || $rev->resource)
                                    @php $revAsset = $rev->asset ?? $rev->resource; @endphp
                                    <div class="extra-small text-slate-500 font-monospace border-t border-slate-100 pt-2 mt-1 d-flex align-items-center gap-1.5">
                                        <i class="bi bi-box-seam text-[#20165c]"></i>
                                        <span>Template:</span>
                                        <a href="{{ route('resource.show', $revAsset->slug ?? $revAsset->id) }}" class="text-decoration-none text-[#20165c] fw-bold hover-primary">
                                            {{ $revAsset->title }}
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
                                    <span class="fw-extrabold {{ ($user->trust_score ?? 100) >= 90 ? 'text-emerald-600' : 'text-[#f47402]' }} font-monospace">{{ number_format($user->trust_score ?? 100, 1) }}%</span>
                                </div>

                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-slate-500">Freelance Availability:</span>
                                    @if($user->is_available)
                                        <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-pill px-2.5 py-1 extra-small fw-bold">Available</span>
                                    @else
                                        <span class="badge bg-gray-100 text-slate-500 border border-slate-200 rounded-pill px-2.5 py-1 extra-small fw-bold">Busy</span>
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
                    <div class="relative w-12 h-12 rounded-full overflow-hidden flex items-center justify-center bg-[#20165c] text-white font-bold text-sm ring-2 ring-indigo-500/50 shrink-0">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover object-center block" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <span class="w-full h-full flex items-center justify-center bg-[#20165c] text-white font-bold text-sm" style="display: none;">
                                {{ $userInitials }}
                            </span>
                        @else
                            <span class="w-full h-full flex items-center justify-center bg-[#20165c] text-white font-bold text-sm">
                                {{ $userInitials }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <div class="fw-bold text-[#121826]">{{ $user->name }}</div>
                        <div class="extra-small text-slate-500 font-monospace">&#64;{{ $user->username }}</div>
                        <div class="extra-small {{ $user->is_available ? 'text-emerald-600' : 'text-slate-500' }} fw-bold">
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
                    btn.className = 'btn-follow-toggle inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 shadow-sm border cursor-pointer bg-emerald-600 hover:bg-emerald-700 text-white border-emerald-600';
                    icon.className = 'bi bi-check2';
                    text.textContent = 'Following';
                } else {
                    btn.className = 'btn-follow-toggle inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 shadow-sm border cursor-pointer bg-white hover:bg-slate-50 text-slate-700 border-slate-200';
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
