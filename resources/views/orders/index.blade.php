@extends('layouts.app')

@section('title', 'My Orders - Noksha (নকশা)')

@section('content')

<!-- CUSTOM ORDERS INDEX STYLES -->
<style>
    .orders-bg {
        background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%);
        min-height: 100vh;
    }

    .orders-card-figma {
        background: #ffffff;
        border-radius: 1.25rem !important;
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
</style>

<div class="orders-bg py-4 py-lg-5">
    <div class="container py-2">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-muted mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('buyer.dashboard') }}" class="text-decoration-none text-primary">Buyer Dashboard</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">My Orders</li>
            </ol>
        </nav>

        <!-- PAGE HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(16, 185, 129, 0.08); color: #10B981;">
                    <i class="bi bi-receipt me-1"></i> Order Receipts
                </span>
                <h2 class="display-6 fw-extrabold text-dark mt-1 mb-0">My Order History</h2>
            </div>
            <span class="small fw-bold text-muted font-monospace">{{ $orders->count() }} total orders</span>
        </div>

        @if($orders->count() > 0)
            <div class="card orders-card-figma p-4 mb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-uppercase extra-small text-muted fw-bold">
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
                                        <div class="fw-extrabold text-dark font-monospace">#{{ $order->order_number }}</div>
                                        <div class="extra-small text-muted font-monospace">{{ $order->transaction_id ?? 'TXN-Direct' }}</div>
                                    </td>
                                    <td class="small text-muted font-monospace">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="fw-extrabold text-dark">
                                        ৳{{ number_format($order->total, 2) }}
                                    </td>
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
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1 fw-bold extra-small">
                                            {{ ucfirst($order->order_status ?? 'Completed') }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-purple-cta rounded-pill btn-sm px-3.5 fw-bold me-1">
                                            <i class="bi bi-eye-fill me-1"></i> View Details & Download
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- EMPTY STATE FOR ORDERS -->
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4">
                <div class="p-4 bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;">
                    <i class="bi bi-receipt-cutoff fs-1"></i>
                </div>
                <h4 class="fw-extrabold text-dark mb-2">No Past Orders Found</h4>
                <p class="text-secondary small mb-4" style="max-width: 460px; margin: 0 auto;">
                    You haven't placed any orders yet. Explore our marketplace to download free design assets or purchase premium templates.
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
