<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
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
        'message',
        'type',
        'action_url',
        'is_read',
        'is_seen',
        'read_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'is_seen' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    /**
     * Recipient user relationship.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper method to dispatch a notification to a specific user.
     * Includes rapid duplicate guard to prevent identical double triggers.
     */
    public static function send(int $userId, string $title, string $message, string $type = 'system', ?string $actionUrl = null): self
    {
        // Guard against duplicate rapid triggers (within last 3 seconds)
        $duplicate = self::where('user_id', $userId)
            ->where('title', $title)
            ->where('message', $message)
            ->where('created_at', '>=', now()->subSeconds(3))
            ->first();

        if ($duplicate) {
            return $duplicate;
        }

        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'action_url' => $actionUrl,
            'is_read' => false,
            'is_seen' => false,
        ]);
    }
}
