<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Resource;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BuyerDashboardController extends Controller
{
    /**
     * Display the Buyer Dashboard with downloads, orders, wishlist, cart, and recommendations.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        // 1. Customer Orders
        $orders = Order::where('user_id', $user->id)
            ->with(['items.resource.category', 'items.resource.owner'])
            ->latest()
            ->get();

        // 2. Downloaded Resources from Orders
        $orderDownloads = Order::where('user_id', $user->id)
            ->with(['items.resource.category', 'items.resource.owner'])
            ->latest()
            ->get()
            ->pluck('items')
            ->flatten()
            ->pluck('resource')
            ->filter();

        // 3. User's Real Database Wishlist Items
        $userWishlists = Wishlist::where('user_id', $user->id)
            ->with(['resource.category', 'resource.owner'])
            ->latest()
            ->get();

        $wishlistItems = $userWishlists->pluck('resource')->filter();

        // 4. User's Real Cart Count
        $cartCount = Cart::where('user_id', $user->id)->count();

        // 5. Recently Viewed Resources
        $recentlyViewed = Resource::where('status', 'approved')
            ->with(['category', 'owner'])
            ->latest()
            ->take(6)
            ->get();

        // 6. Recommended Marketplace Resources
        $recommendedResources = Resource::where('status', 'approved')
            ->with(['category', 'owner'])
            ->orderByDesc('downloads')
            ->take(4)
            ->get();

        // 7. Metric Counters
        $ordersCount = $orders->count();
        $downloadsCount = $orderDownloads->count();
        $wishlistCount = $wishlistItems->count();
        $savedCount = $wishlistCount;

        return view('buyer.dashboard', compact(
            'user',
            'orders',
            'orderDownloads',
            'wishlistItems',
            'cartCount',
            'recentlyViewed',
            'recommendedResources',
            'ordersCount',
            'downloadsCount',
            'wishlistCount',
            'savedCount'
        ));
    }
}
