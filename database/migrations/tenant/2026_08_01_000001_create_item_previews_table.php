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
        Schema::connection('tenant')->create('item_previews', function (Blueprint $table) {
            $table->id();
            $table->string('previewable_type');
            $table->unsignedBigInteger('previewable_id');
            $table->string('file_path');
            $table->string('title')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['previewable_type', 'previewable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('item_previews');
    }
};
