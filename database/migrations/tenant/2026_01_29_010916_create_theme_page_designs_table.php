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
        Schema::create('theme_page_designs', function (Blueprint $table) {
            $table->id();

            // Which installed theme (draft / published)
            $table->unsignedBigInteger('tenant_theme_id');

            // Which page (pages table se)
            $table->unsignedBigInteger('page_id');

            // Full layout: sections + blocks + settings
            $table->json('layout');

            $table->timestamps();

            $table->unique(['tenant_theme_id', 'page_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_page_designs');
    }
};
