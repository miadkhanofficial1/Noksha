@extends('layouts.app')

@section('title', 'Seller Verification - Noksha (নকশা)')

@section('content')

<!-- CUSTOM FIGMA GLASSMORPHISM VERIFICATION STYLES -->
<style>
    .verification-hero-section {
        background: linear-gradient(135deg, #6C4CF1 0%, #8B5CF6 50%, #9F7AEA 100%);
        position: relative;
    }

    .glass-verification-card {
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(108, 76, 241, 0.2) !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 20px 45px -10px rgba(108, 76, 241, 0.15) !important;
    }

    /* Status Header Cards */
    .status-card-box {
        border-radius: 1.25rem;
        border: 1px solid rgba(108, 76, 241, 0.15);
        padding: 1.5rem;
        background: #ffffff;
    }

    /* Drag & Drop Upload Containers */
    .kyc-dropzone {
        border: 2px dashed rgba(108, 76, 241, 0.3);
        border-radius: 1.25rem;
        background: rgba(108, 76, 241, 0.03);
        padding: 2rem 1.25rem;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
    }

    .kyc-dropzone:hover {
        border-color: #6C4CF1;
        background: rgba(108, 76, 241, 0.08);
        transform: translateY(-2px);
    }

    .form-control-figma, .form-select-figma {
        border-radius: 0.85rem !important;
        border: 1px solid rgba(108, 76, 241, 0.2) !important;
        padding: 0.75rem 1rem !important;
        font-size: 0.95rem;
        background: #ffffff;
    }

    .form-control-figma:focus, .form-select-figma:focus {
        border-color: #6C4CF1 !important;
        box-shadow: 0 0 0 4px rgba(108, 76, 241, 0.18) !important;
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
        transform: translateY(-2px);
        box-shadow: 0 14px 28px -4px rgba(108, 76, 241, 0.45);
    }
</style>

<!-- VERIFICATION HERO BANNER -->
<section class="verification-hero-section py-4 py-lg-5 text-white">
    <div class="container py-2">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 small fw-bold mb-3 border border-white border-opacity-25">
                    <i class="bi bi-shield-check-fill me-1 text-warning"></i> Seller Compliance & KYC
                </span>
                <h1 class="display-5 fw-extrabold text-white mb-2">
                    Seller Identity Verification <span class="text-warning">(ভেরিফিকেশন)</span>
                </h1>
                <p class="fs-6 text-white text-opacity-90 mb-0" style="max-width: 620px;">
                    Verify your seller profile to earn the Pro Verified badge, build buyer trust, and unlock asset publishing on Noksha.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('seller.dashboard') }}" class="btn btn-light rounded-pill px-4 py-2.5 fw-bold text-primary shadow-sm">
                    <i class="bi bi-speedometer2 me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</section>

<!-- MAIN VERIFICATION CONTENT SECTION -->
<section class="py-5" style="background-color: #F8F7FF;">
    <div class="container py-2" style="max-width: 900px;">
        
        <!-- Flash Success Notification -->
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-lg rounded-4 p-4 mb-4 d-flex align-items-center gap-3 text-dark bg-white">
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Submission Complete!</h5>
                    <p class="mb-0 small text-secondary">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Validation Error Summary -->
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

        <!-- VERIFICATION STATUS CARD -->
        <div class="status-card-box shadow-sm mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    @if(isset($verification) && $verification->status === 'approved')
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle fs-3">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div>
                            <span class="badge bg-success text-white rounded-pill px-3 py-1.5 fw-bold mb-1">
                                <i class="bi bi-check-circle-fill me-1"></i> Approved
                            </span>
                            <h5 class="fw-bold text-dark mb-0">Pro Verified Author</h5>
                            <p class="text-secondary extra-small mb-0">Your identity has been verified by Noksha Moderation.</p>
                        </div>
                    @elseif(isset($verification) && $verification->status === 'pending')
                        <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-circle fs-3">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold mb-1">
                                <i class="bi bi-hourglass-split me-1"></i> Pending Review
                            </span>
                            <h5 class="fw-bold text-dark mb-0">Documents Under Review</h5>
                            <p class="text-secondary extra-small mb-0">Submitted on {{ $verification->created_at->format('M d, Y') }}. Review takes up to 24 hours.</p>
                        </div>
                    @elseif(isset($verification) && $verification->status === 'rejected')
                        <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-circle fs-3">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div>
                            <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fw-bold mb-1">
                                <i class="bi bi-x-circle-fill me-1"></i> Rejected / Resubmission Required
                            </span>
                            <h5 class="fw-bold text-dark mb-0">Verification Declined</h5>
                            <p class="text-secondary extra-small mb-0">Please check your document legibility and resubmit below.</p>
                        </div>
                    @else
                        <div class="p-3 bg-secondary bg-opacity-10 text-secondary rounded-circle fs-3">
                            <i class="bi bi-shield-exclamation"></i>
                        </div>
                        <div>
                            <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 fw-bold mb-1">
                                <i class="bi bi-info-circle-fill me-1"></i> Not Submitted
                            </span>
                            <h5 class="fw-bold text-dark mb-0">Identity Verification Required</h5>
                            <p class="text-secondary extra-small mb-0">Complete your identity check to earn seller badges.</p>
                        </div>
                    @endif
                </div>

                <div class="text-end">
                    <span class="small fw-bold text-muted d-block mb-1">KYC Level</span>
                    <span class="badge bg-light text-primary border rounded-pill px-3 py-1.5 fw-bold">Tier 2 Seller KYC</span>
                </div>
            </div>
        </div>

        <!-- GLASSMORPHISM VERIFICATION FORM -->
        <div class="card glass-verification-card p-4 p-md-5">
            <form action="{{ route('seller.verification.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- SECTION 1: PERSONAL IDENTITY -->
                <div class="mb-5">
                    <h5 class="fw-extrabold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-person-vcard-fill text-primary"></i> 1. Personal Identity
                    </h5>
                    <p class="text-secondary small mb-4">Enter your legal information as printed on your official government ID.</p>

                    <div class="row g-4">
                        <!-- Full Name -->
                        <div class="col-12 col-md-6">
                            <label for="full_name" class="form-label fw-bold text-dark small">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="full_name" class="form-control form-control-figma @error('full_name') is-invalid @enderror" placeholder="As shown on NID / Passport" value="{{ old('full_name', $verification->full_name ?? auth()->user()->name) }}" required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div class="col-12 col-md-6">
                            <label for="date_of_birth" class="form-label fw-bold text-dark small">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control form-control-figma @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', isset($verification->date_of_birth) ? $verification->date_of_birth->format('Y-m-d') : '') }}" required>
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div class="col-12 col-md-6">
                            <label for="country" class="form-label fw-bold text-dark small">Country of Residence <span class="text-danger">*</span></label>
                            <select name="country" id="country" class="form-select form-select-figma @error('country') is-invalid @enderror" required>
                                <option value="Bangladesh" {{ old('country', $verification->country ?? 'Bangladesh') == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                <option value="United States" {{ old('country', $verification->country ?? '') == 'United States' ? 'selected' : '' }}>United States</option>
                                <option value="United Kingdom" {{ old('country', $verification->country ?? '') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="Canada" {{ old('country', $verification->country ?? '') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                <option value="India" {{ old('country', $verification->country ?? '') == 'India' ? 'selected' : '' }}>India</option>
                            </select>
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Government ID Type -->
                        <div class="col-12 col-md-6">
                            <label for="id_type" class="form-label fw-bold text-dark small">Government ID Type <span class="text-danger">*</span></label>
                            <select name="id_type" id="id_type" class="form-select form-select-figma @error('id_type') is-invalid @enderror" required>
                                <option value="nid" {{ old('id_type', $verification->id_type ?? 'nid') == 'nid' ? 'selected' : '' }}>National ID Card (NID)</option>
                                <option value="passport" {{ old('id_type', $verification->id_type ?? '') == 'passport' ? 'selected' : '' }}>International Passport</option>
                                <option value="driving_license" {{ old('id_type', $verification->id_type ?? '') == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                            </select>
                            @error('id_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4 opacity-10">

                <!-- SECTION 2: GOVERNMENT ID DOCUMENT UPLOAD -->
                <div class="mb-5">
                    <h5 class="fw-extrabold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-person-fill text-primary"></i> 2. Government ID Document
                    </h5>
                    <p class="text-secondary small mb-4">Upload a clear scan or photo of your front & back NID, Passport, or License.</p>

                    <div class="kyc-dropzone" onclick="document.getElementById('id_file').click();">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                            <i class="bi bi-cloud-arrow-up fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Click or Drag ID Document Here</h6>
                        <p class="text-muted extra-small mb-2">Supports JPG, PNG, PDF (Max 10MB)</p>
                        <span class="badge bg-white text-primary border rounded-pill px-3 py-1 small fw-bold" id="idFileName">Choose Document File</span>
                        <input type="file" name="id_file" id="id_file" class="d-none" accept=".jpg,.jpeg,.png,.pdf" required onchange="document.getElementById('idFileName').textContent = this.files[0].name">
                    </div>
                    @error('id_file')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4 opacity-10">

                <!-- SECTION 3: SELFIE PHOTO UPLOAD -->
                <div class="mb-5">
                    <h5 class="fw-extrabold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-camera-fill text-primary"></i> 3. Face Selfie Photo
                    </h5>
                    <p class="text-secondary small mb-4">Upload a recent, well-lit photo of your face without glasses or hat.</p>

                    <div class="kyc-dropzone" onclick="document.getElementById('selfie_file').click();">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                            <i class="bi bi-person-bounding-box fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Click or Drag Selfie Photo</h6>
                        <p class="text-muted extra-small mb-2">Supports JPG, PNG (Max 5MB)</p>
                        <span class="badge bg-white text-primary border rounded-pill px-3 py-1 small fw-bold" id="selfieFileName">Choose Selfie File</span>
                        <input type="file" name="selfie_file" id="selfie_file" class="d-none" accept="image/*" required onchange="document.getElementById('selfieFileName').textContent = this.files[0].name">
                    </div>
                    @error('selfie_file')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4 opacity-10">

                <!-- SECTION 4: SHORT VIDEO VERIFICATION (MAX 15 SEC) -->
                <div class="mb-5">
                    <h5 class="fw-extrabold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-record-circle-fill text-primary"></i> 4. Short Video Verification <span class="badge bg-light text-muted border font-monospace extra-small ms-1">Optional</span>
                    </h5>
                    <p class="text-secondary small mb-4">Upload a short 5–15 second selfie video confirming your identity (Max 15 sec, MP4/WEBM).</p>

                    <div class="kyc-dropzone" onclick="document.getElementById('video_file').click();">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                            <i class="bi bi-file-earmark-play fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Click or Drag Short Video Clip</h6>
                        <p class="text-muted extra-small mb-2">Supports MP4, WEBM, MOV (Max 15s / 50MB)</p>
                        <span class="badge bg-white text-primary border rounded-pill px-3 py-1 small fw-bold" id="videoFileName">Choose Video File</span>
                        <input type="file" name="video_file" id="video_file" class="d-none" accept="video/mp4,video/webm,video/quicktime" onchange="document.getElementById('videoFileName').textContent = this.files[0].name">
                    </div>
                    @error('video_file')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4 opacity-10">

                <!-- SECTION 5: AGREEMENT & SUBMIT ACTION BAR -->
                <div class="mb-4">
                    <div class="form-check p-3 bg-light rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="agreement" id="agreement" value="1" required>
                        <label class="form-check-label fw-bold text-dark small" for="agreement">
                            I confirm that all submitted information is accurate and matches my legal government identity.
                        </label>
                    </div>
                    @error('agreement')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 pt-3 border-top">
                    <span class="extra-small text-muted">
                        <i class="bi bi-lock-fill text-success me-1"></i> End-to-end encrypted storage according to KYC data standards.
                    </span>

                    <button type="submit" class="btn btn-purple-cta btn-lg rounded-pill px-5 py-3 fw-bold w-100 w-sm-auto">
                        <i class="bi bi-shield-check me-2"></i> Submit Verification Application
                    </button>
                </div>

            </form>
        </div>

    </div>
</section>

@endsection
