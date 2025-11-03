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
        Schema::create('fee_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');

            $table->enum('plan_type', ['monthly', 'quarterly', 'yearly', 'onetime']);
            $table->decimal('amount', 10, 2);   // Base fee amount
            $table->date('start_date');
            $table->integer('due_day')->nullable(); // e.g. har month ki 5 tareekh
            $table->date('end_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_plans');
    }
};
