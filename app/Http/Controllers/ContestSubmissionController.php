<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\ContestEntry;
use App\Models\ContestEntryLike;
use App\Models\Notification;
use App\Services\WatermarkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContestSubmissionController extends Controller
{
    /**
     * Submit a design entry to an active contest (Approved Contributor only).
     * Enforces Single Entry Rule, stores clean file, and applies auto-watermark.
     */
    public function store(Request $request, Contest $contest): RedirectResponse
    {
        $user = auth()->user();

        // 1. Check Contributor Authorization
        if (!$user || (!$user->isContributor() && !$user->isAdmin())) {
            return redirect()->route('contributor.apply')
                ->with('error', 'Please apply to become a verified Contributor to enter design contests (KYC Verification Required).');
        }

        // 2. Check Contest Status
        if ($contest->status !== 'active') {
            return redirect()->back()
                ->with('warning', 'This contest is currently closed for new submissions.');
        }

        // 3. STRICT SINGLE ENTRY PER CONTRIBUTOR RULE
        if ($contest->hasUserEntered($user->id)) {
            return redirect()->back()
                ->with('error', 'You have already submitted an entry for this contest (Strict Single Entry Rule applies).');
        }

        // 4. Validate Input
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'source_file_link' => ['nullable', 'string', 'max:500'],
            'clean_preview_image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'agreement' => ['required'],
        ], [
            'title.required' => 'Please provide a title for your design concept.',
            'clean_preview_image.required' => 'Please upload a high-resolution design preview image.',
            'clean_preview_image.max' => 'Preview image size cannot exceed 10MB.',
            'agreement.required' => 'You must confirm that this is your 100% original artwork.',
        ]);

        // 5. Store Clean High-Res Image (Private Storage)
        $cleanFile = $request->file('clean_preview_image');
        $cleanPath = $cleanFile->store('contest_clean', 'local');
        $sourceFullPath = Storage::disk('local')->path($cleanPath);

        // 6. Generate Unique Identifier for Auto-Watermarking
        $tempId = 'TMP_' . time() . '_' . $user->id;

        // Apply repeated diagonal watermark: "NOKSHA CONTEST ENTRY #ID - FOR PREVIEW ONLY"
        $watermarkedRelativePath = WatermarkService::applyContestWatermark(
            $sourceFullPath,
            $tempId,
            "NOKSHA CONTEST ENTRY • FOR PREVIEW ONLY"
        );

        // 7. Save Contest Entry
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

        // 8. Send Notification to Organizer
        if ($contest->user_id && $contest->user_id !== $user->id) {
            Notification::send(
                $contest->user_id,
                'New Contest Entry Submitted',
                "Contributor {$user->name} submitted a new design concept for your contest \"{$contest->title}\".",
                'seller',
                route('contests.show', $contest->slug)
            );
        }

        return redirect()->route('contests.show', $contest->slug)
            ->with('success', '🎨 Your design concept has been submitted with protective watermark! (Zero entry fee deducted)');
    }

    /**
     * Community Like / Upvote toggle for an entry.
     */
    public function toggleLike(Request $request, ContestEntry $entry): JsonResponse
    {
        $userId = auth()->id();
        $ip = $request->ip();

        $query = ContestEntryLike::where('contest_entry_id', $entry->id);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('ip_address', $ip);
        }

        $existing = $query->first();

        if ($existing) {
            $existing->delete();
            $entry->decrement('likes_count');
            $liked = false;
        } else {
            ContestEntryLike::create([
                'contest_entry_id' => $entry->id,
                'user_id' => $userId,
                'ip_address' => $ip,
            ]);
            $entry->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'likes_count' => max(0, $entry->fresh()->likes_count),
            'liked' => $liked,
        ]);
    }
}
