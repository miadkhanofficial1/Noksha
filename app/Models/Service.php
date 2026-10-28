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
        'description',
        'price',
        'duration_days',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_days' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function (Service $service) {
            // Enforce minimum price policy of 600.00 BDT
            if ($service->price !== null && (float) $service->price < self::MINIMUM_PRICE) {
                throw new InvalidArgumentException(
                    sprintf('Service price must be at least %.2f BDT. Given: %.2f BDT.', self::MINIMUM_PRICE, (float) $service->price)
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
     * Scope query to only active services.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
