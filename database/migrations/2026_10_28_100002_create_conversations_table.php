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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->enum('conversation_type', ['direct', 'system_support', 'live_agent'])->default('direct');
            $table->boolean('is_frozen')->default(false);
            $table->foreignId('left_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reopen_requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('reopen_status', ['none', 'requested', 'approved', 'declined'])->default('none');
            $table->unsignedBigInteger('active_order_id')->nullable()->index();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
