<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'role',
        'status',
        'avatar',
        'bio',
        'trust_score',
        'is_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_verified' => 'boolean',
            'trust_score' => 'float',
            'password' => 'hashed',
        ];
    }

    /**
     * User's seller identity verification record.
     */
    public function verification(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SellerVerification::class, 'user_id');
    }

    /**
     * User's uploaded design resources.
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'user_id');
    }

    /**
     * User's purchase orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * User's resource reviews.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    /**
     * User's shopping cart items.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    /**
     * User's wishlist items.
     */
    public function wishlistItems(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'user_id');
    }

    /**
     * User's contest submissions.
     */
    public function contestSubmissions(): HasMany
    {
        return $this->hasMany(ContestSubmission::class, 'user_id');
    }

    /**
     * User's won contests.
     */
    public function wonContests(): HasMany
    {
        return $this->hasMany(Contest::class, 'winner_id');
    }

    /**
     * User's system notifications.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
}
