<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContestSubmission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contest_id',
        'user_id',
        'title',
        'preview_image',
        'design_file',
        'note',
        'is_winner',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_winner' => 'boolean',
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
     * Submitting user (creator/seller).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
