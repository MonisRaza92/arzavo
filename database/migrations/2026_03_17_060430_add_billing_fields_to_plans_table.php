<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {

            // Trial system
            $table->integer('trial_days')->default(0)->after('yearly_price');

            // Features (custom_domain, analytics, etc)
            $table->json('features')->nullable()->after('trial_days');

            // Limits (students, storage, etc)
            $table->json('limits')->nullable()->after('features');

        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['trial_days', 'features', 'limits']);
        });
    }
};
