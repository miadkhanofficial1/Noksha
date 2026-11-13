<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Order;
use App\Models\PlatformReview;
use App\Models\Resource;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
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
        $reviews = PlatformReview::where('asset_id', $resource->id)
            ->with('reviewer')
            ->latest()
            ->paginate(10);

        return view('resource.show', compact('resource', 'reviews'));
    }

    /**
     * Store a newly created verified review in database for a purchased resource.
     */
    public function store(Request $request, Resource $resource): RedirectResponse|JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Please sign in to submit a review.'], 401);
            }
            return redirect()->route('login')->with('warning', 'Please sign in to submit a review.');
        }

        // Creator cannot review their own asset
        if ($resource->user_id === $user->id) {
            $msg = 'You cannot review your own digital asset.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $msg], 403);
            }
            return redirect()->back()->with('warning', $msg);
        }

        // 1. Strict Verified Purchase Rule
        $hasPurchased = Order::where('user_id', $user->id)
            ->where('payment_status', 'completed')
            ->whereHas('items', function ($query) use ($resource) {
                $query->where('resource_id', $resource->id);
            })
            ->exists();

        if (!$hasPurchased && $resource->is_paid) {
            $msg = 'Only verified buyers who purchased this template can post a review.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $msg], 403);
            }
            return redirect()->back()->with('warning', $msg);
        }

        // 2. Prevent duplicate reviews (1 Review per template purchase)
        $alreadyReviewed = PlatformReview::where('reviewer_id', $user->id)
            ->where('asset_id', $resource->id)
            ->exists() || Review::where('user_id', $user->id)
            ->where('resource_id', $resource->id)
            ->exists();

        if ($alreadyReviewed) {
            $msg = 'You have already submitted a verified review for this template.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'info', 'message' => $msg], 422);
            }
            return redirect()->back()->with('warning', $msg);
        }

        // 3. Validation
        $comment = $request->input('review') ?? $request->input('comment');
        $request->merge(['comment' => $comment]);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:5', 'max:1000'],
            'is_complaint' => ['nullable', 'boolean'],
            'evidence_images' => ['nullable', 'array', 'max:3'],
            'evidence_images.*' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ], [
            'rating.required' => 'Please select a star rating between 1 and 5.',
            'rating.min' => 'Star rating must be at least 1 star.',
            'comment.required' => 'Please write your review feedback.',
            'comment.min' => 'Review text must be at least 5 characters.',
        ]);

        // 4. Handle Evidence Images
        $evidencePaths = [];
        if ($request->hasFile('evidence_images')) {
            $files = $request->file('evidence_images');
            if (!is_array($files)) {
                $files = [$files];
            }
            foreach (array_slice($files, 0, 3) as $file) {
                if ($file && $file->isValid()) {
                    $evidencePaths[] = $file->store('review_evidence', 'public');
                }
            }
        }

        $isComplaint = $request->boolean('is_complaint');

        // 5. Save PlatformReview
        $platformReview = PlatformReview::create([
            'reviewer_id' => $user->id,
            'creator_id' => $resource->user_id,
            'asset_id' => $resource->id,
            'rating' => (int) $validated['rating'],
            'comment' => trim($validated['comment']),
            'is_complaint' => $isComplaint,
            'evidence_images' => $evidencePaths,
            'admin_resolved' => false,
        ]);

        // 6. Backwards compatibility: mirror into legacy Review table
        Review::firstOrCreate([
            'user_id' => $user->id,
            'resource_id' => $resource->id,
        ], [
            'rating' => (int) $validated['rating'],
            'review' => trim($validated['comment']),
        ]);

        if ($isComplaint) {
            Notification::create([
                'user_id' => $resource->user_id,
                'title' => '⚠️ Buyer Complaint Logged',
                'message' => "A customer flagged an issue with template \"{$resource->title}\". Admin moderation team notified.",
                'type' => 'system',
                'action_url' => route('resource.show', $resource->slug ?? $resource->id),
            ]);
        }

        $successMessage = '⭐ Thank you! Your verified review has been published.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $successMessage,
                'review' => $platformReview,
            ]);
        }

        return redirect()->back()->with('success', $successMessage);
    }
}
