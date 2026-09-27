<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContestEntryLike extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contest_entry_likes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contest_entry_id',
        'user_id',
        'ip_address',
    ];

    /**
     * Associated entry.
     */
    public function entry(): BelongsTo
    {
        return $this->belongsTo(ContestEntry::class, 'contest_entry_id');
    }

    /**
     * Liked by user (nullable).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
