<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'is_coming_soon')) {
                $table->boolean('is_coming_soon')->default(false)->after('is_popular');
            }
            if (!Schema::hasColumn('plans', 'is_hidden')) {
                $table->boolean('is_hidden')->default(false)->after('is_coming_soon');
            }
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'delete_on_expiry')) {
                $table->boolean('delete_on_expiry')->default(false)->after('custom_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['is_coming_soon', 'is_hidden']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['delete_on_expiry']);
        });
    }
};
