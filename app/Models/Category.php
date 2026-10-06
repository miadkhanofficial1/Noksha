<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'parent_id',
    ];

    /**
     * Parent category relationship.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Subcategories relationship.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Resources belonging to this category.
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'category_id');
    }

    /**
     * Ensure default marketplace categories exist in the database.
     */
    public static function ensureDefaultCategoriesExist(): void
    {
        try {
            if (static::count() === 0) {
                $defaults = [
                    ['name' => 'Graphics', 'slug' => 'graphics', 'icon' => 'bi-palette'],
                    ['name' => 'UI Kit', 'slug' => 'ui-kit', 'icon' => 'bi-layers-fill'],
                    ['name' => 'Mockups', 'slug' => 'mockups', 'icon' => 'bi-box'],
                    ['name' => 'Templates', 'slug' => 'templates', 'icon' => 'bi-window'],
                    ['name' => 'Illustrations', 'slug' => 'illustrations', 'icon' => 'bi-stars'],
                    ['name' => 'Icons', 'slug' => 'icons', 'icon' => 'bi-vector-pen'],
                    ['name' => 'Fonts', 'slug' => 'fonts', 'icon' => 'bi-fonts'],
                ];

                $now = now();
                foreach ($defaults as $item) {
                    static::updateOrCreate(
                        ['slug' => $item['slug']],
                        [
                            'name' => $item['name'],
                            'icon' => $item['icon'],
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    );
                }
            }
        } catch (\Throwable) {
            // Failsafe in case table is not migrated yet
        }
    }
}
