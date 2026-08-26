@extends('layouts.app')

@section('title', 'Smart Marketplace Search - Noksha (নকশা)')

@section('content')

<!-- CUSTOM SEARCH PAGE STYLES -->
<style>
    .search-bg {
        background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%);
        min-height: 100vh;
    }

    .search-hero-box {
        background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #4338CA 100%);
        border-radius: 1.75rem;
        color: #ffffff;
    }

    .search-input-group {
        background: #ffffff;
        border-radius: 50rem;
        padding: 0.35rem 0.5rem 0.35rem 1.25rem;
        box-shadow: 0 15px 35px -5px rgba(0,0,0,0.25);
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
        box-shadow: 0 20px 45px -10px rgba(108, 76, 241, 0.22) !important;
        border-color: rgba(108, 76, 241, 0.35) !important;
    }

    .ai-tag-chip {
        background: rgba(108, 76, 241, 0.08);
        color: #6C4CF1;
        border-radius: 50rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.72rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.25s ease;
        display: inline-block;
    }

    .ai-tag-chip:hover {
        background: #6C4CF1;
        color: #ffffff;
        transform: translateY(-1px);
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

    .card-grad-1 { background: linear-gradient(135deg, #6366F1 0%, #A855F7 100%); }
</style>

<div class="search-bg py-4 py-lg-5">
    <div class="container py-2">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-muted mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">Smart Search</li>
            </ol>
        </nav>

        <!-- LARGE SEARCH HERO BOX -->
        <div class="search-hero-box p-4 p-md-5 mb-5 shadow-lg">
            <div class="text-center mb-4" style="max-width: 680px; margin: 0 auto;">
                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-extrabold extra-small text-uppercase mb-2">
                    <i class="bi bi-magic me-1"></i> Local Keyword AI Auto-Tag Engine
                </span>
                <h2 class="display-6 fw-extrabold text-white mb-2">Search Marketplace Assets</h2>
                <p class="text-white text-opacity-80 small mb-0">Discover UI kits, Figma design systems, vectors, and templates indexed with smart local keywords.</p>
            </div>

            <!-- SEARCH BAR FORM -->
            <form action="{{ route('search.index') }}" method="GET" class="mb-4" style="max-width: 680px; margin: 0 auto;">
                <div class="search-input-group d-flex align-items-center">
                    <i class="bi bi-search text-muted fs-5 me-2"></i>
                    <input type="text" name="q" value="{{ $queryStr }}" class="form-control border-0 bg-transparent text-dark shadow-none ps-0" placeholder="Search by title, tag (e.g. #fintech, #ui), category, or seller..." aria-label="Search">
                    <button type="submit" class="btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold">
                        Search Assets
                    </button>
                </div>
            </form>

            <!-- CATEGORY CHIPS & POPULAR TAGS -->
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-2" style="max-width: 780px; margin: 0 auto;">
                <span class="extra-small fw-bold text-white text-opacity-75 text-uppercase me-2"><i class="bi bi-tags-fill me-1 text-warning"></i>Popular Tags:</span>
                @foreach($popularTags as $pt)
                    <a href="{{ route('search.index', ['tag' => $pt]) }}" class="badge bg-white bg-opacity-15 text-white text-decoration-none rounded-pill px-3 py-1.5 extra-small fw-semibold border border-white border-opacity-25">
                        #{{ $pt }}
                    </a>
                @endforeach
            </div>

            <!-- RECENT SEARCHES SESSION PILLS -->
            @if(isset($recentSearches) && count($recentSearches) > 0)
                <div class="mt-3 text-center">
                    <span class="extra-small text-white text-opacity-60 me-2"><i class="bi bi-clock-history me-1"></i>Recent Searches:</span>
                    @foreach($recentSearches as $rs)
                        <a href="{{ route('search.index', ['q' => $rs]) }}" class="extra-small text-white text-opacity-80 text-decoration-underline me-2.5">
                            {{ $rs }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- FILTER & SORT CONTROLS BAR -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-3 border-bottom gap-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="small fw-bold text-muted me-2">Filters:</span>
                
                <a href="{{ route('search.index', array_merge(request()->query(), ['price' => null])) }}" class="btn btn-sm rounded-pill px-3 fw-bold {{ empty($priceType) ? 'btn-primary' : 'btn-outline-secondary' }}">
                    All Prices
                </a>
                <a href="{{ route('search.index', array_merge(request()->query(), ['price' => 'free'])) }}" class="btn btn-sm rounded-pill px-3 fw-bold {{ $priceType === 'free' ? 'btn-success' : 'btn-outline-secondary' }}">
                    Free Only
                </a>
                <a href="{{ route('search.index', array_merge(request()->query(), ['price' => 'paid'])) }}" class="btn btn-sm rounded-pill px-3 fw-bold {{ $priceType === 'paid' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Paid Only
                </a>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="small fw-bold text-muted font-monospace">{{ $resources->total() }} Assets Found</span>
                
                <form action="{{ route('search.index') }}" method="GET" class="d-inline-block">
                    @if($queryStr)<input type="hidden" name="q" value="{{ $queryStr }}">@endif
                    @if($categoryId)<input type="hidden" name="category" value="{{ $categoryId }}">@endif
                    @if($selectedTag)<input type="hidden" name="tag" value="{{ $selectedTag }}">@endif
                    @if($priceType)<input type="hidden" name="price" value="{{ $priceType }}">@endif

                    <select name="sort" class="form-select form-select-sm rounded-pill font-monospace" onchange="this.form.submit()">
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Latest Published</option>
                        <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Most Downloads</option>
                        <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- SEARCH RESULTS GRID -->
        @if($resources->count() > 0)
            <div class="row g-4 mb-5">
                @foreach($resources as $resource)
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card h-100 template-card-figma">
                            <!-- Preview Image Box -->
                            <div class="position-relative overflow-hidden" style="height: 190px; background: #1E1B4B;">
                                @if($resource->preview_image)
                                    <img src="{{ asset('storage/' . $resource->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $resource->title }}">
                                @else
                                    <div class="w-100 h-100 card-grad-1 p-4 d-flex align-items-center justify-content-center text-white text-center">
                                        <i class="bi bi-layers fs-1 opacity-75"></i>
                                    </div>
                                @endif

                                <!-- Category Badge -->
                                <span class="position-absolute top-0 start-0 m-2.5 badge bg-white text-dark rounded-pill shadow-sm px-2.5 py-1 extra-small fw-bold">
                                    {{ $resource->category ? $resource->category->name : 'General' }}
                                </span>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $resource->title }}">{{ $resource->title }}</h6>
                                    <div class="extra-small text-muted font-monospace mb-2">by {{ $resource->owner ? $resource->owner->name : 'Noksha Creator' }}</div>
                                    
                                    <!-- Auto-generated Tag Chips -->
                                    <div class="d-flex flex-wrap gap-1 mb-3">
                                        @if(!empty($resource->tags))
                                            @foreach(array_slice((array)$resource->tags, 0, 3) as $tg)
                                                <a href="{{ route('search.index', ['tag' => $tg]) }}" class="ai-tag-chip">#{{ $tg }}</a>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <!-- Card Footer -->
                                <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                                    <span class="fw-extrabold text-dark fs-6">
                                        {{ $resource->is_paid && $resource->price > 0 ? '৳' . number_format($resource->price, 2) : 'Free' }}
                                    </span>

                                    <div class="d-flex align-items-center gap-1.5">
                                        <form action="{{ route('wishlist.store', $resource->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-light border btn-sm rounded-circle p-1.5" title="Save to Wishlist">
                                                <i class="bi bi-heart-fill text-danger"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('cart.store', $resource->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-sm rounded-circle p-1.5" title="Add to Cart">
                                                <i class="bi bi-cart-plus-fill"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('resource.show', $resource->slug ?? $resource->id) }}" class="btn btn-purple-cta rounded-pill px-3 py-1.5 btn-sm">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- PAGINATION LINKS -->
            <div class="d-flex justify-content-center mt-4">
                {{ $resources->links() }}
            </div>
        @else
            <!-- EMPTY STATE WHEN ZERO RESULTS -->
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4">
                <div class="p-4 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;">
                    <i class="bi bi-search-heart fs-1"></i>
                </div>
                <h4 class="fw-extrabold text-dark mb-2">No Matching Assets Found</h4>
                <p class="text-secondary small mb-4" style="max-width: 480px; margin: 0 auto;">
                    We couldn't find any design templates matching <strong>"{{ $queryStr ?: $selectedTag }}"</strong>. Try searching for different keywords, explore popular tags, or clear filters.
                </p>
                <div>
                    <a href="{{ route('search.index') }}" class="btn btn-purple-cta rounded-pill px-5 py-3 fw-bold">
                        <i class="bi bi-arrow-counterclockwise me-2"></i> Clear All Search Filters
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
