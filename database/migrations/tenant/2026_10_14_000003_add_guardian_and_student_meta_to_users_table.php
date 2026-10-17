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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'roll_number')) {
                $table->string('roll_number', 50)->nullable()->after('username');
            }
            if (!Schema::hasColumn('users', 'father_name')) {
                $table->string('father_name', 255)->nullable()->after('lname');
            }
            if (!Schema::hasColumn('users', 'mother_name')) {
                $table->string('mother_name', 255)->nullable()->after('father_name');
            }
            if (!Schema::hasColumn('users', 'parent_phone')) {
                $table->string('parent_phone', 30)->nullable()->after('number');
            }
            if (!Schema::hasColumn('users', 'emergency_contact')) {
                $table->string('emergency_contact', 30)->nullable()->after('parent_phone');
            }
            if (!Schema::hasColumn('users', 'blood_group')) {
                $table->string('blood_group', 10)->nullable()->after('dob');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = ['roll_number', 'father_name', 'mother_name', 'parent_phone', 'emergency_contact', 'blood_group'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
