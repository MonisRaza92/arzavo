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
        Schema::create('theme_states', function (Blueprint $table) {
            $table->id();
            // Reference to MAIN DB theme
            $table->unsignedBigInteger('theme_id');
            $table->string('theme_name');
            $table->string('theme_slug');
            $table->string('theme_version')->nullable();

            // Apply behaviour
            $table->boolean('applied_with_reset')->default(true);

            $table->timestamp('applied_at')->nullable();

            // Extra (rollback, notes, etc.)
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_states');
    }
};
