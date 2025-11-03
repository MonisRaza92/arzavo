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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->enum('type', ['video', 'note', 'book']);
            $table->string('file');
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('set null');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->decimal('discount_price', 8, 2)->nullable();
            $table->boolean('is_free')->default(false);
            $table->decimal('duration', 8, 2)->nullable(); // duration in minutes for videos
            $table->string('thumbnail')->nullable();
            $table->enum('status', ['published', 'archived', 'draft'])->default('draft');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
