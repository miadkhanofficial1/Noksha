<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\ContestEntry;
use App\Models\Notification;
use App\Services\WatermarkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContestEntryController extends Controller
{
    /**
     * Show the dedicated contest entry submission page.
     * Accessible only to authenticated approved contributors.
     */
    public function create(Contest $contest): View|RedirectResponse
    {
        $user = auth()->user();

        // 1. Contributor authorization check
        if (!$user || (!$user->isContributor() && !$user->isAdmin())) {
            return redirect()->route('contributor.apply')
                ->with('warning', 'You must be an approved Contributor to submit contest entries.');
        }

        // 2. Contest status check
        if ($contest->status !== 'active' || ($contest->effective_deadline && $contest->effective_deadline->isPast())) {
            return redirect()->route('contests.show', $contest->slug ?: $contest->id)
                ->with('warning', 'This contest is currently closed for new submissions.');
        }

        // 3. Strict single entry rule check
        if ($contest->hasUserEntered($user->id)) {
            return redirect()->route('contests.show', $contest->slug ?: $contest->id)
                ->with('error', 'You have already submitted your design entry for this contest (Single Entry Rule).');
        }

        return view('contests.submit', compact('contest'));
    }

    /**
     * Store a newly submitted contest design entry.
     */
    public function store(Request $request, Contest $contest): RedirectResponse
    {
        $user = auth()->user();

        // 1. Contributor authorization check
        if (!$user || (!$user->isContributor() && !$user->isAdmin())) {
            return redirect()->route('contributor.apply')
                ->with('warning', 'Please apply to become an approved Contributor to enter design contests.');
        }

        // 2. Contest active status and deadline check
        if ($contest->status !== 'active' || ($contest->effective_deadline && $contest->effective_deadline->isPast())) {
            return redirect()->route('contests.show', $contest->slug ?: $contest->id)
                ->with('warning', 'This contest is no longer accepting new submissions.');
        }

        // 3. Strict Single Entry Rule
        if ($contest->hasUserEntered($user->id)) {
            return redirect()->route('contests.show', $contest->slug ?: $contest->id)
                ->with('error', 'You have already submitted an entry for this contest (Single Entry Rule applies).');
        }

        // 4. Validate input
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'source_file_link' => ['nullable', 'string', 'max:500'],
            'clean_preview_image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'agreement' => ['required'],
        ], [
            'title.required' => 'Please provide a clear title for your design concept.',
            'clean_preview_image.required' => 'Please upload a high-resolution design preview image.',
            'clean_preview_image.max' => 'Artwork preview image size cannot exceed 10MB.',
            'agreement.required' => 'You must confirm that this is your 100% original design artwork.',
        ]);

        // 5. Store clean high-res image in private storage
        $cleanFile = $request->file('clean_preview_image');
        $cleanPath = $cleanFile->store('contest_clean', 'local');
        $sourceFullPath = Storage::disk('local')->path($cleanPath);

        // 6. Generate protective auto-watermark for public gallery display
        $tempId = 'ENTRY_' . time() . '_' . $user->id;
        $watermarkedRelativePath = WatermarkService::applyContestWatermark(
            $sourceFullPath,
            $tempId,
            'NOKSHA CONTEST ENTRY • FOR PREVIEW ONLY'
        );

        // 7. Create contest entry record
        $entry = ContestEntry::create([
            'contest_id' => $contest->id,
            'user_id' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'clean_preview_image' => $cleanPath,
            'watermarked_preview_image' => $watermarkedRelativePath,
            'source_file_link' => $validated['source_file_link'] ?? null,
            'likes_count' => 0,
            'is_winner' => false,
        ]);

        // 8. Notify contest organizer
        if ($contest->user_id && $contest->user_id !== $user->id) {
            Notification::send(
                $contest->user_id,
                'New Contest Entry Submitted',
                "Contributor {$user->name} submitted a new design concept for your contest \"{$contest->title}\".",
                'contest',
                route('contests.show', $contest->slug ?: $contest->id)
            );
        }

        return redirect()->route('contests.show', $contest->slug ?: $contest->id)
            ->with('success', '🎨 Your design entry has been successfully submitted with watermarked protection!');
    }
}
