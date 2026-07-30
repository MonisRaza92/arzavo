<?php

namespace App\Traits;

use App\Models\Tenant\ProductVariant;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

trait HasVariants
{
    /**
     * Morphic relationship to product variants.
     */
    public function variants(): MorphMany
    {
        return $this->morphMany(ProductVariant::class, 'purchasable');
    }

    /**
     * Get active variants collection.
     */
    public function getVariants(): Collection
    {
        return $this->variants()->where('is_active', true)->get();
    }

    /**
     * Get default variant or first active variant.
     */
    public function getDefaultVariant()
    {
        return $this->variants()->where('is_active', true)->first();
    }

    /**
     * Helper to retrieve effective current price.
     */
    public function getEffectivePriceAttribute(): float
    {
        $variant = $this->getDefaultVariant();
        if (!$variant) {
            return (float) ($this->sale_price ?? $this->price ?? 0);
        }

        return (float) ($variant->compare_at_price && $variant->price < $variant->compare_at_price 
            ? $variant->price 
            : ($variant->price ?? 0));
    }
}
