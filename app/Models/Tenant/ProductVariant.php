<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProductVariant extends Model
{
    use SoftDeletes;

    protected $connection = 'tenant';

    protected $fillable = [
        'purchasable_type',
        'purchasable_id',
        'title',
        'sku',
        'price',
        'compare_at_price',
        'cost_price',
        'fulfillment_type',
        'is_downloadable',
        'is_streamable',
        'digital_file_path',
        'preview_file_path',
        'stock_quantity',
        'stock_status',
        'weight_kg',
        'attributes',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'is_downloadable' => 'boolean',
        'is_streamable' => 'boolean',
        'is_active' => 'boolean',
        'stock_quantity' => 'integer',
        'attributes' => 'array',
    ];

    /**
     * Morphic relationship to purchasable parent model (Book, Course, etc.).
     */
    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Calculate discount percentage if compare_at_price exists.
     */
    public function getDiscountPercentageAttribute(): int
    {
        if ($this->compare_at_price && $this->compare_at_price > $this->price) {
            return round((($this->compare_at_price - $this->price) / $this->compare_at_price) * 100);
        }

        return 0;
    }
}
