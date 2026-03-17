<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;

class SubscriptionOverride extends Model
{
    protected $fillable = [
        'subscription_id',
        'key',
        'value',
    ];

    // 🔹 Relation: belongs to subscription
    public function subscription()
    {
        return $this->belongsTo(\App\Models\Arzavo\Subscription::class);
    }

    // 🔹 Optional: auto-cast numeric values
    public function getValueAttribute($value)
    {
        return is_numeric($value) ? (int) $value : $value;
    }
}