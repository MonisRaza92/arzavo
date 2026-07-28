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
        Schema::connection('tenant')->create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->string('edition')->nullable();
            $table->string('isbn')->nullable();
            $table->text('description')->nullable();
            $table->integer('pages_count')->nullable();
            
            // Paths to Content items
            $table->string('cover_image')->nullable();
            $table->string('file_path');
            $table->string('preview_file_path')->nullable();
            
            // Pricing
            $table->string('price_type')->default('free'); // free, paid
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('sale_price', 10, 2)->nullable();
            
            // Access control
            $table->string('access_type')->default('public'); // public, students_only, enrolled_students_only
            
            // Status & stats
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('downloads_count')->default(0);
            
            // Relationships
            $table->foreignId('book_category_id')->constrained('book_categories')->cascadeOnDelete();
            
            // Academic linkages (all nullable for general/global reading books)
            $table->foreignId('academic_category_id')->nullable()->constrained('academic_categories')->nullOnDelete();
            $table->foreignId('class_course_id')->nullable()->constrained('class_courses')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('books');
    }
};
