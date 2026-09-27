<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Resource;
use App\Models\Review;
use App\Models\SellerVerification;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Unified Top-Tabs User & Seller Dashboard (/dashboard).
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        // ==========================================
        // 1. BUYER & GENERAL USER DATA
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

        $ordersCount = $orders->count();
        $downloadsCount = $orderDownloads->count();
        $wishlistCount = $wishlistItems->count();

        // ==========================================
        // 2. CONTRIBUTOR / SELLER STUDIO DATA
        // ==========================================
        $contributorResources = Resource::where('user_id', $user->id)
            ->with('category')
            ->latest()
            ->get();

        $totalResources = $contributorResources->count();
        $totalDownloads = (int) $contributorResources->sum('downloads');
        $totalViews = (int) $contributorResources->sum('views');
        $pendingApproval = $contributorResources->where('status', 'pending')->count();
        $approvedResources = $contributorResources->where('status', 'approved')->count();
        $rejectedResources = $contributorResources->where('status', 'rejected')->count();

        $sellerResourceIds = $contributorResources->pluck('id');

        // Sales and Earnings (50% Contributor Royalty)
        $completedSalesQuery = OrderItem::whereIn('resource_id', $sellerResourceIds)
            ->whereHas('order', function ($q) {
                $q->where('payment_status', 'completed');
            })
            ->with(['order.user', 'resource'])
            ->latest();

        $completedSales = $completedSalesQuery->get();
        $totalSales = $completedSales->count();
        $grossSales = (float) $completedSales->sum('price');
        $totalEarnings = $grossSales * 0.50; // 50.00% royalty
        $recentSales = $completedSales->take(15);

        // Verification application record
        $verification = SellerVerification::where('user_id', $user->id)->first();

        // Seller Reviews
        $sellerReviews = Review::whereIn('resource_id', $sellerResourceIds)
            ->with(['user', 'resource'])
            ->latest()
            ->get();

        $avgRating = $sellerReviews->avg('rating') ? number_format($sellerReviews->avg('rating'), 1) : '5.0';
        $totalReviews = $sellerReviews->count();

        // Categories for embedded upload form
        $categories = Category::all();

        // Top Performing AI Tags
        $allTags = $contributorResources->pluck('tags')->flatten()->filter()->toArray();
        $tagCounts = array_count_values(array_map('strtolower', $allTags));
        arsort($tagCounts);
        $topTags = array_slice($tagCounts, 0, 8, true);

        $totalSpent = (float) $orders->where('payment_status', 'completed')->sum('total');
        $isUserAdmin = $user->isAdmin();
        $isUserContributor = $user->isContributor();
        $isUserKycPending = $user->isKycPending();
        $isUserKycRejectedInCooldown = $user->isKycRejectedInCooldown();
        $kycCooldownDate = $user->getKycCooldownRemainingDate();
        $kycRejectionReason = $user->getKycRejectionReason();
        $userBadgeLabel = $user->getRoleBadgeLabel();

        // Active mode (seller or buyer)
        $tabParam = $request->query('tab');
        $defaultMode = ($isUserAdmin || $isUserContributor) ? 'seller' : 'buyer';
        if (in_array($tabParam, ['upload', 'designs', 'wallet'])) {
            $initialMode = 'seller';
        } else {
            $initialMode = $request->query('mode', $defaultMode);
        }

        return view('dashboard', compact(
            'user',
            'tabParam',
            'orders',
            'orderDownloads',
            'wishlistItems',
            'cartCount',
            'ordersCount',
            'downloadsCount',
            'wishlistCount',
            'totalSpent',
            'contributorResources',
            'totalResources',
            'totalDownloads',
            'totalViews',
            'pendingApproval',
            'approvedResources',
            'rejectedResources',
            'totalSales',
            'grossSales',
            'totalEarnings',
            'recentSales',
            'verification',
            'sellerReviews',
            'avgRating',
            'totalReviews',
            'categories',
            'topTags',
            'initialMode',
            'isUserAdmin',
            'isUserContributor',
            'isUserKycPending',
            'isUserKycRejectedInCooldown',
            'kycCooldownDate',
            'kycRejectionReason',
            'userBadgeLabel'
        ));
    }

    /**
     * Submit a payout withdrawal request (Minimum threshold: 1,000 BDT).
     */
    public function requestPayout(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $sellerResourceIds = Resource::where('user_id', $user->id)->pluck('id');

        $grossSales = (float) OrderItem::whereIn('resource_id', $sellerResourceIds)
            ->whereHas('order', function ($q) {
                $q->where('payment_status', 'completed');
            })->sum('price');

        $availableBalance = $grossSales * 0.50;

        // Enforce strict 1,000 BDT minimum withdrawal threshold
        if ($availableBalance < 1000) {
            return redirect()->to(route('dashboard') . '#wallet')
                ->with('error', 'Minimum balance for payout is 1,000 BDT.');
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1000', 'max:' . $availableBalance],
            'payment_method' => ['required', 'in:bkash,nagad,bank'],
            'account_type' => ['nullable', 'string', 'in:personal,merchant'],
            'phone' => ['required_if:payment_method,bkash,nagad', 'nullable', 'string', 'max:25'],
            'bank_name' => ['required_if:payment_method,bank', 'nullable', 'string', 'max:100'],
            'account_name' => ['required_if:payment_method,bank', 'nullable', 'string', 'max:100'],
            'account_number' => ['required_if:payment_method,bank', 'nullable', 'string', 'max:50'],
            'routing_number' => ['nullable', 'string', 'max:50'],
        ], [
            'amount.min' => 'Minimum payout amount is 1,000 BDT.',
            'amount.max' => 'Insufficient available balance.',
            'payment_method.required' => 'Please select a payment gateway.',
            'phone.required_if' => 'Phone number is required.',
            'bank_name.required_if' => 'Bank name is required.',
            'account_name.required_if' => 'Account holder name is required.',
            'account_number.required_if' => 'Account number is required.',
        ]);

        $gatewayName = strtoupper($validated['payment_method']);

        // Send confirmation notification to Contributor
        Notification::send(
            $user->id,
            'Payout Request Submitted',
            "Your payout request for ৳" . number_format($validated['amount'], 2) . " via {$gatewayName} has been received and will be processed within 24-48 hours.",
            'seller',
            route('dashboard') . '#wallet'
        );

        // Send alert notification to Administrators
        $adminUsers = User::whereIn('role', ['admin', 'super_admin'])->get();
        foreach ($adminUsers as $admin) {
            Notification::send(
                $admin->id,
                'New Payout Request',
                "Contributor {$user->name} requested ৳" . number_format($validated['amount'], 2) . " via {$gatewayName}",
                'admin',
                route('admin.payouts.index')
            );
        }

        return redirect()->to(route('dashboard') . '#wallet')
            ->with('success', 'Payout request submitted successfully! Funds will be disbursed within 24-48 hours.');
    }

    /**
     * Delete a design resource owned by the authenticated contributor.
     */
    public function destroyResource(Resource $resource): RedirectResponse
    {
        if ($resource->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $resource->delete();

        return redirect()->to(route('dashboard') . '#designs')
            ->with('success', 'Design deleted successfully from your portfolio.');
    }
}
