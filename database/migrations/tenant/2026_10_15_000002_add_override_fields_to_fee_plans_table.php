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
        Schema::table('fee_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('fee_plans', 'fee_structure_id')) {
                $table->foreignId('fee_structure_id')->nullable()->after('student_id')->constrained('fee_structures')->nullOnDelete();
            }
            if (!Schema::hasColumn('fee_plans', 'is_override')) {
                $table->boolean('is_override')->default(false)->after('amount');
            }
            if (!Schema::hasColumn('fee_plans', 'override_reason')) {
                $table->string('override_reason')->nullable()->after('is_override');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_plans', function (Blueprint $table) {
            if (Schema::hasColumn('fee_plans', 'fee_structure_id')) {
                $table->dropForeign(['fee_structure_id']);
                $table->dropColumn('fee_structure_id');
            }
            if (Schema::hasColumn('fee_plans', 'is_override')) {
                $table->dropColumn('is_override');
            }
            if (Schema::hasColumn('fee_plans', 'override_reason')) {
                $table->dropColumn('override_reason');
            }
        });
    }
};
