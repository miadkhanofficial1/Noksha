@extends('layouts.app')

@section('title', 'AI Template Customizer - ' . ($resource->title ?? 'Workspace') . ' - Noksha')

@section('content')
<style>
    /* Dark Workspace Theme */
    .ai-workspace-wrapper {
        background-color: #020617; /* slate-950 */
        color: #F8FAFC; /* slate-50 */
        min-height: calc(100vh - 72px);
        position: relative;
    }

    .ai-workspace-glow {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 800px;
        height: 380px;
        background: radial-gradient(circle, rgba(124, 58, 237, 0.16) 0%, rgba(59, 130, 246, 0.06) 50%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }

    /* Top Utility Bar */
    .workspace-utility-bar {
        background: rgba(15, 23, 42, 0.85); /* slate-900 */
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(148, 163, 184, 0.15);
        padding: 0.85rem 1.5rem;
        position: sticky;
        top: 0;
        z-index: 30;
    }

    /* Glass Cards */
    .workspace-card {
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(148, 163, 184, 0.14);
        border-radius: 1.25rem;
        padding: 1.5rem;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
    }

    /* Dark Form Inputs */
    .form-label-ai {
        font-size: 0.775rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94A3B8; /* slate-400 */
        margin-bottom: 0.45rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .form-control-ai {
        background-color: #0B1120; /* deeper slate */
        border: 1.5px solid #1E293B; /* slate-800 */
        color: #F8FAFC !important;
        border-radius: 0.75rem;
        padding: 0.7rem 0.95rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .form-control-ai:focus {
        background-color: #0F172A;
        border-color: #8B5CF6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.25);
        outline: none;
    }

    .form-control-ai::placeholder {
        color: #475569;
    }

    /* Color Tone Radio Swatch Cards */
    .vibe-card-option {
        position: relative;
        cursor: pointer;
        display: block;
        margin-bottom: 0;
    }

    .vibe-card-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .vibe-card-box {
        border: 1.5px solid #1E293B;
        background: #0B1120;
        border-radius: 0.75rem;
        padding: 0.65rem 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.2s ease;
    }

    .vibe-card-option:hover .vibe-card-box {
        border-color: rgba(139, 92, 246, 0.5);
        background: #0F172A;
    }

    .vibe-card-option input[type="radio"]:checked + .vibe-card-box {
        border-color: #8B5CF6;
        background: rgba(139, 92, 246, 0.12);
        box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.3);
    }

    .vibe-swatch-dot {
        width: 22px;
        height: 22px;
        border-radius: 50rem;
        flex-shrink: 0;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    /* Primary Action Gradient Button */
    .btn-generate-gradient {
        background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 50%, #2563EB 100%);
        color: #FFFFFF !important;
        border: none;
        border-radius: 0.75rem;
        padding: 0.85rem 1.5rem;
        font-weight: 700;
        font-size: 0.95rem;
        box-shadow: 0 4px 20px rgba(124, 58, 237, 0.45);
        transition: all 0.25s ease;
    }

    .btn-generate-gradient:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(124, 58, 237, 0.65);
    }

    .btn-generate-gradient:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-outline-slate {
        background: rgba(30, 41, 59, 0.6);
        color: #94A3B8 !important;
        border: 1px solid #334155;
        border-radius: 0.75rem;
        padding: 0.85rem 1.25rem;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .btn-outline-slate:hover:not(:disabled) {
        background: #1E293B;
        color: #F8FAFC !important;
        border-color: #64748B;
    }

    .btn-outline-slate:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* Preview Containers */
    .preview-stage-container {
        border-radius: 1rem;
        overflow: hidden;
        background: #090D16;
        border: 1px solid rgba(148, 163, 184, 0.12);
        position: relative;
    }

    .original-preview-img {
        width: 100%;
        max-height: 280px;
        object-fit: contain;
        background: #070B12;
        display: block;
    }

    /* Customized AI Output Canvas / Stage */
    .ai-output-empty-state {
        border: 2px dashed rgba(148, 163, 184, 0.2);
        border-radius: 1rem;
        padding: 3.5rem 1.5rem;
        text-align: center;
        background: rgba(11, 17, 32, 0.5);
        transition: all 0.3s ease;
    }

    .ai-output-live-card {
        border-radius: 1rem;
        overflow: hidden;
        min-height: 380px;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 2.25rem;
        box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.7);
        transition: all 0.4s ease;
    }

    /* Output Themes */
    .theme-modern_dark {
        background: radial-gradient(circle at top right, #2E1065 0%, #090D16 70%);
        border: 1.5px solid rgba(139, 92, 246, 0.35);
        color: #FFFFFF;
    }

    .theme-vibrant_gradient {
        background: linear-gradient(135deg, #831843 0%, #4C1D95 50%, #1E1B4B 100%);
        border: 1.5px solid rgba(244, 63, 94, 0.35);
        color: #FFFFFF;
    }

    .theme-minimal_light {
        background: linear-gradient(135deg, #F8FAFC 0%, #E2E8F0 100%);
        border: 1.5px solid rgba(203, 213, 225, 0.8);
        color: #0F172A;
    }

    .theme-corporate_blue {
        background: radial-gradient(circle at top right, #1E3A8A 0%, #030712 75%);
        border: 1.5px solid rgba(59, 130, 246, 0.35);
        color: #FFFFFF;
    }

    /* Pulsing Shimmer Animation for AI Rendering */
    @keyframes shimmerMove {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }

    .ai-shimmer-container {
        border-radius: 1rem;
        background: linear-gradient(90deg, #090D16 0%, #1E1B4B 35%, #2E1065 50%, #1E1B4B 65%, #090D16 100%);
        background-size: 250% 100%;
        animation: shimmerMove 2s infinite ease-in-out;
        border: 1.5px solid rgba(139, 92, 246, 0.35);
        min-height: 380px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .ai-shimmer-spinner-ring {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .shimmer-bar {
        border-radius: 50rem;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.05) 100%);
        background-size: 200% 100%;
        animation: shimmerMove 1.6s infinite linear;
    }

    .synthesized-img-stage {
        width: 100%;
        max-height: 480px;
        object-fit: contain;
        background: #070B12;
        display: block;
        transition: transform 0.3s ease;
    }

    /* Insufficient Credit Modal Styles */
    .insufficient-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(2, 6, 23, 0.82);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
    }

    .insufficient-modal-dialog {
        background: #0B1120;
        border: 1.5px solid rgba(139, 92, 246, 0.35);
        border-radius: 1.5rem;
        box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.9), 0 0 40px rgba(124, 58, 237, 0.2);
        width: 100%;
        max-width: 480px;
        padding: 2rem;
        position: relative;
        animation: modalFadeIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .btn-close-modal {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        background: rgba(30, 41, 59, 0.6);
        border: 1px solid rgba(148, 163, 184, 0.2);
        color: #94A3B8;
        width: 32px;
        height: 32px;
        border-radius: 50rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-close-modal:hover {
        background: #1E293B;
        color: #FFFFFF;
        border-color: #64748B;
    }

    .modal-icon-badge {
        width: 64px;
        height: 64px;
        border-radius: 1.25rem;
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid rgba(245, 158, 11, 0.35);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .package-preview-card {
        background: #0F172A;
        border: 1.5px solid #1E293B;
        transition: all 0.2s ease;
    }

    .package-preview-card:hover {
        background: #131E35;
        border-color: rgba(139, 92, 246, 0.5);
        transform: translateY(-2px);
    }

    .package-preview-card.popular-pack {
        border-color: rgba(124, 58, 237, 0.45);
        background: linear-gradient(180deg, rgba(124, 58, 237, 0.08) 0%, rgba(15, 23, 42, 0.9) 100%);
    }

    .package-preview-card.popular-pack:hover {
        border-color: #8B5CF6;
        box-shadow: 0 4px 20px rgba(124, 58, 237, 0.25);
    }
</style>

<div class="ai-workspace-wrapper position-relative pb-5">
    <div class="ai-workspace-glow"></div>

    <!-- ========================================================= -->
    <!-- TOP UTILITY BAR                                           -->
    <!-- ========================================================= -->
    <div class="workspace-utility-bar">
        <div class="container-fluid px-lg-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
            
            <!-- Left: Back Button & Breadcrumb -->
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('resource.show', $resource->slug ?? $resource->id) }}" 
                   class="btn btn-outline-slate btn-sm rounded-pill py-1 px-3 d-inline-flex align-items-center gap-1.5"
                   title="Return to template details">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to Template</span>
                </a>

                <div class="vr opacity-25 d-none d-sm-block" style="height: 1.5rem;"></div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill px-2.5 py-1 extra-small fw-bold" 
                          style="background: rgba(124, 58, 237, 0.2); color: #C4B5FD; border: 1px solid rgba(124, 58, 237, 0.35);">
                        <i class="bi bi-magic me-1 text-warning"></i> AI Customizer
                    </span>
                    <h1 class="h6 mb-0 fw-bold text-white text-truncate" style="max-width: 320px;" title="{{ $resource->title }}">
                        {{ $resource->title }}
                    </h1>
                </div>
            </div>

            <!-- Right: Credit Pill & User Balance Info -->
            <div class="d-flex align-items-center gap-2.5">
                <!-- 1 Edit = 1 Credit Badge / Admin Pass -->
                @if(auth()->check() && auth()->user()->isAdmin())
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill" 
                         style="background: rgba(139, 92, 246, 0.15); border: 1px solid rgba(139, 92, 246, 0.4); color: #C4B5FD;">
                        <i class="bi bi-shield-check text-purple-400"></i>
                        <span class="extra-small fw-extrabold tracking-wider font-monospace text-uppercase">Admin Pass</span>
                    </div>

                    <!-- User Available Credits Pill (Admin Unlimited) -->
                    <a href="{{ route('wallet.index') }}" 
                       class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill bg-slate-900 border border-purple-500/30 text-purple-300 small font-monospace text-decoration-none"
                       title="Admin Unlimited Access">
                        <span class="text-slate-400 extra-small">Access:</span>
                        <strong class="text-white" id="userCreditDisplay">Unlimited</strong>
                        <span class="text-warning extra-small">⚡</span>
                    </a>
                @else
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill" 
                         style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.35); color: #FBBF24;">
                        <i class="bi bi-lightning-charge-fill"></i>
                        <span class="extra-small fw-extrabold tracking-wider font-monospace text-uppercase">1 Edit = 1 Credit</span>
                    </div>

                    <!-- User Available Credits Pill -->
                    <a href="{{ route('wallet.index') }}" 
                       class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill bg-slate-900 border border-slate-800 text-slate-300 small font-monospace text-decoration-none hover-border-purple"
                       title="Your available AI generation credits • Click to buy more">
                        <span class="text-slate-400 extra-small">Balance:</span>
                        <strong class="text-white" id="userCreditDisplay">{{ $userCredits ?? auth()->user()->aiCredit?->credits ?? 0 }}</strong>
                        <span class="text-warning extra-small">⚡</span>
                        <i class="bi bi-plus-circle-fill text-purple-400 ms-1 extra-small" title="Recharge credits"></i>
                    </a>
                @endif
            </div>

        </div>
    </div>

    <!-- ========================================================= -->
    <!-- 2-COLUMN MAIN WORKSPACE AREA                              -->
    <!-- ========================================================= -->
    <div class="container-fluid px-lg-4 py-4 position-relative z-1">
        
        <div class="row g-4">

            <!-- ========================================================= -->
            <!-- LEFT COLUMN: CUSTOMIZATION CONTROL PANEL (5 COLUMNS)      -->
            <!-- ========================================================= -->
            <div class="col-12 col-xl-5">
                <div class="d-flex flex-column gap-4">

                    <!-- Template Summary Card -->
                    <div class="workspace-card">
                        <div class="d-flex align-items-center gap-3">
                            <!-- Thumbnail -->
                            <div class="rounded-3 overflow-hidden border border-slate-800 flex-shrink-0" style="width: 80px; height: 80px; background: #070B12;">
                                @php
                                    $imgUrl = $resource->preview_image 
                                        ? (Str::startsWith($resource->preview_image, ['http://', 'https://']) ? $resource->preview_image : asset('storage/' . $resource->preview_image)) 
                                        : asset('images/placeholder.jpg');
                                @endphp
                                <img src="{{ $imgUrl }}" 
                                     alt="{{ $resource->title }}" 
                                     class="w-100 h-100 object-cover"
                                     onerror="this.src='{{ asset('images/logo.png') }}';">
                            </div>

                            <!-- Meta Info -->
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge rounded-pill px-2 py-0.5 extra-small fw-semibold bg-primary bg-opacity-20 text-primary border border-primary border-opacity-30">
                                        {{ $resource->category->name ?? 'Design Template' }}
                                    </span>
                                    <span class="extra-small text-slate-400 font-monospace">
                                        <i class="bi bi-aspect-ratio me-1"></i>{{ $resource->size_dimensions ?? 'Vector / 300 DPI' }}
                                    </span>
                                </div>
                                <h2 class="h6 fw-bold text-white mb-1 text-truncate" title="{{ $resource->title }}">
                                    {{ $resource->title }}
                                </h2>
                                <div class="extra-small text-slate-400">
                                    Created by 
                                    @if($resource->owner)
                                        <a href="{{ route('user.profile', $resource->owner->username ?? $resource->owner->id) }}" class="text-purple-400 fw-semibold text-decoration-none hover-underline">
                                            {{ $resource->owner->name }}
                                        </a>
                                    @else
                                        <span class="text-slate-300">Verified Creator</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Customization Form Card -->
                    <div class="workspace-card">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-slate-800">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-sliders text-purple-400 fs-5"></i>
                                <h3 class="h6 fw-bold text-white mb-0">Customization Controls</h3>
                            </div>
                            <span class="badge bg-slate-800 text-slate-400 rounded-pill extra-small font-monospace">
                                Live Prompt Engine
                            </span>
                        </div>

                        <form id="aiCustomizerForm" onsubmit="event.preventDefault(); handleGenerateDesign();">
                            @csrf

                            <div class="d-flex flex-column gap-3.5">

                                <!-- Field 1: Main Headline / Title -->
                                <div>
                                    <label for="inputHeadline" class="form-label-ai">
                                        <span>Main Headline / Title <span class="text-danger">*</span></span>
                                        <span class="extra-small text-slate-500 font-normal" id="headlineCount">0/120</span>
                                    </label>
                                    <input type="text" 
                                           id="inputHeadline" 
                                           name="headline" 
                                           class="form-control form-control-ai" 
                                           required 
                                           maxlength="120"
                                           value="{{ $resource->title }}"
                                           placeholder="e.g. Next-Gen Mobile Banking System">
                                </div>

                                <!-- Field 2: Subtitle / Tagline -->
                                <div>
                                    <label for="inputSubtitle" class="form-label-ai">
                                        <span>Subtitle / Tagline</span>
                                        <span class="extra-small text-slate-500 font-normal" id="subtitleCount">0/200</span>
                                    </label>
                                    <textarea id="inputSubtitle" 
                                              name="subtitle" 
                                              rows="2" 
                                              class="form-control form-control-ai" 
                                              maxlength="200"
                                              placeholder="e.g. Empowering users with seamless financial transfers and real-time security.">{{ Str::limit($resource->description, 100, '') }}</textarea>
                                </div>

                                <!-- Row: Brand Name & CTA Text -->
                                <div class="row g-3">
                                    <!-- Field 3: Brand / Company Name -->
                                    <div class="col-12 col-sm-6">
                                        <label for="inputBrandName" class="form-label-ai">
                                            <span>Brand / Company</span>
                                        </label>
                                        <input type="text" 
                                               id="inputBrandName" 
                                               name="brand_name" 
                                               class="form-control form-control-ai" 
                                               maxlength="60"
                                               value="{{ auth()->user()->name ?? 'Noksha Studio' }}"
                                               placeholder="e.g. Apex Creative">
                                    </div>

                                    <!-- Field 4: Call-to-Action (CTA) Text -->
                                    <div class="col-12 col-sm-6">
                                        <label for="inputCtaText" class="form-label-ai">
                                            <span>Call to Action (CTA)</span>
                                        </label>
                                        <input type="text" 
                                               id="inputCtaText" 
                                               name="cta_text" 
                                               class="form-control form-control-ai" 
                                               maxlength="30"
                                               value="Get Started"
                                               placeholder="e.g. Order Now, Explore">
                                    </div>
                                </div>

                                <!-- Field 5: Color Tone / Vibe Selector -->
                                <div>
                                    <label class="form-label-ai mb-2">
                                        <span>Color Tone & Vibe</span>
                                        <span class="extra-small text-slate-500 font-normal">Select Palette</span>
                                    </label>
                                    
                                    <div class="row g-2">
                                        <!-- Option 1: Modern Dark -->
                                        <div class="col-6">
                                            <label class="vibe-card-option">
                                                <input type="radio" name="color_tone" value="modern_dark" checked>
                                                <div class="vibe-card-box">
                                                    <span class="vibe-swatch-dot" style="background: linear-gradient(135deg, #090D16, #8B5CF6);"></span>
                                                    <div class="min-w-0">
                                                        <div class="fw-bold text-white extra-small text-truncate">Modern Dark</div>
                                                        <div class="extra-small text-slate-400" style="font-size: 0.7rem;">Neon Violet</div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- Option 2: Vibrant Gradient -->
                                        <div class="col-6">
                                            <label class="vibe-card-option">
                                                <input type="radio" name="color_tone" value="vibrant_gradient">
                                                <div class="vibe-card-box">
                                                    <span class="vibe-swatch-dot" style="background: linear-gradient(135deg, #F43F5E, #7C3AED);"></span>
                                                    <div class="min-w-0">
                                                        <div class="fw-bold text-white extra-small text-truncate">Vibrant Gradient</div>
                                                        <div class="extra-small text-slate-400" style="font-size: 0.7rem;">Sunset Glow</div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- Option 3: Minimal Light -->
                                        <div class="col-6">
                                            <label class="vibe-card-option">
                                                <input type="radio" name="color_tone" value="minimal_light">
                                                <div class="vibe-card-box">
                                                    <span class="vibe-swatch-dot" style="background: linear-gradient(135deg, #FFFFFF, #94A3B8);"></span>
                                                    <div class="min-w-0">
                                                        <div class="fw-bold text-white extra-small text-truncate">Minimal Light</div>
                                                        <div class="extra-small text-slate-400" style="font-size: 0.7rem;">Stark Porcelain</div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- Option 4: Corporate Blue -->
                                        <div class="col-6">
                                            <label class="vibe-card-option">
                                                <input type="radio" name="color_tone" value="corporate_blue">
                                                <div class="vibe-card-box">
                                                    <span class="vibe-swatch-dot" style="background: linear-gradient(135deg, #1E3A8A, #38BDF8);"></span>
                                                    <div class="min-w-0">
                                                        <div class="fw-bold text-white extra-small text-truncate">Corporate Blue</div>
                                                        <div class="extra-small text-slate-400" style="font-size: 0.7rem;">Azure Trust</div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Triggers -->
                                <div class="d-flex align-items-center gap-3 pt-3 border-top border-slate-800">
                                    <button type="submit" id="btnGenerate" class="btn btn-generate-gradient flex-grow-1 d-inline-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-stars text-warning fs-5"></i>
                                        <span id="btnGenerateText">Generate Design</span>
                                    </button>

                                    <button type="button" id="btnReset" onclick="resetCustomizerForm()" class="btn btn-outline-slate d-inline-flex align-items-center gap-1.5" title="Reset all input fields">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                        <span>Reset</span>
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>
            </div>

            <!-- ========================================================= -->
            <!-- RIGHT COLUMN: INTERACTIVE PREVIEW STAGE (7 COLUMNS)       -->
            <!-- ========================================================= -->
            <div class="col-12 col-xl-7">
                <div class="d-flex flex-column gap-4">

                    <!-- Card 1 (Top/Reference): Original Design Preview -->
                    <div class="workspace-card">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-slate-800">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-pill px-2.5 py-1 extra-small fw-bold" style="background: rgba(148, 163, 184, 0.15); color: #CBD5E1; border: 1px solid rgba(148, 163, 184, 0.25);">
                                    <i class="bi bi-image me-1"></i> Original Design
                                </span>
                                <span class="extra-small text-slate-400">Baseline Template Reference</span>
                            </div>
                            <span class="extra-small text-slate-400 font-monospace">
                                {{ $resource->size_dimensions ?? '1920 × 1080' }}
                            </span>
                        </div>

                        <!-- Original Preview Image Box -->
                        <div class="preview-stage-container text-center p-2">
                            <img src="{{ $imgUrl }}" 
                                 alt="{{ $resource->title }} Original" 
                                 class="original-preview-img rounded-3"
                                 id="originalPreviewImage">
                        </div>
                    </div>

                    <!-- Card 2 (Bottom/Target): AI Output Container -->
                    <div class="workspace-card" id="outputCardContainer">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-slate-800">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-pill px-2.5 py-1 extra-small fw-bold" style="background: rgba(124, 58, 237, 0.25); color: #C4B5FD; border: 1px solid rgba(124, 58, 237, 0.4);">
                                    <i class="bi bi-sparkles me-1 text-warning"></i> Customized AI Output
                                </span>
                                <span class="badge bg-slate-800 text-slate-400 rounded-pill extra-small font-monospace" id="renderStatusBadge">
                                    Awaiting Generation
                                </span>
                            </div>

                            <span class="extra-small text-purple-400 font-monospace d-none" id="renderSpecsBadge">
                                1920 × 1080 • Vector Synthesised
                            </span>
                        </div>

                        <!-- State A: Empty Placeholder State -->
                        <div id="aiEmptyState" class="ai-output-empty-state">
                            <div style="width: 64px; height: 64px; border-radius: 1.25rem; background: rgba(124, 58, 237, 0.15); color: #A78BFA; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; border: 1px solid rgba(124, 58, 237, 0.3);">
                                <i class="bi bi-magic fs-2 text-warning"></i>
                            </div>
                            <h4 class="h6 fw-bold text-white mb-2">No Customized Preview Generated Yet</h4>
                            <p class="text-slate-400 extra-small mx-auto mb-0" style="max-width: 420px; line-height: 1.6;">
                                Fill out the fields on the left and click <strong>Generate</strong> to preview your customized graphic.
                            </p>
                        </div>

                        <!-- State B: Pulsing Shimmer Loading State -->
                        <div id="aiShimmerState" class="ai-shimmer-container d-none">
                            <div class="d-flex flex-column align-items-center justify-content-center text-center w-100 position-relative z-1">
                                <div class="ai-shimmer-spinner-ring mb-3">
                                    <div class="spinner-border text-purple-400" role="status" style="width: 3.25rem; height: 3.25rem; border-width: 3.5px; color: #A855F7;">
                                        <span class="visually-hidden">Rendering...</span>
                                    </div>
                                    <i class="bi bi-sparkles text-warning position-absolute top-50 start-50 translate-middle fs-5"></i>
                                </div>
                                <h5 class="fw-bold text-white mb-1.5 fs-6">Rendering Variation...</h5>
                                <p class="text-slate-400 extra-small mb-4" style="max-width: 380px;">
                                    Compositing typography, gradient ambience, and vector matrix onto high-res canvas...
                                </p>
                                <div class="w-100" style="max-width: 360px;">
                                    <div class="shimmer-bar" style="height: 12px; width: 45%; margin: 0 auto 10px;"></div>
                                    <div class="shimmer-bar" style="height: 24px; width: 85%; margin: 0 auto 10px;"></div>
                                    <div class="shimmer-bar" style="height: 14px; width: 65%; margin: 0 auto 16px;"></div>
                                    <div class="shimmer-bar" style="height: 36px; width: 40%; margin: 0 auto;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- State C: Live Interactive Render Output -->
                        <div id="aiLiveOutput" class="d-none">
                            <div class="preview-stage-container text-center p-2 position-relative">
                                <img id="synthesizedOutputImg" 
                                     alt="Customized AI Output" 
                                     class="synthesized-img-stage rounded-3">
                                <div class="position-absolute top-0 end-0 p-3">
                                    <span class="badge bg-black bg-opacity-75 text-white rounded-pill px-3 py-1.5 extra-small font-monospace border border-white border-opacity-20 backdrop-blur-sm shadow-sm">
                                        <i class="bi bi-patch-check-fill text-success me-1"></i> Synthesized High-Res
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Direct Action Buttons Footer -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-3 mt-3 border-top border-slate-800">
                            <div class="d-flex align-items-center gap-2">
                                <!-- Download High-Res JPG (initially disabled) -->
                                <button type="button" 
                                        id="btnDownloadJpg" 
                                        disabled 
                                        onclick="downloadCustomizedJpg()" 
                                        class="btn btn-outline-slate d-inline-flex align-items-center gap-2">
                                    <i class="bi bi-download"></i>
                                    <span>Download High-Res JPG</span>
                                </button>

                                <!-- Regenerate (initially disabled) -->
                                <button type="button" 
                                        id="btnRegenerate" 
                                        disabled 
                                        onclick="handleGenerateDesign()" 
                                        class="btn btn-outline-slate d-inline-flex align-items-center gap-1.5"
                                        title="Regenerate design with current settings">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <span>Re-generate</span>
                                </button>
                            </div>

                            <span class="extra-small text-slate-500 font-monospace">
                                Output Format: 1920 × 1080 JPEG
                            </span>
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

<!-- ========================================================= -->
<!-- INSUFFICIENT CREDIT IN-PAGE MODAL                         -->
<!-- ========================================================= -->
<div id="insufficientCreditModal" 
     class="insufficient-modal-backdrop d-none" 
     x-data="{ open: false }" 
     x-cloak 
     x-show="open" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @open-insufficient-modal.window="open = true; $el.classList.remove('d-none');"
     @close-insufficient-modal.window="open = false; $el.classList.add('d-none');"
     onclick="if(event.target === this) closeInsufficientCreditModal();">
    
    <div class="insufficient-modal-dialog">
        <!-- Close button top-right -->
        <button type="button" 
                onclick="closeInsufficientCreditModal()" 
                class="btn-close-modal" 
                aria-label="Close modal">
            <i class="bi bi-x-lg"></i>
        </button>

        <!-- Warning Icon Header -->
        <div class="text-center mb-3">
            <div class="modal-icon-badge mx-auto mb-3">
                <i class="bi bi-lightning-charge-fill text-warning fs-2"></i>
            </div>
            <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill mb-2" 
                 style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.35); color: #FCA5A5;">
                <span class="extra-small fw-extrabold font-monospace text-uppercase tracking-wider">⚡ 0 Credits Remaining</span>
            </div>
            <h3 class="h5 fw-extrabold text-white mb-1.5">Out of AI Credits</h3>
            <p class="text-slate-400 extra-small mx-auto mb-0" style="max-width: 380px; line-height: 1.6;">
                Each customized AI variation requires <strong>1 generation credit</strong>. Recharge your credit pack to continue synthesizing high-resolution designs.
            </p>
        </div>

        <!-- Quick Package Preview Cards -->
        <div class="row g-2.5 mb-4">
            <!-- Pack 1: Starter -->
            <div class="col-6">
                <a href="{{ route('wallet.index') }}" class="package-preview-card text-decoration-none d-block h-100 p-3 rounded-3">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="badge rounded-pill extra-small bg-slate-800 text-slate-300">Starter</span>
                        <span class="extra-small text-slate-400 font-monospace">৳4.00/ea</span>
                    </div>
                    <div class="fw-extrabold text-white fs-5 mb-0.5">25 Credits</div>
                    <div class="text-emerald-400 fw-bold small">৳100 BDT</div>
                </a>
            </div>

            <!-- Pack 2: Creator -->
            <div class="col-6">
                <a href="{{ route('wallet.index') }}" class="package-preview-card popular-pack text-decoration-none d-block h-100 p-3 rounded-3 position-relative">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="badge rounded-pill extra-small" style="background: rgba(124, 58, 237, 0.3); color: #DDD6FE; border: 1px solid rgba(124, 58, 237, 0.5);">Creator</span>
                        <span class="badge bg-warning text-dark extra-small fw-bold py-0.5 px-1.5">Popular</span>
                    </div>
                    <div class="fw-extrabold text-white fs-5 mb-0.5">75 Credits</div>
                    <div class="text-emerald-400 fw-bold small">৳250 BDT</div>
                </a>
            </div>
        </div>

        <!-- Action CTAs -->
        <div class="d-flex align-items-center gap-2.5">
            <a href="{{ route('wallet.index') }}" 
               class="btn btn-generate-gradient flex-grow-1 d-inline-flex align-items-center justify-content-center gap-2 py-2.5">
                <i class="bi bi-wallet2"></i>
                <span>Go to Credit Store</span>
            </a>
            <button type="button" 
                    onclick="closeInsufficientCreditModal()" 
                    class="btn btn-outline-slate px-4 py-2.5">
                Cancel
            </button>
        </div>
    </div>
</div>

<!-- Offscreen Canvas for High-Resolution Dynamic Synthesis & JPG Export -->
<canvas id="exportCanvas" width="1920" height="1080" style="display: none;"></canvas>

<!-- Interactive Customizer JavaScript Logic -->
<script>
    let currentSynthesizedDataUrl = null;

    document.addEventListener('DOMContentLoaded', function () {
        const headlineInput = document.getElementById('inputHeadline');
        const subtitleInput = document.getElementById('inputSubtitle');
        const headlineCount = document.getElementById('headlineCount');
        const subtitleCount = document.getElementById('subtitleCount');

        // Character Counters
        function updateCounters() {
            if (headlineInput && headlineCount) {
                headlineCount.textContent = `${headlineInput.value.length}/120`;
            }
            if (subtitleInput && subtitleCount) {
                subtitleCount.textContent = `${subtitleInput.value.length}/200`;
            }
        }

        if (headlineInput) headlineInput.addEventListener('input', updateCounters);
        if (subtitleInput) subtitleInput.addEventListener('input', updateCounters);
        updateCounters();
    });

    /**
     * Modal trigger functions for Out of Credits alert.
     */
    function openInsufficientCreditModal() {
        const modal = document.getElementById('insufficientCreditModal');
        if (modal) {
            modal.classList.remove('d-none');
        }
        window.dispatchEvent(new CustomEvent('open-insufficient-modal'));
    }

    function closeInsufficientCreditModal() {
        const modal = document.getElementById('insufficientCreditModal');
        if (modal) {
            modal.classList.add('d-none');
        }
        window.dispatchEvent(new CustomEvent('close-insufficient-modal'));
    }

    /**
     * Handles dynamic AI generation and updates output card.
     */
    function handleGenerateDesign() {
        const headline = document.getElementById('inputHeadline').value.trim();
        const subtitle = document.getElementById('inputSubtitle').value.trim();
        const brand = document.getElementById('inputBrandName').value.trim() || 'Noksha Studio';
        const cta = document.getElementById('inputCtaText').value.trim() || 'Get Started';
        const colorTone = document.querySelector('input[name="color_tone"]:checked')?.value || 'modern_dark';

        if (!headline) {
            alert('Please enter a headline before generating.');
            document.getElementById('inputHeadline').focus();
            return;
        }

        const btnGen = document.getElementById('btnGenerate');
        const btnRegen = document.getElementById('btnRegenerate');
        const btnDownload = document.getElementById('btnDownloadJpg');
        const emptyState = document.getElementById('aiEmptyState');
        const shimmerState = document.getElementById('aiShimmerState');
        const liveOutput = document.getElementById('aiLiveOutput');
        const statusBadge = document.getElementById('renderStatusBadge');
        const specsBadge = document.getElementById('renderSpecsBadge');
        const creditDisplay = document.getElementById('userCreditDisplay');

        const isAdmin = {{ auth()->check() && auth()->user()->isAdmin() ? 'true' : 'false' }};

        // Fast-path local validation: check if credit display already indicates 0 (bypassed for Admin)
        if (!isAdmin) {
            const currentCredits = parseInt(creditDisplay ? creditDisplay.textContent : '0', 10);
            if (isNaN(currentCredits) || currentCredits <= 0) {
                openInsufficientCreditModal();
                return;
            }
        }

        const originalBtnHtml = btnGen.innerHTML;
        btnGen.innerHTML = '<span class="spinner-border spinner-border-sm me-2 text-warning" role="status" aria-hidden="true"></span><span>Validating Credits...</span>';
        btnGen.disabled = true;

        // 1. Credit Deduction API Request before executing canvas synthesis
        fetch("{{ route('templates.ai-edit.deduct') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                template_id: "{{ $resource->id }}"
            })
        })
        .then(async (response) => {
            const data = await response.json().catch(() => ({}));
            return { ok: response.ok, status: response.status, data: data };
        })
        .then((result) => {
            // Check for insufficient credits or server rejection
            if (!result.ok || !result.data.success || result.data.error === 'insufficient_credits') {
                // Restore button state immediately
                btnGen.innerHTML = originalBtnHtml;
                btnGen.disabled = false;

                if (!isAdmin && result.data.remaining_credits !== undefined && creditDisplay) {
                    creditDisplay.textContent = result.data.remaining_credits;
                }

                // Trigger Insufficient Credit Modal
                openInsufficientCreditModal();
                return;
            }

            // 2. Deduction Succeeded / Admin Bypass: Update in-page credit pill badge & navbar badges dynamically without refresh
            if (result.data.is_admin || isAdmin) {
                if (creditDisplay) {
                    creditDisplay.textContent = 'Unlimited';
                }
                // Live DOM update for all navbar credit badges
                document.querySelectorAll('#navbar-credit-count, .navbar-credit-count').forEach(el => {
                    el.innerHTML = '⚡ Unlimited Credits';
                });
            } else if (result.data.remaining_credits !== undefined) {
                if (creditDisplay) {
                    creditDisplay.textContent = result.data.remaining_credits;
                }
                // Live DOM update for all navbar credit badges
                document.querySelectorAll('#navbar-credit-count, .navbar-credit-count').forEach(el => {
                    el.innerHTML = `⚡ ${result.data.remaining_credits} Credits`;
                });
            }

            // Update button to Rendering state
            btnGen.innerHTML = '<span class="spinner-border spinner-border-sm me-2 text-warning" role="status" aria-hidden="true"></span><span>Rendering Variation...</span>';

            // Show pulsing shimmer loader placeholder
            emptyState.classList.add('d-none');
            liveOutput.classList.add('d-none');
            shimmerState.classList.remove('d-none');

            statusBadge.textContent = 'Rendering Variation...';
            statusBadge.className = 'badge bg-warning bg-opacity-25 text-warning rounded-pill extra-small font-monospace';

            // Background generation metadata log
            fetch("{{ route('templates.ai-generate', $resource->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    headline: headline,
                    subtitle: subtitle,
                    brand_name: brand,
                    cta_text: cta,
                    color_tone: colorTone
                })
            }).catch(err => {
                console.log('Background generation note:', err);
            });

            // 3. Synthesize Canvas Graphics
            synthesizeCanvasDesign(headline, subtitle, brand, cta, colorTone, function (dataUrl) {
                // Natural transition delay so the smooth shimmer transition is visually apparent
                setTimeout(() => {
                    currentSynthesizedDataUrl = dataUrl;
                    const outputImg = document.getElementById('synthesizedOutputImg');
                    if (outputImg) {
                        outputImg.src = dataUrl;
                    }

                    // Hide Shimmer, show Live Output
                    shimmerState.classList.add('d-none');
                    liveOutput.classList.remove('d-none');
                    specsBadge.classList.remove('d-none');

                    statusBadge.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i> Synthesized High-Res';
                    statusBadge.className = 'badge bg-success bg-opacity-25 text-success rounded-pill extra-small font-monospace';

                    // Re-generate State & Download Action:
                    btnGen.innerHTML = '<i class="bi bi-arrow-repeat me-1.5 fs-5"></i><span>Re-generate</span>';
                    btnGen.disabled = false;

                    // Enable Download High-Res JPG button
                    btnDownload.disabled = false;
                    btnDownload.classList.remove('btn-outline-slate');
                    btnDownload.classList.add('btn-generate-gradient');

                    // Enable footer Regenerate button
                    btnRegen.disabled = false;

                    // Mobile scroll into view
                    if (window.innerWidth < 1200) {
                        document.getElementById('outputCardContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }, 750);
            });
        })
        .catch((error) => {
            console.error('AI generation flow error:', error);
            btnGen.innerHTML = originalBtnHtml;
            btnGen.disabled = false;
            alert('A network error occurred while connecting to the credit engine. Please try again.');
        });
    }

    /**
     * Client-Side HTML5 Canvas Dynamic Synthesis Engine.
     */
    function synthesizeCanvasDesign(headline, subtitle, brand, cta, colorTone, callback) {
        const canvas = document.getElementById('exportCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');

        const width = 1920;
        const height = 1080;
        canvas.width = width;
        canvas.height = height;
        ctx.clearRect(0, 0, width, height);

        const baseImg = new Image();
        baseImg.crossOrigin = 'anonymous';

        // Source of baseline template
        const imgSource = "{{ $imgUrl }}";

        function renderComposite(loadedImg) {
            // 1. Draw base template image (if loaded) or default tech background
            if (loadedImg) {
                // Draw image scaled to cover the 1920x1080 canvas
                const scale = Math.max(width / loadedImg.width, height / loadedImg.height);
                const x = (width / 2) - (loadedImg.width / 2) * scale;
                const y = (height / 2) - (loadedImg.height / 2) * scale;
                ctx.drawImage(loadedImg, x, y, loadedImg.width * scale, loadedImg.height * scale);
            } else {
                // Fallback gradient base
                const baseGrad = ctx.createLinearGradient(0, 0, width, height);
                baseGrad.addColorStop(0, '#0F172A');
                baseGrad.addColorStop(1, '#020617');
                ctx.fillStyle = baseGrad;
                ctx.fillRect(0, 0, width, height);
            }

            // 2. Apply Ambient Color Tint / Gradient Overlay matching "Color Tone / Vibe"
            if (colorTone === 'modern_dark') {
                // Deep obsidian with neon violet ambient radial lighting
                const tint = ctx.createRadialGradient(width * 0.75, height * 0.3, 100, width * 0.5, height * 0.5, 1200);
                tint.addColorStop(0, 'rgba(46, 16, 101, 0.88)'); // deep violet
                tint.addColorStop(0.65, 'rgba(15, 23, 42, 0.92)');
                tint.addColorStop(1, 'rgba(2, 6, 23, 0.96)');
                ctx.fillStyle = tint;
                ctx.fillRect(0, 0, width, height);

                // Ambient glow orb
                const orb = ctx.createRadialGradient(width * 0.8, height * 0.25, 10, width * 0.8, height * 0.25, 450);
                orb.addColorStop(0, 'rgba(139, 92, 246, 0.45)');
                orb.addColorStop(1, 'rgba(139, 92, 246, 0)');
                ctx.fillStyle = orb;
                ctx.fillRect(0, 0, width, height);
            } else if (colorTone === 'vibrant_gradient') {
                // Sunset gradient overlay: rose/coral to purple/indigo
                const tint = ctx.createLinearGradient(0, 0, width, height);
                tint.addColorStop(0, 'rgba(190, 24, 93, 0.82)');
                tint.addColorStop(0.45, 'rgba(124, 58, 237, 0.85)');
                tint.addColorStop(1, 'rgba(30, 27, 75, 0.94)');
                ctx.fillStyle = tint;
                ctx.fillRect(0, 0, width, height);

                // Vibrant highlight orb
                const orb = ctx.createRadialGradient(width * 0.2, height * 0.8, 10, width * 0.2, height * 0.8, 500);
                orb.addColorStop(0, 'rgba(244, 63, 94, 0.4)');
                orb.addColorStop(1, 'rgba(244, 63, 94, 0)');
                ctx.fillStyle = orb;
                ctx.fillRect(0, 0, width, height);
            } else if (colorTone === 'minimal_light') {
                // Clean porcelain stark ivory overlay
                const tint = ctx.createLinearGradient(0, 0, width, height);
                tint.addColorStop(0, 'rgba(248, 250, 252, 0.94)');
                tint.addColorStop(1, 'rgba(226, 232, 240, 0.92)');
                ctx.fillStyle = tint;
                ctx.fillRect(0, 0, width, height);

                // Subtle slate accent grid lines
                ctx.strokeStyle = 'rgba(15, 23, 42, 0.05)';
                ctx.lineWidth = 2;
                for (let i = 100; i < width; i += 200) {
                    ctx.beginPath();
                    ctx.moveTo(i, 0);
                    ctx.lineTo(i, height);
                    ctx.stroke();
                }
            } else {
                // corporate_blue: Deep royal azure
                const tint = ctx.createRadialGradient(width * 0.8, height * 0.3, 50, width * 0.5, height * 0.5, 1200);
                tint.addColorStop(0, 'rgba(30, 58, 138, 0.88)');
                tint.addColorStop(0.65, 'rgba(15, 23, 42, 0.92)');
                tint.addColorStop(1, 'rgba(3, 7, 18, 0.96)');
                ctx.fillStyle = tint;
                ctx.fillRect(0, 0, width, height);

                const orb = ctx.createRadialGradient(width * 0.85, height * 0.2, 10, width * 0.85, height * 0.2, 450);
                orb.addColorStop(0, 'rgba(56, 189, 248, 0.4)');
                orb.addColorStop(1, 'rgba(56, 189, 248, 0)');
                ctx.fillStyle = orb;
                ctx.fillRect(0, 0, width, height);
            }

            // 3. Composite Central Scrim / Frosted Glass Focus Card
            const isLight = colorTone === 'minimal_light';
            const cardX = 140;
            const cardY = 120;
            const cardW = 1640;
            const cardH = 840;

            // Soft backdrop card
            ctx.fillStyle = isLight ? 'rgba(255, 255, 255, 0.65)' : 'rgba(15, 23, 42, 0.55)';
            ctx.beginPath();
            ctx.roundRect(cardX, cardY, cardW, cardH, 28);
            ctx.fill();

            ctx.strokeStyle = isLight ? 'rgba(15, 23, 42, 0.08)' : 'rgba(255, 255, 255, 0.12)';
            ctx.lineWidth = 1.5;
            ctx.stroke();

            // Decorative background geometric accent circles
            ctx.strokeStyle = isLight ? 'rgba(15, 23, 42, 0.06)' : 'rgba(255, 255, 255, 0.06)';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.arc(cardX + cardW - 240, cardY + cardH / 2, 280, 0, Math.PI * 2);
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(cardX + cardW - 240, cardY + cardH / 2, 160, 0, Math.PI * 2);
            ctx.stroke();

            // 4. Draw Brand Pill
            const textColor = isLight ? '#0F172A' : '#FFFFFF';
            const subColor = isLight ? '#475569' : '#CBD5E1';

            ctx.font = 'bold 22px "Plus Jakarta Sans", sans-serif';
            const brandText = brand.toUpperCase();
            const brandMetrics = ctx.measureText(brandText);
            const pillW = brandMetrics.width + 70;
            const pillH = 48;
            const pillX = cardX + 70;
            const pillY = cardY + 70;

            ctx.fillStyle = isLight ? 'rgba(15, 23, 42, 0.06)' : 'rgba(255, 255, 255, 0.1)';
            ctx.beginPath();
            ctx.roundRect(pillX, pillY, pillW, pillH, 24);
            ctx.fill();

            ctx.strokeStyle = isLight ? 'rgba(15, 23, 42, 0.12)' : 'rgba(255, 255, 255, 0.18)';
            ctx.stroke();

            // Green live status dot
            ctx.fillStyle = '#10B981';
            ctx.beginPath();
            ctx.arc(pillX + 24, pillY + pillH / 2, 6, 0, Math.PI * 2);
            ctx.fill();

            ctx.fillStyle = textColor;
            ctx.fillText(brandText, pillX + 42, pillY + 32);

            // 5. Draw Headline (Multi-line text wrapping with custom font sizes)
            ctx.fillStyle = textColor;
            ctx.font = 'bold 64px "Plus Jakarta Sans", sans-serif';

            const maxTextWidth = 1100;
            const words = headline.split(' ');
            let line = '';
            let currentY = cardY + 230;
            const lineHeight = 80;

            for (let n = 0; n < words.length; n++) {
                const testLine = line + words[n] + ' ';
                const metrics = ctx.measureText(testLine);
                if (metrics.width > maxTextWidth && n > 0) {
                    ctx.fillText(line, cardX + 70, currentY);
                    line = words[n] + ' ';
                    currentY += lineHeight;
                } else {
                    line = testLine;
                }
            }
            ctx.fillText(line, cardX + 70, currentY);

            // 6. Draw Subtitle / Tagline
            if (subtitle) {
                currentY += 45;
                ctx.fillStyle = subColor;
                ctx.font = '28px "Plus Jakarta Sans", sans-serif';
                const subWords = subtitle.split(' ');
                let subLine = '';
                for (let n = 0; n < subWords.length; n++) {
                    const testSubLine = subLine + subWords[n] + ' ';
                    const metrics = ctx.measureText(testSubLine);
                    if (metrics.width > maxTextWidth && n > 0) {
                        ctx.fillText(subLine, cardX + 70, currentY);
                        subLine = subWords[n] + ' ';
                        currentY += 44;
                    } else {
                        subLine = testSubLine;
                    }
                }
                ctx.fillText(subLine, cardX + 70, currentY);
            }

            // 7. Draw Call-to-Action (CTA) Pill Button
            currentY += 65;
            ctx.font = 'bold 24px "Plus Jakarta Sans", sans-serif';
            const ctaText = cta + '  →';
            const ctaMetrics = ctx.measureText(ctaText);
            const ctaBtnW = Math.max(240, ctaMetrics.width + 60);
            const ctaBtnH = 64;

            ctx.fillStyle = isLight ? '#0F172A' : '#FFFFFF';
            ctx.beginPath();
            ctx.roundRect(cardX + 70, currentY, ctaBtnW, ctaBtnH, 32);
            ctx.fill();

            ctx.fillStyle = isLight ? '#FFFFFF' : '#0F172A';
            ctx.fillText(ctaText, cardX + 100, currentY + 41);

            // 8. Footer Specs & Watermark
            ctx.fillStyle = isLight ? 'rgba(15, 23, 42, 0.45)' : 'rgba(255, 255, 255, 0.4)';
            ctx.font = '20px monospace';
            ctx.fillText('NOKSHA AI TEMPLATE CUSTOMIZER • HIGH-RESOLUTION ASSET #{{ $resource->id }}', cardX + 70, cardY + cardH - 50);

            let dataUrl;
            try {
                dataUrl = canvas.toDataURL('image/jpeg', 0.95);
            } catch (e) {
                console.warn('Canvas export fallback:', e);
                // If tainted, redraw without external image
                if (loadedImg) {
                    renderComposite(null);
                    return;
                }
            }

            if (callback && dataUrl) {
                callback(dataUrl);
            }
        }

        baseImg.onload = function () {
            renderComposite(baseImg);
        };

        baseImg.onerror = function () {
            console.warn('Could not load base image via CORS, synthesizing using vector gradient layers.');
            renderComposite(null);
        };

        baseImg.src = imgSource;
    }

    /**
     * Resets customization form back to clean baseline.
     */
    function resetCustomizerForm() {
        document.getElementById('aiCustomizerForm').reset();
        document.getElementById('inputHeadline').value = '{{ addslashes($resource->title) }}';
        document.getElementById('inputSubtitle').value = '{{ addslashes(Str::limit($resource->description, 100, '')) }}';
        document.getElementById('inputBrandName').value = '{{ addslashes(auth()->user()->name ?? 'Noksha Studio') }}';
        document.getElementById('inputCtaText').value = 'Get Started';

        // Re-enable modern dark radio
        const firstRadio = document.querySelector('input[name="color_tone"][value="modern_dark"]');
        if (firstRadio) firstRadio.checked = true;

        // Reset containers
        document.getElementById('aiEmptyState').classList.remove('d-none');
        document.getElementById('aiShimmerState').classList.add('d-none');
        document.getElementById('aiLiveOutput').classList.add('d-none');
        document.getElementById('renderSpecsBadge').classList.add('d-none');

        // Reset status badge
        const statusBadge = document.getElementById('renderStatusBadge');
        statusBadge.textContent = 'Awaiting Generation';
        statusBadge.className = 'badge bg-slate-800 text-slate-400 rounded-pill extra-small font-monospace';

        // Restore primary button to Generate Design
        const btnGen = document.getElementById('btnGenerate');
        btnGen.disabled = false;
        btnGen.innerHTML = '<i class="bi bi-stars text-warning fs-5"></i><span>Generate Design</span>';

        // Disable Download High-Res JPG button
        const btnDownload = document.getElementById('btnDownloadJpg');
        btnDownload.disabled = true;
        btnDownload.className = 'btn btn-outline-slate d-inline-flex align-items-center gap-2';

        // Disable Regenerate button
        document.getElementById('btnRegenerate').disabled = true;

        currentSynthesizedDataUrl = null;

        // Clear output image src
        const outputImg = document.getElementById('synthesizedOutputImg');
        if (outputImg) outputImg.src = '';

        // Clear canvas
        const canvas = document.getElementById('exportCanvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        // Update counters
        const headlineCount = document.getElementById('headlineCount');
        const subtitleCount = document.getElementById('subtitleCount');
        if (headlineCount) headlineCount.textContent = `${document.getElementById('inputHeadline').value.length}/120`;
        if (subtitleCount) subtitleCount.textContent = `${document.getElementById('inputSubtitle').value.length}/200`;
    }

    /**
     * Renders high-res 1920x1080 canvas export and triggers direct JPG download.
     */
    function downloadCustomizedJpg() {
        if (!currentSynthesizedDataUrl) {
            const canvas = document.getElementById('exportCanvas');
            if (canvas) {
                currentSynthesizedDataUrl = canvas.toDataURL('image/jpeg', 0.95);
            }
        }

        if (!currentSynthesizedDataUrl) return;

        const link = document.createElement('a');
        link.download = `noksha-customized-{{ $resource->id }}.jpg`;
        link.href = currentSynthesizedDataUrl;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
@endsection
