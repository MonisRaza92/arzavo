<?php

namespace App\Contracts\Commerce;

use Illuminate\Support\Collection;

/**
 * Interface PurchasableContract
 * Enforced on any sellable entity (Book, Course, Physical Product, Test Series, Event, etc.)
 */
interface PurchasableContract
{
    public function getPurchasableTitle(): string;
    
    public function getVariants(): Collection;
    
    public function getDefaultVariant();

    public function averageRating(): float;

    public function reviewsCount(): int;
}
