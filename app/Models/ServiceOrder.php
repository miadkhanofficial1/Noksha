<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * Default Noksha platform commission percentage (15%).
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
        'order_code',
        'order_number',
        'service_id',
        'conversation_id',
        'buyer_id',
        'seller_id',
        'custom_title',
        'requirements_brief',
        'amount',
        'total_amount',
        'platform_commission_rate',
        'platform_fee',
        'seller_payout',
        'seller_payout_amount',
        'delivery_days',
        'revisions_allowed',
        'revisions_used',
        'deadline_at',
        'extension_used',
        'deadline_notified_12h',
        'deadline_notified_overdue',
        'status',
        'delivery_preview_images',
        'delivery_source_file',
        'delivery_instruction_pdf',
        'delivery_manifest',
        'delivered_at',
        'completed_at',
        'minor_fix_used',
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
            'amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'platform_commission_rate' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'seller_payout' => 'decimal:2',
            'seller_payout_amount' => 'decimal:2',
            'delivery_days' => 'integer',
            'revisions_allowed' => 'integer',
            'revisions_used' => 'integer',
            'extension_used' => 'boolean',
            'deadline_notified_12h' => 'boolean',
            'deadline_notified_overdue' => 'boolean',
            'minor_fix_used' => 'boolean',
            'delivery_preview_images' => 'array',
            'delivery_manifest' => 'array',
            'deadline_at' => 'datetime',
            'delivered_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function (ServiceOrder $order) {
            // Synchronize amount and total_amount
            if ($order->amount !== null && $order->total_amount === null) {
                $order->total_amount = $order->amount;
            } elseif ($order->total_amount !== null && $order->amount === null) {
                $order->amount = $order->total_amount;
            }

            $effectiveAmount = $order->amount ?? $order->total_amount;

            // Enforce minimum price policy of 600.00 BDT
            if ($effectiveAmount !== null && (float) $effectiveAmount < self::MINIMUM_ORDER_AMOUNT) {
                throw new InvalidArgumentException(
                    sprintf('Service order amount must be at least %.2f BDT. Given: %.2f BDT.', self::MINIMUM_ORDER_AMOUNT, (float) $effectiveAmount)
                );
            }

            // Auto-generate unique order code
            if (empty($order->order_code)) {
                $order->order_code = static::generateOrderCode();
            }
            if (empty($order->order_number)) {
                $order->order_number = $order->order_code;
            }

            // Default commission rate
            if ($order->platform_commission_rate === null) {
                $order->platform_commission_rate = self::DEFAULT_COMMISSION_RATE;
            }

            // Calculate fees and seller payout
            if ($effectiveAmount !== null) {
                $financials = static::calculateFinancials((float) $effectiveAmount, (float) $order->platform_commission_rate);
                if ($order->platform_fee === null || (float) $order->platform_fee == 0.00) {
                    $order->platform_fee = $financials['platform_fee'];
                }
                if ($order->seller_payout === null || (float) $order->seller_payout == 0.00) {
                    $order->seller_payout = $financials['seller_payout'];
                    $order->seller_payout_amount = $financials['seller_payout'];
                }
            }
        });
    }

    /**
     * Generate a unique order identifier (e.g., NOK-SRV-8X92LA1B).
     */
    public static function generateOrderCode(): string
    {
        do {
            $code = 'NOK-SRV-' . strtoupper(Str::random(8));
        } while (static::where('order_code', $code)->orWhere('order_number', $code)->exists());

        return $code;
    }

    /**
     * Backwards-compatible alias for generateOrderCode.
     */
    public static function generateOrderNumber(): string
    {
        return static::generateOrderCode();
    }

    /**
     * Compute platform commission (15%) and net creator payout (85%).
     *
     * @return array{platform_fee: float, seller_payout: float, seller_payout_amount: float}
     */
    public static function calculateFinancials(float $totalAmount, float $commissionRate = self::DEFAULT_COMMISSION_RATE): array
    {
        $platformFee = round($totalAmount * ($commissionRate / 100), 2);
        $sellerPayout = round($totalAmount - $platformFee, 2);

        return [
            'platform_fee' => $platformFee,
            'seller_payout' => $sellerPayout,
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
     * Platform reviews tied to this order.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(PlatformReview::class, 'service_order_id');
    }

    /**
     * Determine if order escrow has been funded and work is active or being reviewed.
     */
    public function isFunded(): bool
    {
        return in_array($this->status, [
            'in_progress',
            'funded_in_progress',
            'delivered_preview',
            'delivered',
            'completed',
            'disputed',
        ]);
    }

    /**
     * Determine if order is pending creator acceptance.
     */
    public function isPendingAcceptance(): bool
    {
        return in_array($this->status, ['pending_acceptance', 'pending_approval']);
    }

    /**
     * Determine if order is in progress.
     */
    public function isInProgress(): bool
    {
        return in_array($this->status, ['in_progress', 'funded_in_progress']);
    }

    /**
     * Determine if preview delivery has been submitted.
     */
    public function isDeliveredPreview(): bool
    {
        return in_array($this->status, ['delivered_preview', 'delivered']);
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

    /**
     * Determine if order has been cancelled.
     */
    public function isCancelled(): bool
    {
        return in_array($this->status, ['cancelled', 'refunded', 'declined']);
    }

    /**
     * Check if client can request a revision.
     */
    public function canRequestRevision(): bool
    {
        return $this->isDeliveredPreview() && ($this->revisions_used < $this->revisions_allowed);
    }

    /**
     * Check if creator can request a deadline extension (+24h or +48h, max 1 time).
     */
    public function canRequestExtension(): bool
    {
        return $this->isInProgress() && !$this->extension_used;
    }

    /**
     * Check if buyer can request 1 minor tweak within 24h of completion.
     */
    public function canRequestMinorFix(): bool
    {
        return $this->isCompleted()
            && !$this->minor_fix_used
            && $this->completed_at !== null
            && $this->completed_at->copy()->addHours(24)->isFuture();
    }
}
