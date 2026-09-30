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
        'data',
        'is_read',
        'is_seen',
        'read_at',
    ];

    /**
     * The "booted" method of the model.
     * Automatically maps Laravel notification payload data into display fields.
     */
    protected static function booted(): void
    {
        static::creating(function (Notification $notification) {
            $payload = null;
            if (isset($notification->attributes['data'])) {
                if (is_string($notification->attributes['data'])) {
                    $payload = json_decode($notification->attributes['data'], true);
                } elseif (is_array($notification->attributes['data'])) {
                    $payload = $notification->attributes['data'];
                }
            }

            if (is_array($payload)) {
                if (empty($notification->title) && !empty($payload['title'])) {
                    $notification->title = $payload['title'];
                }
                if (empty($notification->message)) {
                    $prize = $payload['prize'] ?? '';
                    $category = $payload['category'] ?? '';
                    $notification->message = $payload['message']
                        ?? ($prize ? "Bounty: {$prize}" . ($category ? " • Category: {$category}" : '') : 'New design contest launched!');
                }
                if (empty($notification->type) || str_contains($notification->type, '\\')) {
                    $notification->type = $payload['type'] ?? 'contest';
                }
                if (empty($notification->action_url) && !empty($payload['url'])) {
                    $notification->action_url = $payload['url'];
                }
            }

            // Defaults for display consistency
            if (empty($notification->title)) {
                $notification->title = 'New Notification';
            }
            if (!isset($notification->message)) {
                $notification->message = '';
            }
            if (empty($notification->type) || str_contains($notification->type, '\\')) {
                $notification->type = 'system';
            }

            // Prevent string UUID from conflicting with MySQL auto-increment primary key
            if (isset($notification->attributes['id']) && !is_numeric($notification->attributes['id'])) {
                unset($notification->attributes['id']);
            }
        });
    }

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
            'data' => 'array',
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
