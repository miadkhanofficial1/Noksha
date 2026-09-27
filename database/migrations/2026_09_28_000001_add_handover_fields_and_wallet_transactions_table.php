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
        // 1. Update contests table
        Schema::table('contests', function (Blueprint $table) {
            if (!Schema::hasColumn('contests', 'handover_completed_at')) {
                $table->timestamp('handover_completed_at')->nullable()->after('winner_entry_id');
            }
        });

        // 2. Update contest_entries table
        Schema::table('contest_entries', function (Blueprint $table) {
            if (!Schema::hasColumn('contest_entries', 'handover_files')) {
                $table->json('handover_files')->nullable()->after('is_winner');
            }
            if (!Schema::hasColumn('contest_entries', 'handover_notes')) {
                $table->text('handover_notes')->nullable()->after('handover_files');
            }
            if (!Schema::hasColumn('contest_entries', 'handover_submitted_at')) {
                $table->timestamp('handover_submitted_at')->nullable()->after('handover_notes');
            }
            if (!Schema::hasColumn('contest_entries', 'handover_status')) {
                $table->string('handover_status', 50)->default('pending')->after('handover_submitted_at');
            }
        });

        // 3. Add balance to users table if not exists
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'balance')) {
                $table->decimal('balance', 12, 2)->default(0.00)->after('is_contributor');
            }
        });

        // 4. Create wallet_transactions table if not exists
        if (!Schema::hasTable('wallet_transactions')) {
            Schema::create('wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->enum('type', ['credit', 'debit'])->default('credit');
                $table->decimal('amount', 12, 2);
                $table->decimal('balance_after', 12, 2);
                $table->string('reference_type', 50)->nullable();
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->string('description');
                $table->string('status', 30)->default('completed');
                $table->timestamps();

                $table->index(['user_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'balance')) {
                $table->dropColumn('balance');
            }
        });

        Schema::table('contest_entries', function (Blueprint $table) {
            $cols = ['handover_files', 'handover_notes', 'handover_submitted_at', 'handover_status'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('contest_entries', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('contests', function (Blueprint $table) {
            if (Schema::hasColumn('contests', 'handover_completed_at')) {
                $table->dropColumn('handover_completed_at');
            }
        });
    }
};
