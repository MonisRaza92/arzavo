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
        Schema::create('user_entitlements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->string('entitable_type');
            $table->unsignedBigInteger('entitable_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->boolean('can_download')->default(false);
            $table->boolean('can_stream_online')->default(true);
            $table->integer('download_limit')->nullable();
            $table->integer('downloads_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'entitable_type', 'entitable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_entitlements');
    }
};
