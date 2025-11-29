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
        Schema::create('tenant_subscription', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();

            // Trial
            $table->boolean('is_trial')->default(false);
            $table->timestamp('trial_ends_at')->nullable();

            //Billing
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            // Status
            $table->enum('status', ['trial','active','expired','canceled'])->default('trial');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_subscription');
    }
};
