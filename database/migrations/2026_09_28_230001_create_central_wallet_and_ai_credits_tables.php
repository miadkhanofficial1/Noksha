<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create wallets table
        if (!Schema::hasTable('wallets')) {
            Schema::create('wallets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
                $table->decimal('balance', 10, 2)->default(0.00);
                $table->timestamps();
            });
        }

        // 2. Create ai_credits table
        if (!Schema::hasTable('ai_credits')) {
            Schema::create('ai_credits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
                $table->integer('credits')->default(0);
                $table->timestamps();
            });
        }

        // 3. Initialize wallets and ai_credits for existing users (grant 5 free starter credits)
        $existingUsers = DB::table('users')->get();
        foreach ($existingUsers as $u) {
            if (!DB::table('wallets')->where('user_id', $u->id)->exists()) {
                DB::table('wallets')->insert([
                    'user_id' => $u->id,
                    'balance' => $u->balance ?? 0.00,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (!DB::table('ai_credits')->where('user_id', $u->id)->exists()) {
                DB::table('ai_credits')->insert([
                    'user_id' => $u->id,
                    'credits' => 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 4. Update or create wallet_transactions table
        if (!Schema::hasTable('wallet_transactions')) {
            Schema::create('wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wallet_id')->constrained('wallets')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('type', 50);
                $table->decimal('amount', 10, 2)->default(0.00);
                $table->integer('credits_transacted')->default(0);
                $table->string('description');
                $table->string('status', 30)->default('completed');
                $table->timestamps();
            });
        } else {
            // Add wallet_id and credits_transacted if missing
            Schema::table('wallet_transactions', function (Blueprint $table) {
                if (!Schema::hasColumn('wallet_transactions', 'wallet_id')) {
                    $table->foreignId('wallet_id')->nullable()->after('id')->constrained('wallets')->cascadeOnDelete();
                }
                if (!Schema::hasColumn('wallet_transactions', 'credits_transacted')) {
                    $table->integer('credits_transacted')->default(0)->after('amount');
                }
            });

            // Backfill wallet_id from existing wallets
            try {
                DB::statement("
                    UPDATE wallet_transactions wt
                    JOIN wallets w ON wt.user_id = w.user_id
                    SET wt.wallet_id = w.id
                    WHERE wt.wallet_id IS NULL
                ");
            } catch (\Throwable $e) {
                // Ignore fallback
            }

            // Modify type column to varchar(50) to support all transaction types
            try {
                DB::statement("ALTER TABLE wallet_transactions MODIFY COLUMN type VARCHAR(50) NOT NULL");
            } catch (\Throwable $e) {
                // Ignore if not MySQL
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('wallet_transactions') && Schema::hasColumn('wallet_transactions', 'wallet_id')) {
            Schema::table('wallet_transactions', function (Blueprint $table) {
                $table->dropForeign(['wallet_id']);
                $table->dropColumn(['wallet_id', 'credits_transacted']);
            });
        }

        Schema::dropIfExists('ai_credits');
        Schema::dropIfExists('wallets');
    }
};
