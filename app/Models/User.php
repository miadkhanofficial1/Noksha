<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The "booted" method of the model.
     * Auto-initializes central Wallet and AI Credits for newly created users.
     */
    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->wallet()->firstOrCreate([], [
                'balance' => $user->balance ?? 0.00,
            ]);

            $user->aiCredit()->firstOrCreate([], [
                'credits' => 0, // Initial AI credits: 0
            ]);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'role',
        'status',
        'avatar',
        'cover_image',
        'headline',
        'bio',
        'skills',
        'social_links',
        'is_available',
        'trust_score',
        'is_verified',
        'is_admin',
        'is_contributor',
        'balance',
        'ai_credits',
        'contributor_status',
        'active_mode',
        'nid_or_passport_number',
        'portfolio_link',
        'kyc_document_path',
        'contributor_bio',
        'earnings_balance',
        'kyc_rejected_at',
        'kyc_rejection_reason',
        'demerit_points',
        'late_deliveries_count',
        'reliability_warning',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_verified' => 'boolean',
            'is_admin' => 'boolean',
            'is_contributor' => 'boolean',
            'is_available' => 'boolean',
            'skills' => 'array',
            'social_links' => 'array',
            'balance' => 'float',
            'earnings_balance' => 'float',
            'ai_credits' => 'integer',
            'kyc_rejected_at' => 'datetime',
            'trust_score' => 'float',
            'password' => 'hashed',
            'demerit_points' => 'integer',
            'late_deliveries_count' => 'integer',
            'reliability_warning' => 'boolean',
        ];
    }

    /**
     * Get deposited buyer wallet balance (used for purchases & tokens, not cashout).
     */
    public function getWalletBalanceAttribute(): float
    {
        return (float) ($this->wallet?->balance ?? $this->attributes['balance'] ?? 0.00);
    }

    /**
     * Get seller royalties balance (available for payout/withdrawal).
     */
    public function getEarningsBalanceAttribute(): float
    {
        return (float) ($this->attributes['earnings_balance'] ?? $this->wallet?->earnings_balance ?? 0.00);
    }

    /**
     * Check if user is an administrator.
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin || in_array($this->role, ['admin', 'super_admin']);
    }

    /**
     * Scope a query to only include marketplace regular users (exclude admins).
     */
    public function scopeNonAdmin($query)
    {
        return $query->where('is_admin', false)->whereNotIn('role', ['admin', 'super_admin']);
    }

    /**
     * Check if user is an approved contributor / verified creator.
     */
    public function isContributor(): bool
    {
        return $this->isAdmin() || $this->contributor_status === 'approved';
    }

    /**
     * Check if user is an approved contributor.
     */
    public function isApprovedContributor(): bool
    {
        return $this->isAdmin() || $this->contributor_status === 'approved';
    }

    /**
     * Check if user is in seller / contributor studio mode.
     */
    public function isSellerMode(): bool
    {
        if (!$this->isApprovedContributor()) {
            return false;
        }

        // If explicitly set, respect the active mode. Otherwise default to seller for approved contributors.
        return ($this->active_mode ?? 'seller') === 'seller';
    }

    /**
     * Check if user is in buyer mode.
     */
    public function isBuyerMode(): bool
    {
        return !$this->isSellerMode();
    }

    /**
     * Check if user is a verified creator.
     */
    public function isVerifiedCreator(): bool
    {
        return $this->isContributor();
    }

    /**
     * Check if user is a Pro Author.
     */
    public function isProAuthor(): bool
    {
        return $this->isContributor();
    }

    /**
     * Get user AI generation / edit credits balance.
     */
    public function getCreditsAttribute(): int
    {
        if (isset($this->attributes['ai_credits']) && $this->attributes['ai_credits'] > 0) {
            return (int) $this->attributes['ai_credits'];
        }
        return (int) ($this->aiCredit?->credits ?? $this->attributes['ai_credits'] ?? 0);
    }

    /**
     * Get user AI generation / edit credits balance (ai_credits alias).
     */
    public function getAiCreditsAttribute(): int
    {
        if (isset($this->attributes['ai_credits']) && $this->attributes['ai_credits'] > 0) {
            return (int) $this->attributes['ai_credits'];
        }
        return (int) ($this->aiCredit?->credits ?? $this->attributes['ai_credits'] ?? 0);
    }

    /**
     * User's central BDT cash wallet.
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'user_id');
    }

    /**
     * Ensure the user has an initialized Wallet (0.00 balance if missing).
     */
    public function getWalletAttribute(): Wallet
    {
        if ($this->relationLoaded('wallet') && $this->getRelation('wallet')) {
            return $this->getRelation('wallet');
        }

        $wallet = $this->wallet()->first();
        if (!$wallet && $this->exists) {
            $wallet = $this->wallet()->create([
                'balance' => $this->attributes['balance'] ?? 0.00,
            ]);
            $this->setRelation('wallet', $wallet);
        }

        return $wallet ?? new Wallet(['user_id' => $this->id, 'balance' => 0.00]);
    }

    /**
     * User's AI generation credit account.
     */
    public function aiCredit(): HasOne
    {
        return $this->hasOne(AiCredit::class, 'user_id');
    }

    /**
     * Ensure the user has an initialized AiCredit account (initial 0 credits).
     */
    public function getAiCreditAttribute(): AiCredit
    {
        if ($this->relationLoaded('aiCredit') && $this->getRelation('aiCredit')) {
            return $this->getRelation('aiCredit');
        }

        $credit = $this->aiCredit()->first();
        if (!$credit && $this->exists) {
            $initialCredits = isset($this->attributes['ai_credits']) ? (int) $this->attributes['ai_credits'] : 0;
            $credit = $this->aiCredit()->create([
                'credits' => $initialCredits,
            ]);
            $this->setRelation('aiCredit', $credit);
        }

        return $credit ?? new AiCredit(['user_id' => $this->id, 'credits' => 0]);
    }

    /**
     * Check if user's KYC verification is currently pending review.
     */
    public function isKycPending(): bool
    {
        if ($this->isAdmin() || $this->isContributor()) {
            return false;
        }

        return $this->contributor_status === 'pending'
            || ($this->verification && $this->verification->status === 'pending');
    }

    /**
     * Check if user's KYC verification was rejected.
     */
    public function isKycRejected(): bool
    {
        if ($this->isAdmin() || $this->isContributor()) {
            return false;
        }

        return $this->contributor_status === 'rejected'
            || ($this->verification && $this->verification->status === 'rejected');
    }

    /**
     * Check if user's KYC rejection is within the 7-day cooldown.
     */
    public function isKycRejectedInCooldown(): bool
    {
        if (!$this->isKycRejected()) {
            return false;
        }

        $rejectedAt = $this->kyc_rejected_at ?? $this->verification?->rejected_at ?? $this->verification?->reviewed_at;
        if (!$rejectedAt) {
            return false;
        }

        return now()->lt($rejectedAt->copy()->addDays(7));
    }

    /**
     * Get the date when the 7-day cooldown expires.
     */
    public function getKycCooldownRemainingDate(): ?string
    {
        $rejectedAt = $this->kyc_rejected_at ?? $this->verification?->rejected_at ?? $this->verification?->reviewed_at;
        if (!$rejectedAt) {
            return null;
        }

        return $rejectedAt->copy()->addDays(7)->format('M d, Y h:i A');
    }

    /**
     * Get the rejection reason text.
     */
    public function getKycRejectionReason(): string
    {
        return $this->kyc_rejection_reason 
            ?? $this->verification?->rejection_reason 
            ?? $this->verification?->admin_note 
            ?? $this->verification?->admin_notes 
            ?? 'Document details or selfie verification did not meet compliance criteria.';
    }

    /**
     * Get display role badge text strictly matching user status.
     */
    public function getRoleBadgeLabel(): string
    {
        $isBn = app()->getLocale() === 'bn';

        if ($this->isAdmin()) {
            return $this->role === 'super_admin' ? 'Super Admin' : 'Admin';
        }

        if ($this->isContributor()) {
            return 'Contributor / Creator';
        }

        if ($this->isKycPending()) {
            return 'KYC Pending Review';
        }

        if ($this->isKycRejectedInCooldown()) {
            return 'KYC Cooldown';
        }

        return 'Buyer';
    }

    /**
     * User's seller identity verification record.
     */
    public function verification(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SellerVerification::class, 'user_id');
    }

    /**
     * User's uploaded design resources.
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'user_id');
    }

    /**
     * User's uploaded design templates (alias for resources).
     */
    public function templates(): HasMany
    {
        return $this->hasMany(Template::class, 'user_id');
    }

    /**
     * User's purchase orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * User's resource reviews submitted by this user.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    /**
     * Customer reviews received on this user's marketplace resources.
     */
    public function receivedReviews(): HasManyThrough
    {
        return $this->hasManyThrough(Review::class, Resource::class, 'user_id', 'resource_id');
    }

    /**
     * User's shopping cart items.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    /**
     * User's wishlist items.
     */
    public function wishlistItems(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'user_id');
    }

    /**
     * User's contest submissions (legacy).
     */
    public function contestSubmissions(): HasMany
    {
        return $this->hasMany(ContestSubmission::class, 'user_id');
    }

    /**
     * User's contest entries.
     */
    public function contestEntries(): HasMany
    {
        return $this->hasMany(ContestEntry::class, 'user_id');
    }

    /**
     * Contests organized by this user.
     */
    public function organizedContests(): HasMany
    {
        return $this->hasMany(Contest::class, 'user_id');
    }

    /**
     * User's won contests.
     */
    public function wonContests(): HasMany
    {
        return $this->hasMany(Contest::class, 'winner_id');
    }

    /**
     * User's system notifications.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    /**
     * User's unread system notifications.
     */
    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id')->where('is_read', false);
    }

    /**
     * User's wallet / escrow transactions ledger.
     */
    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'user_id')->latest();
    }

    /**
     * Increment user's wallet balance and record a ledger transaction.
     */
    public function creditBalance(float $amount, string $description, ?string $referenceType = null, ?int $referenceId = null): WalletTransaction
    {
        $this->increment('balance', $amount);
        $freshBalance = (float) $this->fresh()->balance;

        $wallet = $this->wallet;
        if ($wallet) {
            $wallet->update(['balance' => $freshBalance]);
        }

        return WalletTransaction::create([
            'wallet_id' => $wallet?->id,
            'user_id' => $this->id,
            'type' => 'credit',
            'amount' => $amount,
            'balance_after' => $freshBalance,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'description' => $description,
            'status' => 'completed',
        ]);
    }

    /**
     * Users following this user.
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_follows', 'following_id', 'follower_id')->withTimestamps();
    }

    /**
     * Users this user is following.
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_follows', 'follower_id', 'following_id')->withTimestamps();
    }

    /**
     * Check if this user is following another user.
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Payout / withdrawal requests by this seller.
     */
    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class, 'user_id')->latest();
    }

    /**
     * Get avatar URL or default fallback.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (empty($this->avatar)) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff';
        }

        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        $disk = config('filesystems.default', 'public');
        if ($disk === 's3') {
            return \Illuminate\Support\Facades\Storage::disk('s3')->url($this->avatar);
        }

        $cleanPath = ltrim(str_replace('storage/', '', (string) $this->avatar), '/');
        return asset('storage/' . $cleanPath);
    }

    /**
     * Get cover image URL or default fallback.
     */
    public function getCoverUrlAttribute(): ?string
    {
        if (empty($this->cover_image)) {
            return null;
        }

        if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
            return $this->cover_image;
        }

        $disk = config('filesystems.default', 'public');
        if ($disk === 's3') {
            return \Illuminate\Support\Facades\Storage::disk('s3')->url($this->cover_image);
        }

        $cleanPath = ltrim(str_replace('storage/', '', (string) $this->cover_image), '/');
        return asset('storage/' . $cleanPath);
    }

    /**
     * Retrieve the model for a bound value (supports both id and username).
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($field) {
            return parent::resolveRouteBinding($value, $field);
        }

        return $this->where('username', $value)->orWhere('id', $value)->first()
            ?? abort(404, 'User not found.');
    }

    /**
     * Check if user is a seller or creator.
     */
    public function isSellerOrCreator(): bool
    {
        return in_array($this->role, ['seller', 'creator']) || $this->isContributor() || $this->isApprovedContributor();
    }

    /**
     * Conversations where user is the buyer.
     */
    public function buyerConversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'buyer_id');
    }

    /**
     * Conversations where user is the seller.
     */
    public function sellerConversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'seller_id');
    }

    /**
     * Query for all conversations where user is buyer or seller.
     */
    public function conversations()
    {
        return Conversation::where(function ($query) {
            $query->where('buyer_id', $this->id)
                  ->orWhere('seller_id', $this->id);
        });
    }

    /**
     * Accessor for all conversations where user is buyer or seller.
     */
    public function getConversationsAttribute()
    {
        return $this->conversations()->latest('last_message_at')->get();
    }

    /**
     * Creative services listed by this creator.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'user_id');
    }

    /**
     * Messages sent by this user.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Service orders where user is the client buyer.
     */
    public function buyerServiceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'buyer_id');
    }

    /**
     * Alias for buyerServiceOrders.
     */
    public function serviceOrdersAsBuyer(): HasMany
    {
        return $this->buyerServiceOrders();
    }

    /**
     * Service orders where user is the delivering seller.
     */
    public function sellerServiceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'seller_id');
    }

    /**
     * Alias for sellerServiceOrders.
     */
    public function serviceOrdersAsSeller(): HasMany
    {
        return $this->sellerServiceOrders();
    }

    /**
     * Check if user has received a reliability or late delivery warning.
     */
    public function hasReliabilityWarning(): bool
    {
        return (bool) $this->reliability_warning || ($this->demerit_points ?? 0) >= 20 || ($this->late_deliveries_count ?? 0) >= 2;
    }

    /**
     * Increment demerit points and flag reliability warnings if threshold reached.
     */
    public function recordDemerit(int $points = 10, bool $isLateDelivery = false): void
    {
        $this->increment('demerit_points', $points);
        if ($isLateDelivery) {
            $this->increment('late_deliveries_count');
        }

        if ($this->demerit_points >= 20 || $this->late_deliveries_count >= 2) {
            $this->update(['reliability_warning' => true]);
        }
    }

    /**
     * Platform reviews received by this creator.
     */
    public function creatorReviews(): HasMany
    {
        return $this->hasMany(PlatformReview::class, 'creator_id');
    }

    /**
     * Admin adjustment of creator demerits and reliability warning.
     */
    public function setDemerits(int $points, ?bool $warning = null): void
    {
        $data = ['demerit_points' => max(0, $points)];
        if ($warning !== null) {
            $data['reliability_warning'] = $warning;
        } else {
            $data['reliability_warning'] = $points >= 20 || ($this->late_deliveries_count ?? 0) >= 2;
        }
        $this->update($data);
    }

    /**
     * Overall average rating calculated across verified platform reviews & marketplace ratings.
     */
    public function getAverageRatingAttribute(): float
    {
        $platformAvg = $this->creatorReviews()->where('is_complaint', false)->avg('rating');
        $legacyAvg = $this->receivedReviews()->avg('rating');

        if ($platformAvg !== null && $legacyAvg !== null) {
            return round(((float) $platformAvg + (float) $legacyAvg) / 2, 1);
        }
        if ($platformAvg !== null) {
            return round((float) $platformAvg, 1);
        }
        if ($legacyAvg !== null) {
            return round((float) $legacyAvg, 1);
        }
        return 5.0;
    }

    /**
     * Total verified reviews count across services and marketplace items.
     */
    public function getTotalReviewsCountAttribute(): int
    {
        $platformCount = (int) $this->creatorReviews()->where('is_complaint', false)->count();
        $legacyCount = (int) $this->receivedReviews()->count();
        return $platformCount + $legacyCount;
    }

    /**
     * Platform reviews authored by this user.
     */
    public function authoredReviews(): HasMany
    {
        return $this->hasMany(PlatformReview::class, 'reviewer_id');
    }

    /**
     * Get count of unread messages across all active conversations.
     */
    public function unreadMessagesCount(): int
    {
        return Message::whereHas('conversation', function ($query) {
            $query->where('buyer_id', $this->id)
                  ->orWhere('seller_id', $this->id);
        })
        ->where('sender_id', '!=', $this->id)
        ->where('is_read', false)
        ->count();
    }
}
