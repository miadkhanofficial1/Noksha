<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display the Notification Center with Today, Yesterday, and Earlier sections.
     */
    public function index(Request $request): View
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->get();

        $today = $notifications->filter(fn($n) => $n->created_at->isToday());
        $yesterday = $notifications->filter(fn($n) => $n->created_at->isYesterday());
        $earlier = $notifications->filter(fn($n) => !$n->created_at->isToday() && !$n->created_at->isYesterday());

        $unreadCount = $notifications->where('is_read', false)->count();

        return view('notifications.index', compact(
            'notifications',
            'today',
            'yesterday',
            'earlier',
            'unreadCount'
        ));
    }

    /**
     * Mark a specific notification as read and redirect to its action URL.
     */
    public function markAsRead(Notification $notification): RedirectResponse
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized notification access.');
        }

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        if ($notification->action_url) {
            return redirect()->to($notification->action_url);
        }

        return redirect()->back();
    }

    /**
     * Mark all unread notifications as read for the authenticated user.
     */
    public function markAllAsRead(Request $request): RedirectResponse
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
