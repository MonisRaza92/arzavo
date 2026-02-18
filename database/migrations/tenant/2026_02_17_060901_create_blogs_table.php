<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            // Basic blog info
            $table->string('title');
            $table->string('slug')->unique();

            // Optional separate heading
            $table->string('heading')->nullable();

            // Main content (HTML allowed)
            $table->longText('content')->nullable();

            // Main image
            $table->string('featured_image')->nullable();
            $table->string('image_alt')->nullable();

            // Author (optional)
            $table->unsignedBigInteger('author_id')->nullable();

            // Status
            $table->enum('status', ['draft', 'published'])->default('draft');

            // Publish date
            $table->timestamp('published_at')->nullable();

            // SEO basics
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // performance indexes
            $table->index('status');
            $table->index('published_at');
            $table->index('author_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
