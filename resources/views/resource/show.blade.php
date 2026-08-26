@extends('layouts.app')

@section('title', 'Fintech Mobile App UI Kit - Noksha (নকশা)')

@section('content')

<!-- CUSTOM FIGMA/DRIBBBLE RESOURCE DETAILS STYLES -->
<style>
    .resource-details-hero {
        background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%);
        position: relative;
    }

    /* Main Preview Container */
    .resource-preview-main {
        border-radius: 1.5rem !important; /* 24px Radius */
        border: 1px solid rgba(108, 76, 241, 0.15) !important;
        box-shadow: 0 15px 35px -10px rgba(108, 76, 241, 0.12) !important;
        overflow: hidden;
        background: #ffffff;
        position: relative;
    }

    .resource-preview-box {
        height: 420px;
        position: relative;
        overflow: hidden;
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
        background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 8px 20px -4px rgba(108, 76, 241, 0.4);
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #5A3DE0 0%, #4327C6 100%);
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 14px 28px -4px rgba(108, 76, 241, 0.5);
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
                <li class="breadcrumb-item active text-dark" aria-current="page">{{ isset($resource) ? $resource->title : 'Fintech Mobile App UI Kit' }}</li>
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
                </div>
                <h1 class="display-5 fw-extrabold text-dark mb-2">
                    {{ isset($resource) ? $resource->title : 'Fintech Mobile App UI Kit' }}
                </h1>
                <p class="fs-6 text-secondary mb-0">
                    {{ isset($resource) ? Str::limit($resource->description, 180) : 'Complete financial management mobile UI design system with vector components.' }}
                </p>
            </div>
            
            <div class="col-lg-4 text-lg-end">
                <div class="d-inline-flex flex-wrap gap-3 p-3 bg-white rounded-4 border shadow-sm align-items-center justify-content-center">
                    <div class="text-center">
                        <div class="fw-extrabold text-dark fs-5 mb-0"><i class="bi bi-star-fill text-warning me-1"></i>4.9</div>
                        <div class="extra-small text-muted fw-semibold">120 Reviews</div>
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
                <div class="resource-preview-main mb-4">
                    <div id="mainPreviewDisplay" class="resource-preview-box p-0 position-relative overflow-hidden" style="height: 420px; background: #1E1B4B;">
                        @if(isset($resource) && $resource->preview_image)
                            <img src="{{ asset('storage/' . $resource->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $resource->title }}">
                        @else
                            <div class="w-100 h-100 card-grad-1 p-4 d-flex align-items-center justify-content-center text-white text-center">
                                <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90 mb-3">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg>
                                <h4 class="fw-extrabold mb-1">{{ isset($resource) ? $resource->title : 'Vector Preview Screen' }}</h4>
                                <p class="small text-white text-opacity-80 mb-0">High-resolution Design Asset Preview</p>
                            </div>
                        @endif

                        <!-- Top Floating Badges -->
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3.5 py-2 fw-bold small">
                            <i class="bi bi-award-fill text-primary me-1"></i> Editor's Choice
                        </span>
                        <span class="position-absolute top-0 end-0 m-3 badge bg-dark bg-opacity-75 text-white rounded-pill px-3 py-1.5 small font-monospace">
                            {{ isset($resource) ? strtoupper($resource->file_type ?? 'ZIP') : 'FIGMA' }} Format
                        </span>
                    </div>
                </div>

                <!-- AI-Generated Tags -->
                <div class="mb-5 p-4 rounded-4 bg-light border">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-robot text-primary"></i> AI Tags & Metadata
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

                <!-- Overview & Description -->
                <div class="mb-5">
                    <h3 class="fw-extrabold text-dark mb-3">Product Description</h3>
                    <p class="text-secondary lh-lg mb-4">
                        {{ isset($resource) ? $resource->description : 'The Fintech Mobile UI Kit is a modern, pixel-perfect design system built for professional designers and developers.' }}
                    </p>

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
                                    <div class="display-3 fw-extrabold text-dark mb-0">{{ $avgRating ?? '4.9' }}</div>
                                    <div class="text-warning fs-5 my-1">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                    </div>
                                    <div class="extra-small text-muted font-monospace fw-bold">Based on {{ $totalReviews ?? 0 }} Customer Reviews</div>
                                </div>

                                <div class="col-12 col-md-8">
                                    <div class="d-flex flex-column gap-2">
                                        @foreach([5, 4, 3, 2, 1] as $stars)
                                            @php
                                                $count = $ratingBreakdown[$stars] ?? 0;
                                                $pct = ($totalReviews ?? 0) > 0 ? round(($count / $totalReviews) * 100) : ($stars >= 4 ? 80 : 5);
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
                                    
                                    <form action="{{ route('resource.review.store', $resource->id ?? 1) }}" method="POST">
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

                                        <button type="submit" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm fw-bold">
                                            <i class="bi bi-send-fill me-1"></i> Submit Review
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
                                    <div class="card p-3.5 rounded-4 border-0 shadow-sm bg-white">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2.5">
                                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold extra-small d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                    {{ strtoupper(substr($rev->user ? $rev->user->name : 'C', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark small mb-0">{{ $rev->user ? $rev->user->name : 'Customer' }}</div>
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-0.5 extra-small">
                                                        <i class="bi bi-patch-check-fill me-1"></i> Verified Buyer
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-warning small mb-0">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star-fill{{ $i <= $rev->rating ? '' : ' text-muted opacity-25' }}"></i>
                                                    @endfor
                                                </div>
                                                <div class="extra-small text-muted text-end font-monospace">{{ $rev->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        <p class="text-secondary small mb-0 lh-base">
                                            "{{ $rev->review }}"
                                        </p>
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
                                    <span class="display-5 fw-extrabold text-dark">৳{{ number_format($resource->price, 2) }}</span>
                                @else
                                    <span class="display-5 fw-extrabold text-success">Free</span>
                                @endif
                            </div>
                        </div>

                        <!-- Main Action Buttons -->
                        <div class="d-grid gap-2.5 mb-4">
                            @if(isset($hasPurchased) && $hasPurchased)
                                <a href="{{ route('resource.download', $resource->id ?? 1) }}" class="btn btn-purple-cta btn-lg rounded-pill py-3 fw-bold">
                                    <i class="bi bi-cloud-arrow-down-fill me-2"></i> Download Asset Now
                                </a>
                            @else
                                <form action="{{ route('cart.store', $resource->id ?? 1) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-purple-cta btn-lg rounded-pill py-3 fw-bold w-100">
                                        <i class="bi bi-cart-plus-fill me-2"></i> Add to Cart — {{ isset($resource) && $resource->is_paid && $resource->price > 0 ? '৳' . number_format($resource->price, 2) : 'Free' }}
                                    </button>
                                </form>
                            @endif

                            @if(isset($resource) && $resource->demo_link)
                                <a href="{{ $resource->demo_link }}" target="_blank" class="btn btn-outline-purple btn-lg rounded-pill py-3 fw-bold">
                                    <i class="bi bi-box-arrow-up-right me-2"></i> Live Demo Preview
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
                    </div>

                    <!-- Seller Profile Card -->
                    <div class="card seller-card-figma p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 56px; height: 56px;">
                                {{ strtoupper(substr(isset($resource) && $resource->owner ? $resource->owner->name : 'N', 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">{{ isset($resource) && $resource->owner ? $resource->owner->name : 'Noksha Creator' }}</h6>
                                <div class="small text-muted"><i class="bi bi-patch-check-fill text-primary me-1"></i> Pro Verified Author</div>
                                <div class="small text-warning fw-bold"><i class="bi bi-star-fill me-1"></i>4.9 (120 Ratings)</div>
                            </div>
                        </div>

                        <a href="{{ route('seller.demo') }}" class="btn btn-outline-secondary rounded-pill w-100 fw-bold btn-sm">
                            <i class="bi bi-shop me-1"></i> View Seller Storefront
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- RELATED RESOURCES SECTION -->
<section class="py-5 py-lg-6" style="background-color: #F8F7FF;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="badge px-3 py-1 rounded-pill text-uppercase fw-bold small mb-1" style="background: rgba(108, 76, 241, 0.08); color: #6C4CF1;">
                    Recommendations
                </span>
                <h3 class="fw-extrabold text-dark mb-0">Related Marketplace Resources <span class="text-primary">(সম্পর্কিত ডিজাইন)</span></h3>
            </div>
            <a href="{{ route('home') }}#templates" class="btn btn-outline-purple rounded-pill px-4 py-2 fw-bold btn-sm">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @if(isset($relatedResources) && $relatedResources->count() > 0)
                @foreach($relatedResources as $rel)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 template-card-figma">
                            <div class="template-preview-area p-0 overflow-hidden position-relative" style="height: 200px; background: #1E1B4B;">
                                @if($rel->preview_image)
                                    <img src="{{ asset('storage/' . $rel->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $rel->title }}">
                                @else
                                    <div class="w-100 h-100 card-grad-2 p-4 d-flex align-items-center justify-content-center text-white">
                                        <svg width="68" height="68" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                        </svg>
                                    </div>
                                @endif
                                <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                                    {{ $rel->category ? $rel->category->name : 'General' }}
                                </span>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="card-title fw-bold text-dark mb-1 text-truncate">{{ $rel->title }}</h5>
                                <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-2">{{ Str::limit($rel->description, 80) }}</p>
                                <div class="d-flex align-items-center justify-content-between pt-3 border-top border-light-subtle">
                                    <span class="fw-extrabold text-dark">{{ $rel->is_paid && $rel->price > 0 ? '৳' . number_format($rel->price, 2) : 'Free' }}</span>
                                    <a href="{{ route('resource.show', $rel->slug ?? $rel->id) }}" class="btn btn-purple-cta rounded-pill px-4 py-2 btn-sm">
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
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <span class="fw-extrabold text-success fs-5">Free</span>
                            <a href="{{ route('resource.demo') }}" class="btn btn-purple-cta rounded-pill px-4 py-2 btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 template-card-figma">
                    <div class="template-preview-area card-grad-3 p-4 d-flex align-items-center justify-content-center text-white" style="height: 200px;">
                        <svg width="68" height="68" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                            Social Media
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between text-muted small mb-2">
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-star-fill text-warning me-1"></i>5.0 (210)
                            </span>
                            <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>3.1k</span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate">Instagram Post & Story Bundle</h5>
                        <p class="card-text text-secondary small mb-4 flex-grow-1">30 minimalist social media layouts for agency marketing.</p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <span class="fw-extrabold text-dark fs-5">৳299</span>
                            <a href="{{ route('resource.demo') }}" class="btn btn-purple-cta rounded-pill px-4 py-2 btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 template-card-figma">
                    <div class="template-preview-area card-grad-4 p-4 d-flex align-items-center justify-content-center text-white" style="height: 200px;">
                        <svg width="68" height="68" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                            3D Mockup
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between text-muted small mb-2">
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-star-fill text-warning me-1"></i>4.9 (67)
                            </span>
                            <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>950</span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate">3D Isometric Tech Icons</h5>
                        <p class="card-text text-secondary small mb-4 flex-grow-1">High-res transparent PNG & Blender 3D source files included.</p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <span class="fw-extrabold text-dark fs-5">৳199</span>
                            <a href="{{ route('resource.demo') }}" class="btn btn-purple-cta rounded-pill px-4 py-2 btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
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
</script>

@endsection
