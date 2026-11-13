<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformReview extends Model
{
    use HasFactory;

    protected $table = 'platform_reviews';

    protected $fillable = [
        'reviewer_id',
        'creator_id',
        'service_id',
        'service_order_id',
        'asset_id',
        'rating',
        'comment',
        'is_complaint',
        'evidence_images',
        'admin_resolved',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_complaint' => 'boolean',
            'admin_resolved' => 'boolean',
            'evidence_images' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (PlatformReview $review) {
            if ($review->service_id) {
                $service = Service::find($review->service_id);
                if ($service) {
                    $avg = static::where('service_id', $service->id)
                        ->where('is_complaint', false)
                        ->avg('rating');
                    $count = static::where('service_id', $service->id)
                        ->where('is_complaint', false)
                        ->count();

                    $service->updateQuietly([
                        'rating_avg' => round($avg ?? 5.00, 2),
                        'reviews_count' => $count,
                    ]);
                }
            }
        });
    }

    /**
     * Get array of absolute/public URLs for evidence images.
     *
     * @return array<int, string>
     */
    public function getEvidenceImageUrlsAttribute(): array
    {
        if (empty($this->evidence_images) || !is_array($this->evidence_images)) {
            return [];
        }

        return array_values(array_filter(array_map(function ($path) {
            if (empty($path)) return null;
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }
            $clean = ltrim(str_replace('storage/', '', $path), '/');
            return asset('storage/' . $clean);
        }, $this->evidence_images)));
    }

    /**
     * User who authored the review.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Creator who received the review.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Service attached to review.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Service order attached to review.
     */
    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class, 'service_order_id');
    }

    /**
     * Marketplace asset/resource attached to review.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'asset_id');
    }
}
