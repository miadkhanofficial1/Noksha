@extends('layouts.app')

@section('title', 'Order Placed Successfully - Noksha (নকশা)')

@section('content')

<!-- CUSTOM SUCCESS PAGE STYLES -->
<style>
    .success-bg {
        background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%);
        min-height: 100vh;
    }

    .success-card-figma {
        background: #ffffff;
        border-radius: 1.5rem !important;
        border: 1px solid rgba(108, 76, 241, 0.15) !important;
        box-shadow: 0 20px 45px -10px rgba(108, 76, 241, 0.15) !important;
    }

    .check-circle-pulse {
        width: 96px;
        height: 96px;
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: #ffffff;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 0 12px rgba(16, 185, 129, 0.15);
        animation: pulse-ring 2s infinite;
    }

    @keyframes pulse-ring {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 18px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
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

<div class="success-bg py-4 py-lg-5">
    <div class="container py-2" style="max-width: 860px;">
        
        <!-- SUCCESS CARD -->
        <div class="card success-card-figma p-4 p-md-5 text-center mb-5">
            <div class="mb-4">
                <div class="check-circle-pulse mb-3">
                    <i class="bi bi-check-lg display-4 fw-extrabold"></i>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5 fw-bold extra-small text-uppercase mb-2">
                    <i class="bi bi-patch-check-fill me-1"></i> Instant Digital Delivery Ready
                </span>
                <h2 class="display-6 fw-extrabold text-dark mb-2">Order Placed Successfully!</h2>
                <p class="text-secondary fs-6 mb-0" style="max-width: 520px; margin: 0 auto;">
                    Thank you for your purchase. Your payment was verified via {{ strtoupper($order->payment_method ?? 'bKash') }}. You can download your purchased digital resources below immediately.
                </p>
            </div>

            <!-- ORDER METADATA STRIP -->
            <div class="row g-3 bg-light p-3 rounded-4 mb-4 text-start border">
                <div class="col-6 col-md-3">
                    <div class="extra-small text-muted text-uppercase fw-bold">Order ID</div>
                    <div class="fw-extrabold text-dark font-monospace">#{{ $order->order_number }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="extra-small text-muted text-uppercase fw-bold">Transaction ID</div>
                    <div class="fw-bold text-primary font-monospace small">{{ $order->transaction_id ?? 'TXN-998271' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="extra-small text-muted text-uppercase fw-bold">Total Paid</div>
                    <div class="fw-extrabold text-dark">৳{{ number_format($order->total, 2) }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="extra-small text-muted text-uppercase fw-bold">Payment Status</div>
                    <div>
                        <span class="badge bg-success rounded-pill px-2.5 py-1 extra-small fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> Completed
                        </span>
                    </div>
                </div>
            </div>

            <!-- PURCHASED RESOURCES TABLE -->
            <div class="text-start mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cloud-arrow-down-fill text-primary me-2"></i>Purchased Assets (Ready for Download)</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 border rounded-3">
                        <thead class="bg-light text-uppercase extra-small text-muted fw-bold">
                            <tr>
                                <th>Asset Preview</th>
                                <th>Resource Title</th>
                                <th>Format</th>
                                <th class="text-end">Instant Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                @php $res = $item->resource; @endphp
                                @if($res)
                                    <tr>
                                        <td style="width: 70px;">
                                            <div class="rounded-3 overflow-hidden" style="width: 54px; height: 40px; background: #1E1B4B;">
                                                @if($res->preview_image)
                                                    <img src="{{ asset('storage/' . $res->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $res->title }}">
                                                @else
                                                    <div class="w-100 h-100 card-grad-1 p-1 text-white text-center"><i class="bi bi-box"></i></div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="fw-bold text-dark mb-0 small">{{ $res->title }}</h6>
                                            <span class="extra-small text-muted">{{ $res->category ? $res->category->name : 'General' }}</span>
                                        </td>
                                        <td class="small font-monospace text-uppercase text-muted">
                                            {{ $res->file_type ?? 'ZIP' }}
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('resource.download', $res->id) }}" class="btn btn-purple-cta rounded-pill btn-sm px-3.5 fw-bold">
                                                <i class="bi bi-download me-1"></i> Download Asset
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center gap-3">
                <a href="{{ route('home') }}#templates" class="btn btn-purple-cta rounded-pill px-4 py-2.5 fw-bold">
                    <i class="bi bi-shop me-1"></i> Continue Browsing
                </a>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2.5 fw-bold">
                    <i class="bi bi-receipt me-1"></i> View Order History
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
