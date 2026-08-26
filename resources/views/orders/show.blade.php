@extends('layouts.app')

@section('title', 'Order Details #' . $order->order_number . ' - Noksha (নকশা)')

@section('content')

<!-- CUSTOM ORDER DETAILS STYLES -->
<style>
    .order-details-bg {
        background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%);
        min-height: 100vh;
    }

    .order-details-card-figma {
        background: #ffffff;
        border-radius: 1.5rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.06) !important;
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

<div class="order-details-bg py-4 py-lg-5">
    <div class="container py-2" style="max-width: 900px;">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-muted mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none text-primary">Orders</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">Order #{{ $order->order_number }}</li>
            </ol>
        </nav>

        <!-- PAGE HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(108, 76, 241, 0.08); color: #6C4CF1;">
                    <i class="bi bi-receipt me-1"></i> Receipt Inspector
                </span>
                <h2 class="display-6 fw-extrabold text-dark mt-1 mb-0">Order Details</h2>
            </div>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm px-3.5 fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Back to Orders
            </a>
        </div>

        <!-- MAIN ORDER CARD -->
        <div class="card order-details-card-figma p-4 p-md-5 mb-4">
            <!-- TOP METADATA SUMMARY -->
            <div class="row g-3 bg-light p-3.5 rounded-4 mb-4 border">
                <div class="col-6 col-md-3">
                    <div class="extra-small text-muted text-uppercase fw-bold">Order Number</div>
                    <div class="fw-extrabold text-dark font-monospace">#{{ $order->order_number }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="extra-small text-muted text-uppercase fw-bold">Transaction ID</div>
                    <div class="fw-bold text-primary font-monospace small">{{ $order->transaction_id ?? 'TXN-Direct' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="extra-small text-muted text-uppercase fw-bold">Payment Method</div>
                    <div class="fw-bold text-dark text-capitalize small">{{ $order->payment_method ?? 'bKash' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="extra-small text-muted text-uppercase fw-bold">Order Date</div>
                    <div class="small text-muted font-monospace">{{ $order->created_at->format('M d, Y') }}</div>
                </div>
            </div>

            <!-- ITEMS TABLE -->
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-box-seam text-primary me-2"></i>Purchased Digital Resources</h5>
            
            <div class="table-responsive mb-4">
                <table class="table table-hover align-middle mb-0 border rounded-3">
                    <thead class="bg-light text-uppercase extra-small text-muted fw-bold">
                        <tr>
                            <th>Resource Item</th>
                            <th>Category</th>
                            <th>Format</th>
                            <th>Price</th>
                            <th class="text-end">Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            @php $res = $item->resource; @endphp
                            @if($res)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 56px; height: 42px; background: #1E1B4B;">
                                                @if($res->preview_image)
                                                    <img src="{{ asset('storage/' . $res->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $res->title }}">
                                                @else
                                                    <div class="w-100 h-100 card-grad-1 p-1 text-white text-center"><i class="bi bi-box"></i></div>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('resource.show', $res->slug ?? $res->id) }}" class="fw-bold text-dark text-decoration-none small d-block" title="{{ $res->title }}">
                                                    {{ $res->title }}
                                                </a>
                                                <div class="extra-small text-muted">by {{ $res->owner ? $res->owner->name : 'Noksha Creator' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1 fw-bold extra-small">
                                            {{ $res->category ? $res->category->name : 'General' }}
                                        </span>
                                    </td>
                                    <td class="small font-monospace text-uppercase text-muted">
                                        {{ $res->file_type ?? 'ZIP' }}
                                    </td>
                                    <td>
                                        <span class="fw-extrabold text-dark small">
                                            {{ $res->is_paid && $res->price > 0 ? '৳' . number_format($res->price, 2) : 'Free' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('resource.download', $res->id) }}" class="btn btn-purple-cta rounded-pill btn-sm px-3.5 fw-bold">
                                            <i class="bi bi-cloud-arrow-down-fill me-1"></i> Download
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TOTAL SUMMARY -->
            <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                <div class="small text-muted">
                    <i class="bi bi-patch-check-fill text-success me-1"></i> Order Payment Verified & Completed
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="small fw-bold text-muted text-uppercase">Total Paid:</span>
                    <span class="display-6 fw-extrabold text-primary">৳{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
