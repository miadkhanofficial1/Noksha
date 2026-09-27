<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContestEntry extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contest_entries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contest_id',
        'user_id',
        'title',
        'description',
        'clean_preview_image',
        'watermarked_preview_image',
        'source_file_link',
        'client_rating',
        'client_feedback',
        'likes_count',
        'is_winner',
        'handover_files',
        'handover_notes',
        'handover_submitted_at',
        'handover_status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'client_rating' => 'integer',
            'likes_count' => 'integer',
            'is_winner' => 'boolean',
            'handover_files' => 'array',
            'handover_submitted_at' => 'datetime',
        ];
    }

    /**
     * Parent contest.
     */
    public function contest(): BelongsTo
    {
        return $this->belongsTo(Contest::class, 'contest_id');
    }

    /**
     * Submitting contributor user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Community likes for this entry.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(ContestEntryLike::class, 'contest_entry_id');
    }

    /**
     * Check if a specific user or IP has liked this entry.
     */
    public function isLikedBy(?int $userId, ?string $ipAddress = null): bool
    {
        if ($userId) {
            return $this->likes()->where('user_id', $userId)->exists();
        }

        if ($ipAddress) {
            return $this->likes()->where('ip_address', $ipAddress)->exists();
        }

        return false;
    }

    /**
     * Check if entry handover source files are pending submission.
     */
    public function isHandoverPending(): bool
    {
        return $this->handover_status === 'pending';
    }

    /**
     * Check if entry handover source files have been submitted.
     */
    public function isHandoverSubmitted(): bool
    {
        return $this->handover_status === 'submitted';
    }

    /**
     * Check if revision has been requested on handover.
     */
    public function isHandoverRevisionRequested(): bool
    {
        return $this->handover_status === 'revision_requested';
    }

    /**
     * Check if handover is approved by client.
     */
    public function isHandoverApproved(): bool
    {
        return $this->handover_status === 'approved';
    }

    /**
     * Get primary handover file record if uploaded.
     */
    public function getPrimaryHandoverFile(): ?array
    {
        if (is_array($this->handover_files) && !empty($this->handover_files)) {
            return $this->handover_files[0] ?? null;
        }

        return null;
    }
}
