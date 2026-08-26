<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contest;
use App\Models\ContestSubmission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContestController extends Controller
{
    /**
     * Display public contests showcase page & Top 10 Creator Leaderboard.
     */
    public function index(Request $request): View
    {
        $contests = Contest::with(['category', 'winner', 'submissions'])
            ->latest()
            ->get();

        // Top 10 Creators Leaderboard based on contest wins and uploaded resources
        $topCreators = User::withCount(['wonContests as wins_count', 'resources as resources_count'])
            ->orderByDesc('wins_count')
            ->orderByDesc('resources_count')
            ->take(10)
            ->get();

        return view('contests.index', compact('contests', 'topCreators'));
    }

    /**
     * Display single contest details page with submissions gallery and seller upload UI.
     */
    public function show(Contest $contest): View
    {
        $contest->load(['category', 'winner', 'winningSubmission', 'submissions.user']);

        $userHasSubmitted = auth()->check()
            ? ContestSubmission::where('contest_id', $contest->id)->where('user_id', auth()->id())->exists()
            : false;

        return view('contests.show', compact('contest', 'userHasSubmitted'));
    }

    /**
     * Display Super Admin Contest Management Panel.
     */
    public function adminIndex(Request $request): View
    {
        $contests = Contest::with(['category', 'winner', 'submissions.user'])
            ->latest()
            ->get();

        $categories = Category::all();

        $totalContests = $contests->count();
        $activeContests = $contests->where('status', 'active')->count();
        $completedContests = $contests->where('status', 'completed')->count();
        $totalSubmissions = ContestSubmission::count();

        return view('admin.contests.index', compact(
            'contests',
            'categories',
            'totalContests',
            'activeContests',
            'completedContests',
            'totalSubmissions'
        ));
    }

    /**
     * Store a newly created contest (Admin action).
     */
    public function adminStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'prize_amount' => ['required', 'numeric', 'min:0'],
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

        return redirect()->route('admin.contests.index')
            ->with('success', '🏆 Design contest created successfully!');
    }

    /**
     * Announce and select winner for a design contest (Admin action).
     */
    public function selectWinner(Request $request, Contest $contest): RedirectResponse
    {
        $validated = $request->validate([
            'submission_id' => ['required', 'exists:contest_submissions,id'],
        ]);

        $submission = ContestSubmission::where('contest_id', $contest->id)
            ->where('id', $validated['submission_id'])
            ->firstOrFail();

        // 1. Reset any previous winners for this contest
        ContestSubmission::where('contest_id', $contest->id)->update(['is_winner' => false]);

        // 2. Mark selected submission as winner
        $submission->update(['is_winner' => true]);

        // 3. Mark contest completed and assign winner
        $contest->update([
            'status' => 'completed',
            'winner_id' => $submission->user_id,
            'winner_submission_id' => $submission->id,
        ]);

        return redirect()->back()
            ->with('success', "🏆 Winner selected! Congratulations to {$submission->user->name}!");
    }
}
