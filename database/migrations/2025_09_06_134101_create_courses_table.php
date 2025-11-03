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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            // Course info
            $table->string('title');
            $table->string('slug')->unique(); // url friendly name
            $table->text('description')->nullable();
            $table->string('video')->nullable(); // video path
            $table->string('thumbnail')->nullable(); // image path
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('class_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('price', 10, 2)->default(0); // free/paid course
            $table->decimal('discount_price', 10, 2)->nullable(); // agar discount hai to
            $table->integer('max_students')->nullable(); // maximum students allowed
            $table->boolean('is_featured')->default(false); // featured course
            $table->boolean('is_popular')->default(false); // popular course
            $table->boolean('is_new')->default(true); // new course
            $table->boolean('is_recommended')->default(false); // recommended course
            $table->boolean('is_certified')->default(false); // certificate available
            $table->boolean('allow_reviews')->default(true); // reviews allowed
            $table->integer('total_reviews')->default(0); // total reviews count
            $table->integer('total_enrollments')->default(0); // total enrollments count
            
            
            // Teacher / Author
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // ye users table se connect karega

            // Extra fields
            $table->string('language')->default('en'); // course language
            $table->integer('duration')->nullable(); // minutes me store kar sakte ho
            $table->integer('level')->default(1); // 1=Beginner, 2=Intermediate, 3=Advanced
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->date('expire_date')->nullable(); // course expire date

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
