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
        Schema::connection('tenant')->table('blogs', function (Blueprint $table) {
            $table->text('short_description')->nullable()->after('heading');
            $table->unsignedInteger('views_count')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->table('blogs', function (Blueprint $table) {
            $table->dropColumn(['short_description', 'views_count']);
        });
    }
};
