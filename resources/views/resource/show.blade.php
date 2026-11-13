@extends('layouts.app')

@section('title', (isset($resource) ? $resource->title : 'Digital Asset') . ' - Noksha')

@section('content')

<!-- CUSTOM FIGMA/DRIBBBLE RESOURCE DETAILS STYLES -->
<style>
    .resource-details-hero {
        background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%);
        position: relative;
    }

    /* Main Preview Container */
    .resource-preview-main {
        border-radius: 1rem !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
        background: #f8fafc;
        position: relative;
    }

    .resource-preview-box {
        min-height: 420px;
        position: relative;
        overflow: hidden;
        background: #f8fafc;
        transition: all 0.3s ease;
    }

    /* Gallery Thumbnails */
    .thumb-card {
        border-radius: 1rem;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
        height: 85px;
        background: #F8F7FF;
    }

    .thumb-card:hover, .thumb-card.active {
        border-color: #6C4CF1 !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -4px rgba(108, 76, 241, 0.3);
    }

    /* Glassmorphism Pricing Sticky Card */
    .glass-pricing-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(108, 76, 241, 0.2) !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 20px 45px -10px rgba(108, 76, 241, 0.18) !important;
        position: sticky;
        top: 100px;
    }

    /* Seller Profile Card */
    .seller-card-figma {
        border-radius: 1.5rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        background: #ffffff;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.06) !important;
    }
    
    /* AI Tag Pills */
    .ai-tag-pill {
        background: rgba(108, 76, 241, 0.06);
        color: #6C4CF1;
        border: 1px solid rgba(108, 76, 241, 0.18);
        border-radius: 999px;
        padding: 0.4rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .ai-tag-pill:hover {
        background: #6C4CF1;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px -3px rgba(108, 76, 241, 0.4);
    }

    /* Action Buttons */
    .btn-purple-cta {
        background: linear-gradient(135deg, #20165c 0%, #2c1e7a 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 700;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 8px 20px -4px rgba(32, 22, 92, 0.35);
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #181045 0%, #20165c 100%);
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 14px 28px -4px rgba(32, 22, 92, 0.45);
        color: #ffffff !important;
    }

    .btn-outline-purple {
        background: rgba(108, 76, 241, 0.05);
        color: #6C4CF1 !important;
        border: 1.5px solid rgba(108, 76, 241, 0.3) !important;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-outline-purple:hover {
        background: rgba(108, 76, 241, 0.12);
        border-color: #6C4CF1 !important;
        transform: translateY(-2px);
        color: #5A3DE0 !important;
    }

    .btn-ai-quick-edit {
        background: linear-gradient(135deg, #1E1B4B 0%, #4338CA 50%, #7C3AED 100%);
        color: #FFFFFF !important;
        border: 1.5px solid rgba(167, 139, 250, 0.45) !important;
        box-shadow: 0 8px 25px -4px rgba(124, 58, 237, 0.4);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .btn-ai-quick-edit:hover {
        background: linear-gradient(135deg, #312E81 0%, #4F46E5 50%, #8B5CF6 100%);
        transform: translateY(-2px) scale(1.015);
        box-shadow: 0 14px 30px -4px rgba(124, 58, 237, 0.6);
        border-color: rgba(196, 181, 253, 0.8) !important;
        color: #FFFFFF !important;
    }

    /* Template Cards for Related Section */
    .template-card-figma {
        border-radius: 1.5rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 12px 35px -10px rgba(108, 76, 241, 0.1) !important;
        transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
        overflow: hidden;
        background: #ffffff;
    }

    .template-card-figma:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 45px -10px rgba(108, 76, 241, 0.22) !important;
        border-color: rgba(108, 76, 241, 0.35) !important;
    }

    .card-grad-1 { background: linear-gradient(135deg, #6366F1 0%, #A855F7 100%); }
    .card-grad-2 { background: linear-gradient(135deg, #3B82F6 0%, #06B6D4 100%); }
    .card-grad-3 { background: linear-gradient(135deg, #EC4899 0%, #8B5CF6 100%); }
    .card-grad-4 { background: linear-gradient(135deg, #F59E0B 0%, #EF4444 100%); }
</style>

<!-- HEADER & BREADCRUMB SECTION -->
<section class="resource-details-hero py-4 py-lg-5 border-bottom border-light-subtle">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small fw-semibold text-muted mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('home') }}#templates" class="text-decoration-none text-primary">{{ isset($resource) && $resource->category ? $resource->category->name : 'Templates' }}</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">{{ isset($resource) ? $resource->title : 'Digital Asset' }}</li>
            </ol>
        </nav>

        <!-- Product Header Bar -->
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1.5 fw-bold small">
                        <i class="bi bi-grid-fill me-1"></i> {{ isset($resource) && $resource->category ? $resource->category->name : 'Design Asset' }}
                    </span>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1.5 fw-bold small">
                        <i class="bi bi-patch-check-fill me-1"></i> Verified Marketplace Asset
                    </span>
                    @if(isset($resource) && $resource->owner)
                        <span class="text-secondary small ms-1 d-inline-flex align-items-center gap-1">
                            by <a href="{{ route('user.profile', $resource->owner->username ?? $resource->owner->id) }}" class="text-dark fw-bold text-decoration-none hover-primary">{{ $resource->owner->name }}</a>
                            @if($resource->owner->isVerifiedCreator())
                                <span class="badge rounded-pill px-2 py-0.5 extra-small fw-bold text-emerald-700 bg-emerald-50 border border-emerald-300 shadow-sm" style="box-shadow: 0 0 8px rgba(16, 185, 129, 0.25);" title="Verified Creator">
                                    <i class="bi bi-patch-check-fill text-emerald-500"></i> Verified
                                </span>
                            @endif
                        </span>
                    @endif
                </div>
                <h1 class="display-5 fw-extrabold text-dark mb-2">
                    {{ isset($resource) ? $resource->title : 'Digital Asset' }}
                </h1>
                <p class="fs-6 text-secondary mb-0">
                    {{ isset($resource) ? Str::limit($cleanDesc ?? $resource->description, 180) : 'High quality design asset for modern creative projects.' }}
                </p>
            </div>
            
            <div class="col-lg-4 text-lg-end">
                <div class="d-inline-flex flex-wrap gap-3 p-3 bg-white rounded-4 border shadow-sm align-items-center justify-content-center">
                    <div class="text-center">
                        <div class="fw-extrabold text-dark fs-5 mb-0">
                            @if(($totalReviews ?? 0) > 0)
                                <i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($avgRating, 1) }}
                            @else
                                <i class="bi bi-star text-muted me-1"></i>0.0
                            @endif
                        </div>
                        <div class="extra-small text-muted fw-semibold">
                            {{ ($totalReviews ?? 0) > 0 ? $totalReviews . ' ' . Str::plural('Review', $totalReviews) : 'No ratings yet' }}
                        </div>
                    </div>
                    <div class="vr opacity-20"></div>
                    <div class="text-center">
                        <div class="fw-extrabold text-dark fs-5 mb-0"><i class="bi bi-download text-primary me-1"></i>{{ isset($resource) ? number_format($resource->downloads) : '5.2k' }}</div>
                        <div class="extra-small text-muted fw-semibold">Downloads</div>
                    </div>
                    <div class="vr opacity-20"></div>
                    <div class="text-center">
                        <div class="fw-extrabold text-dark fs-5 mb-0"><i class="bi bi-eye text-info me-1"></i>{{ isset($resource) ? number_format($resource->views) : '18.4k' }}</div>
                        <div class="extra-small text-muted fw-semibold">Views</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MAIN PRODUCT DETAILS CONTENT SECTION -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4 g-lg-5">
            
            <!-- LEFT COLUMN: PREVIEW GALLERY & DETAILS (8 COLUMNS) -->
            <div class="col-12 col-lg-8">
                
                <!-- Large Product Preview Gallery -->
                @php
    $coverUrl = null;
    if (isset($resource)) {
        if (!empty($resource->preview_image)) {
            if (str_starts_with($resource->preview_image, 'http://') || str_starts_with($resource->preview_image, 'https://')) {
                $coverUrl = $resource->preview_image;
            } else {
                $cleanPath = ltrim(str_replace('storage/', '', $resource->preview_image), '/');
                $coverUrl = asset('storage/' . $cleanPath);
            }
        } elseif (!empty($resource->thumbnail_url)) {
            $coverUrl = $resource->thumbnail_url;
        }
    }

    // Filter out dummy/test strings (e.g. dlknfonsdof, asdf, etc.)
    $rawDesc = trim($resource->description ?? '');
    $isDummyDesc = empty($rawDesc) || strlen($rawDesc) < 15 || preg_match('/^[a-z]{8,}$/i', $rawDesc) || in_array(strtolower($rawDesc), ['test', 'asdf', 'dlknfonsdof', 'sample', 'description']);
    $cleanDesc = !$isDummyDesc 
        ? $rawDesc 
        : 'High-caliber digital design asset crafted with precision for professional UI/UX, brand identity, and modern digital workflows. Fully organized vector layers, global design tokens, and scalable components configured for instant production deployment.';
@endphp

                <!-- Large Product Preview Gallery Frame (White/Slate-50 Frame) -->
                <div class="resource-preview-main mb-4 bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    <div id="mainPreviewDisplay" class="resource-preview-box p-0 position-relative overflow-hidden bg-slate-50 d-flex align-items-center justify-content-center" style="min-height: 440px;">
                        @if(!empty($coverUrl))
                            <img src="{{ $coverUrl }}" 
                                 id="resourceMainCoverImg"
                                 class="w-100 h-100 object-fit-cover" 
                                 alt="{{ $resource->title }}"
                                 onerror="this.onerror=null; this.classList.add('d-none'); document.getElementById('previewVectorFallback').classList.remove('d-none');">
                        @endif

                        <!-- Graceful Vector Showcase Fallback (When cover image is missing or broken) -->
                        <div id="previewVectorFallback" class="{{ !empty($coverUrl) ? 'd-none' : '' }} w-100 h-100 p-5 d-flex flex-column align-items-center justify-content-center text-center bg-gradient-to-br from-purple-50/60 via-slate-50 to-amber-50/40" style="min-height: 440px;">
                            <div class="rounded-2xl p-4 bg-white border border-purple-100 shadow-sm mb-3 text-[#20165c]">
                                <svg width="68" height="68" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg>
                            </div>
                            <h4 class="fw-bold text-[#121826] mb-1.5">{{ isset($resource) ? $resource->title : 'Digital Asset Showcase' }}</h4>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="badge bg-purple-50 text-[#20165c] border border-purple-200 px-3 py-1 rounded-pill extra-small font-mono fw-bold">
                                    {{ isset($resource) ? strtoupper($resource->file_type ?? 'VECTOR') : 'VECTOR' }} FORMAT
                                </span>
                                <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-pill extra-small font-mono fw-bold">
                                    VERIFIED ASSET
                                </span>
                            </div>
                        </div>

                        <!-- Top Floating Badges -->
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white/95 backdrop-blur-md text-[#121826] border border-slate-200 rounded-pill shadow-sm px-3.5 py-2 fw-bold small">
                            <i class="bi bi-award-fill text-[#20165c] me-1"></i> Editor's Choice
                        </span>
                        <span class="position-absolute top-0 end-0 m-3 badge bg-white/95 backdrop-blur-md text-[#20165c] border border-slate-200 rounded-pill px-3 py-1.5 small font-monospace fw-bold shadow-sm">
                            {{ isset($resource) ? strtoupper($resource->file_type ?? 'ZIP') : 'FIGMA' }} Format
                        </span>
                    </div>
                </div>

                <!-- Multi-image Preview Gallery Thumbnails (if available) -->
                @if(isset($resource) && !empty($resource->preview_images) && count((array)$resource->preview_images) > 1)
                    <div class="d-flex align-items-center gap-2 mb-4 overflow-x-auto pb-2">
                        @foreach((array)$resource->preview_images as $idx => $pImg)
                            @php
                                $pUrl = str_starts_with($pImg, 'http') ? $pImg : asset('storage/' . ltrim(str_replace('storage/', '', $pImg), '/'));
                            @endphp
                            <button type="button" 
                                    onclick="document.getElementById('resourceMainCoverImg').src = '{{ $pUrl }}';" 
                                    class="btn p-0 border border-slate-300 rounded-3 overflow-hidden shadow-xs hover-border-purple transition flex-shrink-0" 
                                    style="width: 76px; height: 52px;"
                                    title="View Slide {{ $idx + 1 }}">
                                <img src="{{ $pUrl }}" class="w-100 h-100 object-fit-cover" alt="Slide {{ $idx + 1 }}">
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Asset Tags -->
                <div class="mb-5 p-4 rounded-4 bg-light border">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-tags-fill text-primary"></i> Asset Tags & Metadata
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @if(isset($resource) && !empty($resource->tags))
                            @foreach((array)$resource->tags as $tag)
                                <a href="{{ route('home') }}" class="ai-tag-pill text-decoration-none"><i class="bi bi-tag-fill me-1"></i>#{{ trim($tag) }}</a>
                            @endforeach
                        @else
                            <a href="{{ route('home') }}" class="ai-tag-pill text-decoration-none"><i class="bi bi-tag-fill me-1"></i>#noksha</a>
                            <a href="{{ route('home') }}" class="ai-tag-pill text-decoration-none"><i class="bi bi-tag-fill me-1"></i>#design-template</a>
                            <a href="{{ route('home') }}" class="ai-tag-pill text-decoration-none"><i class="bi bi-tag-fill me-1"></i>#vector-asset</a>
                        @endif
                    </div>
                </div>

                <!-- Overview & Visual Case Study Breakdown -->
                <div class="mb-5">
                    <h3 class="fw-extrabold text-dark mb-3">Design Story & Case Study</h3>
                    <p class="text-secondary lh-lg mb-4">
                        {{ $cleanDesc ?? ($resource->description ?? '') }}
                    </p>

                    <!-- Tools Used Badges -->
                    <div class="mb-4 p-4 rounded-4 bg-white border border-slate-200 shadow-sm">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-tools text-[#20165c]"></i> Creative Tools Used
                        </h6>
                        <div class="d-flex flex-wrap gap-2.5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-purple-50 text-[#20165c] border border-purple-200">
                                <i class="bi bi-bezier2 text-[#20165c]"></i> Figma Component Library
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                <i class="bi bi-vector-pen text-amber-600"></i> Adobe Illustrator (.AI / .EPS)
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200">
                                <i class="bi bi-file-earmark-image text-blue-600"></i> Adobe Photoshop (.PSD)
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                <i class="bi bi-box text-emerald-600"></i> Blender 3D Assets
                            </span>
                        </div>
                    </div>

                    <!-- Color Palette Swatches -->
                    <div class="mb-4 p-4 rounded-4 bg-white border border-slate-200 shadow-sm">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-palette-fill text-[#f47402]"></i> Design System Color Palette
                        </h6>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-2 p-1.5 pe-3 bg-slate-50 border border-slate-200 rounded-xl">
                                <span class="rounded-lg shadow-sm" style="width: 28px; height: 28px; background: #20165c; display: inline-block;"></span>
                                <div>
                                    <div class="text-[11px] font-bold text-slate-800 font-mono">#20165C</div>
                                    <div class="text-[10px] text-slate-500">Royal Purple</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 p-1.5 pe-3 bg-slate-50 border border-slate-200 rounded-xl">
                                <span class="rounded-lg shadow-sm" style="width: 28px; height: 28px; background: #f47402; display: inline-block;"></span>
                                <div>
                                    <div class="text-[11px] font-bold text-slate-800 font-mono">#F47402</div>
                                    <div class="text-[10px] text-slate-500">Tangerine</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 p-1.5 pe-3 bg-slate-50 border border-slate-200 rounded-xl">
                                <span class="rounded-lg shadow-sm" style="width: 28px; height: 28px; background: #121826; display: inline-block;"></span>
                                <div>
                                    <div class="text-[11px] font-bold text-slate-800 font-mono">#121826</div>
                                    <div class="text-[10px] text-slate-500">Charcoal</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 p-1.5 pe-3 bg-slate-50 border border-slate-200 rounded-xl">
                                <span class="rounded-lg shadow-sm" style="width: 28px; height: 28px; background: #f8fafc; border: 1px solid #e2e8f0; display: inline-block;"></span>
                                <div>
                                    <div class="text-[11px] font-bold text-slate-800 font-mono">#F8FAFC</div>
                                    <div class="text-[10px] text-slate-500">Soft Surface</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 p-1.5 pe-3 bg-slate-50 border border-slate-200 rounded-xl">
                                <span class="rounded-lg shadow-sm" style="width: 28px; height: 28px; background: #ffffff; border: 1px solid #e2e8f0; display: inline-block;"></span>
                                <div>
                                    <div class="text-[11px] font-bold text-slate-800 font-mono">#FFFFFF</div>
                                    <div class="text-[10px] text-slate-500">Pure White</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Typography Breakdown -->
                    <div class="mb-4 p-4 rounded-4 bg-white border border-slate-200 shadow-sm">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-fonts text-[#20165c]"></i> Typography Hierarchy
                        </h6>
                        <div class="row g-3 text-xs">
                            <div class="col-12 col-sm-6">
                                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                                    <span class="text-slate-500 extra-small font-bold uppercase tracking-wider block mb-1">Display & Headings</span>
                                    <div class="text-lg font-black text-[#121826]">Plus Jakarta Sans</div>
                                    <div class="text-slate-500 text-[11px] mt-0.5">Bold / SemiBold • 24px - 48px</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                                    <span class="text-slate-500 extra-small font-bold uppercase tracking-wider block mb-1">Body & Interface</span>
                                    <div class="text-lg font-medium text-[#121826]">Inter / System UI</div>
                                    <div class="text-slate-500 text-[11px] mt-0.5">Regular / Medium • 12px - 16px</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Software Requirements Section -->
                    <div class="p-4 bg-light rounded-4 border mb-5">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cpu text-primary me-2"></i>Requirements & Compatibility</h5>
                        <div class="small text-secondary lh-lg">
                            {{ isset($resource) && $resource->requirements ? $resource->requirements : 'Compatible with Figma, Adobe Illustrator, Photoshop, and standard vector software.' }}
                        </div>
                    </div>

                    <!-- CUSTOMER RATINGS & REVIEWS SECTION -->
                    <div class="mb-5">
                        <h3 class="fw-extrabold text-dark mb-4"><i class="bi bi-star-fill text-warning me-2"></i>Customer Reviews & Ratings</h3>
                        
                        <!-- RATING SUMMARY BOX -->
                        <div class="p-4 rounded-4 bg-white border mb-4 shadow-sm">
                            <div class="row align-items-center g-4">
                                <div class="col-12 col-md-4 text-center border-md-end">
                                    @if(($totalReviews ?? 0) === 0)
                                        <div class="display-3 fw-extrabold text-muted mb-0">0.0</div>
                                        <div class="text-muted fs-5 my-1" style="opacity: 0.35;">
                                            <i class="bi bi-star"></i>
                                            <i class="bi bi-star"></i>
                                            <i class="bi bi-star"></i>
                                            <i class="bi bi-star"></i>
                                            <i class="bi bi-star"></i>
                                        </div>
                                        <div class="extra-small text-muted font-monospace fw-bold">No ratings yet</div>
                                    @else
                                        <div class="display-3 fw-extrabold text-dark mb-0">{{ number_format($avgRating, 1) }}</div>
                                        <div class="text-warning fs-5 my-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($avgRating >= $i)
                                                    <i class="bi bi-star-fill"></i>
                                                @elseif($avgRating >= ($i - 0.5))
                                                    <i class="bi bi-star-half"></i>
                                                @else
                                                    <i class="bi bi-star text-muted" style="opacity: 0.35;"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="extra-small text-muted font-monospace fw-bold">Based on {{ $totalReviews }} Customer Reviews</div>
                                    @endif
                                </div>

                                <div class="col-12 col-md-8">
                                    <div class="d-flex flex-column gap-2">
                                        @foreach([5, 4, 3, 2, 1] as $stars)
                                            @php
                                                $pct = ($totalReviews ?? 0) > 0 ? ($ratingPercentages[$stars] ?? ($starPercentages[$stars] ?? (isset($ratingBreakdown[$stars]) ? round(($ratingBreakdown[$stars] / $totalReviews) * 100) : 0))) : 0;
                                            @endphp
                                            <div class="d-flex align-items-center gap-2 extra-small">
                                                <span class="fw-bold text-muted" style="width: 40px;">{{ $stars }} ★</span>
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="text-muted font-monospace" style="width: 45px; text-align: right;">{{ $pct }}%</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- REVIEW FORM FOR VERIFIED BUYERS -->
                        @auth
                            @if(isset($hasPurchased) && $hasPurchased && (!isset($hasReviewed) || !$hasReviewed))
                                <div class="card p-4 rounded-4 border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, rgba(108, 76, 241, 0.05) 0%, rgba(255, 255, 255, 1) 100%); border: 1px solid rgba(108, 76, 241, 0.2) !important;">
                                    <h5 class="fw-extrabold text-dark mb-2"><i class="bi bi-pencil-square text-primary me-2"></i>Write a Verified Buyer Review</h5>
                                    <p class="text-secondary extra-small mb-3">You own a verified license for this asset. Share your experience with the creator and community.</p>
                                    
                                    <form action="{{ route('resource.review.store', $resource->id ?? 1) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        
                                        <!-- Interactive Star Selector -->
                                        <div class="mb-3">
                                            <label class="form-label extra-small fw-bold text-uppercase text-muted">Rating</label>
                                            <div class="d-flex align-items-center gap-3">
                                                <select name="rating" class="form-select form-select-sm rounded-pill fw-bold" style="max-width: 160px;">
                                                    <option value="5" selected>⭐⭐⭐⭐⭐ (5/5)</option>
                                                    <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                                    <option value="3">⭐⭐⭐ (3/5)</option>
                                                    <option value="2">⭐⭐ (2/5)</option>
                                                    <option value="1">⭐ (1/5)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Review Feedback -->
                                        <div class="mb-3">
                                            <label class="form-label extra-small fw-bold text-uppercase text-muted">Your Review</label>
                                            <textarea name="review" rows="3" class="form-control rounded-3" placeholder="Describe component organization, design quality, or support..." required></textarea>
                                        </div>

                                        <!-- Evidence Images (Up to 3) -->
                                        <div class="mb-3">
                                            <label class="form-label extra-small fw-bold text-uppercase text-muted">Evidence Screenshots <span class="fw-normal text-muted">(Optional, max 3 images)</span></label>
                                            <input type="file" name="evidence_images[]" multiple accept="image/png,image/jpeg,image/webp" class="form-control form-control-sm rounded-3">
                                            <div class="extra-small text-muted mt-1">Upload screenshots of design output or render proof (max 5MB each)</div>
                                        </div>

                                        <!-- Complaint Toggle -->
                                        <div class="p-2.5 rounded-3 bg-light border mb-3">
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="checkbox" name="is_complaint" value="1" id="assetReviewComplaintCheck">
                                                <label class="form-check-label extra-small text-muted" for="assetReviewComplaintCheck">
                                                    <strong class="text-dark">Report an issue or complaint</strong> with this digital asset (notifies moderation).
                                                </label>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm fw-bold">
                                            <i class="bi bi-send-fill me-1"></i> Submit Verified Review
                                        </button>
                                    </form>
                                </div>
                            @elseif(isset($hasReviewed) && $hasReviewed)
                                <div class="alert alert-success rounded-4 small border-0 shadow-sm mb-4">
                                    <i class="bi bi-check-circle-fill me-1"></i> You have already submitted a review for this asset. Thank you for your feedback!
                                </div>
                            @else
                                <div class="alert alert-light border rounded-4 small mb-4 text-muted">
                                    <i class="bi bi-info-circle-fill text-primary me-1"></i> Only verified buyers who purchased this template can write a review.
                                </div>
                            @endif
                        @else
                            <div class="alert alert-light border rounded-4 small mb-4 text-muted">
                                <i class="bi bi-lock-fill text-primary me-1"></i> Please <a href="{{ route('login') }}" class="fw-bold text-primary">Sign In</a> and purchase this resource to leave a review.
                            </div>
                        @endauth

                        <!-- REVIEWS LIST CARDS -->
                        @if(isset($reviews) && $reviews->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($reviews as $rev)
                                    @php
                                        $revUser = $rev->reviewer ?? $rev->user;
                                        $revName = $revUser->name ?? 'Customer';
                                        $revInitial = strtoupper(substr($revName, 0, 1));
                                        $revText = $rev->comment ?? $rev->review ?? '';
                                        $evidenceUrls = method_exists($rev, 'getEvidenceImageUrlsAttribute') ? $rev->evidence_image_urls : [];
                                    @endphp
                                    <div class="card p-3.5 rounded-4 border-0 shadow-sm bg-white">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2.5">
                                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold extra-small d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                    {{ $revInitial }}
                                                </div>
                                                <div>
                                                    <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                                        <span class="fw-bold text-dark small mb-0">{{ $revName }}</span>
                                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-0.5 extra-small">
                                                            <i class="bi bi-patch-check-fill me-1"></i> Verified Buyer
                                                        </span>
                                                        @if(isset($rev->is_complaint) && $rev->is_complaint)
                                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-0.5 extra-small">
                                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Issue Reported
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-warning small mb-0 text-end">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star-fill{{ $i <= ($rev->rating ?? 5) ? '' : ' text-muted opacity-25' }}"></i>
                                                    @endfor
                                                </div>
                                                <div class="extra-small text-muted text-end font-monospace">{{ $rev->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        <p class="text-secondary small mb-2 lh-base">
                                            "{{ $revText }}"
                                        </p>
                                        @if(!empty($evidenceUrls) && count($evidenceUrls) > 0)
                                            <div class="d-flex flex-wrap gap-2 mt-2 pt-2 border-top">
                                                @foreach($evidenceUrls as $idx => $url)
                                                    <a href="{{ $url }}" target="_blank" class="border rounded-2 overflow-hidden d-inline-block" style="width: 60px; height: 60px;">
                                                        <img src="{{ $url }}" alt="Evidence {{ $idx + 1 }}" class="w-100 h-100 object-fit-cover">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 bg-light rounded-4 border">
                                <i class="bi bi-chat-square-text text-muted fs-2 d-block mb-2"></i>
                                <h6 class="fw-bold text-dark mb-1">No Reviews Submitted Yet</h6>
                                <p class="text-secondary extra-small mb-0">Be the first verified customer to rate and review this template.</p>
                            </div>
                        @endif
                    </div>

                </div>

            </div>

            <!-- RIGHT COLUMN: STICKY GLASSMORPHISM PRICE & SELLER SIDEBAR (4 COLUMNS) -->
            <div class="col-12 col-lg-4">
                <div class="d-flex flex-column gap-4">
                    
                    <!-- Glassmorphism Price Card -->
                    <div class="card glass-pricing-card p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1.5 fw-bold">
                                Commercial License
                            </span>
                            <span class="small text-muted font-monospace"><i class="bi bi-shield-check me-1"></i>Verified Asset</span>
                        </div>

                        <!-- Price Tag -->
                        <div class="mb-4">
                            <div class="text-muted extra-small fw-bold text-uppercase tracking-wider">Marketplace Price</div>
                            <div class="d-flex align-items-baseline gap-2">
                                @if(isset($resource) && $resource->is_paid && $resource->price > 0)
                                    <span class="display-5 fw-extrabold text-[#f47402]">৳{{ number_format($resource->price, 2) }}</span>
                                    <span class="text-muted small fw-bold">BDT</span>
                                @else
                                    <span class="display-5 fw-extrabold text-emerald-600">Free</span>
                                @endif
                            </div>
                        </div>

                        <!-- Main Action Buttons -->
                        <div class="d-grid gap-2.5 mb-4">
                            @if(isset($hasPurchased) && $hasPurchased)
                                <div class="p-2.5 rounded-3 bg-emerald-50 border border-emerald-200 text-center mb-1">
                                    <span class="text-emerald-700 extra-small fw-bold">
                                        <i class="bi bi-patch-check-fill me-1"></i> License Owned & Unlocked
                                    </span>
                                </div>
                                <a href="{{ route('resource.download', $resource->id ?? 1) }}" class="inline-flex items-center justify-center gap-2 w-100 py-3 px-6 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white font-semibold text-decoration-none shadow-md transition-all">
                                    <i class="bi bi-cloud-arrow-down-fill fs-5"></i>
                                    <span>Download Asset</span>
                                </a>
                            @else
                                @if(isset($resource) && $resource->is_paid && $resource->price > 0)
                                    <!-- Buy Template Direct with Wallet -->
                                    <button type="button" onclick="handleBuyTemplate()" class="inline-flex items-center justify-center gap-2 w-100 py-3 px-6 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white font-semibold shadow-md transition-all border-0">
                                        <i class="bi bi-wallet2"></i>
                                        <span>Download Asset — ৳{{ number_format($resource->price, 2) }}</span>
                                    </button>

                                    <!-- Add to Cart Alternative -->
                                    <form action="{{ route('cart.store', $resource->id ?? 1) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary rounded-xl py-2.5 fw-semibold w-100 d-flex align-items-center justify-content-center gap-2 extra-small">
                                            <i class="bi bi-cart-plus"></i>
                                            <span>Add to Cart</span>
                                        </button>
                                    </form>
                                @else
                                    <!-- Free Download Direct -->
                                    <form action="{{ route('resource.buy-wallet', $resource->id ?? 1) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center gap-2 w-100 py-3 px-6 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white font-semibold shadow-md transition-all border-0">
                                            <i class="bi bi-cloud-arrow-down-fill fs-5"></i>
                                            <span>Download Asset (Free)</span>
                                        </button>
                                    </form>
                                @endif
                            @endif

                            <!-- Studio Quick Customize Action Trigger -->
                            <a href="{{ route('templates.ai-edit', $resource->id ?? 1) }}" class="inline-flex items-center justify-center gap-2 w-100 py-3 px-6 rounded-xl bg-white hover:bg-purple-50 text-[#20165c] border border-[#20165c]/30 hover:border-[#20165c] font-semibold text-decoration-none shadow-sm transition-all" title="Customize this template in the web editor">
                                <i class="bi bi-palette2 fs-5 text-[#f47402]"></i>
                                <span>Studio Quick Edit</span>
                            </a>

                            @if(isset($resource) && $resource->demo_link)
                                <a href="{{ $resource->demo_link }}" target="_blank" class="inline-flex items-center justify-center gap-2 w-100 py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 hover:text-[#20165c] hover:bg-slate-50 font-semibold text-decoration-none text-xs transition-all">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Live Demo Preview
                                </a>
                            @endif
                        </div>

                        <!-- Guarantee Badges -->
                        <div class="pt-3 border-top border-light-subtle d-flex flex-column gap-2 text-secondary small">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-lightning-charge-fill text-warning fs-5"></i>
                                <span><strong>Instant Delivery:</strong> Direct digital file download.</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-shield-check text-success fs-5"></i>
                                <span><strong>Quality Checked:</strong> Verified by Noksha Compliance.</span>
                            </div>
                        </div>

                        <!-- Technical Specifications Matrix -->
                        <div class="pt-3 border-top border-light-subtle mt-2">
                            <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-2.5">
                                <i class="bi bi-sliders text-[#20165c] me-1"></i> Technical Specifications
                            </div>
                            <div class="d-flex flex-column gap-2 text-xs">
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-slate-100">
                                    <span class="text-slate-500">License</span>
                                    <span class="fw-bold text-slate-800">Commercial Standard</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-slate-100">
                                    <span class="text-slate-500">Resolution</span>
                                    <span class="fw-bold text-slate-800">Vector Scalable / 3840×2160</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-slate-100">
                                    <span class="text-slate-500">Source Format</span>
                                    <span class="badge bg-purple-50 text-[#20165c] border border-purple-200 font-mono">{{ isset($resource) ? strtoupper($resource->file_type ?? 'ZIP') : 'FIGMA' }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1">
                                    <span class="text-slate-500">Compatibility</span>
                                    <span class="fw-bold text-slate-800">Figma, Illustrator, Photoshop</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seller Profile Card -->
                    <div class="card seller-card-figma p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <a href="{{ isset($resource) && $resource->owner ? route('user.profile', $resource->owner->username ?? $resource->owner->id) : route('seller.demo') }}" class="text-decoration-none">
                                @if(isset($resource) && $resource->owner && $resource->owner->avatar)
                                    <img src="{{ asset('storage/' . $resource->owner->avatar) }}" alt="{{ $resource->owner->name }}" class="rounded-circle border" style="width: 56px; height: 56px; object-fit: cover;">
                                @else
                                    <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 56px; height: 56px;">
                                        {{ strtoupper(substr(isset($resource) && $resource->owner ? $resource->owner->name : 'N', 0, 1)) }}
                                    </div>
                                @endif
                            </a>
                            <div>
                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                    <h6 class="fw-bold text-dark mb-0">
                                        <a href="{{ isset($resource) && $resource->owner ? route('user.profile', $resource->owner->username ?? $resource->owner->id) : route('seller.demo') }}" class="text-dark text-decoration-none hover-primary">
                                            {{ isset($resource) && $resource->owner ? $resource->owner->name : 'Noksha Creator' }}
                                        </a>
                                    </h6>
                                    @if(isset($resource) && $resource->owner && $resource->owner->isVerifiedCreator())
                                        <span class="badge rounded-pill px-2 py-0.5 extra-small fw-bold text-emerald-700 bg-emerald-50 border border-emerald-300 shadow-sm" style="box-shadow: 0 0 8px rgba(16, 185, 129, 0.25);" title="Verified Creator">
                                            <i class="bi bi-patch-check-fill text-emerald-500"></i> Verified
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-1.5 mt-1 flex-wrap">
                                    @if(isset($resource) && $resource->owner && $resource->owner->isProAuthor())
                                        <span class="badge rounded-pill px-2 py-0.5 extra-small fw-bold text-amber-700 bg-amber-50 border border-amber-300 shadow-sm" style="box-shadow: 0 0 8px rgba(245, 158, 11, 0.25);">
                                            <i class="bi bi-award-fill text-amber-500 me-0.5"></i> Pro Author
                                        </span>
                                    @else
                                        <div class="small text-muted"><i class="bi bi-patch-check-fill text-primary me-1"></i> Author</div>
                                    @endif
                                </div>
                                @if(($totalReviews ?? 0) > 0)
                                    <div class="small text-warning fw-bold mt-1"><i class="bi bi-star-fill me-1"></i>{{ number_format($avgRating, 1) }} ({{ $totalReviews }} {{ Str::plural('Review', $totalReviews) }})</div>
                                @else
                                    <div class="small text-muted mt-1"><i class="bi bi-star me-1"></i>0.0 (No reviews yet)</div>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-2 mt-3">
                            <a href="{{ isset($resource) && $resource->owner ? route('inbox.index', ['recipient' => $resource->owner->id]) : route('seller.demo') }}" class="btn rounded-pill w-100 fw-bold btn-sm py-2 text-white bg-[#20165c] hover:bg-[#181045] transition shadow-sm">
                                <i class="bi bi-chat-dots-fill me-1.5"></i> Hire for Custom Job
                            </a>
                            <a href="{{ isset($resource) && $resource->owner ? route('user.profile', $resource->owner->username ?? $resource->owner->id) : route('seller.demo') }}" class="btn btn-outline-secondary rounded-pill w-100 fw-bold btn-sm">
                                <i class="bi bi-person-circle me-1"></i> View Creator Profile
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- RELATED RESOURCES SECTION -->
<section class="py-5 py-lg-6" style="background-color: #f8fafc;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="badge px-3 py-1 rounded-pill text-uppercase fw-bold small mb-1" style="background: rgba(32, 22, 92, 0.08); color: #20165c;">
                    Recommendations
                </span>
                <h3 class="fw-extrabold text-[#121826] mb-0">Related Marketplace Resources</h3>
            </div>
            <a href="{{ route('home') }}#templates" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold btn-sm">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @if(isset($relatedResources) && $relatedResources->count() > 0)
                @foreach($relatedResources as $rel)
                    @php
                        $relCover = null;
                        if (!empty($rel->preview_image)) {
                            if (str_starts_with($rel->preview_image, 'http://') || str_starts_with($rel->preview_image, 'https://')) {
                                $relCover = $rel->preview_image;
                            } else {
                                $relCover = asset('storage/' . ltrim(str_replace('storage/', '', $rel->preview_image), '/'));
                            }
                        } elseif (!empty($rel->thumbnail_url)) {
                            $relCover = $rel->thumbnail_url;
                        }
                    @endphp
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
                            <div class="position-relative overflow-hidden bg-slate-50 border-bottom border-slate-100" style="height: 180px;">
                                @if($relCover)
                                    <img src="{{ $relCover }}" class="w-100 h-100 object-fit-cover" alt="{{ $rel->title }}" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';">
                                @else
                                    <div class="w-100 h-100 card-grad-2 p-4 d-flex align-items-center justify-content-center text-white">
                                        <svg width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                        </svg>
                                    </div>
                                @endif
                                <span class="position-absolute top-0 start-0 m-2.5 badge bg-white/95 text-[#121826] border border-slate-200 rounded-pill shadow-sm px-2.5 py-1 fw-bold extra-small">
                                    {{ $rel->category ? $rel->category->name : 'General' }}
                                </span>
                            </div>
                            <div class="p-4 d-flex flex-column flex-grow-1 justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between gap-2 mb-1.5">
                                        <h6 class="fw-bold text-[#121826] mb-0 text-truncate flex-grow-1" title="{{ $rel->title }}">
                                            <a href="{{ route('resource.show', $rel->slug ?? $rel->id) }}" class="text-[#121826] hover:text-[#20165c] text-decoration-none">
                                                {{ $rel->title }}
                                            </a>
                                        </h6>
                                        <span class="fw-black text-[#f47402] font-mono text-sm shrink-0">
                                            {{ $rel->is_paid && $rel->price > 0 ? '৳' . number_format($rel->price, 2) : 'Free' }}
                                        </span>
                                    </div>
                                    <p class="text-slate-500 extra-small mb-3 line-clamp-2">{{ Str::limit($rel->description, 75) }}</p>
                                </div>
                                <div class="pt-3 border-top border-slate-100 d-flex align-items-center justify-content-between">
                                    <span class="badge bg-slate-100 text-slate-600 px-2 py-0.5 rounded extra-small uppercase font-mono">
                                        {{ strtoupper($rel->file_type ?? 'ZIP') }}
                                    </span>
                                    <a href="{{ route('resource.show', $rel->slug ?? $rel->id) }}" class="btn btn-sm rounded-xl px-3 py-1.5 text-white bg-[#20165c] hover:bg-[#181045] font-semibold extra-small shadow-sm text-decoration-none">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

<!-- INTERACTIVE GALLERY PREVIEW SCRIPT -->
<script>
function switchPreview(gradClass, titleText) {
    const display = document.getElementById('mainPreviewDisplay');
    const canvas = document.getElementById('previewCanvas');
    
    // Remove previous grad classes
    display.className = 'resource-preview-box p-4 d-flex align-items-center justify-content-center text-white ' + gradClass;
    
    // Update Text Title
    canvas.querySelector('h4').textContent = titleText;

    // Active state on thumbs
    document.querySelectorAll('.thumb-card').forEach(t => t.classList.remove('active'));
    event.currentTarget.classList.add('active');
}

function handleBuyTemplate() {
    @guest
        window.location.href = "{{ route('login') }}";
        return;
    @else
        const userBalance = {{ auth()->check() ? (float)(auth()->user()->wallet->balance ?? 0) : 0 }};
        const templatePrice = {{ (float)($resource->price ?? 0) }};

        if (userBalance < templatePrice) {
            const modalEl = document.getElementById('insufficientBalanceModal');
            if (window.bootstrap && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            } else {
                alert(`Insufficient wallet balance. You need ৳${(templatePrice - userBalance).toFixed(2)} more. Please recharge your wallet.`);
                window.location.href = "{{ route('wallet.index') }}";
            }
        } else {
            const modalEl = document.getElementById('confirmPurchaseModal');
            if (window.bootstrap && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            } else {
                if (confirm(`Confirm purchase of "{{ $resource->title }}" for ৳${templatePrice.toFixed(2)} using your wallet?`)) {
                    document.getElementById('walletPurchaseForm').submit();
                }
            }
        }
    @endguest
}
</script>

<!-- MODAL: Insufficient Wallet Balance Prompt -->
<div class="modal fade" id="insufficientBalanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow-lg" style="background: #ffffff; border: 1px solid #e2e8f0 !important; border-radius: 1.5rem; color: #121826;">
            <div class="modal-body p-4 text-center">
                <div style="width: 64px; height: 64px; border-radius: 1.25rem; background: rgba(239, 68, 68, 0.15); color: #F87171; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; border: 1px solid rgba(239, 68, 68, 0.35);">
                    <i class="bi bi-wallet-fill fs-2 text-danger"></i>
                </div>
                
                <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill mb-2" 
                     style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.35); color: #FCA5A5;">
                    <span class="extra-small fw-extrabold font-monospace text-uppercase tracking-wider">
                        Shortfall: ৳{{ number_format(max(0, ($resource->price ?? 0) - (auth()->check() ? (auth()->user()->wallet->balance ?? 0) : 0)), 2) }}
                    </span>
                </div>

                <h3 class="h5 fw-extrabold text-[#121826] mb-2">Insufficient Wallet Balance</h3>
                <p class="text-slate-600 extra-small mb-3" style="line-height: 1.6;">
                    Insufficient Wallet Balance. Please deposit funds to continue.
                </p>
                <p class="text-slate-500 extra-small mb-4" style="line-height: 1.6;">
                    This premium design costs <strong class="text-[#121826]">৳{{ number_format($resource->price ?? 0, 2) }}</strong>. Your current available wallet balance is <strong class="text-slate-300">৳{{ number_format(auth()->check() ? (auth()->user()->wallet->balance ?? 0) : 0, 2) }}</strong>.
                </p>

                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('wallet.index') }}" class="btn btn-purple-cta py-2.5 rounded-pill fw-bold d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span>Recharge Wallet & Add Funds</span>
                    </a>
                    <button type="button" class="btn btn-outline-slate rounded-pill py-2 text-slate-500 extra-small" data-bs-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Confirm Template Purchase with Wallet -->
<div class="modal fade" id="confirmPurchaseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow-lg" style="background: #ffffff; border: 1px solid #e2e8f0 !important; border-radius: 1.5rem; color: #121826;">
            <div class="modal-body p-4 text-center">
                <div style="width: 64px; height: 64px; border-radius: 1.25rem; background: rgba(124, 58, 237, 0.2); color: #C4B5FD; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; border: 1px solid rgba(124, 58, 237, 0.4);">
                    <i class="bi bi-bag-check-fill fs-2 text-warning"></i>
                </div>
                
                <h3 class="h5 fw-extrabold text-[#121826] mb-1">Confirm Instant Purchase</h3>
                <p class="text-slate-500 extra-small mb-3">
                    Unlock full commercial license and instant digital source file download.
                </p>

                <div class="p-3 rounded-3 mb-4 text-start" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="d-flex justify-content-between extra-small text-slate-400 mb-1.5">
                        <span>Asset:</span>
                        <strong class="text-[#121826] text-truncate ms-2" style="max-width: 220px;">{{ $resource->title }}</strong>
                    </div>
                    <div class="d-flex justify-content-between extra-small text-slate-400 mb-1.5">
                        <span>Template Price:</span>
                        <strong class="text-[#121826] font-monospace">৳{{ number_format($resource->price ?? 0, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between extra-small text-slate-400 mb-1.5">
                        <span>Your Current Balance:</span>
                        <strong class="text-emerald-400 font-monospace">৳{{ number_format(auth()->check() ? (auth()->user()->wallet->balance ?? 0) : 0, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between extra-small text-slate-400 pt-2 border-top border-slate-800">
                        <span>Balance After Purchase:</span>
                        <strong class="text-purple-300 font-monospace">
                            ৳{{ number_format(max(0, (auth()->check() ? (auth()->user()->wallet->balance ?? 0) : 0) - ($resource->price ?? 0)), 2) }}
                        </strong>
                    </div>
                </div>

                <form id="walletPurchaseForm" action="{{ route('resource.buy-wallet', $resource->id) }}" method="POST">
                    @csrf
                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-purple-cta py-2.5 rounded-pill fw-bold d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Confirm & Unlock Files</span>
                        </button>
                        <button type="button" class="btn btn-outline-slate rounded-pill py-2 text-slate-500 extra-small" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
