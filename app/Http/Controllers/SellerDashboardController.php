<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerDashboardController extends Controller
{
    /**
     * Display the seller dashboard.
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
        $verification = \App\Models\SellerVerification::where('user_id', $user->id)->first();

        return view('seller.dashboard', compact(
            'resources',
            'totalResources',
            'totalDownloads',
            'totalViews',
            'pendingApproval',
            'verification'
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
