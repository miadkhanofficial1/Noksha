<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ServiceOrder extends Model
{
    use HasFactory;

    /**
     * Minimum funded service order threshold in BDT.
     */
    public const MINIMUM_ORDER_AMOUNT = 600.00;

    /**
     * Default Noksha platform commission percentage.
     */
    public const DEFAULT_COMMISSION_RATE = 15.00;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'conversation_id',
        'service_id',
        'buyer_id',
        'seller_id',
        'total_amount',
        'platform_commission_rate',
        'platform_fee',
        'seller_payout_amount',
        'status',
        'deadline_at',
        'delivered_at',
        'delivery_archive_path',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'platform_commission_rate' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'seller_payout_amount' => 'decimal:2',
            'deadline_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function (ServiceOrder $order) {
            // Enforce minimum price policy of 600.00 BDT
            if ($order->total_amount !== null && (float) $order->total_amount < self::MINIMUM_ORDER_AMOUNT) {
                throw new InvalidArgumentException(
                    sprintf('Service order amount must be at least %.2f BDT. Given: %.2f BDT.', self::MINIMUM_ORDER_AMOUNT, (float) $order->total_amount)
                );
            }

            // Auto-generate unique order number
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber();
            }

            // Default commission rate
            if ($order->platform_commission_rate === null) {
                $order->platform_commission_rate = self::DEFAULT_COMMISSION_RATE;
            }

            // Calculate fees and seller payout
            if ($order->total_amount !== null) {
                $financials = static::calculateFinancials((float) $order->total_amount, (float) $order->platform_commission_rate);
                if ($order->platform_fee === null || (float) $order->platform_fee == 0.00) {
                    $order->platform_fee = $financials['platform_fee'];
                }
                if ($order->seller_payout_amount === null || (float) $order->seller_payout_amount == 0.00) {
                    $order->seller_payout_amount = $financials['seller_payout_amount'];
                }
            }
        });
    }

    /**
     * Generate a unique order identifier (e.g., NOK-SRV-ABC123XY).
     */
    public static function generateOrderNumber(): string
    {
        do {
            $number = 'NOK-SRV-' . strtoupper(Str::random(8));
        } while (static::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * Compute platform commission and net creator payout.
     *
     * @return array{platform_fee: float, seller_payout_amount: float}
     */
    public static function calculateFinancials(float $totalAmount, float $commissionRate = self::DEFAULT_COMMISSION_RATE): array
    {
        $platformFee = round($totalAmount * ($commissionRate / 100), 2);
        $sellerPayout = round($totalAmount - $platformFee, 2);

        return [
            'platform_fee' => $platformFee,
            'seller_payout_amount' => $sellerPayout,
        ];
    }

    /**
     * Conversation thread tied to this escrow order.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    /**
     * Service catalogue offering being purchased.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Buyer customer who funded the order.
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Creator / Seller fulfilling the order.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Determine if order escrow has been funded and work is active or being reviewed.
     */
    public function isFunded(): bool
    {
        return in_array($this->status, [
            'funded_in_progress',
            'delivered',
            'completed',
            'disputed',
        ]);
    }

    /**
     * Determine if order is pending creator acceptance.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending_approval';
    }

    /**
     * Determine if order has been delivered by seller.
     */
    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    /**
     * Determine if order has been accepted and escrow released.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Determine if order is in arbitration dispute.
     */
    public function isDisputed(): bool
    {
        return $this->status === 'disputed';
    }
}
