<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageAttachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MessageController extends Controller
{
    /**
     * Regex pattern to detect phone numbers (e.g. BD mobile and international formats).
     */
    protected const PHONE_REGEX = '/(?:\+?880?|01)\d{8,9}|\b(?:\+?88|01)?\d{9,11}\b|\b\d{10,14}\b/';

    /**
     * Regex pattern to detect email addresses.
     */
    protected const EMAIL_REGEX = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';

    /**
     * Regex pattern to detect external links and social channels.
     */
    protected const EXTERNAL_LINKS_REGEX = '/(https?:\/\/|www\.|wa\.me|wa\.link|facebook\.com|fb\.com|t\.me|telegram|whatsapp|instagram\.com|discord\.gg|linkedin\.com|skype|zoom\.us)/i';

    /**
     * Store and dispatch a new message within a conversation.
     * Enforces strict contact leak guard and attachment authorization.
     */
    public function store(Request $request, Conversation $conversation): JsonResponse|RedirectResponse
    {
        $user = Auth::user();

        // 1. Authorize thread participant or administrator
        if (!$conversation->isParticipant($user) && !$user->isAdmin()) {
            abort(403, 'Unauthorized access to this conversation thread.');
        }

        // 2. Enforce system support broadcast rules (replies strictly disabled for non-admins)
        if ($conversation->conversation_type === 'system_support' && !$user->isAdmin()) {
            return $this->respondError(
                'This is an official system notice. Replies are disabled.',
                $request,
                403
            );
        }

        // 3. Enforce frozen conversation policy
        if ($conversation->isFrozen() && !$user->isAdmin()) {
            return $this->respondError(
                'Conversation is frozen. Please request to re-open to send messages.',
                $request,
                403
            );
        }

        // Validate payload
        $request->validate([
            'body' => 'nullable|string|max:5000',
            'attachment' => 'nullable|file|max:51200', // max 50MB
            'message_type' => 'nullable|string|in:text,service_proposal,order_status,system_notice',
        ]);

        $body = trim((string) $request->input('body', ''));
        $hasAttachment = $request->hasFile('attachment');

        if (empty($body) && !$hasAttachment) {
            return $this->respondError('Message content or attachment is required.', $request, 422);
        }

        // 4. Check whether conversation has an active funded order
        $activeOrder = $conversation->activeOrder;
        $isFunded = $activeOrder && $activeOrder->isFunded();

        // 5. ANTI-BYPASS / PRIVACY GUARD: Validate against sensitive contact leakage BEFORE order is funded
        if (!$isFunded && !$user->isAdmin()) {
            if ($this->containsSensitiveContactInfo($body)) {
                return $this->respondError(
                    '⚠️ Security Policy Alert: Sharing personal phone numbers, email addresses, or external links outside Noksha is strictly prohibited.',
                    $request,
                    422
                );
            }
        }

        // 6. ATTACHMENT LOCK POLICY: Strictly disabled until order is 'funded_in_progress'
        if ($hasAttachment) {
            $isOrderFundedInProgress = $activeOrder && $activeOrder->status === 'funded_in_progress';

            if (!$isOrderFundedInProgress && !$user->isAdmin()) {
                return $this->respondError(
                    '⚠️ File attachments remain locked until a service order has been funded in progress.',
                    $request,
                    422
                );
            }
        }

        // 7. Persist Message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => $body,
            'message_type' => $request->input('message_type', 'text'),
            'is_read' => false,
            'metadata' => $request->input('metadata', null),
        ]);

        // 8. Handle Attachment upload if provided and authorized
        if ($hasAttachment) {
            $file = $request->file('attachment');
            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getClientMimeType() ?: 'application/octet-stream';
            $fileSize = $file->getSize();

            $disk = config('filesystems.default', 's3');
            $path = $file->store('inbox/attachments/' . $conversation->id, $disk);

            MessageAttachment::create([
                'message_id' => $message->id,
                'file_name' => $originalName,
                'file_path' => $path,
                'file_type' => $mimeType,
                'file_size' => $fileSize,
            ]);
        }

        // Touch conversation activity
        $conversation->update(['last_message_at' => now()]);

        if ($request->expectsJson() || $request->ajax()) {
            $message->load(['sender', 'attachments']);
            return response()->json([
                'status' => 'success',
                'message' => 'Message dispatched successfully.',
                'data' => $message,
            ]);
        }

        return redirect()->route('inbox.show', $conversation->id)
            ->with('success', 'Message sent successfully.');
    }

    /**
     * Inspect text for sensitive contact info (phone, email, external URLs/socials).
     */
    protected function containsSensitiveContactInfo(string $text): bool
    {
        if (empty($text)) {
            return false;
        }

        // 1. Email check
        if (preg_match(self::EMAIL_REGEX, $text)) {
            return true;
        }

        // 2. External links & social domains check
        if (preg_match(self::EXTERNAL_LINKS_REGEX, $text)) {
            return true;
        }

        // 3. Phone check (clean whitespace, dots, and hyphens to catch spaced numbers like 0 1 7 ...)
        $stripped = preg_replace('/[\s\-\.\(\)]/', '', $text);
        if (preg_match(self::PHONE_REGEX, $text) || preg_match('/(?:\+?88|01)\d{8,11}/', $stripped)) {
            return true;
        }

        return false;
    }

    /**
     * Format standardized error response for both JSON and standard web requests.
     */
    protected function respondError(string $message, Request $request, int $statusCode = 422): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => $message,
            ], $statusCode);
        }

        return back()
            ->withInput()
            ->withErrors(['body' => $message])
            ->with('error', $message);
    }
}
