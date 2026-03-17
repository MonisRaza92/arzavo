<?php

namespace App\Services;

use App\Models\Arzavo\Invoice;
use App\Models\Arzavo\InvoiceItem;
use App\Models\Arzavo\Usage;
use App\Models\Arzavo\UsagePricing;

class BillingService
{
    public function generateInvoice($tenant)
    {
        $subscription = $tenant->subscription;
        $plan = $subscription->plan;

        // 🔥 STEP 1: check existing invoice (IMPORTANT)
        $existingInvoice = Invoice::where('tenant_id', $tenant->id)
            ->where('status', 'pending')
            ->whereBetween('billing_period_start', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])
            ->first();

        if ($existingInvoice) {
            return $existingInvoice; // ❌ duplicate mat banao
        }

        $total = 0;

        // 🔹 Create invoice
        $invoice = Invoice::create([
            'tenant_id' => $tenant->id,
            'total_amount' => 0,
            'status' => 'pending',
            'billing_period_start' => now()->startOfMonth(),
            'billing_period_end' => now()->endOfMonth(),
        ]);

        // 🔥 1. Plan price
        $planPrice = $subscription->custom_price ?? $plan->monthly_price;

        if ($planPrice > 0) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'type' => 'plan',
                'description' => $plan->name . ' Plan',
                'amount' => $planPrice,
            ]);

            $total += $planPrice;
        }

        // 🔥 2. Usage billing (FIXED aggregation)
        $usages = Usage::where('tenant_id', $tenant->id)
            ->selectRaw('key, SUM(used_value) as used_value')
            ->groupBy('key')
            ->get();

        foreach ($usages as $usage) {

            $limit = $plan->limits[$usage->key] ?? 0;

            if ($usage->used_value > $limit) {

                $extra = $usage->used_value - $limit;

                $price = UsagePricing::where('key', $usage->key)
                    ->where(function ($q) use ($plan) {
                        $q->whereNull('plan_id')
                            ->orWhere('plan_id', $plan->id);
                    })
                    ->value('price_per_unit') ?? 0;

                $amount = $extra * $price;

                if ($amount > 0) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'type' => 'usage',
                        'description' => $usage->key . ' extra usage',
                        'amount' => $amount,
                    ]);

                    $total += $amount;
                }
            }
        }

        // 🔥 3. Addons
        foreach ($subscription->addons ?? [] as $addon) {

            if ($addon->pricing_type === 'monthly') {

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'type' => 'addon',
                    'description' => $addon->name,
                    'amount' => $addon->price,
                ]);

                $total += $addon->price;
            }
        }

        // 🔹 Update total
        $invoice->update([
            'total_amount' => $total
        ]);

        return $invoice;
    }
}