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
                'credits' => 5, // Grant 5 free starter credits
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
        'contributor_status',
        'kyc_rejected_at',
        'kyc_rejection_reason',
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
            'kyc_rejected_at' => 'datetime',
            'trust_score' => 'float',
            'password' => 'hashed',
        ];
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
        return $this->isAdmin()
            || (bool) $this->is_contributor
            || $this->contributor_status === 'approved'
            || (bool) $this->is_verified
            || $this->role === 'seller';
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
        return $this->aiCredit?->credits ?? 5;
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
     * Ensure the user has an initialized AiCredit account (5 free starter credits if missing).
     */
    public function getAiCreditAttribute(): AiCredit
    {
        if ($this->relationLoaded('aiCredit') && $this->getRelation('aiCredit')) {
            return $this->getRelation('aiCredit');
        }

        $credit = $this->aiCredit()->first();
        if (!$credit && $this->exists) {
            $credit = $this->aiCredit()->create([
                'credits' => 5, // Grant 5 free starter credits
            ]);
            $this->setRelation('aiCredit', $credit);
        }

        return $credit ?? new AiCredit(['user_id' => $this->id, 'credits' => 5]);
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
        if ($this->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff';
    }

    /**
     * Get cover image URL or default fallback.
     */
    public function getCoverUrlAttribute(): ?string
    {
        if ($this->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->cover_image)) {
            return asset('storage/' . $this->cover_image);
        }
        return null;
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
}
