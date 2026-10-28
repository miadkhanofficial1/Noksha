<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->restrictOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('total_amount', 10, 2)->unsigned();
            $table->decimal('platform_commission_rate', 5, 2)->default(15.00);
            $table->decimal('platform_fee', 10, 2)->default(0.00);
            $table->decimal('seller_payout_amount', 10, 2)->default(0.00);
            $table->enum('status', [
                'pending_approval',
                'declined',
                'awaiting_payment',
                'funded_in_progress',
                'delivered',
                'completed',
                'disputed',
                'cancelled',
                'refunded',
            ])->default('pending_approval');
            $table->timestamp('deadline_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('delivery_archive_path')->nullable();
            $table->timestamps();
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->foreign('active_order_id')->references('id')->on('service_orders')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('conversations')) {
            Schema::table('conversations', function (Blueprint $table) {
                $table->dropForeign(['active_order_id']);
            });
        }

        Schema::dropIfExists('service_orders');
    }
};
