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
        if (!Schema::hasTable('platform_reviews')) {
            Schema::create('platform_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
                $table->foreignId('service_order_id')->nullable()->constrained('service_orders')->nullOnDelete();
                $table->foreignId('asset_id')->nullable()->constrained('resources')->nullOnDelete();
                $table->unsignedTinyInteger('rating')->default(5);
                $table->text('comment')->nullable();
                $table->boolean('is_complaint')->default(false);
                $table->json('evidence_images')->nullable();
                $table->boolean('admin_resolved')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_reviews');
    }
};
