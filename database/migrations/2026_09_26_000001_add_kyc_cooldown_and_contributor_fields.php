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
            if (!Schema::hasColumn('users', 'is_contributor')) {
                $table->boolean('is_contributor')->default(false)->after('is_verified');
            }
            if (!Schema::hasColumn('users', 'kyc_rejected_at')) {
                $table->timestamp('kyc_rejected_at')->nullable()->after('contributor_status');
            }
            if (!Schema::hasColumn('users', 'kyc_rejection_reason')) {
                $table->text('kyc_rejection_reason')->nullable()->after('kyc_rejected_at');
            }
        });

        Schema::table('seller_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('seller_verifications', 'id_number')) {
                $table->string('id_number')->nullable()->after('document_file');
            }
            if (!Schema::hasColumn('seller_verifications', 'portfolio_link')) {
                $table->string('portfolio_link')->nullable()->after('id_number');
            }
            if (!Schema::hasColumn('seller_verifications', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('reviewed_at');
            }
            if (!Schema::hasColumn('seller_verifications', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_contributor')) {
                $table->dropColumn('is_contributor');
            }
            if (Schema::hasColumn('users', 'kyc_rejected_at')) {
                $table->dropColumn('kyc_rejected_at');
            }
            if (Schema::hasColumn('users', 'kyc_rejection_reason')) {
                $table->dropColumn('kyc_rejection_reason');
            }
        });

        Schema::table('seller_verifications', function (Blueprint $table) {
            if (Schema::hasColumn('seller_verifications', 'id_number')) {
                $table->dropColumn('id_number');
            }
            if (Schema::hasColumn('seller_verifications', 'portfolio_link')) {
                $table->dropColumn('portfolio_link');
            }
            if (Schema::hasColumn('seller_verifications', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
            if (Schema::hasColumn('seller_verifications', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });
    }
};
