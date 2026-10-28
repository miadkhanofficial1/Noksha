<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'conversations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'buyer_id',
        'seller_id',
        'conversation_type',
        'is_frozen',
        'left_by_user_id',
        'reopen_requested_by',
        'reopen_status',
        'active_order_id',
        'last_message_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_frozen' => 'boolean',
            'last_message_at' => 'datetime',
        ];
    }

    /**
     * Buyer participant in the conversation.
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Seller / Creator participant in the conversation.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Participant who chose to leave or freeze the conversation.
     */
    public function leftBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'left_by_user_id');
    }

    /**
     * Participant who requested to reopen a frozen conversation.
     */
    public function reopenRequestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reopen_requested_by');
    }

    /**
     * Currently active funded service order linked to this conversation.
     */
    public function activeOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class, 'active_order_id');
    }

    /**
     * Messages exchanged inside this conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id')->oldest();
    }

    /**
     * Latest message exchanged in the conversation.
     */
    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class, 'conversation_id')->latestOfMany();
    }

    /**
     * All service orders initiated within this conversation thread.
     */
    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'conversation_id')->latest();
    }

    /**
     * Determine if chat interaction is locked.
     * Returns true if is_frozen is true OR activeOrder is missing/not funded.
     */
    public function isChatLocked(?User $user = null): bool
    {
        // Admin interacting in live_agent support mode bypasses lock
        if ($user && $user->isAdmin() && $this->conversation_type === 'live_agent') {
            return false;
        }

        // 1. Thread is explicitly frozen
        if ($this->is_frozen) {
            return true;
        }

        // 2. Active order is missing or not funded
        $order = $this->activeOrder;
        if (!$order || !$order->isFunded()) {
            return true;
        }

        return false;
    }

    /**
     * Determine if conversation is frozen.
     */
    public function isFrozen(): bool
    {
        return (bool) $this->is_frozen;
    }

    /**
     * Freeze the conversation when a participant leaves.
     */
    public function freeze(int|User $user): bool
    {
        $userId = $user instanceof User ? $user->id : $user;

        return $this->update([
            'is_frozen' => true,
            'left_by_user_id' => $userId,
            'reopen_status' => 'none',
            'reopen_requested_by' => null,
        ]);
    }

    /**
     * Unfreeze / restore the conversation.
     */
    public function unfreeze(): bool
    {
        return $this->update([
            'is_frozen' => false,
            'left_by_user_id' => null,
            'reopen_status' => 'approved',
            'reopen_requested_by' => null,
        ]);
    }

    /**
     * Request reopening of a frozen conversation.
     */
    public function requestReopen(int|User $user): bool
    {
        $userId = $user instanceof User ? $user->id : $user;

        return $this->update([
            'reopen_requested_by' => $userId,
            'reopen_status' => 'requested',
        ]);
    }

    /**
     * Approve reopen request and unfreeze thread.
     */
    public function approveReopen(): bool
    {
        return $this->unfreeze();
    }

    /**
     * Decline reopen request while keeping thread frozen.
     */
    public function declineReopen(): bool
    {
        return $this->update([
            'reopen_status' => 'declined',
        ]);
    }

    /**
     * Check if a given user is an active participant in this conversation.
     */
    public function isParticipant(int|User $user): bool
    {
        $userId = $user instanceof User ? $user->id : $user;

        return (int) $this->buyer_id === (int) $userId || (int) $this->seller_id === (int) $userId;
    }

    /**
     * Retrieve the counterpart participant relative to the given user.
     */
    public function getOtherParticipant(int|User $user): ?User
    {
        $userId = $user instanceof User ? $user->id : $user;

        if ((int) $this->buyer_id === (int) $userId) {
            return $this->seller;
        }

        if ((int) $this->seller_id === (int) $userId) {
            return $this->buyer;
        }

        return null;
    }
}
