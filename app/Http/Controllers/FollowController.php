<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Toggle follow/unfollow status for the target user.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function toggle(Request $request, User $user)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        // Prevent self-following
        if ($authUser->id === $user->id) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot follow yourself.',
                ], 400);
            }
            return back()->with('error', 'You cannot follow yourself.');
        }

        // Toggle relationship
        $toggleResult = $authUser->following()->toggle($user->id);
        $isFollowing = in_array($user->id, $toggleResult['attached']);

        // Dispatch in-app notification if now following
        if ($isFollowing) {
            Notification::send(
                $user->id,
                'New Follower',
                $authUser->name . ' (@' . $authUser->username . ') started following you.',
                'community',
                route('user.profile', $authUser->username)
            );
        }

        $message = $isFollowing
            ? 'You are now following @' . $user->username
            : 'You have unfollowed @' . $user->username;

        $followersCount = $user->followers()->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_following' => $isFollowing,
                'followers_count' => $followersCount,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
