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
        // 1. Add Class Teacher / Mentor to class_courses table
        Schema::table('class_courses', function (Blueprint $table) {
            if (!Schema::hasColumn('class_courses', 'class_teacher_id')) {
                $table->foreignId('class_teacher_id')
                    ->nullable()
                    ->after('academic_category_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });

        // 2. Add Teacher & Timings to subjects table
        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('subjects', 'teacher_id')) {
                $table->foreignId('teacher_id')
                    ->nullable()
                    ->after('class_courses_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }
            if (!Schema::hasColumn('subjects', 'start_time')) {
                $table->time('start_time')->nullable()->after('description');
            }
            if (!Schema::hasColumn('subjects', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }
            if (!Schema::hasColumn('subjects', 'days')) {
                $table->string('days')->nullable()->after('end_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'teacher_id')) {
                $table->dropForeign(['teacher_id']);
                $table->dropColumn('teacher_id');
            }
            if (Schema::hasColumn('subjects', 'start_time')) {
                $table->dropColumn('start_time');
            }
            if (Schema::hasColumn('subjects', 'end_time')) {
                $table->dropColumn('end_time');
            }
            if (Schema::hasColumn('subjects', 'days')) {
                $table->dropColumn('days');
            }
        });

        Schema::table('class_courses', function (Blueprint $table) {
            if (Schema::hasColumn('class_courses', 'class_teacher_id')) {
                $table->dropForeign(['class_teacher_id']);
                $table->dropColumn('class_teacher_id');
            }
        });
    }
};
