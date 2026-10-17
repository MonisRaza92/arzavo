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
        if (Schema::hasTable('staff_payrolls')) {
            Schema::table('staff_payrolls', function (Blueprint $table) {
                if (!Schema::hasColumn('staff_payrolls', 'reimbursements')) {
                    $table->decimal('reimbursements', 10, 2)->default(0.00)->after('bonuses');
                }
                if (!Schema::hasColumn('staff_payrolls', 'breakdown')) {
                    $table->json('breakdown')->nullable()->after('reimbursements');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('staff_payrolls')) {
            Schema::table('staff_payrolls', function (Blueprint $table) {
                if (Schema::hasColumn('staff_payrolls', 'breakdown')) {
                    $table->dropColumn('breakdown');
                }
                if (Schema::hasColumn('staff_payrolls', 'reimbursements')) {
                    $table->dropColumn('reimbursements');
                }
            });
        }
    }
};
