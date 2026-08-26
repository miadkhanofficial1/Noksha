@extends('layouts.app')

@section('title', 'Verification Details - ' . $verification->full_name . ' - Noksha (নকশা)')

@section('content')

<!-- CUSTOM GLASSMORPHISM DETAILS STYLES -->
<style>
    .verification-details-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(108, 76, 241, 0.2) !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 20px 45px -10px rgba(108, 76, 241, 0.15) !important;
    }

    .preview-media-box {
        background: #F8F5FF;
        border: 1px solid rgba(108, 76, 241, 0.15);
        border-radius: 1.25rem;
        overflow: hidden;
        position: relative;
    }

    .preview-media-box img, .preview-media-box video {
        width: 100%;
        max-height: 380px;
        object-fit: contain;
        background: #000000;
    }

    .btn-purple-cta {
        background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #5A3DE0 0%, #4327C6 100%);
        transform: translateY(-2px);
    }
</style>

<!-- VERIFICATION DETAILS HEADER -->
<section class="py-4 py-lg-5 text-white" style="background: linear-gradient(135deg, #4C1D95 0%, #6C4CF1 50%, #8B5CF6 100%);">
    <div class="container py-2">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 small fw-bold border border-white border-opacity-25">
                        <i class="bi bi-shield-check-fill me-1 text-warning"></i> KYC Compliance Details
                    </span>
                    @if($verification->status === 'approved')
                        <span class="badge bg-success text-white rounded-pill px-3 py-1.5 small fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> Status: Approved
                        </span>
                    @elseif($verification->status === 'pending')
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 small fw-bold">
                            <i class="bi bi-clock-history me-1"></i> Status: Pending Review
                        </span>
                    @else
                        <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 small fw-bold">
                            <i class="bi bi-x-circle-fill me-1"></i> Status: Rejected
                        </span>
                    @endif
                </div>

                <h1 class="display-6 fw-extrabold text-white mb-2">
                    {{ $verification->full_name }}
                </h1>
                <p class="text-white text-opacity-90 mb-0 small">
                    <i class="bi bi-person-fill me-1"></i> Registered Account: {{ $verification->user ? $verification->user->email : 'N/A' }} | 
                    <i class="bi bi-geo-alt-fill me-1 text-warning"></i> {{ $verification->country }}
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('admin.verifications.index') }}" class="btn btn-light rounded-pill px-4 py-2.5 fw-bold text-primary shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Review Queue
                </a>
            </div>
        </div>
    </div>
</section>

<!-- MAIN DETAILS CONTENT SECTION -->
<section class="py-5" style="background-color: #F8F7FF;">
    <div class="container py-2" style="max-width: 1100px;">
        
        <!-- Flash Alert -->
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-lg rounded-4 p-4 mb-4 d-flex align-items-center gap-3 text-dark bg-white">
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Status Updated!</h5>
                    <p class="mb-0 small text-secondary">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="row g-4">
            
            <!-- LEFT COLUMN: PERSONAL METADATA & ACTIONS -->
            <div class="col-12 col-lg-5">
                <div class="card verification-details-card p-4 mb-4">
                    <h5 class="fw-extrabold text-dark mb-4 pb-2 border-bottom">
                        <i class="bi bi-person-vcard-fill text-primary me-2"></i> Identity Details
                    </h5>

                    <div class="mb-3">
                        <span class="text-muted extra-small fw-bold text-uppercase d-block mb-1">Full Legal Name</span>
                        <div class="fw-bold text-dark fs-6">{{ $verification->full_name }}</div>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted extra-small fw-bold text-uppercase d-block mb-1">Date of Birth</span>
                        <div class="fw-bold text-dark fs-6">
                            {{ $verification->date_of_birth ? $verification->date_of_birth->format('F d, Y') : 'N/A' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted extra-small fw-bold text-uppercase d-block mb-1">Country of Residence</span>
                        <div class="fw-bold text-dark fs-6">{{ $verification->country }}</div>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted extra-small fw-bold text-uppercase d-block mb-1">Government ID Type</span>
                        <span class="badge bg-light text-primary border rounded-pill px-3 py-1.5 fw-bold text-uppercase">
                            {{ $verification->document_type ?? 'NID' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted extra-small fw-bold text-uppercase d-block mb-1">Submission Timestamp</span>
                        <div class="small text-secondary font-monospace">
                            {{ $verification->submitted_at ? $verification->submitted_at->format('M d, Y h:i A') : $verification->created_at->format('M d, Y h:i A') }}
                        </div>
                    </div>

                    @if($verification->reviewed_at)
                        <div class="mb-3">
                            <span class="text-muted extra-small fw-bold text-uppercase d-block mb-1">Reviewed Timestamp</span>
                            <div class="small text-secondary font-monospace">
                                {{ $verification->reviewed_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                    @endif

                    @if($verification->admin_note)
                        <div class="mb-3">
                            <span class="text-muted extra-small fw-bold text-uppercase d-block mb-1">Admin Rejection Note</span>
                            <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-3 small">
                                {{ $verification->admin_note }}
                            </div>
                        </div>
                    @endif

                    <hr class="my-4 opacity-10">

                    <!-- MODERATION ACTION CONTROL PANEL -->
                    <h6 class="fw-extrabold text-dark mb-3"><i class="bi bi-shield-slash text-primary me-1"></i> Compliance Decisions</h6>

                    <div class="d-flex flex-column gap-2">
                        @if($verification->status !== 'approved')
                            <form action="{{ route('admin.verifications.approve', $verification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg rounded-pill fw-bold w-100 py-2.5">
                                    <i class="bi bi-patch-check-fill me-2"></i> Approve & Verify Seller
                                </button>
                            </form>
                        @endif

                        @if($verification->status !== 'rejected')
                            <button type="button" class="btn btn-outline-danger btn-lg rounded-pill fw-bold w-100 py-2.5" data-bs-toggle="modal" data-bs-target="#showRejectModal">
                                <i class="bi bi-x-circle-fill me-2"></i> Reject Application
                            </button>
                        @endif
                    </div>

                </div>
            </div>

            <!-- RIGHT COLUMN: DOCUMENT, SELFIE & VIDEO PREVIEWS -->
            <div class="col-12 col-lg-7">
                <div class="card verification-details-card p-4">
                    <h5 class="fw-extrabold text-dark mb-4 pb-2 border-bottom">
                        <i class="bi bi-images text-primary me-2"></i> KYC Document Media Previews
                    </h5>

                    <!-- 1. Government ID Document Preview -->
                    <div class="mb-4">
                        <label class="fw-bold text-dark mb-2 d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-file-earmark-person text-primary me-1"></i> Government ID Document</span>
                            @if($verification->document_file)
                                <a href="{{ asset('storage/' . $verification->document_file) }}" target="_blank" class="extra-small text-primary fw-bold">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Open Fullscreen / Download
                                </a>
                            @endif
                        </label>
                        <div class="preview-media-box p-2 text-center">
                            @if($verification->document_file)
                                @if(str_ends_with(strtolower($verification->document_file), '.pdf'))
                                    <div class="p-4 bg-light text-center">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger display-4 mb-2 d-block"></i>
                                        <span class="fw-bold text-dark d-block mb-2">PDF Document Uploaded</span>
                                        <a href="{{ asset('storage/' . $verification->document_file) }}" target="_blank" class="btn btn-primary btn-sm rounded-pill px-3">
                                            View PDF Document
                                        </a>
                                    </div>
                                @else
                                    <img src="{{ asset('storage/' . $verification->document_file) }}" alt="Government ID" class="img-fluid rounded-3">
                                @endif
                            @else
                                <div class="p-4 text-muted small">No document file uploaded.</div>
                            @endif
                        </div>
                    </div>

                    <!-- 2. Face Selfie Photo Preview -->
                    <div class="mb-4">
                        <label class="fw-bold text-dark mb-2 d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-camera-fill text-primary me-1"></i> Face Selfie Photo</span>
                            @if($verification->selfie_file)
                                <a href="{{ asset('storage/' . $verification->selfie_file) }}" target="_blank" class="extra-small text-primary fw-bold">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> View Full Image
                                </a>
                            @endif
                        </label>
                        <div class="preview-media-box p-2 text-center">
                            @if($verification->selfie_file)
                                <img src="{{ asset('storage/' . $verification->selfie_file) }}" alt="Selfie Photo" class="img-fluid rounded-3">
                            @else
                                <div class="p-4 text-muted small">No selfie photo uploaded.</div>
                            @endif
                        </div>
                    </div>

                    <!-- 3. Short Video Verification Preview -->
                    <div class="mb-3">
                        <label class="fw-bold text-dark mb-2 d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-record-circle-fill text-primary me-1"></i> Video Verification (Max 15s)</span>
                            @if($verification->video_file)
                                <a href="{{ asset('storage/' . $verification->video_file) }}" download class="extra-small text-primary fw-bold">
                                    <i class="bi bi-download me-1"></i> Download Video
                                </a>
                            @endif
                        </label>
                        <div class="preview-media-box p-2 text-center">
                            @if($verification->video_file)
                                <video controls class="w-100 rounded-3">
                                    <source src="{{ asset('storage/' . $verification->video_file) }}" type="video/mp4">
                                    Your browser does not support HTML5 video playback.
                                </video>
                            @else
                                <div class="p-4 text-muted small">No video clip uploaded.</div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section>

<!-- REJECT MODAL FOR SHOW VIEW -->
<div class="modal fade text-start" id="showRejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="{{ route('admin.verifications.reject', $verification->id) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Reject Verification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <p class="text-secondary small mb-3">Provide a clear reason for declining seller identity application:</p>
                    <textarea name="admin_note" class="form-control rounded-3 border fs-6" rows="3" placeholder="e.g. Document photo is blurry or full name does not match government record." required>{{ $verification->admin_note }}</textarea>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Reject Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
