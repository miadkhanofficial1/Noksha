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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'active_mode')) {
                $table->enum('active_mode', ['buyer', 'seller'])->default('buyer')->after('role');
            }
            if (!Schema::hasColumn('users', 'nid_or_passport_number')) {
                $table->string('nid_or_passport_number')->nullable()->after('contributor_status');
            }
            if (!Schema::hasColumn('users', 'portfolio_link')) {
                $table->string('portfolio_link')->nullable()->after('nid_or_passport_number');
            }
            if (!Schema::hasColumn('users', 'kyc_document_path')) {
                $table->string('kyc_document_path')->nullable()->after('portfolio_link');
            }
            if (!Schema::hasColumn('users', 'contributor_bio')) {
                $table->text('contributor_bio')->nullable()->after('kyc_document_path');
            }
        });

        // Ensure role supports buyer, contributor, admin
        if (DB::getDriverName() === 'mysql') {
            try {
                DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('buyer', 'contributor', 'admin', 'super_admin', 'moderator', 'user', 'seller') NOT NULL DEFAULT 'buyer'");
            } catch (\Throwable $e) {
                // Ignore if not supported
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['active_mode', 'nid_or_passport_number', 'portfolio_link', 'kyc_document_path', 'contributor_bio'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
