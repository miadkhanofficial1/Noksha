@extends('layouts.app')

@php
    $user = $user ?? $seller ?? auth()->user() ?? \App\Models\User::where('role', 'seller')->orWhere('is_contributor', true)->first() ?? \App\Models\User::first();
    $isOwnProfile = auth()->check() && $user && auth()->id() === $user->id;
    $isFollowing = auth()->check() && $user ? auth()->user()->isFollowing($user) : false;
    $followersCount = $user ? $user->followers()->count() : 0;
    $totalResourcesCount = $user ? $user->templates()->where('status', 'approved')->count() : 0;
    $totalDownloadsCount = $user ? (int) $user->templates()->sum('downloads') : 0;

    $categories = $categories ?? \App\Models\Category::all();
    $selectedCategory = request()->query('category');

    $templatesQuery = $user ? $user->templates()->where('status', 'approved')->with('category') : \App\Models\Template::where('status', 'approved')->with('category');
    if ($selectedCategory) {
        $templatesQuery->where('category_id', $selectedCategory);
    }
    $templates = $templatesQuery->latest()->paginate(12)->withQueryString();
@endphp

@section('title', ($user->name ?? 'Creator') . ' - Noksha Author Portfolio')

@section('content')

<!-- CUSTOM FIGMA/DRIBBBLE SELLER PROFILE STYLES -->
<style>
    /* Cover Banner Gradient & Pattern */
    .seller-cover-banner {
        height: 240px;
        background: linear-gradient(135deg, #6C4CF1 0%, #8B5CF6 50%, #9F7AEA 100%);
        position: relative;
        overflow: hidden;
    }

    .seller-cover-banner::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.2) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: 0.4;
    }

    /* Circular Avatar with Online/Verified Indicator */
    .seller-avatar-wrapper {
        position: relative;
        margin-top: -75px;
        display: inline-block;
    }

    .seller-avatar-img {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 4px solid #ffffff;
        box-shadow: 0 15px 35px -10px rgba(108, 76, 241, 0.35);
        object-fit: cover;
        background: #F8F5FF;
    }

    .seller-online-badge {
        position: absolute;
        bottom: 8px;
        right: 8px;
        width: 22px;
        height: 22px;
        background-color: #10B981;
        border: 3px solid #ffffff;
        border-radius: 50%;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    /* Glassmorphism Stat Cards */
    .glass-stat-box {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(108, 76, 241, 0.18) !important;
        border-radius: 1.25rem;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.08) !important;
    }

    /* Skill Chips */
    .seller-skill-chip {
        background: rgba(108, 76, 241, 0.06);
        color: #6C4CF1;
        border: 1px solid rgba(108, 76, 241, 0.18);
        border-radius: 999px;
        padding: 0.35rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .seller-skill-chip:hover {
        background: #6C4CF1;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px -3px rgba(108, 76, 241, 0.35);
    }

    /* Action Buttons */
    .btn-purple-cta {
        background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 6px 18px -4px rgba(108, 76, 241, 0.35);
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #5A3DE0 0%, #4327C6 100%);
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 12px 25px -4px rgba(108, 76, 241, 0.45);
        color: #ffffff !important;
    }

    .btn-outline-purple {
        border: 2px solid #6C4CF1;
        color: #6C4CF1;
        background: transparent;
        font-weight: 600;
        transition: all 0.25s ease;
    }

    .btn-outline-purple:hover {
        background: rgba(108, 76, 241, 0.08);
        color: #5A3DE0;
        border-color: #5A3DE0;
    }

    /* Figma Template Cards */
    .template-card-figma {
        border-radius: 1.25rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.08) !important;
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        overflow: hidden;
        background: #ffffff;
    }

    .template-card-figma:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px -10px rgba(108, 76, 241, 0.22) !important;
        border-color: rgba(108, 76, 241, 0.3) !important;
    }
</style>

<!-- HERO COVER BANNER -->
<section class="seller-cover-banner">
    <div class="container h-100 position-relative d-flex align-items-end justify-content-end pb-3">
        @if($user && $user->isContributor())
            <span class="badge bg-white bg-opacity-20 text-white backdrop-blur border border-white border-opacity-30 rounded-pill px-3 py-1.5 small fw-bold">
                <i class="bi bi-patch-check-fill me-1"></i> Verified Creator Studio
            </span>
        @endif
    </div>
</section>

<!-- SELLER HEADER & PROFILE INFO -->
<section class="bg-white border-bottom pb-4">
    <div class="container">
        <div class="row align-items-end g-4">
            
            <!-- Left: Avatar & Name -->
            <div class="col-12 col-md-7 col-lg-8">
                <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-end gap-4 text-center text-sm-start">
                    
                    <!-- Circular Avatar -->
                    <div class="seller-avatar-wrapper">
                        @if($user && $user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="seller-avatar-img">
                        @else
                            <div class="seller-avatar-img d-flex align-items-center justify-content-center text-primary fs-1 fw-bold bg-white">
                                {{ strtoupper(substr($user->name ?? 'C', 0, 1)) }}
                            </div>
                        @endif
                        <div class="seller-online-badge" title="Online now"></div>
                    </div>

                    <!-- Name & Badges -->
                    <div class="pb-1">
                        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-2 mb-1.5">
                            <h2 class="fw-extrabold text-dark mb-0">{{ $user->name ?? 'Creator' }}</h2>
                            @if($user && $user->isContributor())
                                <span class="badge rounded-pill px-3 py-1 fw-bold small text-emerald-700 bg-emerald-50 border border-emerald-300 shadow-sm">
                                    <i class="bi bi-patch-check-fill text-emerald-500 me-1"></i> Verified Creator
                                </span>
                            @endif
                            @if($user && $user->isAdmin())
                                <span class="badge rounded-pill px-3 py-1 fw-bold small text-purple-700 bg-purple-50 border border-purple-300 shadow-sm">
                                    <i class="bi bi-shield-lock-fill text-purple-500 me-1"></i> Admin
                                </span>
                            @endif
                        </div>
                        <p class="text-secondary fw-semibold mb-2">
                            {{ $user->headline ?? 'Digital Creator & Template Designer' }}
                        </p>
                        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-3 small text-muted">
                            <span><i class="bi bi-geo-alt-fill text-primary me-1"></i> {{ $user->location ?? 'Bangladesh' }}</span>
                            <span><i class="bi bi-calendar3 me-1"></i> Member since {{ $user && $user->created_at ? $user->created_at->format('M Y') : 'Recent' }}</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right: Action Buttons (Follow / Contact) -->
            <div class="col-12 col-md-5 col-lg-4 text-center text-md-end">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-end gap-2.5">
                    @if($isOwnProfile)
                        <a href="{{ route('settings.profile') }}" class="btn btn-outline-purple rounded-pill px-4 py-2.5 fw-bold">
                            <i class="bi bi-gear-fill me-1.5"></i> Edit Profile
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold">
                            <i class="bi bi-speedometer2 me-1.5"></i> Dashboard
                        </a>
                    @else
                        @auth
                            <button type="button" id="followBtn" onclick="toggleFollow('{{ $user->username ?? $user->id }}')" class="btn {{ $isFollowing ? 'btn-success' : 'btn-purple-cta' }} rounded-pill px-4 py-2.5 fw-bold">
                                <i class="bi {{ $isFollowing ? 'bi-check-lg' : 'bi-person-plus-fill' }} me-1.5" id="followBtnIcon"></i>
                                <span id="followBtnText">{{ $isFollowing ? 'Following' : 'Follow Author' }}</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold">
                                <i class="bi bi-person-plus-fill me-1.5"></i> Follow Author
                            </a>
                        @endauth
                        <a href="{{ route('contact.index') }}" class="btn btn-outline-purple rounded-pill px-4 py-2.5 fw-bold">
                            <i class="bi bi-chat-dots-fill me-1.5"></i> Contact Seller
                        </a>
                    @endif
                </div>
            </div>

        </div>

        <!-- Bio & Skills Row -->
        <div class="row g-4 mt-3">
            <div class="col-12 col-lg-8">
                <!-- Bio -->
                <p class="text-secondary lh-lg mb-3">
                    {{ $user->bio ?? 'Creative digital designer and template author on Noksha Marketplace.' }}
                </p>

                <!-- Skills Chips -->
                @if(!empty($user->skills) && is_array($user->skills))
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($user->skills as $skill)
                            <span class="seller-skill-chip"><i class="bi bi-check2-circle"></i> {{ $skill }}</span>
                        @endforeach
                    </div>
                @else
                    <div class="d-flex flex-wrap gap-2">
                        <span class="seller-skill-chip"><i class="bi bi-layers-fill"></i> Figma</span>
                        <span class="seller-skill-chip"><i class="bi bi-grid-3x3-gap-fill"></i> UI/UX Design</span>
                        <span class="seller-skill-chip"><i class="bi bi-vector-pen"></i> Vector Graphics</span>
                    </div>
                @endif
            </div>

            <!-- Stat Counters Box (Glassmorphism) -->
            <div class="col-12 col-lg-4">
                <div class="glass-stat-box p-3.5">
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div class="fw-extrabold fs-4 text-dark mb-0" id="followerCount">{{ number_format($followersCount) }}</div>
                            <div class="extra-small text-muted fw-semibold">Followers</div>
                        </div>
                        <div class="col-4 border-start border-end">
                            <div class="fw-extrabold fs-4 text-dark mb-0">{{ number_format($totalResourcesCount) }}</div>
                            <div class="extra-small text-muted fw-semibold">Resources</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-extrabold fs-4 text-dark mb-0">{{ number_format($totalDownloadsCount) }}</div>
                            <div class="extra-small text-muted fw-semibold">Downloads</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PORTFOLIO GRID SECTION -->
<section class="py-5 py-lg-6" style="background-color: #F8F7FF;">
    <div class="container">
        
        <!-- Filter Bar & Section Title -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <span class="badge px-3 py-1 rounded-pill text-uppercase fw-bold small mb-1" style="background: rgba(108, 76, 241, 0.08); color: #6C4CF1;">
                    Author Portfolio
                </span>
                <h3 class="fw-extrabold text-dark mb-0">Created Resources</h3>
            </div>

            <!-- Category Filter Tabs -->
            <div class="d-flex align-items-center gap-2 overflow-x-auto pb-1">
                <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="btn {{ empty($selectedCategory) ? 'btn-purple-cta' : 'btn-outline-secondary' }} rounded-pill btn-sm px-3.5 py-1.5 text-nowrap">
                    All Assets ({{ $totalResourcesCount }})
                </a>
                @foreach($categories as $category)
                    @php
                        $catCount = $user ? $user->templates()->where('status', 'approved')->where('category_id', $category->id)->count() : 0;
                    @endphp
                    @if($catCount > 0 || empty($user))
                        <a href="{{ request()->fullUrlWithQuery(['category' => $category->id]) }}" class="btn {{ (string)$selectedCategory === (string)$category->id ? 'btn-purple-cta' : 'btn-outline-secondary' }} rounded-pill btn-sm px-3.5 py-1.5 text-nowrap">
                            {{ $category->name }} ({{ $catCount }})
                        </a>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Created Resources Grid -->
        <div class="row g-4">
            @forelse($templates as $template)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 template-card-figma">
                        <div class="template-preview-area p-0 overflow-hidden position-relative" style="height: 220px; background-color: #0F172A;">
                            <img src="{{ $template->thumbnail_url }}" alt="{{ $template->title }}" class="w-100 h-100 object-fit-cover" onerror="this.src='{{ asset('images/logo.png') }}'">
                            <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                                {{ $template->category?->name ?? 'Design' }}
                            </span>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between text-muted small mb-2.5">
                                <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                    <i class="bi bi-star-fill text-warning me-1"></i>5.0
                                </span>
                                <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>{{ number_format($template->downloads) }}</span>
                            </div>
                            <h5 class="card-title fw-bold text-dark mb-1 text-truncate" title="{{ $template->title }}">{{ $template->title }}</h5>
                            <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-2">{{ Str::limit($template->description ?? 'Creative digital design asset published on Noksha.', 90) }}</p>
                            <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                                <span class="fw-extrabold text-dark fs-5">{{ $template->price == 0 ? 'Free' : '৳' . number_format($template->price, 2) }}</span>
                                <a href="{{ route('templates.show', $template->id) }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="p-5 rounded-4 bg-white shadow-sm border mx-auto" style="max-width: 540px;">
                        <div class="w-16 h-16 rounded-circle bg-primary bg-opacity-10 text-primary mx-auto mb-3 d-flex align-items-center justify-content-center fs-2" style="width: 64px; height: 64px; margin: 0 auto;">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">No creations published yet</h4>
                        <p class="text-muted small mb-4">Upload your first design asset to start showcasing your portfolio and earning royalties.</p>
                        @if($isOwnProfile || auth()->check())
                            <a href="{{ route('dashboard', ['tab' => 'upload']) }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold">
                                <i class="bi bi-cloud-arrow-up-fill me-1.5"></i> Upload New Asset
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        @if($templates->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $templates->links() }}
            </div>
        @endif

    </div>
</section>

<!-- INTERACTIVE FOLLOW BUTTON TOGGLE SCRIPT -->
<script>
function toggleFollow(userId) {
    const followBtn = document.getElementById('followBtn');
    const followerCount = document.getElementById('followerCount');
    const followBtnText = document.getElementById('followBtnText');
    const followBtnIcon = document.getElementById('followBtnIcon');

    fetch('/u/' + userId + '/follow', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.following) {
            followBtn.className = 'btn btn-success rounded-pill px-4 py-2.5 fw-bold';
            followBtnText.textContent = 'Following';
            followBtnIcon.className = 'bi bi-check-lg me-1.5';
        } else {
            followBtn.className = 'btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold';
            followBtnText.textContent = 'Follow Author';
            followBtnIcon.className = 'bi bi-person-plus-fill me-1.5';
        }
        if (data.followers_count !== undefined) {
            followerCount.textContent = data.followers_count;
        }
    })
    .catch(() => {});
}
</script>

@endsection
