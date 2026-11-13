<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use InvalidArgumentException;

class Service extends Model
{
    use HasFactory;

    /**
     * Minimum price allowed for a Noksha creative service in BDT.
     */
    public const MINIMUM_PRICE = 600.00;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'services';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category',
        'description',
        'starting_price',
        'price',
        'delivery_days',
        'duration_days',
        'concepts_count',
        'revisions_count',
        'max_slots',
        'active_orders_count',
        'preview_images',
        'status',
        'is_active',
        'rating_avg',
        'reviews_count',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starting_price' => 'decimal:2',
            'price' => 'decimal:2',
            'delivery_days' => 'integer',
            'duration_days' => 'integer',
            'concepts_count' => 'integer',
            'revisions_count' => 'integer',
            'max_slots' => 'integer',
            'active_orders_count' => 'integer',
            'preview_images' => 'array',
            'rating_avg' => 'decimal:2',
            'reviews_count' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function (Service $service) {
            // Synchronize price & starting_price
            if ($service->starting_price !== null && $service->price === null) {
                $service->price = $service->starting_price;
            } elseif ($service->price !== null && $service->starting_price === null) {
                $service->starting_price = $service->price;
            }

            // Synchronize delivery_days & duration_days
            if ($service->delivery_days !== null && $service->duration_days === null) {
                $service->duration_days = $service->delivery_days;
            } elseif ($service->duration_days !== null && $service->delivery_days === null) {
                $service->delivery_days = $service->duration_days;
            }

            // Synchronize status & is_active
            if ($service->status === 'active') {
                $service->is_active = true;
            } elseif (in_array($service->status, ['paused', 'suspended'])) {
                $service->is_active = false;
            } elseif ($service->is_active !== null) {
                $service->status = $service->is_active ? 'active' : 'paused';
            }

            $effectivePrice = $service->starting_price ?? $service->price;

            // Enforce minimum price policy of 600.00 BDT
            if ($effectivePrice !== null && (float) $effectivePrice < self::MINIMUM_PRICE) {
                throw new InvalidArgumentException(
                    sprintf('Service price must be at least %.2f BDT. Given: %.2f BDT.', self::MINIMUM_PRICE, (float) $effectivePrice)
                );
            }

            // Auto-generate slug if not provided
            if (empty($service->slug) && !empty($service->title)) {
                $baseSlug = Str::slug($service->title);
                $slug = $baseSlug;
                $counter = 1;

                while (static::where('slug', $slug)->where('id', '!=', $service->id ?? 0)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $service->slug = $slug;
            }
        });
    }

    /**
     * Creator / Seller who owns this service.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Creator alias for user relationship.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Orders placed for this service.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'service_id');
    }

    /**
     * Alias for orders relationship.
     */
    public function serviceOrders(): HasMany
    {
        return $this->orders();
    }

    /**
     * Reviews received for this service.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(PlatformReview::class, 'service_id');
    }

    /**
     * Check if service can take more concurrent client orders.
     */
    public function hasAvailableSlot(): bool
    {
        return ($this->active_orders_count ?? 0) < ($this->max_slots ?? 3);
    }

    /**
     * Compute remaining available weekly/active slots.
     */
    public function getAvailableSlotsAttribute(): int
    {
        return max(0, (int) ($this->max_slots ?? 3) - (int) ($this->active_orders_count ?? 0));
    }

    /**
     * Primary preview image URL.
     */
    public function getPrimaryPreviewUrlAttribute(): ?string
    {
        if (is_array($this->preview_images) && count($this->preview_images) > 0) {
            $first = $this->preview_images[0];
            return str_starts_with($first, 'http') ? $first : asset('storage/' . $first);
        }

        return null;
    }

    /**
     * Scope query to only active services.
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'active')->orWhere('is_active', true);
        });
    }
}
