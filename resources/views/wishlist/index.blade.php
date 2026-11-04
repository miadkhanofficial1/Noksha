@extends('layouts.app')

@section('title', 'Saved Wishlist - Noksha')

@section('content')

<!-- CUSTOM WISHLIST STYLES -->
<style>
    .template-card-figma {
        border-radius: 1.25rem !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
        transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
        overflow: hidden;
        background: #0d1527;
    }

    .template-card-figma:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.25) !important;
        border-color: rgba(99, 102, 241, 0.45) !important;
    }

    .btn-purple-cta {
        background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 6px 16px -4px rgba(99, 102, 241, 0.35);
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #4F46E5 0%, #4338CA 100%);
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 12px 25px -4px rgba(99, 102, 241, 0.45);
        color: #ffffff !important;
    }

    .card-grad-3 { background: linear-gradient(135deg, #EC4899 0%, #8B5CF6 100%); }
</style>

<div class="min-h-screen bg-[#070b14] py-12 px-4 sm:px-6 lg:px-8 text-white">
    <div class="max-w-7xl mx-auto">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-gray-400 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-indigo-400 hover:text-indigo-300"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item active text-gray-300" aria-current="page">Saved Wishlist</li>
            </ol>
        </nav>

        <!-- PAGE HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small bg-rose-500/10 text-rose-400 border border-rose-500/20">
                    <i class="bi bi-heart-fill me-1"></i> Saved Templates
                </span>
                <h2 class="display-6 fw-extrabold text-white mt-1 mb-0">My Saved Wishlist</h2>
            </div>
            <span class="small fw-bold text-gray-400 font-monospace">{{ $wishlistItems->count() }} items saved</span>
        </div>

        @if($wishlistItems->count() > 0)
            <div class="row g-4">
                @foreach($wishlistItems as $item)
                    @php $res = $item->resource; @endphp
                    @if($res)
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="card h-100 template-card-figma">
                                <!-- Preview Area -->
                                <div class="template-preview-area p-0 overflow-hidden position-relative" style="height: 200px; background: #131238;">
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
                                        <button type="submit" class="btn btn-dark btn-sm rounded-circle shadow-md p-1.5 bg-slate-900/90 text-rose-400 border border-slate-700/80 hover:bg-rose-600 hover:text-white transition-all" title="Remove from wishlist">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>

                                    <!-- Category Badge -->
                                    <span class="position-absolute top-0 start-0 m-2.5 badge bg-slate-900/90 text-gray-200 border border-slate-700/80 rounded-pill shadow-sm px-2.5 py-1 extra-small fw-bold backdrop-blur-sm">
                                        {{ $res->category ? $res->category->name : 'General' }}
                                    </span>
                                </div>

                                <!-- Card Content -->
                                <div class="card-body p-3.5 d-flex flex-column justify-content-between" style="background: #0d1527;">
                                    <div>
                                        <h6 class="fw-bold text-white mb-1 text-truncate hover:text-indigo-400 transition-colors" title="{{ $res->title }}">{{ $res->title }}</h6>
                                        <div class="extra-small text-gray-400 font-monospace mb-3">by {{ $res->owner ? $res->owner->name : 'Noksha Creator' }}</div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between pt-3 border-t border-gray-800">
                                        <span class="fw-extrabold text-emerald-400 fs-6 font-monospace">
                                            {{ $res->is_paid && $res->price > 0 ? '৳' . number_format($res->price, 2) : 'Free' }}
                                        </span>
                                        <div class="d-flex align-items-center gap-1.5">
                                            <form action="{{ route('cart.store', $res->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn rounded-pill btn-sm px-2.5 py-1 text-indigo-300 border border-indigo-500/40 hover:bg-indigo-600 hover:text-white transition-all" title="Add to Cart">
                                                    <i class="bi bi-cart-plus-fill"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('resource.show', $res->slug ?? $res->id) }}" class="btn btn-purple-cta rounded-pill px-3 py-1.5 btn-sm fw-bold">
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
            <div class="bg-[#0b1329] border border-gray-800/80 rounded-2xl p-10 text-center my-4 shadow-2xl">
                <div class="p-4 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;">
                    <i class="bi bi-heartbreak fs-1"></i>
                </div>
                <h4 class="fw-extrabold text-white mb-2">Your Saved Wishlist is Empty</h4>
                <p class="text-gray-400 small mb-4" style="max-width: 460px; margin: 0 auto;">
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
