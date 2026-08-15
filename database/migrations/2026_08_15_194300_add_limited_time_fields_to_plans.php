<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'is_limited_time')) {
                $table->boolean('is_limited_time')->default(false)->after('is_coming_soon');
            }
            if (!Schema::hasColumn('plans', 'limited_time_ends_at')) {
                $table->dateTime('limited_time_ends_at')->nullable()->after('is_limited_time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['is_limited_time', 'limited_time_ends_at']);
        });
    }
};
