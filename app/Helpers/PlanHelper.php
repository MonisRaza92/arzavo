<?php

if (!function_exists('getLimit')) {
    function getLimit($tenant, $key)
    {
        $subscription = $tenant->subscription;

        if (!$subscription)
            return null;

        // 🔹 override check
        $override = $subscription->overrides()
            ->where('key', $key)
            ->first();

        if ($override) {
            return (int) $override->value;
        }

        // 🔹 fallback to plan
        return $subscription->plan->limits[$key] ?? null;
    }
}