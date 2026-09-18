@extends('layouts.app')

@section('title', 'Noksha (নকশা) - বাংলার সেরা Graphic Marketplace')

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
        background: linear-gradient(135deg, #6C4CF1 0%, #8B5CF6 50%, #9F7AEA 100%);
        position: relative;
    }

    .hero-radial-glow {
        background: 
            radial-gradient(circle at 18% 25%, rgba(255, 255, 255, 0.22) 0%, transparent 45%),
            radial-gradient(circle at 82% 75%, rgba(255, 255, 255, 0.18) 0%, transparent 45%),
            radial-gradient(circle at 50% 50%, rgba(159, 122, 234, 0.35) 0%, transparent 60%);
    }

    /* Animated Floating Background Orbs */
    .hero-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(100px);
        opacity: 0.28;
        pointer-events: none;
        z-index: 0;
    }

    .hero-orb-1 {
        width: 380px;
        height: 380px;
        background: radial-gradient(circle, #EC4899 0%, #8B5CF6 100%);
        top: -60px;
        left: -80px;
        animation: orbFloat1 12s ease-in-out infinite alternate;
    }

    .hero-orb-2 {
        width: 440px;
        height: 440px;
        background: radial-gradient(circle, #3B82F6 0%, #6C4CF1 100%);
        bottom: -100px;
        right: -60px;
        animation: orbFloat2 14s ease-in-out infinite alternate;
    }

    .hero-orb-3 {
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, #F59E0B 0%, #9F7AEA 100%);
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
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
    }

    .hero-search-box:focus-within {
        box-shadow: 0 18px 40px -5px rgba(108, 76, 241, 0.35), 0 0 0 4px rgba(108, 76, 241, 0.25) !important;
        transform: translateY(-3px);
    }

    .hero-search-box:focus-within .bi-search {
        transform: scale(1.2) rotate(10deg);
        color: #6C4CF1 !important;
        transition: transform 0.3s ease;
    }

    /* Premium CTA Buttons */
    .btn-cta-primary {
        background: linear-gradient(135deg, #ffffff 0%, #F8FAFC 100%);
        color: #6C4CF1 !important;
        border: none;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .btn-cta-primary:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 14px 28px -5px rgba(0, 0, 0, 0.25), 0 0 15px rgba(255, 255, 255, 0.4);
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
        transform: translateY(-3px) scale(1.02);
        color: #ffffff !important;
    }

    .btn-purple-cta {
        background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 6px 16px -4px rgba(108, 76, 241, 0.35);
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #5A3DE0 0%, #4327C6 100%);
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 12px 25px -4px rgba(108, 76, 241, 0.45);
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

    /* Floating Glassmorphism Filter Bar */
    .filter-bar-glass {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(108, 76, 241, 0.2) !important;
        box-shadow: 0 20px 40px -15px rgba(108, 76, 241, 0.12) !important;
    }

    /* Figma/Dribbble Category Chips */
    .cat-chip-pill {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-radius: 999px !important;
        border: 1px solid rgba(108, 76, 241, 0.2) !important;
        color: #4B5563;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.5rem 1.25rem;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        white-space: nowrap;
        flex-shrink: 0;
    }

    .cat-chip-pill:hover {
        border-color: rgba(108, 76, 241, 0.4) !important;
        box-shadow: 0 0 15px rgba(108, 76, 241, 0.3);
        color: #6C4CF1;
        transform: translateY(-1px);
    }

    .cat-chip-pill.active {
        background: #6C4CF1 !important;
        color: #ffffff !important;
        border-color: #6C4CF1 !important;
        box-shadow: 0 8px 20px -4px rgba(108, 76, 241, 0.4) !important;
        transform: scale(1.05) !important;
    }

    /* Improved Template Cards with Shimmer Sweep & 24px Radius */
    .template-card-figma, .cat-card-figma, .trending-card-figma {
        border-radius: 1.5rem !important; /* 24px Radius */
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 12px 35px -10px rgba(108, 76, 241, 0.1) !important;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.35s ease;
        overflow: hidden;
        background: #ffffff;
        position: relative;
    }

    .template-card-figma::after, .cat-card-figma::after, .trending-card-figma::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -60%;
        width: 40%;
        height: 200%;
        background: linear-gradient(
            to right,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0.25) 50%,
            rgba(255, 255, 255, 0) 100%
        );
        transform: rotate(30deg);
        transition: all 0.75s ease;
        pointer-events: none;
        opacity: 0;
    }

    .template-card-figma:hover::after, .cat-card-figma:hover::after, .trending-card-figma:hover::after {
        left: 130%;
        opacity: 1;
    }

    .template-card-figma:hover, .cat-card-figma:hover, .trending-card-figma:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 45px -10px rgba(108, 76, 241, 0.22) !important;
        border-color: rgba(108, 76, 241, 0.35) !important;
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

        /* AI Features Section */
        #ai-features {
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
<section class="position-relative text-white py-5 py-lg-6 overflow-hidden hero-bg-gradient reveal-on-scroll">
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
                <!-- AI Badge with Floating Motion -->
                <div class="d-inline-flex align-items-center gap-2 px-3.5 py-1.5 rounded-pill bg-white bg-opacity-20 backdrop-blur text-white mb-4 border border-white border-opacity-30 shadow-sm animate-badge-float">
                    <span class="badge bg-white text-primary rounded-pill px-2.5 py-1 small fw-bold">AI 2.0</span>
                    <span class="small fw-semibold">Next-Gen Intelligent Design Hub</span>
                </div>

                <!-- Bilingual Headline -->
                <h1 class="display-3 fw-extrabold text-white mb-3 tracking-tight lh-sm">
                    {{ __('marketplace.hero_title') }}
                </h1>

                <!-- Subtitle -->
                <p class="fs-5 text-white text-opacity-90 mb-4 me-lg-4 lh-base" style="max-width: 620px;">
                    {{ __('marketplace.hero_subtitle') }}
                </p>

                <!-- Glassmorphism Search Bar -->
                <div class="p-2 hero-search-box rounded-pill shadow-lg mb-4 text-start" style="max-width: 590px;">
                    <form class="d-flex align-items-center" action="{{ route('search.index') }}" method="GET">
                        <span class="ps-3 text-muted fs-5">
                            <i class="bi bi-search text-primary"></i>
                        </span>
                        <input type="text" name="q" id="heroSearchInput" class="form-control border-0 shadow-none bg-transparent ps-3 text-dark fs-6" placeholder="{{ __('marketplace.search_placeholder') }}" aria-label="Search Marketplace">
                        <button class="btn text-white rounded-pill px-4 py-2.5 fw-bold shadow-sm btn-search-hero" type="submit" style="background: linear-gradient(135deg, #6C4CF1 0%, #4F46E5 100%);">
                            {{ __('app.search') }}
                        </button>
                    </form>
                </div>

                <!-- Hero CTA Buttons -->
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-3 mb-5">
                    <a href="#templates" class="btn btn-cta-primary btn-lg rounded-pill px-4 py-3 fw-bold shadow-sm">
                        <i class="bi bi-grid-3x3-gap-fill me-2"></i> {{ __('marketplace.explore_templates') }}
                    </a>
                    <a href="{{ route('resource.demo') }}" class="btn btn-cta-secondary btn-lg rounded-pill px-4 py-3 fw-bold">
                        <i class="bi bi-eye-fill me-2"></i> View Demo Resource
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-cta-secondary btn-lg rounded-pill px-4 py-3 fw-bold">
                        <i class="bi bi-bag-plus-fill me-2"></i> {{ __('marketplace.become_seller') }}
                    </a>
                </div>


                <!-- Bottom Statistics Grid with Animated Counters -->
                <div class="pt-4 border-top border-white border-opacity-20">
                    <div class="row g-3 text-center text-lg-start">
                        <div class="col-4">
                            <div class="fw-extrabold fs-2 text-white mb-0 stat-counter" data-target="10" data-suffix="K+">0K+</div>
                            <div class="small text-white text-opacity-80 fw-semibold">Templates</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-extrabold fs-2 text-white mb-0 stat-counter" data-target="2" data-suffix="K+">0K+</div>
                            <div class="small text-white text-opacity-80 fw-semibold">Creators</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-extrabold fs-2 text-white mb-0 stat-counter" data-target="50" data-suffix="K+">0K+</div>
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
                            <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-2.5 py-1 small border border-white border-opacity-25 animate-badge-float">
                                <i class="bi bi-stars text-warning me-1"></i> AI Powered
                            </span>
                        </div>

                        <!-- Canvas Workspace Mockup -->
                        <div class="bg-white bg-opacity-20 rounded-3 p-3 mb-3 border border-white border-opacity-20 hero-canvas-workspace">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="p-3 bg-white text-primary rounded-3 shadow-sm d-flex align-items-center justify-content-center hero-tool-icon" style="width: 52px; height: 52px;">
                                    <i class="bi bi-layers-fill fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="h6 fw-bold text-white mb-0">E-Commerce Brand Kit.fig</div>
                                    <div class="small text-white text-opacity-75">Figma UI Kit & Vector Presets</div>
                                </div>
                                <span class="badge bg-success text-white rounded-pill px-2.5 py-1 animate-badge-float">Verified</span>
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
                                <div class="p-3 rounded-3 bg-white bg-opacity-20 border border-white border-opacity-25 text-white hero-stat-mini-card">
                                    <div class="small text-white text-opacity-75 mb-1">Rating</div>
                                    <div class="fw-bold fs-5 text-warning"><i class="bi bi-star-fill me-1"></i> 4.9 / 5.0</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 bg-white bg-opacity-20 border border-white border-opacity-25 text-white hero-stat-mini-card">
                                    <div class="small text-white text-opacity-75 mb-1">Downloads</div>
                                    <div class="fw-bold fs-5"><i class="bi bi-download me-1"></i> 12.8K</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative Floating Badges with Depth -->
                    <div class="position-absolute top-0 end-0 translate-middle-y me-n2 mt-n2 p-2.5 bg-white text-dark rounded-4 shadow-lg d-none d-sm-flex align-items-center gap-2 border border-light hero-floating-pill" style="transform: rotate(4deg);">
                        <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <div>
                            <div class="fw-bold small mb-0">Instant Download</div>
                            <div class="text-muted extra-small">Commercial License</div>
                        </div>
                    </div>

                    <div class="position-absolute bottom-0 start-0 translate-middle-y ms-n2 mb-n2 p-2.5 bg-white text-dark rounded-4 shadow-lg d-none d-sm-flex align-items-center gap-2 border border-light hero-floating-pill" style="transform: rotate(-3deg);">
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


<!-- FEATURED TEMPLATES SECTION (REDESIGNED FIGMA/DRIBBBLE FLOATING GLASS FILTER BAR & POLISHED CARDS) -->
<section id="templates" class="py-5 py-lg-6 featured-templates-section reveal-on-scroll">
    <div class="container py-3">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <span class="badge px-3.5 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold mb-2 animate-badge-float" style="background: rgba(108, 76, 241, 0.08); color: #6C4CF1; border: 1px solid rgba(108, 76, 241, 0.2);">
                <i class="bi bi-stars me-1"></i> Featured Showcase
            </span>
            <h2 class="display-6 fw-extrabold text-dark mt-2 mb-2">
                Featured Templates <span class="text-primary">(জনপ্রিয় টেমপ্লেট)</span>
            </h2>
            <p class="text-secondary fs-6 mb-0" style="max-width: 580px; margin: 0 auto;">
                Hand-picked premium and free design assets.
            </p>
        </div>

        <!-- FLOATING GLASSMORPHISM FILTER BAR -->
        <div class="card p-3.5 p-md-4 border-0 shadow-lg mb-4 rounded-4 filter-bar-glass position-relative overflow-hidden">
            <!-- Top Row: Search Input (Left) & Sort Dropdown (Right) -->
            <div class="row g-3 align-items-center mb-3">
                <!-- Search Input (Left) -->
                <div class="col-12 col-md-7 col-lg-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white bg-opacity-75 border-end-0 text-muted ps-3.5 rounded-pill-start">
                            <i class="bi bi-search text-primary fs-6"></i>
                        </span>
                        <input type="text" id="filterSearchInput" class="form-control bg-white bg-opacity-75 border-start-0 shadow-none ps-1 rounded-pill-end fs-6 text-dark" placeholder="Search templates, categories, keywords..." aria-label="Search">
                    </div>
                </div>

                <!-- Sort Dropdown (Right) -->
                <div class="col-12 col-md-5 col-lg-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="small fw-bold text-muted text-nowrap d-none d-sm-inline"><i class="bi bi-sort-down text-primary me-1"></i> Sort:</span>
                        <select id="sortSelect" class="form-select bg-white bg-opacity-75 border shadow-none rounded-pill text-dark fw-semibold fs-6">
                            <option value="popular">Most Popular</option>
                            <option value="newest">Newest First</option>
                            <option value="price-asc">Price: Low to High</option>
                            <option value="price-desc">Price: High to Low</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Separate Row: Premium Category Chips -->
            <div class="d-flex align-items-center gap-2.5 overflow-x-auto pt-3 border-top border-purple-subtle pb-1 no-scrollbar">
                <span class="small fw-bold text-muted text-uppercase tracking-wider me-2 flex-shrink-0"><i class="bi bi-funnel-fill text-primary me-1"></i> Category:</span>
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
                        <div class="card h-100 template-card-figma">
                            <!-- Preview Area -->
                            <div class="template-preview-area p-0 position-relative overflow-hidden" style="height: 240px; background: #1E1B4B;">
                                @if($resource->preview_image)
                                    <img src="{{ asset('storage/' . $resource->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $resource->title }}">
                                @else
                                    <div class="w-100 h-100 {{ $gradClass }} p-4 d-flex align-items-center justify-content-center text-white">
                                        <svg width="76" height="76" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-90">
                                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                            <line x1="8" y1="21" x2="16" y2="21"></line>
                                            <line x1="12" y1="17" x2="12" y2="21"></line>
                                        </svg>
                                    </div>
                                @endif
                                <!-- Floating Category Badge -->
                                <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-pill shadow-sm px-3 py-1.5 fw-bold small animate-badge-float">
                                    {{ $catName }}
                                </span>
                            </div>

                            <!-- Card Content -->
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between text-muted small mb-2.5">
                                    <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                        <i class="bi bi-star-fill text-warning me-1"></i>4.9 ({{ 45 + ($resource->id * 7) % 150 }})
                                    </span>
                                    <span class="text-secondary font-monospace"><i class="bi bi-download me-1"></i>{{ number_format($resource->downloads) }} downloads</span>
                                </div>

                                <h5 class="card-title fw-bold text-dark mb-1.5 text-truncate" title="{{ $resource->title }}">
                                    {{ $resource->title }}
                                </h5>
                                <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-2">
                                    {{ Str::limit($resource->description, 90) }}
                                </p>

                                <div class="d-flex align-items-center justify-content-between pt-3 border-top border-light-subtle">
                                    <div>
                                        <span class="text-muted extra-small d-block fw-semibold text-uppercase">Price</span>
                                        @if($resource->is_paid && $resource->price > 0)
                                            <span class="fw-extrabold text-dark fs-5">৳{{ number_format($resource->price, 2) }}</span>
                                        @else
                                            <span class="fw-extrabold text-success fs-5">Free</span>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center gap-1.5">
                                        <form action="{{ route('wishlist.store', $resource->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-light border btn-sm rounded-circle p-2" title="Save to Wishlist">
                                                <i class="bi bi-heart-fill text-danger"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('cart.store', $resource->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-sm rounded-circle p-2" title="Add to Cart">
                                                <i class="bi bi-cart-plus-fill"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('resource.show', $resource->slug ?? $resource->id) }}" class="btn btn-purple-cta rounded-pill px-3 py-2 btn-sm">
                                            View
                                        </a>
                                    </div>
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
                    <a href="{{ route('resource.create') }}" class="btn btn-purple-cta rounded-pill px-5 py-3 fw-bold">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> Upload First Resource
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>


<!-- PROFESSIONAL CATEGORIES SECTION (DYNAMIC DATABASE CATEGORIES) -->
<section id="categories" class="py-5 py-lg-6 reveal-on-scroll" style="background-color: #F8F5FF;">
    <div class="container py-3">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <span class="badge px-3.5 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold mb-2 animate-badge-float" style="background: rgba(108, 76, 241, 0.08); color: #6C4CF1; border: 1px solid rgba(108, 76, 241, 0.2);">
                <i class="bi bi-grid-fill me-1"></i> Categories
            </span>
            <h2 class="display-6 fw-extrabold text-dark mt-2 mb-2">
                Explore Categories <span class="text-primary">(ক্যাটাগরি ব্রাউজ করুন)</span>
            </h2>
            <p class="text-secondary fs-6 mb-0" style="max-width: 580px; margin: 0 auto;">
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


<!-- TRENDING RESOURCES + AI RECOMMENDATION SECTION (DYNAMIC DATABASE TRENDING) -->
<section id="trending" class="py-5 py-lg-6 reveal-on-scroll" style="background: linear-gradient(180deg, #FFFFFF 0%, #F8F5FF 100%);">
    <div class="container py-3">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <span class="badge px-3.5 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold mb-2 animate-badge-float" style="background: rgba(108, 76, 241, 0.08); color: #6C4CF1; border: 1px solid rgba(108, 76, 241, 0.2);">
                <i class="bi bi-fire me-1 text-danger"></i> Trending
            </span>
            <h2 class="display-6 fw-extrabold text-dark mt-2 mb-2">
                Trending Resources <span class="text-primary">(আজকের জনপ্রিয় ডিজাইন)</span>
            </h2>
            <p class="text-secondary fs-6 mb-0" style="max-width: 580px; margin: 0 auto;">
                AI-selected high-performing assets loved by creators.
            </p>
        </div>

        <!-- Cards Layout -->
        <div class="row g-4 mb-5">
            </div>
        </div>

        <!-- Bottom AI Recommendation Strip -->
        <div class="p-4 ai-strip-box d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 reveal-on-scroll">
            <div class="d-flex align-items-center gap-3 text-center text-md-start">
                <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                    <i class="bi bi-robot fs-3 text-primary"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1">Smart Recommendation Engine</h5>
                    <p class="text-secondary small mb-0">Personalized suggestions based on creator trends.</p>
                </div>
            </div>
            <a href="#trending" class="btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold shadow-sm text-nowrap">
                <i class="bi bi-stars me-1 text-warning"></i> Explore AI Picks
            </a>
        </div>

    </div>
</section>


<!-- AI FEATURES SPOTLIGHT -->
<section id="ai-features" class="py-5 bg-light reveal-on-scroll">
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
    if (heroSearchInput && filterSearchInput) {
        heroSearchInput.addEventListener('input', function (e) {
            filterSearchInput.value = e.target.value;
            if (e.target.value.trim() !== '') {
                const templatesElem = document.getElementById('templates');
                if (templatesElem) templatesElem.scrollIntoView({ behavior: 'smooth' });
            }
        });

        filterSearchInput.addEventListener('input', function () {
            heroSearchInput.value = filterSearchInput.value;
        });
    }

    // 2. Category Chips Active State Toggle
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
            setActiveChip(selectedCat);
        });
    });

    window.activateChipCategory = function (categoryName) {
        setActiveChip(categoryName);
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
