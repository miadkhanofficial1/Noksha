<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConversationPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     * Super Admin bypass for full platform moderation and support broadcasts.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view the conversation.
     */
    public function view(User $user, Conversation $conversation): bool
    {
        return $conversation->isParticipant($user);
    }

    /**
     * Determine whether the initiator can start a conversation thread with the recipient.
     *
     * Rules:
     * 1. Buyer-to-Seller only initiation: Normal buyers can only start threads with sellers/creators.
     * 2. Sellers can initiate threads with other sellers/creators.
     * 3. Sellers CANNOT cold-message buyers who have not already initiated a thread.
     * 4. Super Admin bypass: Admin can send one-way 'system_support' broadcasts or participate as 'live_agent'.
     */
    public function initiate(User $initiator, User $recipient, string $conversationType = 'direct'): bool
    {
        // Cannot message oneself
        if ($initiator->id && $recipient->id && (int) $initiator->id === (int) $recipient->id) {
            return false;
        }

        // Super Admin bypass: Admins can initiate system_support, live_agent, or direct threads
        if ($initiator->isAdmin()) {
            return true;
        }

        // Only admins can initiate one-way system_support broadcasts
        if ($conversationType === 'system_support') {
            return false;
        }

        // Live agent support thread initiation with admin/support
        if ($conversationType === 'live_agent') {
            return $recipient->isAdmin();
        }

        $isInitiatorSeller = $initiator->isSellerOrCreator();
        $isRecipientSeller = $recipient->isSellerOrCreator();

        // Case 1: Buyer-to-Seller only initiation
        // Normal buyers can only start threads with users where role === 'seller' / 'creator'
        if (!$isInitiatorSeller) {
            return $isRecipientSeller;
        }

        // Case 2: Sellers can initiate threads with other sellers/creators
        if ($isInitiatorSeller && $isRecipientSeller) {
            return true;
        }

        // Case 3: Sellers CANNOT cold-message buyers who have not already initiated a thread
        if ($isInitiatorSeller && !$isRecipientSeller) {
            return Conversation::where('buyer_id', $recipient->id)
                ->where('seller_id', $initiator->id)
                ->exists();
        }

        return false;
    }

    /**
     * Alias for initiate() to support standard Laravel Resource policy conventions.
     */
    public function create(User $initiator, ?User $recipient = null, string $conversationType = 'direct'): bool
    {
        if ($recipient === null) {
            return true;
        }

        return $this->initiate($initiator, $recipient, $conversationType);
    }

    /**
     * Determine whether the user can send a message within the conversation.
     *
     * Rules:
     * 1. User must be a participant (buyer or seller) or admin.
     * 2. System support broadcasts are one-way (replies disabled for non-admins).
     * 3. Frozen threads do not accept new messages.
     * 4. Chat is locked if activeOrder is missing or not funded.
     */
    public function sendMessage(User $user, Conversation $conversation): bool
    {
        // Super Admin bypass: Admin can send one-way broadcasts or participate as live agent
        if ($user->isAdmin()) {
            return true;
        }

        // User must participate in the thread
        if (!$conversation->isParticipant($user)) {
            return false;
        }

        // System support broadcasts are one-way; non-admins cannot reply
        if ($conversation->conversation_type === 'system_support') {
            return false;
        }

        // If thread is frozen, messages cannot be posted until reopened
        if ($conversation->isFrozen()) {
            return false;
        }

        // Escrow chat locking policy: chat is locked if activeOrder is missing/not funded
        if ($conversation->isChatLocked($user)) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can freeze / leave the conversation.
     */
    public function freeze(User $user, Conversation $conversation): bool
    {
        return $conversation->isParticipant($user) && !$conversation->isFrozen();
    }

    /**
     * Determine whether the user can request to reopen a frozen conversation.
     */
    public function requestReopen(User $user, Conversation $conversation): bool
    {
        return $conversation->isParticipant($user)
            && $conversation->isFrozen()
            && $conversation->reopen_status !== 'requested';
    }

    /**
     * Determine whether the user can approve or decline a reopen request.
     * Only the counterpart participant (or an admin) can respond to reopen requests.
     */
    public function respondReopen(User $user, Conversation $conversation): bool
    {
        if (!$conversation->isParticipant($user) || !$conversation->isFrozen()) {
            return false;
        }

        return $conversation->reopen_status === 'requested'
            && (int) $conversation->reopen_requested_by !== (int) $user->id;
    }
}
