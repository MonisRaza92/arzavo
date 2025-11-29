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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // Basic, Pro, Enterprise
            $table->string('slug')->unique();               // basic, pro, enterprise
            $table->decimal('monthly_price', 10, 2);        // 0 = free
            $table->decimal('yearly_price', 10, 2);         // yearly billing option
            $table->boolean('is_active')->default(true);    // plan enabled?
            $table->boolean('is_popular')->default(false);  // highlight plan
            $table->string('short_description')->nullable(); // tagline under plan
            $table->text('description')->nullable();        // long description
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
