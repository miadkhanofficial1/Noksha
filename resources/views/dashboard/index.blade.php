@extends('layouts.app')

@section('title', __('app.dashboard') . ' - Noksha (নকশা)')

@section('content')

<!-- CUSTOM DASHBOARD FIGMA GLASSMORPHISM & GRADIENT STYLES -->
<style>
    .dash-hero-banner {
        background: linear-gradient(135deg, #6C4CF1 0%, #8B5CF6 50%, #9F7AEA 100%);
        border-radius: 1.5rem !important;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 45px -10px rgba(108, 76, 241, 0.3) !important;
    }

    .dash-hero-banner::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.2) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: 0.35;
        pointer-events: none;
    }

    .dash-glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(108, 76, 241, 0.16) !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 15px 35px -10px rgba(108, 76, 241, 0.1) !important;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
    }

    .dash-glass-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 22px 45px -8px rgba(108, 76, 241, 0.18) !important;
        border-color: rgba(108, 76, 241, 0.3) !important;
    }

    .dash-stat-card {
        background: #ffffff;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        border-radius: 1.25rem !important;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.08) !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .dash-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 35px -8px rgba(108, 76, 241, 0.2) !important;
        border-color: #6C4CF1 !important;
    }

    .dash-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: transform 0.3s ease;
    }

    .dash-stat-card:hover .dash-stat-icon {
        transform: scale(1.1) rotate(4deg);
    }

    .badge-contributor-glow {
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.3);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }

    .badge-creator-shield {
        background: rgba(108, 76, 241, 0.12);
        color: #6C4CF1;
        border: 1px solid rgba(108, 76, 241, 0.3);
        box-shadow: 0 4px 12px rgba(108, 76, 241, 0.15);
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

    .table-dash-hover tbody tr {
        transition: background-color 0.2s ease;
    }

    .table-dash-hover tbody tr:hover {
        background-color: rgba(108, 76, 241, 0.03) !important;
    }
</style>

<div class="container py-4 py-lg-5">
    
    <!-- USER PROFILE & CONTRIBUTOR HERO BANNER CARD -->
    <div class="card dash-hero-banner text-white p-4 p-md-5 mb-4 position-relative">
        <div class="row align-items-center g-4 position-relative" style="z-index: 1;">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3">
                    <div class="noksha-logo-badge rounded-circle d-flex align-items-center justify-content-center fw-extrabold fs-2 text-white shadow-lg border border-2 border-white border-opacity-40" style="width: 72px; height: 72px; background: rgba(255, 255, 255, 0.25); backdrop-filter: blur(10px);">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <h2 class="fw-extrabold text-white mb-0">{{ $user->name }}</h2>
                            
                            @if($user->isVerifiedCreator() || $isContributor)
                                <span class="badge badge-contributor-glow rounded-pill px-3 py-1.5 small fw-bold">
                                    <i class="bi bi-patch-check-fill me-1"></i> Contributor ✓
                                </span>
                                <span class="badge badge-creator-shield rounded-pill px-3 py-1.5 small fw-bold">
                                    <i class="bi bi-shield-check me-1"></i> Verified Creator
                                </span>
                            @else
                                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 small fw-bold border border-white border-opacity-25">
                                    <i class="bi bi-person me-1"></i> Member / বায়ার
                                </span>
                            @endif
                        </div>
                        <p class="text-white text-opacity-90 small mb-0">
                            @ {{ $user->username }} • {{ $user->email }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Header Action CTA -->
            <div class="col-lg-4 text-lg-end">
                @if($isContributor)
                    <a href="{{ route('resource.create') }}" class="btn btn-light rounded-pill px-4 py-2.5 fw-bold text-primary shadow-lg">
                        <i class="bi bi-cloud-arrow-up-fill me-1.5"></i> {{ __('dashboard.upload') }}
                    </a>
                @else
                    <a href="{{ route('contributor.apply') }}" class="btn btn-light rounded-pill px-4 py-2.5 fw-bold text-primary shadow-lg">
                        <i class="bi bi-award-fill me-1.5"></i> Become a Contributor
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- FLASH NOTIFICATIONS -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-lg rounded-4 p-4 mb-4 d-flex align-items-center gap-3 text-dark bg-white">
            <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                <i class="bi bi-check-circle-fill fs-3"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-1">Success</h6>
                <p class="mb-0 small text-secondary">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- CONTRIBUTOR VERIFICATION APPLICATION BANNER (For Non-Contributors) -->
    @if(!$isContributor)
        <div class="card dash-glass-card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, rgba(108, 76, 241, 0.08) 0%, rgba(79, 70, 229, 0.04) 100%);">
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center g-3">
                    <div class="col-lg-8">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1.5 small fw-bold mb-2 border border-primary border-opacity-20">
                            <i class="bi bi-star-fill me-1 text-warning"></i> Noksha Creator Program
                        </span>
                        <h4 class="fw-extrabold text-dark mb-1">Earn by Selling Your Designs on Noksha</h4>
                        <p class="mb-0 text-secondary small" style="max-width: 600px;">
                            Submit your National ID (NID) or Passport and selfie photo to become a verified <strong>Noksha Contributor</strong> and unlock asset publishing.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('contributor.apply') }}" class="btn btn-purple-cta rounded-pill px-4 py-3 fw-bold">
                            <i class="bi bi-shield-check me-1.5"></i> {{ __('dashboard.seller_verification') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- OVERVIEW METRIC STATS CARDS -->
    <div class="row g-3 g-md-4 mb-4">
        <!-- 1. Orders Count -->
        <div class="col-6 col-md-3">
            <div class="dash-stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">{{ __('marketplace.orders') }}</div>
                        <div class="display-6 fw-extrabold text-dark mb-0">{{ $ordersCount }}</div>
                    </div>
                    <div class="dash-stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. My Downloads Count -->
        <div class="col-6 col-md-3">
            <div class="dash-stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">My Downloads</div>
                        <div class="display-6 fw-extrabold text-success mb-0">{{ $downloadsCount }}</div>
                    </div>
                    <div class="dash-stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-cloud-arrow-down-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Saved Wishlist -->
        <div class="col-6 col-md-3">
            <div class="dash-stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">{{ __('marketplace.wishlist') }}</div>
                        <div class="display-6 fw-extrabold text-danger mb-0">{{ $wishlistCount }}</div>
                    </div>
                    <div class="dash-stat-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Active Cart -->
        <div class="col-6 col-md-3">
            <div class="dash-stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">{{ __('marketplace.cart') }}</div>
                        <div class="display-6 fw-extrabold text-purple mb-0">{{ $cartCount }}</div>
                    </div>
                    <div class="dash-stat-icon bg-purple bg-opacity-10 text-purple">
                        <i class="bi bi-cart-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTRIBUTOR SECTION (Visible ONLY for Approved Contributor Users) -->
    @if($isContributor)
        <div class="card dash-glass-card border-0 rounded-4 mb-4 p-4 p-md-5">
            <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3">
                        <i class="bi bi-speedometer2 fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-extrabold text-dark mb-0">{{ __('dashboard.seller_dashboard') }} (Contributor Center)</h4>
                        <p class="text-muted extra-small mb-0">Manage published designs, track downloads, and review earnings.</p>
                    </div>
                </div>
                <span class="badge badge-contributor-glow rounded-pill px-3 py-1.5 small fw-bold">Active Contributor</span>
            </div>

            <!-- Contributor Metric Counters -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-4 border">
                        <div class="extra-small text-muted fw-bold text-uppercase mb-1">{{ __('dashboard.published_resources') }}</div>
                        <div class="fs-2 fw-extrabold text-dark">{{ $totalResources }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-4 border">
                        <div class="extra-small text-muted fw-bold text-uppercase mb-1">Total Downloads</div>
                        <div class="fs-2 fw-extrabold text-primary">{{ number_format($totalDownloads) }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-4 border">
                        <div class="extra-small text-muted fw-bold text-uppercase mb-1">Portfolio Views</div>
                        <div class="fs-2 fw-extrabold text-info">{{ number_format($totalViews) }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-4 border">
                        <div class="extra-small text-muted fw-bold text-uppercase mb-1">{{ __('dashboard.pending_approval') }}</div>
                        <div class="fs-2 fw-extrabold text-warning">{{ $pendingApproval }}</div>
                    </div>
                </div>
            </div>

            <!-- My Uploaded Resources Table -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold text-dark mb-0">My Uploaded Resources</h5>
                <a href="{{ route('resource.create') }}" class="btn btn-sm btn-purple-cta rounded-3 px-3 py-1.5 fw-bold">
                    <i class="bi bi-plus-lg me-1"></i> {{ __('dashboard.upload') }}
                </a>
            </div>

            <div class="table-responsive border rounded-4 overflow-hidden mb-2">
                <table class="table table-dash-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 ps-3">Template Title</th>
                            <th class="py-3">Category</th>
                            <th class="py-3">Price</th>
                            <th class="py-3">Downloads</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contributorResources as $res)
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="noksha-logo-badge rounded-3 flex-shrink-0" style="width:42px; height:42px; font-size:1.1rem; background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);">
                                            {{ strtoupper(substr($res->title, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-6 mb-0">{{ $res->title }}</div>
                                            <div class="extra-small text-muted">{{ $res->created_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border rounded-pill px-3 py-1">{{ $res->category->name ?? 'General' }}</span></td>
                                <td>
                                    @if($res->price > 0)
                                        <span class="fw-extrabold text-dark">৳{{ number_format($res->price) }}</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1">FREE</span>
                                    @endif
                                </td>
                                <td><span class="fw-bold text-primary"><i class="bi bi-download me-1"></i> {{ $res->downloads }}</span></td>
                                <td>
                                    @if($res->status === 'approved')
                                        <span class="badge bg-success rounded-pill px-3 py-1">Approved</span>
                                    @elseif($res->status === 'pending')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1">Pending</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill px-3 py-1">Rejected</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <form action="{{ route('seller.resource.destroy', $res->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this resource?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 py-1.5 px-3 fw-bold">
                                            <i class="bi bi-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-cloud-arrow-up fs-1 text-muted d-block mb-2"></i>
                                    You haven't uploaded any resources yet. <a href="{{ route('resource.create') }}" class="fw-bold text-primary">Upload your first design</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- BUYER DOWNLOADS & ORDER HISTORY SECTION -->
    <div class="card dash-glass-card border-0 rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom py-3.5 px-4 d-flex align-items-center justify-content-between">
            <h5 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-cloud-arrow-down-fill text-success fs-4"></i> My Purchased & Free Downloads
            </h5>
            <a href="{{ route('orders.index') }}" class="extra-small fw-bold text-primary text-decoration-none">
                View Order History <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dash-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 ps-4">Item Name</th>
                            <th class="py-3">Contributor / Author</th>
                            <th class="py-3">Purchase Date</th>
                            <th class="py-3">Amount Paid</th>
                            <th class="py-3 text-end pe-4">Download Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark fs-6">{{ $item->resource->title ?? 'Digital Asset' }}</div>
                                        <div class="extra-small text-muted font-monospace">Order #{{ $order->order_number }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill px-3 py-1">
                                            <i class="bi bi-person-circle me-1 text-primary"></i> {{ $item->resource->owner->name ?? 'Noksha Creator' }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="fw-extrabold text-dark">৳{{ number_format($item->price) }}</td>
                                    <td class="text-end pe-4">
                                        @if($item->resource)
                                            <a href="{{ route('resource.download', $item->resource->id) }}" class="btn btn-success btn-sm rounded-pill fw-bold px-4 py-1.5 shadow-sm">
                                                <i class="bi bi-cloud-arrow-down-fill me-1"></i> Download File
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-bag-check fs-1 text-muted d-block mb-2"></i>
                                    No purchase history yet. <a href="{{ route('home') }}" class="fw-bold text-primary">Explore marketplace templates</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
