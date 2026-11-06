@extends('layouts.app')

@section('title', 'Order Details #' . $order->order_number . ' - Noksha')

@section('content')

<div class="bg-white min-h-screen text-[#121826] py-6 sm:py-10">
    <div class="container" style="max-width: 960px;">
        
        <!-- 1. BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb text-xs uppercase font-medium text-slate-500 mb-0 flex items-center gap-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-slate-500 hover:text-[#20165c] font-medium text-decoration-none flex items-center gap-1">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('orders.index') }}" class="text-slate-500 hover:text-[#20165c] font-medium text-decoration-none">
                        Orders
                    </a>
                </li>
                <li class="breadcrumb-item active text-slate-700 font-semibold" aria-current="page">
                    Order #{{ $order->order_number }}
                </li>
            </ol>
        </nav>

        <!-- PAGE HEADER -->
        <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
            <div>
                <span class="bg-purple-50 text-[#20165c] border border-purple-200 rounded-full px-3 py-1 text-xs font-semibold inline-flex items-center gap-1.5 mb-1.5">
                    <i class="bi bi-receipt"></i> Receipt Inspector
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#121826] tracking-tight mb-0">Order Details</h1>
            </div>
            <a href="{{ route('orders.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 rounded-xl px-4 py-2 font-medium transition text-sm flex items-center gap-1.5 text-decoration-none shadow-xs">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Orders</span>
            </a>
        </div>

        <!-- 2. ORDER METADATA SUMMARY BOX -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Order Number</div>
                <div class="font-semibold text-[#121826] font-mono text-base">#{{ $order->order_number }}</div>
            </div>
            <div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Transaction ID</div>
                <div class="font-semibold text-[#20165c] font-mono text-sm inline-flex items-center px-2.5 py-0.5 rounded-md bg-purple-50 border border-purple-200">
                    {{ $order->transaction_id ?? 'TXN-Direct' }}
                </div>
            </div>
            <div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Payment Method</div>
                <div class="font-semibold text-[#121826] text-capitalize text-sm">{{ $order->payment_method ?? 'bKash' }}</div>
            </div>
            <div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Order Date</div>
                <div class="font-semibold text-[#121826] font-mono text-sm">{{ $order->created_at->format('M d, Y') }}</div>
            </div>
        </div>

        <!-- 3. PURCHASED DIGITAL RESOURCES TABLE CONTAINER -->
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm mt-6">
            <div class="p-5 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-base font-bold text-[#121826] mb-0 flex items-center gap-2">
                    <i class="bi bi-box-seam text-[#20165c]"></i>
                    <span>Purchased Digital Resources</span>
                </h3>
                <span class="text-xs font-semibold text-slate-500">
                    {{ $order->items->count() }} {{ Str::plural('Item', $order->items->count()) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs font-semibold uppercase tracking-wider">
                            <th class="py-3.5 px-5">Resource Item</th>
                            <th class="py-3.5 px-4">Category</th>
                            <th class="py-3.5 px-4">Format</th>
                            <th class="py-3.5 px-4">Price</th>
                            <th class="py-3.5 px-5 text-right">Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            @php 
                                $res = $item->resource; 
                                $thumbUrl = null;
                                if ($res) {
                                    if (!empty($res->preview_image)) {
                                        if (str_starts_with($res->preview_image, 'http://') || str_starts_with($res->preview_image, 'https://')) {
                                            $thumbUrl = $res->preview_image;
                                        } else {
                                            $thumbUrl = asset('storage/' . ltrim(str_replace('storage/', '', $res->preview_image), '/'));
                                        }
                                    } elseif (!empty($res->thumbnail_url)) {
                                        $thumbUrl = $res->thumbnail_url;
                                    } else {
                                        $thumbUrl = '/images/placeholder-asset.png';
                                    }
                                }
                            @endphp
                            @if($res)
                                <tr class="bg-white border-b border-slate-100 hover:bg-slate-50/50 transition">
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $thumbUrl }}" 
                                                 alt="{{ $res->title }}" 
                                                 class="w-12 h-12 rounded-xl object-cover border border-slate-200 shadow-xs shrink-0" 
                                                 onerror="this.src='/images/placeholder-asset.png'; this.onerror=null;">
                                            <div class="min-w-0">
                                                <a href="{{ route('resource.show', $res->slug ?? $res->id) }}" class="font-bold text-[#121826] hover:text-[#20165c] text-sm block truncate text-decoration-none" title="{{ $res->title }}">
                                                    {{ $res->title }}
                                                </a>
                                                <div class="text-xs text-slate-500 mt-0.5">by {{ $res->owner ? $res->owner->name : 'Noksha Creator' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <span class="bg-slate-100 text-slate-700 rounded-md px-2.5 py-1 text-xs font-medium">
                                            {{ $res->category ? $res->category->name : 'General' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <span class="bg-purple-50 text-[#20165c] border border-purple-200 rounded-md px-2 py-0.5 text-xs font-bold uppercase">
                                            {{ $res->file_type ?? 'ZIP' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <span class="font-bold text-[#121826] font-mono text-sm">
                                            {{ $res->is_paid && $res->price > 0 ? '৳' . number_format($res->price, 2) : 'Free' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        <div class="flex justify-end">
                                            <a href="{{ route('resource.download', $res->id) }}" class="bg-[#20165c] hover:bg-[#181045] text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-xs inline-flex items-center gap-2 transition text-decoration-none">
                                                <i class="bi bi-cloud-arrow-down-fill"></i>
                                                <span>Download</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- 4. TOTAL PAID & STATUS FOOTER -->
            <div class="p-5 bg-slate-50 border-t border-slate-200 flex items-center justify-between flex-wrap gap-4">
                <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1.5 rounded-full text-xs font-medium flex items-center gap-1.5">
                    <i class="bi bi-patch-check-fill text-emerald-600"></i>
                    <span>Order Payment Verified & Completed</span>
                </div>
                <div class="flex items-center">
                    <span class="text-slate-600 font-medium text-sm">TOTAL PAID:</span>
                    <span class="text-[#f47402] text-2xl font-black ml-2 font-mono">৳{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
