<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create academic_holidays table for tracking weekly offs, festival holidays, and emergency closures
        if (!Schema::connection('tenant')->hasTable('academic_holidays')) {
            Schema::connection('tenant')->create('academic_holidays', function (Blueprint $table) {
                $table->id();
                $table->string('title', 255);
                $table->date('start_date');
                $table->date('end_date');
                $table->string('type', 50)->default('festival'); // weekly_off, festival, emergency, event, vacation, other
                $table->string('scope', 50)->default('all'); // all, students_only, staff_only
                $table->text('description')->nullable();
                $table->boolean('status')->default(true); // true = active holiday, false = working day override
                $table->string('academic_year', 20)->nullable(); // e.g. 2026-2027
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->index(['start_date', 'end_date', 'status']);
                $table->index(['type', 'status']);
                $table->index(['start_date', 'status']);
            });
        }

        // 2. Allow 'holiday' status in student_attendances table
        try {
            DB::connection('tenant')->statement("ALTER TABLE `student_attendances` MODIFY COLUMN `status` VARCHAR(30) NOT NULL DEFAULT 'present'");
        } catch (\Throwable $e) {
            // Already altered or fallback
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('academic_holidays');
    }
};
