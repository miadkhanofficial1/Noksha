<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Resource;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResourceController extends Controller
{
    /**
     * Show the contributor design upload form.
     * Redirects to the Unified Dashboard Top-Tabs embedded upload form.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('dashboard', ['tab' => 'upload']);
    }

    /**
     * Store a newly created design resource with flexible digital asset file support.
     */
    public function store(Request $request): RedirectResponse
    {
        return app(AssetController::class)->store($request);
    }

    /**
     * Display details of a specific resource along with purchase verification & ratings.
     */
    public function show(string $slugOrId): View
    {
        $resource = Resource::where('slug', $slugOrId)
            ->orWhere('id', $slugOrId)
            ->with(['owner', 'category', 'reviews.user'])
            ->first();

        if (!$resource) {
            $resource = Resource::with(['owner', 'category', 'reviews.user'])->latest()->first();
        }

        $hasPurchased = false;
        $hasReviewed = false;

        if ($resource) {
            $resource->increment('views');

            $relatedResources = Resource::where('status', 'approved')
                ->where('id', '!=', $resource->id)
                ->where('category_id', $resource->category_id)
                ->take(3)
                ->get();

            if (auth()->check()) {
                $hasPurchased = auth()->user()->isAdmin() || !$resource->is_paid || Order::where('user_id', auth()->id())
                    ->where('payment_status', 'completed')
                    ->whereHas('items', function ($query) use ($resource) {
                        $query->where('resource_id', $resource->id);
                    })
                    ->exists();

                $hasReviewed = Review::where('user_id', auth()->id())
                    ->where('resource_id', $resource->id)
                    ->exists();
            }

            $reviews = $resource->reviews()->with('user')->latest()->get();
            $totalReviews = $resource->reviews()->count();
            $avgRating = $totalReviews > 0 ? round($resource->reviews()->avg('rating'), 1) : 0.0;

            // Star distribution percentages
            $starBreakdown = [];
            for ($i = 5; $i >= 1; $i--) {
                $count = $resource->reviews()->where('rating', $i)->count();
                $pct = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
                $starBreakdown[$i] = [
                    'count' => $count,
                    'percentage' => $pct,
                ];
            }

            return view('resource.show', compact(
                'resource',
                'relatedResources',
                'hasPurchased',
                'hasReviewed',
                'reviews',
                'totalReviews',
                'avgRating',
                'starBreakdown'
            ));
        }

        abort(404, 'Design resource not found.');
    }

    /**
     * Download or stream the official Noksha Creator Upload Guidelines (PDF / Printable HTML).
     */
    public function downloadGuidelines()
    {
        $data = [
            'appName' => 'Noksha Digital Marketplace',
            'title' => 'Noksha Creator Upload Guidelines & Submission Standards',
            'version' => 'Official Guidelines',
            'date' => now()->format('F d, Y'),
        ];

        // If DomPDF package is installed in environment
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.guidelines', $data);
            return $pdf->download('Noksha-Creator-Upload-Guidelines.pdf');
        }

        // Clean, printable, self-contained HTML/PDF response
        return response()->view('pdf.guidelines', $data, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'inline; filename="Noksha-Creator-Upload-Guidelines.html"',
        ]);
    }
}
