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
        Schema::table('fee_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('fee_plan_id')->nullable()->change();
            $table->string('month_year', 20)->nullable()->change();
            $table->date('due_date')->nullable()->change();
            $table->decimal('amount', 10, 2)->default(0)->change();
            $table->decimal('final_amount', 10, 2)->default(0)->change();
            $table->decimal('amount_paid', 10, 2)->default(0)->change();
            $table->string('payment_method', 50)->nullable()->change();
            $table->string('payment_type', 50)->default('manual')->change();
            $table->string('status', 30)->default('pending')->change();
            
            if (!Schema::hasColumn('fee_payments', 'transaction_id')) {
                $table->string('transaction_id', 100)->nullable()->after('payment_type');
            }
            if (!Schema::hasColumn('fee_payments', 'notes')) {
                $table->text('notes')->nullable()->after('transaction_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe reversible
    }
};
