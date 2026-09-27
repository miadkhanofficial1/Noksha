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
        try {
            DB::statement("ALTER TABLE `contests` MODIFY COLUMN `end_date` DATETIME NULL DEFAULT NULL");
            DB::statement("ALTER TABLE `contests` MODIFY COLUMN `start_date` DATETIME NULL DEFAULT NULL");
        } catch (\Throwable $e) {
            // fallback
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
