<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contest;
use App\Models\ContestSubmission;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminContestController extends Controller
{
    /**
     * Display the Admin Contest Hub with status filters, stats, and action controls.
     */
    public function index(Request $request): View
    {
        $query = Contest::with(['category', 'winner', 'submissions.user'])->latest();

        $activeTab = $request->query('status', 'all');
        if (in_array($activeTab, ['active', 'completed', 'cancelled'])) {
            $query->where('status', $activeTab);
        } elseif ($activeTab === 'upcoming') {
            $query->where('start_date', '>', now());
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $contests = $query->paginate(12)->withQueryString();

        // Statistics
        $totalContests = Contest::count();
        $activeContests = Contest::where('status', 'active')->count();
        $completedContests = Contest::where('status', 'completed')->count();
        $cancelledContests = Contest::where('status', 'cancelled')->count();
        $totalPrizePool = Contest::sum('prize_amount');
        $totalSubmissions = ContestSubmission::count();
        $categories = Category::all();

        return view('admin.contests.index', compact(
            'contests',
            'categories',
            'activeTab',
            'totalContests',
            'activeContests',
            'completedContests',
            'cancelledContests',
            'totalPrizePool',
            'totalSubmissions'
        ));
    }

    /**
     * Store a newly created contest from admin command center.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'prize_amount' => ['required', 'numeric', 'min:1'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        $slug = Str::slug($validated['title']) . '-' . Str::random(5);

        Contest::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'prize_amount' => $validated['prize_amount'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'category_id' => $validated['category_id'] ?: null,
            'status' => 'active',
        ]);

        return redirect()->back()
            ->with('success', '🏆 Design contest created and published successfully!');
    }

    /**
     * Force Edit any contest parameters.
     */
    public function update(Request $request, Contest $contest): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'prize_amount' => ['required', 'numeric', 'min:1'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:active,completed,cancelled'],
        ]);

        $contest->update($validated);

        return redirect()->back()
            ->with('success', "✨ Contest \"{$contest->title}\" updated successfully.");
    }

    /**
     * Force Cancel any ongoing contest.
     */
    public function cancel(Contest $contest): RedirectResponse
    {
        $contest->update(['status' => 'cancelled']);

        return redirect()->back()
            ->with('warning', "🚫 Contest \"{$contest->title}\" has been CANCELLED by Super Admin.");
    }

    /**
     * Force Delete a contest and wipe all submissions permanently.
     */
    public function destroy(Contest $contest): RedirectResponse
    {
        $title = $contest->title;
        $contest->delete();

        return redirect()->back()
            ->with('success', "🗑️ Contest \"{$title}\" and associated submissions were permanently deleted.");
    }

    /**
     * View all submitted designs for a contest with manual winner declaration controls.
     */
    public function submissions(Contest $contest): View
    {
        $contest->load(['category', 'winner', 'submissions.user']);

        return view('admin.contests.submissions', compact('contest'));
    }

    /**
     * Manually declare or override winner for a design contest.
     */
    public function selectWinner(Request $request, Contest $contest): RedirectResponse
    {
        $validated = $request->validate([
            'submission_id' => ['required', 'exists:contest_submissions,id'],
        ]);

        $submission = ContestSubmission::where('contest_id', $contest->id)
            ->where('id', $validated['submission_id'])
            ->firstOrFail();

        // Reset previous winners
        ContestSubmission::where('contest_id', $contest->id)->update(['is_winner' => false]);

        // Mark winning submission
        $submission->update(['is_winner' => true]);

        // Complete contest
        $contest->update([
            'status' => 'completed',
            'winner_id' => $submission->user_id,
            'winner_submission_id' => $submission->id,
        ]);

        // Notify winner
        Notification::send(
            $submission->user_id,
            '🏆 You Won the Contest!',
            "Congratulations! Your submission \"{$submission->title}\" was declared the WINNER of \"{$contest->title}\". Prize: ৳" . number_format($contest->prize_amount, 2),
            'success',
            route('contests.show', $contest->slug)
        );

        return redirect()->back()
            ->with('success', "🏆 Winner selected! Congratulations to {$submission->user->name} for winning \"{$contest->title}\"!");
    }
}
