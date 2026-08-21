@extends('layouts.app')

@section('title', 'Upload New Asset - Noksha (নকশা)')

@section('content')

<!-- CUSTOM FIGMA/DRIBBBLE GLASSMORPHISM UPLOAD STYLES -->
<style>
    .upload-hero-section {
        background: linear-gradient(135deg, #6C4CF1 0%, #8B5CF6 50%, #9F7AEA 100%);
        position: relative;
    }

    .glass-upload-card {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(108, 76, 241, 0.2) !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 20px 45px -10px rgba(108, 76, 241, 0.15) !important;
    }

    /* Drag & Drop Upload Boxes */
    .dropzone-box {
        border: 2px dashed rgba(108, 76, 241, 0.3);
        border-radius: 1.25rem;
        background: rgba(108, 76, 241, 0.03);
        padding: 2.5rem 1.5rem;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        position: relative;
    }

    .dropzone-box:hover, .dropzone-box.dragover {
        border-color: #6C4CF1;
        background: rgba(108, 76, 241, 0.08);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.2);
    }

    .dropzone-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(108, 76, 241, 0.1);
        color: #6C4CF1;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto;
        font-size: 1.75rem;
        transition: transform 0.3s ease;
    }

    .dropzone-box:hover .dropzone-icon {
        transform: scale(1.1) rotate(-5deg);
    }

    /* Form Controls */
    .form-control-figma, .form-select-figma {
        border-radius: 0.85rem !important;
        border: 1px solid rgba(108, 76, 241, 0.2) !important;
        padding: 0.75rem 1rem !important;
        font-size: 0.95rem;
        transition: all 0.25s ease;
        background: #ffffff;
    }

    .form-control-figma:focus, .form-select-figma:focus {
        border-color: #6C4CF1 !important;
        box-shadow: 0 0 0 4px rgba(108, 76, 241, 0.18) !important;
        background: #ffffff;
    }

    /* Pricing Option Cards */
    .price-option-card {
        border: 2px solid rgba(108, 76, 241, 0.15);
        border-radius: 1rem;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .price-option-card:hover, .price-option-card.active {
        border-color: #6C4CF1;
        background: rgba(108, 76, 241, 0.04);
        box-shadow: 0 8px 20px -4px rgba(108, 76, 241, 0.2);
    }

    .btn-purple-cta {
        background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 8px 20px -4px rgba(108, 76, 241, 0.35);
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #5A3DE0 0%, #4327C6 100%);
        transform: translateY(-2px) scale(1.01);
        box-shadow: 0 14px 28px -4px rgba(108, 76, 241, 0.45);
        color: #ffffff !important;
    }
</style>

<!-- UPLOAD HERO BANNER -->
<section class="upload-hero-section py-4 py-lg-5 text-white">
    <div class="container py-3">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 small fw-bold mb-3 border border-white border-opacity-25">
                    <i class="bi bi-cloud-arrow-up-fill me-1"></i> Creator Studio
                </span>
                <h1 class="display-5 fw-extrabold text-white mb-2">
                    Upload Digital Asset <span class="text-warning">(নতুন রিসোর্স আপলোড করুন)</span>
                </h1>
                <p class="fs-6 text-white text-opacity-90 mb-0" style="max-width: 620px;">
                    Publish your Figma templates, vector graphics, UI kits, and 3D assets to Noksha Creator Marketplace.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('home') }}" class="btn btn-light rounded-pill px-4 py-2.5 fw-bold text-primary shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Marketplace
                </a>
            </div>
        </div>
    </div>
</section>

<!-- MAIN UPLOAD FORM SECTION -->
<section class="py-5" style="background-color: #F8F7FF;">
    <div class="container py-2" style="max-width: 960px;">
        
        <!-- Flash Success Notification -->
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-lg rounded-4 p-4 mb-4 d-flex align-items-center gap-3 text-dark bg-white">
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Upload Successful!</h5>
                    <p class="mb-0 small text-secondary">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Validation Error Alerts -->
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-4 p-4 mb-4 bg-white text-danger border-start border-danger border-4">
                <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Please correct the following errors:</h6>
                <ul class="mb-0 small ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Glassmorphism Upload Card Form -->
        <div class="card glass-upload-card p-4 p-md-5">
            <form action="{{ route('resource.store') }}" method="POST" enctype="multipart/form-data" id="resourceUploadForm">
                @csrf

                <!-- SECTION 1: BASIC ASSET INFORMATION -->
                <div class="mb-5">
                    <h5 class="fw-extrabold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill text-primary"></i> 1. Asset Overview & Information
                    </h5>
                    <p class="text-secondary small mb-4">Provide clear titles and details to help buyers discover your design.</p>

                    <div class="row g-4">
                        <!-- Asset Title -->
                        <div class="col-12">
                            <label for="title" class="form-label fw-bold text-dark small">Resource Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control form-control-figma @error('title') is-invalid @enderror" placeholder="e.g. Fintech Mobile App UI Kit, Corporate Business Flyer" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category Selection -->
                        <div class="col-12 col-md-6">
                            <label for="category_id" class="form-label fw-bold text-dark small">Primary Category</label>
                            <select name="category_id" id="category_id" class="form-select form-select-figma @error('category_id') is-invalid @enderror">
                                <option value="">-- Select Category --</option>
                                @if(isset($categories) && $categories->count() > 0)
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <!-- Fallback Options if DB categories pending seed -->
                                    <option value="1">UI Kits</option>
                                    <option value="2">Logos & Vectors</option>
                                    <option value="3">Social Media Templates</option>
                                    <option value="4">Posters & Flyers</option>
                                    <option value="5">Branding Guidelines</option>
                                    <option value="6">Web Design Systems</option>
                                    <option value="7">3D Mockups</option>
                                @endif
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Asset Description -->
                        <div class="col-12">
                            <label for="description" class="form-label fw-bold text-dark small">Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="4" class="form-control form-control-figma @error('description') is-invalid @enderror" placeholder="Explain what makes this resource unique, screen count, software layers, and font specs..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4 opacity-10">

                <!-- SECTION 2: MEDIA & FILE UPLOADS -->
                <div class="mb-5">
                    <h5 class="fw-extrabold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-cloud-upload-fill text-primary"></i> 2. Media Preview & Source File Storage
                    </h5>
                    <p class="text-secondary small mb-4">Upload a high-resolution preview image and your packaged asset source file.</p>

                    <div class="row g-4">
                        <!-- Preview Image Upload Box -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark small">Cover Preview Image <span class="text-danger">*</span></label>
                            <div class="dropzone-box" id="previewDropZone" onclick="document.getElementById('preview_image').click();">
                                <div class="dropzone-icon">
                                    <i class="bi bi-image"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Click or Drag Image Here</h6>
                                <p class="text-muted extra-small mb-2">Supports PNG, JPG, WEBP (Max 5MB)</p>
                                <span class="badge bg-white text-primary border rounded-pill px-3 py-1 small fw-bold" id="previewFileName">Choose Cover File</span>
                                <input type="file" name="preview_image" id="preview_image" class="d-none" accept="image/*" required onchange="handlePreviewImageSelect(this)">
                            </div>
                            <!-- Image Preview Display Canvas -->
                            <div id="imagePreviewContainer" class="mt-3 text-center d-none">
                                <img id="imagePreviewCanvas" class="img-fluid rounded-3 border shadow-sm" style="max-height: 140px;" alt="Selected Preview">
                            </div>
                            @error('preview_image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Main Resource File Upload Box -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark small">Main Resource Package File <span class="text-danger">*</span></label>
                            <div class="dropzone-box" id="fileDropZone" onclick="document.getElementById('resource_file').click();">
                                <div class="dropzone-icon">
                                    <i class="bi bi-file-earmark-zip"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Click or Drag Asset Package</h6>
                                <p class="text-muted extra-small mb-2">Supports ZIP, RAR, PSD, AI, SVG (Max 50MB)</p>
                                <span class="badge bg-white text-primary border rounded-pill px-3 py-1 small fw-bold" id="resourceFileName">Choose Source ZIP</span>
                                <input type="file" name="resource_file" id="resource_file" class="d-none" accept=".zip,.rar,.psd,.ai,.svg,.pdf" required onchange="handleResourceFileSelect(this)">
                            </div>
                            <!-- File Upload Progress Bar Simulation -->
                            <div id="uploadProgressBar" class="mt-3 d-none">
                                <div class="d-flex justify-content-between small fw-bold text-muted mb-1">
                                    <span>File Selected</span>
                                    <span id="fileSizeText">0 MB</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 8px;">
                                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="width: 100%;"></div>
                                </div>
                            </div>
                            @error('resource_file')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4 opacity-10">

                <!-- SECTION 3: PRICING & COMMERCIAL MODEL -->
                <div class="mb-5">
                    <h5 class="fw-extrabold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-tag-fill text-primary"></i> 3. Pricing & Access Control
                    </h5>
                    <p class="text-secondary small mb-4">Choose whether to publish this item as a Free download or a Paid commercial asset.</p>

                    <!-- Hidden Is Paid Input -->
                    <input type="hidden" name="is_paid" id="is_paid" value="{{ old('is_paid', '0') }}">

                    <div class="row g-3 mb-4">
                        <!-- Free Option -->
                        <div class="col-6">
                            <div class="price-option-card text-center {{ old('is_paid', '0') == '0' ? 'active' : '' }}" id="optFree" onclick="selectPricingModel('0')">
                                <div class="fs-4 text-success mb-1"><i class="bi bi-gift-fill"></i></div>
                                <h6 class="fw-bold text-dark mb-0">Free Resource</h6>
                                <span class="extra-small text-muted">Available to all users (৳0)</span>
                            </div>
                        </div>

                        <!-- Paid Option -->
                        <div class="col-6">
                            <div class="price-option-card text-center {{ old('is_paid') == '1' ? 'active' : '' }}" id="optPaid" onclick="selectPricingModel('1')">
                                <div class="fs-4 text-primary mb-1"><i class="bi bi-cash-stack"></i></div>
                                <h6 class="fw-bold text-dark mb-0">Paid Commercial</h6>
                                <span class="extra-small text-muted">Set price in BDT (৳)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Price Input Container (Shown only when Paid) -->
                    <div id="priceInputBox" class="{{ old('is_paid') == '1' ? '' : 'd-none' }}">
                        <label for="price" class="form-label fw-bold text-dark small">Price in BDT (৳) <span class="text-danger">*</span></label>
                        <div class="input-group" style="max-width: 320px;">
                            <span class="input-group-text bg-light fw-bold">৳</span>
                            <input type="number" step="0.01" min="1" name="price" id="price" class="form-control form-control-figma @error('price') is-invalid @enderror" placeholder="299" value="{{ old('price') }}">
                        </div>
                        @error('price')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4 opacity-10">

                <!-- SECTION 4: METADATA, TAGS & LINKS -->
                <div class="mb-5">
                    <h5 class="fw-extrabold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-sliders text-primary"></i> 4. Metadata, Tags & Live Demo
                    </h5>
                    <p class="text-secondary small mb-4">Add optional metadata to enhance search indexing and AI recommendation matching.</p>

                    <div class="row g-4">
                        <!-- AI Tags -->
                        <div class="col-12 col-md-6">
                            <label for="tags" class="form-label fw-bold text-dark small">Tags (Comma Separated)</label>
                            <input type="text" name="tags" id="tags" class="form-control form-control-figma" placeholder="e.g. figma, ui-kit, fintech, dark-mode, mobile" value="{{ old('tags') }}">
                            <div class="form-text extra-small">Separate keywords with commas for AI search indexing.</div>
                        </div>

                        <!-- Live Demo URL -->
                        <div class="col-12 col-md-6">
                            <label for="demo_link" class="form-label fw-bold text-dark small">Live Demo / Figma Preview URL</label>
                            <input type="url" name="demo_link" id="demo_link" class="form-control form-control-figma @error('demo_link') is-invalid @enderror" placeholder="https://figma.com/file/..." value="{{ old('demo_link') }}">
                            @error('demo_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- System Requirements -->
                        <div class="col-12">
                            <label for="requirements" class="form-label fw-bold text-dark small">System & Software Requirements</label>
                            <textarea name="requirements" id="requirements" rows="2" class="form-control form-control-figma" placeholder="e.g. Figma Desktop v116+, Inter & Plus Jakarta Sans Fonts required">{{ old('requirements') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT ACTION BAR -->
                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 pt-3 border-top">
                    <span class="small text-muted">
                        <i class="bi bi-shield-check text-success me-1"></i> All uploaded assets undergo automatic AI scan & moderation checks.
                    </span>

                    <button type="submit" class="btn btn-purple-cta btn-lg rounded-pill px-5 py-3 fw-bold w-100 w-sm-auto">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> Submit & Publish Asset
                    </button>
                </div>

            </form>
        </div>

    </div>
</section>

<!-- INTERACTIVE DRAG & DROP AND FORM TOGGLE SCRIPT -->
<script>
function selectPricingModel(modelVal) {
    document.getElementById('is_paid').value = modelVal;
    const optFree = document.getElementById('optFree');
    const optPaid = document.getElementById('optPaid');
    const priceInputBox = document.getElementById('priceInputBox');

    if (modelVal === '1') {
        optFree.classList.remove('active');
        optPaid.classList.add('active');
        priceInputBox.classList.remove('d-none');
        document.getElementById('price').setAttribute('required', 'required');
    } else {
        optPaid.classList.remove('active');
        optFree.classList.add('active');
        priceInputBox.classList.add('d-none');
        document.getElementById('price').removeAttribute('required');
    }
}

function handlePreviewImageSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('previewFileName').textContent = file.name;
        
        const reader = new FileReader();
        reader.onload = function (e) {
            const canvas = document.getElementById('imagePreviewCanvas');
            canvas.src = e.target.result;
            document.getElementById('imagePreviewContainer').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
}

function handleResourceFileSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('resourceFileName').textContent = file.name;
        const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
        document.getElementById('fileSizeText').textContent = sizeMb + ' MB';
        document.getElementById('uploadProgressBar').classList.remove('d-none');
    }
}

// Drag and drop event handlers
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    document.getElementById('previewDropZone').addEventListener(eventName, preventDefaults, false);
    document.getElementById('fileDropZone').addEventListener(eventName, preventDefaults, false);
});

function preventDefaults (e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    document.getElementById('previewDropZone').addEventListener(eventName, () => document.getElementById('previewDropZone').classList.add('dragover'), false);
    document.getElementById('fileDropZone').addEventListener(eventName, () => document.getElementById('fileDropZone').classList.add('dragover'), false);
});

['dragleave', 'drop'].forEach(eventName => {
    document.getElementById('previewDropZone').addEventListener(eventName, () => document.getElementById('previewDropZone').classList.remove('dragover'), false);
    document.getElementById('fileDropZone').addEventListener(eventName, () => document.getElementById('fileDropZone').classList.remove('dragover'), false);
});

document.getElementById('previewDropZone').addEventListener('drop', function(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    document.getElementById('preview_image').files = files;
    handlePreviewImageSelect(document.getElementById('preview_image'));
});

document.getElementById('fileDropZone').addEventListener('drop', function(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    document.getElementById('resource_file').files = files;
    handleResourceFileSelect(document.getElementById('resource_file'));
});
</script>

@endsection
