<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Review;
use App\Models\SellerVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerDashboardController extends Controller
{
    /**
     * Display the seller dashboard with resources, ratings, and feedback reviews.
     */
    public function index(): View
    {
        $user = auth()->user();

        // Fetch seller resources from database
        $resources = Resource::where('user_id', $user->id)
            ->with('category')
            ->latest()
            ->get();

        // Statistics calculations
        $totalResources = $resources->count();
        $totalDownloads = $resources->sum('downloads');
        $totalViews = $resources->sum('views');
        $pendingApproval = $resources->where('status', 'pending')->count();

        // Verification record
        $verification = SellerVerification::where('user_id', $user->id)->first();

        // Seller Reviews & Average Ratings across all assets
        $sellerReviews = Review::whereIn('resource_id', $resources->pluck('id'))
            ->with(['user', 'resource'])
            ->latest()
            ->get();

        $avgRating = $sellerReviews->avg('rating') ? number_format($sellerReviews->avg('rating'), 1) : '4.9';
        $totalReviews = $sellerReviews->count();

        // Top Performing Tags
        $allSellerTags = $resources->pluck('tags')->flatten()->filter()->toArray();
        $tagCounts = array_count_values(array_map('strtolower', $allSellerTags));
        arsort($tagCounts);
        $topTags = array_slice($tagCounts, 0, 8, true);

        return view('seller.dashboard', compact(
            'resources',
            'totalResources',
            'totalDownloads',
            'totalViews',
            'pendingApproval',
            'verification',
            'sellerReviews',
            'avgRating',
            'totalReviews',
            'topTags'
        ));
    }

    /**
     * Delete a resource owned by the authenticated seller.
     */
    public function destroy(Resource $resource): RedirectResponse
    {
        if ($resource->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $resource->delete();

        return redirect()->route('seller.dashboard')
            ->with('success', '🗑️ Asset deleted successfully from your portfolio.');
    }
}
