<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPayoutController extends Controller
{
    /**
     * Display Financials, Revenue & Seller Payouts Command Center.
     */
    public function index(Request $request): View
    {
        $ordersQuery = Order::with(['user', 'items.resource.owner'])->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $ordersQuery->where('order_number', 'like', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
        }

        $orders = $ordersQuery->paginate(15)->withQueryString();

        // Financial KPIs
        $totalGmv = (float) Order::where('payment_status', 'completed')->sum('total');
        $platformFee = $totalGmv * 0.20; // 20% platform cut
        $sellerPayouts = $totalGmv * 0.80; // 80% to creators
        $completedOrdersCount = Order::where('payment_status', 'completed')->count();
        $pendingOrdersCount = Order::where('payment_status', 'pending')->count();

        // Top Sellers by Revenue
        $topSellers = User::where('role', 'seller')
            ->withCount('resources')
            ->get()
            ->map(function ($seller) {
                // Calculate gross earnings from seller's resources
                $gross = (float) \App\Models\OrderItem::whereHas('resource', function ($q) use ($seller) {
                    $q->where('user_id', $seller->id);
                })->sum('price');

                $seller->gross_sales = $gross;
                $seller->net_payout = $gross * 0.80;
                $seller->platform_cut = $gross * 0.20;
                return $seller;
            })
            ->sortByDesc('gross_sales')
            ->take(10);

        return view('admin.payouts.index', compact(
            'orders',
            'totalGmv',
            'platformFee',
            'sellerPayouts',
            'completedOrdersCount',
            'pendingOrdersCount',
            'topSellers'
        ));
    }
}
