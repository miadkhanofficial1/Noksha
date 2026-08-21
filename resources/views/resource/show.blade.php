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
                <li class="breadcrumb-item"><a href="{{ route('home') }}#templates" class="text-decoration-none text-primary">UI Kits</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">Fintech Mobile App UI Kit</li>
            </ol>
        </nav>

        <!-- Product Header Bar -->
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1.5 fw-bold small">
                        <i class="bi bi-grid-fill me-1"></i> UI Kit & System
                    </span>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1.5 fw-bold small">
                        <i class="bi bi-patch-check-fill me-1"></i> Verified Asset
                    </span>
                </div>
                <h1 class="display-5 fw-extrabold text-dark mb-2">
                    Fintech Mobile App UI Kit
                </h1>
                <p class="fs-6 text-secondary mb-0">
                    Complete financial management mobile UI design system with 50+ vector screens, dark/light modes, and Figma components.
                </p>
            </div>
            
            <div class="col-lg-4 text-lg-end">
                <div class="d-inline-flex flex-wrap gap-3 p-3 bg-white rounded-4 border shadow-sm align-items-center justify-content-center">
                    <div class="text-center">
                        <div class="fw-extrabold text-dark fs-5 mb-0"><i class="bi bi-star-fill text-warning me-1"></i>4.9</div>
                        <div class="extra-small text-muted fw-semibold">240 Reviews</div>
                    </div>
                    <div class="vr opacity-20"></div>
                    <div class="text-center">
                        <div class="fw-extrabold text-dark fs-5 mb-0"><i class="bi bi-download text-primary me-1"></i>5.2k</div>
                        <div class="extra-small text-muted fw-semibold">Downloads</div>
                    </div>
                    <div class="vr opacity-20"></div>
                    <div class="text-center">
                        <div class="fw-extrabold text-dark fs-5 mb-0"><i class="bi bi-eye text-info me-1"></i>18.4k</div>
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
                    <div id="mainPreviewDisplay" class="resource-preview-box card-grad-1 p-4 d-flex align-items-center justify-content-center text-white">
                        <div class="text-center" id="previewCanvas">
                            <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90 mb-3">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                <line x1="12" y1="17" x2="12" y2="21"></line>
                            </svg>
                            <h4 class="fw-extrabold mb-1">Dashboard & Wallet Overview Screen</h4>
                            <p class="small text-white text-opacity-80 mb-0">High-resolution Figma Vector Preview</p>
                        </div>

                        <!-- Top Floating Badges -->
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3.5 py-2 fw-bold small">
                            <i class="bi bi-award-fill text-primary me-1"></i> Editor's Choice
                        </span>
                        <span class="position-absolute top-0 end-0 m-3 badge bg-dark bg-opacity-75 text-white rounded-pill px-3 py-1.5 small font-monospace">
                            Figma v116+
                        </span>
                    </div>
                </div>

                <!-- Gallery Thumbnails (4 Interactive Thumbnails) -->
                <div class="row g-2.5 mb-5">
                    <div class="col-3">
                        <div class="thumb-card active p-2 d-flex align-items-center justify-content-center text-white card-grad-1" onclick="switchPreview('card-grad-1', 'Dashboard & Wallet Overview Screen')">
                            <span class="small fw-bold text-center">Dashboard</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="thumb-card p-2 d-flex align-items-center justify-content-center text-white card-grad-3" onclick="switchPreview('card-grad-3', 'Transaction & Analytics Screen')">
                            <span class="small fw-bold text-center">Analytics</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="thumb-card p-2 d-flex align-items-center justify-content-center text-white card-grad-2" onclick="switchPreview('card-grad-2', 'Card Management & Security Screen')">
                            <span class="small fw-bold text-center">Cards</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="thumb-card p-2 d-flex align-items-center justify-content-center text-white card-grad-4" onclick="switchPreview('card-grad-4', 'Dark Mode Vector Variants')">
                            <span class="small fw-bold text-center">Dark Mode</span>
                        </div>
                    </div>
                </div>

                <!-- AI-Generated Tags -->
                <div class="mb-5 p-4 rounded-4 bg-light border">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-robot text-primary"></i> AI-Generated Tags & Metadata
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('home') }}" class="ai-tag-pill text-decoration-none"><i class="bi bi-tag-fill"></i> #fintech</a>
                        <a href="{{ route('home') }}" class="ai-tag-pill text-decoration-none"><i class="bi bi-tag-fill"></i> #mobile-ui</a>
                        <a href="{{ route('home') }}" class="ai-tag-pill text-decoration-none"><i class="bi bi-tag-fill"></i> #figma-system</a>
                        <a href="{{ route('home') }}" class="ai-tag-pill text-decoration-none"><i class="bi bi-tag-fill"></i> #dark-mode</a>
                        <a href="{{ route('home') }}" class="ai-tag-pill text-decoration-none"><i class="bi bi-tag-fill"></i> #crypto-wallet</a>
                        <a href="{{ route('home') }}" class="ai-tag-pill text-decoration-none"><i class="bi bi-tag-fill"></i> #ios-design</a>
                        <a href="{{ route('home') }}" class="ai-tag-pill text-decoration-none"><i class="bi bi-tag-fill"></i> #vector-kit</a>
                    </div>
                </div>

                <!-- Overview & Description -->
                <div class="mb-5">
                    <h3 class="fw-extrabold text-dark mb-3">Product Overview</h3>
                    <p class="text-secondary lh-lg mb-4">
                        The <strong>Fintech Mobile App UI Kit</strong> is a modern, pixel-perfect financial management design system built for Figma. It features 50+ ready-to-use mobile screens covering onboarding, digital wallet overview, transaction statistics, bill payments, card controls, and user settings.
                    </p>

                    <!-- What's Included Section -->
                    <div class="p-4 bg-white rounded-4 border mb-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-box-seam text-primary me-2"></i>What's Included</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 text-dark small fw-semibold">
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i> 50+ Vector Mobile Screens (.fig, .svg)
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 text-dark small fw-semibold">
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i> Light & Dark Auto-Layout Variants
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 text-dark small fw-semibold">
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i> Full Color & Typography Token System
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 text-dark small fw-semibold">
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i> 100+ Custom UI Components & Icons
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 text-dark small fw-semibold">
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i> Full Commercial Rights License
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 text-dark small fw-semibold">
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i> Free Lifetime Version Updates
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Requirements Section -->
                    <div class="p-4 bg-light rounded-4 border">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cpu text-primary me-2"></i>Software Requirements</h5>
                        <ul class="list-unstyled text-secondary small mb-0 d-flex flex-column gap-2">
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-app text-primary"></i> <strong>Figma:</strong> Desktop v116+ or Figma Web Application.
                            </li>
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-type text-primary"></i> <strong>Fonts:</strong> Inter & Plus Jakarta Sans (Google Free License).
                            </li>
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-aspect-ratio text-primary"></i> <strong>Scale:</strong> 100% Vector Scalable (iPhone 15 Pro & Android Grid).
                            </li>
                        </ul>
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
                            <span class="small text-muted font-monospace"><i class="bi bi-clock-history me-1"></i>Updated Aug 2026</span>
                        </div>

                        <!-- Price Tag -->
                        <div class="mb-4">
                            <div class="text-muted extra-small fw-bold text-uppercase tracking-wider">Total Price</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="display-5 fw-extrabold text-dark">৳499</span>
                                <span class="text-muted text-decoration-line-through fs-6">৳999</span>
                                <span class="badge bg-danger rounded-pill px-2.5 py-1 small">50% OFF</span>
                            </div>
                        </div>

                        <!-- Main Action Buttons -->
                        <div class="d-grid gap-2.5 mb-4">
                            <button type="button" class="btn btn-purple-cta btn-lg rounded-pill py-3 fw-bold">
                                <i class="bi bi-bag-check-fill me-2"></i> Buy & Download Now
                            </button>
                            <button type="button" onclick="alert('Opening Live Interactive Figma Preview Modal...')" class="btn btn-outline-purple btn-lg rounded-pill py-3 fw-bold">
                                <i class="bi bi-eye-fill me-2"></i> Live Demo Preview
                            </button>
                        </div>

                        <!-- Guarantee Badges -->
                        <div class="pt-3 border-top border-light-subtle d-flex flex-column gap-2 text-secondary small">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-lightning-charge-fill text-warning fs-5"></i>
                                <span><strong>Instant Delivery:</strong> Download files immediately after purchase.</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-shield-check text-success fs-5"></i>
                                <span><strong>Quality Checked:</strong> Verified vector layout by Noksha Moderation.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Seller Profile Card -->
                    <div class="card seller-card-figma p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                                <i class="bi bi-person-circle fs-2 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Noksha Studio</h6>
                                <div class="small text-muted"><i class="bi bi-patch-check-fill text-primary me-1"></i> Pro Verified Author</div>
                                <div class="small text-warning fw-bold"><i class="bi bi-star-fill me-1"></i>4.98 (1.4k Ratings)</div>
                            </div>
                        </div>

                        <div class="row g-2 text-center bg-light p-2.5 rounded-3 mb-3 border">
                            <div class="col-6">
                                <div class="fw-bold text-dark fs-6">142</div>
                                <div class="extra-small text-muted">Total Assets</div>
                            </div>
                            <div class="col-6">
                                <div class="fw-bold text-dark fs-6">45.8k</div>
                                <div class="extra-small text-muted">Total Sales</div>
                            </div>
                        </div>

                        <a href="{{ route('seller.demo') }}" class="btn btn-outline-secondary rounded-pill w-100 fw-bold btn-sm">
                            <i class="bi bi-shop me-1"></i> View Seller Portfolio
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
                <h3 class="fw-extrabold text-dark mb-0">Related Resources <span class="text-primary">(সম্পর্কিত ডিজাইন)</span></h3>
            </div>
            <a href="{{ route('home') }}#templates" class="btn btn-outline-purple rounded-pill px-4 py-2 fw-bold btn-sm">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            <!-- Card 1 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 template-card-figma">
                    <div class="template-preview-area card-grad-2 p-4 d-flex align-items-center justify-content-center text-white" style="height: 200px;">
                        <svg width="68" height="68" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                            <polyline points="2 17 12 22 22 17"></polyline>
                            <polyline points="2 12 12 17 22 12"></polyline>
                        </svg>
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                            Vector
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between text-muted small mb-2">
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-star-fill text-warning me-1"></i>4.8 (94)
                            </span>
                            <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>2.8k</span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate">Corporate Business Flyer</h5>
                        <p class="card-text text-secondary small mb-4 flex-grow-1">Print-ready A4 vector layout for corporate brand presentations.</p>
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
