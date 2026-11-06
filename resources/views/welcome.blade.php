@extends('layouts.app')

@section('title', 'Noksha')

@section('content')

<!-- CUSTOM FIGMA/DRIBBBLE LEVEL CSS ANIMATIONS & UTILITIES -->
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

    @keyframes badgeFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-6px); }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .animate-float {
        animation: floatSlow 6s ease-in-out infinite;
    }

    .animate-badge-float {
        animation: badgeFloat 4s ease-in-out infinite;
    }

    /* Scroll Reveal Animation */
    .reveal-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
    }

    .reveal-on-scroll.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Hero Gradient & Animated Background Orbs */
    .hero-bg-gradient {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 60%, #f1f5f9 100%);
        position: relative;
    }

    .hero-radial-glow {
        background: 
            radial-gradient(circle at 18% 25%, rgba(32, 22, 92, 0.04) 0%, transparent 45%),
            radial-gradient(circle at 82% 75%, rgba(244, 116, 2, 0.05) 0%, transparent 45%),
            radial-gradient(circle at 50% 50%, rgba(32, 22, 92, 0.03) 0%, transparent 60%);
    }

    /* Animated Floating Background Orbs */
    .hero-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(100px);
        opacity: 0.35;
        pointer-events: none;
        z-index: 0;
    }

    .hero-orb-1 {
        width: 380px;
        height: 380px;
        background: radial-gradient(circle, rgba(32, 22, 92, 0.08) 0%, rgba(244, 116, 2, 0.04) 100%);
        top: -60px;
        left: -80px;
        animation: orbFloat1 12s ease-in-out infinite alternate;
    }

    .hero-orb-2 {
        width: 440px;
        height: 440px;
        background: radial-gradient(circle, rgba(244, 116, 2, 0.08) 0%, rgba(32, 22, 92, 0.04) 100%);
        bottom: -100px;
        right: -60px;
        animation: orbFloat2 14s ease-in-out infinite alternate;
    }

    .hero-orb-3 {
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(32, 22, 92, 0.06) 0%, rgba(244, 116, 2, 0.05) 100%);
        top: 40%;
        left: 45%;
        animation: orbFloat3 10s ease-in-out infinite alternate;
    }

    @keyframes orbFloat1 {
        0% { transform: translate(0, 0) rotate(0deg); }
        100% { transform: translate(50px, 40px) rotate(15deg); }
    }
    @keyframes orbFloat2 {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(-60px, -50px) scale(1.1); }
    }
    @keyframes orbFloat3 {
        0% { transform: translate(0, 0); }
        100% { transform: translate(40px, -30px); }
    }

    /* Glassmorphism Search Bar */
    .hero-search-box {
        background: #ffffff;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1.5px solid #e2e8f0 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    }

    .hero-search-box:focus-within {
        box-shadow: 0 18px 30px -5px rgba(32, 22, 92, 0.12), 0 0 0 3px rgba(32, 22, 92, 0.15) !important;
        transform: translateY(-2px);
        border-color: #20165c !important;
    }

    .hero-search-box:focus-within .bi-search {
        transform: scale(1.15) rotate(5deg);
        color: #20165c !important;
        transition: transform 0.3s ease;
    }

    /* Premium CTA Buttons */
    .btn-cta-primary {
        background: #20165c;
        color: #ffffff !important;
        border: none;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .btn-cta-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px -5px rgba(32, 22, 92, 0.3);
        background: #181045;
        color: #ffffff !important;
    }

    .btn-cta-secondary {
        background: rgba(244, 116, 2, 0.06);
        color: #f47402 !important;
        border: 2px solid #f47402 !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-cta-secondary:hover {
        background: #f47402;
        border-color: #f47402 !important;
        transform: translateY(-2px);
        color: #ffffff !important;
    }

    .btn-purple-cta {
        background: #20165c;
        color: #ffffff !important;
        border: 1px solid #181045;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 12px rgba(32, 22, 92, 0.2);
    }

    .btn-purple-cta:hover {
        background: #181045;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(32, 22, 92, 0.3);
        color: #ffffff !important;
    }

    /* Glass Illustration Card */
    .glass-mockup-card {
        background: #ffffff;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.08);
        transition: all 0.4s ease;
    }

    .glass-mockup-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 25px 50px -10px rgba(0, 0, 0, 0.12);
    }

    /* Featured Template Section & Cards */
    .featured-templates-section {
        background-color: #F8FAFC;
        position: relative;
    }

    .featured-templates-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #20165c 0%, #f47402 100%);
    }

    /* Floating Glassmorphism Filter Bar */
    .filter-bar-glass {
        background: #ffffff;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05) !important;
    }

    /* Figma/Dribbble Category Chips */
    .cat-chip-pill {
        background: #ffffff;
        border-radius: 999px !important;
        border: 1px solid #e2e8f0 !important;
        color: #64748b;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.5rem 1.25rem;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        white-space: nowrap;
        flex-shrink: 0;
    }

    .cat-chip-pill:hover {
        border-color: #20165c !important;
        box-shadow: 0 4px 12px rgba(32, 22, 92, 0.1);
        color: #20165c;
        transform: translateY(-1px);
    }

    .cat-chip-pill.active {
        background: #20165c !important;
        color: #ffffff !important;
        border-color: #20165c !important;
        box-shadow: 0 6px 16px rgba(32, 22, 92, 0.25) !important;
        transform: scale(1.03) !important;
    }

    /* Improved Template Cards with Shimmer Sweep & 24px Radius */
    .template-card-figma, .cat-card-figma, .trending-card-figma {
        border-radius: 1.25rem !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.35s ease;
        overflow: hidden;
        background: #ffffff;
        position: relative;
    }

    .template-card-figma:hover, .cat-card-figma:hover, .trending-card-figma:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08) !important;
        border-color: #cbd5e1 !important;
    }

    .template-preview-area {
        height: 230px; /* Bigger Gradient Preview */
        position: relative;
        overflow: hidden;
    }

    .template-preview-area svg {
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .template-card-figma:hover .template-preview-area svg {
        transform: scale(1.08) rotate(-1deg);
    }

    /* Professional Categories Card Styling */
    .cat-icon-wrapper {
        width: 64px;
        height: 64px;
        border-radius: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1.75rem;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .cat-card-figma:hover .cat-icon-wrapper {
        transform: scale(1.12) rotate(-6deg);
    }

    .glass-trending-badge {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .ai-strip-box {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(108, 76, 241, 0.2) !important;
        border-radius: 1.5rem;
        box-shadow: 0 15px 35px -10px rgba(108, 76, 241, 0.12);
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

    /* Hide scrollbar for category filter chips */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .cursor-pointer { cursor: pointer; }

    /* ========================================================= */
    /* COMPREHENSIVE DARK MODE OVERRIDES FOR WELCOME PAGE        */
    /* ========================================================= */
    html.dark {
        /* Hero Section */
        .hero-bg-gradient {
            background: linear-gradient(135deg, #1E1B4B 0%, #0F172A 50%, #0B0F19 100%) !important;
        }

        .hero-radial-glow {
            background: 
                radial-gradient(circle at 18% 25%, rgba(99, 102, 241, 0.18) 0%, transparent 45%),
                radial-gradient(circle at 82% 75%, rgba(124, 58, 237, 0.15) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(15, 23, 42, 0.6) 0%, transparent 60%) !important;
        }

        .hero-search-box {
            background: rgba(30, 41, 59, 0.85) !important;
            border-color: rgba(75, 85, 99, 0.6) !important;
            box-shadow: 0 12px 30px -5px rgba(0, 0, 0, 0.5) !important;

            input {
                color: #FFFFFF !important;
                &::placeholder {
                    color: #9CA3AF !important;
                }
            }
        }

        .glass-mockup-card {
            background: rgba(30, 41, 59, 0.55) !important;
            border: 1px solid rgba(75, 85, 99, 0.45) !important;
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.6) !important;
        }

        .hero-canvas-workspace {
            background: rgba(15, 23, 42, 0.75) !important;
            border-color: rgba(55, 65, 81, 0.7) !important;
        }

        .hero-stat-mini-card {
            background: rgba(15, 23, 42, 0.65) !important;
            border-color: rgba(55, 65, 81, 0.7) !important;
        }

        .hero-tool-icon {
            background: #1E293B !important;
            color: #818CF8 !important;
        }

        .hero-floating-pill {
            background: #1E293B !important;
            border-color: #374151 !important;
            color: #F3F4F6 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;

            .text-dark {
                color: #F3F4F6 !important;
            }
            .text-muted {
                color: #9CA3AF !important;
            }
        }

        /* Featured Templates Section */
        .featured-templates-section {
            background-color: #0B0F19 !important;

            &::before {
                background: linear-gradient(90deg, #4F46E5 0%, #7C3AED 50%, #06B6D4 100%) !important;
            }

            h2, .text-dark {
                color: #F9FAFB !important;
            }
            .text-secondary {
                color: #9CA3AF !important;
            }
        }

        /* Floating Glass Filter Bar */
        .filter-bar-glass {
            background: rgba(30, 41, 59, 0.85) !important;
            border: 1px solid rgba(55, 65, 81, 0.8) !important;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.6) !important;

            .input-group-text {
                background-color: rgba(15, 23, 42, 0.7) !important;
                border-color: #374151 !important;
                color: #9CA3AF !important;
            }

            #filterSearchInput {
                background-color: rgba(15, 23, 42, 0.7) !important;
                border-color: #374151 !important;
                color: #FFFFFF !important;
                &::placeholder {
                    color: #9CA3AF !important;
                }
            }

            #sortSelect {
                background-color: rgba(15, 23, 42, 0.7) !important;
                border-color: #374151 !important;
                color: #F3F4F6 !important;
            }

            .border-top {
                border-color: #374151 !important;
            }
        }

        /* Category Chips */
        .cat-chip-pill {
            background: rgba(15, 23, 42, 0.7) !important;
            border: 1px solid rgba(55, 65, 81, 0.8) !important;
            color: #D1D5DB !important;

            &:hover {
                border-color: #6366F1 !important;
                color: #A5B4FC !important;
                background: rgba(99, 102, 241, 0.2) !important;
            }

            &.active {
                background: #4F46E5 !important;
                color: #FFFFFF !important;
                border-color: #4F46E5 !important;
                box-shadow: 0 8px 20px -4px rgba(79, 70, 229, 0.5) !important;
            }
        }

        /* Template Cards */
        .template-card-figma {
            background: #1E293B !important;
            border-color: #374151 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4) !important;

            &:hover {
                border-color: #6366F1 !important;
                box-shadow: 0 20px 35px -10px rgba(79, 70, 229, 0.35) !important;
            }

            .card-title, h5 {
                color: #FFFFFF !important;
            }
            .card-text {
                color: #9CA3AF !important;
            }
            .border-top {
                border-color: #374151 !important;
            }
            .fw-extrabold.text-dark {
                color: #FFFFFF !important;
            }

            .btn-light {
                background: #374151 !important;
                border-color: #4B5563 !important;
                color: #F3F4F6 !important;
                &:hover {
                    background: #4B5563 !important;
                }
            }

            .btn-outline-primary {
                border-color: #6366F1 !important;
                color: #A5B4FC !important;
                &:hover {
                    background: #6366F1 !important;
                    color: #FFFFFF !important;
                }
            }
        }

        .template-preview-area .badge {
            background: #1E293B !important;
            color: #F3F4F6 !important;
            border: 1px solid #374151 !important;
        }

        /* Categories Section */
        #categories {
            background-color: #0B0F19 !important;

            h2, .text-dark {
                color: #FFFFFF !important;
            }
            .text-secondary {
                color: #9CA3AF !important;
            }
        }

        .cat-card-figma {
            background: #1E293B !important;
            border-color: #374151 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3) !important;

            &:hover {
                border-color: #6366F1 !important;
                box-shadow: 0 20px 35px -10px rgba(79, 70, 229, 0.35) !important;
            }

            h5 {
                color: #FFFFFF !important;
            }
            span {
                color: #9CA3AF !important;
            }
        }

        /* Trending Section */
        #trending {
            background: #0B0F19 !important;

            h2, .text-dark {
                color: #FFFFFF !important;
            }
            .text-secondary {
                color: #9CA3AF !important;
            }
        }

        .ai-strip-box {
            background: #1E293B !important;
            border-color: #374151 !important;
            box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.4) !important;

            h5 {
                color: #FFFFFF !important;
            }
            p {
                color: #9CA3AF !important;
            }
        }

        /* Creator Features Section */
        #ai-features, #creator-features {
            background-color: #0F172A !important;

            h2, h5, h6 {
                color: #FFFFFF !important;
            }
            p, .text-secondary, .text-muted {
                color: #9CA3AF !important;
            }
            .bg-white {
                background-color: #1E293B !important;
                border-color: #374151 !important;
            }
            .bg-light {
                background-color: #0F172A !important;
                border-color: #374151 !important;
            }
            .border-bottom {
                border-color: #374151 !important;
            }
            .text-dark {
                color: #FFFFFF !important;
            }
            .badge.bg-light {
                background-color: #374151 !important;
                color: #E5E7EB !important;
                border-color: #4B5563 !important;
            }
        }

        /* Badge Floats */
        .animate-badge-float {
            background: rgba(99, 102, 241, 0.18) !important;
            color: #A5B4FC !important;
            border-color: rgba(99, 102, 241, 0.35) !important;
        }
    }
</style>


<!-- PREMIUM FIGMA-LEVEL HERO SECTION WITH ANIMATED ORBS -->
<section class="position-relative py-5 py-lg-6 overflow-hidden hero-bg-gradient reveal-on-scroll">
    <!-- Animated Floating Background Orbs -->
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-orb hero-orb-3"></div>

    <!-- Soft Background Radial Glow -->
    <div class="position-absolute top-0 start-0 w-100 h-100 pointer-events-none hero-radial-glow"></div>

    <div class="container position-relative py-4 py-lg-5 animate-fade-in-up" style="z-index: 1;">
        <div class="row align-items-center g-5">
            <!-- Left Column: Bilingual Headline & Search -->
            <div class="col-lg-7 text-center text-lg-start">

                <!-- Bilingual Headline -->
                <h1 class="display-3 fw-extrabold text-[#121826] mb-3 tracking-tight lh-sm">
                    <span class="bg-gradient-to-r from-[#20165c] via-[#2c1e7a] to-[#f47402] bg-clip-text text-transparent">{{ __('marketplace.hero_title') }}</span>
                </h1>

                <!-- Subtitle -->
                <p class="fs-5 text-slate-600 mb-4 me-lg-4 lh-base" style="max-width: 620px;">
                    {{ __('marketplace.hero_subtitle') }}
                </p>

                <!-- Glassmorphism Search Bar -->
                <div class="p-2 hero-search-box rounded-pill shadow-sm mb-4 text-start" style="max-width: 590px;">
                    <form class="d-flex align-items-center" action="{{ route('search.index') }}" method="GET">
                        <span class="ps-3 text-muted fs-5">
                            <i class="bi bi-search text-[#20165c]"></i>
                        </span>
                        <input type="text" name="q" id="heroSearchInput" class="form-control border-0 shadow-none bg-transparent ps-3 text-[#121826] fs-6" placeholder="{{ __('marketplace.search_placeholder') }}" aria-label="Search Marketplace">
                        <button class="btn text-white rounded-pill px-4 py-2.5 fw-bold shadow-sm btn-search-hero" type="submit" style="background: #20165c;">
                            {{ __('app.search') }}
                        </button>
                    </form>
                </div>

                <!-- Hero CTA Buttons -->
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-3 mb-5">
                    <a href="#templates" class="btn btn-cta-primary btn-lg rounded-pill px-4 py-3 fw-bold shadow-sm">
                        <i class="bi bi-grid-3x3-gap-fill me-2"></i> {{ __('marketplace.explore_templates') }}
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-cta-secondary btn-lg rounded-pill px-4 py-3 fw-bold">
                            <i class="bi bi-patch-check-fill me-2"></i> {{ __('dashboard.become_contributor_btn') ?? 'Become a Contributor' }}
                        </a>
                    @else
                        @if(auth()->user()->isContributor())
                            <a href="{{ route('dashboard', ['tab' => 'upload']) }}" class="btn btn-cta-secondary btn-lg rounded-pill px-4 py-3 fw-bold">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Upload Asset
                            </a>
                        @else
                            <a href="{{ route('contributor.apply') }}" class="btn btn-cta-secondary btn-lg rounded-pill px-4 py-3 fw-bold">
                                <i class="bi bi-patch-check-fill me-2"></i> {{ __('dashboard.become_contributor_btn') ?? 'Become a Contributor' }}
                            </a>
                        @endif
                    @endguest
                </div>

                <!-- Bottom Statistics Grid with Animated Counters -->
                <div class="pt-4 border-top border-slate-200">
                    <div class="row g-3 text-center text-lg-start">
                        <div class="col-4">
                            <div class="fw-extrabold fs-2 text-[#121826] mb-0 stat-counter" data-target="{{ $templatesStat['target'] ?? $templatesCount }}" data-suffix="{{ $templatesStat['suffix'] ?? '' }}">{{ $formattedTemplatesCount ?? $templatesCount }}</div>
                            <div class="small text-slate-500 fw-semibold">Templates</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-extrabold fs-2 text-[#121826] mb-0 stat-counter" data-target="{{ $creatorsStat['target'] ?? $creatorsCount }}" data-suffix="{{ $creatorsStat['suffix'] ?? '' }}">{{ $formattedCreatorsCount ?? $creatorsCount }}</div>
                            <div class="small text-slate-500 fw-semibold">Creators</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-extrabold fs-2 text-[#121826] mb-0 stat-counter" data-target="{{ $downloadsStat['target'] ?? $downloadsCount }}" data-suffix="{{ $downloadsStat['suffix'] ?? '' }}">{{ $formattedDownloadsCount ?? $downloadsCount }}</div>
                            <div class="small text-slate-500 fw-semibold">Downloads</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Dynamic Asset Showcase / Glass Illustration Card -->
            <div class="col-lg-5">
                <div class="position-relative animate-float">
                    @if(isset($heroShowcase) && $heroShowcase)
                        <!-- Glassmorphism Container Card -->
                        <div class="p-4 rounded-4 glass-mockup-card border border-slate-200 bg-white shadow-xl">
                            
                            <!-- Studio Header Bar -->
                            <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-slate-200">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-danger" style="width: 10px; height: 10px;"></div>
                                    <div class="rounded-circle bg-warning" style="width: 10px; height: 10px;"></div>
                                    <div class="rounded-circle bg-success" style="width: 10px; height: 10px;"></div>
                                    <span class="small fw-semibold text-[#121826] ms-2 text-truncate" style="max-width: 180px;">{{ $heroShowcase->title }}</span>
                                </div>
                                <span class="badge rounded-pill px-2.5 py-1 small animate-badge-float" style="background: rgba(32, 22, 92, 0.08); color: #20165c; border: 1px solid rgba(32, 22, 92, 0.15);">
                                    <i class="bi bi-patch-check-fill text-[#f47402] me-1"></i> Featured Asset
                                </span>
                            </div>

                            <!-- Canvas Workspace Showcase -->
                            <div class="rounded-3 p-3 mb-3 border border-slate-200 bg-slate-50 hero-canvas-workspace">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if($heroShowcase->preview_image)
                                        <img src="{{ $heroShowcase->thumbnail_url }}" alt="{{ $heroShowcase->title }}" class="rounded-3 shadow-sm object-fit-cover" style="width: 52px; height: 52px;">
                                    @else
                                        <div class="p-3 bg-white text-[#20165c] rounded-3 shadow-sm d-flex align-items-center justify-content-center hero-tool-icon" style="width: 52px; height: 52px;">
                                            <i class="bi bi-layers-fill fs-3"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1 overflow-hidden">
                                        <a href="{{ route('resource.show', $heroShowcase->slug ?? $heroShowcase->id) }}" class="h6 fw-bold text-[#121826] mb-0 text-decoration-none d-block text-truncate hover-underline">
                                            {{ $heroShowcase->title }}
                                        </a>
                                        <div class="small text-slate-500 text-truncate">
                                            {{ $heroShowcase->category?->name ?? 'Design Asset' }} • by {{ $heroShowcase->owner?->name ?? 'Noksha Creator' }}
                                        </div>
                                    </div>
                                    @if($heroShowcase->status === 'approved')
                                        <span class="badge bg-emerald-600 text-white rounded-pill px-2.5 py-1 animate-badge-float">Verified</span>
                                    @endif
                                </div>

                                <!-- Dynamic Asset Tags / Format Badges -->
                                <div class="d-flex flex-wrap gap-2">
                                    @if(!empty($heroShowcase->tags) && is_array($heroShowcase->tags))
                                        @foreach(array_slice($heroShowcase->tags, 0, 4) as $tag)
                                            <span class="badge bg-slate-200 text-slate-700 border border-slate-300">#{{ ltrim($tag, '#') }}</span>
                                        @endforeach
                                    @elseif(!empty($heroShowcase->file_type))
                                        <span class="badge bg-slate-200 text-slate-700 border border-slate-300">#{{ strtoupper($heroShowcase->file_type) }}</span>
                                        <span class="badge bg-slate-200 text-slate-700 border border-slate-300">#{{ $heroShowcase->category?->name ?? 'Design' }}</span>
                                    @else
                                        <span class="badge bg-slate-200 text-slate-700 border border-slate-300">#{{ $heroShowcase->category?->name ?? 'Design' }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Glass Mini Cards -->
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="p-3 rounded-3 bg-slate-50 border border-slate-200 text-[#121826] hero-stat-mini-card">
                                        <div class="small text-slate-500 mb-1">Price</div>
                                        <div class="fw-bold fs-5 text-[#f47402]">
                                            @if($heroShowcase->is_paid && $heroShowcase->price > 0)
                                                ৳{{ number_format($heroShowcase->price, 2) }}
                                            @else
                                                <span class="text-emerald-600">Free</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded-3 bg-slate-50 border border-slate-200 text-[#121826] hero-stat-mini-card">
                                        <div class="small text-slate-500 mb-1">Downloads</div>
                                        <div class="fw-bold fs-5 text-[#121826]"><i class="bi bi-download me-1 text-[#20165c]"></i> {{ number_format($heroShowcase->downloads ?? 0) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Decorative Floating Badges with Real Context -->
                        <div class="position-absolute top-0 end-0 translate-middle-y me-n2 mt-n2 p-2.5 bg-white text-[#121826] rounded-4 shadow-lg d-none d-sm-flex align-items-center gap-2 border border-slate-200 hero-floating-pill" style="transform: rotate(4deg);">
                            <div class="p-2 bg-[#20165c]/10 text-[#20165c] rounded-3">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </div>
                            <div>
                                <div class="fw-bold small mb-0">{{ $heroShowcase->is_paid ? 'Commercial License' : 'Free Download' }}</div>
                                <div class="text-slate-500 extra-small">Instant Digital Delivery</div>
                            </div>
                        </div>

                        <div class="position-absolute bottom-0 start-0 translate-middle-y ms-n2 mb-n2 p-2.5 bg-white text-[#121826] rounded-4 shadow-lg d-none d-sm-flex align-items-center gap-2 border border-slate-200 hero-floating-pill" style="transform: rotate(-3deg);">
                            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-3">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <div class="fw-bold small mb-0">100% Quality Checked</div>
                                <div class="text-slate-500 extra-small">Admin Verified Asset</div>
                            </div>
                        </div>
                    @else
                        <!-- Production Clean Empty State (When 0 assets exist) -->
                        <div class="p-4 p-md-5 rounded-4 glass-mockup-card border border-slate-200 bg-white text-center py-5 shadow-xl">
                            <div class="p-3 bg-[#20165c]/10 text-[#20165c] rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                <i class="bi bi-palette2 fs-2"></i>
                            </div>
                            <h5 class="fw-bold text-[#121826] mb-2">Bangladeshi Creator Marketplace</h5>
                            <p class="small text-slate-500 mb-4 px-2" style="max-width: 380px; margin: 0 auto;">
                                Be the first creator to upload and publish design assets on Noksha.
                            </p>
                            @guest
                                <a href="{{ route('register') }}" class="btn btn-cta-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                                    <i class="bi bi-person-plus-fill me-1"></i> Join Creator Community
                                </a>
                            @else
                                @if(auth()->user()->isContributor())
                                    <a href="{{ route('dashboard', ['tab' => 'upload']) }}" class="btn btn-cta-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload First Template
                                    </a>
                                @else
                                    <a href="{{ route('contributor.apply') }}" class="btn btn-cta-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                                        <i class="bi bi-patch-check-fill me-1"></i> Become a Contributor
                                    </a>
                                @endif
                            @endguest
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>


<!-- FEATURED TEMPLATES SECTION (REDESIGNED FIGMA/DRIBBBLE FLOATING GLASS FILTER BAR & POLISHED CARDS) -->
<section id="templates" class="py-5 py-lg-6 featured-templates-section reveal-on-scroll">
    <div class="container py-3">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <span class="badge px-3.5 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold mb-2 animate-badge-float" style="background: rgba(32, 22, 92, 0.08); color: #20165c; border: 1px solid rgba(32, 22, 92, 0.2);">
                <i class="bi bi-stars me-1 text-[#f47402]"></i> Featured Showcase
            </span>
            <h2 class="display-6 fw-extrabold text-[#121826] mt-2 mb-2">
                Featured Templates
            </h2>
            <p class="text-slate-600 fs-6 mb-0" style="max-width: 580px; margin: 0 auto;">
                Hand-picked premium and free design assets.
            </p>
        </div>

        <!-- FLOATING GLASSMORPHISM FILTER BAR -->
        <div class="card p-3.5 p-md-4 border border-slate-200 shadow-sm mb-4 rounded-4 filter-bar-glass position-sticky top-20 z-10 bg-white/95 backdrop-blur-md">
            <!-- Top Row: Search Input (Left) & Sort Dropdown (Right) -->
            <div class="row g-3 align-items-center mb-3">
                <!-- Search Input (Left) -->
                <div class="col-12 col-md-7 col-lg-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3.5 rounded-pill-start">
                            <i class="bi bi-search text-[#20165c] fs-6"></i>
                        </span>
                        <input type="text" id="filterSearchInput" class="form-control bg-white border-start-0 shadow-none ps-1 rounded-pill-end fs-6 text-[#121826]" placeholder="Search templates, categories, keywords..." aria-label="Search">
                    </div>
                </div>

                <!-- Sort Dropdown (Right) -->
                <div class="col-12 col-md-5 col-lg-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="small fw-bold text-muted text-nowrap d-none d-sm-inline"><i class="bi bi-sort-down text-[#20165c] me-1"></i> Sort:</span>
                        <select id="sortSelect" class="form-select bg-white border border-slate-200 shadow-none rounded-pill text-[#121826] fw-semibold fs-6">
                            <option value="popular">Most Popular</option>
                            <option value="newest">Newest First</option>
                            <option value="price-asc">Price: Low to High</option>
                            <option value="price-desc">Price: High to Low</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Separate Row: Premium Category Chips -->
            <div class="d-flex align-items-center gap-2.5 overflow-x-auto pt-3 border-top border-slate-200 pb-1 no-scrollbar">
                <span class="small fw-bold text-muted text-uppercase tracking-wider me-2 flex-shrink-0"><i class="bi bi-funnel-fill text-[#20165c] me-1"></i> Category:</span>
                <button type="button" class="btn cat-chip-pill active" data-cat="All">All</button>
                @foreach($categories as $cat)
                    <button type="button" class="btn cat-chip-pill" data-cat="{{ $cat->name }}">{{ $cat->name }}</button>
                @endforeach
            </div>
        </div>

        <!-- Result Counter Badge -->
        <div class="d-flex align-items-center justify-content-between mb-4 px-1">
            <div class="small fw-bold text-muted">
                <i class="bi bi-layers me-1 text-primary"></i> <span id="resultCountText">Showing {{ $resources->count() }} approved marketplace templates</span>
            </div>
        </div>

        <!-- SKELETON LOADERS (Subtle In-App Content Transition Placeholders) -->
        <div id="templateSkeletonGrid" class="d-none mb-4">
            @include('partials.skeleton-cards', ['count' => 6])
        </div>

        <!-- DYNAMIC DATABASE APPROVED TEMPLATE CARDS GRID -->
        <div class="row g-4" id="templateCardsContainer">
            @if($resources->count() > 0)
                @foreach($resources as $resource)
                    @php
                        $catName = $resource->category ? $resource->category->name : 'General';
                        $gradClass = 'card-grad-' . (($loop->index % 4) + 1);
                    @endphp
                    <div class="col-12 col-md-6 col-lg-4 template-card-item" 
                         data-title="{{ strtolower($resource->title) }}" 
                         data-category="{{ $catName }}" 
                         data-price="{{ $resource->is_paid ? 'paid' : 'free' }}" 
                         data-downloads="{{ $resource->downloads }}" 
                         data-price-val="{{ $resource->price }}" 
                         data-id="{{ $resource->id }}">
                        <div class="card h-100 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
                            <!-- Preview Media Container with Behance Hover Overlay -->
                            <div class="position-relative overflow-hidden bg-slate-900" style="height: 250px;">
                                <a href="{{ route('resource.show', $resource->slug ?? $resource->id) }}" class="d-block w-100 h-100 text-decoration-none">
                                    @if($resource->preview_image)
                                        <img src="{{ $resource->thumbnail_url }}" class="w-100 h-100 object-fit-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $resource->title }}" loading="lazy">
                                    @else
                                        <div class="w-100 h-100 {{ $gradClass }} p-4 d-flex align-items-center justify-content-center text-white group-hover:scale-105 transition-transform duration-500">
                                            <svg width="68" height="68" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                                <line x1="12" y1="17" x2="12" y2="21"></line>
                                            </svg>
                                        </div>
                                    @endif
                                </a>

                                <!-- Floating Category Badge (Default View) -->
                                <span class="position-absolute top-0 start-0 m-3 badge bg-white/90 backdrop-blur-sm text-[#121826] rounded-pill shadow-sm px-2.5 py-1 font-bold extra-small pointer-events-none group-hover:opacity-0 transition-opacity">
                                    {{ $catName }}
                                </span>

                                <!-- Behance Hover Overlay -->
                                <div class="position-absolute inset-0 d-flex flex-column justify-content-between p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none" style="background: linear-gradient(to bottom, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0.85) 100%);">
                                    <!-- Top Row: Category & Quick Save Actions -->
                                    <div class="d-flex align-items-center justify-content-between w-100 pointer-events-auto">
                                        <span class="badge bg-white/95 text-[#20165c] rounded-pill px-2.5 py-1 font-bold extra-small shadow-sm">
                                            {{ $catName }}
                                        </span>
                                        <div class="d-flex align-items-center gap-1.5">
                                            <form action="{{ route('wishlist.store', $resource->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm rounded-circle bg-white/95 hover:bg-white text-rose-500 shadow-sm p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Save to Wishlist">
                                                    <i class="bi bi-heart-fill small"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('cart.store', $resource->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm rounded-circle bg-white/95 hover:bg-white text-[#20165c] shadow-sm p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Add to Cart">
                                                    <i class="bi bi-cart-plus-fill small"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Bottom Row: Creator Info & View Action -->
                                    <div class="d-flex align-items-center justify-content-between w-100 pointer-events-auto">
                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                            @if($resource->owner && $resource->owner->avatar)
                                                <img src="{{ asset('storage/' . $resource->owner->avatar) }}" alt="{{ $resource->owner->name }}" class="rounded-circle border border-white/50 shrink-0 object-cover" style="width: 28px; height: 28px;">
                                            @else
                                                <div class="rounded-circle bg-[#20165c] text-white extra-small fw-bold d-flex align-items-center justify-content-center shrink-0 border border-white/50" style="width: 28px; height: 28px;">
                                                    {{ strtoupper(substr($resource->owner?->name ?? 'N', 0, 1)) }}
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <div class="text-white fw-bold extra-small text-truncate" style="max-width: 130px;">
                                                    {{ $resource->owner?->name ?? 'Noksha Creator' }}
                                                </div>
                                                <div class="text-white-50 font-monospace extra-small" style="font-size: 0.68rem;">
                                                    <i class="bi bi-download me-0.5"></i>{{ number_format($resource->downloads) }}
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('resource.show', $resource->slug ?? $resource->id) }}" class="btn btn-sm rounded-pill px-3 py-1 text-white font-bold extra-small shadow-sm" style="background: #f47402;">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Clean Bottom Information Bar -->
                            <div class="p-3.5 d-flex flex-column bg-white">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-1.5">
                                    <h6 class="fw-bold text-[#121826] mb-0 text-truncate flex-grow-1" title="{{ $resource->title }}">
                                        <a href="{{ route('resource.show', $resource->slug ?? $resource->id) }}" class="text-[#121826] hover:text-[#20165c] text-decoration-none transition-colors">
                                            {{ $resource->title }}
                                        </a>
                                    </h6>
                                    <!-- Price Pill -->
                                    <div class="shrink-0 text-end">
                                        @if($resource->is_paid && $resource->price > 0)
                                            <span class="fw-black text-[#f47402] fs-6">৳{{ number_format($resource->price, 2) }}</span>
                                        @else
                                            <span class="fw-black text-emerald-600 fs-6">Free</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-slate-100 text-slate-500 extra-small">
                                    <div class="d-flex align-items-center gap-1.5">
                                        <span class="badge bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-medium">
                                            {{ $catName }}
                                        </span>
                                        <span class="text-slate-400">•</span>
                                        <span class="d-inline-flex align-items-center text-amber-600 fw-semibold">
                                            <i class="bi bi-star-fill text-amber-400 me-1"></i>{{ $resource->average_rating ?? '5.0' }}
                                        </span>
                                    </div>
                                    <span class="text-slate-400 font-mono text-[11px] uppercase">
                                        {{ strtoupper($resource->file_type ?? 'ZIP') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination Links -->
                <div class="col-12 mt-4 d-flex justify-content-center">
                    {{ $resources->links() }}
                </div>
            @else
                <!-- EMPTY STATE WHEN ZERO APPROVED RESOURCES EXIST -->
                <div class="col-12 text-center py-5">
                    <div class="p-4 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 84px; height: 84px;">
                        <i class="bi bi-rocket-takeoff fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">No Marketplace Assets Published Yet</h4>
                    <p class="text-secondary small mb-4" style="max-width: 480px; margin: 0 auto;">
                        Be the first creator to upload and publish design assets on Noksha. Admin approvals will immediately list templates here.
                    </p>
                    <a href="{{ route('dashboard', ['tab' => 'upload']) }}" class="btn btn-purple-cta rounded-pill px-5 py-3 fw-bold">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> Upload First Resource
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>


<!-- PROFESSIONAL CATEGORIES SECTION (DYNAMIC DATABASE CATEGORIES) -->
<section id="categories" class="py-5 py-lg-6 reveal-on-scroll" style="background-color: #ffffff;">
    <div class="container py-3">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <span class="badge px-3.5 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold mb-2 animate-badge-float" style="background: rgba(32, 22, 92, 0.08); color: #20165c; border: 1px solid rgba(32, 22, 92, 0.2);">
                <i class="bi bi-grid-fill me-1 text-[#f47402]"></i> Categories
            </span>
            <h2 class="display-6 fw-extrabold text-[#121826] mt-2 mb-2">
                Explore Categories
            </h2>
            <p class="text-slate-600 fs-6 mb-0" style="max-width: 580px; margin: 0 auto;">
                Find templates by design type.
            </p>
        </div>

        <!-- Dynamic Category Cards Grid -->
        <div class="row g-3 g-md-4 mb-5">
            @if($categories->count() > 0)
                @foreach($categories as $index => $cat)
                    @php
                        $iconClass = match($index % 8) {
                            0 => 'bi-grid',
                            1 => 'bi-vector-pen',
                            2 => 'bi-instagram',
                            3 => 'bi-image',
                            4 => 'bi-palette',
                            5 => 'bi-window',
                            6 => 'bi-box',
                            default => 'bi-stars',
                        };
                        $gradClass = 'card-grad-' . (($index % 4) + 1);
                    @endphp
                    <div class="col-6 col-md-6 col-lg-3">
                        <div class="card h-100 cat-card-figma p-3.5 text-center d-flex flex-column align-items-center justify-content-center cursor-pointer" onclick="activateChipCategory('{{ $cat->name }}')">
                            <div class="cat-icon-wrapper {{ $gradClass }} mb-3 shadow-sm">
                                <i class="bi {{ $iconClass }}"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">{{ $cat->name }}</h5>
                            <span class="small text-secondary font-monospace">{{ number_format($cat->resources_count) }} Templates</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Centered Bottom CTA Button -->
        <div class="text-center">
            <a href="#templates" onclick="activateChipCategory('All')" class="btn btn-purple-cta rounded-pill px-5 py-3 fs-6 fw-bold shadow-sm">
                View All Categories <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>


<!-- TRENDING RESOURCES & FEATURED SPOTLIGHT (DYNAMIC DATABASE TRENDING) -->
<section id="trending" class="py-5 py-lg-6 reveal-on-scroll" style="background-color: #f8fafc;">
    <div class="container py-3">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <span class="badge px-3.5 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold mb-2 animate-badge-float" style="background: rgba(32, 22, 92, 0.08); color: #20165c; border: 1px solid rgba(32, 22, 92, 0.2);">
                <i class="bi bi-fire me-1 text-[#f47402]"></i> Trending
            </span>
            <h2 class="display-6 fw-extrabold text-[#121826] mt-2 mb-2">
                Trending Resources
            </h2>
            <p class="text-slate-600 fs-6 mb-0" style="max-width: 580px; margin: 0 auto;">
                Curated high-performing assets loved by creators and creative teams.
            </p>
        </div>

        <!-- Dynamic Trending Cards Layout -->
        <div class="row g-4 mb-5">
            @if(isset($trendingResources) && $trendingResources->count() > 0)
                @foreach($trendingResources as $trending)
                    @php
                        $tCat = $trending->category?->name ?? 'Design';
                        $tGrad = 'card-grad-' . (($loop->index % 4) + 1);
                    @endphp
                    <div class="col-12 col-md-4">
                        <div class="card h-100 template-card-figma border border-slate-200 shadow-sm bg-white">
                            <div class="template-preview-area p-0 position-relative overflow-hidden" style="height: 220px; background: #1E1B4B;">
                                @if($trending->preview_image)
                                    <img src="{{ $trending->thumbnail_url }}" class="w-100 h-100 object-fit-cover" alt="{{ $trending->title }}">
                                @else
                                    <div class="w-100 h-100 {{ $tGrad }} p-4 d-flex align-items-center justify-content-center text-white">
                                        <i class="bi bi-fire fs-1 opacity-75"></i>
                                    </div>
                                @endif
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-danger rounded-pill px-2.5 py-1 shadow-sm">
                                        <i class="bi bi-fire me-1"></i> #{{ $loop->iteration }} Trending
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-3.5 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-slate-100 text-[#20165c] border border-slate-200">{{ $tCat }}</span>
                                    <span class="small text-slate-500"><i class="bi bi-download me-1 text-[#20165c]"></i> {{ number_format($trending->downloads) }}</span>
                                </div>
                                <h6 class="fw-bold text-[#121826] text-truncate mb-2">
                                    <a href="{{ route('resource.show', $trending->slug ?? $trending->id) }}" class="text-[#121826] text-decoration-none hover-underline">
                                        {{ $trending->title }}
                                    </a>
                                </h6>
                                <div class="small text-slate-500 mb-3">by {{ $trending->owner?->name ?? 'Creator' }}</div>
                                <div class="mt-auto pt-2 border-top border-slate-200 d-flex align-items-center justify-content-between">
                                    <span class="fw-extrabold {{ $trending->is_paid && $trending->price > 0 ? 'text-[#f47402]' : 'text-emerald-600' }}">
                                        {{ $trending->is_paid && $trending->price > 0 ? '৳' . number_format($trending->price, 2) : 'Free' }}
                                    </span>
                                    <a href="{{ route('resource.show', $trending->slug ?? $trending->id) }}" class="btn btn-sm btn-purple-cta rounded-pill px-3">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center py-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm border d-inline-block px-5 py-4">
                        <i class="bi bi-graph-up-arrow text-primary fs-3 d-block mb-2"></i>
                        <span class="text-secondary small fw-medium">Trending templates will automatically appear here based on community downloads and views.</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Bottom Featured Recommendation Strip -->
        <div class="p-4 ai-strip-box d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 reveal-on-scroll">
            <div class="d-flex align-items-center gap-3 text-center text-md-start">
                <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                    <i class="bi bi-palette-fill fs-3 text-primary"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1">Looking for custom project specifications?</h5>
                    <p class="text-secondary small mb-0">Discover creator-crafted design systems, vector kits, and branding packages curated for professional design teams.</p>
                </div>
            </div>
            <a href="#trending" class="btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold shadow-sm text-nowrap">
                <i class="bi bi-stars me-1 text-warning"></i> Explore Featured Picks
            </a>
        </div>

    </div>
</section>


<!-- CREATOR CAPABILITIES SPOTLIGHT -->
<section id="creator-features" class="py-5 bg-light reveal-on-scroll">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="noksha-badge mb-2">Creator Marketplace</span>
                <h2 class="display-6 fw-bold text-dark mb-3">Designed for Creators & Creative Professionals</h2>
                <p class="text-secondary mb-4">
                    Noksha empowers top designers, illustrators, and digital artists across Bangladesh to publish bespoke design assets, templates, and creative toolkits with automated verification and instant payouts.
                </p>

                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3">
                            <i class="bi bi-tags-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Smart Asset Organization</h5>
                            <p class="small text-muted mb-0">Every template is automatically organized by category, software compatibility (.FIG, .PSD, .AI), and licensing terms.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="p-3 bg-secondary bg-opacity-10 text-secondary rounded-3">
                            <i class="bi bi-search-heart fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Semantic Category Search</h5>
                            <p class="small text-muted mb-0">Quickly discover templates, vector packs, and design systems using curated keywords and precise filters.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="p-3 bg-info bg-opacity-10 text-info rounded-3">
                            <i class="bi bi-sliders fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Verified Source Deliverables</h5>
                            <p class="small text-muted mb-0">Inspect layered previews, editable source components, and file formats before securing your commercial license.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Creator Feature Showcase Card -->
            <div class="col-lg-6">
                @if(isset($aiShowcase) && $aiShowcase)
                    <div class="p-4 p-md-5 bg-white border rounded-4 shadow-sm">
                        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary rounded-pill px-3 py-2">Creator Spotlight</span>
                                <span class="fw-semibold text-dark">Verified Asset Showcase</span>
                            </div>
                            <span class="text-success small fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Active</span>
                        </div>

                        <div class="bg-light p-3 rounded-3 mb-3 border">
                            <div class="d-flex align-items-center gap-3">
                                @if($aiShowcase->preview_image)
                                    <img src="{{ $aiShowcase->thumbnail_url }}" alt="{{ $aiShowcase->title }}" class="rounded shadow-sm object-fit-cover" style="width: 60px; height: 60px;">
                                @else
                                    <div class="bg-primary bg-opacity-10 text-primary rounded p-3 text-center d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="bi bi-file-earmark-code fs-3"></i>
                                    </div>
                                @endif
                                <div class="overflow-hidden flex-grow-1">
                                    <h6 class="fw-bold mb-1 text-dark text-truncate">{{ $aiShowcase->title }}</h6>
                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge bg-secondary text-light">{{ strtoupper($aiShowcase->file_type ?? 'Vector') }}</span>
                                        <span class="badge bg-dark text-light">{{ $aiShowcase->category?->name ?? 'Design' }}</span>
                                        @if($aiShowcase->is_paid && $aiShowcase->price > 0)
                                            <span class="badge bg-primary text-light">৳{{ number_format($aiShowcase->price, 2) }}</span>
                                        @else
                                            <span class="badge bg-success text-light">Free</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Asset Tags & Specifications:</label>
                            <div class="d-flex flex-wrap gap-1">
                                @if(!empty($aiShowcase->tags) && is_array($aiShowcase->tags))
                                    @foreach(array_slice($aiShowcase->tags, 0, 6) as $tag)
                                        <span class="badge bg-light text-dark border">#{{ ltrim($tag, '#') }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-light text-dark border">#{{ strtolower($aiShowcase->category?->name ?? 'design') }}</span>
                                    <span class="badge bg-light text-dark border">#{{ strtolower($aiShowcase->file_type ?? 'vector') }}</span>
                                    <span class="badge bg-light text-dark border">#marketplace</span>
                                    <span class="badge bg-light text-dark border">#bangladesh</span>
                                @endif
                            </div>
                        </div>

                        <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                            <span class="small text-muted">Uploaded by <strong class="text-dark">{{ $aiShowcase->owner?->name ?? 'Noksha Creator' }}</strong></span>
                            <a href="{{ route('resource.show', $aiShowcase->slug ?? $aiShowcase->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                Inspect Asset <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="p-4 p-md-5 bg-white border rounded-4 shadow-sm text-center py-5">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-brush fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Bespoke Creator Showcase</h5>
                        <p class="small text-muted mb-4" style="max-width: 420px; margin: 0 auto;">
                            No items published yet. Be the first to upload Photoshop, Figma, or Illustrator assets to showcase your creative work to buyers worldwide!
                        </p>
                        @auth
                            @if(auth()->user()->isContributor())
                                <a href="{{ route('dashboard', ['tab' => 'upload']) }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold">
                                    <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Asset to Test
                                </a>
                            @else
                                <a href="{{ route('contributor.apply') }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold">
                                    <i class="bi bi-patch-check-fill me-1"></i> Become a Contributor
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold">
                                <i class="bi bi-person-plus-fill me-1"></i> Join as Creator
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- SCROLL TO TOP BUTTON -->
<button type="button" id="scrollTopBtn" class="btn btn-purple-cta rounded-circle position-fixed bottom-0 end-0 m-4 shadow-lg d-flex align-items-center justify-content-center opacity-0 pointer-events-none" style="width: 50px; height: 50px; z-index: 1050; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);" aria-label="Scroll to top">
    <i class="bi bi-arrow-up fs-5 text-white"></i>
</button>

<!-- FIGMA/DRIBBBLE FILTER CHIP UI & HERO INTERACTION ENGINE SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const heroSearchInput = document.getElementById('heroSearchInput');
    const filterSearchInput = document.getElementById('filterSearchInput');
    const catChips = document.querySelectorAll('.cat-chip-pill');
    const scrollTopBtn = document.getElementById('scrollTopBtn');

    // 1. Sync Hero Search with Filter Bar Search Input
    let currentCategory = 'All';
    let filterDebounceTimer = null;

    function applyTemplateFilters() {
        const query = (filterSearchInput ? filterSearchInput.value : '').toLowerCase().trim();
        const items = document.querySelectorAll('.template-card-item');
        const skeletonGrid = document.getElementById('templateSkeletonGrid');
        const cardsGrid = document.getElementById('templateCardsContainer');
        const countText = document.getElementById('resultCountText');

        if (skeletonGrid && cardsGrid) {
            skeletonGrid.classList.remove('d-none');
            cardsGrid.classList.add('d-none');
        }

        setTimeout(() => {
            let visibleCount = 0;
            items.forEach(item => {
                const itemCat = item.getAttribute('data-category') || '';
                const itemTitle = item.getAttribute('data-title') || '';
                
                const matchesCat = (currentCategory === 'All' || itemCat.toLowerCase() === currentCategory.toLowerCase());
                const matchesQuery = (!query || itemTitle.includes(query) || itemCat.toLowerCase().includes(query));

                if (matchesCat && matchesQuery) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (countText) {
                countText.textContent = `Showing ${visibleCount} approved marketplace templates`;
            }

            if (skeletonGrid && cardsGrid) {
                skeletonGrid.classList.add('d-none');
                cardsGrid.classList.remove('d-none');
            }
        }, 220);
    }

    if (heroSearchInput && filterSearchInput) {
        heroSearchInput.addEventListener('input', function (e) {
            filterSearchInput.value = e.target.value;
            if (e.target.value.trim() !== '') {
                const templatesElem = document.getElementById('templates');
                if (templatesElem) templatesElem.scrollIntoView({ behavior: 'smooth' });
            }
            clearTimeout(filterDebounceTimer);
            filterDebounceTimer = setTimeout(applyTemplateFilters, 280);
        });

        filterSearchInput.addEventListener('input', function () {
            heroSearchInput.value = filterSearchInput.value;
            clearTimeout(filterDebounceTimer);
            filterDebounceTimer = setTimeout(applyTemplateFilters, 280);
        });
    }

    // 2. Category Chips Active State Toggle & Skeleton Filter
    function setActiveChip(categoryName) {
        catChips.forEach(chip => {
            const chipCat = chip.getAttribute('data-cat');
            if (chipCat === categoryName || (categoryName === 'All' && chipCat === 'All')) {
                chip.classList.add('active');
            } else {
                chip.classList.remove('active');
            }
        });
    }

    catChips.forEach(chip => {
        chip.addEventListener('click', function () {
            const selectedCat = this.getAttribute('data-cat');
            currentCategory = selectedCat;
            setActiveChip(selectedCat);
            applyTemplateFilters();
        });
    });

    window.activateChipCategory = function (categoryName) {
        currentCategory = categoryName;
        setActiveChip(categoryName);
        applyTemplateFilters();
        const templatesElem = document.getElementById('templates');
        if (templatesElem) {
            templatesElem.scrollIntoView({ behavior: 'smooth' });
        }
    };

    // 3. Scroll Reveal Observer
    const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.reveal-on-scroll').forEach(el => revealObserver.observe(el));

    // 4. Statistics Counters Animation on Scroll
    let countersAnimated = false;
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-counter');
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target') || 0, 10);
            const suffix = counter.getAttribute('data-suffix') || '';
            let start = 0;
            const duration = 1800;
            const stepTime = 20;
            const totalSteps = duration / stepTime;
            const increment = target / totalSteps;

            const timer = setInterval(() => {
                start += increment;
                if (start >= target) {
                    counter.textContent = target + suffix;
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.floor(start) + suffix;
                }
            }, stepTime);
        });
    }

    const heroStatSection = document.querySelector('.stat-counter');
    if (heroStatSection) {
        const statObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !countersAnimated) {
                    countersAnimated = true;
                    animateCounters();
                }
            });
        }, { threshold: 0.5 });
        statObserver.observe(heroStatSection);
    }

    // 5. Scroll-to-Top Button Handler
    if (scrollTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 400) {
                scrollTopBtn.classList.remove('opacity-0', 'pointer-events-none');
                scrollTopBtn.classList.add('opacity-100');
            } else {
                scrollTopBtn.classList.remove('opacity-100');
                scrollTopBtn.classList.add('opacity-0', 'pointer-events-none');
            }
        });

        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});
</script>

@endsection
