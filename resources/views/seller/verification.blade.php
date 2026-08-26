@extends('layouts.app')

@section('title', 'Seller Identity Verification - Noksha (নকশা)')

@section('content')

<!-- CUSTOM FIGMA GLASSMORPHISM VERIFICATION STYLES -->
<style>
    .verification-hero-section {
        background: linear-gradient(135deg, #6C4CF1 0%, #8B5CF6 50%, #9F7AEA 100%);
        position: relative;
    }

    .glass-verification-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(108, 76, 241, 0.2) !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 20px 45px -10px rgba(108, 76, 241, 0.15) !important;
    }

    /* Status Header Box */
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
                    <i class="bi bi-shield-check-fill me-1 text-warning"></i> Seller Identity Verification (KYC)
                </span>
                <h1 class="display-5 fw-extrabold text-white mb-2">
                    Seller Identity Verification <span class="text-warning">(ভেরিফিকেশন)</span>
                </h1>
                <p class="fs-6 text-white text-opacity-90 mb-0" style="max-width: 620px;">
                    Verify your identity to earn the Pro Verified badge, unlock seller permissions, and build trust on Noksha Marketplace.
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
                    <h5 class="fw-bold mb-1">Submission Successful!</h5>
                    <p class="mb-0 small text-secondary">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Validation Error Summary -->
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-4 p-4 mb-4 bg-white text-danger border-start border-danger border-4">
                <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Please fix the errors below:</h6>
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
                            <h5 class="fw-bold text-dark mb-0">Pro Verified Author Status</h5>
                            <p class="text-secondary extra-small mb-0">
                                Verified on {{ $verification->reviewed_at ? $verification->reviewed_at->format('M d, Y') : $verification->updated_at->format('M d, Y') }}.
                            </p>
                        </div>
                    @elseif(isset($verification) && $verification->status === 'pending')
                        <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-circle fs-3">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold mb-1">
                                <i class="bi bi-hourglass-split me-1"></i> Pending Review
                            </span>
                            <h5 class="fw-bold text-dark mb-0">Application Under Compliance Review</h5>
                            <p class="text-secondary extra-small mb-0">
                                Submitted on {{ $verification->submitted_at ? $verification->submitted_at->format('M d, Y h:i A') : $verification->created_at->format('M d, Y') }}.
                            </p>
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
                            <p class="text-secondary extra-small mb-0">
                                {{ $verification->admin_note ?? 'Please re-upload clear government ID and selfie photos.' }}
                            </p>
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
                            <p class="text-secondary extra-small mb-0">Please submit your official identity details below.</p>
                        </div>
                    @endif
                </div>

                <div class="text-end">
                    <span class="small fw-bold text-muted d-block mb-1">Security Standard</span>
                    <span class="badge bg-light text-primary border rounded-pill px-3 py-1.5 fw-bold">Local Encrypted KYC Storage</span>
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
                        <i class="bi bi-person-vcard-fill text-primary"></i> Identity Information
                    </h5>
                    <p class="text-secondary small mb-4">Provide your legal details as printed on your government identity card.</p>

                    <div class="row g-4">
                        <!-- Full Name -->
                        <div class="col-12 col-md-6">
                            <label for="full_name" class="form-label fw-bold text-dark small">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="full_name" class="form-control form-control-figma @error('full_name') is-invalid @enderror" placeholder="Legal full name" value="{{ old('full_name', $verification->full_name ?? auth()->user()->name) }}" required>
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
                            <label for="country" class="form-label fw-bold text-dark small">Country <span class="text-danger">*</span></label>
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
                            <label for="document_type" class="form-label fw-bold text-dark small">Government ID Document <span class="text-danger">*</span></label>
                            <select name="document_type" id="document_type" class="form-select form-select-figma @error('document_type') is-invalid @enderror" required>
                                <option value="nid" {{ old('document_type', $verification->document_type ?? 'nid') == 'nid' ? 'selected' : '' }}>NID Card (National ID)</option>
                                <option value="passport" {{ old('document_type', $verification->document_type ?? '') == 'passport' ? 'selected' : '' }}>International Passport</option>
                                <option value="driving_license" {{ old('document_type', $verification->document_type ?? '') == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                            </select>
                            @error('document_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4 opacity-10">

                <!-- SECTION 2: GOVERNMENT ID DOCUMENT UPLOAD -->
                <div class="mb-5">
                    <h5 class="fw-extrabold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-person-fill text-primary"></i> Government ID Upload
                    </h5>
                    <p class="text-secondary small mb-4">Upload NID, Passport, or Driving License document (JPG, PNG, PDF max 10MB).</p>

                    <div class="kyc-dropzone" onclick="document.getElementById('document_file').click();">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                            <i class="bi bi-cloud-arrow-up fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Click or Drag Government ID File Here</h6>
                        <p class="text-muted extra-small mb-2">Allowed Formats: JPG, PNG, PDF (Max 10MB)</p>
                        <span class="badge bg-white text-primary border rounded-pill px-3 py-1 small fw-bold" id="docFileName">
                            {{ isset($verification->document_file) ? 'Replace Document File' : 'Choose Document File' }}
                        </span>
                        <input type="file" name="document_file" id="document_file" class="d-none" accept=".jpg,.jpeg,.png,.pdf" {{ isset($verification) ? '' : 'required' }} onchange="document.getElementById('docFileName').textContent = this.files[0].name">
                    </div>
                    @error('document_file')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4 opacity-10">

                <!-- SECTION 3: SELFIE PHOTO UPLOAD -->
                <div class="mb-5">
                    <h5 class="fw-extrabold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-camera-fill text-primary"></i> Selfie Photo Upload
                    </h5>
                    <p class="text-secondary small mb-4">Upload a clear photo of your face (JPG, PNG max 5MB).</p>

                    <div class="kyc-dropzone" onclick="document.getElementById('selfie_file').click();">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                            <i class="bi bi-person-bounding-box fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Click or Drag Selfie Photo Here</h6>
                        <p class="text-muted extra-small mb-2">Allowed Formats: JPG, PNG (Max 5MB)</p>
                        <span class="badge bg-white text-primary border rounded-pill px-3 py-1 small fw-bold" id="selfieFileName">
                            {{ isset($verification->selfie_file) ? 'Replace Selfie Photo' : 'Choose Selfie Photo' }}
                        </span>
                        <input type="file" name="selfie_file" id="selfie_file" class="d-none" accept="image/*" {{ isset($verification) ? '' : 'required' }} onchange="document.getElementById('selfieFileName').textContent = this.files[0].name">
                    </div>
                    @error('selfie_file')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4 opacity-10">

                <!-- SECTION 4: VIDEO VERIFICATION (MAX 15 SECONDS) -->
                <div class="mb-5">
                    <h5 class="fw-extrabold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-record-circle-fill text-primary"></i> Video Upload <span class="badge bg-light text-muted border font-monospace extra-small ms-1">Optional / Max 15s</span>
                    </h5>
                    <p class="text-secondary small mb-4">Upload a short 5–15 second selfie video (MP4, WEBM max 15 seconds / 50MB).</p>

                    <div class="kyc-dropzone" onclick="document.getElementById('video_file').click();">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                            <i class="bi bi-file-earmark-play fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Click or Drag Video File Here</h6>
                        <p class="text-muted extra-small mb-2">Allowed Format: MP4 (Max 15 sec / 50MB)</p>
                        <span class="badge bg-white text-primary border rounded-pill px-3 py-1 small fw-bold" id="videoFileName">
                            {{ isset($verification->video_file) ? 'Replace Video File' : 'Choose Video File' }}
                        </span>
                        <input type="file" name="video_file" id="video_file" class="d-none" accept="video/mp4,video/webm" onchange="document.getElementById('videoFileName').textContent = this.files[0].name">
                    </div>
                    @error('video_file')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4 opacity-10">

                <!-- SECTION 5: AGREEMENT CHECKBOX & SUBMISSION -->
                <div class="mb-4">
                    <div class="form-check p-3 bg-light rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="agreement" id="agreement" value="1" required>
                        <label class="form-check-label fw-bold text-dark small" for="agreement">
                            I confirm that all submitted information and documents are accurate and legally belong to me.
                        </label>
                    </div>
                    @error('agreement')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 pt-3 border-top">
                    <span class="extra-small text-muted">
                        <i class="bi bi-lock-fill text-success me-1"></i> Data stored locally using Laravel Storage security policies.
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
