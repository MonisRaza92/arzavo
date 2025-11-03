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
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('fee_plan_id')->constrained('fee_plans')->onDelete('cascade');
            $table->string('month_year', 7); // Format: MM-YYYY
            $table->date('due_date');
            $table->decimal('amount', 10, 2);   // Original amount from fee plan
            $table->decimal('arrears', 10, 2)->default(0); // Any previous unpaid amount
            $table->decimal('discount', 10, 2)->default(0); // Any discount applied
            $table->decimal('fine', 10, 2)->default(0); // Any fine applied
            $table->decimal('final_amount', 10, 2); // Calculated total amount (amount + arrears - discount + fine)
            $table->decimal('amount_paid', 10, 2);
            $table->date('payment_date')->nullable();
            $table->enum('payment_method',['cash','online'])->nullable();
            $table->enum('payment_type',['autopay','manual']);
            $table->enum('status',['pending', 'partial','paid','overdue'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};
