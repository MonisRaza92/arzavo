<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $fillable = [
        'name',
        'type',
        'pricing_type',
        'price',
    ];

    // 🔹 Relations
    public function subscriptions()
    {
        return $this->belongsToMany(
            \App\Models\Arzavo\Subscription::class,
            'subscription_addons'
        )->withTimestamps();
    }
}