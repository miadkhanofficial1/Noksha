@extends('layouts.app')

@section('title', 'Noksha (নকশা) - বাংলার সেরা Graphic Marketplace')

@section('content')

<!-- CUSTOM FIGMA-LEVEL CSS ANIMATIONS & UTILITIES -->
<style>
    /* Smooth Keyframes & Transitions */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(24px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes floatSlow {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-8px) rotate(1deg); }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .animate-float {
        animation: floatSlow 6s ease-in-out infinite;
    }

    /* Hero Gradient & Glow */
    .hero-bg-gradient {
        background: linear-gradient(135deg, #6C4CF1 0%, #8B5CF6 50%, #9F7AEA 100%);
        position: relative;
    }

    .hero-radial-glow {
        background: 
            radial-gradient(circle at 18% 25%, rgba(255, 255, 255, 0.2) 0%, transparent 45%),
            radial-gradient(circle at 82% 75%, rgba(255, 255, 255, 0.15) 0%, transparent 45%),
            radial-gradient(circle at 50% 50%, rgba(159, 122, 234, 0.3) 0%, transparent 60%);
    }

    /* Glassmorphism Search Bar */
    .hero-search-box {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
    }

    .hero-search-box:focus-within {
        box-shadow: 0 15px 35px -5px rgba(108, 76, 241, 0.35), 0 0 0 4px rgba(255, 255, 255, 0.35) !important;
        transform: translateY(-2px);
    }

    /* Premium CTA Buttons */
    .btn-cta-primary {
        background: linear-gradient(135deg, #ffffff 0%, #F8FAFC 100%);
        color: #6C4CF1 !important;
        border: none;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-cta-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.2);
        color: #5A3DE0 !important;
    }

    .btn-cta-secondary {
        background: rgba(255, 255, 255, 0.14);
        color: #ffffff !important;
        border: 1.5px solid rgba(255, 255, 255, 0.35) !important;
        backdrop-filter: blur(12px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-cta-secondary:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.6) !important;
        transform: translateY(-3px);
        color: #ffffff !important;
    }

    /* Glass Illustration Card */
    .glass-mockup-card {
        background: rgba(255, 255, 255, 0.16);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.35) !important;
        box-shadow: 0 30px 60px -12px rgba(15, 23, 42, 0.35);
        transition: all 0.4s ease;
    }

    .glass-mockup-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 35px 70px -10px rgba(15, 23, 42, 0.4);
    }

    /* Featured Template Section & Cards */
    .featured-templates-section {
        background-color: #F8F7FF;
        position: relative;
    }

    .featured-templates-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #6C4CF1 0%, #8B5CF6 50%, #9F7AEA 100%);
    }

    .template-card-figma {
        border-radius: 1.5rem !important; /* 24px Radius */
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 30px -10px rgba(108, 76, 241, 0.08) !important;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
        overflow: hidden;
        background: #ffffff;
    }

    .template-card-figma:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 45px -10px rgba(108, 76, 241, 0.22) !important;
        border-color: rgba(108, 76, 241, 0.35) !important;
    }

    .template-preview-area {
        height: 200px;
        position: relative;
        overflow: hidden;
    }

    .template-preview-area svg {
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .template-card-figma:hover .template-preview-area svg {
        transform: scale(1.08) rotate(-1deg);
    }

    .btn-purple-cta {
        background-color: #6C4CF1;
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-purple-cta:hover {
        background-color: #5A3DE0;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px -4px rgba(108, 76, 241, 0.4);
        color: #ffffff !important;
    }

    /* Professional Categories Card Styling */
    .cat-card-figma {
        border-radius: 1.5rem !important; /* 24px Radius */
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.06) !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        overflow: hidden;
        background: #ffffff;
    }

    .cat-card-figma:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -10px rgba(108, 76, 241, 0.2) !important;
        border-color: rgba(108, 76, 241, 0.35) !important;
    }

    .cat-icon-wrapper {
        width: 64px;
        height: 64px;
        border-radius: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1.75rem;
        transition: transform 0.3s ease;
    }

    .cat-card-figma:hover .cat-icon-wrapper {
        transform: scale(1.1) rotate(-3deg);
    }

    /* Gradient Presets for Card Previews & Categories */
    .card-grad-1 { background: linear-gradient(135deg, #6366F1 0%, #A855F7 100%); }
    .card-grad-2 { background: linear-gradient(135deg, #3B82F6 0%, #06B6D4 100%); }
    .card-grad-3 { background: linear-gradient(135deg, #EC4899 0%, #8B5CF6 100%); }
    .card-grad-4 { background: linear-gradient(135deg, #F59E0B 0%, #EF4444 100%); }
    .card-grad-5 { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
    .card-grad-6 { background: linear-gradient(135deg, #8B5CF6 0%, #6366F1 100%); }
    .cat-grad-7  { background: linear-gradient(135deg, #7C3AED 0%, #A855F7 100%); }
    .cat-grad-8  { background: linear-gradient(135deg, #14B8A6 0%, #0EA5E9 100%); }
</style>


<!-- PREMIUM FIGMA-LEVEL HERO SECTION -->
<section class="position-relative text-white py-5 py-lg-6 overflow-hidden hero-bg-gradient">
    <!-- Soft Background Radial Glow -->
    <div class="position-absolute top-0 start-0 w-100 h-100 pointer-events-none hero-radial-glow"></div>

    <div class="container position-relative py-4 py-lg-5 animate-fade-in-up">
        <div class="row align-items-center g-5">
            <!-- Left Column: Bilingual Headline & Search -->
            <div class="col-lg-7 text-center text-lg-start">
                <!-- AI Badge -->
                <div class="d-inline-flex align-items-center gap-2 px-3.5 py-1.5 rounded-pill bg-white bg-opacity-20 backdrop-blur text-white mb-4 border border-white border-opacity-30 shadow-sm">
                    <span class="badge bg-white text-primary rounded-pill px-2.5 py-1 small fw-bold">AI 2.0</span>
                    <span class="small fw-semibold">Next-Gen Intelligent Design Hub</span>
                </div>

                <!-- Bilingual Headline -->
                <h1 class="display-3 fw-extrabold text-white mb-3 tracking-tight lh-sm">
                    বাংলার সেরা <span class="text-warning">Graphic Marketplace</span>
                </h1>

                <!-- Subtitle -->
                <p class="fs-5 text-white text-opacity-90 mb-4 me-lg-4 lh-base" style="max-width: 620px;">
                    AI-powered marketplace for templates, UI kits, vectors and digital assets.
                </p>

                <!-- Glassmorphism Search Bar -->
                <div class="p-2 hero-search-box rounded-pill shadow-lg mb-4 text-start" style="max-width: 590px;">
                    <form class="d-flex align-items-center" action="#templates" method="GET">
                        <span class="ps-3 text-muted fs-5">
                            <i class="bi bi-search text-primary"></i>
                        </span>
                        <input type="text" class="form-control border-0 shadow-none bg-transparent ps-3 text-dark fs-6" placeholder="Search templates, UI kits, vectors, logos..." aria-label="Search Marketplace">
                        <button class="btn text-white rounded-pill px-4 py-2.5 fw-bold shadow-sm" type="button" style="background: linear-gradient(135deg, #6C4CF1 0%, #4F46E5 100%);">
                            Search / খুঁজুন
                        </button>
                    </form>
                </div>

                <!-- Two CTA Buttons -->
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-3 mb-5">
                    <a href="#templates" class="btn btn-cta-primary btn-lg rounded-pill px-4 py-3 fw-bold shadow-sm">
                        <i class="bi bi-grid-3x3-gap-fill me-2"></i> Explore Templates
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-cta-secondary btn-lg rounded-pill px-4 py-3 fw-bold">
                        <i class="bi bi-bag-plus-fill me-2"></i> Become Seller
                    </a>
                </div>

                <!-- Bottom Statistics Grid -->
                <div class="pt-4 border-top border-white border-opacity-20">
                    <div class="row g-3 text-center text-lg-start">
                        <div class="col-4">
                            <div class="fw-extrabold fs-2 text-white mb-0">10K+</div>
                            <div class="small text-white text-opacity-80 fw-semibold">Templates</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-extrabold fs-2 text-white mb-0">2K+</div>
                            <div class="small text-white text-opacity-80 fw-semibold">Creators</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-extrabold fs-2 text-white mb-0">50K+</div>
                            <div class="small text-white text-opacity-80 fw-semibold">Downloads</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Floating Glass Mockup Illustration Card -->
            <div class="col-lg-5">
                <div class="position-relative animate-float">
                    <!-- Glassmorphism Container Card -->
                    <div class="p-4 rounded-4 glass-mockup-card">
                        
                        <!-- Studio Header Bar -->
                        <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-white border-opacity-20">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-danger" style="width: 10px; height: 10px;"></div>
                                <div class="rounded-circle bg-warning" style="width: 10px; height: 10px;"></div>
                                <div class="rounded-circle bg-success" style="width: 10px; height: 10px;"></div>
                                <span class="small fw-semibold text-white ms-2">Noksha Studio Canvas v2.4</span>
                            </div>
                            <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-2.5 py-1 small border border-white border-opacity-25">
                                <i class="bi bi-stars text-warning me-1"></i> AI Powered
                            </span>
                        </div>

                        <!-- Canvas Workspace Mockup -->
                        <div class="bg-white bg-opacity-20 rounded-3 p-3 mb-3 border border-white border-opacity-20">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="p-3 bg-white text-primary rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                    <i class="bi bi-layers-fill fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="h6 fw-bold text-white mb-0">E-Commerce Brand Kit.fig</div>
                                    <div class="small text-white text-opacity-75">Figma UI Kit & Vector Presets</div>
                                </div>
                                <span class="badge bg-success text-white rounded-pill px-2.5 py-1">Verified</span>
                            </div>

                            <!-- Design Tool Badges -->
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-20">#Figma</span>
                                <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-20">#Photoshop</span>
                                <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-20">#Illustrator</span>
                                <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-20">#SVG</span>
                            </div>
                        </div>

                        <!-- Glass Mini Cards -->
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="p-3 rounded-3 bg-white bg-opacity-20 border border-white border-opacity-25 text-white">
                                    <div class="small text-white text-opacity-75 mb-1">Rating</div>
                                    <div class="fw-bold fs-5 text-warning"><i class="bi bi-star-fill me-1"></i> 4.9 / 5.0</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 bg-white bg-opacity-20 border border-white border-opacity-25 text-white">
                                    <div class="small text-white text-opacity-75 mb-1">Downloads</div>
                                    <div class="fw-bold fs-5"><i class="bi bi-download me-1"></i> 12.8K</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative Floating Badges with Depth -->
                    <div class="position-absolute top-0 end-0 translate-middle-y me-n2 mt-n2 p-2.5 bg-white text-dark rounded-4 shadow-lg d-none d-sm-flex align-items-center gap-2 border border-light" style="transform: rotate(4deg);">
                        <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <div>
                            <div class="fw-bold small mb-0">Instant Download</div>
                            <div class="text-muted extra-small">Commercial License</div>
                        </div>
                    </div>

                    <div class="position-absolute bottom-0 start-0 translate-middle-y ms-n2 mb-n2 p-2.5 bg-white text-dark rounded-4 shadow-lg d-none d-sm-flex align-items-center gap-2 border border-light" style="transform: rotate(-3deg);">
                        <div class="p-2 bg-success bg-opacity-10 text-success rounded-3">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <div class="fw-bold small mb-0">100% Quality Check</div>
                            <div class="text-muted extra-small">Top Creator Guarantee</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- FEATURED TEMPLATES SECTION (REDESIGNED FIGMA-LEVEL MARKETPLACE UI) -->
<section id="templates" class="py-5 py-lg-6 featured-templates-section">
    <div class="container py-3">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <span class="badge px-3.5 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold mb-2" style="background: rgba(108, 76, 241, 0.08); color: #6C4CF1; border: 1px solid rgba(108, 76, 241, 0.2);">
                <i class="bi bi-stars me-1"></i> Featured Showcase
            </span>
            <h2 class="display-6 fw-extrabold text-dark mt-2 mb-2">
                Featured Templates <span class="text-primary">(জনপ্রিয় টেমপ্লেট)</span>
            </h2>
            <p class="text-secondary fs-6 mb-0" style="max-width: 580px; margin: 0 auto;">
                Hand-picked premium and free design assets.
            </p>
        </div>

        <!-- 6 Modern Cards Grid (Desktop: 3 cols col-lg-4, Tablet: 2 cols col-md-6, Mobile: 1 col col-12) -->
        <div class="row g-4">
            
            <!-- Card 1 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 template-card-figma">
                    <!-- Gradient Preview Area -->
                    <div class="template-preview-area card-grad-1 p-4 d-flex align-items-center justify-content-center text-white">
                        <svg width="68" height="68" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                        <!-- Floating Category Badge -->
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                            UI Kit
                        </span>
                    </div>

                    <!-- Card Content -->
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between text-muted small mb-2">
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-star-fill text-warning me-1"></i>4.9 (128)
                            </span>
                            <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>1.4k downloads</span>
                        </div>

                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate" title="Fintech Mobile App UI Kit">
                            Fintech Mobile App UI Kit
                        </h5>
                        <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-2">
                            50+ iOS & Android screens with dark and light mode vector components.
                        </p>

                        <!-- Price & Purple CTA Button -->
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-light-subtle">
                            <div>
                                <span class="text-muted extra-small d-block fw-semibold text-uppercase">Price</span>
                                <span class="fw-extrabold text-dark fs-5">৳499</span>
                            </div>
                            <a href="#templates" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 template-card-figma">
                    <!-- Gradient Preview Area -->
                    <div class="template-preview-area card-grad-2 p-4 d-flex align-items-center justify-content-center text-white">
                        <svg width="68" height="68" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                            <polyline points="2 17 12 22 22 17"></polyline>
                            <polyline points="2 12 12 17 22 12"></polyline>
                        </svg>
                        <!-- Floating Category Badge -->
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                            Vector
                        </span>
                    </div>

                    <!-- Card Content -->
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between text-muted small mb-2">
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-star-fill text-warning me-1"></i>4.8 (94)
                            </span>
                            <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>2.8k downloads</span>
                        </div>

                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate" title="Corporate Business Flyer">
                            Corporate Business Flyer
                        </h5>
                        <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-2">
                            Print-ready A4 vector layout for corporate brand presentations.
                        </p>

                        <!-- Price & Purple CTA Button -->
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-light-subtle">
                            <div>
                                <span class="text-muted extra-small d-block fw-semibold text-uppercase">Price</span>
                                <span class="fw-extrabold text-success fs-5">Free</span>
                            </div>
                            <a href="#templates" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 template-card-figma">
                    <!-- Gradient Preview Area -->
                    <div class="template-preview-area card-grad-3 p-4 d-flex align-items-center justify-content-center text-white">
                        <svg width="68" height="68" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                        <!-- Floating Category Badge -->
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                            Social Media
                        </span>
                    </div>

                    <!-- Card Content -->
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between text-muted small mb-2">
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-star-fill text-warning me-1"></i>5.0 (210)
                            </span>
                            <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>3.1k downloads</span>
                        </div>

                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate" title="Instagram Post & Story Bundle">
                            Instagram Post & Story Bundle
                        </h5>
                        <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-2">
                            30 minimalist social media layouts for agency marketing.
                        </p>

                        <!-- Price & Purple CTA Button -->
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-light-subtle">
                            <div>
                                <span class="text-muted extra-small d-block fw-semibold text-uppercase">Price</span>
                                <span class="fw-extrabold text-dark fs-5">৳299</span>
                            </div>
                            <a href="#templates" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 template-card-figma">
                    <!-- Gradient Preview Area -->
                    <div class="template-preview-area card-grad-4 p-4 d-flex align-items-center justify-content-center text-white">
                        <svg width="68" height="68" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <!-- Floating Category Badge -->
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                            3D Mockup
                        </span>
                    </div>

                    <!-- Card Content -->
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between text-muted small mb-2">
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-star-fill text-warning me-1"></i>4.9 (67)
                            </span>
                            <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>950 downloads</span>
                        </div>

                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate" title="3D Isometric Tech Icons">
                            3D Isometric Tech Icons
                        </h5>
                        <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-2">
                            High-res transparent PNG & Blender 3D source files included.
                        </p>

                        <!-- Price & Purple CTA Button -->
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-light-subtle">
                            <div>
                                <span class="text-muted extra-small d-block fw-semibold text-uppercase">Price</span>
                                <span class="fw-extrabold text-dark fs-5">৳199</span>
                            </div>
                            <a href="#templates" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 template-card-figma">
                    <!-- Gradient Preview Area -->
                    <div class="template-preview-area card-grad-5 p-4 d-flex align-items-center justify-content-center text-white">
                        <svg width="68" height="68" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                        <!-- Floating Category Badge -->
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                            Branding
                        </span>
                    </div>

                    <!-- Card Content -->
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between text-muted small mb-2">
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-star-fill text-warning me-1"></i>4.7 (112)
                            </span>
                            <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>1.9k downloads</span>
                        </div>

                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate" title="Minimalist Agency Logo Kit">
                            Minimalist Agency Logo Kit
                        </h5>
                        <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-2">
                            Fully editable vector logotypes with font pairing guidelines.
                        </p>

                        <!-- Price & Purple CTA Button -->
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-light-subtle">
                            <div>
                                <span class="text-muted extra-small d-block fw-semibold text-uppercase">Price</span>
                                <span class="fw-extrabold text-success fs-5">Free</span>
                            </div>
                            <a href="#templates" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 template-card-figma">
                    <!-- Gradient Preview Area -->
                    <div class="template-preview-area card-grad-6 p-4 d-flex align-items-center justify-content-center text-white">
                        <svg width="68" height="68" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="3" y1="9" x2="21" y2="9"></line>
                            <line x1="9" y1="21" x2="9" y2="9"></line>
                        </svg>
                        <!-- Floating Category Badge -->
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                            SaaS System
                        </span>
                    </div>

                    <!-- Card Content -->
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between text-muted small mb-2">
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-star-fill text-warning me-1"></i>4.9 (88)
                            </span>
                            <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>820 downloads</span>
                        </div>

                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate" title="SaaS Web Admin System">
                            SaaS Web Admin System
                        </h5>
                        <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-2">
                            Complete admin dashboard UI component library with charts.
                        </p>

                        <!-- Price & Purple CTA Button -->
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-light-subtle">
                            <div>
                                <span class="text-muted extra-small d-block fw-semibold text-uppercase">Price</span>
                                <span class="fw-extrabold text-dark fs-5">৳299</span>
                            </div>
                            <a href="#templates" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- PROFESSIONAL CATEGORIES SECTION (IMMEDIATELY BELOW FEATURED TEMPLATES) -->
<section id="categories" class="py-5 py-lg-6" style="background-color: #F8F5FF;">
    <div class="container py-3">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <span class="badge px-3.5 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold mb-2" style="background: rgba(108, 76, 241, 0.08); color: #6C4CF1; border: 1px solid rgba(108, 76, 241, 0.2);">
                <i class="bi bi-grid-fill me-1"></i> Categories
            </span>
            <h2 class="display-6 fw-extrabold text-dark mt-2 mb-2">
                Explore Categories <span class="text-primary">(ক্যাটাগরি ব্রাউজ করুন)</span>
            </h2>
            <p class="text-secondary fs-6 mb-0" style="max-width: 580px; margin: 0 auto;">
                Find templates by design type.
            </p>
        </div>

        <!-- 8 Category Cards Grid (Desktop: 4 cols col-lg-3, Tablet: 2 cols col-md-6, Mobile: 2 cols col-6) -->
        <div class="row g-3 g-md-4 mb-5">
            
            <!-- Category Card 1: UI Kits -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card h-100 cat-card-figma p-3.5 text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="cat-icon-wrapper card-grad-1 mb-3 shadow-sm">
                        <i class="bi bi-grid"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">UI Kits</h5>
                    <span class="small text-secondary font-monospace">1.2k Templates</span>
                </div>
            </div>

            <!-- Category Card 2: Logos -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card h-100 cat-card-figma p-3.5 text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="cat-icon-wrapper card-grad-3 mb-3 shadow-sm">
                        <i class="bi bi-vector-pen"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Logos</h5>
                    <span class="small text-secondary font-monospace">850 Templates</span>
                </div>
            </div>

            <!-- Category Card 3: Social Media -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card h-100 cat-card-figma p-3.5 text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="cat-icon-wrapper card-grad-5 mb-3 shadow-sm">
                        <i class="bi bi-instagram"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Social Media</h5>
                    <span class="small text-secondary font-monospace">3.1k Templates</span>
                </div>
            </div>

            <!-- Category Card 4: Posters -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card h-100 cat-card-figma p-3.5 text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="cat-icon-wrapper card-grad-4 mb-3 shadow-sm">
                        <i class="bi bi-image"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Posters</h5>
                    <span class="small text-secondary font-monospace">740 Templates</span>
                </div>
            </div>

            <!-- Category Card 5: Branding -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card h-100 cat-card-figma p-3.5 text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="cat-icon-wrapper card-grad-2 mb-3 shadow-sm">
                        <i class="bi bi-palette"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Branding</h5>
                    <span class="small text-secondary font-monospace">620 Templates</span>
                </div>
            </div>

            <!-- Category Card 6: Web Design -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card h-100 cat-card-figma p-3.5 text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="cat-icon-wrapper card-grad-6 mb-3 shadow-sm">
                        <i class="bi bi-window"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Web Design</h5>
                    <span class="small text-secondary font-monospace">980 Templates</span>
                </div>
            </div>

            <!-- Category Card 7: 3D Mockups -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card h-100 cat-card-figma p-3.5 text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="cat-icon-wrapper cat-grad-7 mb-3 shadow-sm">
                        <i class="bi bi-box"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">3D Mockups</h5>
                    <span class="small text-secondary font-monospace">430 Templates</span>
                </div>
            </div>

            <!-- Category Card 8: Icons -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card h-100 cat-card-figma p-3.5 text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="cat-icon-wrapper cat-grad-8 mb-3 shadow-sm">
                        <i class="bi bi-stars"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Icons</h5>
                    <span class="small text-secondary font-monospace">2.4k Templates</span>
                </div>
            </div>

        </div>

        <!-- Centered Bottom CTA Button -->
        <div class="text-center">
            <a href="#categories" class="btn btn-purple-cta rounded-pill px-5 py-3 fs-6 fw-bold shadow-sm">
                View All Categories <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>


<!-- AI FEATURES SPOTLIGHT -->
<section id="ai-features" class="py-5 bg-light">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="noksha-badge mb-2">Smart Intelligence</span>
                <h2 class="display-6 fw-bold text-dark mb-3">AI-Driven Capabilities built for Designers</h2>
                <p class="text-secondary mb-4">
                    Noksha integrates computer vision and natural language processing directly into the marketplace lifecycle to save hours of manual metadata management.
                </p>

                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3">
                            <i class="bi bi-tags-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Automated Tag Generation</h5>
                            <p class="small text-muted mb-0">AI automatically scans template previews upon upload to infer precise tags, software compatibility, and colors.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="p-3 bg-secondary bg-opacity-10 text-secondary rounded-3">
                            <i class="bi bi-search-heart fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Prompt-to-Asset Search</h5>
                            <p class="small text-muted mb-0">Search templates using natural prompts like "modern tech flyer with dark cyan accents" to get relevant matches.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="p-3 bg-info bg-opacity-10 text-info rounded-3">
                            <i class="bi bi-sliders fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Dynamic Preview Variations</h5>
                            <p class="small text-muted mb-0">Generate intelligent color palette previews and layout variations before downloading source files.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature Card Visualization -->
            <div class="col-lg-6">
                <div class="p-4 p-md-5 bg-white border rounded-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-pill px-3 py-2">AI Simulation</span>
                            <span class="fw-semibold text-dark">Metadata Extractor</span>
                        </div>
                        <span class="text-success small fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Active</span>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-3 border">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-secondary text-white rounded p-3 text-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-file-earmark-image fs-3"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Corporate_Flyer_Template.psd</h6>
                                <span class="badge bg-secondary text-light">Photoshop CS6+</span>
                                <span class="badge bg-dark text-light">300 DPI</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Extracted AI Keywords:</label>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge bg-light text-dark border">#corporate</span>
                            <span class="badge bg-light text-dark border">#business</span>
                            <span class="badge bg-light text-dark border">#minimalist</span>
                            <span class="badge bg-light text-dark border">#gradient-blue</span>
                            <span class="badge bg-light text-dark border">#a4-print</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
