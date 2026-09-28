<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicProfileController extends Controller
{
    /**
     * Display the public user/designer profile page.
     *
     * @param string $username
     * @return \Illuminate\View\View
     */
    public function show(string $username)
    {
        // Find user by username handle (or fallback to ID if numeric)
        $user = User::where('username', $username)->first();

        if (!$user && is_numeric($username)) {
            $user = User::find($username);
        }

        if (!$user) {
            abort(404, 'User profile not found.');
        }

        // Active authenticated user context
        $authUser = Auth::user();
        $isOwnProfile = $authUser && $authUser->id === $user->id;
        $isFollowing = $authUser ? $authUser->isFollowing($user) : false;

        // Follower metrics
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        // 1. Approved Downloadable Store Assets
        $assets = $user->resources()
            ->where('status', 'approved')
            ->with(['category'])
            ->withCount(['reviews', 'wishlists'])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->paginate(12, ['*'], 'assets_page');

        $totalAssetsCount = $user->resources()->where('status', 'approved')->count();

        // 2. Contest Wins
        $contestWins = $user->contestEntries()
            ->where('is_winner', true)
            ->with(['contest.user'])
            ->latest()
            ->get();

        $totalWinsCount = $contestWins->count();

        // 3. All Public Contest Submissions
        $contestEntries = $user->contestEntries()
            ->with(['contest'])
            ->latest()
            ->paginate(12, ['*'], 'entries_page');

        // 4. Client Reviews & Feedback
        $reviews = $user->receivedReviews()
            ->with(['user', 'resource'])
            ->latest()
            ->paginate(8, ['*'], 'reviews_page');

        $totalReviewsCount = $user->receivedReviews()->count();
        $rawAvgRating = $user->receivedReviews()->avg('rating');
        $averageRating = $rawAvgRating ? round($rawAvgRating, 1) : 5.0;

        // 5. Contests Organized (for buyers or hybrid creators)
        $organizedContests = $user->organizedContests()
            ->whereIn('status', ['active', 'judging', 'handover', 'completed'])
            ->withCount('entries')
            ->latest()
            ->get();

        return view('profile.show', compact(
            'user',
            'isOwnProfile',
            'isFollowing',
            'followersCount',
            'followingCount',
            'assets',
            'totalAssetsCount',
            'contestWins',
            'totalWinsCount',
            'contestEntries',
            'reviews',
            'totalReviewsCount',
            'averageRating',
            'organizedContests'
        ));
    }

    /**
     * Toggle follow/unfollow status for the given user.
     *
     * @param Request $request
     * @param string $username
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function toggleFollow(Request $request, string $username)
    {
        $targetUser = User::where('username', $username)->first();

        if (!$targetUser && is_numeric($username)) {
            $targetUser = User::find($username);
        }

        if (!$targetUser) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'User not found.'], 404);
            }
            abort(404);
        }

        /** @var User $authUser */
        $authUser = Auth::user();

        if ($authUser->id === $targetUser->id) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'You cannot follow yourself.'], 422);
            }
            return back()->with('error', 'You cannot follow yourself.');
        }

        if ($authUser->isFollowing($targetUser)) {
            $authUser->following()->detach($targetUser->id);
            $isFollowing = false;
            $message = 'You have unfollowed @' . $targetUser->username;
        } else {
            $authUser->following()->attach($targetUser->id);
            $isFollowing = true;
            $message = 'You are now following @' . $targetUser->username;
        }

        $freshFollowersCount = $targetUser->followers()->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_following' => $isFollowing,
                'followers_count' => $freshFollowersCount,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
