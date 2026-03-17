<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;

class SubscriptionAddon extends Model
{
    protected $fillable = [
        'subscription_id',
        'addon_id',
        'status',
    ];

    public function subscription()
    {
        return $this->belongsTo(\App\Models\Arzavo\Subscription::class);
    }

    public function addon()
    {
        return $this->belongsTo(\App\Models\Arzavo\Addon::class);
    }
}