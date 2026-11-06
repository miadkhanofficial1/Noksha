@extends('layouts.app')

@section('title', 'Order Placed Successfully - Noksha')

@section('content')

<style>
    .check-circle-pulse {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: #ffffff;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 0 10px rgba(16, 185, 129, 0.15);
        animation: pulse-ring 2s infinite;
    }

    @keyframes pulse-ring {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 16px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
</style>

<div class="bg-white min-h-screen text-[#121826] py-6 sm:py-10">
    <div class="container" style="max-width: 860px;">
        
        <!-- SUCCESS CARD -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8 text-center mb-5">
            <div class="mb-4">
                <div class="check-circle-pulse mb-3">
                    <i class="bi bi-check-lg fs-1 fw-extrabold text-white"></i>
                </div>
                <div>
                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full px-3 py-1 fw-bold text-xs uppercase mb-2 inline-flex items-center gap-1.5">
                        <i class="bi bi-patch-check-fill text-emerald-600"></i> Instant Digital Delivery Ready
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#121826] mb-2 mt-2">Order Placed Successfully!</h1>
                <p class="text-slate-500 text-sm mb-0" style="max-width: 540px; margin: 0 auto;">
                    Thank you for your purchase. Your payment was verified via {{ strtoupper($order->payment_method ?? 'bKash') }}. You can download your purchased digital resources below immediately.
                </p>
            </div>

            <!-- ORDER METADATA STRIP -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-5 text-start grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Order ID</div>
                    <div class="font-semibold text-[#121826] font-mono text-base">#{{ $order->order_number }}</div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Transaction ID</div>
                    <div class="font-semibold text-[#20165c] font-mono text-sm inline-flex items-center px-2 py-0.5 rounded-md bg-purple-50 border border-purple-200">
                        {{ $order->transaction_id ?? 'TXN-Direct' }}
                    </div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Paid</div>
                    <div class="font-bold text-[#f47402] font-mono text-lg">৳{{ number_format($order->total, 2) }}</div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Payment Status</div>
                    <div>
                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full px-2.5 py-1 text-xs font-semibold inline-flex items-center gap-1">
                            <i class="bi bi-check-circle-fill text-emerald-600"></i> Completed
                        </span>
                    </div>
                </div>
            </div>

            <!-- PURCHASED RESOURCES TABLE -->
            <div class="text-start mb-5">
                <h3 class="text-base font-bold text-[#121826] mb-3 flex items-center gap-2">
                    <i class="bi bi-cloud-arrow-down-fill text-[#20165c]"></i>
                    <span>Purchased Assets (Ready for Download)</span>
                </h3>
                
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-xs">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs font-semibold uppercase tracking-wider">
                                    <th class="py-3 px-4">Asset Preview</th>
                                    <th class="py-3 px-4">Resource Title</th>
                                    <th class="py-3 px-4">Format</th>
                                    <th class="py-3 px-4 text-right">Instant Action</th>
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
                                            <td class="py-3.5 px-4" style="width: 70px;">
                                                <img src="{{ $thumbUrl }}" 
                                                     alt="{{ $res->title }}" 
                                                     class="w-12 h-12 rounded-xl object-cover border border-slate-200 shadow-xs" 
                                                     onerror="this.src='/images/placeholder-asset.png'; this.onerror=null;">
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <div class="font-bold text-[#121826] text-sm mb-0.5">{{ $res->title }}</div>
                                                <span class="text-xs text-slate-500">{{ $res->category ? $res->category->name : 'General' }}</span>
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                <span class="bg-purple-50 text-[#20165c] border border-purple-200 rounded-md px-2 py-0.5 text-xs font-bold uppercase">
                                                    {{ $res->file_type ?? 'ZIP' }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                                <div class="flex justify-end">
                                                    <a href="{{ route('resource.download', $res->id) }}" class="bg-[#20165c] hover:bg-[#181045] text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-xs inline-flex items-center gap-2 transition text-decoration-none">
                                                        <i class="bi bi-download"></i>
                                                        <span>Download Asset</span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('home') }}#templates" class="bg-[#20165c] hover:bg-[#181045] text-white font-semibold rounded-xl px-5 py-2.5 transition text-sm flex items-center gap-2 text-decoration-none shadow-sm">
                    <i class="bi bi-shop"></i>
                    <span>Continue Browsing</span>
                </a>
                <a href="{{ route('orders.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 rounded-xl px-5 py-2.5 font-medium transition text-sm flex items-center gap-2 text-decoration-none shadow-xs">
                    <i class="bi bi-receipt"></i>
                    <span>View Order History</span>
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
