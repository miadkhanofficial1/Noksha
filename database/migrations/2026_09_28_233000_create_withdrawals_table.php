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
        if (!Schema::hasTable('withdrawals')) {
            Schema::create('withdrawals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->decimal('amount', 10, 2);
                $table->string('payment_method', 50); // 'bkash', 'nagad', 'bank'
                $table->text('account_details'); // Phone number or Bank routing/account details
                $table->string('status', 30)->default('pending'); // 'pending', 'approved', 'rejected'
                $table->text('note')->nullable(); // Seller note or admin memo
                $table->timestamps();

                $table->index(['user_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
