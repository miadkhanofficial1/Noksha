<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contest;
use App\Models\ContestEntry;
use App\Models\Notification;
use App\Models\Resource;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContestController extends Controller
{
    /**
     * Display public contests showcase page, filter tabs & Top 10 Creator Leaderboard.
     */
    public function index(Request $request): View
    {
        $query = Contest::with(['organizer', 'categoryRelation', 'winner', 'entries.user', 'submissions.user'])
            ->whereIn('status', ['active', 'judging', 'completed']);

        // Filter tab
        $tab = $request->query('tab', 'all');
        if ($tab === 'active') {
            $query->where('status', 'active');
        } elseif ($tab === 'judging') {
            $query->where('status', 'judging');
        } elseif ($tab === 'completed') {
            $query->where('status', 'completed');
        }

        // Category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $cat = trim($request->category);
            $query->where(function ($q) use ($cat) {
                $q->where('category', 'like', "%{$cat}%")
                  ->orWhereHas('categoryRelation', function ($sub) use ($cat) {
                      $sub->where('name', 'like', "%{$cat}%");
                  });
            });
        }

        // Search query
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $contests = $query->latest('is_guaranteed')->latest()->paginate(12)->withQueryString();

        // Top 10 Creators Leaderboard based on real dynamic platform ranking algorithm:
        // Score = (Completed Withdrawals * 1.0) + (Contest Wins * 500) + (Approved Assets * 50)
        // Strictly includes active sellers/creators/contributors and excludes pure buyers, admins, and test accounts.
        $creators = User::query()
            ->where('is_admin', false)
            ->whereNotIn('role', ['admin', 'super_admin'])
            ->where(function ($q) {
                $q->whereIn('role', ['seller', 'creator'])
                  ->orWhere('is_contributor', true)
                  ->orWhere('contributor_status', 'approved')
                  ->orWhereHas('wonContests');
            })
            ->where(function ($q) {
                $q->whereNull('status')->orWhere('status', '!=', 'banned');
            })
            ->where('username', 'not like', '%test%')
            ->get();

        $topCreators = $creators->map(function ($creator) {
            $totalWithdrawn = (float) Withdrawal::where('user_id', $creator->id)
                ->where('status', 'completed')
                ->sum('amount');

            $contestWins = (int) Contest::where('winner_id', $creator->id)->count();

            $publishedAssets = (int) Resource::where('user_id', $creator->id)
                ->where('status', 'approved')
                ->count();

            $rankingScore = ($totalWithdrawn * 1.0) + ($contestWins * 500) + ($publishedAssets * 50);

            $creator->total_withdrawn = $totalWithdrawn;
            $creator->contest_wins = $contestWins;
            $creator->published_assets = $publishedAssets;
            $creator->ranking_score = $rankingScore;

            return $creator;
        })
        ->filter(function ($creator) {
            return $creator->ranking_score > 0;
        })
        ->sort(function ($a, $b) {
            if ($b->ranking_score !== $a->ranking_score) {
                return $b->ranking_score <=> $a->ranking_score;
            }
            if ($b->contest_wins !== $a->contest_wins) {
                return $b->contest_wins <=> $a->contest_wins;
            }
            return $b->published_assets <=> $a->published_assets;
        })
        ->values()
        ->take(10)
        ->map(function ($creator, $index) {
            $rank = $index + 1;
            $creator->rank = $rank;

            if ($rank === 1) {
                $creator->tier_badge = 'Elite Creator';
                $creator->tier_style = 'gold';
            } elseif ($rank <= 3) {
                $creator->tier_badge = 'Pro Contributor';
                $creator->tier_style = 'silver';
            } else {
                $creator->tier_badge = 'Verified Seller';
                $creator->tier_style = 'emerald';
            }

            return $creator;
        });

        $activeContestsCount = Contest::where('status', 'active')->count();
        $totalPrizePool = Contest::whereIn('status', ['active', 'judging', 'completed'])->sum('prize_amount');
        $totalCompleted = Contest::where('status', 'completed')->count();

        return view('contests.index', compact(
            'contests',
            'topCreators',
            'tab',
            'activeContestsCount',
            'totalPrizePool',
            'totalCompleted'
        ));
    }

    /**
     * Show the contest creation form for Buyers & Super Admins.
     */
    public function create(): View
    {
        $categories = Category::all();
        $user = auth()->user();

        return view('contests.create', compact('categories', 'user'));
    }

    /**
     * Store a newly created contest.
     * Super Admins publish instantly without escrow checks.
     * Regular Buyers submit with payment reference and go to 'pending_approval'.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $isAdmin = $user->isAdmin();

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'required_dimensions' => ['nullable', 'string', 'max:200'],
            'prize_amount' => ['required', 'numeric', 'min:1000'],
            'deadline_days' => ['required', 'integer', 'min:1', 'max:60'],
            'attachment_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,zip,rar,docx,txt', 'max:20480'],
        ];

        // Payment reference required for non-admins
        if (!$isAdmin) {
            $rules['payment_method'] = ['required', 'string', 'in:bkash,nagad,rocket,bank'];
            $rules['payment_reference'] = ['required', 'string', 'max:255'];
        }

        $validated = $request->validate($rules, [
            'prize_amount.min' => 'Minimum contest prize pool is 1,000 BDT.',
            'payment_reference.required' => 'Please provide the transaction ID (TrxID) or bank reference.',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment_file')) {
            $attachmentPath = $request->file('attachment_file')->store('contest_attachments', 'public');
        }

        $deadline = now()->addDays((int) $validated['deadline_days']);
        $slug = Str::slug($validated['title']) . '-' . Str::lower(Str::random(6));

        // Category mapping
        $categoryId = Category::where('name', 'like', "%{$validated['category']}%")->value('id');

        if ($isAdmin) {
            $status = 'active';
            $isGuaranteed = true;
            $postingFee = 0.00;
            $paymentRef = 'Admin Authorized (Platform Escrow)';
        } else {
            $status = 'pending_approval';
            $isGuaranteed = false;
            $postingFee = 500.00;
            $paymentRef = strtoupper($request->payment_method) . ': ' . trim($validated['payment_reference']);
        }

        $contest = Contest::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'category_id' => $categoryId,
            'description' => $validated['description'],
            'required_dimensions' => $validated['required_dimensions'] ?? null,
            'attachment_file' => $attachmentPath,
            'prize_amount' => $validated['prize_amount'],
            'posting_fee' => $postingFee,
            'payment_reference' => $paymentRef,
            'is_guaranteed' => $isGuaranteed,
            'status' => $status,
            'start_date' => now(),
            'end_date' => $deadline,
            'deadline' => $deadline,
        ]);

        // Dispatch new contest launched notification to all other users
        $recipients = User::where('id', '!=', auth()->id())->get();
        if ($recipients->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send($recipients, new \App\Notifications\NewContestLaunchedNotification($contest));
        }

        if ($isAdmin) {
            return redirect()->route('contests.show', $contest->slug)
                ->with('success', '🏆 Official Noksha Contest has been created and published instantly!');
        }

        // Notify Admins
        $admins = User::where('is_admin', true)->orWhereIn('role', ['admin', 'super_admin'])->get();
        foreach ($admins as $adm) {
            if ($adm->id !== $user->id) {
                Notification::send(
                    $adm->id,
                    'New Contest Escrow Verification',
                    "Buyer {$user->name} submitted new contest \"{$contest->title}\". Prize: ৳{$contest->prize_amount}, TrxID: {$contest->payment_reference}",
                    'warning',
                    route('admin.contests.index', ['status' => 'pending_approval'])
                );
            }
        }

        return redirect()->route('contests.index')
            ->with('success', 'Your contest has been submitted for escrow payment verification. It will go live once verified by Admin.');
    }

    /**
     * Display single contest details page with public submissions gallery, ratings, and submission modal.
     */
    public function show(Contest $contest): View
    {
        $contest->load([
            'organizer',
            'categoryRelation',
            'winner',
            'winningEntry.user',
            'entries.user',
            'entries.likes',
        ]);

        $user = auth()->user();
        $isOrganizer = $user && ($user->id === $contest->user_id || $user->isAdmin());
        $isContributor = $user && ($user->isContributor() || $user->isAdmin());
        $userHasSubmitted = $user ? $contest->hasUserEntered($user->id) : false;
        $userEntry = $user ? $contest->entries->firstWhere('user_id', $user->id) : null;

        return view('contests.show', compact(
            'contest',
            'isOrganizer',
            'isContributor',
            'userHasSubmitted',
            'userEntry'
        ));
    }

    /**
     * Rate an entry and leave feedback (Organizer / Buyer action).
     */
    public function rateEntry(Request $request, Contest $contest, ContestEntry $entry): JsonResponse|RedirectResponse
    {
        $user = auth()->user();
        if ($user->id !== $contest->user_id && !$user->isAdmin()) {
            abort(403, 'Only the contest organizer can rate submissions.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        $entry->update([
            'client_rating' => $validated['rating'],
            'client_feedback' => $validated['feedback'] ?? $entry->client_feedback,
        ]);

        // Send feedback notification to contributor
        if ($entry->user_id && $entry->user_id !== auth()->id()) {
            Notification::send(
                $entry->user_id,
                'Client Rating & Feedback on Contest',
                "The client gave your design entry for \"{$contest->title}\" a {$validated['rating']}-star rating.",
                'seller',
                route('contests.show', $contest->slug)
            );
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Rating saved successfully!',
                'rating' => $entry->client_rating,
                'feedback' => $entry->client_feedback,
            ]);
        }

        return redirect()->back()->with('success', 'Client feedback and rating updated!');
    }

    /**
     * Award an entry as the WINNER of the contest (Organizer / Buyer action).
     * Transitions contest to handover status.
     */
    public function awardWinner(Request $request, Contest $contest, ContestEntry $entry): RedirectResponse
    {
        return app(ContestHandoverController::class)->awardWinner($request, $contest, $entry);
    }
}
