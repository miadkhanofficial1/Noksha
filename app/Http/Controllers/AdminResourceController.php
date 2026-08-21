<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminResourceController extends Controller
{
    /**
     * Display the Admin Resource Approval Panel with search & filters.
     */
    public function index(Request $request): View
    {
        $query = Resource::with(['owner', 'category']);

        // Filter by Status if specified
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        // Search Keyword (Title, Seller Name, or Category Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('owner', function ($qUser) use ($search) {
                      $qUser->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('category', function ($qCat) use ($search) {
                      $qCat->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $resources = $query->latest()->paginate(10)->withQueryString();

        // Calculate Overview Statistics
        $pendingCount = Resource::where('status', 'pending')->count();
        $approvedCount = Resource::where('status', 'approved')->count();
        $rejectedCount = Resource::where('status', 'rejected')->count();
        $totalDownloads = Resource::sum('downloads');

        return view('admin.resources.index', compact(
            'resources',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalDownloads'
        ));
    }

    /**
     * Approve a resource and mark it live for the marketplace.
     */
    public function approve(Resource $resource): RedirectResponse
    {
        $resource->update([
            'status' => 'approved',
        ]);

        return redirect()->back()
            ->with('success', '✨ Asset "' . $resource->title . '" has been APPROVED and is now live on the marketplace!');
    }

    /**
     * Reject a pending resource.
     */
    public function reject(Resource $resource): RedirectResponse
    {
        $resource->update([
            'status' => 'rejected',
        ]);

        return redirect()->back()
            ->with('success', '🚫 Asset "' . $resource->title . '" has been REJECTED.');
    }
}
