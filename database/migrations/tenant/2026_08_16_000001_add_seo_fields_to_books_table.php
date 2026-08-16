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
        if (Schema::connection('tenant')->hasTable('books')) {
            Schema::connection('tenant')->table('books', function (Blueprint $table) {
                if (!Schema::connection('tenant')->hasColumn('books', 'meta_title')) {
                    $table->string('meta_title')->nullable()->after('description');
                }
                if (!Schema::connection('tenant')->hasColumn('books', 'meta_description')) {
                    $table->text('meta_description')->nullable()->after('meta_title');
                }
                if (!Schema::connection('tenant')->hasColumn('books', 'meta_keywords')) {
                    $table->text('meta_keywords')->nullable()->after('meta_description');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection('tenant')->hasTable('books')) {
            Schema::connection('tenant')->table('books', function (Blueprint $table) {
                $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords']);
            });
        }
    }
};
