@extends('layouts.app')

@section('title', 'Noksha (নকশা) - বাংলার সেরা Graphic Marketplace')

@section('content')

<!-- PREMIUM FIGMA-LEVEL HERO SECTION -->
<section class="position-relative text-white py-5 py-lg-6 overflow-hidden" style="background: linear-gradient(135deg, #6C4CF1 0%, #8B5CF6 100%);">
    <!-- Subtle Background Radial Glow -->
    <div class="position-absolute top-0 start-0 w-100 h-100 pointer-events-none" style="background: radial-gradient(circle at 15% 20%, rgba(255, 255, 255, 0.15) 0%, transparent 45%), radial-gradient(circle at 85% 80%, rgba(255, 255, 255, 0.12) 0%, transparent 45%);"></div>

    <div class="container position-relative py-4 py-lg-5">
        <div class="row align-items-center g-5">
            <!-- Left Column: Bilingual Text & Search -->
            <div class="col-lg-7 text-center text-lg-start">
                <!-- AI Badge -->
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-white bg-opacity-20 backdrop-blur text-white mb-4 border border-white border-opacity-25 shadow-sm">
                    <span class="badge bg-white text-primary rounded-pill px-2.5 py-1 small fw-bold">AI 2.0</span>
                    <span class="small fw-semibold">Next-Gen Intelligent Design Hub</span>
                </div>

                <!-- Bilingual Headline -->
                <h1 class="display-3 fw-extrabold text-white mb-3 tracking-tight lh-sm">
                    বাংলার সেরা <span class="text-warning">Graphic Marketplace</span>
                </h1>

                <!-- Subtitle -->
                <p class="fs-5 text-white text-opacity-90 mb-4 me-lg-4" style="max-width: 620px;">
                    AI-powered marketplace for templates, UI kits, vectors and digital assets.
                </p>

                <!-- Large Rounded Search Bar -->
                <div class="p-2 bg-white rounded-pill shadow-lg mb-4 text-start border border-white border-opacity-30" style="max-width: 580px;">
                    <form class="d-flex align-items-center" action="{{ route('home') }}#templates" method="GET">
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
                    <a href="#templates" class="btn btn-light btn-lg rounded-pill px-4 py-3 fw-bold text-primary shadow-sm">
                        <i class="bi bi-grid-3x3-gap-fill me-2"></i> Explore Templates
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 py-3 fw-bold border-2">
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

            <!-- Right Column: Glassmorphism Illustration Placeholder -->
            <div class="col-lg-5">
                <div class="position-relative">
                    <!-- Glassmorphism Container -->
                    <div class="p-4 rounded-4 shadow-2xl border border-white border-opacity-30" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
                        
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

                    <!-- Decorative Floating Badges -->
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


<!-- CATEGORIES SECTION -->
<section id="categories" class="py-5 bg-white border-bottom">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="noksha-badge mb-2">Curated Categories</span>
            <h2 class="fw-bold text-dark mb-2">Explore Graphics & Digital Media</h2>
            <p class="text-muted">Browse thousands of professional templates crafted for creative projects.</p>
        </div>

        <div class="row g-4">
            <!-- Category Card 1 -->
            <div class="col-6 col-md-4 col-lg-2">
                <div class="noksha-card p-4 text-center h-100">
                    <div class="fs-1 text-primary mb-2">
                        <i class="bi bi-palette"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Vector Graphics</h6>
                    <span class="small text-muted">1,240 Assets</span>
                </div>
            </div>

            <!-- Category Card 2 -->
            <div class="col-6 col-md-4 col-lg-2">
                <div class="noksha-card p-4 text-center h-100">
                    <div class="fs-1 text-secondary mb-2">
                        <i class="bi bi-aspect-ratio"></i>
                    </div>
                    <h6 class="fw-bold mb-1">UI Wireframes</h6>
                    <span class="small text-muted">890 Kits</span>
                </div>
            </div>

            <!-- Category Card 3 -->
            <div class="col-6 col-md-4 col-lg-2">
                <div class="noksha-card p-4 text-center h-100">
                    <div class="fs-1 text-info mb-2">
                        <i class="bi bi-bounding-box-circles"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Social Media</h6>
                    <span class="small text-muted">2,150 Packs</span>
                </div>
            </div>

            <!-- Category Card 4 -->
            <div class="col-6 col-md-4 col-lg-2">
                <div class="noksha-card p-4 text-center h-100">
                    <div class="fs-1 text-warning mb-2">
                        <i class="bi bi-box"></i>
                    </div>
                    <h6 class="fw-bold mb-1">3D Mockups</h6>
                    <span class="small text-muted">640 Models</span>
                </div>
            </div>

            <!-- Category Card 5 -->
            <div class="col-6 col-md-4 col-lg-2">
                <div class="noksha-card p-4 text-center h-100">
                    <div class="fs-1 text-success mb-2">
                        <i class="bi bi-type-bold"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Logos & Fonts</h6>
                    <span class="small text-muted">1,500 Items</span>
                </div>
            </div>

            <!-- Category Card 6 -->
            <div class="col-6 col-md-4 col-lg-2">
                <div class="noksha-card p-4 text-center h-100">
                    <div class="fs-1 text-danger mb-2">
                        <i class="bi bi-robot"></i>
                    </div>
                    <h6 class="fw-bold mb-1">AI Presets</h6>
                    <span class="small text-muted">420 Prompts</span>
                </div>
            </div>
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
