<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contest;
use App\Models\ContestEntry;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminContestController extends Controller
{
    /**
     * Display the Admin Contest Hub with status tabs, stats, and moderation controls.
     */
    public function index(Request $request): View
    {
        $query = Contest::with(['organizer', 'categoryRelation', 'winner', 'entries.user'])
            ->latest();

        $activeTab = $request->query('status', 'all');

        if ($activeTab === 'pending_approval' || $activeTab === 'pending') {
            $query->where('status', 'pending_approval');
        } elseif ($activeTab === 'active') {
            $query->where('status', 'active');
        } elseif ($activeTab === 'judging') {
            $query->where('status', 'judging');
        } elseif ($activeTab === 'completed') {
            $query->where('status', 'completed');
        } elseif ($activeTab === 'rejected') {
            $query->where('status', 'rejected');
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('payment_reference', 'like', "%{$search}%")
                  ->orWhereHas('organizer', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $contests = $query->paginate(15)->withQueryString();

        // Statistics
        $pendingCount = Contest::where('status', 'pending_approval')->count();
        $activeCount = Contest::where('status', 'active')->count();
        $judgingCount = Contest::where('status', 'judging')->count();
        $completedCount = Contest::where('status', 'completed')->count();
        $totalContests = Contest::count();
        $totalPrizePool = Contest::where('status', '!=', 'rejected')->sum('prize_amount');
        $totalEntries = ContestEntry::count();
        $categories = Category::all();

        return view('admin.contests.index', compact(
            'contests',
            'categories',
            'activeTab',
            'pendingCount',
            'activeCount',
            'judgingCount',
            'completedCount',
            'totalContests',
            'totalPrizePool',
            'totalEntries'
        ));
    }

    /**
     * Approve a pending contest and mark as active + guaranteed.
     */
    public function approve(Request $request, Contest $contest): RedirectResponse
    {
        $contest->update([
            'status' => 'active',
            'is_guaranteed' => true,
            'rejection_reason' => null,
            'start_date' => now(),
            'deadline' => $contest->deadline ?: now()->addDays(7),
        ]);

        if ($contest->user_id && $contest->user_id !== auth()->id()) {
            Notification::send(
                $contest->user_id,
                '🏆 Contest Approved & Published Live!',
                "Congratulations! Your contest \"{$contest->title}\" payment verification was successful and the contest is now live.",
                'success',
                route('contests.show', $contest->slug)
            );
        }

        // Broadcast contest launch to all other users
        $recipients = User::where('id', '!=', $contest->user_id)->get();
        if ($recipients->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send($recipients, new \App\Notifications\NewContestLaunchedNotification($contest));
        }

        return redirect()->back()
            ->with('success', "🏆 Contest \"{$contest->title}\" approved and published as ACTIVE with Guaranteed Prize Pool!");
    }

    /**
     * Reject a pending contest with administrative note.
     */
    public function reject(Request $request, Contest $contest): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $contest->update([
            'status' => 'rejected',
            'is_guaranteed' => false,
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        if ($contest->user_id && $contest->user_id !== auth()->id()) {
            Notification::send(
                $contest->user_id,
                '⚠️ Contest Request Rejected',
                "Your contest \"{$contest->title}\" was rejected. Reason: {$validated['rejection_reason']}",
                'danger',
                route('contests.index')
            );
        }

        return redirect()->back()
            ->with('warning', "🚫 Contest \"{$contest->title}\" has been REJECTED. Rejection reason recorded.");
    }

    /**
     * Direct "+ Create Official Noksha Contest" modal for admin instant posting.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'prize_amount' => ['required', 'numeric', 'min:1000'],
            'deadline_days' => ['required', 'integer', 'min:1', 'max:60'],
            'required_dimensions' => ['nullable', 'string', 'max:200'],
        ]);

        $slug = Str::slug($validated['title']) . '-' . Str::lower(Str::random(6));
        $deadline = now()->addDays((int) $validated['deadline_days']);
        $categoryId = Category::where('name', 'like', "%{$validated['category']}%")->value('id');

        $contest = Contest::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'category_id' => $categoryId,
            'description' => $validated['description'],
            'required_dimensions' => $validated['required_dimensions'] ?? null,
            'prize_amount' => $validated['prize_amount'],
            'posting_fee' => 0.00,
            'payment_reference' => 'Official Noksha Admin Escrow',
            'is_guaranteed' => true,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => $deadline,
            'deadline' => $deadline,
        ]);

        // Broadcast contest launch to all other users
        $recipients = User::where('id', '!=', auth()->id())->get();
        if ($recipients->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send($recipients, new \App\Notifications\NewContestLaunchedNotification($contest));
        }

        return redirect()->back()
            ->with('success', '🏆 Official Noksha Contest has been created and published instantly!');
    }

    /**
     * Force Update contest parameters.
     */
    public function update(Request $request, Contest $contest): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'prize_amount' => ['required', 'numeric', 'min:1000'],
            'status' => ['required', 'in:pending_approval,active,judging,completed,rejected,cancelled'],
            'is_guaranteed' => ['nullable'],
        ]);

        $contest->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'prize_amount' => $validated['prize_amount'],
            'status' => $validated['status'],
            'is_guaranteed' => $request->has('is_guaranteed'),
        ]);

        return redirect()->back()
            ->with('success', "✨ Contest \"{$contest->title}\" updated successfully.");
    }

    /**
     * Force Cancel any ongoing contest.
     */
    public function cancel(Contest $contest): RedirectResponse
    {
        $contest->update([
            'status' => 'cancelled',
            'is_guaranteed' => false,
        ]);

        return redirect()->back()
            ->with('warning', "🚫 Contest \"{$contest->title}\" has been CANCELLED by Admin.");
    }

    /**
     * Force Delete a contest.
     */
    public function destroy(Contest $contest): RedirectResponse
    {
        $title = $contest->title;
        $contest->delete();

        return redirect()->back()
            ->with('success', "🗑️ Contest \"{$title}\" and associated entries were deleted.");
    }

    /**
     * View all submitted designs for a contest with manual winner declaration controls.
     */
    public function submissions(Contest $contest): View
    {
        $contest->load(['organizer', 'categoryRelation', 'winner', 'entries.user', 'submissions.user']);

        return view('admin.contests.submissions', compact('contest'));
    }

    /**
     * Manually declare or override winner for a design contest.
     */
    public function selectWinner(Request $request, Contest $contest): RedirectResponse
    {
        $entryId = $request->entry_id ?: $request->submission_id;

        if (!$entryId) {
            return redirect()->back()->with('error', 'Please select a valid entry.');
        }

        $entry = ContestEntry::where('contest_id', $contest->id)->where('id', $entryId)->first();

        if ($entry) {
            return app(ContestHandoverController::class)->awardWinner($request, $contest, $entry);
        }

        return redirect()->back()->with('error', 'Entry not found.');
    }
}
