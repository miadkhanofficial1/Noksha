@extends('layouts.app')

@section('title', 'Contributor Dashboard - Noksha')

@section('content')

<!-- CUSTOM CONTRIBUTOR STYLES -->
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
        width: 52px;
        height: 52px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Table Glass Container */
    .table-container-glass {
        background: #ffffff;
        border-radius: 1.25rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 30px -10px rgba(108, 76, 241, 0.08) !important;
        overflow: hidden;
    }

    .table-preview-thumb {
        width: 52px;
        height: 52px;
        border-radius: 0.75rem;
        object-fit: cover;
        background: #F8F5FF;
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

    /* Tab Panes */
    .contributor-tab-pane {
        display: none;
        animation: fadeInTab 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .contributor-tab-pane.active {
        display: block;
    }

    @keyframes fadeInTab {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    html.dark .stat-card-glass,
    html.dark .table-container-glass,
    html.dark .card-figma {
        background: #1E293B !important;
        border-color: #334155 !important;
    }

    html.dark .text-dark {
        color: #F8FAFC !important;
    }

    html.dark .text-secondary,
    html.dark .text-muted {
        color: #94A3B8 !important;
    }

    html.dark .bg-light {
        background-color: #0F172A !important;
    }
</style>

<!-- CONTRIBUTOR DASHBOARD WRAPPER -->
<section class="py-4 py-lg-5" style="background-color: #F8F7FF; min-height: 85vh;">
    <div class="container">
        

        <div class="row g-4">
            <!-- LEFT COLUMN: CONTRIBUTOR SIDEBAR NAVIGATION -->
            <div class="col-12 col-lg-3">
                @include('seller.partials.sidebar')
            </div>

            <!-- RIGHT COLUMN: MAIN CONTENT & TABS -->
            <div class="col-12 col-lg-9">

                <!-- ========================================== -->
                <!-- TAB 1: DASHBOARD OVERVIEW -->
                <!-- ========================================== -->
                <div class="contributor-tab-pane active" id="tab-dashboard">
                    
                    <!-- HERO WELCOME BANNER -->
                    <div class="card dashboard-hero-card p-4 p-md-5 text-white mb-4 position-relative">
                        <div class="row align-items-center g-4 position-relative" style="z-index: 1;">
                            <div class="col-lg-8">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                    @if(isset($verification) && $verification->status === 'approved')
                                        <span class="badge bg-success bg-opacity-90 text-white rounded-pill px-3 py-1.5 small fw-bold">
                                            <i class="bi bi-patch-check-fill me-1 text-warning"></i> Verified Pro Author
                                        </span>
                                    @elseif(isset($verification) && $verification->status === 'pending')
                                        <span class="badge bg-warning bg-opacity-90 text-dark rounded-pill px-3 py-1.5 small fw-bold">
                                            <i class="bi bi-clock-history me-1"></i> Verification Pending
                                        </span>
                                    @else
                                        <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 small fw-bold border border-white border-opacity-25">
                                            <i class="bi bi-shield-exclamation me-1"></i> Unverified Contributor
                                        </span>
                                    @endif
                                    <span class="badge bg-success bg-opacity-90 text-white rounded-pill px-3 py-1.5 small fw-bold">
                                        <i class="bi bi-star-fill text-warning me-1"></i> {{ $avgRating }} Rating
                                    </span>
                                </div>

                                <h2 class="display-6 fw-extrabold text-white mb-2">
                                    Welcome, {{ auth()->user()->name }}! 👋
                                </h2>
                                <p class="text-white text-opacity-90 mb-0 small" style="max-width: 580px;">
                                    Track your design portfolio, total sales, royalty earnings, and customer downloads in Noksha Creator Studio.
                                </p>
                            </div>

                            <div class="col-lg-4 text-lg-end">
                                <a href="{{ route('dashboard', ['tab' => 'upload']) }}" class="btn btn-warning btn-lg rounded-pill px-4 py-3 fw-bold shadow-sm text-dark">
                                    <i class="bi bi-cloud-arrow-up-fill me-2"></i> Upload Asset
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 4 PRIMARY METRIC STATS CARDS -->
                    <div class="row g-3 g-md-4 mb-4">
                        <!-- Stat 1: Total Uploads (Total Uploads) -->
                        <div class="col-6 col-md-3">
                            <div class="card stat-card-glass p-3.5">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Total Uploads</div>
                                        <div class="fs-3 fw-extrabold text-dark mb-0">{{ $totalResources }}</div>
                                        <span class="extra-small text-muted">Design Files</span>
                                    </div>
                                    <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                                        <i class="bi bi-layers-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stat 2: Total Sales -->
                        <div class="col-6 col-md-3">
                            <div class="card stat-card-glass p-3.5">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Total Sales</div>
                                        <div class="fs-3 fw-extrabold text-success mb-0">{{ $totalSales }}</div>
                                        <span class="extra-small text-muted">Completed Orders</span>
                                    </div>
                                    <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                                        <i class="bi bi-cart-check-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stat 3: Total Earnings / Wallet -->
                        <div class="col-6 col-md-3">
                            <div class="card stat-card-glass p-3.5">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Earnings & Wallet</div>
                                        <div class="fs-3 fw-extrabold text-primary mb-0">৳{{ number_format($totalEarnings, 2) }}</div>
                                        <span class="extra-small text-success fw-bold">50% Creator Share</span>
                                    </div>
                                    <div class="stat-icon-wrapper bg-info bg-opacity-10 text-info">
                                        <i class="bi bi-wallet2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stat 4: Total Downloads -->
                        <div class="col-6 col-md-3">
                            <div class="card stat-card-glass p-3.5">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Total Downloads</div>
                                        <div class="fs-3 fw-extrabold text-purple mb-0" style="color: #6C4CF1;">{{ number_format($totalDownloads) }}</div>
                                        <span class="extra-small text-muted">User Downloads</span>
                                    </div>
                                    <div class="stat-icon-wrapper bg-purple bg-opacity-10 text-purple" style="background: rgba(108,76,241,0.1); color: #6C4CF1;">
                                        <i class="bi bi-cloud-arrow-down-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECONDARY STATS BAR (Pending, Views, Rating) -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card p-3 rounded-4 border-0 shadow-sm bg-white d-flex flex-row align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted extra-small fw-bold">Pending Review</span>
                                    <h5 class="fw-bold text-warning mb-0">{{ $pendingApproval }} Assets</h5>
                                </div>
                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-circle p-2.5 fs-5">
                                    <i class="bi bi-hourglass-split"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card p-3 rounded-4 border-0 shadow-sm bg-white d-flex flex-row align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted extra-small fw-bold">Total Views</span>
                                    <h5 class="fw-bold text-dark mb-0">{{ number_format($totalViews) }} Views</h5>
                                </div>
                                <span class="badge bg-info bg-opacity-10 text-info rounded-circle p-2.5 fs-5">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card p-3 rounded-4 border-0 shadow-sm bg-white d-flex flex-row align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted extra-small fw-bold">Customer Reviews & Rating</span>
                                    <h5 class="fw-bold text-dark mb-0">{{ $avgRating }} ★ ({{ $totalReviews }} Reviews)</h5>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-circle p-2.5 fs-5">
                                    <i class="bi bi-star-fill text-warning"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- RECENT DESIGNS PREVIEW TABLE -->
                    <div class="table-container-glass mb-4">
                        <div class="p-3.5 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h6 class="fw-extrabold text-dark mb-0">
                                    <i class="bi bi-collection-fill text-primary me-1"></i> Recent Uploaded Designs
                                </h6>
                                <span class="extra-small text-muted">Latest submitted creative assets</span>
                            </div>
                            <a href="#designs" onclick="switchContributorTab('tab-designs')" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 fw-bold extra-small">
                                View All Designs ({{ $totalResources }})
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted extra-small text-uppercase tracking-wider">
                                    <tr>
                                        <th class="ps-3">Preview</th>
                                        <th>Title & Category</th>
                                        <th>Type & Price</th>
                                        <th>Downloads</th>
                                        <th>Status</th>
                                        <th class="text-end pe-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($resources->take(5) as $res)
                                        <tr>
                                            <td class="ps-3" style="width: 60px;">
                                                <img src="{{ asset('storage/' . $res->preview_image) }}" class="table-preview-thumb border" alt="{{ $res->title }}" onerror="this.src='{{ asset('images/logo.png') }}'">
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark text-truncate" style="max-width: 250px;">
                                                    <a href="{{ route('resource.show', $res->slug ?? $res->id) }}" class="text-dark text-decoration-none hover-primary">
                                                        {{ $res->title }}
                                                    </a>
                                                </div>
                                                <span class="badge bg-light text-muted extra-small">{{ $res->category?->name ?? 'Uncategorized' }}</span>
                                            </td>
                                            <td>
                                                @if($res->is_paid)
                                                    <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">৳{{ number_format($res->price, 2) }}</span>
                                                @else
                                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold">Free</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold text-muted small"><i class="bi bi-download me-1 text-primary"></i> {{ $res->downloads }}</span>
                                            </td>
                                            <td>
                                                @if($res->status === 'approved')
                                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold rounded-pill px-2.5 py-1">Active</span>
                                                @elseif($res->status === 'pending')
                                                    <span class="badge bg-warning bg-opacity-10 text-warning fw-bold rounded-pill px-2.5 py-1">Pending</span>
                                                @elseif($res->status === 'inactive')
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold rounded-pill px-2.5 py-1">Inactive</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger fw-bold rounded-pill px-2.5 py-1">Rejected</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-3">
                                                <a href="{{ route('resource.show', $res->slug ?? $res->id) }}" class="btn btn-light btn-sm rounded-pill px-2.5 py-1 text-primary me-1" title="View Preview">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="bi bi-cloud-arrow-up display-6 d-block text-secondary opacity-50 mb-2"></i>
                                                <p class="mb-2">No designs uploaded yet.</p>
                                                <a href="{{ route('dashboard', ['tab' => 'upload']) }}" class="btn btn-purple-cta btn-sm rounded-pill px-4 py-2">
                                                    Upload First Asset
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- ========================================== -->
                <!-- TAB 2: MANAGE DESIGNS -->
                <!-- ========================================== -->
                <div class="contributor-tab-pane" id="tab-designs">
                    <div class="table-container-glass mb-4">
                        <div class="p-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div>
                                <h5 class="fw-extrabold text-dark mb-1">
                                    <i class="bi bi-collection-fill text-primary me-2"></i> Manage Designs
                                </h5>
                                <p class="extra-small text-muted mb-0">Manage all your uploaded assets, approval status, and pricing.</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('dashboard', ['tab' => 'upload']) }}" class="btn btn-purple-cta btn-sm rounded-pill px-3.5 py-2 fw-bold">
                                    <i class="bi bi-plus-lg me-1"></i> Upload New Asset
                                </a>
                            </div>
                        </div>

                        <!-- SEARCH & FILTER BAR -->
                        <div class="p-3 bg-light border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 400px;">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" id="designSearchInput" class="form-control border-start-0" placeholder="Search by title..." onkeyup="filterDesignsTable()">
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-sm btn-white border rounded-pill active fw-bold px-3 py-1" onclick="filterByStatus('all', this)">All ({{ $totalResources }})</button>
                                <button class="btn btn-sm btn-white border rounded-pill fw-bold px-3 py-1" onclick="filterByStatus('approved', this)">Active ({{ $approvedResources }})</button>
                                <button class="btn btn-sm btn-white border rounded-pill fw-bold px-3 py-1" onclick="filterByStatus('pending', this)">Pending ({{ $pendingApproval }})</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="allDesignsTable">
                                <thead class="bg-light text-muted extra-small text-uppercase tracking-wider">
                                    <tr>
                                        <th class="ps-4">Cover</th>
                                        <th>Title & Category</th>
                                        <th>Type / Price</th>
                                        <th>Views</th>
                                        <th>Downloads</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($resources as $res)
                                        <tr data-status="{{ $res->status }}" class="design-table-row">
                                            <td class="ps-4" style="width: 70px;">
                                                <img src="{{ asset('storage/' . $res->preview_image) }}" class="table-preview-thumb border" alt="{{ $res->title }}" onerror="this.src='{{ asset('images/logo.png') }}'">
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark design-title">
                                                    <a href="{{ route('resource.show', $res->slug ?? $res->id) }}" class="text-dark text-decoration-none">
                                                        {{ $res->title }}
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mt-0.5">
                                                    <span class="badge bg-light text-muted extra-small">{{ $res->category?->name ?? 'Uncategorized' }}</span>
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary extra-small text-uppercase">{{ $res->file_type ?? 'ZIP' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                @if($res->is_paid)
                                                    <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">৳{{ number_format($res->price, 2) }}</span>
                                                    <div class="extra-small text-success">50% Share: ৳{{ number_format($res->price * 0.5, 2) }}</div>
                                                @else
                                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold">Free</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="small text-muted"><i class="bi bi-eye me-1"></i>{{ $res->views }}</span>
                                            </td>
                                            <td>
                                                <span class="small fw-bold text-dark"><i class="bi bi-cloud-arrow-down me-1 text-primary"></i>{{ $res->downloads }}</span>
                                            </td>
                                            <td>
                                                @if($res->status === 'approved')
                                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold rounded-pill px-2.5 py-1">Active</span>
                                                @elseif($res->status === 'pending')
                                                    <span class="badge bg-warning bg-opacity-10 text-warning fw-bold rounded-pill px-2.5 py-1">Pending Review</span>
                                                @elseif($res->status === 'inactive')
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold rounded-pill px-2.5 py-1">Inactive</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger fw-bold rounded-pill px-2.5 py-1">Rejected</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex align-items-center justify-content-end gap-1">
                                                    <a href="{{ route('resource.show', $res->slug ?? $res->id) }}" class="btn btn-light btn-sm rounded-pill px-2.5 py-1 text-primary" title="View Preview">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <form action="{{ route('seller.resource.destroy', $res) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this design?');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-light btn-sm rounded-pill px-2.5 py-1 text-danger" title="Delete">
                                                            <i class="bi bi-trash3"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <p class="mb-0">No designs found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- TAB 3: DOWNLOAD HISTORY -->
                <!-- ========================================== -->
                <div class="contributor-tab-pane" id="tab-downloads">
                    <div class="table-container-glass mb-4">
                        <div class="p-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h5 class="fw-extrabold text-dark mb-1">
                                    <i class="bi bi-clock-history text-primary me-2"></i> Download & Order History
                                </h5>
                                <p class="extra-small text-muted mb-0">Real-time purchase and download records for your designs.</p>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1.5 fw-bold">
                                Total Downloads: {{ number_format($totalDownloads) }}
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted extra-small text-uppercase tracking-wider">
                                    <tr>
                                        <th class="ps-4">Order #</th>
                                        <th>Design Title</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Price</th>
                                        <th class="text-end pe-4">Royalty (50%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentSales as $sale)
                                        <tr>
                                            <td class="ps-4 fw-mono small fw-bold text-muted">
                                                #{{ $sale->order?->order_number ?? $sale->id }}
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark text-truncate" style="max-width: 250px;">
                                                    {{ $sale->resource?->title ?? 'Digital Asset' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="small text-muted"><i class="bi bi-person me-1"></i>{{ $sale->order?->user?->name ?? 'Verified Buyer' }}</span>
                                            </td>
                                            <td>
                                                <span class="extra-small text-muted">{{ $sale->created_at->format('M d, Y h:i A') }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark fw-bold">৳{{ number_format($sale->price, 2) }}</span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2.5 py-1">
                                                    + ৳{{ number_format($sale->price * 0.50, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="bi bi-inbox display-6 d-block text-secondary opacity-50 mb-2"></i>
                                                <p class="mb-0">No premium download or sales history records yet.</p>
                                                <span class="extra-small text-muted">When your designs are set as paid, 50% will be credited directly to your balance per download.</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- TAB 4: Earnings & Wallet (EARNINGS & PAYOUTS) -->
                <!-- ========================================== -->
                <div class="contributor-tab-pane" id="tab-earnings">
                    <div class="row g-4 mb-4">
                        <!-- Wallet Balance Card -->
                        <div class="col-md-6">
                            <div class="card p-4 rounded-4 border-0 shadow-sm bg-gradient-purple text-white position-relative overflow-hidden h-100">
                                <div class="position-relative" style="z-index: 1;">
                                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 extra-small fw-bold mb-3">
                                        <i class="bi bi-wallet2 me-1"></i> Contributor Wallet
                                    </span>
                                    <div class="text-white text-opacity-80 small mb-1">Withdrawable Royalty Balance</div>
                                    <h1 class="display-5 fw-extrabold text-white mb-3">৳{{ number_format($totalEarnings, 2) }}</h1>
                                    
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="btn btn-warning rounded-pill px-4 py-2.5 fw-bold text-dark shadow-sm" onclick="alert('Minimum withdrawal threshold is ৳500 BDT. Your current balance: ৳{{ number_format($totalEarnings, 2) }}')">
                                            <i class="bi bi-cash-stack me-1"></i> Request BDT Payout
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Royalty Policy Card -->
                        <div class="col-md-6">
                            <div class="card p-4 rounded-4 border-0 shadow-sm bg-white h-100">
                                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-info-circle-fill text-primary"></i> Royalty & Payout Policy
                                </h6>
                                <ul class="list-unstyled small text-secondary mb-0 d-flex flex-column gap-2">
                                    <li class="d-flex align-items-start gap-2">
                                        <i class="bi bi-check-circle-fill text-success mt-0.5"></i>
                                        <span>You receive a direct 50% royalty commission on every paid download.</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2">
                                        <i class="bi bi-check-circle-fill text-success mt-0.5"></i>
                                        <span>Minimum payout threshold: ৳500 BDT (bKash, Nagad, Rocket, Bank Transfer).</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2">
                                        <i class="bi bi-check-circle-fill text-success mt-0.5"></i>
                                        <span>Withdrawal requests are processed within 24 to 48 hours.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- TAB 5: SUPPORT TICKET -->
                <!-- ========================================== -->
                <div class="contributor-tab-pane" id="tab-support">
                    <div class="card p-4 rounded-4 border-0 shadow-sm bg-white mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                            <div>
                                <h5 class="fw-extrabold text-dark mb-1">
                                    <i class="bi bi-headset text-primary me-2"></i> Contributor Support Desk
                                </h5>
                                <p class="extra-small text-muted mb-0">Open a ticket if you need assistance with design uploads, rejections, copyright claims, or payout inquiries.</p>
                            </div>
                        </div>

                        <form onsubmit="event.preventDefault(); alert('Support ticket submitted successfully! Our support team will get back to you shortly.'); this.reset();">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-3" placeholder="e.g. Upload inquiry / Payout verification" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Category <span class="text-danger">*</span></label>
                                    <select class="form-select rounded-3" required>
                                        <option value="">-- Select Category --</option>
                                        <option value="upload">Design Upload / Rejection</option>
                                        <option value="payment">Financial / Payout Request</option>
                                        <option value="copyright">Copyright Infringement Report</option>
                                        <option value="account">Account & KYC Verification</option>
                                        <option value="other">Other Inquiries</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-dark small">Detailed Message <span class="text-danger">*</span></label>
                                    <textarea rows="4" class="form-control rounded-3" placeholder="Describe your issue in detail..." required></textarea>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-purple-cta rounded-pill px-4 py-2 fw-bold">
                                        <i class="bi bi-send-fill me-1"></i> Submit Support Ticket
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- TAB 6: ACCOUNT SETTINGS -->
                <!-- ========================================== -->
                <div class="contributor-tab-pane" id="tab-account">
                    <div class="card p-4 rounded-4 border-0 shadow-sm bg-white mb-4">
                        <h5 class="fw-extrabold text-dark mb-3">
                            <i class="bi bi-person-gear text-primary me-2"></i> Account & Contributor Profile
                        </h5>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Full Name</label>
                                <input type="text" class="form-control bg-light" value="{{ auth()->user()->name }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Email Address</label>
                                <input type="email" class="form-control bg-light" value="{{ auth()->user()->email }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Contributor Role</label>
                                <input type="text" class="form-control bg-light" value="Noksha Creator / Contributor" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Trust Score</label>
                                <input type="text" class="form-control bg-light text-success fw-bold" value="{{ auth()->user()->trust_score ?? 99.4 }}% (Pro)" readonly>
                            </div>
                            <div class="col-12 pt-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Identity Verification (KYC)</h6>
                                    <span class="extra-small text-muted">Verify your account with official government ID and selfie.</span>
                                </div>
                                <a href="{{ route('seller.verification.create') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold btn-sm">
                                    <i class="bi bi-shield-check me-1"></i> View Verification Status
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<!-- TAB SWITCHING SCRIPT -->
<script>
function switchContributorTab(tabId) {
    // Hide all tabs
    document.querySelectorAll('.contributor-tab-pane').forEach(el => el.classList.remove('active'));
    
    // Deactivate all sidebar links
    document.querySelectorAll('.seller-sidebar-link').forEach(el => el.classList.remove('active'));

    // Show target tab
    const target = document.getElementById(tabId);
    if (target) {
        target.classList.add('active');
    }

    // Highlight target link in sidebar
    const link = document.querySelector(`.seller-sidebar-link[data-tab="${tabId}"]`);
    if (link) {
        link.classList.add('active');
    }

    // Update URL hash without scrolling
    if (history.pushState) {
        history.pushState(null, null, '#' + tabId.replace('tab-', ''));
    }
}

// Attach click listeners to sidebar links that have data-tab
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.seller-sidebar-link[data-tab]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const tabId = this.getAttribute('data-tab');
            switchContributorTab(tabId);
        });
    });

    // Check hash on page load
    const hash = window.location.hash.replace('#', '');
    if (hash && document.getElementById('tab-' + hash)) {
        switchContributorTab('tab-' + hash);
    }
});

function filterDesignsTable() {
    const input = document.getElementById('designSearchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.design-table-row');
    rows.forEach(row => {
        const title = row.querySelector('.design-title').textContent.toLowerCase();
        row.style.display = title.includes(input) ? '' : 'none';
    });
}

function filterByStatus(status, btn) {
    document.querySelectorAll('#allDesignsTable button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const rows = document.querySelectorAll('.design-table-row');
    rows.forEach(row => {
        if (status === 'all' || row.getAttribute('data-status') === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

@endsection
