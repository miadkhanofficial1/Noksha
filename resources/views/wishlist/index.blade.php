@extends('layouts.app')

@section('title', 'Saved Wishlist - Noksha (নকশা)')

@section('content')

<!-- CUSTOM WISHLIST STYLES -->
<style>
    .wishlist-bg {
        background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%);
        min-height: 100vh;
    }

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

    .card-grad-3 { background: linear-gradient(135deg, #EC4899 0%, #8B5CF6 100%); }
</style>

<div class="wishlist-bg py-4 py-lg-5">
    <div class="container py-2">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-muted mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">Saved Wishlist</li>
            </ol>
        </nav>

        <!-- PAGE HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(220, 38, 38, 0.08); color: #DC2626;">
                    <i class="bi bi-heart-fill me-1"></i> Saved Templates
                </span>
                <h2 class="display-6 fw-extrabold text-dark mt-1 mb-0">My Saved Wishlist</h2>
            </div>
            <span class="small fw-bold text-muted font-monospace">{{ $wishlistItems->count() }} items saved</span>
        </div>

        @if($wishlistItems->count() > 0)
            <div class="row g-4">
                @foreach($wishlistItems as $item)
                    @php $res = $item->resource; @endphp
                    @if($res)
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="card h-100 template-card-figma">
                                <!-- Preview Area -->
                                <div class="template-preview-area p-0 overflow-hidden position-relative" style="height: 200px; background: #1E1B4B;">
                                    @if($res->preview_image)
                                        <img src="{{ asset('storage/' . $res->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $res->title }}">
                                    @else
                                        <div class="w-100 h-100 card-grad-3 p-4 d-flex align-items-center justify-content-center text-white">
                                            <i class="bi bi-heart-fill fs-1 opacity-75"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Remove Button -->
                                    <form action="{{ route('wishlist.destroy', $res->id) }}" method="POST" class="position-absolute top-0 end-0 m-2.5">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm p-1.5" title="Remove from wishlist">
                                            <i class="bi bi-x-lg text-danger"></i>
                                        </button>
                                    </form>

                                    <!-- Category Badge -->
                                    <span class="position-absolute top-0 start-0 m-2.5 badge bg-white text-dark rounded-pill shadow-sm px-2.5 py-1 extra-small fw-bold">
                                        {{ $res->category ? $res->category->name : 'General' }}
                                    </span>
                                </div>

                                <!-- Card Content -->
                                <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $res->title }}">{{ $res->title }}</h6>
                                        <div class="extra-small text-muted font-monospace mb-3">by {{ $res->owner ? $res->owner->name : 'Noksha Creator' }}</div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                                        <span class="fw-extrabold text-dark fs-6">
                                            {{ $res->is_paid && $res->price > 0 ? '৳' . number_format($res->price, 2) : 'Free' }}
                                        </span>
                                        <div class="d-flex align-items-center gap-1.5">
                                            <form action="{{ route('cart.store', $res->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary rounded-pill btn-sm px-2.5" title="Add to Cart">
                                                    <i class="bi bi-cart-plus-fill"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('resource.show', $res->slug ?? $res->id) }}" class="btn btn-purple-cta rounded-pill px-3 py-1.5 btn-sm">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <!-- EMPTY STATE FOR WISHLIST -->
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4">
                <div class="p-4 bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;">
                    <i class="bi bi-heartbreak fs-1"></i>
                </div>
                <h4 class="fw-extrabold text-dark mb-2">Your Saved Wishlist is Empty</h4>
                <p class="text-secondary small mb-4" style="max-width: 460px; margin: 0 auto;">
                    You haven't saved any templates yet. Click the heart icon on any design card to bookmark assets for later inspection or checkout.
                </p>
                <div>
                    <a href="{{ route('home') }}#templates" class="btn btn-purple-cta rounded-pill px-5 py-3 fw-bold">
                        <i class="bi bi-compass me-2"></i> Explore Marketplace Templates
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
