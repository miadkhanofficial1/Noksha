@extends('layouts.app')

@section('title', __('app.dashboard') . ' - Noksha')

@section('content')
<div class="container py-4">
    
    <!-- USER PROFILE & CONTRIBUTOR HEADER CARD -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="noksha-logo-badge rounded-circle d-flex align-items-center justify-content-center fw-bold fs-3 text-white" style="width: 64px; height: 64px; background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h3 class="fw-bold text-dark mb-0">{{ $user->name }}</h3>
                            
                            @if($user->isVerifiedCreator() || $isContributor)
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5 border border-success border-opacity-20 small fw-bold">
                                    <i class="bi bi-patch-check-fill me-1"></i> Contributor ✓
                                </span>
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1.5 border border-primary border-opacity-20 small fw-bold">
                                    <i class="bi bi-shield-check me-1"></i> Verified Creator
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1.5 small fw-bold">
                                    <i class="bi bi-person me-1"></i> Member / বায়ার
                                </span>
                            @endif
                        </div>
                        <p class="text-muted small mb-0 mt-1">
                            @ {{ $user->username }} • {{ $user->email }}
                        </p>
                    </div>
                </div>

                <!-- Actions Header -->
                <div class="d-flex align-items-center gap-2">
                    @if($isContributor)
                        <a href="{{ route('resource.create') }}" class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> {{ __('dashboard.upload') }}
                        </a>
                    @else
                        <a href="{{ route('contributor.apply') }}" class="btn btn-outline-primary rounded-3 px-3 py-2 fw-bold">
                            <i class="bi bi-award-fill me-1"></i> Become a Contributor / কন্ট্রিবিউটর হন
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- FLASH NOTIFICATIONS -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- CONTRIBUTOR VERIFICATION APPLICATION BANNER (For Non-Contributors) -->
    @if(!$isContributor)
        <div class="card border-0 shadow-sm rounded-4 mb-4 text-white overflow-hidden" style="background: linear-gradient(135deg, #6C4CF1 0%, #4F46E5 100%);">
            <div class="card-body p-4">
                <div class="row align-items-center g-3">
                    <div class="col-lg-8">
                        <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 small fw-bold mb-2">
                            <i class="bi bi-star-fill me-1 text-warning"></i> Creator Program
                        </span>
                        <h4 class="fw-bold mb-1">Earn by Selling Your Designs on Noksha</h4>
                        <p class="mb-0 text-white text-opacity-90 small">
                            Submit your National ID (NID) and selfie to become a verified <strong>Noksha Contributor</strong> and reach thousands of buyers worldwide.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('contributor.apply') }}" class="btn btn-light text-primary rounded-pill px-4 py-2.5 fw-bold shadow">
                            <i class="bi bi-shield-check me-1"></i> {{ __('dashboard.seller_verification') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- OVERVIEW STATS CARDS -->
    <div class="row g-3 mb-4">
        <!-- 1. Orders Count -->
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3">
                        <i class="bi bi-receipt fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ $ordersCount }}</div>
                        <div class="small text-muted fw-semibold">{{ __('marketplace.orders') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Downloads Count -->
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 bg-success bg-opacity-10 text-success rounded-3">
                        <i class="bi bi-download fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ $downloadsCount }}</div>
                        <div class="small text-muted fw-semibold">My Downloads</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Saved Wishlist -->
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-3">
                        <i class="bi bi-heart-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ $wishlistCount }}</div>
                        <div class="small text-muted fw-semibold">{{ __('marketplace.wishlist') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Active Cart -->
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 bg-purple bg-opacity-10 text-purple rounded-3">
                        <i class="bi bi-cart-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ $cartCount }}</div>
                        <div class="small text-muted fw-semibold">{{ __('marketplace.cart') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTRIBUTOR SECTION (Visible ONLY for Approved Contributor Users) -->
    @if($isContributor)
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
            <div class="card-header bg-light border-bottom py-3 d-flex align-items-center justify-content-between">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-speedometer2 text-primary me-2"></i> {{ __('dashboard.seller_dashboard') }} (Contributor Center)
                </h5>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 small fw-bold">Active Contributor</span>
            </div>
            <div class="card-body p-4">
                <!-- Contributor Metric Counters -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="small text-muted fw-semibold">{{ __('dashboard.published_resources') }}</div>
                            <div class="fs-3 fw-bold text-dark mt-1">{{ $totalResources }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="small text-muted fw-semibold">Total Downloads</div>
                            <div class="fs-3 fw-bold text-primary mt-1">{{ number_format($totalDownloads) }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="small text-muted fw-semibold">Total Portfolio Views</div>
                            <div class="fs-3 fw-bold text-info mt-1">{{ number_format($totalViews) }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="small text-muted fw-semibold">{{ __('dashboard.pending_approval') }}</div>
                            <div class="fs-3 fw-bold text-warning mt-1">{{ $pendingApproval }}</div>
                        </div>
                    </div>
                </div>

                <!-- My Contributor Resources Table -->
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-dark mb-0">My Uploaded Resources</h6>
                    <a href="{{ route('resource.create') }}" class="btn btn-sm btn-primary rounded-3 fw-bold">
                        <i class="bi bi-plus-lg me-1"></i> {{ __('dashboard.upload') }}
                    </a>
                </div>

                <div class="table-responsive border rounded-3 mb-4">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Template Title</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Downloads</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contributorResources as $res)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="noksha-logo-badge rounded-2 flex-shrink-0" style="width:36px; height:36px; font-size:0.9rem;">
                                                {{ strtoupper(substr($res->title, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $res->title }}</div>
                                                <div class="extra-small text-muted">{{ $res->created_at->format('M d, Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $res->category->name ?? 'General' }}</span></td>
                                    <td>
                                        @if($res->price > 0)
                                            <span class="fw-bold text-dark">৳{{ number_format($res->price) }}</span>
                                        @else
                                            <span class="badge bg-success bg-opacity-10 text-success">FREE</span>
                                        @endif
                                    </td>
                                    <td><span class="fw-semibold text-primary"><i class="bi bi-download me-1"></i> {{ $res->downloads }}</span></td>
                                    <td>
                                        @if($res->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($res->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('seller.resource.destroy', $res->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this resource?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 py-1 px-2">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        You haven't uploaded any resources yet. <a href="{{ route('resource.create') }}" class="fw-bold text-primary">Upload your first design</a>
                                    </td>
                                </tr>
                            @forelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- BUYER DOWNLOADS & ORDER HISTORY SECTION -->
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-header bg-light border-bottom py-3 d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-download text-success me-2"></i> My Purchased & Free Downloads
            </h5>
            <a href="{{ route('orders.index') }}" class="extra-small fw-bold text-primary text-decoration-none">
                View All Orders <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Item Name</th>
                            <th>Contributor / Author</th>
                            <th>Purchase Date</th>
                            <th>Amount Paid</th>
                            <th class="text-end">Download Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->resource->title ?? 'Digital Asset' }}</div>
                                        <div class="extra-small text-muted">Order #{{ $order->order_number }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-person me-1"></i> {{ $item->resource->owner->name ?? 'Noksha Creator' }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="fw-bold text-dark">৳{{ number_format($item->price) }}</td>
                                    <td class="text-end">
                                        @if($item->resource)
                                            <a href="{{ route('resource.download', $item->resource->id) }}" class="btn btn-success btn-sm rounded-3 fw-bold px-3">
                                                <i class="bi bi-cloud-arrow-down-fill me-1"></i> Download
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
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
