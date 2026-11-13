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
        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('title');
                $table->string('slug')->unique();
                $table->string('category')->default('Graphics');
                $table->text('description')->nullable();
                $table->decimal('starting_price', 10, 2)->default(600.00);
                $table->integer('delivery_days')->default(3);
                $table->integer('concepts_count')->default(1);
                $table->integer('revisions_count')->default(2);
                $table->integer('max_slots')->default(3);
                $table->integer('active_orders_count')->default(0);
                $table->json('preview_images')->nullable();
                $table->enum('status', ['active', 'paused', 'suspended'])->default('active');
                $table->decimal('rating_avg', 3, 2)->default(5.00);
                $table->integer('reviews_count')->default(0);
                $table->timestamps();
            });
            return;
        }

        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'category')) {
                $table->string('category')->default('Graphics')->after('slug');
            }
            if (!Schema::hasColumn('services', 'starting_price')) {
                $table->decimal('starting_price', 10, 2)->default(600.00)->after('description');
            }
            if (!Schema::hasColumn('services', 'delivery_days')) {
                $table->integer('delivery_days')->default(3)->after('starting_price');
            }
            if (!Schema::hasColumn('services', 'concepts_count')) {
                $table->integer('concepts_count')->default(1)->after('delivery_days');
            }
            if (!Schema::hasColumn('services', 'revisions_count')) {
                $table->integer('revisions_count')->default(2)->after('concepts_count');
            }
            if (!Schema::hasColumn('services', 'max_slots')) {
                $table->integer('max_slots')->default(3)->after('revisions_count');
            }
            if (!Schema::hasColumn('services', 'active_orders_count')) {
                $table->integer('active_orders_count')->default(0)->after('max_slots');
            }
            if (!Schema::hasColumn('services', 'preview_images')) {
                $table->json('preview_images')->nullable()->after('active_orders_count');
            }
            if (!Schema::hasColumn('services', 'status')) {
                $table->string('status', 50)->default('active')->after('preview_images');
            }
            if (!Schema::hasColumn('services', 'rating_avg')) {
                $table->decimal('rating_avg', 3, 2)->default(5.00)->after('status');
            }
            if (!Schema::hasColumn('services', 'reviews_count')) {
                $table->integer('reviews_count')->default(0)->after('rating_avg');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe rollback: do not drop user columns to prevent data loss
    }
};
