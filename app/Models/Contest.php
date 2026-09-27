<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category',
        'category_id',
        'description',
        'required_dimensions',
        'attachment_file',
        'prize_amount',
        'posting_fee',
        'payment_reference',
        'is_guaranteed',
        'status',
        'rejection_reason',
        'start_date',
        'end_date',
        'deadline',
        'winner_id',
        'winner_submission_id',
        'winner_entry_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'prize_amount' => 'float',
            'posting_fee' => 'float',
            'is_guaranteed' => 'boolean',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'deadline' => 'datetime',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function (Contest $contest) {
            if ($contest->deadline && !$contest->end_date) {
                $contest->end_date = $contest->deadline;
            } elseif ($contest->end_date && !$contest->deadline) {
                $contest->deadline = $contest->end_date;
            }
            if (!$contest->start_date) {
                $contest->start_date = now();
            }
        });
    }

    /**
     * Organizer / Buyer who created this contest.
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Category associated with contest.
     */
    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Entries submitted to this contest.
     */
    public function entries(): HasMany
    {
        return $this->hasMany(ContestEntry::class, 'contest_id')->latest();
    }

    /**
     * Legacy Submissions submitted to this contest.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(ContestSubmission::class, 'contest_id');
    }

    /**
     * Winning contributor user.
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    /**
     * Winning contest entry.
     */
    public function winningEntry(): BelongsTo
    {
        return $this->belongsTo(ContestEntry::class, 'winner_entry_id');
    }

    /**
     * Legacy winning submission entry.
     */
    public function winningSubmission(): BelongsTo
    {
        return $this->belongsTo(ContestSubmission::class, 'winner_submission_id');
    }

    /**
     * Check if contest is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if contest is pending approval.
     */
    public function isPendingApproval(): bool
    {
        return $this->status === 'pending_approval';
    }

    /**
     * Check if contest is in judging phase.
     */
    public function isJudging(): bool
    {
        return $this->status === 'judging';
    }

    /**
     * Check if contest is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if contest is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if contest is expired/past deadline.
     */
    public function isExpired(): bool
    {
        $d = $this->effective_deadline;
        return $d ? now()->gt($d) : false;
    }

    /**
     * Get effective deadline timestamp.
     */
    public function getEffectiveDeadlineAttribute(): ?Carbon
    {
        return $this->deadline ?? $this->end_date;
    }

    /**
     * Get remaining time in friendly human format.
     */
    public function getRemainingTimeAttribute(): string
    {
        $d = $this->effective_deadline;
        if (!$d) {
            return 'Ongoing';
        }

        if (now()->gt($d)) {
            return $this->status === 'completed' ? 'Ended' : 'In Judging';
        }

        $diff = now()->diff($d);
        if ($diff->days > 0) {
            return "{$diff->days}d {$diff->h}h left";
        }
        if ($diff->h > 0) {
            return "{$diff->h}h {$diff->i}m left";
        }
        return "{$diff->i}m left";
    }

    /**
     * Display category label.
     */
    public function getDisplayCategoryAttribute(): string
    {
        if (!empty($this->category)) {
            return $this->category;
        }

        return $this->categoryRelation?->name ?? 'Graphic Design';
    }

    /**
     * Check if a specific user has already submitted an entry (enforce single entry).
     */
    public function hasUserEntered(?int $userId): bool
    {
        if (!$userId) {
            return false;
        }

        return $this->entries()->where('user_id', $userId)->exists()
            || $this->submissions()->where('user_id', $userId)->exists();
    }

    /**
     * Retrieve the model for a bound value (supports both id and slug).
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($field) {
            return parent::resolveRouteBinding($value, $field);
        }

        return $this->where('id', $value)->orWhere('slug', $value)->first()
            ?? abort(404);
    }
}

