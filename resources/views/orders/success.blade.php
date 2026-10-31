@extends('layouts.app')

@section('title', 'Order Placed Successfully - Noksha')

@section('content')

<!-- CUSTOM SUCCESS PAGE STYLES (DARK THEME) -->
<style>
    .success-bg {
        background: #020617; /* slate-950 */
        min-height: 100vh;
        color: #F8FAFC;
    }

    .success-card-figma {
        background: rgba(15, 23, 42, 0.85) !important;
        border-radius: 1.5rem !important;
        border: 1px solid rgba(148, 163, 184, 0.15) !important;
        box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.5) !important;
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

    .table-success-orders {
        color: #F8FAFC;
        margin-bottom: 0;
    }

    .table-success-orders th {
        background: transparent;
        color: #94A3B8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: 1px solid rgba(148, 163, 184, 0.15);
        padding: 0.85rem 1rem;
    }

    .table-success-orders td {
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

<div class="success-bg py-4 py-lg-5">
    <div class="container py-2" style="max-width: 860px;">
        
        <!-- SUCCESS CARD -->
        <div class="card success-card-figma p-4 p-md-5 text-center mb-5">
            <div class="mb-4">
                <div class="check-circle-pulse mb-3">
                    <i class="bi bi-check-lg display-4 fw-extrabold"></i>
                </div>
                <span class="badge bg-success bg-opacity-20 text-success rounded-pill px-3 py-1.5 fw-bold extra-small text-uppercase mb-2 border border-success border-opacity-25">
                    <i class="bi bi-patch-check-fill me-1"></i> Instant Digital Delivery Ready
                </span>
                <h2 class="display-6 fw-extrabold text-white mb-2">Order Placed Successfully!</h2>
                <p class="text-slate-400 fs-6 mb-0" style="max-width: 520px; margin: 0 auto;">
                    Thank you for your purchase. Your payment was verified via {{ strtoupper($order->payment_method ?? 'bKash') }}. You can download your purchased digital resources below immediately.
                </p>
            </div>

            <!-- ORDER METADATA STRIP -->
            <div class="row g-3 p-3 rounded-4 mb-4 text-start border border-slate-800" style="background: rgba(11, 17, 32, 0.7);">
                <div class="col-6 col-md-3">
                    <div class="extra-small text-slate-400 text-uppercase fw-bold">Order ID</div>
                    <div class="fw-extrabold text-white font-monospace">#{{ $order->order_number }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="extra-small text-slate-400 text-uppercase fw-bold">Transaction ID</div>
                    <div class="fw-bold text-indigo-400 font-monospace small">{{ $order->transaction_id ?? 'TXN-998271' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="extra-small text-slate-400 text-uppercase fw-bold">Total Paid</div>
                    <div class="fw-extrabold text-emerald-400 font-monospace">৳{{ number_format($order->total, 2) }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="extra-small text-slate-400 text-uppercase fw-bold">Payment Status</div>
                    <div>
                        <span class="badge bg-success bg-opacity-20 text-success rounded-pill px-2.5 py-1 extra-small fw-bold border border-success border-opacity-25">
                            <i class="bi bi-check-circle-fill me-1"></i> Completed
                        </span>
                    </div>
                </div>
            </div>

            <!-- PURCHASED RESOURCES TABLE -->
            <div class="text-start mb-4">
                <h5 class="fw-bold text-white mb-3"><i class="bi bi-cloud-arrow-down-fill text-indigo-400 me-2"></i>Purchased Assets (Ready for Download)</h5>
                
                <div class="table-responsive">
                    <table class="table table-success-orders align-middle mb-0 border border-slate-800 rounded-3">
                        <thead>
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
                                            <div class="rounded-3 overflow-hidden" style="width: 54px; height: 40px; background: #0B1120; border: 1px solid rgba(148, 163, 184, 0.12);">
                                                @if($res->preview_image)
                                                    <img src="{{ asset('storage/' . $res->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $res->title }}">
                                                @else
                                                    <div class="w-100 h-100 card-grad-1 p-1 text-white text-center"><i class="bi bi-box"></i></div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="fw-bold text-white mb-0 small">{{ $res->title }}</h6>
                                            <span class="extra-small text-slate-400">{{ $res->category ? $res->category->name : 'General' }}</span>
                                        </td>
                                        <td class="small font-monospace text-uppercase text-slate-400">
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
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2.5 fw-bold text-slate-300" style="border-color: rgba(148, 163, 184, 0.25);">
                    <i class="bi bi-receipt me-1"></i> View Order History
                </a>
            </div>

        </div>

    </div>
</div>
@endsection