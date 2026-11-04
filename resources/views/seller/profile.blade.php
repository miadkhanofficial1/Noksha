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
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        position: relative;
        overflow: hidden;
    }

    .seller-cover-banner::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: 0.3;
    }

    /* Circular Avatar with Online Indicator */
    .seller-avatar-wrapper {
        position: relative;
        margin-top: -75px;
        display: inline-block;
    }

    .seller-avatar-img {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 4px solid #0b1329;
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.6);
        object-fit: cover;
        background: #0b1329;
        display: block;
    }

    .seller-online-badge {
        position: absolute;
        bottom: 8px;
        right: 8px;
        width: 22px;
        height: 22px;
        background-color: #10B981;
        border: 3px solid #0b1329;
        border-radius: 50%;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
    }

    /* Glassmorphism Stat Cards */
    .glass-stat-box {
        background: #070d1e;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 1.25rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
    }

    /* Skill Chips */
    .seller-skill-chip {
        background: rgba(99, 102, 241, 0.15);
        color: #a5b4fc;
        border: 1px solid rgba(99, 102, 241, 0.3);
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
        background: #6366f1;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px -3px rgba(99, 102, 241, 0.4);
    }

    /* Action Buttons */
    .btn-purple-cta {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 6px 18px -4px rgba(79, 70, 229, 0.35);
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 12px 25px -4px rgba(79, 70, 229, 0.45);
        color: #ffffff !important;
    }

    .btn-outline-purple {
        border: 1px solid #6366f1;
        color: #c7d2fe;
        background: rgba(99, 102, 241, 0.1);
        font-weight: 600;
        transition: all 0.25s ease;
    }

    .btn-outline-purple:hover {
        background: #6366f1;
        color: #ffffff !important;
        border-color: #6366f1;
    }

    /* Figma Template Cards */
    .template-card-figma {
        border-radius: 1.25rem !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        overflow: hidden;
        background: #0b1329;
    }

    .template-card-figma:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.25) !important;
        border-color: rgba(99, 102, 241, 0.4) !important;
    }
</style>

<div class="min-h-screen bg-[#070b14] text-gray-100">

    <!-- HERO COVER BANNER -->
    <section class="seller-cover-banner" @if($user && $user->cover_url) style="background: url('{{ $user->cover_url }}') center/cover no-repeat;" @endif>
        <div class="container h-100 position-relative d-flex align-items-end justify-content-end pb-3">
            @if($user && $user->isContributor())
                <span class="badge bg-black/40 text-emerald-300 backdrop-blur border border-emerald-500/30 rounded-pill px-3 py-1.5 small fw-bold">
                    <i class="bi bi-patch-check-fill me-1 text-emerald-400"></i> Verified Creator Studio
                </span>
            @endif
        </div>
    </section>

    <!-- SELLER HEADER & PROFILE INFO -->
    <section class="bg-[#0b1329] border-b border-gray-800/80 pb-6 text-gray-100">
        <div class="container">
            <div class="row align-items-end g-4">
                
                <!-- Left: Avatar & Name -->
                <div class="col-12 col-md-7 col-lg-8">
                    <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-end gap-4 text-center text-sm-start">
                        
                        <!-- Circular Avatar -->
                        <div class="seller-avatar-wrapper">
                            <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'Creator') . '&background=6366f1&color=fff' }}" 
                                 alt="{{ $user->name ?? 'Creator' }}" 
                                 class="seller-avatar-img ring-4 ring-[#0b1329]"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'Creator') }}&background=6366f1&color=fff';">
                            <div class="seller-online-badge" title="Online now"></div>
                        </div>

                        <!-- Name & Badges -->
                        <div class="pb-1">
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-2 mb-1.5">
                                <h2 class="fw-extrabold text-white mb-0">{{ $user->name ?? 'Creator' }}</h2>
                                @if($user && $user->isContributor())
                                    <span class="badge rounded-pill px-3 py-1 fw-bold small text-emerald-400 bg-emerald-950/40 border border-emerald-500/30 shadow-sm">
                                        <i class="bi bi-patch-check-fill text-emerald-400 me-1"></i> Verified Creator
                                    </span>
                                @endif
                                @if($user && $user->isAdmin())
                                    <span class="badge rounded-pill px-3 py-1 fw-bold small text-purple-300 bg-purple-950/40 border border-purple-500/30 shadow-sm">
                                        <i class="bi bi-shield-lock-fill text-purple-400 me-1"></i> Admin
                                    </span>
                                @endif
                            </div>
                            <p class="text-gray-300 fw-semibold mb-2">
                                {{ $user->headline ?? 'Digital Creator & Template Designer' }}
                            </p>
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-3 small text-gray-400">
                                <span><i class="bi bi-geo-alt-fill text-indigo-400 me-1"></i> {{ $user->location ?? 'Bangladesh' }}</span>
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
                            <a href="{{ route('inbox.show', $user->id) }}" class="btn btn-outline-purple rounded-pill px-4 py-2.5 fw-bold">
                                <i class="bi bi-chat-dots-fill me-1.5"></i> Send Message
                            </a>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Bio & Skills Row -->
            <div class="row g-4 mt-3">
                <div class="col-12 col-lg-8">
                    <!-- Bio -->
                    <p class="text-gray-300 lh-lg mb-3">
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
                                <div class="fw-extrabold fs-4 text-white mb-0" id="followerCount">{{ number_format($followersCount) }}</div>
                                <div class="extra-small text-gray-400 fw-semibold">Followers</div>
                            </div>
                            <div class="col-4 border-start border-end border-gray-800">
                                <div class="fw-extrabold fs-4 text-white mb-0">{{ number_format($totalResourcesCount) }}</div>
                                <div class="extra-small text-gray-400 fw-semibold">Resources</div>
                            </div>
                            <div class="col-4">
                                <div class="fw-extrabold fs-4 text-white mb-0">{{ number_format($totalDownloadsCount) }}</div>
                                <div class="extra-small text-gray-400 fw-semibold">Downloads</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PORTFOLIO GRID SECTION -->
    <section class="py-8 sm:py-12 bg-[#070b14] min-h-[500px]">
        <div class="container">
            
            <!-- Filter Bar & Section Title -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <span class="badge px-3 py-1 rounded-pill text-uppercase fw-bold small mb-1 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                        Author Portfolio
                    </span>
                    <h3 class="fw-extrabold text-white mb-0">Created Resources</h3>
                </div>

                <!-- Category Filter Tabs -->
                <div class="d-flex align-items-center gap-2 overflow-x-auto pb-1">
                    <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="btn {{ empty($selectedCategory) ? 'btn-purple-cta' : 'btn-outline-dark text-gray-300 border-gray-700 hover:bg-gray-800 hover:text-white' }} rounded-pill btn-sm px-3.5 py-1.5 text-nowrap">
                        All Assets ({{ $totalResourcesCount }})
                    </a>
                    @foreach($categories as $category)
                        @php
                            $catCount = $user ? $user->templates()->where('status', 'approved')->where('category_id', $category->id)->count() : 0;
                        @endphp
                        @if($catCount > 0 || empty($user))
                            <a href="{{ request()->fullUrlWithQuery(['category' => $category->id]) }}" class="btn {{ (string)$selectedCategory === (string)$category->id ? 'btn-purple-cta' : 'btn-outline-dark text-gray-300 border-gray-700 hover:bg-gray-800 hover:text-white' }} rounded-pill btn-sm px-3.5 py-1.5 text-nowrap">
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
                            <div class="template-preview-area p-0 overflow-hidden position-relative" style="height: 220px; background-color: #070d1e;">
                                <img src="{{ $template->thumbnail_url }}" alt="{{ $template->title }}" class="w-100 h-100 object-fit-cover" onerror="this.src='{{ asset('images/logo.png') }}'">
                                <span class="position-absolute top-0 start-0 m-3 badge bg-[#0b1329]/80 text-gray-200 border border-gray-700/60 rounded-pill shadow-sm px-3 py-1.5 fw-bold small backdrop-blur">
                                    {{ $template->category?->name ?? 'Design' }}
                                </span>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between text-muted small mb-2.5">
                                    <span class="badge bg-amber-500/10 text-amber-400 border border-amber-500/20 px-2.5 py-1 rounded-pill">
                                        <i class="bi bi-star-fill text-amber-400 me-1"></i>5.0
                                    </span>
                                    <span class="text-gray-400 font-monospace"><i class="bi bi-download me-1"></i>{{ number_format($template->downloads) }}</span>
                                </div>
                                <h5 class="card-title fw-bold text-white mb-1 text-truncate" title="{{ $template->title }}">{{ $template->title }}</h5>
                                <p class="card-text text-gray-400 small mb-4 flex-grow-1 line-clamp-2">{{ Str::limit($template->description ?? 'Creative digital design asset published on Noksha.', 90) }}</p>
                                <div class="d-flex align-items-center justify-content-between pt-3 border-t border-gray-800">
                                    <span class="fw-extrabold text-white fs-5">{{ $template->price == 0 ? 'Free' : '৳' . number_format($template->price, 2) }}</span>
                                    <a href="{{ route('templates.show', $template->id) }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="p-5 rounded-4 bg-[#0b1329] border border-gray-800 text-center mx-auto" style="max-width: 540px;">
                            <div class="w-16 h-16 rounded-circle bg-indigo-600/10 text-indigo-400 mx-auto mb-3 d-flex align-items-center justify-content-center fs-2" style="width: 64px; height: 64px; margin: 0 auto;">
                                <i class="bi bi-folder2-open"></i>
                            </div>
                            <h4 class="fw-bold text-white mb-2">No creations published yet</h4>
                            <p class="text-gray-400 small mb-4">Upload your first design asset to start showcasing your portfolio and earning royalties.</p>
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

</div>

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
