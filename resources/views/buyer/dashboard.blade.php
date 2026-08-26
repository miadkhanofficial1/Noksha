@extends('layouts.app')

@section('title', 'Buyer Dashboard - Noksha (নকশা)')

@section('content')

<!-- CUSTOM FIGMA/DRIBBBLE BUYER DASHBOARD STYLES -->
<style>
    .buyer-dashboard-bg {
        background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%);
        min-height: 100vh;
    }

    /* Glassmorphism Welcome Card */
    .glass-welcome-card {
        background: linear-gradient(135deg, rgba(108, 76, 241, 0.95) 0%, rgba(90, 61, 224, 0.9) 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 20px 40px -10px rgba(108, 76, 241, 0.35) !important;
        position: relative;
        overflow: hidden;
    }

    .glass-welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Animated Stat Card */
    .stat-card-figma {
        background: #ffffff;
        border-radius: 1.25rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.08) !important;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
    }

    .stat-card-figma:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 35px -8px rgba(108, 76, 241, 0.18) !important;
        border-color: rgba(108, 76, 241, 0.3) !important;
    }

    /* Template Cards for Wishlist & Recommended */
    .template-card-figma {
        border-radius: 1.25rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.08) !important;
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        overflow: hidden;
        background: #ffffff;
    }

    .template-card-figma:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px -10px rgba(108, 76, 241, 0.2) !important;
        border-color: rgba(108, 76, 241, 0.35) !important;
    }

    /* Purple CTA Button */
    .btn-purple-cta {
        background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 6px 16px -4px rgba(108, 76, 241, 0.35);
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #5A3DE0 0%, #4327C6 100%);
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 12px 25px -4px rgba(108, 76, 241, 0.45);
        color: #ffffff !important;
    }

    .btn-white-glass {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff !important;
        border: 1.5px solid rgba(255, 255, 255, 0.4) !important;
        backdrop-filter: blur(10px);
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-white-glass:hover {
        background: #ffffff;
        color: #5A3DE0 !important;
        transform: translateY(-2px);
    }

    /* Gradients */
    .card-grad-1 { background: linear-gradient(135deg, #6366F1 0%, #A855F7 100%); }
    .card-grad-2 { background: linear-gradient(135deg, #3B82F6 0%, #06B6D4 100%); }
    .card-grad-3 { background: linear-gradient(135deg, #EC4899 0%, #8B5CF6 100%); }
    .card-grad-4 { background: linear-gradient(135deg, #F59E0B 0%, #EF4444 100%); }
</style>

<div class="buyer-dashboard-bg py-4 py-lg-5">
    <div class="container py-2">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-muted mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">Buyer Dashboard</li>
            </ol>
        </nav>

        <!-- TOP WELCOME CARD (PURPLE GLASSMORPHISM) -->
        <div class="card glass-welcome-card p-4 p-md-5 mb-5 text-white">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 bg-white bg-opacity-20 rounded-circle text-white d-flex align-items-center justify-content-center fw-extrabold fs-3 shadow-sm" style="width: 64px; height: 64px; border: 2px solid rgba(255,255,255,0.4);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <span class="badge bg-white text-dark rounded-pill px-3 py-1 fw-bold extra-small text-uppercase mb-1 shadow-sm">
                                <i class="bi bi-person-heart text-danger me-1"></i> Verified Customer
                            </span>
                            <h2 class="display-6 fw-extrabold mb-0">Welcome back, {{ $user->name }}!</h2>
                        </div>
                    </div>
                    <p class="fs-6 text-white text-opacity-90 mb-0" style="max-width: 580px;">
                        Manage your digital downloads, view order receipts, explore saved wishlist templates, and discover AI-recommended design assets.
                    </p>
                </div>
                
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-column flex-sm-row flex-lg-column gap-2.5 justify-content-lg-end">
                        <a href="{{ route('home') }}#templates" class="btn btn-white-glass rounded-pill px-4 py-3 fw-bold">
                            <i class="bi bi-compass-fill me-2"></i> Browse Marketplace
                        </a>
                        <span class="small text-white text-opacity-80 font-monospace me-lg-2">
                            <i class="bi bi-calendar-check me-1"></i> Customer Member since {{ $user->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 ANIMATED STATISTICS CARDS -->
        <div class="row g-3 g-md-4 mb-5">
            <!-- Card 1: Total Downloads -->
            <div class="col-6 col-md-3">
                <div class="card stat-card-figma p-4 text-center">
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                        <i class="bi bi-cloud-arrow-down-fill fs-3 text-primary"></i>
                    </div>
                    <div class="display-6 fw-extrabold text-dark mb-0">{{ $downloadsCount }}</div>
                    <div class="extra-small text-uppercase tracking-wider fw-bold text-muted mt-1">Downloads</div>
                </div>
            </div>

            <!-- Card 2: Saved Wishlist -->
            <div class="col-6 col-md-3">
                <a href="{{ route('wishlist.index') }}" class="text-decoration-none">
                    <div class="card stat-card-figma p-4 text-center">
                        <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                            <i class="bi bi-heart-fill fs-3 text-danger"></i>
                        </div>
                        <div class="display-6 fw-extrabold text-dark mb-0">{{ $wishlistCount }}</div>
                        <div class="extra-small text-uppercase tracking-wider fw-bold text-muted mt-1">Wishlist</div>
                    </div>
                </a>
            </div>

            <!-- Card 3: Orders Placed -->
            <div class="col-6 col-md-3">
                <div class="card stat-card-figma p-4 text-center">
                    <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                        <i class="bi bi-bag-check-fill fs-3 text-success"></i>
                    </div>
                    <div class="display-6 fw-extrabold text-dark mb-0">{{ $ordersCount }}</div>
                    <div class="extra-small text-uppercase tracking-wider fw-bold text-muted mt-1">Orders</div>
                </div>
            </div>

            <!-- Card 4: Cart Items -->
            <div class="col-6 col-md-3">
                <a href="{{ route('cart.index') }}" class="text-decoration-none">
                    <div class="card stat-card-figma p-4 text-center">
                        <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                            <i class="bi bi-cart-fill fs-3 text-warning"></i>
                        </div>
                        <div class="display-6 fw-extrabold text-dark mb-0">{{ $cartCount ?? 0 }}</div>
                        <div class="extra-small text-uppercase tracking-wider fw-bold text-muted mt-1">Cart Items</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- RECENT DOWNLOADS SECTION -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-5">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(108, 76, 241, 0.08); color: #6C4CF1;">
                        <i class="bi bi-download me-1"></i> Digital Asset Vault
                    </span>
                    <h3 class="fw-extrabold text-dark mt-1 mb-0">Recent Downloads</h3>
                </div>
                <a href="{{ route('home') }}#templates" class="btn btn-outline-primary rounded-pill btn-sm px-3 fw-bold">
                    Explore More <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            @if(isset($orderDownloads) && $orderDownloads->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-uppercase extra-small text-muted fw-bold">
                            <tr>
                                <th>Preview</th>
                                <th>Resource Title</th>
                                <th>Category</th>
                                <th>Download Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderDownloads as $item)
                                <tr>
                                    <td style="width: 80px;">
                                        <div class="rounded-3 overflow-hidden" style="width: 60px; height: 45px; background: #1E1B4B;">
                                            @if($item->preview_image)
                                                <img src="{{ asset('storage/' . $item->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->title }}">
                                            @else
                                                <div class="w-100 h-100 card-grad-1 p-2 d-flex align-items-center justify-content-center text-white">
                                                    <i class="bi bi-file-earmark-code"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="fw-bold text-dark mb-0">{{ $item->title }}</h6>
                                        <div class="extra-small text-muted font-monospace">{{ strtoupper($item->file_type ?? 'ZIP') }} File</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1 fw-bold extra-small">
                                            {{ $item->category ? $item->category->name : 'General' }}
                                        </span>
                                    </td>
                                    <td class="small text-muted font-monospace">
                                        {{ now()->format('M d, Y') }}
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('resource.show', $item->slug ?? $item->id) }}" class="btn btn-purple-cta rounded-pill btn-sm px-3.5 fw-bold">
                                            <i class="bi bi-cloud-arrow-down-fill me-1"></i> Open & Download
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- EMPTY STATE FOR DOWNLOADS -->
                <div class="text-center py-5">
                    <div class="p-4 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-cloud-slash fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">No Purchased Downloads Yet</h5>
                    <p class="text-secondary small mb-4" style="max-width: 440px; margin: 0 auto;">
                        When you purchase or download free marketplace templates, your instant file download links will be stored here.
                    </p>
                    <a href="{{ route('home') }}#templates" class="btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold">
                        <i class="bi bi-search me-1"></i> Find Design Assets
                    </a>
                </div>
            @endif
        </div>

        <!-- WISHLIST PREVIEW SECTION -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-5">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(220, 38, 38, 0.08); color: #DC2626;">
                        <i class="bi bi-heart-fill me-1"></i> Saved Wishlist
                    </span>
                    <h3 class="fw-extrabold text-dark mt-1 mb-0">Wishlist Saved Items</h3>
                </div>
                <span class="small fw-bold text-muted font-monospace">{{ $wishlistItems->count() }} items saved</span>
            </div>

            @if(isset($wishlistItems) && $wishlistItems->count() > 0)
                <div class="row g-4">
                    @foreach($wishlistItems as $wish)
                        <div class="col-12 col-md-6 col-lg-3" id="wishlist-card-{{ $wish->id }}">
                            <div class="card h-100 template-card-figma">
                                <div class="template-preview-area p-0 overflow-hidden position-relative" style="height: 180px; background: #1E1B4B;">
                                    @if($wish->preview_image)
                                        <img src="{{ asset('storage/' . $wish->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $wish->title }}">
                                    @else
                                        <div class="w-100 h-100 card-grad-3 p-4 d-flex align-items-center justify-content-center text-white">
                                            <i class="bi bi-heart-fill fs-1 opacity-75"></i>
                                        </div>
                                    @endif
                                    <button type="button" onclick="document.getElementById('wishlist-card-{{ $wish->id }}').style.display='none';" class="position-absolute top-0 end-0 m-2.5 btn btn-light btn-sm rounded-circle shadow-sm" title="Remove from wishlist">
                                        <i class="bi bi-x-lg text-danger"></i>
                                    </button>
                                </div>
                                <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $wish->title }}</h6>
                                        <div class="small text-muted mb-3">{{ $wish->category ? $wish->category->name : 'General' }}</div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                        <span class="fw-extrabold text-dark">
                                            {{ $wish->is_paid && $wish->price > 0 ? '৳' . number_format($wish->price, 2) : 'Free' }}
                                        </span>
                                        <a href="{{ route('resource.show', $wish->slug ?? $wish->id) }}" class="btn btn-purple-cta rounded-pill px-3 py-1.5 btn-sm">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- EMPTY STATE FOR WISHLIST -->
                <div class="text-center py-5">
                    <div class="p-4 bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-heartbreak fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Your Wishlist is Empty</h5>
                    <p class="text-secondary small mb-4" style="max-width: 440px; margin: 0 auto;">
                        Click the heart icon on any template card to save design resources for later download or purchasing.
                    </p>
                    <a href="{{ route('home') }}#templates" class="btn btn-outline-danger rounded-pill px-4 py-2.5 fw-bold">
                        Browse Design Assets
                    </a>
                </div>
            @endif
        </div>

        <!-- ORDER HISTORY SECTION -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-5">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(16, 185, 129, 0.08); color: #10B981;">
                        <i class="bi bi-receipt me-1"></i> Billing & Transactions
                    </span>
                    <h3 class="fw-extrabold text-dark mt-1 mb-0">Order History</h3>
                </div>
            </div>

            @if(isset($orders) && $orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-uppercase extra-small text-muted fw-bold">
                            <tr>
                                <th>Order Number</th>
                                <th>Total Price</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="fw-bold text-dark font-monospace">#{{ $order->order_number }}</td>
                                    <td class="fw-bold text-dark">৳{{ number_format($order->total, 2) }}</td>
                                    <td class="small text-muted text-capitalize">{{ $order->payment_method ?? 'bKash / Card' }}</td>
                                    <td>
                                        @if($order->payment_status === 'completed')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1 fw-bold extra-small">
                                                <i class="bi bi-check-circle-fill me-1"></i> Completed
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 rounded-pill px-3 py-1 fw-bold extra-small">
                                                <i class="bi bi-clock-history me-1"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="small text-muted font-monospace">{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- EMPTY STATE FOR ORDERS -->
                <div class="text-center py-5">
                    <div class="p-4 bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-receipt-cutoff fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">No Past Orders Found</h5>
                    <p class="text-secondary small mb-0" style="max-width: 440px; margin: 0 auto;">
                        Your complete billing history and digital payment receipts will appear here after checkout.
                    </p>
                </div>
            @endif
        </div>

        <!-- RECENTLY VIEWED & RECOMMENDED RESOURCES -->
        <div class="row g-4">
            <!-- Recently Viewed -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <h5 class="fw-extrabold text-dark mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Recently Viewed</h5>
                    @if(isset($recentlyViewed) && $recentlyViewed->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($recentlyViewed->take(3) as $rv)
                                <div class="d-flex align-items-center justify-content-between p-2.5 bg-light rounded-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 overflow-hidden" style="width: 50px; height: 40px; background: #1E1B4B;">
                                            @if($rv->preview_image)
                                                <img src="{{ asset('storage/' . $rv->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $rv->title }}">
                                            @else
                                                <div class="w-100 h-100 card-grad-2 p-1 text-white text-center"><i class="bi bi-eye"></i></div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0 small text-truncate" style="max-width: 200px;">{{ $rv->title }}</h6>
                                            <span class="extra-small text-muted">{{ $rv->category ? $rv->category->name : 'General' }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('resource.show', $rv->slug ?? $rv->id) }}" class="btn btn-outline-purple rounded-pill btn-sm px-3 extra-small fw-bold">
                                        View
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recommended Resources -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <h5 class="fw-extrabold text-dark mb-3"><i class="bi bi-stars text-warning me-2"></i>Recommended for You</h5>
                    @if(isset($recommendedResources) && $recommendedResources->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($recommendedResources->take(3) as $rec)
                                <div class="d-flex align-items-center justify-content-between p-2.5 bg-light rounded-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 overflow-hidden" style="width: 50px; height: 40px; background: #1E1B4B;">
                                            @if($rec->preview_image)
                                                <img src="{{ asset('storage/' . $rec->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $rec->title }}">
                                            @else
                                                <div class="w-100 h-100 card-grad-4 p-1 text-white text-center"><i class="bi bi-stars"></i></div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0 small text-truncate" style="max-width: 200px;">{{ $rec->title }}</h6>
                                            <span class="extra-small text-success fw-bold">{{ $rec->is_paid && $rec->price > 0 ? '৳' . number_format($rec->price, 2) : 'Free' }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('resource.show', $rec->slug ?? $rec->id) }}" class="btn btn-purple-cta rounded-pill btn-sm px-3 extra-small fw-bold">
                                        Inspect
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
