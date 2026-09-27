<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\ContestEntry;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContestHandoverController extends Controller
{
    /**
     * Award an entry as the WINNER of the contest (Organizer / Buyer action).
     * Transitions contest from 'active'/'judging' to 'handover' status.
     */
    public function awardWinner(Request $request, Contest $contest, ContestEntry $entry): RedirectResponse
    {
        $user = auth()->user();

        // 1. Validate auth user is contest creator/buyer or administrator
        if ($user->id !== $contest->user_id && !$user->isAdmin()) {
            abort(403, 'Only the contest organizer can select the winner.');
        }

        // 2. Ensure entry belongs to this contest
        if ($entry->contest_id !== $contest->id) {
            abort(400, 'Entry does not belong to this contest.');
        }

        // 3. Ensure contest is in active or judging state
        if (!in_array($contest->status, ['active', 'judging'])) {
            return redirect()->back()
                ->with('error', 'Contest is not in an active or judging state.');
        }

        // 4. Update entries and contest in transaction
        DB::transaction(function () use ($contest, $entry) {
            // Reset previous winners if any
            ContestEntry::where('contest_id', $contest->id)->update(['is_winner' => false]);

            // Set entry as winner with pending handover status
            $entry->update([
                'is_winner' => true,
                'handover_status' => 'pending',
            ]);

            // Transition contest to handover status
            $contest->update([
                'status' => 'handover',
                'winner_id' => $entry->user_id,
                'winner_entry_id' => $entry->id,
            ]);
        });

        // 5. Fire notification to winning designer
        if ($entry->user_id && $entry->user_id !== $user->id) {
            Notification::send(
                $entry->user_id,
                '🏆 Contest Winner Selected!',
                "Congratulations! Your entry has been selected as the winner for \"{$contest->title}\". Please submit the editable source files to receive your prize.",
                'success',
                route('contests.show', $contest->slug)
            );
        }

        return redirect()->route('contests.show', $contest->slug)
            ->with('success', "🏆 Congratulations! You have awarded {$entry->user->name} as the WINNER. The contest is now in Handover mode awaiting editable source files.");
    }

    /**
     * Upload protected source files by the winning designer.
     * Stored strictly in private storage: storage/app/private/contest_handovers/{contest_id}/
     */
    public function uploadSourceFiles(Request $request, Contest $contest): RedirectResponse
    {
        $user = auth()->user();
        $entry = $contest->winningEntry ?? ContestEntry::where('contest_id', $contest->id)->where('is_winner', true)->first();

        if (!$entry) {
            return redirect()->back()->with('error', 'No winning entry found for this contest.');
        }

        // 1. Validate auth user is the winning entry contributor or admin
        if ($user->id !== $entry->user_id && !$user->isAdmin()) {
            abort(403, 'Only the winning contributor can submit handover source files.');
        }

        // 2. Ensure contest is in handover status
        if ($contest->status !== 'handover') {
            return redirect()->back()->with('error', 'Contest is not currently in the handover phase.');
        }

        // 3. Validate input files (zip, rar, ai, psd, eps, svg up to 100MB)
        $request->validate([
            'source_file' => ['required', 'file', 'max:102400'], // 100MB in KB
            'handover_notes' => ['nullable', 'string', 'max:2000'],
        ], [
            'source_file.required' => 'Please upload your final editable source files package.',
            'source_file.max' => 'Source file size cannot exceed 100MB.',
        ]);

        $file = $request->file('source_file');
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = ['zip', 'rar', 'ai', 'psd', 'eps', 'svg', 'tar', 'gz', '7z', 'pdf'];

        if (!in_array($extension, $allowedExtensions)) {
            return redirect()->back()
                ->with('error', 'Invalid file type. Allowed formats: ZIP, RAR, AI, PSD, EPS, SVG, 7Z (Max: 100MB).');
        }

        // 4. Store file safely under private storage: contest_handovers/{contest_id}/
        // In Laravel, Storage::disk('local') stores inside storage/app/private/
        $directory = 'contest_handovers/' . $contest->id;
        $originalName = $file->getClientOriginalName();
        $safeFileName = 'handover_' . time() . '_' . Str::random(8) . '.' . $extension;
        $storedPath = $file->storeAs($directory, $safeFileName, 'local');

        $fileMeta = [
            'file_path' => $storedPath,
            'original_name' => $originalName,
            'mime_type' => $file->getClientMimeType() ?: 'application/octet-stream',
            'size' => $file->getSize(),
            'uploaded_at' => now()->toDateTimeString(),
        ];

        $currentFiles = is_array($entry->handover_files) ? $entry->handover_files : [];
        $currentFiles[] = $fileMeta;

        // 5. Update entry with handover files & status
        $entry->update([
            'handover_files' => $currentFiles,
            'handover_notes' => $request->input('handover_notes') ?: $entry->handover_notes,
            'handover_submitted_at' => now(),
            'handover_status' => 'submitted',
        ]);

        // 6. Notify buyer
        if ($contest->user_id && $contest->user_id !== $user->id) {
            Notification::send(
                $contest->user_id,
                'Source Files Submitted for Review',
                "Winner {$user->name} has submitted the editable source files for \"{$contest->title}\". Please review and release payout.",
                'seller',
                route('contests.show', $contest->slug)
            );
        }

        return redirect()->route('contests.show', $contest->slug)
            ->with('success', '📁 Source files uploaded securely to private storage! The organizer has been notified to review and release escrow.');
    }

    /**
     * Securely download protected handover files.
     * Authorized only for: Contest Buyer/Organizer, Winning Designer, or Admin.
     */
    public function downloadHandoverFiles(Contest $contest): StreamedResponse|RedirectResponse
    {
        $user = auth()->user();
        $entry = $contest->winningEntry ?? ContestEntry::where('contest_id', $contest->id)->where('is_winner', true)->first();

        if (!$entry) {
            abort(404, 'No winning entry found for this contest.');
        }

        // Strict authorization check: Only the contest owner (buyer), winning designer, or admin
        $isBuyer = ($contest->user_id && $contest->user_id === $user->id);
        $isWinner = ($entry->user_id === $user->id);
        $isAdmin = $user->isAdmin();

        if (!$isBuyer && !$isWinner && !$isAdmin) {
            abort(403, 'Unauthorized access: You do not have permission to download these source files.');
        }

        $files = is_array($entry->handover_files) ? $entry->handover_files : [];
        if (empty($files)) {
            return redirect()->back()->with('error', 'Handover source files have not been uploaded yet.');
        }

        // Get the latest uploaded handover package
        $latestFile = end($files);
        $filePath = $latestFile['file_path'] ?? null;
        $originalName = $latestFile['original_name'] ?? ('contest_' . $contest->id . '_source_files.' . pathinfo($filePath, PATHINFO_EXTENSION));

        if (!$filePath || !Storage::disk('local')->exists($filePath)) {
            abort(404, 'Source file could not be located in private storage.');
        }

        return Storage::disk('local')->download($filePath, $originalName);
    }

    /**
     * Release escrow payment to the winning designer upon file approval.
     */
    public function releaseEscrow(Request $request, Contest $contest): RedirectResponse
    {
        $user = auth()->user();

        // 1. Validate auth user is buyer or admin
        if ($user->id !== $contest->user_id && !$user->isAdmin()) {
            abort(403, 'Only the contest organizer or an administrator can release escrow funds.');
        }

        // 2. Retrieve winning entry
        $entry = $contest->winningEntry ?? ContestEntry::where('contest_id', $contest->id)->where('is_winner', true)->first();
        if (!$entry) {
            return redirect()->back()->with('error', 'No winning entry found for this contest.');
        }

        // 3. Verify contest is in 'handover' status and entry is 'submitted'
        if ($contest->status !== 'handover') {
            return redirect()->back()->with('error', 'Contest is not in handover state.');
        }

        if ($entry->handover_status !== 'submitted') {
            return redirect()->back()->with('error', 'Source files must be submitted before releasing escrow funds.');
        }

        $winner = $entry->user;
        if (!$winner) {
            return redirect()->back()->with('error', 'Winning user profile not found.');
        }

        $prizeAmount = (float) $contest->prize_amount;

        // 4. Execute atomic database transaction
        DB::transaction(function () use ($contest, $entry, $winner, $prizeAmount) {
            // Mark entry handover_status = 'approved'
            $entry->update([
                'handover_status' => 'approved',
            ]);

            // Mark contest status = 'completed', handover_completed_at = now()
            $contest->update([
                'status' => 'completed',
                'handover_completed_at' => now(),
            ]);

            // Credit contest prize money to winner's balance/wallet & record ledger transaction
            $winner->creditBalance(
                $prizeAmount,
                "Contest prize escrow payout for \"{$contest->title}\"",
                'contest_escrow',
                $contest->id
            );
        });

        // 5. Notify winning designer
        Notification::send(
            $winner->id,
            '💰 Escrow Funds Released!',
            "Escrow funds released! ৳" . number_format($prizeAmount, 2) . " has been added to your balance.",
            'success',
            route('dashboard') . '#wallet'
        );

        return redirect()->route('contests.show', $contest->slug)
            ->with('success', "🎉 Escrow funds of ৳" . number_format($prizeAmount, 2) . " have been released to {$winner->name}! Contest completed successfully.");
    }

    /**
     * Request revision on source files (Buyer action).
     */
    public function requestRevision(Request $request, Contest $contest): RedirectResponse
    {
        $user = auth()->user();

        if ($user->id !== $contest->user_id && !$user->isAdmin()) {
            abort(403, 'Only the contest organizer can request revisions.');
        }

        $entry = $contest->winningEntry ?? ContestEntry::where('contest_id', $contest->id)->where('is_winner', true)->first();
        if (!$entry) {
            return redirect()->back()->with('error', 'Winning entry not found.');
        }

        $validated = $request->validate([
            'revision_notes' => ['required', 'string', 'max:2000'],
        ], [
            'revision_notes.required' => 'Please explain the revision requested for the source files.',
        ]);

        $entry->update([
            'handover_status' => 'revision_requested',
            'handover_notes' => $validated['revision_notes'],
        ]);

        if ($entry->user_id) {
            Notification::send(
                $entry->user_id,
                'Revision Requested on Source Files',
                "The client requested adjustments for \"{$contest->title}\": \"{$validated['revision_notes']}\". Please re-upload updated files.",
                'warning',
                route('contests.show', $contest->slug)
            );
        }

        return redirect()->back()
            ->with('success', 'Revision requested. The winning designer has been notified to submit revised files.');
    }
}
