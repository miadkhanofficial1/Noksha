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
}
