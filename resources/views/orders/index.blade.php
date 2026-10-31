@extends('layouts.app')

@section('title', 'My Orders - Noksha')

@section('content')

<!-- CUSTOM ORDERS INDEX STYLES (DARK THEME) -->
<style>
    .orders-bg {
        background: #020617; /* slate-950 */
        min-height: 100vh;
        color: #F8FAFC;
    }

    .orders-card-figma {
        background: rgba(15, 23, 42, 0.85) !important;
        border-radius: 1.25rem !important;
        border: 1px solid rgba(148, 163, 184, 0.15) !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
    }

    .table-orders {
        color: #F8FAFC;
        margin-bottom: 0;
    }

    .table-orders th {
        background: transparent;
        color: #94A3B8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: 1px solid rgba(148, 163, 184, 0.15);
        padding: 0.85rem 1rem;
    }

    .table-orders td {
        background: transparent;
        color: #E2E8F0;
        font-size: 0.875rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(148, 163, 184, 0.08);
        padding: 1rem;
    }

    .table-orders tr:hover td {
        background: rgba(255, 255, 255, 0.02);
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
</style>

<div class="orders-bg py-4 py-lg-5">
    <div class="container py-2">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-slate-400 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-indigo-400 hover:text-indigo-300"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('buyer.dashboard') }}" class="text-decoration-none text-indigo-400 hover:text-indigo-300">Buyer Dashboard</a></li>
                <li class="breadcrumb-item active text-slate-200" aria-current="page">My Orders</li>
            </ol>
        </nav>

        <!-- PAGE HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(16, 185, 129, 0.15); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.3);">
                    <i class="bi bi-receipt me-1"></i> Order Receipts
                </span>
                <h2 class="display-6 fw-extrabold text-white mt-1 mb-0">My Order History</h2>
            </div>
            <span class="small fw-bold text-slate-400 font-monospace">{{ $orders->count() }} total orders</span>
        </div>

        @if($orders->count() > 0)
            <div class="card orders-card-figma p-4 mb-4">
                <div class="table-responsive">
                    <table class="table table-orders align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Payment Status</th>
                                <th>Order Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <div class="fw-extrabold text-white font-monospace">#{{ $order->order_number }}</div>
                                        <div class="extra-small text-slate-400 font-monospace">{{ $order->transaction_id ?? 'TXN-Direct' }}</div>
                                    </td>
                                    <td class="small text-slate-400 font-monospace">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="fw-extrabold text-emerald-400 font-monospace">
                                        ৳{{ number_format($order->total, 2) }}
                                    </td>
                                    <td>
                                        @if($order->payment_status === 'completed')
                                            <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-25 rounded-pill px-3 py-1 fw-bold extra-small">
                                                <i class="bi bi-check-circle-fill me-1"></i> Completed
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-25 rounded-pill px-3 py-1 fw-bold extra-small">
                                                <i class="bi bi-clock-history me-1"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-3 py-1 fw-bold extra-small" style="background: rgba(99, 102, 241, 0.18); color: #C4B5FD; border: 1px solid rgba(99, 102, 241, 0.3);">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 text-slate-300" style="border-color: rgba(148, 163, 184, 0.3);">
                                            <i class="bi bi-eye me-1"></i> Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- EMPTY STATE -->
            <div class="card border-0 shadow-lg rounded-4 p-5 text-center my-4" style="background: rgba(15, 23, 42, 0.85); border: 1px solid rgba(148, 163, 184, 0.15) !important;">
                <div class="p-4 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; background: rgba(99, 102, 241, 0.15); color: #818CF8; border: 1px solid rgba(99, 102, 241, 0.3);">
                    <i class="bi bi-receipt-cutoff fs-2"></i>
                </div>
                <h4 class="fw-extrabold text-white mb-2">No Past Orders Found</h4>
                <p class="text-slate-400 small mb-4" style="max-width: 440px; margin: 0 auto;">
                    You haven't purchased any templates or designs on Noksha yet. Start exploring our marketplace now.
                </p>
                <div>
                    <a href="{{ route('home') }}#templates" class="btn btn-purple-cta rounded-pill px-5 py-3 fw-bold">
                        <i class="bi bi-shop me-2"></i> Browse Design Templates
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection