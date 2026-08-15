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
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'has_used_trial')) {
                $table->boolean('has_used_trial')->default(false)->after('status');
                $table->timestamp('trial_used_at')->nullable()->after('has_used_trial');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (Schema::hasColumn('tenants', 'has_used_trial')) {
                $table->dropColumn(['has_used_trial', 'trial_used_at']);
            }
        });
    }
};
