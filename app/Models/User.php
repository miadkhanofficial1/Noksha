<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

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
        'bio',
        'trust_score',
        'is_verified',
        'is_admin',
        'is_contributor',
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
     * User's resource reviews.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
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
}
