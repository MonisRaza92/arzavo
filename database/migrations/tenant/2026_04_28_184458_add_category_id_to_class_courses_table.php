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
        Schema::table('class_courses', function (Blueprint $table) {
            $table->unsignedBigInteger('academic_category_id')->nullable()->after('id');
            // Assuming academic_categories table exists, no strict foreign key constraint yet to avoid issues if migrating existing DB without categories, or we can add it.
            // Let's add the constraint.
            $table->foreign('academic_category_id')->references('id')->on('academic_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_courses', function (Blueprint $table) {
            $table->dropForeign(['academic_category_id']);
            $table->dropColumn('academic_category_id');
        });
    }
};
