@extends('layouts.app')

@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
@endphp

@section('title', __('upload.page_title') . ' - Noksha')

@section('content')

<!-- SLEEK CREATOR STUDIO STYLES -->
<style>
    .upload-hero-section {
        background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 50%, #9333EA 100%);
        position: relative;
    }

    /* Minimalist Sleek Warning Banner */
    .warning-suspension-banner {
        background: rgba(220, 38, 38, 0.92);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 0.75rem;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(220, 38, 38, 0.18);
    }

    .form-studio-card {
        background: #ffffff;
        border: 1px solid rgba(124, 58, 237, 0.12) !important;
        border-radius: 1.25rem !important;
        box-shadow: 0 10px 30px -10px rgba(79, 70, 229, 0.08) !important;
    }

    /* Left Column: Image Dropzone */
    .image-dropzone-box {
        border: 2px dashed rgba(124, 58, 237, 0.28);
        border-radius: 1rem;
        background: rgba(124, 58, 237, 0.02);
        padding: 2.5rem 1.25rem;
        text-align: center;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        position: relative;
        min-height: 340px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .image-dropzone-box:hover, .image-dropzone-box.dragover {
        border-color: #7C3AED;
        background: rgba(124, 58, 237, 0.06);
        transform: translateY(-2px);
    }

    .image-preview-wrapper {
        position: relative;
        border-radius: 1rem;
        overflow: hidden;
        border: 1.5px solid rgba(124, 58, 237, 0.2);
        box-shadow: 0 8px 20px -4px rgba(79, 70, 229, 0.12);
        background: #0B0F19;
    }

    .image-preview-wrapper img {
        width: 100%;
        max-height: 380px;
        object-fit: cover;
        display: block;
    }

    /* Pricing Set 1 Block */
    .pricing-set-box {
        background: #FAF8FF;
        border: 1.5px solid rgba(124, 58, 237, 0.2);
        border-radius: 1rem;
        padding: 1.5rem;
        position: relative;
    }

    .pricing-set-badge {
        position: absolute;
        top: -12px;
        left: 20px;
        background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 0.02em;
        padding: 0.25rem 0.85rem;
        border-radius: 50rem;
        box-shadow: 0 4px 10px rgba(124, 58, 237, 0.3);
    }

    /* Studio Form Controls */
    .form-control-studio, .form-select-studio {
        border-radius: 0.75rem !important;
        border: 1px solid rgba(124, 58, 237, 0.2) !important;
        padding: 0.7rem 0.95rem !important;
        font-size: 0.92rem;
        transition: all 0.2s ease;
        background: #ffffff;
    }

    .form-control-studio:focus, .form-select-studio:focus {
        border-color: #7C3AED !important;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.16) !important;
        background: #ffffff;
    }

    /* ======================================================== */
    /* DISTINCT EXTENSION BUTTONS (Dark Slate vs High-Contrast) */
    /* ======================================================== */
    .ext-toggle-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.95rem;
        border-radius: 0.65rem;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        user-select: none;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        
        /* Default Unselected: Clean dark slate button with subtle border */
        background: #0F172A;
        border: 1px solid #334155;
        color: #94A3B8;
    }

    .ext-toggle-btn:hover {
        border-color: #64748B;
        color: #F8FAFC;
        transform: translateY(-1px);
    }

    /* Selected State: High-contrast active gradient with glow & ring */
    .ext-toggle-input:checked + .ext-toggle-btn {
        background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%) !important;
        border-color: transparent !important;
        color: #FFFFFF !important;
        font-weight: 700;
        box-shadow: 0 6px 18px -2px rgba(124, 58, 237, 0.45);
        outline: 2px solid rgba(167, 139, 250, 0.6);
        outline-offset: 1px;
    }

    .ext-toggle-btn .ext-check-svg {
        display: none;
        width: 14px;
        height: 14px;
    }

    .ext-toggle-input:checked + .ext-toggle-btn .ext-check-svg {
        display: inline-block;
    }

    /* Color Swatches */
    .color-swatch-circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .color-swatch-circle:hover {
        transform: scale(1.15);
    }

    /* CTA Button */
    .btn-studio-cta {
        background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 700;
        border-radius: 50rem;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 8px 22px -4px rgba(124, 58, 237, 0.4);
    }

    .btn-studio-cta:hover {
        background: linear-gradient(135deg, #6D28D9 0%, #4338CA 100%);
        transform: translateY(-2px);
        box-shadow: 0 12px 28px -4px rgba(124, 58, 237, 0.5);
    }

    /* Dark Mode Overrides */
    html.dark .form-studio-card,
    html.dark .pricing-set-box {
        background: #1E293B !important;
        border-color: #334155 !important;
    }

    html.dark .form-control-studio,
    html.dark .form-select-studio {
        background-color: #0F172A !important;
        border-color: #334155 !important;
        color: #F8FAFC !important;
    }

    html.dark .image-dropzone-box {
        background: rgba(15, 23, 42, 0.5) !important;
        border-color: #334155 !important;
    }

    html.dark .text-dark {
        color: #F8FAFC !important;
    }

    html.dark .text-secondary {
        color: #94A3B8 !important;
    }
</style>

<!-- UPLOAD HERO BANNER -->
<section class="upload-hero-section py-3 py-md-4 text-white">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-2.5 py-1 extra-small fw-bold mb-1.5 border border-white border-opacity-25">
                    <i class="bi bi-palette2 me-1"></i> {{ __('upload.hero_badge') }}
                </span>
                <h1 class="h3 fw-extrabold text-white mb-0">
                    {{ __('upload.hero_title') }}
                </h1>
                <p class="extra-small text-white text-opacity-80 mb-0">
                    {{ __('upload.hero_subtitle') }}
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-outline-light rounded-pill px-3.5 py-1.5 fw-bold shadow-sm extra-small" data-bs-toggle="modal" data-bs-target="#uploadGuidelinesModalBS">
                    <i class="bi bi-book-half me-1"></i> Upload Guidelines
                </button>
                <a href="{{ route('seller.dashboard') }}" class="btn btn-light rounded-pill px-3.5 py-1.5 fw-bold text-primary shadow-sm extra-small">
                    <i class="bi bi-speedometer2 me-1"></i> {{ __('upload.back_dashboard') }}
                </a>
            </div>
        </div>
    </div>
</section>

<!-- MAIN STUDIO CONTENT -->
<section class="py-4" style="background-color: #F8F7FF; min-height: 80vh;">
    <div class="container" style="max-width: 1100px;">

        <!-- 1. CONCISE SUSPENSION WARNING BANNER -->
        <div class="warning-suspension-banner py-2.5 px-3 mb-3.5 d-flex align-items-center gap-2.5">
            <i class="bi bi-shield-slash-fill text-warning fs-5 flex-shrink-0"></i>
            <div class="small fw-semibold flex-grow-1">
                {{ __('upload.warning_suspension') }}
            </div>
            <a href="#contributorRulesCollapse" class="text-white text-decoration-underline extra-small fw-bold flex-shrink-0" data-bs-toggle="collapse">
                {{ __('upload.view_guidelines') }}
            </a>
        </div>

        <!-- 2. COLLAPSIBLE GUIDELINES -->
        <div class="collapse mb-3.5" id="contributorRulesCollapse">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="p-2.5 rounded-2 bg-light border h-100">
                            <span class="fw-bold text-primary small d-block mb-1">{{ __('upload.guideline_1_title') }}</span>
                            <span class="extra-small text-secondary">{{ __('upload.guideline_1_desc') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-2.5 rounded-2 bg-light border h-100">
                            <span class="fw-bold text-success small d-block mb-1">{{ __('upload.guideline_2_title') }}</span>
                            <span class="extra-small text-secondary">{{ __('upload.guideline_2_desc') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-2.5 rounded-2 bg-light border h-100">
                            <span class="fw-bold text-warning small d-block mb-1">{{ __('upload.guideline_3_title') }}</span>
                            <span class="extra-small text-secondary">{{ __('upload.guideline_3_desc') }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-2 text-danger extra-small fw-bold">
                    <i class="bi bi-info-circle-fill me-1"></i> {{ __('upload.guideline_zip_note') }}
                </div>
            </div>
        </div>

        <!-- Validation Error Alerts Display -->
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-3 p-3 mb-3 bg-white text-danger border-start border-danger border-4">
                <ul class="mb-0 small ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- MAIN FORM CARD -->
        <div class="card form-studio-card p-3.5 p-md-4">
            <form action="{{ route('resource.store') }}" method="POST" enctype="multipart/form-data" id="designUploadForm">
                @csrf

                <!-- TWO-COLUMN LAYOUT -->
                <div class="row g-4">
                    
                    <!-- ========================================== -->
                    <!-- LEFT COLUMN: DRAG & DROP PREVIEW ZONE -->
                    <!-- ========================================== -->
                    <div class="col-12 col-lg-5">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label fw-bold text-dark small mb-0">
                                {{ __('upload.cover_image') }} <span class="text-danger">*</span>
                            </label>
                            <span class="extra-small text-muted">{{ __('upload.cover_image_specs') }}</span>
                        </div>

                        <!-- Dropzone Container -->
                        <div class="image-dropzone-box" id="imageDropZone" onclick="document.getElementById('preview_image').click();">
                            <div class="mb-2 text-primary opacity-80" style="font-size: 3rem; line-height: 1;">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1 small">
                                {{ __('upload.dropzone_text') }}
                            </h6>
                            <p class="text-muted extra-small mb-3">
                                {{ __('upload.dropzone_sub') }}
                            </p>
                            <button type="button" class="btn btn-outline-primary rounded-pill px-3.5 py-1.5 fw-bold extra-small" onclick="event.stopPropagation(); document.getElementById('preview_image').click();">
                                <i class="bi bi-image me-1"></i> {{ __('upload.add_image_btn') }}
                            </button>
                            <input type="file" name="preview_image" id="preview_image" class="d-none" accept="image/png,image/jpeg,image/webp,image/jpg" required onchange="handlePreviewImageSelect(this)">
                        </div>

                        <!-- Active Image Preview Container (Hidden by default) -->
                        <div id="imagePreviewContainer" class="d-none mt-2">
                            <div class="image-preview-wrapper position-relative">
                                <img id="imagePreviewCanvas" src="" alt="Cover Preview">
                                <div class="position-absolute top-0 end-0 p-2">
                                    <button type="button" class="btn btn-danger btn-sm rounded-circle p-1.5 shadow" onclick="removeSelectedImage()" title="Remove">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-1.5 px-1">
                                <span class="extra-small fw-bold text-success" id="imageFileName">image.png</span>
                                <span class="extra-small text-muted" id="imageFileSize">0 KB</span>
                            </div>
                        </div>
                        @error('preview_image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        <!-- Minimalist Tip Tooltip -->
                        <div class="mt-3 p-2.5 bg-light rounded-3 border extra-small text-secondary d-flex align-items-center gap-2">
                            <i class="bi bi-lightbulb text-warning fs-6"></i>
                            <span>{{ __('upload.image_tip_desc') }}</span>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- RIGHT COLUMN: METADATA & PRICING BLOCK -->
                    <!-- ========================================== -->
                    <div class="col-12 col-lg-7">
                        
                        <!-- 1. Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold text-dark small mb-1">
                                {{ __('upload.title') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" id="title" class="form-control form-control-studio @error('title') is-invalid @enderror" placeholder="{{ __('upload.title_placeholder') }}" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 2. Category -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-bold text-dark small mb-1">
                                {{ __('upload.category') }} <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" id="category_id" class="form-select form-select-studio @error('category_id') is-invalid @enderror" required>
                                <option value="">-- {{ __('upload.select_category') }} --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 3. Color Palette -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="form-label fw-bold text-dark small mb-0">
                                    {{ __('upload.color') }} <span class="text-danger">*</span>
                                </label>
                                <span class="extra-small text-muted">{{ __('upload.color_sub') }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <input type="color" name="color" id="colorPicker" value="{{ old('color', '#7C3AED') }}" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 32px; height: 32px; cursor: pointer;">
                                <span class="badge bg-light text-dark border px-2 py-1 font-monospace extra-small" id="colorHexText">{{ old('color', '#7C3AED') }}</span>
                                
                                <!-- Color Swatch Circles -->
                                <button type="button" class="color-swatch-circle" style="background: #000000;" onclick="selectColor('#000000')" title="Black"></button>
                                <button type="button" class="color-swatch-circle" style="background: #FFFFFF; border-color: #cbd5e1;" onclick="selectColor('#FFFFFF')" title="White"></button>
                                <button type="button" class="color-swatch-circle" style="background: #7C3AED;" onclick="selectColor('#7C3AED')" title="Purple"></button>
                                <button type="button" class="color-swatch-circle" style="background: #3B82F6;" onclick="selectColor('#3B82F6')" title="Blue"></button>
                                <button type="button" class="color-swatch-circle" style="background: #10B981;" onclick="selectColor('#10B981')" title="Green"></button>
                                <button type="button" class="color-swatch-circle" style="background: #EF4444;" onclick="selectColor('#EF4444')" title="Red"></button>
                                <button type="button" class="color-swatch-circle" style="background: #F59E0B;" onclick="selectColor('#F59E0B')" title="Yellow"></button>
                            </div>
                        </div>

                        <!-- 4. Supported Extensions (DISTINCT BUTTON SELECTION STATES) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small mb-1.5 d-block">
                                {{ __('upload.extensions') }} <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                @php
                                    $availableExts = ['Figma', 'PSD', 'AI', 'XD', 'EPS', 'Sketch', 'SVG', 'PDF'];
                                @endphp
                                @foreach($availableExts as $ext)
                                    <div>
                                        <input type="checkbox" name="extensions[]" value="{{ $ext }}" id="ext_{{ $ext }}" class="d-none ext-toggle-input" {{ $loop->first ? 'checked' : '' }}>
                                        <label for="ext_{{ $ext }}" class="ext-toggle-btn">
                                            <!-- Crisp SVG Checkmark -->
                                            <svg class="ext-check-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $ext }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 5. Tags -->
                        <div class="mb-3.5">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label for="tagsInput" class="form-label fw-bold text-dark small mb-0">
                                    {{ __('upload.tags') }} <span class="text-danger">*</span>
                                </label>
                                <span class="extra-small text-muted font-monospace" id="tagCounter">0/10 {{ __('upload.tags_max') }}</span>
                            </div>
                            <input type="text" name="tags" id="tagsInput" class="form-control form-control-studio" placeholder="{{ __('upload.tags_placeholder') }}" value="{{ old('tags') }}" oninput="updateTagCounter(this)">
                            <div class="extra-small text-muted mt-1">{{ __('upload.tags_sub') }}</div>
                        </div>

                        <!-- ========================================== -->
                        <!-- PRICING & FILE ASSET BLOCK ("Pricing Set - 1") -->
                        <!-- ========================================== -->
                        <div class="pricing-set-box mb-3.5">
                            <span class="pricing-set-badge">
                                {{ __('upload.pricing_set') }}
                            </span>

                            <div class="row g-3 mt-0.5">
                                <!-- Size / Dimensions -->
                                <div class="col-12 col-md-6">
                                    <label for="size_dimensions" class="form-label fw-bold text-dark small mb-1">
                                        {{ __('upload.size_dimensions') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="size_dimensions" id="size_dimensions" class="form-control form-control-studio @error('size_dimensions') is-invalid @enderror" placeholder="{{ __('upload.size_placeholder') }}" value="{{ old('size_dimensions', '1920x1080 px') }}" required>
                                </div>

                                <!-- Status (Active / Inactive) -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-dark small mb-1">
                                        {{ __('upload.status') }}
                                    </label>
                                    <select name="status_toggle" id="status_toggle" class="form-select form-select-studio">
                                        <option value="active" selected>{{ __('upload.status_active') }}</option>
                                        <option value="inactive">{{ __('upload.status_inactive') }}</option>
                                    </select>
                                </div>

                                <!-- ZIP File Upload -->
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label class="form-label fw-bold text-dark small mb-0">
                                            {{ __('upload.zip_file') }} <span class="text-danger">*</span>
                                        </label>
                                        <span class="badge bg-danger bg-opacity-10 text-danger extra-small fw-bold">{{ __('upload.zip_note') }}</span>
                                    </div>
                                    <div class="p-2.5 border rounded-3 bg-white d-flex align-items-center justify-content-between flex-wrap gap-2" style="border-style: dashed !important; border-width: 1.5px !important; border-color: rgba(124,58,237,0.3) !important;">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-2 fs-5">
                                                <i class="bi bi-file-earmark-zip-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark extra-small" id="zipFileNameDisplay">{{ __('upload.zip_no_file') }}</div>
                                                <div class="extra-small text-muted" id="zipFileSizeDisplay">{{ __('upload.zip_format_only') }}</div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-studio-cta btn-sm px-3 py-1 extra-small" onclick="document.getElementById('resource_file').click();">
                                            <i class="bi bi-folder2-open me-1"></i> {{ __('upload.zip_select_btn') }}
                                        </button>
                                        <input type="file" name="resource_file" id="resource_file" class="d-none" accept=".zip" required onchange="handleZipFileSelect(this)">
                                    </div>
                                    @error('resource_file')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Pricing Type: Free / Premium -->
                                <div class="col-12 col-md-5">
                                    <label class="form-label fw-bold text-dark small mb-1">
                                        {{ __('upload.asset_type') }} <span class="text-danger">*</span>
                                    </label>
                                    <select name="is_paid" id="is_paid" class="form-select form-select-studio" onchange="togglePricingType(this.value)">
                                        <option value="1" {{ old('is_paid', '1') == '1' ? 'selected' : '' }}>{{ __('upload.type_premium') }}</option>
                                        <option value="0" {{ old('is_paid') == '0' ? 'selected' : '' }}>{{ __('upload.type_free') }}</option>
                                    </select>
                                </div>

                                <!-- Price in BDT -->
                                <div class="col-12 col-md-7" id="priceInputContainer">
                                    <label for="price" class="form-label fw-bold text-dark small mb-1">
                                        {{ __('upload.price') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light fw-bold text-primary">৳</span>
                                        <input type="number" step="1" min="10" name="price" id="price" class="form-control form-control-studio @error('price') is-invalid @enderror" placeholder="{{ __('upload.price_placeholder') }}" value="{{ old('price', '299') }}" oninput="calculateCreatorShare(this.value)">
                                    </div>
                                    <div class="extra-small text-success fw-bold mt-1 d-flex align-items-center gap-1" id="shareCalculatorText">
                                        <i class="bi bi-cash-stack"></i> {{ __('upload.share_calculation') }}: ৳149.50
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold text-dark small mb-1">
                                {{ __('upload.description') }} <span class="text-danger">*</span>
                            </label>
                            <textarea name="description" id="description" rows="3" class="form-control form-control-studio @error('description') is-invalid @enderror" placeholder="{{ __('upload.description_placeholder') }}" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Live Demo Link (Optional) -->
                        <div class="mb-3.5">
                            <label for="demo_link" class="form-label fw-bold text-dark small mb-1">
                                {{ __('upload.demo_link') }}
                            </label>
                            <input type="url" name="demo_link" id="demo_link" class="form-control form-control-studio" placeholder="{{ __('upload.demo_placeholder') }}" value="{{ old('demo_link') }}">
                        </div>

                        <!-- SUBMIT BUTTON -->
                        <div class="pt-1">
                            <button type="submit" class="btn btn-studio-cta btn-lg w-100 py-2.5 fw-bold shadow">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> {{ __('upload.submit_button') }}
                            </button>
                            <div class="text-center mt-2 extra-small text-muted">
                                <i class="bi bi-shield-check text-success me-1"></i> {{ __('upload.submit_sub') }}
                            </div>
                        </div>

                    </div>
                </div>

            </form>
        </div>

    </div>
</section>

<!-- INTERACTIVE SCRIPTS -->
<script>
// Handle Image Drop & Select
function handlePreviewImageSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        if (!file.type.match('image.*')) {
            alert('Please select a valid image file (JPG, PNG, WEBP).');
            return;
        }

        document.getElementById('imageFileName').textContent = file.name;
        document.getElementById('imageFileSize').textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreviewCanvas').src = e.target.result;
            document.getElementById('imageDropZone').classList.add('d-none');
            document.getElementById('imagePreviewContainer').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
}

function removeSelectedImage() {
    document.getElementById('preview_image').value = '';
    document.getElementById('imagePreviewCanvas').src = '';
    document.getElementById('imagePreviewContainer').classList.add('d-none');
    document.getElementById('imageDropZone').classList.remove('d-none');
}

// Drag & Drop handlers for Image
const dropZone = document.getElementById('imageDropZone');
['dragenter', 'dragover'].forEach(name => {
    dropZone.addEventListener(name, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.add('dragover');
    });
});
['dragleave', 'drop'].forEach(name => {
    dropZone.addEventListener(name, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('dragover');
    });
});
dropZone.addEventListener('drop', (e) => {
    const dt = e.dataTransfer;
    const files = dt.files;
    if (files && files.length > 0) {
        document.getElementById('preview_image').files = files;
        handlePreviewImageSelect(document.getElementById('preview_image'));
    }
});

// Color picker & presets
function selectColor(hex) {
    document.getElementById('colorPicker').value = hex;
    document.getElementById('colorHexText').textContent = hex;
}
document.getElementById('colorPicker').addEventListener('input', function() {
    document.getElementById('colorHexText').textContent = this.value.toUpperCase();
});

// ZIP file select handler
function handleZipFileSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const ext = file.name.split('.').pop().toLowerCase();
        
        if (ext !== 'zip') {
            alert('Invalid file format. Only .zip archives are allowed.');
            input.value = '';
            document.getElementById('zipFileNameDisplay').textContent = "{{ __('upload.zip_no_file') }}";
            document.getElementById('zipFileSizeDisplay').textContent = "{{ __('upload.zip_format_only') }}";
            return;
        }

        document.getElementById('zipFileNameDisplay').textContent = file.name;
        document.getElementById('zipFileSizeDisplay').textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB (ZIP Archive)';
    }
}

// Pricing switch & 50% revenue share calculator
function togglePricingType(val) {
    const priceBox = document.getElementById('priceInputContainer');
    const priceInput = document.getElementById('price');
    if (val === '0') {
        priceBox.classList.add('d-none');
        priceInput.removeAttribute('required');
    } else {
        priceBox.classList.remove('d-none');
        priceInput.setAttribute('required', 'required');
        calculateCreatorShare(priceInput.value);
    }
}

const shareLabelBase = "{{ __('upload.share_calculation') }}";
function calculateCreatorShare(amount) {
    const val = parseFloat(amount) || 0;
    const share = (val * 0.50).toFixed(2);
    document.getElementById('shareCalculatorText').innerHTML = 
        `<i class="bi bi-cash-stack"></i> ${shareLabelBase}: ৳${share}`;
}

// Tags counter (Max 10)
function updateTagCounter(input) {
    const val = input.value.trim();
    if (!val) {
        document.getElementById('tagCounter').textContent = '0/10 {{ __("upload.tags_max") }}';
        return;
    }
    const tags = val.split(',').filter(t => t.trim().length > 0);
    document.getElementById('tagCounter').textContent = `${tags.length}/10 {{ __("upload.tags_max") }}`;
    if (tags.length > 10) {
        document.getElementById('tagCounter').classList.add('text-danger');
    } else {
        document.getElementById('tagCounter').classList.remove('text-danger');
    }
}

// Initial calculation on load
document.addEventListener('DOMContentLoaded', function() {
    const initialPrice = document.getElementById('price').value;
    if (initialPrice) {
        calculateCreatorShare(initialPrice);
    }
    updateTagCounter(document.getElementById('tagsInput'));
});
</script>

<!-- UPLOAD GUIDELINES MODAL (BOOTSTRAP) -->
<div class="modal fade" id="uploadGuidelinesModalBS" tabindex="-1" aria-labelledby="uploadGuidelinesModalBSLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25 rounded-4 shadow-2xl overflow-hidden">
            <div class="modal-header border-bottom border-secondary border-opacity-25 bg-black bg-opacity-40 py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 rounded-3 bg-primary bg-opacity-20 text-primary border border-primary border-opacity-25">
                        <i class="bi bi-book-half fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" id="uploadGuidelinesModalBSLabel">Noksha Creator Upload Guidelines</h5>
                        <p class="text-secondary extra-small mb-0">Follow our quality and licensing rules to ensure rapid asset approval</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-dark">
                <div class="d-flex flex-column gap-3">
                    <!-- Rule 1: Cover Preview -->
                    <div class="p-3 rounded-3 bg-black bg-opacity-40 border border-secondary border-opacity-25 d-flex align-items-start gap-3">
                        <div class="p-2 rounded-3 bg-purple bg-opacity-20 text-purple border border-purple border-opacity-25 flex-shrink-0" style="color: #a855f7;">
                            <i class="bi bi-image fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-white mb-1 small">1. High-Resolution Cover Preview</h6>
                            <p class="text-secondary extra-small mb-0 leading-relaxed">
                                Upload a clean, high-resolution preview image in <strong>16:9</strong> or <strong>4:3</strong> aspect ratio. Supported formats are <strong>JPG, PNG, or WEBP</strong> (Max file size: <strong>5MB</strong>). Avoid pixelated imagery, external watermarks, or intrusive text overlays.
                            </p>
                        </div>
                    </div>

                    <!-- Rule 2: Source File Packing -->
                    <div class="p-3 rounded-3 bg-black bg-opacity-40 border border-secondary border-opacity-25 d-flex align-items-start gap-3">
                        <div class="p-2 rounded-3 bg-primary bg-opacity-20 text-primary border border-primary border-opacity-25 flex-shrink-0">
                            <i class="bi bi-file-earmark-zip fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-white mb-1 small">2. Clean Source Package (.ZIP)</h6>
                            <p class="text-secondary extra-small mb-0 leading-relaxed">
                                All downloadable asset packages must strictly be packed into a clean <strong>.ZIP archive</strong> (Max 100MB). Include well-structured raw vector and design files: <strong>Figma (.fig), Adobe Photoshop (.psd), Illustrator (.ai), EPS, SVG</strong>, alongside font license documentation and readme notes.
                            </p>
                        </div>
                    </div>

                    <!-- Rule 3: Category & Tags -->
                    <div class="p-3 rounded-3 bg-black bg-opacity-40 border border-secondary border-opacity-25 d-flex align-items-start gap-3">
                        <div class="p-2 rounded-3 bg-success bg-opacity-20 text-success border border-success border-opacity-25 flex-shrink-0">
                            <i class="bi bi-tags fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-white mb-1 small">3. Relevant Categories & Keyword Tags</h6>
                            <p class="text-secondary extra-small mb-0 leading-relaxed">
                                Select the most precise marketplace category (UI Kit, Mockups, Templates, Graphics, etc.). Provide at least <strong>3 to 5 comma-separated tags</strong> (e.g., <em>dashboard, dark mode, fintech, responsive</em>) to optimize discoverability on marketplace search engines.
                            </p>
                        </div>
                    </div>

                    <!-- Rule 4: IP Rights -->
                    <div class="p-3 rounded-3 bg-black bg-opacity-40 border border-danger border-opacity-40 d-flex align-items-start gap-3">
                        <div class="p-2 rounded-3 bg-danger bg-opacity-20 text-danger border border-danger border-opacity-25 flex-shrink-0">
                            <i class="bi bi-shield-x fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-danger mb-1 small">4. 100% Original Intellectual Property</h6>
                            <p class="text-secondary extra-small mb-0 leading-relaxed">
                                You must hold full ownership and intellectual property rights for every file uploaded. The upload of ripped designs, freeware without commercial re-distribution rights, or copyright-infringing content is strictly prohibited and results in <strong>immediate and permanent account suspension</strong>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25 bg-black bg-opacity-40 py-3 px-4 d-flex justify-content-between align-items-center flex-wrap">
                <span class="text-secondary extra-small">Noksha Creator Standards v2026.1</span>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('guidelines.download-pdf') }}" target="_blank" class="btn btn-primary rounded-pill px-3.5 py-1.5 extra-small fw-bold">
                        <i class="bi bi-download me-1"></i> Download PDF Guidelines
                    </a>
                    <button type="button" class="btn btn-secondary rounded-pill px-3.5 py-1.5 extra-small" data-bs-dismiss="modal">
                        Got it, Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
