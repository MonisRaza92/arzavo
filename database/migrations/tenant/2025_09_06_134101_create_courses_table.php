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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            // Core
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('video')->nullable();

            // Meta
            $table->string('language')->default('en');
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])
                ->default('beginner');

            $table->integer('duration')->nullable();
            $table->integer('max_students')->nullable();

            // Pricing
            $table->boolean('is_paid')->default(false);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount_price', 10, 2)->nullable();

            // Access & visibility
            $table->boolean('is_public')->default(true);
            $table->boolean('requires_enrollment')->default(true);

            // Feature toggles
            $table->boolean('enable_modules')->default(true);
            $table->boolean('enable_lessons')->default(true);
            $table->boolean('enable_quizzes')->default(false);
            $table->boolean('enable_assignments')->default(false);
            $table->boolean('enable_certificates')->default(false);
            $table->boolean('enable_reviews')->default(true);

            // Lifecycle
            $table->enum('status', ['draft', 'published', 'archived'])
                ->default('draft');
            $table->date('publish_date')->nullable();
            $table->date('expire_date')->nullable();

            // Analytics
            $table->unsignedInteger('total_enrollments')->default(0);
            $table->unsignedInteger('total_reviews')->default(0);

            // Author
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
