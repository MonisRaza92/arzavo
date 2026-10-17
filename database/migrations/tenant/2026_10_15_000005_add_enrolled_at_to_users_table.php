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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'enrolled_at')) {
                $table->timestamp('enrolled_at')->nullable()->after('admission_status');
            }
        });

        // Backfill existing enrolled students with their approved_at date or earliest attendance
        if (Schema::hasTable('users')) {
            $students = DB::table('users')->where('role', 'student')->get();
            foreach ($students as $student) {
                $enrolledAt = null;

                if (Schema::hasTable('admissions')) {
                    $admission = DB::table('admissions')
                        ->where('user_id', $student->id)
                        ->where('status', 'approved')
                        ->orderByDesc('id')
                        ->first();

                    if ($admission && !empty($admission->approved_at)) {
                        $enrolledAt = $admission->approved_at;
                    } elseif ($admission && !empty($admission->created_at)) {
                        $enrolledAt = $admission->created_at;
                    }
                }

                if (!$enrolledAt && Schema::hasTable('student_attendances')) {
                    $earliestAttendance = DB::table('student_attendances')
                        ->where('student_id', $student->id)
                        ->min('date');
                    if ($earliestAttendance) {
                        $enrolledAt = $earliestAttendance . ' 00:00:00';
                    }
                }

                if (!$enrolledAt) {
                    $enrolledAt = $student->created_at ?? now();
                }

                DB::table('users')->where('id', $student->id)->update([
                    'enrolled_at' => $enrolledAt
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'enrolled_at')) {
                $table->dropColumn('enrolled_at');
            }
        });
    }
};
