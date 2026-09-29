<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMessageController extends Controller
{
    /**
     * Check administrative privileges before executing actions.
     */
    protected function checkAdminAuthorization(): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isAdmin() && !$user->is_admin && !in_array($user->role, ['admin', 'super_admin']))) {
            abort(403, 'Unauthorized access: Super Admin privileges required to access customer support inbox.');
        }
    }

    /**
     * Display a listing of customer inquiries submitted via /contact.
     */
    public function index(Request $request): View
    {
        $this->checkAdminAuthorization();

        $status = $request->input('status', 'all');
        $subject = $request->input('subject', 'all');
        $search = trim($request->input('q', ''));

        // Query Builder
        $query = ContactMessage::query();

        // 1. Status Filter
        if (in_array($status, ['unread', 'read', 'replied', 'archived'])) {
            $query->where('status', $status);
        }

        // 2. Subject Filter
        if ($subject && $subject !== 'all') {
            $query->where('subject', $subject);
        }

        // 3. Keyword Search Filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Paginated results with user relationship
        $messages = $query->with('user')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Stat Counter Metrics
        $totalCount = ContactMessage::count();
        $unreadCount = ContactMessage::where('status', 'unread')->count();
        $readCount = ContactMessage::where('status', 'read')->count();
        $repliedCount = ContactMessage::where('status', 'replied')->count();
        $archivedCount = ContactMessage::where('status', 'archived')->count();

        // Distinct subjects for filter dropdown
        $subjects = ContactMessage::select('subject')
            ->distinct()
            ->pluck('subject')
            ->filter()
            ->values();

        return view('admin.messages.index', compact(
            'messages',
            'totalCount',
            'unreadCount',
            'readCount',
            'repliedCount',
            'archivedCount',
            'status',
            'subject',
            'search',
            'subjects'
        ));
    }

    /**
     * Update the status of a specific contact message.
     */
    public function updateStatus(Request|int|string $request, int|string|null $id = null): JsonResponse|RedirectResponse
    {
        $this->checkAdminAuthorization();

        if ($request instanceof Request) {
            $messageId = $id;
            $req = $request;
        } else {
            $messageId = $request;
            $req = request();
        }

        $validated = $req->validate([
            'status' => ['required', 'string', 'in:unread,read,replied,archived'],
        ]);

        $message = ContactMessage::findOrFail($messageId);
        $message->update(['status' => $validated['status']]);

        if ($req->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $message->status,
                'message' => 'Message status updated to ' . ucfirst($message->status) . '.',
            ]);
        }

        return redirect()->back()
            ->with('success', 'Message marked as ' . ucfirst($message->status) . ' successfully.');
    }

    /**
     * Delete a contact inquiry record.
     */
    public function destroy(Request|int|string $request, int|string|null $id = null): JsonResponse|RedirectResponse
    {
        $this->checkAdminAuthorization();

        if ($request instanceof Request) {
            $messageId = $id;
            $req = $request;
        } else {
            $messageId = $request;
            $req = request();
        }

        $message = ContactMessage::findOrFail($messageId);
        $message->delete();

        if ($req->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Support inquiry deleted successfully.',
            ]);
        }

        return redirect()->back()
            ->with('success', 'Support message deleted successfully.');
    }
}
