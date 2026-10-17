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
        if (Schema::connection('tenant')->hasTable('batches') && !Schema::connection('tenant')->hasColumn('batches', 'order')) {
            Schema::connection('tenant')->table('batches', function (Blueprint $table) {
                $table->unsignedInteger('order')->default(0)->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection('tenant')->hasTable('batches') && Schema::connection('tenant')->hasColumn('batches', 'order')) {
            Schema::connection('tenant')->table('batches', function (Blueprint $table) {
                $table->dropColumn('order');
            });
        }
    }
};
