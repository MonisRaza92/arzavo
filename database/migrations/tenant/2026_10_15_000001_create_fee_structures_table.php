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
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            
            // Scope hierarchy level: category -> class -> subject -> batch
            $table->enum('scope', ['category', 'class', 'subject', 'batch'])->default('class');

            // Hierarchy relationships
            $table->foreignId('academic_category_id')->nullable()->constrained('academic_categories')->nullOnDelete();
            $table->foreignId('class_id')->nullable()->constrained('class_courses')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();

            // Fee specifications
            $table->enum('plan_type', ['monthly', 'quarterly', 'yearly', 'onetime'])->default('monthly');
            $table->decimal('amount', 10, 2);
            $table->unsignedTinyInteger('due_day')->default(1); // 1-31
            $table->date('start_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};
