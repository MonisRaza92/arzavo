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
        if (!Schema::hasTable('staff_salary_structures')) {
            Schema::create('staff_salary_structures', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->unique();
                $table->decimal('base_salary', 10, 2)->default(0.00);
                $table->string('salary_type', 50)->default('monthly'); // monthly, per_session, hourly
                $table->json('fixed_deductions')->nullable(); // multiple recurring monthly deductions (PF, ESI, loan, etc.)
                $table->json('fixed_bonuses')->nullable(); // multiple recurring monthly bonuses / allowances
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_salary_structures');
    }
};
