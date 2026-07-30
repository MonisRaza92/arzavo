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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->string('purchasable_type');
            $table->unsignedBigInteger('purchasable_id');
            $table->string('title');
            $table->string('sku')->nullable()->index();
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('compare_at_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->enum('fulfillment_type', ['digital_download', 'online_access', 'physical_shipping', 'service'])->default('digital_download');
            $table->boolean('is_downloadable')->default(false);
            $table->boolean('is_streamable')->default(true);
            $table->string('digital_file_path')->nullable();
            $table->string('preview_file_path')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'backorder'])->default('in_stock');
            $table->decimal('weight_kg', 8, 3)->nullable();
            $table->json('attributes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['purchasable_type', 'purchasable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
