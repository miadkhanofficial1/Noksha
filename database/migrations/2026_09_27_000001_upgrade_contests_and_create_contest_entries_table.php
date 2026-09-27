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
        // 1. Upgrade contests table
        Schema::table('contests', function (Blueprint $table) {
            if (!Schema::hasColumn('contests', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('contests', 'category')) {
                $table->string('category')->nullable()->after('category_id');
            }
            if (!Schema::hasColumn('contests', 'required_dimensions')) {
                $table->string('required_dimensions')->nullable()->after('description');
            }
            if (!Schema::hasColumn('contests', 'attachment_file')) {
                $table->string('attachment_file')->nullable()->after('required_dimensions');
            }
            if (!Schema::hasColumn('contests', 'posting_fee')) {
                $table->decimal('posting_fee', 10, 2)->default(500.00)->after('prize_amount');
            }
            if (!Schema::hasColumn('contests', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('posting_fee');
            }
            if (!Schema::hasColumn('contests', 'is_guaranteed')) {
                $table->boolean('is_guaranteed')->default(false)->after('payment_reference');
            }
            if (!Schema::hasColumn('contests', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('contests', 'deadline')) {
                $table->timestamp('deadline')->nullable()->after('end_date');
            }
            if (!Schema::hasColumn('contests', 'winner_entry_id')) {
                $table->unsignedBigInteger('winner_entry_id')->nullable()->after('winner_submission_id');
            }
        });

        // Ensure status column on contests allows all statuses
        try {
            DB::statement("ALTER TABLE `contests` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'active'");
        } catch (\Throwable $e) {
            // fallback if not MySQL/MariaDB
        }

        // Set user_id and deadline for existing demo contests if null
        DB::table('contests')->whereNull('user_id')->update([
            'user_id' => DB::table('users')->where('role', 'admin')->value('id') ?? 1,
            'is_guaranteed' => true,
        ]);
        DB::statement("UPDATE `contests` SET `deadline` = `end_date` WHERE `deadline` IS NULL AND `end_date` IS NOT NULL");

        // 2. Create contest_entries table
        if (!Schema::hasTable('contest_entries')) {
            Schema::create('contest_entries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('contest_id')->constrained('contests')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('clean_preview_image');
                $table->string('watermarked_preview_image');
                $table->string('source_file_link')->nullable();
                $table->unsignedTinyInteger('client_rating')->nullable();
                $table->text('client_feedback')->nullable();
                $table->unsignedInteger('likes_count')->default(0);
                $table->boolean('is_winner')->default(false);
                $table->timestamps();

                // STRICT SINGLE ENTRY PER CONTRIBUTOR CONSTRAINT
                $table->unique(['contest_id', 'user_id'], 'contest_user_unique_entry');
            });
        }

        // 3. Create contest_entry_likes table
        if (!Schema::hasTable('contest_entry_likes')) {
            Schema::create('contest_entry_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('contest_entry_id')->constrained('contest_entries')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
                $table->string('ip_address', 45)->nullable();
                $table->timestamps();

                $table->index(['contest_entry_id', 'user_id']);
                $table->index(['contest_entry_id', 'ip_address']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contest_entry_likes');
        Schema::dropIfExists('contest_entries');

        Schema::table('contests', function (Blueprint $table) {
            $columnsToDrop = [
                'user_id',
                'category',
                'required_dimensions',
                'attachment_file',
                'posting_fee',
                'payment_reference',
                'is_guaranteed',
                'rejection_reason',
                'deadline',
                'winner_entry_id',
            ];
            foreach ($columnsToDrop as $col) {
                if (Schema::hasColumn('contests', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
