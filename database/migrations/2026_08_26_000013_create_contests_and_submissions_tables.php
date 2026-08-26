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
        Schema::create('contests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('prize_amount', 10, 2);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active')->index();
            $table->foreignId('winner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('winner_submission_id')->nullable();
            $table->timestamps();
        });

        Schema::create('contest_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contest_id')->constrained('contests')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('preview_image');
            $table->string('design_file');
            $table->text('note')->nullable();
            $table->boolean('is_winner')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contest_submissions');
        Schema::dropIfExists('contests');
    }
};
