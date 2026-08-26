<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Resource;
use App\Models\Review;
use App\Models\SellerVerification;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Unified User & Contributor Dashboard (/dashboard).
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        // ==========================================
        // 1. BUYER & GENERAL USER DATA (Always Visible)
        // ==========================================
        $orders = Order::where('user_id', $user->id)
            ->with(['items.resource.category', 'items.resource.owner'])
            ->latest()
            ->get();

        $orderDownloads = $orders->pluck('items')
            ->flatten()
            ->pluck('resource')
            ->filter();

        $userWishlists = Wishlist::where('user_id', $user->id)
            ->with(['resource.category', 'resource.owner'])
            ->latest()
            ->get();

        $wishlistItems = $userWishlists->pluck('resource')->filter();
        $cartCount = Cart::where('user_id', $user->id)->count();

        $recentlyViewed = Resource::where('status', 'approved')
            ->with(['category', 'owner'])
            ->latest()
            ->take(6)
            ->get();

        $recommendedResources = Resource::where('status', 'approved')
            ->with(['category', 'owner'])
            ->orderByDesc('downloads')
            ->take(4)
            ->get();

        $ordersCount = $orders->count();
        $downloadsCount = $orderDownloads->count();
        $wishlistCount = $wishlistItems->count();

        // ==========================================
        // 2. CONTRIBUTOR DATA (For Verified Contributors)
        // ==========================================
        $isContributor = $user->isContributor();
        $contributorResources = collect();
        $totalResources = 0;
        $totalDownloads = 0;
        $totalViews = 0;
        $pendingApproval = 0;
        $sellerReviews = collect();
        $avgRating = '5.0';
        $topTags = [];

        if ($isContributor) {
            $contributorResources = Resource::where('user_id', $user->id)
                ->with('category')
                ->latest()
                ->get();

            $totalResources = $contributorResources->count();
            $totalDownloads = $contributorResources->sum('downloads');
            $totalViews = $contributorResources->sum('views');
            $pendingApproval = $contributorResources->where('status', 'pending')->count();

            $sellerReviews = Review::whereIn('resource_id', $contributorResources->pluck('id'))
                ->with(['user', 'resource'])
                ->latest()
                ->get();

            $avgRating = $sellerReviews->avg('rating') ? number_format($sellerReviews->avg('rating'), 1) : '5.0';

            $allTags = $contributorResources->pluck('tags')->flatten()->filter()->toArray();
            $tagCounts = array_count_values(array_map('strtolower', $allTags));
            arsort($tagCounts);
            $topTags = array_slice($tagCounts, 0, 8, true);
        }

        // Verification application record
        $verification = SellerVerification::where('user_id', $user->id)->first();

        return view('dashboard.index', compact(
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
            'isContributor',
            'contributorResources',
            'totalResources',
            'totalDownloads',
            'totalViews',
            'pendingApproval',
            'sellerReviews',
            'avgRating',
            'topTags',
            'verification'
        ));
    }
}
