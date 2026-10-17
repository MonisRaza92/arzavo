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
        if (!Schema::connection('tenant')->hasTable('batches')) {
            Schema::connection('tenant')->create('batches', function (Blueprint $table) {
                $table->id();
                $table->foreignId('class_course_id')->constrained('class_courses')->cascadeOnDelete();
                $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
                // Teacher assignment is strictly mandatory for batches
                $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();

                $table->string('name');
                $table->string('code')->nullable();
                $table->time('start_time')->nullable();
                $table->time('end_time')->nullable();
                $table->string('days')->nullable();
                $table->string('room_number')->nullable();
                $table->unsignedInteger('max_students')->default(35);
                $table->boolean('status')->default(true);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::connection('tenant')->hasTable('users') && !Schema::connection('tenant')->hasColumn('users', 'batch_id')) {
            Schema::connection('tenant')->table('users', function (Blueprint $table) {
                $table->foreignId('batch_id')->nullable()->after('subject_id')->constrained('batches')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection('tenant')->hasTable('users') && Schema::connection('tenant')->hasColumn('users', 'batch_id')) {
            Schema::connection('tenant')->table('users', function (Blueprint $table) {
                $table->dropForeign(['batch_id']);
                $table->dropColumn('batch_id');
            });
        }

        Schema::connection('tenant')->dropIfExists('batches');
    }
};
