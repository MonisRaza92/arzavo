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
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            // Identity
            $table->string('slug')->unique();      // saas-landing
            $table->string('name');                // SaaS Landing
            $table->string('category')->nullable();
            $table->string('version')->default('1.0.0');

            // Pricing
            $table->boolean('is_paid')->default(false);
            $table->decimal('price', 10, 2)->nullable();

            // Ownership / source
            $table->enum('source', ['system', 'user'])->default('system');
            $table->unsignedBigInteger('owner_tenant_id')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            // Extra info (optional, future)
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
