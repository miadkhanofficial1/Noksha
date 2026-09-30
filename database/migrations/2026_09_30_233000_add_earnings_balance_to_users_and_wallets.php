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
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'earnings_balance')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('earnings_balance', 10, 2)->default(0.00)->after('balance');
            });
        }

        if (Schema::hasTable('wallets') && !Schema::hasColumn('wallets', 'earnings_balance')) {
            Schema::table('wallets', function (Blueprint $table) {
                $table->decimal('earnings_balance', 10, 2)->default(0.00)->after('balance');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'earnings_balance')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('earnings_balance');
            });
        }

        if (Schema::hasTable('wallets') && Schema::hasColumn('wallets', 'earnings_balance')) {
            Schema::table('wallets', function (Blueprint $table) {
                $table->dropColumn('earnings_balance');
            });
        }
    }
};
