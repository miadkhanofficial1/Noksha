@extends('layouts.app')

@section('title', 'Admin Resource Approval Panel - Noksha (নকশা)')

@section('content')

<!-- CUSTOM SUPER ADMIN DASHBOARD FIGMA STYLES -->
<style>
    .admin-hero-card {
        background: linear-gradient(135deg, #4C1D95 0%, #6C4CF1 50%, #8B5CF6 100%);
        border-radius: 1.5rem !important;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 45px -10px rgba(76, 29, 149, 0.3) !important;
    }

    .admin-hero-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.2) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: 0.35;
        pointer-events: none;
    }

    /* Stat Cards */
    .admin-stat-card {
        background: #ffffff;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        border-radius: 1.25rem !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.08) !important;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
    }

    .admin-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 35px -8px rgba(108, 76, 241, 0.18) !important;
        border-color: rgba(108, 76, 241, 0.3) !important;
    }

    .admin-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Table Container */
    .admin-table-card {
        background: #ffffff;
        border-radius: 1.5rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 15px 35px -10px rgba(108, 76, 241, 0.1) !important;
        overflow: hidden;
    }

    .admin-preview-thumb {
        width: 58px;
        height: 58px;
        border-radius: 0.75rem;
        object-fit: cover;
        background: #F8F5FF;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Filter Pills */
    .admin-filter-pill {
        border-radius: 999px !important;
        padding: 0.45rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.25s ease;
        text-decoration: none !important;
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
        box-shadow: 0 8px 20px -4px rgba(108, 76, 241, 0.4);
    }
</style>

<!-- ADMIN DASHBOARD HERO SECTION -->
<section class="py-4 py-lg-5" style="background-color: #F8F7FF;">
    <div class="container">
        
        <!-- Success Alert Notification -->
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-lg rounded-4 p-4 mb-4 d-flex align-items-center gap-3 text-dark bg-white">
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Action Processed!</h5>
                    <p class="mb-0 small text-secondary">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- TOP HERO WELCOME BANNER -->
        <div class="card admin-hero-card p-4 p-md-5 text-white mb-4 position-relative">
            <div class="row align-items-center g-4 position-relative" style="z-index: 1;">
                <div class="col-lg-8">
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 small fw-bold mb-3 border border-white border-opacity-25">
                        <i class="bi bi-shield-lock-fill me-1 text-warning"></i> Super Admin Moderation Panel
                    </span>
                    <h2 class="display-6 fw-extrabold text-white mb-2">
                        Resource Approval Center <span class="text-warning">(মডারেশন প্যানেল)</span>
                    </h2>
                    <p class="text-white text-opacity-90 mb-0 small" style="max-width: 620px;">
                        Review creator template submissions, inspect vector previews, verify licensing, and approve or reject marketplace assets.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('home') }}" class="btn btn-light rounded-pill px-4 py-2.5 fw-bold text-primary shadow-sm">
                        <i class="bi bi-shop me-1"></i> Visit Marketplace
                    </a>
                </div>
            </div>
        </div>

        <!-- FOUR OVERVIEW STATISTICS CARDS -->
        <div class="row g-3 g-md-4 mb-5">
            
            <!-- Card 1: Pending Resources -->
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('admin.resources.index', ['status' => 'pending']) }}" class="text-decoration-none">
                    <div class="card admin-stat-card p-3.5 border-start border-warning border-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Pending Review</div>
                                <div class="display-6 fw-extrabold text-warning mb-0">{{ $pendingCount }}</div>
                            </div>
                            <div class="admin-stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-clock-history"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 2: Approved Resources -->
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('admin.resources.index', ['status' => 'approved']) }}" class="text-decoration-none">
                    <div class="card admin-stat-card p-3.5 border-start border-success border-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Approved Assets</div>
                                <div class="display-6 fw-extrabold text-success mb-0">{{ $approvedCount }}</div>
                            </div>
                            <div class="admin-stat-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 3: Rejected Resources -->
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('admin.resources.index', ['status' => 'rejected']) }}" class="text-decoration-none">
                    <div class="card admin-stat-card p-3.5 border-start border-danger border-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Rejected Assets</div>
                                <div class="display-6 fw-extrabold text-danger mb-0">{{ $rejectedCount }}</div>
                            </div>
                            <div class="admin-stat-icon bg-danger bg-opacity-10 text-danger">
                                <i class="bi bi-x-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 4: Total Downloads -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card admin-stat-card p-3.5 border-start border-primary border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Total Downloads</div>
                            <div class="display-6 fw-extrabold text-dark mb-0">{{ number_format($totalDownloads) }}</div>
                        </div>
                        <div class="admin-stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-download"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- SEARCH AND FILTER BAR SECTION -->
        <div class="card p-3.5 border-0 shadow-sm rounded-4 bg-white mb-4">
            <form action="{{ route('admin.resources.index') }}" method="GET" class="row g-3 align-items-center">
                <!-- Status Filter Pills -->
                <div class="col-12 col-lg-7 d-flex flex-wrap align-items-center gap-2">
                    <span class="small fw-bold text-muted me-2"><i class="bi bi-funnel-fill text-primary me-1"></i> Filter Status:</span>
                    <a href="{{ route('admin.resources.index') }}" class="btn admin-filter-pill {{ !request('status') ? 'btn-primary text-white' : 'btn-light border text-dark' }}">
                        All ({{ $pendingCount + $approvedCount + $rejectedCount }})
                    </a>
                    <a href="{{ route('admin.resources.index', ['status' => 'pending']) }}" class="btn admin-filter-pill {{ request('status') === 'pending' ? 'btn-warning text-dark' : 'btn-light border text-dark' }}">
                        Pending ({{ $pendingCount }})
                    </a>
                    <a href="{{ route('admin.resources.index', ['status' => 'approved']) }}" class="btn admin-filter-pill {{ request('status') === 'approved' ? 'btn-success text-white' : 'btn-light border text-dark' }}">
                        Approved ({{ $approvedCount }})
                    </a>
                    <a href="{{ route('admin.resources.index', ['status' => 'rejected']) }}" class="btn admin-filter-pill {{ request('status') === 'rejected' ? 'btn-danger text-white' : 'btn-light border text-dark' }}">
                        Rejected ({{ $rejectedCount }})
                    </a>
                </div>

                <!-- Search Input Field -->
                <div class="col-12 col-lg-5">
                    <div class="input-group">
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <span class="input-group-text bg-light border-end-0 text-muted ps-3">
                            <i class="bi bi-search text-primary"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-light border-start-0 ps-1 rounded-end fs-6" placeholder="Search by title, seller name, or category..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-purple-cta rounded-end px-4 fw-bold">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- RESOURCE MANAGEMENT TABLE -->
        <div class="admin-table-card mb-5">
            <div class="p-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="fw-extrabold text-dark mb-0">Marketplace Submission Queue</h5>
                <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill small fw-semibold">
                    Showing {{ $resources->firstItem() ?? 0 }} - {{ $resources->lastItem() ?? 0 }} of {{ $resources->total() }} Assets
                </span>
            </div>

            @if($resources->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted extra-small text-uppercase tracking-wider">
                            <tr>
                                <th class="ps-4">Preview</th>
                                <th>Resource Title</th>
                                <th>Seller/Author</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Submitted Date</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resources as $resource)
                                <tr>
                                    <!-- Preview Image -->
                                    <td class="ps-4" style="width: 80px;">
                                        @if($resource->preview_image)
                                            <img src="{{ asset('storage/' . $resource->preview_image) }}" class="admin-preview-thumb border" alt="{{ $resource->title }}">
                                        @else
                                            <div class="admin-preview-thumb text-primary fw-bold">
                                                <i class="bi bi-file-earmark-image fs-4"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Title -->
                                    <td>
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 240px;" title="{{ $resource->title }}">
                                            {{ $resource->title }}
                                        </div>
                                        <span class="extra-small text-muted font-monospace"><i class="bi bi-file-earmark-zip me-1"></i>{{ strtoupper($resource->file_type) }} Package</span>
                                    </td>

                                    <!-- Seller -->
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                {{ strtoupper(substr($resource->owner ? $resource->owner->name : 'N', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark small">{{ $resource->owner ? $resource->owner->name : 'Unknown Seller' }}</div>
                                                <div class="extra-small text-muted">@ {{ $resource->owner ? $resource->owner->username : 'seller' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Category -->
                                    <td>
                                        <span class="badge bg-light text-secondary border rounded-pill extra-small fw-semibold">
                                            {{ $resource->category ? $resource->category->name : 'General' }}
                                        </span>
                                    </td>

                                    <!-- Price -->
                                    <td>
                                        @if($resource->is_paid && $resource->price > 0)
                                            <span class="fw-bold text-dark">৳{{ number_format($resource->price, 2) }}</span>
                                        @else
                                            <span class="fw-bold text-success">Free</span>
                                        @endif
                                    </td>

                                    <!-- Uploaded Date -->
                                    <td class="small text-muted font-monospace">
                                        {{ $resource->created_at ? $resource->created_at->format('M d, Y') : 'N/A' }}
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        @if($resource->status === 'approved')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1 fw-bold">
                                                <i class="bi bi-check-circle-fill me-1"></i> Approved
                                            </span>
                                        @elseif($resource->status === 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 rounded-pill px-3 py-1 fw-bold">
                                                <i class="bi bi-clock-history me-1"></i> Pending
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-1 fw-bold">
                                                <i class="bi bi-x-circle-fill me-1"></i> Rejected
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Actions (Preview, Approve, Reject) -->
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-1.5">
                                            <!-- Preview Trigger Button -->
                                            <button type="button" class="btn btn-light border btn-sm rounded-pill px-2.5" data-bs-toggle="modal" data-bs-target="#previewModal{{ $resource->id }}" title="Preview asset details">
                                                <i class="bi bi-eye-fill text-primary"></i> Preview
                                            </button>

                                            <!-- Approve Form Button -->
                                            @if($resource->status !== 'approved')
                                                <form action="{{ route('admin.resources.approve', $resource->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-2.5 fw-bold" title="Approve asset">
                                                        <i class="bi bi-check-lg"></i> Approve
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Reject Form Button -->
                                            @if($resource->status !== 'rejected')
                                                <form action="{{ route('admin.resources.reject', $resource->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-2.5 fw-bold" title="Reject asset">
                                                        <i class="bi bi-x-lg"></i> Reject
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <!-- ASSET PREVIEW MODAL -->
                                        <div class="modal fade text-start" id="previewModal{{ $resource->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                                                    
                                                    <!-- Modal Header -->
                                                    <div class="modal-header border-bottom p-4 bg-light">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="badge bg-primary rounded-pill px-3 py-1.5 fw-bold small">
                                                                {{ $resource->category ? $resource->category->name : 'Asset Preview' }}
                                                            </span>
                                                            <h5 class="modal-title fw-bold text-dark mb-0">{{ $resource->title }}</h5>
                                                        </div>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <!-- Modal Body -->
                                                    <div class="modal-body p-4">
                                                        <div class="row g-4">
                                                            <!-- Cover Image Preview -->
                                                            <div class="col-12 col-md-6">
                                                                @if($resource->preview_image)
                                                                    <img src="{{ asset('storage/' . $resource->preview_image) }}" class="img-fluid rounded-3 border shadow-sm w-100" style="max-height: 260px; object-fit: cover;" alt="{{ $resource->title }}">
                                                                @else
                                                                    <div class="p-5 bg-primary bg-opacity-10 text-primary rounded-3 border text-center">
                                                                        <i class="bi bi-file-earmark-image fs-1 mb-2 d-block"></i>
                                                                        <span class="fw-bold">No Cover Image Uploaded</span>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <!-- Details Breakdown -->
                                                            <div class="col-12 col-md-6">
                                                                <div class="mb-3">
                                                                    <span class="text-muted extra-small fw-bold text-uppercase d-block mb-1">Author / Seller</span>
                                                                    <div class="fw-bold text-dark fs-6">{{ $resource->owner ? $resource->owner->name : 'Unknown' }}</div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <span class="text-muted extra-small fw-bold text-uppercase d-block mb-1">Price</span>
                                                                    <div class="fw-extrabold text-dark fs-5">
                                                                        {{ $resource->is_paid ? '৳' . number_format($resource->price, 2) : 'Free' }}
                                                                    </div>
                                                                </div>

                                                                @if(!empty($resource->tags))
                                                                    <div class="mb-3">
                                                                        <span class="text-muted extra-small fw-bold text-uppercase d-block mb-1">AI Tags</span>
                                                                        <div class="d-flex flex-wrap gap-1">
                                                                            @foreach((array)$resource->tags as $tag)
                                                                                <span class="badge bg-light text-primary border rounded-pill px-2.5 py-1 extra-small">#{{ trim($tag) }}</span>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                @if($resource->demo_link)
                                                                    <div class="mb-3">
                                                                        <span class="text-muted extra-small fw-bold text-uppercase d-block mb-1">Live Demo Link</span>
                                                                        <a href="{{ $resource->demo_link }}" target="_blank" class="small text-primary fw-semibold text-truncate d-block">
                                                                            <i class="bi bi-box-arrow-up-right me-1"></i> {{ $resource->demo_link }}
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <!-- Description Text -->
                                                            <div class="col-12">
                                                                <span class="text-muted extra-small fw-bold text-uppercase d-block mb-1">Asset Description</span>
                                                                <div class="p-3 bg-light rounded-3 border text-secondary small lh-lg">
                                                                    {{ $resource->description }}
                                                                </div>
                                                            </div>

                                                            @if($resource->requirements)
                                                                <div class="col-12">
                                                                    <span class="text-muted extra-small fw-bold text-uppercase d-block mb-1">Requirements</span>
                                                                    <div class="p-3 bg-light rounded-3 border text-secondary small">
                                                                        {{ $resource->requirements }}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Modal Footer Action Controls -->
                                                    <div class="modal-footer border-top bg-light p-3 d-flex justify-content-between">
                                                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                                                        <div class="d-flex gap-2">
                                                            @if($resource->status !== 'rejected')
                                                                <form action="{{ route('admin.resources.reject', $resource->id) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-outline-danger rounded-pill px-4 fw-bold">Reject Asset</button>
                                                                </form>
                                                            @endif

                                                            @if($resource->status !== 'approved')
                                                                <form action="{{ route('admin.resources.approve', $resource->id) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Approve & Publish</button>
                                                                </form>
                                                            @endif
                                                        </div>
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

                <!-- PAGINATION CONTROLS -->
                <div class="p-4 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="small text-muted">
                        Showing {{ $resources->firstItem() ?? 0 }} to {{ $resources->lastItem() ?? 0 }} of {{ $resources->total() }} entries
                    </div>
                    <div>
                        {{ $resources->links() }}
                    </div>
                </div>
            @else
                <!-- EMPTY STATE WHEN ZERO SUBMISSIONS MATCH -->
                <div class="p-5 text-center">
                    <div class="p-4 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-inbox fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">No Matching Resources Found</h4>
                    <p class="text-secondary small mb-3" style="max-width: 450px; margin: 0 auto;">
                        There are currently no creator assets matching the selected status or search query.
                    </p>
                    <a href="{{ route('admin.resources.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold btn-sm">
                        Reset Filters
                    </a>
                </div>
            @endif
        </div>

    </div>
</section>

@endsection
