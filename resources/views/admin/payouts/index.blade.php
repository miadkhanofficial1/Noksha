@extends('layouts.admin')

@section('title', 'Financials & Payouts - Noksha Admin HQ')
@section('page_title', 'Financials')
@section('page_heading', 'Financials & Creator Payouts Ledger')

@section('content')
<div class="space-y-6">

    <!-- FINANCIAL STATS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- GMV -->
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-5 border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gross Merchandise Value</span>
                <div class="w-9 h-9 rounded-xl bg-teal-500/10 text-teal-500 flex items-center justify-center">
                    <i class="bi bi-currency-exchange text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-gray-900 dark:text-white font-mono">৳{{ number_format($totalGmv, 2) }}</div>
                <div class="text-[11px] text-gray-500 mt-1">{{ number_format($completedOrdersCount) }} settled transactions</div>
            </div>
        </div>

        <!-- Platform Fee -->
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-5 border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-brand-500 uppercase tracking-wider">Platform Take (20%)</span>
                <div class="w-9 h-9 rounded-xl bg-brand-500/10 text-brand-500 flex items-center justify-center">
                    <i class="bi bi-pie-chart-fill text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-brand-600 dark:text-brand-400 font-mono">৳{{ number_format($platformFee, 2) }}</div>
                <div class="text-[11px] text-gray-500 mt-1">Noksha Marketplace Net Revenue</div>
            </div>
        </div>

        <!-- Creator Payouts -->
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-5 border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-emerald-500 uppercase tracking-wider">Creator Earnings (80%)</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                    <i class="bi bi-wallet2 text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono">৳{{ number_format($sellerPayouts, 2) }}</div>
                <div class="text-[11px] text-gray-500 mt-1">Payable to sellers & contributors</div>
            </div>
        </div>

        <!-- Order Volume -->
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-5 border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-sky-500 uppercase tracking-wider">Order Volume</span>
                <div class="w-9 h-9 rounded-xl bg-sky-500/10 text-sky-500 flex items-center justify-center">
                    <i class="bi bi-bag-check-fill text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-gray-900 dark:text-white font-mono">{{ number_format($completedOrdersCount + $pendingOrdersCount) }}</div>
                <div class="text-[11px] text-gray-500 mt-1">{{ number_format($pendingOrdersCount) }} processing</div>
            </div>
        </div>

    </div>

    <!-- ROW: TOP EARNING CREATORS & SEARCH -->
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                    <i class="bi bi-cash-coin text-base"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">Top Creator Revenue Distribution</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">80/20 revenue split ledger across active sellers</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 dark:bg-gray-800/40 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-5 py-3.5">Creator / Seller</th>
                        <th class="px-4 py-3.5">Designs Published</th>
                        <th class="px-4 py-3.5">Gross Sales Generated</th>
                        <th class="px-4 py-3.5">Platform Share (20%)</th>
                        <th class="px-4 py-3.5">Creator Share (80%)</th>
                        <th class="px-5 py-3.5 text-right">Payout Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($topSellers as $seller)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $seller->name }}</div>
                                <div class="text-[11px] text-gray-400 font-mono">{{ $seller->email }}</div>
                            </td>
                            <td class="px-4 py-3.5 font-mono text-gray-700 dark:text-gray-300">
                                {{ $seller->resources_count }} templates
                            </td>
                            <td class="px-4 py-3.5 font-mono font-bold text-gray-900 dark:text-white">
                                ৳{{ number_format($seller->gross_sales, 2) }}
                            </td>
                            <td class="px-4 py-3.5 font-mono text-brand-600 dark:text-brand-400 font-semibold">
                                ৳{{ number_format($seller->platform_cut, 2) }}
                            </td>
                            <td class="px-4 py-3.5 font-mono text-emerald-600 dark:text-emerald-400 font-bold">
                                ৳{{ number_format($seller->net_payout, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                    Eligible
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gray-500">No seller earnings recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- RECENT TRANSACTIONS TABLE -->
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between flex-wrap gap-2">
            <h3 class="font-bold text-sm text-gray-900 dark:text-white">Platform Orders & Transactions Ledger</h3>
            
            <form method="GET" action="{{ route('admin.payouts.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Order # or Customer..." class="px-3 py-1.5 text-xs rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white">
                <button type="submit" class="px-3 py-1.5 rounded-xl bg-brand-600 text-white font-bold text-xs">Search</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 dark:bg-gray-800/40 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-5 py-3.5">Order Number</th>
                        <th class="px-4 py-3.5">Customer</th>
                        <th class="px-4 py-3.5">Date</th>
                        <th class="px-4 py-3.5">Order Total</th>
                        <th class="px-4 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-5 py-3.5 font-mono font-bold text-brand-600 dark:text-brand-400">
                                #{{ $order->order_number }}
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $order->user->name ?? 'Guest/Buyer' }}</div>
                                <div class="text-[11px] text-gray-400">{{ $order->user->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3.5 text-gray-500">
                                {{ $order->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-4 py-3.5 font-mono font-bold text-gray-900 dark:text-white">
                                ৳{{ number_format($order->total, 2) }}
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $order->payment_status === 'completed' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20' }}">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-gray-500">No transactions recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
