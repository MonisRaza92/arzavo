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
        // 1. Add fields to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'academic_category_id')) {
                $table->unsignedBigInteger('academic_category_id')->nullable()->after('dob');
            }
            if (!Schema::hasColumn('users', 'aadhaar_number')) {
                $table->string('aadhaar_number', 30)->nullable()->after('subject_id');
            }
            if (!Schema::hasColumn('users', 'aadhaar_front')) {
                $table->string('aadhaar_front', 255)->nullable()->after('aadhaar_number');
            }
            if (!Schema::hasColumn('users', 'aadhaar_back')) {
                $table->string('aadhaar_back', 255)->nullable()->after('aadhaar_front');
            }
            if (!Schema::hasColumn('users', 'previous_marksheet')) {
                $table->string('previous_marksheet', 255)->nullable()->after('aadhaar_back');
            }
            if (!Schema::hasColumn('users', 'previous_school')) {
                $table->string('previous_school', 255)->nullable()->after('previous_marksheet');
            }
            if (!Schema::hasColumn('users', 'admission_status')) {
                $table->string('admission_status', 30)->default('none')->after('status');
            }
            if (!Schema::hasColumn('users', 'pending_profile_updates')) {
                $table->longText('pending_profile_updates')->nullable()->after('admission_status');
            }
        });

        // 2. Create admissions table
        if (!Schema::hasTable('admissions')) {
            Schema::create('admissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('academic_category_id')->nullable();
                $table->unsignedBigInteger('class_id')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->string('aadhaar_number', 30)->nullable();
                $table->string('aadhaar_front', 255)->nullable();
                $table->string('aadhaar_back', 255)->nullable();
                $table->string('previous_marksheet', 255)->nullable();
                $table->string('previous_school', 255)->nullable();
                $table->string('previous_grade', 50)->nullable();
                $table->text('notes')->nullable();
                $table->string('status', 30)->default('pending'); // pending, approved, rejected
                $table->text('admin_remarks')->nullable();
                $table->timestamp('applied_at')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
