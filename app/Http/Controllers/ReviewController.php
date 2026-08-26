<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Resource;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Display all reviews for a specific resource.
     */
    public function index(Resource $resource): View
    {
        $reviews = $resource->reviews()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('resource.show', compact('resource', 'reviews'));
    }

    /**
     * Store a newly created review in database for a purchased resource.
     */
    public function store(Request $request, Resource $resource): RedirectResponse
    {
        $user = auth()->user();

        // 1. Verify Purchase Rule for Paid resources
        if ($resource->is_paid) {
            $hasPurchased = Order::where('user_id', $user->id)
                ->where('payment_status', 'completed')
                ->whereHas('items', function ($query) use ($resource) {
                    $query->where('resource_id', $resource->id);
                })
                ->exists();

            if (!$hasPurchased) {
                return redirect()->back()
                    ->with('warning', 'Only verified buyers who purchased this template can post a review.');
            }
        }

        // 2. Prevent duplicate reviews
        $alreadyReviewed = Review::where('user_id', $user->id)
            ->where('resource_id', $resource->id)
            ->exists();

        if ($alreadyReviewed) {
            return redirect()->back()
                ->with('warning', 'You have already submitted a review for this template.');
        }

        // 3. Validation
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'rating.required' => 'Please select a star rating between 1 and 5.',
            'rating.min' => 'Star rating must be at least 1 star.',
            'review.required' => 'Please write your review feedback.',
            'review.min' => 'Review text must be at least 5 characters.',
        ]);

        // 4. Save Review
        Review::create([
            'user_id' => $user->id,
            'resource_id' => $resource->id,
            'rating' => $validated['rating'],
            'review' => $validated['review'],
        ]);

        return redirect()->back()
            ->with('success', '⭐ Thank you! Your review has been submitted successfully.');
    }
}
