<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminResourceController extends Controller
{
    /**
     * Display the Admin Resource Moderation Center with tabs, search, and category filters.
     */
    public function index(Request $request): View
    {
        $query = Resource::with(['owner', 'category']);

        // Tab Filter: Status
        $activeTab = $request->query('status', 'all');
        if (in_array($activeTab, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $activeTab);
        }

        // Category Filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search Filter (Title, Seller Name/Email, or Tags)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('owner', function ($qUser) use ($search) {
                      $qUser->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('category', function ($qCat) use ($search) {
                      $qCat->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $resources = $query->latest()->paginate(15)->withQueryString();

        // Statistics
        $totalCount = Resource::count();
        $pendingCount = Resource::where('status', 'pending')->count();
        $approvedCount = Resource::where('status', 'approved')->count();
        $rejectedCount = Resource::where('status', 'rejected')->count();
        $categories = Category::all();

        return view('admin.resources.index', compact(
            'resources',
            'categories',
            'activeTab',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Approve a resource and publish it to the marketplace.
     */
    public function approve(Resource $resource): RedirectResponse
    {
        $resource->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        Notification::send(
            $resource->user_id,
            '✨ Asset Approved and Published!',
            "Great news! Your resource \"{$resource->title}\" was approved by moderation and is now live on Noksha Marketplace.",
            'seller',
            route('resource.show', $resource->slug)
        );

        return redirect()->back()
            ->with('success', "✨ Asset \"{$resource->title}\" has been APPROVED and published!");
    }

    /**
     * Reject a resource with a detailed feedback reason.
     */
    public function reject(Request $request, Resource $resource): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $resource->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        Notification::send(
            $resource->user_id,
            '⚠️ Asset Submission Declined',
            "Your resource \"{$resource->title}\" was declined: " . $validated['rejection_reason'],
            'warning',
            route('seller.dashboard')
        );

        return redirect()->back()
            ->with('success', "🚫 Asset \"{$resource->title}\" has been REJECTED with feedback recorded.");
    }

    /**
     * Admin Free Download: Bypasses all payment and verification checks to download actual asset.
     */
    public function download(Resource $resource): StreamedResponse|RedirectResponse
    {
        $filePath = $resource->file_path;

        // Check if file exists in public disk
        if (Storage::disk('public')->exists($filePath)) {
            $fileName = $resource->slug . '.' . ($resource->file_type ?: 'zip');
            return Storage::disk('public')->download($filePath, $fileName);
        }

        // Check local disk fallback
        if (Storage::disk('local')->exists($filePath)) {
            $fileName = $resource->slug . '.' . ($resource->file_type ?: 'zip');
            return Storage::disk('local')->download($filePath, $fileName);
        }

        return redirect()->back()
            ->with('error', "Asset package file not found on disk at storage path: {$filePath}");
    }

    /**
     * Permanently remove resource file and database record.
     */
    public function destroy(Resource $resource): RedirectResponse
    {
        $title = $resource->title;

        // Delete physical files from public storage
        if ($resource->file_path && Storage::disk('public')->exists($resource->file_path)) {
            Storage::disk('public')->delete($resource->file_path);
        }
        if ($resource->preview_image && Storage::disk('public')->exists($resource->preview_image)) {
            Storage::disk('public')->delete($resource->preview_image);
        }

        $resource->forceDelete();

        return redirect()->back()
            ->with('success', "🗑️ Asset \"{$title}\" and associated files were permanently deleted.");
    }
}
