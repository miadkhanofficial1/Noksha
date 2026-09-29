<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiCredit extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ai_credits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'credits',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'credits' => 'integer',
        ];
    }

    /**
     * Associated user account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Add AI generation credits to account.
     */
    public function addCredits(int $count): self
    {
        $this->increment('credits', $count);
        return $this;
    }

    /**
     * Consume credits for AI generation task.
     */
    public function consume(int $count = 1): bool
    {
        if ($this->credits < $count) {
            return false;
        }

        $this->decrement('credits', $count);
        return true;
    }

    /**
     * Check if user has sufficient AI credits.
     */
    public function hasCredits(int $count = 1): bool
    {
        return $this->credits >= $count;
    }
}
