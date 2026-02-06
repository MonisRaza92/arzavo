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
        Schema::create('color_schemes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('theme_id');
            $table->string('key');
            $table->json('colors')->nullable();
            $table->timestamps();

            $table->unique(
                ['theme_id', 'key'],
                'tenant_color_schemes_theme_key_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('color_schemes');
    }
};
