<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('usage_pricing', function (Blueprint $table) {

            // ✅ tenant specific pricing (override)
            $table->foreignId('tenant_id')
                ->nullable()
                ->after('plan_id')
                ->constrained()
                ->nullOnDelete();

            // ✅ unit (GB, student, etc)
            $table->string('unit')
                ->nullable()
                ->after('price_per_unit');
        });
    }

    public function down(): void
    {
        Schema::table('usage_pricing', function (Blueprint $table) {

            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');

            $table->dropColumn('unit');
        });
    }
};