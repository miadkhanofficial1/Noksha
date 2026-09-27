<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
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

        $today     = $notifications->filter(fn($n) => $n->created_at->isToday());
        $yesterday = $notifications->filter(fn($n) => $n->created_at->isYesterday());
        $earlier   = $notifications->filter(fn($n) => !$n->created_at->isToday() && !$n->created_at->isYesterday());

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
     * Safely resolves stored action_url by extracting path only, regardless of stored host.
     */
    public function markAsRead(Notification $notification): RedirectResponse
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized notification access.');
        }

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'is_seen' => true,
                'read_at' => now(),
            ]);
        }

        if ($notification->action_url) {
            try {
                $parsed = parse_url($notification->action_url);
                $path   = $parsed['path'] ?? null;

                if ($path && $path !== '/' && $path !== '') {
                    $queryString = isset($parsed['query'])    ? '?' . $parsed['query']    : '';
                    $fragment    = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';
                    return redirect($path . $queryString . $fragment);
                }
            } catch (\Throwable $e) {
                // Fall through to dashboard
            }
        }

        return redirect()->route('dashboard');
    }

    /**
     * Dedicated lightweight AJAX/API endpoint to mark a single notification as read.
     * Updates read_at = now(), is_read = true, is_seen = true.
     * Returns JSON with updated unread_count and destination_url.
     */
    public function markSingleAsRead(Request $request, Notification $notification): JsonResponse
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'is_seen' => true,
                'read_at' => now(),
            ]);
        }

        $unreadCount = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        $destinationUrl = null;
        if ($notification->action_url) {
            try {
                $parsed = parse_url($notification->action_url);
                $path   = $parsed['path'] ?? null;
                if ($path && $path !== '' && $path !== '/') {
                    $queryString    = isset($parsed['query'])    ? '?' . $parsed['query']    : '';
                    $fragment       = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';
                    $destinationUrl = $path . $queryString . $fragment;
                } else {
                    $destinationUrl = $notification->action_url;
                }
            } catch (\Throwable $e) {
                $destinationUrl = $notification->action_url;
            }
        }

        $isBroadcast = in_array($notification->type, ['broadcast', 'admin', 'system']) ||
                       str_contains(strtolower($notification->title), 'broadcast') ||
                       str_contains(strtolower($notification->title), 'announcement');

        return response()->json([
            'success'         => true,
            'ok'              => true,
            'id'              => $notification->id,
            'is_read'         => true,
            'unread_count'    => $unreadCount,
            'is_broadcast'    => $isBroadcast,
            'destination_url' => $destinationUrl,
            'redirect_url'    => $destinationUrl,
            'type'            => $notification->type,
            'title'           => $notification->title,
            'message'         => $notification->message,
            'created_at'      => $notification->created_at->format('d M Y, g:i A'),
        ]);
    }

    /**
     * AJAX: Mark a single notification as read (alias for markSingleAsRead).
     */
    public function ajaxMarkAsRead(Request $request, Notification $notification): JsonResponse
    {
        return $this->markSingleAsRead($request, $notification);
    }

    /**
     * AJAX: Clear the bell badge (mark all unseen notifications as "seen").
     * Does NOT mark them as read — they remain unread inside the dropdown until individually clicked.
     * Returns the new unread/unseen count (0 for the badge).
     */
    public function badgeClear(Request $request): JsonResponse
    {
        Notification::where('user_id', auth()->id())
            ->where('is_seen', false)
            ->update([
                'is_seen' => true,
            ]);

        return response()->json(['ok' => true, 'unseen_count' => 0]);
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
                'is_seen' => true,
                'read_at' => now(),
            ]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Admin: View broadcast notification history.
     */
    public function broadcastHistory(Request $request): View
    {
        // Broadcasts are system/broadcast/admin notifications sent across users — aggregate by title & created_at
        $broadcasts = Notification::whereIn('type', ['broadcast', 'system', 'admin'])
            ->whereNotNull('title')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(fn($n) => $n->title . '___' . $n->created_at->format('Y-m-d H:i'))
            ->map(fn($group) => [
                'title'      => $group->first()->title,
                'message'    => $group->first()->message,
                'type'       => $group->first()->type,
                'sent_at'    => $group->first()->created_at,
                'recipients' => $group->count(),
                'read_count' => $group->where('is_read', true)->count(),
            ])
            ->values();

        return view('admin.broadcast-history', compact('broadcasts'));
    }
}
