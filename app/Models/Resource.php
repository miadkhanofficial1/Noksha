<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
        'category',
        'title',
        'slug',
        'description',
        'preview_image',
        'preview_images',
        'thumbnail_path',
        'file_path',
        'source_file_path',
        'instruction_pdf_path',
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
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'downloads' => 0,
        'views' => 0,
        'status' => 'approved',
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
            'preview_images' => 'array',
            'is_paid' => 'boolean',
            'price' => 'float',
            'downloads' => 'integer',
            'views' => 'integer',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $base = Str::slug($model->title ?: 'asset');
                $model->slug = $base . '-' . Str::random(6);
            }
            if ($model->is_paid === null) {
                $model->is_paid = ((float) ($model->price ?? 0)) > 0;
            }
            if (empty($model->status)) {
                $model->status = 'approved';
            }
            if (empty($model->category_id)) {
                $model->category_id = Category::first()?->id ?? 1;
            }
            if ($model->downloads === null) {
                $model->downloads = 0;
            }
            if ($model->views === null) {
                $model->views = 0;
            }
        });
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
     * Thumbnail path mutator (maps to preview_image).
     */
    public function setThumbnailPathAttribute($value): void
    {
        $this->attributes['preview_image'] = $value;
    }

    /**
     * Thumbnail path accessor (alias for preview_image).
     */
    public function getThumbnailPathAttribute(): ?string
    {
        return $this->attributes['preview_image'] ?? null;
    }

    /**
     * Category mutator (maps string, integer, or Category instance to category_id).
     */
    public function setCategoryAttribute($value): void
    {
        if (is_numeric($value)) {
            $this->attributes['category_id'] = (int) $value;
        } elseif (is_string($value)) {
            $cat = Category::where('name', $value)->orWhere('slug', $value)->first();
            $this->attributes['category_id'] = $cat?->id ?? Category::first()?->id ?? 1;
        } elseif ($value instanceof Category) {
            $this->attributes['category_id'] = $value->id;
        } else {
            $this->attributes['category_id'] = Category::first()?->id ?? 1;
        }
    }

    /**
     * Tags mutator (supports array or comma-separated string).
     */
    public function setTagsAttribute($value): void
    {
        if (is_string($value)) {
            $tags = array_map('trim', explode(',', $value));
            $this->attributes['tags'] = json_encode(array_values(array_filter($tags)));
        } elseif (is_array($value)) {
            $this->attributes['tags'] = json_encode(array_values($value));
        } else {
            $this->attributes['tags'] = json_encode([]);
        }
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
     * Verified platform reviews for this resource.
     */
    public function platformReviews(): HasMany
    {
        return $this->hasMany(PlatformReview::class, 'asset_id');
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
