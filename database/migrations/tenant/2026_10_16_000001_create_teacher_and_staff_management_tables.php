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
        // 1. Add teacher/staff metadata to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'designation')) {
                $table->string('designation', 150)->nullable()->after('headline');
            }
            if (!Schema::hasColumn('users', 'qualification')) {
                $table->string('qualification', 150)->nullable()->after('designation');
            }
            if (!Schema::hasColumn('users', 'joining_date')) {
                $table->date('joining_date')->nullable()->after('qualification');
            }
            if (!Schema::hasColumn('users', 'base_salary')) {
                $table->decimal('base_salary', 10, 2)->default(0.00)->after('joining_date');
            }
            if (!Schema::hasColumn('users', 'salary_type')) {
                $table->string('salary_type', 50)->default('monthly')->after('base_salary'); // monthly, per_session, hourly
            }
        });

        // 2. Create staff_attendances table for teachers and staff presence tracking
        if (!Schema::hasTable('staff_attendances')) {
            Schema::create('staff_attendances', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->date('date');
                $table->string('status', 30)->default('present'); // present, absent, late, half_day, leave
                $table->string('remarks', 255)->nullable();
                $table->unsignedBigInteger('marked_by')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'date']);
                $table->index(['date', 'status']);
            });
        }

        // 3. Create staff_payrolls table for monthly salary and payslip disbursements
        if (!Schema::hasTable('staff_payrolls')) {
            Schema::create('staff_payrolls', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedTinyInteger('month');
                $table->unsignedSmallInteger('year');
                $table->decimal('base_salary', 10, 2)->default(0.00);
                $table->unsignedSmallInteger('total_days')->default(0);
                $table->decimal('present_days', 5, 1)->default(0.0);
                $table->decimal('deductions', 10, 2)->default(0.00);
                $table->decimal('bonuses', 10, 2)->default(0.00);
                $table->decimal('net_salary', 10, 2)->default(0.00);
                $table->string('status', 30)->default('pending'); // pending, paid
                $table->timestamp('paid_at')->nullable();
                $table->string('payment_method', 50)->nullable(); // cash, bank_transfer, upi, cheque
                $table->string('transaction_reference', 100)->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('processed_by')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'month', 'year']);
                $table->index(['year', 'month', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_payrolls');
        Schema::dropIfExists('staff_attendances');

        Schema::table('users', function (Blueprint $table) {
            $cols = ['designation', 'qualification', 'joining_date', 'base_salary', 'salary_type'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
