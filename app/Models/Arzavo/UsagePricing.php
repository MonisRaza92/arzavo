<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;

class UsagePricing extends Model
{
    protected $table = 'usage_pricing';

    protected $fillable = [
        'key',
        'price_per_unit',
        'plan_id',
    ];

    // 🔹 Relations
    public function plan()
    {
        return $this->belongsTo(\App\Models\Arzavo\Plan::class);
    }
}