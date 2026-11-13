<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('service_orders')) {
            Schema::create('service_orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_code')->unique();
                $table->string('order_number')->nullable()->unique();
                $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
                $table->foreignId('conversation_id')->nullable()->constrained('conversations')->cascadeOnDelete();
                $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
                $table->string('custom_title')->nullable();
                $table->text('requirements_brief')->nullable();
                $table->decimal('amount', 10, 2)->default(0.00);
                $table->decimal('total_amount', 10, 2)->default(0.00);
                $table->decimal('platform_commission_rate', 5, 2)->default(15.00);
                $table->decimal('platform_fee', 10, 2)->default(0.00);
                $table->decimal('seller_payout', 10, 2)->default(0.00);
                $table->decimal('seller_payout_amount', 10, 2)->default(0.00);
                $table->integer('delivery_days')->default(3);
                $table->integer('revisions_allowed')->default(2);
                $table->integer('revisions_used')->default(0);
                $table->timestamp('deadline_at')->nullable();
                $table->boolean('extension_used')->default(false);
                $table->boolean('deadline_notified_12h')->default(false);
                $table->boolean('deadline_notified_overdue')->default(false);
                $table->string('status', 50)->default('pending_acceptance');
                $table->json('delivery_preview_images')->nullable();
                $table->string('delivery_source_file')->nullable();
                $table->string('delivery_instruction_pdf')->nullable();
                $table->json('delivery_manifest')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->boolean('minor_fix_used')->default(false);
                $table->timestamps();
            });
            return;
        }

        Schema::table('service_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('service_orders', 'order_code')) {
                $table->string('order_code')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('service_orders', 'custom_title')) {
                $table->string('custom_title')->nullable()->after('seller_id');
            }
            if (!Schema::hasColumn('service_orders', 'requirements_brief')) {
                $table->text('requirements_brief')->nullable()->after('custom_title');
            }
            if (!Schema::hasColumn('service_orders', 'amount')) {
                $table->decimal('amount', 10, 2)->default(0.00)->after('requirements_brief');
            }
            if (!Schema::hasColumn('service_orders', 'seller_payout')) {
                $table->decimal('seller_payout', 10, 2)->default(0.00)->after('platform_fee');
            }
            if (!Schema::hasColumn('service_orders', 'delivery_days')) {
                $table->integer('delivery_days')->default(3)->after('seller_payout');
            }
            if (!Schema::hasColumn('service_orders', 'revisions_allowed')) {
                $table->integer('revisions_allowed')->default(2)->after('delivery_days');
            }
            if (!Schema::hasColumn('service_orders', 'revisions_used')) {
                $table->integer('revisions_used')->default(0)->after('revisions_allowed');
            }
            if (!Schema::hasColumn('service_orders', 'extension_used')) {
                $table->boolean('extension_used')->default(false)->after('deadline_at');
            }
            if (!Schema::hasColumn('service_orders', 'deadline_notified_12h')) {
                $table->boolean('deadline_notified_12h')->default(false)->after('extension_used');
            }
            if (!Schema::hasColumn('service_orders', 'deadline_notified_overdue')) {
                $table->boolean('deadline_notified_overdue')->default(false)->after('deadline_notified_12h');
            }
            if (!Schema::hasColumn('service_orders', 'delivery_preview_images')) {
                $table->json('delivery_preview_images')->nullable()->after('status');
            }
            if (!Schema::hasColumn('service_orders', 'delivery_source_file')) {
                $table->string('delivery_source_file')->nullable()->after('delivery_preview_images');
            }
            if (!Schema::hasColumn('service_orders', 'delivery_instruction_pdf')) {
                $table->string('delivery_instruction_pdf')->nullable()->after('delivery_source_file');
            }
            if (!Schema::hasColumn('service_orders', 'delivery_manifest')) {
                $table->json('delivery_manifest')->nullable()->after('delivery_instruction_pdf');
            }
            if (!Schema::hasColumn('service_orders', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('delivered_at');
            }
            if (!Schema::hasColumn('service_orders', 'minor_fix_used')) {
                $table->boolean('minor_fix_used')->default(false)->after('completed_at');
            }
        });

        // Modify status column from ENUM to VARCHAR(50) so it supports new and old escrow states seamlessly
        try {
            DB::statement("ALTER TABLE service_orders MODIFY status VARCHAR(50) NOT NULL DEFAULT 'pending_acceptance'");
        } catch (\Throwable $e) {
            // Ignore if already changed or restricted
        }

        // Backfill existing data
        try {
            DB::statement("UPDATE service_orders SET order_code = order_number WHERE order_code IS NULL AND order_number IS NOT NULL");
            DB::statement("UPDATE service_orders SET amount = total_amount WHERE (amount IS NULL OR amount = 0) AND total_amount IS NOT NULL");
            DB::statement("UPDATE service_orders SET seller_payout = seller_payout_amount WHERE (seller_payout IS NULL OR seller_payout = 0) AND seller_payout_amount IS NOT NULL");
        } catch (\Throwable $e) {
            // Silently continue if tables are empty
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe rollback: do not drop user columns to prevent data loss
    }
};
