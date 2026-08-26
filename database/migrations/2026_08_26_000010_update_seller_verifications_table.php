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
        Schema::table('seller_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('seller_verifications', 'document_type')) {
                $table->string('document_type')->nullable()->after('country');
            }
            if (!Schema::hasColumn('seller_verifications', 'document_file')) {
                $table->string('document_file')->nullable()->after('document_type');
            }
            if (!Schema::hasColumn('seller_verifications', 'selfie_file')) {
                $table->string('selfie_file')->nullable()->after('document_file');
            }
            if (!Schema::hasColumn('seller_verifications', 'admin_note')) {
                $table->text('admin_note')->nullable()->after('status');
            }
            if (!Schema::hasColumn('seller_verifications', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('admin_note');
            }
            if (!Schema::hasColumn('seller_verifications', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('submitted_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seller_verifications', function (Blueprint $table) {
            $table->dropColumn([
                'document_type',
                'document_file',
                'selfie_file',
                'admin_note',
                'submitted_at',
                'reviewed_at',
            ]);
        });
    }
};
