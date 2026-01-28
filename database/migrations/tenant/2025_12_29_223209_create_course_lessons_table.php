<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();

            /* ---------------------------------
             | RELATIONS
             |---------------------------------*/
            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();

            // NULL = direct lesson (modules OFF)
            $table->foreignId('course_module_id')
                ->nullable()
                ->constrained('course_modules')
                ->cascadeOnDelete();

            /* ---------------------------------
             | LESSON CORE
             |---------------------------------*/
            $table->string('title');
            $table->text('description')->nullable();

            /*
             | lesson types:
             | video | pdf | text | live | quiz | assignment
             */
            $table->enum('type', [
                'video',
                'pdf',
                'text',
                'live',
                'quiz',
                'assignment',
                'audio',
                'multiple-choice',
            ])->default('video');

            /* ---------------------------------
             | CONTENT
             |---------------------------------*/
            $table->string('video_path')->nullable();
            $table->string('file_path')->nullable(); // pdf, doc
            $table->longText('content')->nullable(); // text lesson

            /* ---------------------------------
             | ACCESS & PROGRESS
             |---------------------------------*/
            $table->boolean('is_free')->default(false); // preview lesson
            $table->boolean('is_active')->default(true);
            $table->boolean('is_mandatory')->default(true);

            $table->integer('duration')->nullable(); // minutes

            /* ---------------------------------
             | ORDERING
             |---------------------------------*/
            $table->integer('order')->default(0);

            $table->timestamps();

            $table->index([
                'course_id',
                'course_module_id',
                'is_active',
                'order'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_lessons');
    }
};
