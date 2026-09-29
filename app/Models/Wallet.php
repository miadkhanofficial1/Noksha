<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wallets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'balance',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'balance' => 'float',
        ];
    }

    /**
     * Associated user account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Wallet transactions history.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id');
    }

    /**
     * Deposit BDT funds into wallet and record transaction.
     */
    public function deposit(float $amount, string $description = 'Wallet deposit', string $type = 'deposit', int $creditsTransacted = 0): WalletTransaction
    {
        $this->increment('balance', $amount);

        // Also update users.balance if column exists
        if ($this->user) {
            $this->user->update(['balance' => $this->balance]);
        }

        return $this->transactions()->create([
            'user_id' => $this->user_id,
            'type' => $type,
            'amount' => $amount,
            'credits_transacted' => $creditsTransacted,
            'balance_after' => $this->balance,
            'description' => $description,
            'status' => 'completed',
        ]);
    }

    /**
     * Deduct funds from wallet if sufficient balance exists.
     */
    public function deduct(float $amount, string $description = 'Wallet deduction', string $type = 'template_purchase'): ?WalletTransaction
    {
        if (!$this->hasBalance($amount)) {
            return null;
        }

        $this->decrement('balance', $amount);

        if ($this->user) {
            $this->user->update(['balance' => $this->balance]);
        }

        return $this->transactions()->create([
            'user_id' => $this->user_id,
            'type' => $type,
            'amount' => $amount,
            'credits_transacted' => 0,
            'balance_after' => $this->balance,
            'description' => $description,
            'status' => 'completed',
        ]);
    }

    /**
     * Check if wallet has sufficient balance.
     */
    public function hasBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }
}
