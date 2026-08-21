@extends('layouts.app')

@section('title', 'Noksha Studio (Pro Verified Author) - Noksha (নকশা)')

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

    /* Template Cards Grid */
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

    .template-preview-area {
        height: 220px;
        position: relative;
        overflow: hidden;
    }

    .card-grad-1 { background: linear-gradient(135deg, #6366F1 0%, #A855F7 100%); }
    .card-grad-2 { background: linear-gradient(135deg, #3B82F6 0%, #06B6D4 100%); }
    .card-grad-3 { background: linear-gradient(135deg, #EC4899 0%, #8B5CF6 100%); }
    .card-grad-4 { background: linear-gradient(135deg, #F59E0B 0%, #EF4444 100%); }
    .card-grad-5 { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
    .card-grad-6 { background: linear-gradient(135deg, #8B5CF6 0%, #6366F1 100%); }
</style>

<!-- SELLER COVER BANNER -->
<section class="seller-cover-banner">
    <div class="container h-100 position-relative d-flex align-items-end justify-content-end pb-3">
        <span class="badge bg-white bg-opacity-20 text-white backdrop-blur border border-white border-opacity-30 rounded-pill px-3 py-1.5 small fw-bold">
            <i class="bi bi-camera-fill me-1"></i> Studio Verification Certified
        </span>
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
                        <div class="seller-avatar-img d-flex align-items-center justify-content-center text-primary fs-1 fw-bold">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="seller-online-badge" title="Online now"></div>
                    </div>

                    <!-- Name & Badges -->
                    <div class="pb-1">
                        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-2 mb-1.5">
                            <h2 class="fw-extrabold text-dark mb-0">Noksha Studio</h2>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1 fw-bold small">
                                <i class="bi bi-patch-check-fill me-1"></i> Pro Verified Author
                            </span>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1 fw-bold small">
                                <i class="bi bi-shield-check me-1"></i> 99.4% Trust Rating
                            </span>
                        </div>
                        <p class="text-secondary fw-semibold mb-2">
                            Senior UI/UX Design Systems Architect & Top Rated Asset Creator
                        </p>
                        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-3 small text-muted">
                            <span><i class="bi bi-geo-alt-fill text-primary me-1"></i> Dhaka, Bangladesh</span>
                            <span><i class="bi bi-calendar3 me-1"></i> Member since Jan 2025</span>
                            <span class="text-warning fw-bold"><i class="bi bi-star-fill me-1"></i> 4.98 (1,420 Reviews)</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right: Action Buttons (Follow / Contact) -->
            <div class="col-12 col-md-5 col-lg-4 text-center text-md-end">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-end gap-2.5">
                    <button type="button" id="followBtn" onclick="toggleFollow()" class="btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold">
                        <i class="bi bi-person-plus-fill me-1.5"></i> Follow Author
                    </button>
                    <button type="button" onclick="alert('Contact Form Modal Opening...')" class="btn btn-outline-purple rounded-pill px-4 py-2.5 fw-bold">
                        <i class="bi bi-chat-dots-fill me-1.5"></i> Contact Seller
                    </button>
                </div>
            </div>

        </div>

        <!-- Bio & Skills Row -->
        <div class="row g-4 mt-3">
            <div class="col-12 col-lg-8">
                <!-- Bio -->
                <p class="text-secondary lh-lg mb-3">
                    Passionate UI/UX designer & design systems architect based in Dhaka, Bangladesh. Specializing in high-performance Figma UI kits, vector brand guidelines, mobile app systems, and modern isometric 3D icons.
                </p>

                <!-- Skills Chips -->
                <div class="d-flex flex-wrap gap-2">
                    <span class="seller-skill-chip"><i class="bi bi-layers-fill"></i> Figma</span>
                    <span class="seller-skill-chip"><i class="bi bi-grid-3x3-gap-fill"></i> UI/UX Design</span>
                    <span class="seller-skill-chip"><i class="bi bi-palette-fill"></i> Design Systems</span>
                    <span class="seller-skill-chip"><i class="bi bi-vector-pen"></i> Vector Illustration</span>
                    <span class="seller-skill-chip"><i class="bi bi-stars"></i> Iconography</span>
                    <span class="seller-skill-chip"><i class="bi bi-moon-stars-fill"></i> Dark Mode</span>
                    <span class="seller-skill-chip"><i class="bi bi-briefcase-fill"></i> Branding</span>
                </div>
            </div>

            <!-- Stat Counters Box (Glassmorphism) -->
            <div class="col-12 col-lg-4">
                <div class="glass-stat-box p-3.5">
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div class="fw-extrabold fs-4 text-dark mb-0" id="followerCount">12.4K</div>
                            <div class="extra-small text-muted fw-semibold">Followers</div>
                        </div>
                        <div class="col-4 border-start border-end">
                            <div class="fw-extrabold fs-4 text-dark mb-0">142</div>
                            <div class="extra-small text-muted fw-semibold">Resources</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-extrabold fs-4 text-dark mb-0">45.8K</div>
                            <div class="extra-small text-muted fw-semibold">Downloads</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PORTFOLIO GRID SECTION (6 DEMO RESOURCES) -->
<section class="py-5 py-lg-6" style="background-color: #F8F7FF;">
    <div class="container">
        
        <!-- Filter Bar & Section Title -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <span class="badge px-3 py-1 rounded-pill text-uppercase fw-bold small mb-1" style="background: rgba(108, 76, 241, 0.08); color: #6C4CF1;">
                    Author Portfolio
                </span>
                <h3 class="fw-extrabold text-dark mb-0">Created Resources <span class="text-primary">(পোর্টফোলিও ডিজাইন)</span></h3>
            </div>

            <!-- Category Filter Tabs -->
            <div class="d-flex align-items-center gap-2 overflow-x-auto pb-1">
                <button type="button" class="btn btn-purple-cta rounded-pill btn-sm px-3.5 py-1.5">All Assets (142)</button>
                <button type="button" class="btn btn-outline-secondary rounded-pill btn-sm px-3.5 py-1.5">UI Kits (58)</button>
                <button type="button" class="btn btn-outline-secondary rounded-pill btn-sm px-3.5 py-1.5">Vectors (34)</button>
                <button type="button" class="btn btn-outline-secondary rounded-pill btn-sm px-3.5 py-1.5">Social (26)</button>
                <button type="button" class="btn btn-outline-secondary rounded-pill btn-sm px-3.5 py-1.5">3D (24)</button>
            </div>
        </div>

        <!-- 6 Demo Resources Grid -->
        <div class="row g-4">
            
            <!-- Card 1 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 template-card-figma">
                    <div class="template-preview-area card-grad-1 p-4 d-flex align-items-center justify-content-center text-white">
                        <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                            UI Kit
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between text-muted small mb-2.5">
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-star-fill text-warning me-1"></i>4.9 (128)
                            </span>
                            <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>1.4k</span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate">Fintech Mobile App UI Kit</h5>
                        <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-2">50+ iOS & Android screens with dark and light mode vector components.</p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <span class="fw-extrabold text-dark fs-5">৳499</span>
                            <a href="{{ route('resource.demo') }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 template-card-figma">
                    <div class="template-preview-area card-grad-2 p-4 d-flex align-items-center justify-content-center text-white">
                        <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                            <polyline points="2 17 12 22 22 17"></polyline>
                            <polyline points="2 12 12 17 22 12"></polyline>
                        </svg>
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                            Vector
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between text-muted small mb-2.5">
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-star-fill text-warning me-1"></i>4.8 (94)
                            </span>
                            <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>2.8k</span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate">Corporate Business Flyer</h5>
                        <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-2">Print-ready A4 vector layout for corporate brand presentations.</p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <span class="fw-extrabold text-success fs-5">Free</span>
                            <a href="{{ route('resource.demo') }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 template-card-figma">
                    <div class="template-preview-area card-grad-3 p-4 d-flex align-items-center justify-content-center text-white">
                        <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                            Social Media
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between text-muted small mb-2.5">
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-star-fill text-warning me-1"></i>5.0 (210)
                            </span>
                            <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>3.1k</span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate">Instagram Post & Story Bundle</h5>
                        <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-2">30 minimalist social media layouts for agency marketing.</p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <span class="fw-extrabold text-dark fs-5">৳299</span>
                            <a href="{{ route('resource.demo') }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 template-card-figma">
                    <div class="template-preview-area card-grad-4 p-4 d-flex align-items-center justify-content-center text-white">
                        <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                            3D Mockup
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between text-muted small mb-2.5">
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-star-fill text-warning me-1"></i>4.9 (67)
                            </span>
                            <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>950</span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate">3D Isometric Tech Icons</h5>
                        <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-2">High-res transparent PNG & Blender 3D source files included.</p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <span class="fw-extrabold text-dark fs-5">৳199</span>
                            <a href="{{ route('resource.demo') }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 template-card-figma">
                    <div class="template-preview-area card-grad-5 p-4 d-flex align-items-center justify-content-center text-white">
                        <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                            Branding
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between text-muted small mb-2.5">
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-star-fill text-warning me-1"></i>4.7 (112)
                            </span>
                            <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>1.9k</span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate">Minimalist Agency Logo Kit</h5>
                        <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-2">Fully editable vector logotypes with font pairing guidelines.</p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <span class="fw-extrabold text-success fs-5">Free</span>
                            <a href="{{ route('resource.demo') }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 template-card-figma">
                    <div class="template-preview-area card-grad-6 p-4 d-flex align-items-center justify-content-center text-white">
                        <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="3" y1="9" x2="21" y2="9"></line>
                            <line x1="9" y1="21" x2="9" y2="9"></line>
                        </svg>
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small">
                            SaaS System
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between text-muted small mb-2.5">
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-star-fill text-warning me-1"></i>4.9 (88)
                            </span>
                            <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>820</span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate">SaaS Web Admin System</h5>
                        <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-2">Complete admin dashboard UI component library with charts.</p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <span class="fw-extrabold text-dark fs-5">৳299</span>
                            <a href="{{ route('resource.demo') }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- INTERACTIVE FOLLOW BUTTON TOGGLE SCRIPT -->
<script>
let isFollowing = false;
function toggleFollow() {
    const followBtn = document.getElementById('followBtn');
    const followerCount = document.getElementById('followerCount');
    
    if (!isFollowing) {
        isFollowing = true;
        followBtn.innerHTML = '<i class="bi bi-check-lg me-1.5"></i> Following';
        followBtn.className = 'btn btn-success rounded-pill px-4 py-2.5 fw-bold';
        followerCount.textContent = '12.4K+';
    } else {
        isFollowing = false;
        followBtn.innerHTML = '<i class="bi bi-person-plus-fill me-1.5"></i> Follow Author';
        followBtn.className = 'btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold';
        followerCount.textContent = '12.4K';
    }
}
</script>

@endsection
