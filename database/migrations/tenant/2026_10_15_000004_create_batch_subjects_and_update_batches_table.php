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
        // 1. Update batches table: make subject_id nullable & add duration / break metrics
        if (Schema::connection('tenant')->hasTable('batches')) {
            Schema::connection('tenant')->table('batches', function (Blueprint $table) {
                // If subject_id exists, make it nullable so batches can hold multiple subjects
                if (Schema::connection('tenant')->hasColumn('batches', 'subject_id')) {
                    $table->unsignedBigInteger('subject_id')->nullable()->change();
                }

                if (!Schema::connection('tenant')->hasColumn('batches', 'total_duration_minutes')) {
                    $table->unsignedInteger('total_duration_minutes')->nullable()->after('end_time');
                }

                if (!Schema::connection('tenant')->hasColumn('batches', 'break_duration_minutes')) {
                    $table->unsignedInteger('break_duration_minutes')->nullable()->after('total_duration_minutes');
                }
            });
        }

        // 2. Create batch_subjects table (Multi-subject periods schedule for Batches)
        if (!Schema::connection('tenant')->hasTable('batch_subjects')) {
            Schema::connection('tenant')->create('batch_subjects', function (Blueprint $table) {
                $table->id();
                $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
                $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
                $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();

                $table->time('start_time')->nullable();
                $table->time('end_time')->nullable();
                $table->string('days')->nullable();
                $table->string('room_number')->nullable();
                $table->unsignedInteger('order')->default(1);
                $table->timestamps();
            });
        }

        // 3. Create student_subjects pivot table for students choosing multiple direct subjects
        if (!Schema::connection('tenant')->hasTable('student_subjects')) {
            Schema::connection('tenant')->create('student_subjects', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['student_id', 'subject_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('student_subjects');
        Schema::connection('tenant')->dropIfExists('batch_subjects');

        if (Schema::connection('tenant')->hasTable('batches')) {
            Schema::connection('tenant')->table('batches', function (Blueprint $table) {
                if (Schema::connection('tenant')->hasColumn('batches', 'break_duration_minutes')) {
                    $table->dropColumn('break_duration_minutes');
                }
                if (Schema::connection('tenant')->hasColumn('batches', 'total_duration_minutes')) {
                    $table->dropColumn('total_duration_minutes');
                }
            });
        }
    }
};
