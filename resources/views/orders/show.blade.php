@extends('layouts.app')

@section('title', 'Order Details #' . $order->order_number . ' - Noksha')

@section('content')

<!-- CUSTOM ORDER DETAILS STYLES (DARK THEME) -->
<style>
    .order-details-bg {
        background: #020617; /* slate-950 */
        min-height: 100vh;
        color: #F8FAFC;
    }

    .order-details-card-figma {
        background: rgba(15, 23, 42, 0.85) !important;
        border-radius: 1.5rem !important;
        border: 1px solid rgba(148, 163, 184, 0.15) !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
    }

    .table-order-items {
        color: #F8FAFC;
        margin-bottom: 0;
    }

    .table-order-items th {
        background: transparent;
        color: #94A3B8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: 1px solid rgba(148, 163, 184, 0.15);
        padding: 0.85rem 1rem;
    }

    .table-order-items td {
        background: transparent;
        color: #E2E8F0;
        font-size: 0.875rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(148, 163, 184, 0.08);
        padding: 1rem;
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
            <ol class="breadcrumb small fw-semibold text-slate-400 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-indigo-400 hover:text-indigo-300"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none text-indigo-400 hover:text-indigo-300">Orders</a></li>
                <li class="breadcrumb-item active text-slate-200" aria-current="page">Order #{{ $order->order_number }}</li>
            </ol>
        </nav>

        <!-- PAGE HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(99, 102, 241, 0.15); color: #A5B4FC; border: 1px solid rgba(99, 102, 241, 0.3);">
                    <i class="bi bi-receipt me-1"></i> Receipt Inspector
                </span>
                <h2 class="display-6 fw-extrabold text-white mt-1 mb-0">Order Details</h2>
            </div>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm px-3.5 fw-bold text-slate-300" style="border-color: rgba(148, 163, 184, 0.25);">
                <i class="bi bi-arrow-left me-1"></i> Back to Orders
            </a>
        </div>

        <!-- MAIN ORDER CARD -->
        <div class="card order-details-card-figma p-4 p-md-5 mb-4">
            <!-- TOP METADATA SUMMARY -->
            <div class="row g-3 p-3.5 rounded-4 mb-4 border border-slate-800" style="background: rgba(11, 17, 32, 0.7);">
                <div class="col-6 col-md-3">
                    <div class="extra-small text-slate-400 text-uppercase fw-bold">Order Number</div>
                    <div class="fw-extrabold text-white font-monospace">#{{ $order->order_number }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="extra-small text-slate-400 text-uppercase fw-bold">Transaction ID</div>
                    <div class="fw-bold text-indigo-400 font-monospace small">{{ $order->transaction_id ?? 'TXN-Direct' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="extra-small text-slate-400 text-uppercase fw-bold">Payment Method</div>
                    <div class="fw-bold text-white text-capitalize small">{{ $order->payment_method ?? 'bKash' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="extra-small text-slate-400 text-uppercase fw-bold">Order Date</div>
                    <div class="small text-slate-400 font-monospace">{{ $order->created_at->format('M d, Y') }}</div>
                </div>
            </div>

            <!-- ITEMS TABLE -->
            <h5 class="fw-bold text-white mb-3"><i class="bi bi-box-seam text-indigo-400 me-2"></i>Purchased Digital Resources</h5>
            
            <div class="table-responsive mb-4">
                <table class="table table-order-items align-middle mb-0 border border-slate-800 rounded-3">
                    <thead>
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
                                            <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 56px; height: 42px; background: #0B1120; border: 1px solid rgba(148, 163, 184, 0.12);">
                                                @if($res->preview_image)
                                                    <img src="{{ asset('storage/' . $res->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $res->title }}">
                                                @else
                                                    <div class="w-100 h-100 card-grad-1 p-1 text-white text-center"><i class="bi bi-box"></i></div>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('resource.show', $res->slug ?? $res->id) }}" class="fw-bold text-white hover-underline text-decoration-none small d-block" title="{{ $res->title }}">
                                                    {{ $res->title }}
                                                </a>
                                                <div class="extra-small text-slate-400">by {{ $res->owner ? $res->owner->name : 'Noksha Creator' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-2.5 py-1 fw-bold extra-small" style="background: rgba(99, 102, 241, 0.18); color: #C4B5FD; border: 1px solid rgba(99, 102, 241, 0.3);">
                                            {{ $res->category ? $res->category->name : 'General' }}
                                        </span>
                                    </td>
                                    <td class="small font-monospace text-uppercase text-slate-400">
                                        {{ $res->file_type ?? 'ZIP' }}
                                    </td>
                                    <td>
                                        <span class="fw-extrabold text-emerald-400 small font-monospace">
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
            <div class="d-flex align-items-center justify-content-between pt-3 border-top border-slate-800">
                <div class="small text-slate-400">
                    <i class="bi bi-patch-check-fill text-success me-1"></i> Order Payment Verified & Completed
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="small fw-bold text-slate-400 text-uppercase">Total Paid:</span>
                    <span class="display-6 fw-extrabold text-emerald-400 font-monospace">৳{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection