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
        Schema::create('theme_global_designs', function (Blueprint $table) {
            $table->id();

            // 🔗 tenant ke theme se relation
            $table->unsignedBigInteger('tenant_theme_id');

            // 🧠 Pure header/footer ka JSON
            $table->json('layout')->nullable();

            $table->timestamps();

            // Optional but recommended
            $table->unique('tenant_theme_id');

            // Agar tum foreign key use karte ho
            $table->foreign('tenant_theme_id')
                ->references('id')
                ->on('tenant_themes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_global_designs');
    }
};
