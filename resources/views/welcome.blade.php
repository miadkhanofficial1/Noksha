@extends('layouts.app')

@section('title', 'Noksha (নকশা) - AI-Powered Graphics Template Marketplace')

@section('content')

<!-- GRADIENT HERO SECTION -->
<section class="bg-noksha-gradient text-white py-5 py-lg-6 px-3">
    <div class="container py-4 py-lg-5">
        <div class="row align-items-center g-5">
            <!-- Hero Text Content -->
            <div class="col-lg-7 text-center text-lg-start">
                <div class="d-inline-flex align-items-center gap-2 noksha-badge mb-3">
                    <i class="bi bi-sparkles"></i>
                    <span>Next-Gen Design Marketplace</span>
                </div>

                <h1 class="display-3 fw-extrabold mb-3 tracking-tight">
                    Noksha <span class="text-noksha-gradient">(নকশা)</span>
                </h1>

                <h2 class="h3 fw-bold text-light opacity-90 mb-4">
                    AI-Powered Graphics Template Marketplace
                </h2>

                <p class="lead text-secondary opacity-90 mb-4 me-lg-4" style="max-width: 600px;">
                    Discover, preview, and license high-quality graphic design templates powered by artificial intelligence auto-tagging, prompt-based asset discovery, and seamless creator workflows.
                </p>

                <!-- Search Bar CTA in Hero -->
                <div class="p-2 bg-white bg-opacity-10 backdrop-blur rounded-4 border border-white border-opacity-20 mb-4 shadow-lg" style="max-width: 540px;">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-0 text-white fs-5 ps-3">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control bg-transparent border-0 text-white placeholder-light fs-6 shadow-none" placeholder="Search vectors, PSDs, Figma UI kits..." aria-label="Search hero">
                        <button class="btn btn-noksha rounded-3 px-4" type="button">
                            Search
                        </button>
                    </div>
                </div>

                <!-- Hero Action Buttons & Badges -->
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-3">
                    <a href="#templates" class="btn btn-noksha">
                        <i class="bi bi-grid-fill me-2"></i> Explore Templates
                    </a>
                    <a href="#ai-features" class="btn btn-noksha-outline">
                        <i class="bi bi-cpu me-2"></i> Discover AI Features
                    </a>
                </div>
            </div>

            <!-- Hero Graphic Preview / Brand Logo Badge Showcase -->
            <div class="col-lg-5 text-center">
                <div class="position-relative d-inline-block">
                    <!-- Glassmorphism Container Card -->
                    <div class="p-4 p-md-5 rounded-5 bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-20 shadow-2xl text-center">
                        <div class="d-inline-flex align-items-center justify-content-center mb-4 rounded-4 p-4" style="width: 110px; height: 110px; background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); box-shadow: 0 15px 35px rgba(124, 58, 237, 0.5);">
                            <span class="display-3 fw-bold text-white font-siliguri">ন</span>
                        </div>

                        <h3 class="h4 fw-bold text-white mb-2">Noksha Platform</h3>
                        <p class="small text-secondary mb-4">Empowering Bangladesh & Global Creators with AI-Driven Digital Assets</p>

                        <!-- Feature Badges -->
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <span class="badge bg-primary bg-opacity-25 text-white border border-primary border-opacity-50 px-3 py-2 rounded-3">
                                <i class="bi bi-tags me-1"></i> Auto-Tagging
                            </span>
                            <span class="badge bg-secondary bg-opacity-25 text-white border border-secondary border-opacity-50 px-3 py-2 rounded-3">
                                <i class="bi bi-file-earmark-vector me-1"></i> Vector Assets
                            </span>
                            <span class="badge bg-info bg-opacity-25 text-white border border-info border-opacity-50 px-3 py-2 rounded-3">
                                <i class="bi bi-layers me-1"></i> PSD & Figma
                            </span>
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
