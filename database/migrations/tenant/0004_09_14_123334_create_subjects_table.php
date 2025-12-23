<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            // Parent Class / Course

            $table->foreignId('class_id')
                ->constrained('classes')
                ->cascadeOnDelete();

            $table->string('image');
            // Display name
            $table->string('name');
            // Maths, Physics, Biology, Legal Reasoning

            // Internal code (auto-generated)
            $table->string('code');
            // maths, physics, biology

            $table->string('description')->nullable();

            $table->unsignedInteger('order')->default(0);
            $table->boolean('status')->default(true);

            $table->timestamps();

            // Safety: same class me duplicate subject nahi
            $table->unique(['class_id', 'name']);
            $table->unique(['class_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
