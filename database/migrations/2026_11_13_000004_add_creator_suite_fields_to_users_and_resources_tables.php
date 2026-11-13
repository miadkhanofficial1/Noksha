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
            if (!Schema::hasColumn('users', 'demerit_points')) {
                $table->integer('demerit_points')->default(0);
            }
            if (!Schema::hasColumn('users', 'late_deliveries_count')) {
                $table->integer('late_deliveries_count')->default(0);
            }
            if (!Schema::hasColumn('users', 'reliability_warning')) {
                $table->boolean('reliability_warning')->default(false);
            }
        });

        Schema::table('resources', function (Blueprint $table) {
            if (!Schema::hasColumn('resources', 'preview_images')) {
                $table->json('preview_images')->nullable();
            }
            if (!Schema::hasColumn('resources', 'source_file_path')) {
                $table->string('source_file_path')->nullable();
            }
            if (!Schema::hasColumn('resources', 'instruction_pdf_path')) {
                $table->string('instruction_pdf_path')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe rollback
    }
};
