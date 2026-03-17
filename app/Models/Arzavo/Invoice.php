<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'tenant_id',
        'total_amount',
        'status',
        'billing_period_start',
        'billing_period_end',
    ];

    protected $casts = [
        'billing_period_start' => 'datetime',
        'billing_period_end' => 'datetime',
    ];

    // 🔹 Relations
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Arzavo\Tenant::class);
    }

    public function items()
    {
        return $this->hasMany(\App\Models\Arzavo\InvoiceItem::class);
    }
}