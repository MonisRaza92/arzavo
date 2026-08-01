<?php

namespace App\Traits;

use App\Models\Tenant\Review;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasReviews
{
    /**
     * Morphic relationship to reviews.
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Approved reviews relation.
     */
    public function approvedReviews(): MorphMany
    {
        return $this->reviews()->where('status', 'approved');
    }

    /**
     * Calculate average rating score.
     */
    public function averageRating(): float
    {
        $avg = $this->approvedReviews()->avg('rating');
        return round($avg ? (float) $avg : 0.0, 1);
    }

    /**
     * Get approved reviews count.
     */
    public function reviewsCount(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Calculate rating breakdown percentages (5 star, 4 star, etc.)
     */
    public function ratingBreakdown(): array
    {
        $total = $this->reviewsCount();
        if ($total === 0) {
            return [
                5 => ['count' => 0, 'percentage' => 0],
                4 => ['count' => 0, 'percentage' => 0],
                3 => ['count' => 0, 'percentage' => 0],
                2 => ['count' => 0, 'percentage' => 0],
                1 => ['count' => 0, 'percentage' => 0],
            ];
        }

        $counts = $this->approvedReviews()
            ->selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        $breakdown = [];
        for ($star = 5; $star >= 1; $star--) {
            $count = $counts[$star] ?? 0;
            $breakdown[$star] = [
                'count' => $count,
                'percentage' => round(($count / $total) * 100),
            ];
        }

        return $breakdown;
    }
}
