<?php

namespace App\Models;

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
        'title',
        'slug',
        'description',
        'prize_amount',
        'start_date',
        'end_date',
        'category_id',
        'status',
        'winner_id',
        'winner_submission_id',
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
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    /**
     * Category associated with contest.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Submissions submitted to this contest.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(ContestSubmission::class, 'contest_id');
    }

    /**
     * Winning seller user.
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    /**
     * Winning submission entry.
     */
    public function winningSubmission(): BelongsTo
    {
        return $this->belongsTo(ContestSubmission::class, 'winner_submission_id');
    }
}
