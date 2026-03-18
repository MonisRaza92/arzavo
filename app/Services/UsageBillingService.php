<?php

namespace App\Services;

use App\Models\Arzavo\Usage;
use App\Models\Arzavo\UsagePricing;

class UsageBillingService
{
    /**
     * Get current usage
     */
    public function getUsage($tenant, $key): int
    {
        return Usage::getUsage($tenant->id, $key);
    }

    /**
     * Get plan limit
     */
    public function getLimit($tenant, $key): ?int
    {
        return $tenant->plan->limit($key);
    }

    /**
     * Get over usage (above free limit)
     */
    public function getOverUsage($tenant, $key): int
    {
        $usage = $this->getUsage($tenant, $key);
        $limit = $this->getLimit($tenant, $key);

        if (is_null($limit)) {
            return 0; // unlimited plan
        }

        return max(0, $usage - $limit);
    }

    /**
     * Get pricing (tenant override > plan)
     */
    public function getPricePerUnit($tenant, $key): float
    {
        $pricing = UsagePricing::getPrice(
            $tenant->id,
            $tenant->plan_id,
            $key
        );

        return $pricing->price_per_unit ?? 0;
    }

    /**
     * Calculate overage cost for a key
     */
    public function calculateCost($tenant, $key): float
    {
        $overUsage = $this->getOverUsage($tenant, $key);

        if ($overUsage <= 0) {
            return 0;
        }

        $price = $this->getPricePerUnit($tenant, $key);

        return $overUsage * $price;
    }

    /**
     * Get full breakdown (VERY IMPORTANT for UI & invoices)
     */
    public function getBreakdown($tenant, $key): array
    {
        $usage = $this->getUsage($tenant, $key);
        $limit = $this->getLimit($tenant, $key);
        $over = $this->getOverUsage($tenant, $key);
        $price = $this->getPricePerUnit($tenant, $key);
        $cost = $over * $price;

        return [
            'key' => $key,
            'usage' => $usage,
            'limit' => $limit,
            'over_usage' => $over,
            'price_per_unit' => $price,
            'cost' => $cost,
        ];
    }

    /**
     * 🔥 Calculate total usage cost for all keys
     */
    public function calculateTotal($tenant, array $keys): float
    {
        $total = 0;

        foreach ($keys as $key) {
            $total += $this->calculateCost($tenant, $key);
        }

        return $total;
    }
}