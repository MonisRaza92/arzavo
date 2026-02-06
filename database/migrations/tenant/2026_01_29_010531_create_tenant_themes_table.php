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
        Schema::create('tenant_themes', function (Blueprint $table) {
            $table->id();

            // Reference to main DB theme
            $table->unsignedBigInteger('theme_id');
            $table->string('theme_slug');
            $table->string('theme_version')->nullable();

            // Lifecycle
            $table->enum('status', ['draft', 'published', 'archived'])
                ->default('draft');

            $table->boolean('is_active')->default(false);

            $table->timestamp('installed_at')->nullable();
            $table->timestamp('published_at')->nullable();

            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['theme_id', 'theme_version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_themes');
    }
};
