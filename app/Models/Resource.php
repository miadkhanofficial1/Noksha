<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'preview_image',
        'file_path',
        'file_type',
        'tags',
        'is_paid',
        'price',
        'requirements',
        'demo_link',
        'status',
        'rejection_reason',
        'downloads',
        'views',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_paid' => 'boolean',
            'price' => 'float',
            'downloads' => 'integer',
            'views' => 'integer',
        ];
    }

    /**
     * Get thumbnail URL accessor (supports S3, local storage, or external URL).
     */
    public function getThumbnailUrlAttribute(): string
    {
        if (empty($this->preview_image)) {
            return asset('images/logo.png');
        }

        if (str_starts_with($this->preview_image, 'http://') || str_starts_with($this->preview_image, 'https://')) {
            return $this->preview_image;
        }

        $disk = config('filesystems.default', 'public');
        if ($disk === 's3') {
            return \Illuminate\Support\Facades\Storage::disk('s3')->url($this->preview_image);
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->preview_image)) {
            return asset('storage/' . $this->preview_image);
        }

        return \Illuminate\Support\Facades\Storage::disk($disk)->url($this->preview_image);
    }

    /**
     * Get downloads count accessor.
     */
    public function getDownloadsCountAttribute(): int
    {
        return (int) $this->downloads;
    }

    /**
     * Resource owner (creator/designer).
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * User alias for owner.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Resource category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Reviews submitted for this resource.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'resource_id');
    }

    /**
     * Order items containing this resource.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'resource_id');
    }

    /**
     * Shopping cart instances containing this resource.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class, 'resource_id');
    }

    /**
     * Wishlist entries for this resource.
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'resource_id');
    }

    /**
     * Get average review rating.
     */
    public function getAverageRatingAttribute(): float
    {
        if (isset($this->attributes['reviews_avg_rating'])) {
            return round((float) $this->attributes['reviews_avg_rating'], 1);
        }
        if (isset($this->attributes['average_rating'])) {
            return (float) $this->attributes['average_rating'];
        }
        if ($this->relationLoaded('reviews')) {
            $avg = $this->reviews->avg('rating');
            return $avg ? round((float) $avg, 1) : 5.0;
        }
        try {
            $avg = $this->reviews()->avg('rating');
            return $avg ? round((float) $avg, 1) : 5.0;
        } catch (\Throwable) {
            return 5.0;
        }
    }

    /**
     * Get count of reviews.
     */
    public function getReviewsCountAttribute(): int
    {
        if (isset($this->attributes['reviews_count'])) {
            return (int) $this->attributes['reviews_count'];
        }
        if ($this->relationLoaded('reviews')) {
            return $this->reviews->count();
        }
        try {
            return (int) $this->reviews()->count();
        } catch (\Throwable) {
            return 0;
        }
    }
}
