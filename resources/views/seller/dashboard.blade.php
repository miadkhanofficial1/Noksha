@extends('layouts.app')

@section('title', 'Seller Dashboard - Noksha (নকশা)')

@section('content')

<!-- CUSTOM SELLER DASHBOARD FIGMA STYLES -->
<style>
    .dashboard-hero-card {
        background: linear-gradient(135deg, #6C4CF1 0%, #8B5CF6 50%, #9F7AEA 100%);
        border-radius: 1.5rem !important;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 45px -10px rgba(108, 76, 241, 0.25) !important;
    }

    .dashboard-hero-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.2) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: 0.35;
        pointer-events: none;
    }

    /* Stat Cards */
    .stat-card-glass {
        background: #ffffff;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        border-radius: 1.25rem !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.08) !important;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
    }

    .stat-card-glass:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 35px -8px rgba(108, 76, 241, 0.18) !important;
        border-color: rgba(108, 76, 241, 0.3) !important;
    }

    .stat-icon-wrapper {
        width: 54px;
        height: 54px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Table Glass Container */
    .table-container-glass {
        background: #ffffff;
        border-radius: 1.5rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 15px 35px -10px rgba(108, 76, 241, 0.1) !important;
        overflow: hidden;
    }

    .table-preview-thumb {
        width: 54px;
        height: 54px;
        border-radius: 0.75rem;
        object-fit: cover;
        background: #F8F5FF;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Quick Action Cards */
    .quick-action-card {
        background: #ffffff;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        border-radius: 1.25rem !important;
        padding: 1.25rem;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none !important;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .quick-action-card:hover {
        transform: translateY(-3px);
        border-color: #6C4CF1 !important;
        box-shadow: 0 12px 25px -5px rgba(108, 76, 241, 0.18) !important;
        background: rgba(108, 76, 241, 0.02);
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
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 14px 28px -4px rgba(108, 76, 241, 0.45);
        color: #ffffff !important;
    }
</style>

<!-- DASHBOARD TOP HEADER SECTION -->
<section class="py-4 py-lg-5" style="background-color: #F8F7FF;">
    <div class="container">
        
        <!-- Flash Success Notification -->
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-lg rounded-4 p-4 mb-4 d-flex align-items-center gap-3 text-dark bg-white">
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Success!</h5>
                    <p class="mb-0 small text-secondary">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- TOP WELCOME CARD (PURPLE GLASSMORPHISM) -->
        <div class="card dashboard-hero-card p-4 p-md-5 text-white mb-4 position-relative">
            <div class="row align-items-center g-4 position-relative" style="z-index: 1;">
                <div class="col-lg-8">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 small fw-bold border border-white border-opacity-25">
                            <i class="bi bi-patch-check-fill me-1 text-warning"></i> Pro Verified Author
                        </span>
                        <span class="badge bg-success bg-opacity-90 text-white rounded-pill px-3 py-1.5 small fw-bold">
                            <i class="bi bi-shield-check me-1"></i> 99.4% Trust Score
                        </span>
                    </div>

                    <h2 class="display-6 fw-extrabold text-white mb-2">
                        Welcome back, {{ auth()->user()->name }}! 👋
                    </h2>
                    <p class="text-white text-opacity-90 mb-0 small">
                        <i class="bi bi-calendar3 me-1"></i> Member since {{ auth()->user()->created_at->format('F Y') }} | Managing Noksha Creator Portfolio
                    </p>
                </div>

                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('resource.create') }}" class="btn btn-warning btn-lg rounded-pill px-4 py-3 fw-bold shadow-sm text-dark">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> Upload New Asset
                    </a>
                </div>
            </div>
        </div>

        <!-- VERIFICATION PROGRESS CARD -->
        <div class="card p-4 rounded-4 border-0 shadow-sm bg-white mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    @if(isset($verification) && $verification->status === 'approved')
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle fs-3">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div>
                            <span class="badge bg-success text-white rounded-pill px-3 py-1 fw-bold mb-1">
                                <i class="bi bi-check-circle-fill me-1"></i> Identity Verified
                            </span>
                            <h5 class="fw-bold text-dark mb-0">Pro Verified Author Status Active</h5>
                            <p class="text-secondary small mb-0">Your identity documents have been approved by compliance moderation.</p>
                        </div>
                    @elseif(isset($verification) && $verification->status === 'pending')
                        <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-circle fs-3">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold mb-1">
                                <i class="bi bi-hourglass-split me-1"></i> Verification Pending Review
                            </span>
                            <h5 class="fw-bold text-dark mb-0">KYC Verification Under Review</h5>
                            <p class="text-secondary small mb-0">Submitted on {{ $verification->created_at->format('M d, Y') }}. Review takes up to 24 hours.</p>
                        </div>
                    @elseif(isset($verification) && $verification->status === 'rejected')
                        <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-circle fs-3">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div>
                            <span class="badge bg-danger text-white rounded-pill px-3 py-1 fw-bold mb-1">
                                <i class="bi bi-x-circle-fill me-1"></i> Verification Declined
                            </span>
                            <h5 class="fw-bold text-dark mb-0">Identity Resubmission Required</h5>
                            <p class="text-secondary small mb-0">Please upload valid government ID documents and face selfie.</p>
                        </div>
                    @else
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle fs-3">
                            <i class="bi bi-shield-exclamation"></i>
                        </div>
                        <div>
                            <span class="badge bg-secondary text-white rounded-pill px-3 py-1 fw-bold mb-1">
                                <i class="bi bi-info-circle-fill me-1"></i> Unverified Seller
                            </span>
                            <h5 class="fw-bold text-dark mb-0">Complete Seller Verification</h5>
                            <p class="text-secondary small mb-0">Verify your government ID to earn trust badges and full seller privileges.</p>
                        </div>
                    @endif
                </div>

                <div>
                    @if(!isset($verification) || $verification->status === 'rejected')
                        <a href="{{ route('seller.verification.create') }}" class="btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold btn-sm">
                            <i class="bi bi-shield-check me-1"></i> Verify Now
                        </a>
                    @else
                        <a href="{{ route('seller.verification.create') }}" class="btn btn-outline-purple rounded-pill px-4 py-2.5 fw-bold btn-sm">
                            <i class="bi bi-eye-fill me-1"></i> View Status
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- FOUR ANIMATED STATISTICS CARDS -->
        <div class="row g-3 g-md-4 mb-5">
            
            <!-- Card 1: Total Resources -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card stat-card-glass p-3.5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Total Assets</div>
                            <div class="display-6 fw-extrabold text-dark mb-0">{{ $totalResources }}</div>
                        </div>
                        <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-layers-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Downloads -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card stat-card-glass p-3.5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Total Downloads</div>
                            <div class="display-6 fw-extrabold text-dark mb-0">{{ number_format($totalDownloads) }}</div>
                        </div>
                        <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                            <i class="bi bi-download"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Total Views -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card stat-card-glass p-3.5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Total Views</div>
                            <div class="display-6 fw-extrabold text-dark mb-0">{{ number_format($totalViews) }}</div>
                        </div>
                        <div class="stat-icon-wrapper bg-info bg-opacity-10 text-info">
                            <i class="bi bi-eye-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Pending Approval -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card stat-card-glass p-3.5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Pending Review</div>
                            <div class="display-6 fw-extrabold text-warning mb-0">{{ $pendingApproval }}</div>
                        </div>
                        <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- QUICK ACTION PANEL -->
        <div class="mb-5">
            <h5 class="fw-extrabold text-dark mb-3"><i class="bi bi-lightning-charge-fill text-primary me-1"></i> Quick Creator Actions</h5>
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="{{ route('resource.create') }}" class="quick-action-card">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="bi bi-cloud-arrow-up-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Upload Asset</h6>
                            <span class="extra-small text-muted">Publish new item</span>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="{{ route('seller.demo') }}" class="quick-action-card">
                        <div class="p-3 bg-info bg-opacity-10 text-info rounded-circle">
                            <i class="bi bi-person-badge fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Public Profile</h6>
                            <span class="extra-small text-muted">View seller storefront</span>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="javascript:void(0)" onclick="alert('Profile Settings Modal')" class="quick-action-card">
                        <div class="p-3 bg-secondary bg-opacity-10 text-secondary rounded-circle">
                            <i class="bi bi-gear-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Edit Profile</h6>
                            <span class="extra-small text-muted">Account settings</span>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="javascript:void(0)" onclick="alert('Sales & Earnings Analytics Panel')" class="quick-action-card">
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                            <i class="bi bi-graph-up-arrow fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Analytics</h6>
                            <span class="extra-small text-muted">Earnings & metrics</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- MY RESOURCES TABLE & MANAGEMENT SECTION -->
        <div class="table-container-glass mb-5">
            <div class="p-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h5 class="fw-extrabold text-dark mb-0">My Uploaded Resources</h5>
                    <span class="small text-muted">Manage your uploaded designs, status, and pricing</span>
                </div>
                <a href="{{ route('resource.create') }}" class="btn btn-purple-cta btn-sm rounded-pill px-3.5 py-2">
                    <i class="bi bi-plus-lg me-1"></i> Add New Asset
                </a>
            </div>

            @if($resources->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted extra-small text-uppercase tracking-wider">
                            <tr>
                                <th class="ps-4">Preview</th>
                                <th>Title & Category</th>
                                <th>Status</th>
                                <th>Price</th>
                                <th>Views</th>
                                <th>Downloads</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resources as $resource)
                                <tr>
                                    <!-- Preview Image -->
                                    <td class="ps-4" style="width: 80px;">
                                        @if($resource->preview_image)
                                            <img src="{{ asset('storage/' . $resource->preview_image) }}" class="table-preview-thumb border" alt="{{ $resource->title }}">
                                        @else
                                            <div class="table-preview-thumb text-primary fw-bold">
                                                <i class="bi bi-file-earmark-image fs-4"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Title & Category -->
                                    <td>
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 260px;" title="{{ $resource->title }}">
                                            {{ $resource->title }}
                                        </div>
                                        <span class="badge bg-light text-secondary border rounded-pill extra-small">
                                            {{ $resource->category ? $resource->category->name : 'Uncategorized' }}
                                        </span>
                                    </td>

                                    <!-- Status Badge -->
                                    <td>
                                        @if($resource->status === 'approved')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1 fw-bold">
                                                <i class="bi bi-check-circle-fill me-1"></i> Approved
                                            </span>
                                        @elseif($resource->status === 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 rounded-pill px-3 py-1 fw-bold">
                                                <i class="bi bi-clock-history me-1"></i> Pending Review
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-1 fw-bold">
                                                <i class="bi bi-x-circle-fill me-1"></i> Rejected
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Price -->
                                    <td>
                                        @if($resource->is_paid && $resource->price > 0)
                                            <span class="fw-bold text-dark">৳{{ number_format($resource->price, 2) }}</span>
                                        @else
                                            <span class="fw-bold text-success">Free</span>
                                        @endif
                                    </td>

                                    <!-- Views -->
                                    <td class="font-monospace text-muted small">
                                        <i class="bi bi-eye me-1"></i> {{ number_format($resource->views) }}
                                    </td>

                                    <!-- Downloads -->
                                    <td class="font-monospace text-muted small">
                                        <i class="bi bi-download me-1 text-primary"></i> {{ number_format($resource->downloads) }}
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-1">
                                            <!-- View -->
                                            <a href="{{ route('resource.demo') }}" class="btn btn-light border btn-sm rounded-circle" title="View details">
                                                <i class="bi bi-eye-fill text-primary"></i>
                                            </a>

                                            <!-- Edit Placeholder -->
                                            <button type="button" onclick="alert('Edit Asset Modal Placeholder for {{ $resource->title }}')" class="btn btn-light border btn-sm rounded-circle" title="Edit asset">
                                                <i class="bi bi-pencil-fill text-secondary"></i>
                                            </button>

                                            <!-- Delete Confirmation Trigger -->
                                            <button type="button" class="btn btn-light border btn-sm rounded-circle text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $resource->id }}" title="Delete asset">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>

                                        <!-- DELETE CONFIRMATION MODAL -->
                                        <div class="modal fade text-start" id="deleteModal{{ $resource->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-4 border-0 shadow-lg">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Confirm Deletion</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body py-3">
                                                        <p class="text-secondary mb-0">
                                                            Are you sure you want to delete <strong>"{{ $resource->title }}"</strong>? This action cannot be undone.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer border-0 pt-0">
                                                        <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('seller.resource.destroy', $resource->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Delete Asset</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- EMPTY STATE WHEN NO RESOURCES EXIST -->
                <div class="p-5 text-center">
                    <div class="p-4 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-folder-plus fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">No Assets Uploaded Yet</h4>
                    <p class="text-secondary small mb-4" style="max-width: 450px; margin: 0 auto;">
                        Start publishing your Figma UI kits, vectors, and design templates to earn on Noksha Marketplace.
                    </p>
                    <a href="{{ route('resource.create') }}" class="btn btn-purple-cta rounded-pill px-4 py-3 fw-bold">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> Upload Your First Asset
                    </a>
                </div>
            @endif
        </div>

    </div>
</section>

@endsection
