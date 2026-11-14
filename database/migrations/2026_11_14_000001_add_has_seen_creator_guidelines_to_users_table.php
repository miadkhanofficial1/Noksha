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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'has_seen_creator_guidelines')) {
                $table->boolean('has_seen_creator_guidelines')->default(false)->after('reliability_warning');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'has_seen_creator_guidelines')) {
                $table->dropColumn('has_seen_creator_guidelines');
            }
        });
    }
};
