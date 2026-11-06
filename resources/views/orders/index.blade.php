@extends('layouts.app')

@section('title', 'My Orders - Noksha')

@section('content')

<div class="bg-white min-h-screen text-[#121826] py-6 sm:py-10">
    <div class="container" style="max-width: 960px;">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb text-xs uppercase font-medium text-slate-500 mb-0 flex items-center gap-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-slate-500 hover:text-[#20165c] font-medium text-decoration-none flex items-center gap-1">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('buyer.dashboard') }}" class="text-slate-500 hover:text-[#20165c] font-medium text-decoration-none">
                        Buyer Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active text-slate-700 font-semibold" aria-current="page">
                    My Orders
                </li>
            </ol>
        </nav>

        <!-- PAGE HEADER -->
        <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
            <div>
                <span class="bg-purple-50 text-[#20165c] border border-purple-200 rounded-full px-3 py-1 text-xs font-semibold inline-flex items-center gap-1.5 mb-1.5">
                    <i class="bi bi-receipt"></i> Order Receipts
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#121826] tracking-tight mb-0">My Order History</h1>
            </div>
            <span class="text-xs font-semibold text-slate-500 bg-slate-100 border border-slate-200 rounded-full px-3 py-1 font-mono">
                {{ $orders->count() }} {{ Str::plural('order', $orders->count()) }}
            </span>
        </div>

        @if($orders->count() > 0)
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs font-semibold uppercase tracking-wider">
                                <th class="py-3.5 px-5">Order ID</th>
                                <th class="py-3.5 px-4">Date</th>
                                <th class="py-3.5 px-4">Total</th>
                                <th class="py-3.5 px-4">Payment Status</th>
                                <th class="py-3.5 px-4">Order Status</th>
                                <th class="py-3.5 px-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="bg-white border-b border-slate-100 hover:bg-slate-50/50 transition">
                                    <td class="py-4 px-5">
                                        <div class="font-extrabold text-[#121826] font-mono text-sm">#{{ $order->order_number }}</div>
                                        <div class="text-xs text-[#20165c] font-mono mt-0.5">{{ $order->transaction_id ?? 'TXN-Direct' }}</div>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap text-xs text-slate-600 font-mono">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <span class="font-bold text-[#f47402] font-mono text-sm">
                                            ৳{{ number_format($order->total, 2) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        @if($order->payment_status === 'completed')
                                            <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full px-2.5 py-1 text-xs font-semibold inline-flex items-center gap-1">
                                                <i class="bi bi-check-circle-fill text-emerald-600"></i> Completed
                                            </span>
                                        @else
                                            <span class="bg-amber-50 text-amber-700 border border-amber-200 rounded-full px-2.5 py-1 text-xs font-semibold inline-flex items-center gap-1">
                                                <i class="bi bi-clock-history text-amber-600"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <span class="bg-purple-50 text-[#20165c] border border-purple-200 rounded-full px-2.5 py-1 text-xs font-semibold">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        <div class="flex justify-end">
                                            <a href="{{ route('orders.show', $order->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition inline-flex items-center gap-1 text-decoration-none">
                                                <i class="bi bi-eye"></i>
                                                <span>Details</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- EMPTY STATE -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-8 text-center my-4">
                <div class="p-4 rounded-2xl inline-flex items-center justify-center mb-3 bg-purple-50 text-[#20165c] border border-purple-200" style="width: 72px; height: 72px;">
                    <i class="bi bi-receipt-cutoff fs-2"></i>
                </div>
                <h3 class="text-xl font-extrabold text-[#121826] mb-1">No Past Orders Found</h3>
                <p class="text-slate-500 text-sm mb-5" style="max-width: 440px; margin: 0 auto;">
                    You haven't purchased any templates or designs on Noksha yet. Start exploring our marketplace now.
                </p>
                <div>
                    <a href="{{ route('home') }}#templates" class="bg-[#20165c] hover:bg-[#181045] text-white font-semibold rounded-xl px-6 py-3 transition text-sm inline-flex items-center gap-2 text-decoration-none shadow-md">
                        <i class="bi bi-shop"></i>
                        <span>Browse Design Templates</span>
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
